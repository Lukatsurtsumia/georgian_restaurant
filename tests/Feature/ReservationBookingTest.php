<?php

namespace Tests\Feature;

use App\Livewire\ReservationForm;
use App\Mail\ReservationAlert;
use App\Mail\ReservationReceived;
use App\Models\Reservation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;
use Tests\TestCase;

class ReservationBookingTest extends TestCase
{
    use RefreshDatabase;

    private function closedDays(): array
    {
        return array_map('intval', (array) config('restaurant.reservation.closed_days'));
    }

    private function nextOpenDate(): string
    {
        $date = now()->addDays(3);

        while (in_array($date->dayOfWeek, $this->closedDays(), true)) {
            $date->addDay();
        }

        return $date->toDateString();
    }

    public function test_guest_can_complete_a_two_step_booking(): void
    {
        Mail::fake();

        $date = $this->nextOpenDate();

        Livewire::test(ReservationForm::class)
            ->set('date', $date)
            ->set('guests', 4)
            ->set('time', '19:30')
            ->call('goToTables')
            ->assertSet('step', 2)
            ->call('goToDetails')
            ->assertSet('step', 3)
            ->set('name', 'Nino Kapanadze')
            ->set('email', 'nino@example.com')
            ->set('phone', '+33611112222')
            ->call('save')
            ->assertHasNoErrors()
            ->assertSet('submitted', true);

        $this->assertDatabaseHas('reservations', [
            'email' => 'nino@example.com',
            'date' => $date,
            'guests' => 4,
            'status' => Reservation::STATUS_PENDING,
        ]);

        $reservation = Reservation::where('email', 'nino@example.com')->firstOrFail();
        $this->assertNotEmpty($reservation->manage_token);

        Mail::assertQueued(ReservationReceived::class, fn ($mail) => $mail->reservation->is($reservation));
        Mail::assertQueued(ReservationAlert::class, fn ($mail) => $mail->reservation->is($reservation));
    }

    public function test_it_rejects_a_booking_on_the_closed_day(): void
    {
        $closedDate = now()->addWeek();
        while (! in_array($closedDate->dayOfWeek, $this->closedDays(), true)) {
            $closedDate->addDay();
        }

        Livewire::test(ReservationForm::class)
            ->set('date', $closedDate->toDateString())
            ->set('guests', 2)
            ->set('time', '19:00')
            ->call('goToTables')
            ->assertHasErrors('date');
    }

    public function test_it_rejects_a_booking_that_exceeds_remaining_capacity(): void
    {
        $date = $this->nextOpenDate();
        $capacity = config('restaurant.reservation.seats_per_slot');

        Reservation::create([
            'name' => 'Existing Party',
            'email' => 'existing@example.com',
            'phone' => '000',
            'date' => $date,
            'time' => '20:00',
            'guests' => $capacity - 2,
            'status' => Reservation::STATUS_CONFIRMED,
        ]);

        Livewire::test(ReservationForm::class)
            ->set('date', $date)
            ->set('guests', 5) // only 2 seats remain
            ->set('time', '20:00')
            ->call('goToTables')
            ->assertHasErrors('time');
    }

    public function test_it_accepts_a_booking_that_exactly_fills_remaining_capacity(): void
    {
        Mail::fake();

        $date = $this->nextOpenDate();
        $capacity = config('restaurant.reservation.seats_per_slot');

        Reservation::create([
            'name' => 'Existing Party',
            'email' => 'existing2@example.com',
            'phone' => '000',
            'date' => $date,
            'time' => '20:30',
            'guests' => $capacity - 3,
            'status' => Reservation::STATUS_CONFIRMED,
        ]);

        Livewire::test(ReservationForm::class)
            ->set('date', $date)
            ->set('guests', 3)
            ->set('time', '20:30')
            ->call('goToTables')
            ->call('goToDetails')
            ->set('name', 'Exact Fit')
            ->set('email', 'exact@example.com')
            ->set('phone', '000')
            ->call('save')
            ->assertHasNoErrors()
            ->assertSet('submitted', true);

        $this->assertSame(0, Reservation::remainingSeats($date, '20:30'));
    }

    public function test_cancelled_reservations_free_up_their_seats(): void
    {
        $date = $this->nextOpenDate();
        $capacity = config('restaurant.reservation.seats_per_slot');

        $reservation = Reservation::create([
            'name' => 'Will Cancel',
            'email' => 'cancel@example.com',
            'phone' => '000',
            'date' => $date,
            'time' => '19:00',
            'guests' => $capacity,
            'status' => Reservation::STATUS_CONFIRMED,
        ]);

        $this->assertSame(0, Reservation::remainingSeats($date, '19:00'));

        $reservation->update(['status' => Reservation::STATUS_CANCELLED]);

        $this->assertSame($capacity, Reservation::remainingSeats($date, '19:00'));
    }

    public function test_create_with_capacity_check_refuses_to_oversell_a_full_slot(): void
    {
        $date = $this->nextOpenDate();
        $capacity = config('restaurant.reservation.seats_per_slot');

        Reservation::create([
            'name' => 'Filler',
            'email' => 'filler@example.com',
            'phone' => '000',
            'date' => $date,
            'time' => '19:00',
            'guests' => $capacity,
            'status' => Reservation::STATUS_CONFIRMED,
        ]);

        $result = Reservation::createWithCapacityCheck([
            'name' => 'Too Many',
            'email' => 'toomany@example.com',
            'phone' => '000',
            'date' => $date,
            'time' => '19:00',
            'guests' => 1,
        ]);

        $this->assertNull($result, 'Booking beyond capacity must be refused, not silently created.');
        $this->assertDatabaseMissing('reservations', ['email' => 'toomany@example.com']);
    }

    public function test_the_google_button_is_hidden_when_google_is_not_configured(): void
    {
        config(['services.google.client_id' => null]);

        Livewire::test(ReservationForm::class)
            ->assertDontSee(__('site.reservation.continue_with_google'));
    }

    public function test_the_google_button_appears_when_google_is_configured(): void
    {
        config(['services.google.client_id' => 'fake-client-id']);

        Livewire::test(ReservationForm::class)
            ->assertSee(__('site.reservation.continue_with_google'));
    }

    public function test_continue_with_google_stores_the_pending_booking_and_redirects_to_oauth(): void
    {
        config(['services.google.client_id' => 'fake-client-id']);

        $date = $this->nextOpenDate();

        Livewire::test(ReservationForm::class)
            ->set('date', $date)
            ->set('guests', 3)
            ->set('time', '19:00')
            ->call('continueWithGoogle')
            ->assertRedirect(route('google.redirect'));

        $this->assertSame([
            'date' => $date,
            'time' => '19:00',
            'guests' => 3,
        ], session('pending_booking'));
    }

    public function test_continue_with_google_still_enforces_capacity_before_redirecting(): void
    {
        config(['services.google.client_id' => 'fake-client-id']);

        $date = $this->nextOpenDate();
        $capacity = config('restaurant.reservation.seats_per_slot');

        Reservation::create([
            'name' => 'Filler', 'email' => 'filler@example.com', 'phone' => '000',
            'date' => $date, 'time' => '19:00', 'guests' => $capacity,
            'status' => Reservation::STATUS_CONFIRMED,
        ]);

        Livewire::test(ReservationForm::class)
            ->set('date', $date)
            ->set('guests', 1)
            ->set('time', '19:00')
            ->call('continueWithGoogle')
            ->assertHasErrors('time');

        $this->assertNull(session('pending_booking'));
    }
}
