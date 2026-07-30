<?php

namespace App\Http\Controllers;

use App\Models\Reservation;

class ManageReservationController extends Controller
{
    public function show(string $token)
    {
        $reservation = Reservation::where('manage_token', $token)->first();

        return view('page.manage-reservation', compact('reservation', 'token'));
    }

    public function cancel(string $token)
    {
        $reservation = Reservation::where('manage_token', $token)->firstOrFail();

        if ($reservation->status !== Reservation::STATUS_CANCELLED) {
            $reservation->update(['status' => Reservation::STATUS_CANCELLED]);
        }

        return redirect()->route('reservations.manage', $token);
    }
}
