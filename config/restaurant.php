<?php

/*
|--------------------------------------------------------------------------
| Pain et Pinot — restaurant details
|--------------------------------------------------------------------------
|
| Views read these instead of hardcoding, so the details can be updated
| in one place. Real venue: Georgian & European restaurant / wine bar,
| 3 Rue Chauvain, 06000 Nice.
|
| ⚠️ HOURS NEED OWNER CONFIRMATION: sourced from the Instagram opening-hours
| post + Google (closes 23:30, closed Tue & Wed). Adjust `closed_days`,
| `service_hours` and the `hours` display rows together if they change.
|
*/

return [
    'name' => 'Pain et Pinot',
    'tagline_en' => 'Georgian Table & Wine Bar',
    'tagline_fr' => 'Table Géorgienne & Bar à Vin',

    'admin_email' => env('ADMIN_EMAIL', 'admin@example.com'),
    'admin_password' => env('ADMIN_PASSWORD', 'password'),

    'contact' => [
        'phone' => '07 49 01 04 24',
        'phone_href' => '+33749010424',
        'email' => 'contact@painetpinot.fr', // placeholder — confirm real inbox
    ],

    'address' => [
        'street' => '3 Rue Chauvain',
        'postcode' => '06000',
        'city' => 'Nice',
        'country' => 'France',
        'lat' => 43.69766,
        'lng' => 7.27207,
    ],

    // Social proof surfaced on the homepage (Google rating at time of build).
    'reviews' => [
        'rating' => '4.8',
        'count' => 143,
        'source' => 'Google',
    ],

    'social' => [
        'instagram' => 'https://www.instagram.com/painetpinot_/',
        'facebook' => null,
        'thefork' => 'https://www.thefork.fr/',
    ],

    // Reservations are only accepted within this window.
    'reservation' => [
        'min_hours_ahead' => 2,
        'max_days_ahead' => 60,
        'max_guests' => 12,

        // Evening service (last table 22:00, kitchen closes 23:30).
        'service_hours' => [
            'dinner' => ['17:30', '22:00'],
        ],

        // Carbon dayOfWeek values the room is closed (0 = Sun … 6 = Sat).
        // Tuesday (2) & Wednesday (3).
        'closed_days' => [2, 3],

        // Total seats the dining room can hold per time slot. Confirmed + pending
        // reservations both count against this so the room never gets oversold.
        'seats_per_slot' => 40,

        // A slot is flagged "low" (and shows a seats-left badge) at/below this.
        'low_availability_threshold' => 6,
    ],

    'hours' => [
        ['days_en' => 'Monday', 'days_fr' => 'Lundi', 'hours' => '17:00 – 23:30'],
        ['days_en' => 'Tuesday – Wednesday', 'days_fr' => 'Mardi – Mercredi', 'hours' => 'Closed / Fermé'],
        ['days_en' => 'Thursday – Sunday', 'days_fr' => 'Jeudi – Dimanche', 'hours' => '17:00 – 23:30'],
    ],
];
