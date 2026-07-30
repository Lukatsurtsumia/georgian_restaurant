<?php

namespace Tests\Feature;

use App\Models\Reservation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReservationManageTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_reservation_can_be_viewed_via_its_manage_token(): void
    {
        $reservation = Reservation::create([
            'name' => 'Levan Test',
            'email' => 'levan@example.com',
            'phone' => '000',
            'date' => now()->addDays(3)->toDateString(),
            'time' => '19:00',
            'guests' => 2,
        ]);

        $response = $this->get(route('reservations.manage', $reservation->manage_token));

        $response->assertOk();
        $response->assertSee('Levan Test');
    }

    public function test_an_invalid_token_shows_a_not_found_state_instead_of_erroring(): void
    {
        $response = $this->get(route('reservations.manage', 'not-a-real-token'));

        $response->assertOk();
        $response->assertDontSee('Levan Test');
    }

    public function test_a_guest_can_cancel_their_own_reservation_and_free_the_seats(): void
    {
        $date = now()->addDays(3)->toDateString();
        $capacity = config('restaurant.reservation.seats_per_slot');

        $reservation = Reservation::create([
            'name' => 'Levan Test',
            'email' => 'levan@example.com',
            'phone' => '000',
            'date' => $date,
            'time' => '19:00',
            'guests' => $capacity,
            'status' => Reservation::STATUS_CONFIRMED,
        ]);

        $this->assertSame(0, Reservation::remainingSeats($date, '19:00'));

        $response = $this->post(route('reservations.cancel', $reservation->manage_token));

        $response->assertRedirect(route('reservations.manage', $reservation->manage_token));
        $this->assertSame(Reservation::STATUS_CANCELLED, $reservation->fresh()->status);
        $this->assertSame($capacity, Reservation::remainingSeats($date, '19:00'));
    }

    public function test_cancelling_an_already_cancelled_reservation_is_a_no_op(): void
    {
        $reservation = Reservation::create([
            'name' => 'Levan Test',
            'email' => 'levan@example.com',
            'phone' => '000',
            'date' => now()->addDays(3)->toDateString(),
            'time' => '19:00',
            'guests' => 2,
            'status' => Reservation::STATUS_CANCELLED,
        ]);

        $this->post(route('reservations.cancel', $reservation->manage_token))
            ->assertRedirect(route('reservations.manage', $reservation->manage_token));

        $this->assertSame(Reservation::STATUS_CANCELLED, $reservation->fresh()->status);
    }

    public function test_cancelling_an_unknown_token_returns_404(): void
    {
        $this->post(route('reservations.cancel', 'not-a-real-token'))
            ->assertNotFound();
    }
}
