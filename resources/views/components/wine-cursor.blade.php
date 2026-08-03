{{-- Desktop custom cursor: a precise dot at the true pointer + a wine glass
     that floats and trails behind it. Activated from app.js (desktop, no
     reduced-motion); native cursor is restored over form fields. --}}
<div data-wc-dot aria-hidden="true"></div>
<div data-wc-glass aria-hidden="true">
    <div class="wc-glass-inner">
        <svg viewBox="0 0 30 34" fill="none" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <clipPath id="wcBowl"><path d="M8 5c0 8 3 12 7 12s7-4 7-12Z"/></clipPath>
            </defs>
            <g clip-path="url(#wcBowl)">
                <rect x="6" y="10" width="18" height="9" fill="#7f2c33"/>
                <ellipse cx="15" cy="10.5" rx="7" ry="1.1" fill="#9c3d44"/>
            </g>
            <path d="M8 5c0 8 3 12 7 12s7-4 7-12" stroke="#cc8a25" stroke-width="1.4" stroke-linecap="round"/>
            <path d="M8 5h14" stroke="#cc8a25" stroke-width="1.4" stroke-linecap="round"/>
            <path d="M15 17v10" stroke="#cc8a25" stroke-width="1.4" stroke-linecap="round"/>
            <path d="M10 28h10" stroke="#cc8a25" stroke-width="1.4" stroke-linecap="round"/>
        </svg>
    </div>
</div>
