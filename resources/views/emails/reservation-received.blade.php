@component('mail::message')
# {{ config('restaurant.name') }}

{{ __('site.mail.reservation_greeting', ['name' => $reservation->name]) }}

{{ __('site.mail.reservation_body', [
    'guests' => $reservation->guests,
    'date' => $reservation->date->translatedFormat('d F Y'),
    'time' => \Carbon\Carbon::parse($reservation->time)->format('H:i'),
]) }}

@component('mail::table')
| | |
| :--- | :--- |
| **{{ __('site.reservation.date') }}** | {{ $reservation->date->translatedFormat('d F Y') }} |
| **{{ __('site.reservation.time') }}** | {{ \Carbon\Carbon::parse($reservation->time)->format('H:i') }} |
| **{{ __('site.reservation.guests') }}** | {{ $reservation->guests }} |
@if ($reservation->notes)
| **{{ __('site.reservation.notes') }}** | {{ $reservation->notes }} |
@endif
@endcomponent

@component('mail::button', ['url' => route('reservations.manage', $reservation->manage_token)])
{{ __('site.reservation.manage_link_label') }}
@endcomponent

{{ __('site.mail.reservation_footer') }}

{{ config('restaurant.name') }}<br>
{{ config('restaurant.address.street') }}, {{ config('restaurant.address.city') }}<br>
{{ config('restaurant.contact.phone') }}
@endcomponent
