<?php

namespace Tests\Feature;

use App\Mail\ReservationReceived;
use App\Models\Reservation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;
use Tests\TestCase;

class GoogleBookingTest extends TestCase
{
    use RefreshDatabase;

    private function fakeGoogleUser(string $name = 'Nino Google', string $email = 'nino.google@example.com'): SocialiteUser
    {
        return (new SocialiteUser)->map(['id' => '123456789', 'name' => $name, 'email' => $email]);
    }

    public function test_the_redirect_route_404s_when_google_is_not_configured(): void
    {
        config(['services.google.client_id' => null]);

        $this->get(route('google.redirect'))->assertNotFound();
    }

    public function test_the_redirect_route_sends_the_browser_to_google_when_configured(): void
    {
        config([
            'services.google.client_id' => 'fake-client-id',
            'services.google.client_secret' => 'fake-secret',
            'services.google.redirect' => 'http://localhost/auth/google/callback',
        ]);

        $response = $this->get(route('google.redirect'));

        $response->assertRedirect();
        $this->assertStringContainsString('accounts.google.com', $response->headers->get('Location'));
    }

    public function test_the_callback_completes_a_booking_from_the_pending_session_data(): void
    {
        Mail::fake();
        config(['services.google.client_id' => 'fake-client-id']);
        Socialite::shouldReceive('driver->user')->once()->andReturn($this->fakeGoogleUser());

        $date = now()->addDays(3)->toDateString();
        $this->withSession(['pending_booking' => ['date' => $date, 'time' => '19:00', 'guests' => 2]]);

        $response = $this->get(route('google.callback'));

        $reservation = Reservation::where('email', 'nino.google@example.com')->first();
        $this->assertNotNull($reservation);
        $this->assertSame('google', $reservation->source);
        $this->assertNull($reservation->phone);
        $this->assertSame(2, $reservation->guests);

        $response->assertRedirect(route('reservations.manage', $reservation->manage_token));
        Mail::assertQueued(ReservationReceived::class);
    }

    public function test_the_callback_redirects_home_with_an_error_when_the_booking_session_expired(): void
    {
        config(['services.google.client_id' => 'fake-client-id']);
        Socialite::shouldReceive('driver->user')->once()->andReturn($this->fakeGoogleUser());

        // No pending_booking in session — e.g. user hit the callback URL directly.
        $response = $this->get(route('google.callback'));

        $response->assertRedirect(route('home'));
        $response->assertSessionHas('booking_error');
        $this->assertDatabaseCount('reservations', 0);
    }

    public function test_the_callback_refuses_to_oversell_a_slot_that_filled_up_during_oauth(): void
    {
        Mail::fake();
        config(['services.google.client_id' => 'fake-client-id']);
        Socialite::shouldReceive('driver->user')->once()->andReturn($this->fakeGoogleUser());

        $date = now()->addDays(3)->toDateString();
        $capacity = config('restaurant.reservation.seats_per_slot');

        Reservation::create([
            'name' => 'Filler', 'email' => 'filler@example.com', 'phone' => '000',
            'date' => $date, 'time' => '19:00', 'guests' => $capacity,
            'status' => Reservation::STATUS_CONFIRMED,
        ]);

        $this->withSession(['pending_booking' => ['date' => $date, 'time' => '19:00', 'guests' => 1]]);

        $response = $this->get(route('google.callback'));

        $response->assertRedirect(route('home'));
        $response->assertSessionHas('booking_error');
        $this->assertDatabaseMissing('reservations', ['email' => 'nino.google@example.com']);
        Mail::assertNothingQueued();
    }
}
