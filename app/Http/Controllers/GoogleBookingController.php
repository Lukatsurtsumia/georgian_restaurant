<?php

namespace App\Http\Controllers;

use App\Mail\ReservationReceived;
use App\Models\Reservation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Laravel\Socialite\Facades\Socialite;

/**
 * "Continue with Google" for the booking form — not a user account/login
 * feature. It exists purely so a guest can skip retyping their name/email by
 * confirming identity via Google, then completes their booking immediately.
 * There is no Google sign-in for the admin dashboard; that's a separate,
 * unrelated auth system (see EnsureUserIsAdmin).
 */
class GoogleBookingController extends Controller
{
    public function redirect(): RedirectResponse
    {
        abort_unless(filled(config('services.google.client_id')), 404);

        return Socialite::driver('google')->redirect();
    }

    public function callback(): RedirectResponse
    {
        abort_unless(filled(config('services.google.client_id')), 404);

        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Throwable $e) {
            return redirect()->route('home')->with('booking_error', __('site.reservation.google_error'));
        }

        $pending = session()->pull('pending_booking');

        if (! $pending) {
            return redirect()->route('home')->with('booking_error', __('site.reservation.google_session_expired'));
        }

        $reservation = Reservation::createWithCapacityCheck([
            'name' => $googleUser->getName() ?: ($googleUser->getNickname() ?: $googleUser->getEmail()),
            'email' => $googleUser->getEmail(),
            'phone' => null,
            'date' => $pending['date'],
            'time' => $pending['time'],
            'guests' => $pending['guests'],
            'source' => 'google',
        ]);

        if (! $reservation) {
            return redirect()->route('home')->with('booking_error', __('site.reservation.slot_full'));
        }

        Mail::to($reservation->email)->locale(app()->getLocale())->send(new ReservationReceived($reservation));

        return redirect()->route('reservations.manage', $reservation->manage_token)->with('just_booked', true);
    }
}
