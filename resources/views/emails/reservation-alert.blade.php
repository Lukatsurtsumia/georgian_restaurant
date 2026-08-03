@component('mail::message')
# {{ __('site.mail.alert_heading') }}

{{ __('site.mail.alert_intro', ['name' => $reservation->name]) }}

@component('mail::table')
| | |
| :--- | :--- |
| **{{ __('site.reservation.name') }}** | {{ $reservation->name }} |
| **{{ __('site.reservation.date') }}** | {{ $reservation->date->translatedFormat('d F Y') }} |
| **{{ __('site.reservation.time') }}** | {{ \Carbon\Carbon::parse($reservation->time)->format('H:i') }} |
| **{{ __('site.reservation.guests') }}** | {{ $reservation->guests }} |
@if ($reservation->table)
| **{{ __('site.reservation.table_step_label') }}** | {{ $reservation->table->label }} · {{ __('site.reservation.zone_'.$reservation->table->zone) }} |
@endif
| **{{ __('site.reservation.email') }}** | {{ $reservation->email }} |
| **{{ __('site.reservation.phone') }}** | {{ $reservation->phone ?: '—' }} |
@if ($reservation->notes)
| **{{ __('site.reservation.notes') }}** | {{ $reservation->notes }} |
@endif
@endcomponent

@component('mail::button', ['url' => route('admin.reservations')])
{{ __('site.mail.alert_manage') }}
@endcomponent

{{ config('restaurant.name') }}
@endcomponent
