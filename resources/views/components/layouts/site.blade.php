@props(['title' => null, 'description' => null, 'heroDark' => false])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? __('site.brand.name') . ' — ' . __('site.brand.tagline') }}</title>
        <meta name="description" content="{{ $description ?? __('site.hero.lead') }}">

        {{-- Favicon --}}
        <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
        <link rel="apple-touch-icon" href="{{ asset('favicon.svg') }}">

        {{-- Open Graph / social share --}}
        <meta property="og:type" content="restaurant">
        <meta property="og:title" content="{{ $title ?? __('site.brand.name') . ' — ' . __('site.brand.tagline') }}">
        <meta property="og:description" content="{{ $description ?? __('site.hero.lead') }}">
        <meta property="og:image" content="{{ asset('images/photos/terrace.jpg') }}">
        <meta name="twitter:card" content="summary_large_image">
        <meta name="theme-color" content="#611f26">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700|playfair-display:500,600,700|great-vibes:400&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles

        {{-- Gate the loader before first paint: skip it on repeat visits and for
             reduced-motion users; otherwise lock scroll until it finishes. --}}
        <script>
            try {
                var pp = window.sessionStorage.getItem('pp_seen');
                var rm = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
                document.documentElement.classList.add((pp || rm) ? 'no-loader' : 'is-loading');
            } catch (e) {}
        </script>
    </head>
    <body class="min-h-screen bg-cream-100 font-sans text-ink-800 antialiased">
        <x-site-loader />
        <x-wine-progress />
        <x-wine-cursor />

        @if (session('booking_error'))
            <div class="fixed inset-x-0 top-0 z-50 bg-wine-700 px-4 py-3 text-center text-sm font-medium text-cream-50 shadow-md">
                {{ session('booking_error') }}
            </div>
        @endif

        <x-site-nav :over-dark="$heroDark" />

        <main>
            {{ $slot }}
        </main>

        <x-site-footer />

        @livewireScripts
    </body>
</html>
