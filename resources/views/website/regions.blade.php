@php
    // Pin positions as % of the map's viewBox (estimated from the real layout).
    $pins = [
        'samegrelo' => ['left' => 33.5, 'top' => 25.6],
        'imereti'   => ['left' => 40.3, 'top' => 58.8],
        'adjara'    => ['left' => 26.7, 'top' => 73.2],
        'kartli'    => ['left' => 53.1, 'top' => 56.9],
        'kakheti'   => ['left' => 78.0, 'top' => 77.1],
    ];
    $data = [
        'adjara'    => ['name' => 'Adjara',    'dish' => __('site.regions.adjara_dish'),    'blurb' => __('site.regions.adjara_blurb'),    'img' => asset('images/photos/dish-truffle-bread.jpg')],
        'imereti'   => ['name' => 'Imereti',   'dish' => __('site.regions.imereti_dish'),   'blurb' => __('site.regions.imereti_blurb'),   'img' => asset('images/gallery/khachapuri.jpg')],
        'samegrelo' => ['name' => 'Samegrelo', 'dish' => __('site.regions.samegrelo_dish'), 'blurb' => __('site.regions.samegrelo_blurb'), 'img' => asset('images/gallery/khachapuri-khinkali.jpg')],
        'kartli'    => ['name' => 'Kartli',    'dish' => __('site.regions.kartli_dish'),    'blurb' => __('site.regions.kartli_blurb'),    'img' => asset('images/gallery/mtsvadi.jpg')],
        'kakheti'   => ['name' => 'Kakheti',   'dish' => __('site.regions.kakheti_dish'),   'blurb' => __('site.regions.kakheti_blurb'),   'img' => asset('images/photos/wine-table.jpg')],
    ];
@endphp

<section class="bg-cream-50 py-24 lg:py-32" x-data="{ active: 'adjara', d: @js($data) }">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <div class="mx-auto max-w-2xl text-center">
            <p class="section-eyebrow reveal justify-center">{{ __('site.regions.eyebrow') }}</p>
            <h2 class="section-title reveal mt-3">{{ __('site.regions.title') }}</h2>
            <x-georgian-divider class="reveal mt-6" />
            <p class="reveal mt-5 text-lg text-ink-700">{{ __('site.regions.lead') }}</p>
        </div>

        <div class="mt-14 grid items-center gap-10 lg:grid-cols-2 lg:gap-12">
            {{-- Real map of Georgia + interactive pins --}}
            <div class="reveal relative">
                <img src="{{ asset('images/georgia.svg') }}" alt="{{ __('site.regions.title') }}"
                     class="w-full select-none" draggable="false"
                     style="filter: drop-shadow(0 10px 24px rgba(97,31,38,0.12));">

                @foreach ($pins as $id => $pos)
                    <button type="button"
                            @mouseenter="active = '{{ $id }}'" @click="active = '{{ $id }}'"
                            aria-label="{{ $data[$id]['name'] }}"
                            class="group absolute z-10 -translate-x-1/2 -translate-y-1/2"
                            style="left: {{ $pos['left'] }}%; top: {{ $pos['top'] }}%;">
                        <span x-show="active === '{{ $id }}'" class="absolute left-1/2 top-1/2 h-11 w-11 -translate-x-1/2 -translate-y-1/2 animate-ping rounded-full bg-wine-700/25"></span>
                        <span class="relative block rounded-full border-[3px] border-cream-50 shadow-lift transition-all duration-300"
                              :class="active === '{{ $id }}' ? 'h-7 w-7 bg-wine-700' : 'h-5 w-5 bg-gold-400 group-hover:bg-wine-500'"></span>
                        <span class="pointer-events-none absolute left-1/2 top-full mt-2 hidden -translate-x-1/2 whitespace-nowrap rounded-full px-3 py-1 text-xs font-bold shadow-sm transition sm:block sm:text-sm"
                              :class="active === '{{ $id }}' ? 'bg-wine-700 text-cream-50 !block' : 'bg-cream-50/95 text-wine-800'">{{ $data[$id]['name'] }}</span>
                    </button>
                @endforeach
            </div>

            {{-- Detail card --}}
            <div class="reveal" style="--reveal-delay: 120ms">
                <article class="overflow-hidden rounded-3xl border border-cream-300/70 bg-cream-100 shadow-soft">
                    <div class="relative aspect-[4/3] overflow-hidden">
                        <template x-for="(r, id) in d" :key="id">
                            <img x-show="active === id" x-transition:enter="transition-opacity duration-500" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                 :src="r.img" :alt="r.dish" class="absolute inset-0 h-full w-full object-cover">
                        </template>
                        <div class="absolute inset-0 bg-gradient-to-t from-wine-950/70 to-transparent"></div>
                        <div class="absolute bottom-4 left-5">
                            <p class="text-xs font-semibold uppercase tracking-luxe text-gold-300" x-text="d[active].name"></p>
                            <h3 class="mt-1 font-display text-2xl font-semibold text-cream-50" x-text="d[active].dish"></h3>
                        </div>
                    </div>
                    <p class="p-6 text-ink-700" x-text="d[active].blurb"></p>
                </article>
            </div>
        </div>
    </div>
</section>
