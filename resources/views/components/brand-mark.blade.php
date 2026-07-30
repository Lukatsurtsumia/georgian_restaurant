@props([
    'tone' => 'wine', // 'wine' on light bg, 'cream' on dark bg
])

@php
    $ink = $tone === 'cream' ? '#faf5ea' : '#611f26';
    $accent = $tone === 'cream' ? '#e9bd63' : '#cc8a25';
@endphp

{{-- Original bread + wine emblem for Pain et Pinot. A wheat sprig behind a
     glass of red — evokes "bread & pinot" without copying the venue's mascot.
     Drop a real logo at public/images/brand/logo.svg and swap this if desired. --}}
<svg {{ $attributes->merge(['class' => 'h-10 w-10', 'viewBox' => '0 0 48 48', 'fill' => 'none', 'aria-hidden' => 'true']) }}
     xmlns="http://www.w3.org/2000/svg">
    {{-- wheat sprig --}}
    <g stroke="{{ $accent }}" stroke-width="1.6" stroke-linecap="round">
        <path d="M24 7v13" />
        <path d="M24 11c2.4-.6 4-2 4.4-4.4-2.4.4-3.8 2-4.4 4.4Z" fill="{{ $accent }}" fill-opacity="0.9" stroke="none" />
        <path d="M24 11c-2.4-.6-4-2-4.4-4.4 2.4.4 3.8 2 4.4 4.4Z" fill="{{ $accent }}" fill-opacity="0.9" stroke="none" />
        <path d="M24 16c2.6-.6 4.3-2.1 4.8-4.7-2.6.4-4.1 2.1-4.8 4.7Z" fill="{{ $accent }}" fill-opacity="0.75" stroke="none" />
        <path d="M24 16c-2.6-.6-4.3-2.1-4.8-4.7 2.6.4 4.1 2.1 4.8 4.7Z" fill="{{ $accent }}" fill-opacity="0.75" stroke="none" />
    </g>
    {{-- wine glass --}}
    <path d="M16 19h16c0 6-3.2 9.5-8 10.4C19.2 28.5 16 25 16 19Z" fill="{{ $ink }}" fill-opacity="0.12" stroke="{{ $ink }}" stroke-width="1.8" stroke-linejoin="round" />
    <path d="M17.4 20.5h13.2" stroke="{{ $ink }}" stroke-width="1.4" stroke-linecap="round" opacity="0.5" />
    <path d="M24 29.4V38" stroke="{{ $ink }}" stroke-width="1.8" stroke-linecap="round" />
    <path d="M18.5 39.5h11" stroke="{{ $ink }}" stroke-width="1.8" stroke-linecap="round" />
    {{-- wine fill --}}
    <path d="M18.2 21.5h11.6c-.5 3.9-2.8 6.3-5.8 6.9-3-.6-5.3-3-5.8-6.9Z" fill="{{ $tone === 'cream' ? '#9c3d44' : '#7f2c33' }}" fill-opacity="0.85" />
</svg>
