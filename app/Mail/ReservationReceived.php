<?php

namespace App\Mail;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReservationReceived extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public Reservation $reservation) {}

    public function build(): self
    {
        return $this->subject(__('site.mail.reservation_subject', ['date' => $this->reservation->date->format('d/m/Y')]))
            ->markdown('emails.reservation-received');
    }
}
