@props([
    'variant' => 'mark', // 'mark' = roundel only, 'full' = roundel + wordmark
])

{{-- Original recreation of the Pain et Pinot mascot: a smiling loaf raising a
     glass of red, in a cream roundel with a little rainbow. Vector, so it stays
     crisp at any size. Drop a real logo at public/images/brand/logo.png and swap
     this out later if you prefer the exact raster. --}}
<div {{ $attributes->merge(['class' => 'inline-flex flex-col items-center']) }}>
    <svg viewBox="0 0 200 200" class="h-full w-full" fill="none" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Pain et Pinot">
        <!-- roundel -->
        <circle cx="100" cy="100" r="96" fill="#f3ecdb"/>
        <circle cx="100" cy="100" r="96" fill="none" stroke="#611f26" stroke-width="6"/>
        <circle cx="100" cy="100" r="86" fill="none" stroke="#cc8a25" stroke-width="1.5" stroke-dasharray="2 5" opacity="0.7"/>

        <!-- rainbow arc (bottom) -->
        <g fill="none" stroke-linecap="round" opacity="0.9">
            <path d="M63 150a37 37 0 0 1 74 0" stroke="#7f2c33" stroke-width="5"/>
            <path d="M71 150a29 29 0 0 1 58 0" stroke="#cc8a25" stroke-width="5"/>
            <path d="M79 150a21 21 0 0 1 42 0" stroke="#3f7d6e" stroke-width="5"/>
        </g>

        <!-- bread body -->
        <path d="M58 96c0-22 19-38 42-38s42 16 42 38v34a8 8 0 0 1-8 8H66a8 8 0 0 1-8-8V96Z"
              fill="#f6e7c8" stroke="#611f26" stroke-width="5" stroke-linejoin="round"/>
        <!-- crust seam -->
        <path d="M58 104h84" stroke="#611f26" stroke-width="3" opacity="0.5"/>
        <!-- seeds -->
        <g fill="#7f2c33">
            <circle cx="80" cy="80" r="1.8"/>
            <circle cx="100" cy="74" r="1.8"/>
            <circle cx="120" cy="80" r="1.8"/>
            <circle cx="90" cy="88" r="1.6"/>
            <circle cx="110" cy="88" r="1.6"/>
        </g>

        <!-- face -->
        <g stroke="#611f26" stroke-width="4.5" stroke-linecap="round">
            <path d="M85 112h.2"/>
            <path d="M115 112h.2"/>
        </g>
        <path d="M88 122c3.5 4 8.5 4 12 4s8.5 0 12-4" stroke="#611f26" stroke-width="4" fill="none" stroke-linecap="round"/>
        <!-- cheeks -->
        <circle cx="78" cy="120" r="4.5" fill="#e0918f" opacity="0.7"/>
        <circle cx="122" cy="120" r="4.5" fill="#e0918f" opacity="0.7"/>

        <!-- arm + wine glass -->
        <path d="M138 118c10 2 15 8 16 16" stroke="#611f26" stroke-width="5" fill="none" stroke-linecap="round"/>
        <g>
            <path d="M148 128h20l-3 11a7 7 0 0 1-14 0l-3-11Z" fill="#7f2c33" stroke="#611f26" stroke-width="3.5" stroke-linejoin="round"/>
            <path d="M158 150v9" stroke="#611f26" stroke-width="3.5" stroke-linecap="round"/>
            <path d="M152 160h12" stroke="#611f26" stroke-width="3.5" stroke-linecap="round"/>
            <path d="M150.5 131h15" stroke="#9c3d44" stroke-width="2" opacity="0.7"/>
        </g>
    </svg>

    @if ($variant === 'full')
        <span class="mt-3 font-display text-lg font-bold uppercase leading-none tracking-luxe text-wine-800">Pain et Pinot</span>
    @endif
</div>
