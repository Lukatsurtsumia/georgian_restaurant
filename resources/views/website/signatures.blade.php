@php
    $rows = ($featuredItems ?? collect())->take(3);
@endphp

@if ($rows->isNotEmpty())
<section id="signatures" class="overflow-hidden bg-cream-50 py-24 lg:py-32">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <div class="mx-auto max-w-2xl text-center">
            <p class="section-eyebrow reveal justify-center"><span class="rule-gold"></span>{{ __('site.signatures.eyebrow') }}<span class="rule-gold rotate-180"></span></p>
            <h2 class="reveal mt-4 font-display text-4xl font-semibold text-ink-900 text-balance sm:text-5xl">{{ __('site.signatures.title') }}</h2>
            <x-georgian-divider class="reveal mt-6" />
            <p class="reveal mt-6 text-lg text-ink-700">{{ __('site.signatures.lead') }}</p>
        </div>

        <div class="mt-20 space-y-24 lg:space-y-32">
            @foreach ($rows as $item)
                @php $num = str_pad($loop->iteration, 2, '0', STR_PAD_LEFT); @endphp
                <article class="grid items-center gap-10 lg:grid-cols-2 lg:gap-20">
                    {{-- Photo --}}
                    <div class="{{ $loop->even ? 'reveal-r lg:order-2' : 'reveal-l' }} relative">
                        <div data-tilt="4" class="group relative overflow-hidden rounded-[1.8rem] border-4 border-cream-100 shadow-lift">
                            <x-dish-photo :item="$item" class="aspect-[4/3]" />
                            <span class="pointer-events-none absolute inset-3 rounded-[1.2rem] border border-cream-50/40"></span>
                        </div>

                        {{-- Gold outline numeral --}}
                        <span class="pointer-events-none absolute -top-9 z-10 hidden select-none font-display text-8xl font-bold leading-none text-transparent lg:block {{ $loop->even ? '-right-4' : '-left-4' }}"
                              style="-webkit-text-stroke: 1.6px #e1a53a; opacity: 0.55;">{{ $num }}</span>

                        {{-- Borjgali wax seal --}}
                        <div class="absolute -bottom-7 z-20 flex h-24 w-24 flex-col items-center justify-center rounded-full border-4 border-cream-50 bg-wine-800 shadow-lift {{ $loop->even ? 'left-8' : 'right-8' }}">
                            <svg viewBox="0 0 40 40" class="h-7 w-7 text-gold-300" fill="currentColor" aria-hidden="true">
                                @for ($i = 0; $i < 7; $i++)
                                    <path d="M20 20c1-6 .3-11-3-14 2 4 2.4 9 3 14Z" transform="rotate({{ $i * 360 / 7 }} 20 20)"/>
                                @endfor
                                <circle cx="20" cy="20" r="2.4" fill="#faf5ea"/>
                            </svg>
                            <span class="mt-1 text-[8px] font-semibold uppercase tracking-[0.18em] text-gold-300">{{ __('site.menu.featured') }}</span>
                        </div>
                    </div>

                    {{-- Text (with a vertical rail dividing it from the photo) --}}
                    <div class="{{ $loop->even ? 'reveal-l lg:order-1' : 'reveal-r' }} flex items-stretch gap-7">
                        {{-- vertical rail --}}
                        <div class="hidden shrink-0 flex-col items-center justify-center gap-3 {{ $loop->even ? 'lg:order-2 lg:flex' : 'lg:flex' }}">
                            <span class="w-px flex-1 bg-gradient-to-b from-transparent via-gold-400/60 to-transparent"></span>
                            <span class="whitespace-nowrap text-[10px] font-semibold uppercase tracking-luxe text-gold-600" style="writing-mode: vertical-rl; transform: rotate(180deg);">{{ __('site.menu.featured') }} · {{ $num }}</span>
                            <span class="w-px flex-1 bg-gradient-to-b from-transparent via-gold-400/60 to-transparent"></span>
                        </div>

                        <div>
                            <p class="text-xs font-semibold uppercase tracking-luxe text-gold-600">{{ $item->category?->name() }}</p>
                            <h3 class="mt-3 font-display text-3xl font-semibold leading-tight text-ink-900 sm:text-4xl">{{ $item->name() }}</h3>

                            <div class="mt-5 flex items-center gap-4">
                                <span class="font-display text-3xl font-semibold text-wine-700">€{{ number_format($item->price, 2) }}</span>
                                <span class="h-px w-16 bg-gradient-to-r from-gold-400 to-transparent"></span>
                            </div>

                            <p class="mt-5 max-w-md text-lg leading-relaxed text-ink-700">{{ $item->description() }}</p>

                            <a href="{{ route('menu.index') }}" class="group mt-7 inline-flex items-center gap-2 text-sm font-semibold text-wine-700">
                                <span class="relative">
                                    {{ __('site.signatures.cta') }}
                                    <span class="absolute -bottom-0.5 left-0 h-px w-0 bg-wine-700 transition-all duration-500 group-hover:w-full"></span>
                                </span>
                                <svg class="h-4 w-4 transition-transform duration-300 group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                            </a>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>
@endif
