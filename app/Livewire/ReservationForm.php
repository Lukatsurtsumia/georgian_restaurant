<?php

namespace App\Livewire;

use App\Mail\ReservationReceived;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Computed;
use Livewire\Component;

class ReservationForm extends Component
{
    public int $step = 1;

    public string $date = '';

    public string $time = '';

    public int $guests = 2;

    public string $name = '';

    public string $email = '';

    public string $phone = '';

    public string $notes = '';

    public bool $submitted = false;

    public ?string $confirmedName = null;

    public ?string $confirmedDate = null;

    public ?string $confirmedTime = null;

    public ?string $manageUrl = null;

    public ?string $confirmedCalendarUrl = null;

    public function updatedDate(): void
    {
        $this->time = '';
        unset($this->availableSlots);
    }

    public function updatedGuests(): void
    {
        unset($this->availableSlots);
    }

    #[Computed]
    public function availableSlots(): array
    {
        if (! $this->date) {
            return [];
        }

        $slots = [];
        $threshold = config('restaurant.reservation.low_availability_threshold');

        foreach (config('restaurant.reservation.service_hours') as $period) {
            [$start, $end] = $period;
            $cursor = Carbon::createFromFormat('H:i', $start);
            $endTime = Carbon::createFromFormat('H:i', $end);

            while ($cursor->lte($endTime)) {
                $value = $cursor->format('H:i');
                $remaining = Reservation::remainingSeats($this->date, $value);

                $slots[] = [
                    'value' => $value,
                    'label' => $cursor->format('H:i'),
                    'remaining' => $remaining,
                    'full' => $remaining < $this->guests,
                    'low' => $remaining >= $this->guests && $remaining <= $threshold,
                ];

                $cursor->addMinutes(30);
            }
        }

        return $slots;
    }

    protected function dateTimeRules(): array
    {
        $maxDays = config('restaurant.reservation.max_days_ahead');
        $minHours = config('restaurant.reservation.min_hours_ahead');
        $maxGuests = config('restaurant.reservation.max_guests');

        return [
            'date' => [
                'required',
                'date',
                'after_or_equal:'.now()->addHours($minHours)->toDateString(),
                'before_or_equal:'.now()->addDays($maxDays)->toDateString(),
                function ($attribute, $value, $fail) {
                    $closedDays = array_map('intval', (array) config('restaurant.reservation.closed_days'));
                    if (in_array(Carbon::parse($value)->dayOfWeek, $closedDays, true)) {
                        $fail(__('site.reservation.closed_notice'));
                    }
                },
            ],
            'guests' => ['required', 'integer', 'min:1', 'max:'.$maxGuests],
            'time' => [
                'required',
                'in:'.collect($this->availableSlots())->pluck('value')->implode(','),
                function ($attribute, $value, $fail) {
                    if (Reservation::remainingSeats($this->date, $value) < $this->guests) {
                        $fail(__('site.reservation.slot_full'));
                    }
                },
            ],
        ];
    }

    protected function detailsRules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'notes' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function goToDetails(): void
    {
        $this->validate($this->dateTimeRules());
        $this->step = 2;
    }

    public function backToDateTime(): void
    {
        $this->step = 1;
    }

    /**
     * "Continue with Google" — skips manual name/email/phone entry by handing
     * the already-chosen date/time/guests to the session and bouncing to the
     * real OAuth redirect (GoogleBookingController). Livewire component state
     * can't survive a full off-site redirect, hence the session handoff instead
     * of just keeping this in $this->.
     */
    public function continueWithGoogle(): void
    {
        $this->validate($this->dateTimeRules());

        session(['pending_booking' => [
            'date' => $this->date,
            'time' => $this->time,
            'guests' => $this->guests,
        ]]);

        $this->redirect(route('google.redirect'));
    }

    public function save(): void
    {
        $this->validate($this->dateTimeRules() + $this->detailsRules());

        // The rule above is an optimistic check for fast UX feedback; the real
        // capacity guard is the atomic re-check inside createWithCapacityCheck(),
        // which closes the race window between two people booking the last seats
        // in the same slot at the same time.
        $reservation = Reservation::createWithCapacityCheck([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'date' => $this->date,
            'time' => $this->time,
            'guests' => $this->guests,
            'notes' => $this->notes,
        ]);

        if (! $reservation) {
            unset($this->availableSlots);
            $this->step = 1;
            $this->addError('time', __('site.reservation.slot_full'));

            return;
        }

        // ->locale() pins the language for when a queue worker renders this later,
        // since app()->getLocale() at that point reflects the worker's process,
        // not the customer's chosen locale for this request.
        Mail::to($reservation->email)->locale(app()->getLocale())->send(new ReservationReceived($reservation));

        $this->confirmedName = $reservation->name;
        $this->confirmedDate = $reservation->date->translatedFormat('d F Y');
        $this->confirmedTime = Carbon::parse($reservation->time)->format('H:i');
        $this->manageUrl = route('reservations.manage', $reservation->manage_token);
        $this->confirmedCalendarUrl = $this->buildCalendarUrl($reservation);

        $this->reset(['name', 'email', 'phone', 'date', 'time', 'notes', 'step']);
        $this->guests = 2;
        $this->submitted = true;
    }

    public function reserveAnother(): void
    {
        $this->submitted = false;
        $this->confirmedName = null;
        $this->confirmedDate = null;
        $this->confirmedTime = null;
        $this->manageUrl = null;
        $this->confirmedCalendarUrl = null;
    }

    /**
     * "Add to calendar" — a Google Calendar template link for the confirmed
     * booking (2h default duration). Purely a convenience on the success
     * screen; it doesn't touch the reservation record itself.
     */
    protected function buildCalendarUrl(Reservation $reservation): string
    {
        $start = Carbon::parse($reservation->date->toDateString().' '.$reservation->time);
        $end = $start->copy()->addHours(2);

        return 'https://calendar.google.com/calendar/render?'.http_build_query([
            'action' => 'TEMPLATE',
            'text' => __('site.brand.name').' — '.__('site.admin.guests_label', ['count' => $reservation->guests]),
            'dates' => $start->format('Ymd\THis').'/'.$end->format('Ymd\THis'),
            'location' => config('restaurant.address.street').', '.config('restaurant.address.postcode').' '.config('restaurant.address.city'),
        ]);
    }

    public function render()
    {
        return view('livewire.reservation-form', [
            'maxGuests' => config('restaurant.reservation.max_guests'),
            'minDate' => now()->addHours(config('restaurant.reservation.min_hours_ahead'))->toDateString(),
            'maxDate' => now()->addDays(config('restaurant.reservation.max_days_ahead'))->toDateString(),
            'googleEnabled' => filled(config('services.google.client_id')),
        ]);
    }
}
