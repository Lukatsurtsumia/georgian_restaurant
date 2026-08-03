<?php

namespace App\Mail;

use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Sent to the restaurant (not the guest) the moment a booking comes in, so the
 * owner is pinged without having to watch the dashboard.
 */
class ReservationAlert extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public Reservation $reservation) {}

    public function build(): self
    {
        return $this->subject(__('site.mail.alert_subject', [
            'name' => $this->reservation->name,
            'date' => $this->reservation->date->format('d/m/Y'),
            'time' => Carbon::parse($this->reservation->time)->format('H:i'),
        ]))->markdown('emails.reservation-alert');
    }
}
