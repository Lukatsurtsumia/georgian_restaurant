{{-- Mobile: slim wine-fill bar at the very top --}}
<div data-wine-bar-wrap aria-hidden="true"><div data-wine-bar></div></div>

{{-- Desktop: a fixed wine glass that fills as you scroll toward the menu --}}
<div data-wine-glass style="--wineH: 38px" aria-hidden="true">
    <svg viewBox="0 0 60 80" fill="none" xmlns="http://www.w3.org/2000/svg">
        <defs>
            <clipPath id="ppBowl"><path d="M14 13c0 20 9 29 16 29s16-9 16-29Z"/></clipPath>
        </defs>
        {{-- wine (level driven by --fill) --}}
        <g clip-path="url(#ppBowl)">
            <g class="wine-fill">
                <rect x="8" y="12" width="44" height="38" fill="#7f2c33"/>
                <ellipse class="wine-surface" cx="30" cy="13" rx="16" ry="2.4" fill="#9c3d44"/>
            </g>
        </g>
        {{-- glass --}}
        <path d="M12 12c0 22 10 32 18 32s18-10 18-32" stroke="#611f26" stroke-width="1.8" stroke-linecap="round"/>
        <path d="M12 12h36" stroke="#611f26" stroke-width="1.8" stroke-linecap="round"/>
        <path d="M30 44v22" stroke="#611f26" stroke-width="1.8" stroke-linecap="round"/>
        <path d="M20 67h20" stroke="#611f26" stroke-width="1.8" stroke-linecap="round"/>
        <path d="M18 15c0 13 4 21 9 24" stroke="#faf5ea" stroke-width="1" opacity="0.45" stroke-linecap="round"/>
    </svg>
</div>
