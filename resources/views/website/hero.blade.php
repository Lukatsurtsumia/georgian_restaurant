@php
    $rating = config('restaurant.reviews.rating');
    $reviewCount = config('restaurant.reviews.count');
    $reviewSource = config('restaurant.reviews.source');
    $phone = config('restaurant.contact.phone');

    $clips = [
        ['file' => 'videos/wine-pour.mp4',     'poster' => 'images/photos/wine-table.jpg'],
        ['file' => 'videos/kitchen-grill.mp4', 'poster' => 'images/photos/interior.jpg'],
        ['file' => 'videos/sizzling-pan.mp4',  'poster' => 'images/photos/dish-mushrooms2.jpg'],
    ];
@endphp

<section
    data-hero
    x-data="{
        active: 0,
        count: {{ count($clips) }},
        useVideo: false,
        ready: false,
        timer: null,
        go(i) { this.active = (i + this.count) % this.count; this.restart(); },
        next() { this.go(this.active + 1); },
        prev() { this.go(this.active - 1); },
        restart() { clearInterval(this.timer); this.start(); },
        start() { this.timer = setInterval(() => { this.active = (this.active + 1) % this.count; }, 6500); },
        init() {
            const reduceMq = window.matchMedia('(prefers-reduced-motion: reduce)');
            const compute = () => {
                // Play videos on every device (mobile included) — only skip for
                // reduced-motion or an active data-saver preference.
                const saveData = navigator.connection && navigator.connection.saveData;
                this.useVideo = ! reduceMq.matches && ! saveData;
            };
            compute();
            reduceMq.addEventListener('change', compute);
            this.start();
            // Let the hero paint first (poster, headline, buttons), THEN start the
            // heavy video download — so the page opens instantly instead of waiting
            // on the clip. Only the visible slide's video ever loads.
            const kickoff = () => { this.ready = true; };
            if (document.readyState === 'complete') { setTimeout(kickoff, 350); }
            else { window.addEventListener('load', () => setTimeout(kickoff, 350), { once: true }); }
        },
    }"
    class="relative isolate flex min-h-[100svh] items-center overflow-hidden bg-wine-950">

    {{-- Media (scales slowly on scroll) --}}
    <div data-hero-media class="absolute inset-0 -z-10">
        @foreach ($clips as $i => $clip)
            <div x-show="active === {{ $i }}"
                 x-transition:enter="transition-opacity duration-1000 ease-out" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity duration-1000 ease-in" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                 class="absolute inset-0 bg-wine-950">
                {{-- Blurred still fills the frame behind the uncut video (no black bars); it's also the reduced-motion / data-saver fallback (then shown sharp & full). --}}
                <img src="{{ asset($clip['poster']) }}" alt=""
                     class="absolute inset-0 h-full w-full object-cover"
                     :class="useVideo ? 'scale-110 blur-lg brightness-[0.65]' : ''"
                     {{ $i === 0 ? 'loading=eager' : 'loading=lazy' }}>
                <video x-show="useVideo" x-cloak
                       x-effect="(useVideo && ready && active === {{ $i }}) ? $el.play().catch(() => {}) : $el.pause()"
                       class="absolute inset-0 h-full w-full scale-[1.22] object-contain"
                       muted loop playsinline preload="none" poster="{{ asset($clip['poster']) }}">
                    <source src="{{ asset($clip['file']) }}" type="video/mp4">
                </video>
            </div>
        @endforeach

        <div class="absolute inset-0 bg-gradient-to-b from-wine-950/70 via-wine-950/30 to-wine-950/85"></div>
        <div class="absolute inset-0 bg-[radial-gradient(120%_80%_at_50%_0%,transparent_40%,rgba(20,6,8,0.6)_100%)]"></div>
    </div>

    {{-- Content (fades + lifts on scroll) --}}
    <div data-hero-copy class="mx-auto w-full max-w-4xl px-6 py-28 text-center lg:px-8">
        <p class="reveal font-script text-3xl text-gold-300 sm:text-5xl">{{ __('site.hero.welcome') }}</p>

        <h1 class="reveal mt-2 font-display text-5xl font-semibold leading-[0.98] text-cream-50 text-balance sm:text-7xl lg:text-8xl"
            style="--reveal-delay: 80ms; text-shadow: 0 2px 30px rgba(20,6,8,0.5);">
            {{ __('site.brand.name') }}
        </h1>

        <p class="reveal mx-auto mt-6 max-w-xl text-base font-medium uppercase tracking-luxe text-cream-50/80 sm:text-lg" style="--reveal-delay: 160ms;">
            {{ __('site.brand.tagline') }}
        </p>
        <p class="reveal mx-auto mt-3 text-sm uppercase tracking-[0.15em] text-cream-50/60" style="--reveal-delay: 200ms;">
            {{ __('site.hero.book_line') }}
            <a href="tel:{{ config('restaurant.contact.phone_href') }}" class="font-semibold text-gold-300 hover:text-gold-200">{{ $phone }}</a>
        </p>

        <div class="reveal mt-10 flex flex-col items-center justify-center gap-4 sm:flex-row" style="--reveal-delay: 260ms;">
            <a href="#reserve" class="btn-gold text-base">
                {{ __('site.hero.cta_reserve') }}
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" /></svg>
            </a>
            <a href="{{ route('menu.index') }}" class="btn-ghost text-base">{{ __('site.hero.cta_menu') }}</a>
        </div>

        <div class="reveal mt-9 flex justify-center" style="--reveal-delay: 320ms;">
            <div class="inline-flex items-center gap-3 rounded-full border border-cream-50/15 bg-wine-950/25 px-5 py-2.5 backdrop-blur-sm">
                <div class="flex items-center gap-1 text-gold-300">
                    @for ($i = 0; $i < 5; $i++)
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true"><path d="M10 15.27 16.18 19l-1.64-7.03L20 7.24l-7.19-.61L10 0 7.19 6.63 0 7.24l5.46 4.73L3.82 19z"/></svg>
                    @endfor
                </div>
                <span class="font-display text-xl font-semibold leading-none text-cream-50">{{ $rating }}</span>
            </div>
        </div>
    </div>

    {{-- Carousel arrows --}}
    <button type="button" @click="prev()" aria-label="Previous"
            class="absolute left-3 top-1/2 z-20 hidden -translate-y-1/2 rounded-full border border-cream-50/25 bg-wine-950/20 p-3 text-cream-50/80 backdrop-blur transition hover:bg-wine-950/50 hover:text-cream-50 sm:flex">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" /></svg>
    </button>
    <button type="button" @click="next()" aria-label="Next"
            class="absolute right-3 top-1/2 z-20 hidden -translate-y-1/2 rounded-full border border-cream-50/25 bg-wine-950/20 p-3 text-cream-50/80 backdrop-blur transition hover:bg-wine-950/50 hover:text-cream-50 sm:flex">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg>
    </button>

    {{-- Dots + scroll cue --}}
    <div class="absolute inset-x-0 bottom-8 z-20 flex flex-col items-center gap-5">
        <div class="flex items-center gap-2.5">
            @foreach ($clips as $i => $clip)
                <button type="button" @click="go({{ $i }})" aria-label="Slide {{ $i + 1 }}"
                        :class="active === {{ $i }} ? 'w-7 bg-gold-300' : 'w-2 bg-cream-50/40 hover:bg-cream-50/70'"
                        class="h-2 rounded-full transition-all duration-300"></button>
            @endforeach
        </div>
        <a href="#about" class="hidden text-cream-50/50 transition hover:text-cream-50 sm:block" aria-label="{{ __('site.hero.scroll') }}">
            <svg class="h-6 w-6 animate-bounce" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25 12 15.75 4.5 8.25" /></svg>
        </a>
    </div>
</section>
