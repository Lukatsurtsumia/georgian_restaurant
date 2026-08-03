@props(['class' => ''])

{{-- Gold hairline flanking a borjgali — the traditional Georgian seven-ray
     sun motif. A small cultural flourish between sections. --}}
<div {{ $attributes->merge(['class' => 'flex items-center justify-center gap-5 '.$class]) }} aria-hidden="true">
    <span class="h-px w-16 bg-gradient-to-r from-transparent to-gold-400/70 sm:w-24"></span>
    <svg viewBox="0 0 40 40" class="h-8 w-8 text-gold-500" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
        @for ($i = 0; $i < 7; $i++)
            <path d="M20 20c1-6 .3-11-3-14 2 4 2.4 9 3 14Z" transform="rotate({{ $i * 360 / 7 }} 20 20)" opacity="0.9"/>
        @endfor
        <circle cx="20" cy="20" r="2.6" fill="#faf5ea"/>
        <circle cx="20" cy="20" r="2.6" fill="none" stroke="currentColor" stroke-width="1"/>
    </svg>
    <span class="h-px w-16 bg-gradient-to-l from-transparent to-gold-400/70 sm:w-24"></span>
</div>
