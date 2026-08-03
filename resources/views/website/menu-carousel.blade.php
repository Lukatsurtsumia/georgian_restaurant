@php
    $cats = $menuCategories ?? collect();

    $imageFor = function ($cat) {
        $name = \Illuminate\Support\Str::lower($cat->name_en ?? '');
        return match (true) {
            str_contains($name, 'khachapuri') => 'photos/dish-truffle-bread.jpg',
            str_contains($name, 'khinkali') => 'gallery/khinkali.jpg',
            str_contains($name, 'grill') => 'gallery/mtsvadi.jpg',
            str_contains($name, 'cold'), str_contains($name, 'starter') => 'photos/starter-pkhali.jpg',
            str_contains($name, 'wine'), str_contains($name, 'drink') => 'photos/wine-table.jpg',
            str_contains($name, 'dessert') => 'photos/dish-dessert.jpg',
            default => 'photos/interior.jpg',
        };
    };

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
               class="group relative block aspect-[3/4] w-[230px] shrink-0 overflow-hidden rounded-xl shadow-soft transition-shadow duration-500 hover:shadow-lift sm:w-[256px] lg:w-[288px]">
                <img src="{{ asset('images/'.$imageFor($cat)) }}" alt="{{ $cat->name() }}"
                     class="absolute inset-0 h-full w-full object-cover transition-transform duration-[1200ms] ease-out group-hover:scale-[1.12]" loading="lazy">
                <div class="absolute inset-0 bg-gradient-to-t from-wine-950/95 via-wine-950/25 to-transparent"></div>

                {{-- gold frame on hover --}}
                <span class="pointer-events-none absolute inset-3.5 rounded-lg border border-gold-300/0 transition-all duration-500 group-hover:border-gold-300/70"></span>

                {{-- content --}}
                <div class="absolute inset-x-0 bottom-0 p-6">
                    <h3 class="font-display text-xl font-semibold uppercase leading-tight tracking-[0.1em] text-cream-50">{{ $cat->name() }}</h3>
                    <div class="mt-3 flex items-center gap-2.5">
                        <span class="h-px w-8 bg-gold-300 transition-all duration-500 group-hover:w-12"></span>
                        <span class="translate-x-1 whitespace-nowrap text-[11px] font-semibold uppercase tracking-luxe text-gold-300 opacity-0 transition-all duration-500 group-hover:translate-x-0 group-hover:opacity-100">{{ __('site.menu.see_more') }} &rarr;</span>
                    </div>
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
