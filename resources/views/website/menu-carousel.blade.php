@php
    $cats = $menuCategories ?? collect();

    // Repeat the set enough times that the strip always overflows the screen and
    // can loop forever with no gaps — even for a small menu (5 items or fewer).
    $setSize = max(1, $cats->count());
    $sets = (int) max(3, ceil(16 / $setSize));
    $loopCats = collect();
    for ($s = 0; $s < $sets; $s++) {
        $loopCats = $loopCats->concat($cats);
    }
@endphp

@if ($cats->isNotEmpty())
<section
    x-data="{
        raf: null,
        speed: 0.45,
        paused: false,
        setSize: {{ $cats->count() }},
        period() {
            const el = $refs.track;
            const cards = el.querySelectorAll('[data-card]');
            return (cards.length > this.setSize) ? (cards[this.setSize].offsetLeft - cards[0].offsetLeft) : el.scrollWidth;
        },
        loop() {
            const el = $refs.track;
            if (el && ! this.paused) {
                el.scrollLeft += this.speed;
                const p = this.period();
                if (p > 0 && el.scrollLeft >= p) el.scrollLeft -= p;
            }
            this.raf = requestAnimationFrame(() => this.loop());
        },
        start() {
            if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
            cancelAnimationFrame(this.raf);
            this.loop();
        },
        nudge(dir) {
            const el = $refs.track;
            const card = el.querySelector('[data-card]');
            const w = card ? card.offsetWidth + 16 : 220;
            const p = this.period();
            if (dir < 0 && el.scrollLeft < w * 2 && p > 0) el.scrollLeft += p;
            el.scrollBy({ left: dir * w * 2, behavior: 'smooth' });
        },
    }"
    x-init="start()"
    id="menu"
    class="overflow-hidden bg-cream-100 py-24 lg:py-28">

    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <div class="reveal">
            <p class="section-eyebrow"><span class="rule-gold"></span>{{ __('site.menu.eyebrow') }}</p>
            <h2 class="section-title mt-4">{{ __('site.menu.explore_title') }}</h2>
        </div>
    </div>

    {{-- Continuous, never-stopping loop. Edges fade into the cream. --}}
    <div x-ref="track" class="no-scrollbar mt-14 flex gap-5 overflow-x-auto px-6 lg:px-8"
         @touchstart.passive="paused = true" @touchend="paused = false" @touchcancel="paused = false">
        @foreach ($loopCats as $i => $cat)
            <a href="{{ route('menu.index') }}" data-card
               @if ($i >= $cats->count()) aria-hidden="true" tabindex="-1" @endif
               class="group relative flex aspect-[3/4] w-[230px] shrink-0 flex-col items-center justify-center overflow-hidden rounded-xl bg-gradient-to-br from-wine-800 via-wine-900 to-wine-950 shadow-soft transition-shadow duration-500 hover:shadow-lift sm:w-[256px] lg:w-[288px]">
                {{-- warm paper texture + a large faint logo watermark --}}
                <div class="absolute inset-0 bg-paper-radial opacity-30"></div>
                <div class="pointer-events-none absolute inset-0 flex items-center justify-center opacity-[0.07]">
                    <x-brand-mark tone="cream" class="h-56 w-56" />
                </div>

                {{-- gold frame (brightens on hover) --}}
                <span class="pointer-events-none absolute inset-3.5 rounded-lg border border-gold-300/20 transition-all duration-500 group-hover:border-gold-300/70"></span>

                {{-- classic logo + category name only --}}
                <div class="relative flex flex-col items-center px-5 text-center">
                    <x-brand-mark tone="cream" class="h-11 w-11 transition-transform duration-500 group-hover:-translate-y-1" />
                    <h3 class="mt-5 font-display text-xl font-semibold uppercase leading-snug tracking-[0.15em] text-cream-50 sm:text-2xl">{{ $cat->name() }}</h3>
                    <span class="mt-4 h-px w-8 bg-gold-300/70 transition-all duration-500 group-hover:w-16"></span>
                </div>
            </a>
        @endforeach
    </div>

    {{-- close the section with an ornament, separating it from what follows --}}
    <div class="mx-auto mt-16 max-w-7xl px-6 lg:px-8">
        <x-georgian-divider />
    </div>
</section>
@endif
