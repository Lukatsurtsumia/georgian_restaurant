<x-layouts.site :title="__('site.nav.menu').' — '.__('site.brand.name')">
    <div x-data="{ filter: 'all' }">
        {{-- Cream hero (keeps the dark-text nav readable) --}}
        <section class="relative isolate overflow-hidden bg-cream-100 bg-paper-radial px-6 pb-12 pt-32 text-center sm:pt-36">
            <p class="section-eyebrow justify-center"><span class="rule-gold"></span>{{ __('site.menu.eyebrow') }}<span class="rule-gold rotate-180"></span></p>
            <h1 class="mt-4 font-display text-5xl font-semibold text-ink-900 sm:text-6xl">{{ __('site.menu.title') }}</h1>
            <p class="mx-auto mt-4 max-w-xl text-lg text-ink-700">{{ __('site.menu.lead') }}</p>
        </section>

        {{-- Sticky filter bar --}}
        <div class="sticky top-[68px] z-30 border-y border-cream-300/70 bg-cream-50/90 backdrop-blur">
            <div class="mx-auto flex max-w-5xl items-center gap-2 overflow-x-auto px-6 py-3 lg:px-8">
                @foreach (['all' => __('site.menu.filter_all'), 'signature' => __('site.menu.filter_signature'), 'veg' => __('site.menu.filter_vegetarian')] as $key => $label)
                    <button type="button" @click="filter = '{{ $key }}'"
                            :class="filter === '{{ $key }}' ? 'bg-wine-700 text-cream-50 shadow-sm' : 'bg-transparent text-ink-700 hover:bg-wine-700/5'"
                            class="whitespace-nowrap rounded-full px-4 py-1.5 text-sm font-semibold tracking-wide transition">
                        {{ $label }}
                    </button>
                @endforeach
            </div>
        </div>

        <section class="bg-cream-100 py-16 lg:py-24">
            <div class="mx-auto max-w-6xl px-6 lg:px-8">
                @forelse ($categories as $category)
                    @php
                        $catHasSignature = $category->items->contains(fn ($i) => $i->is_featured);
                        $catHasVeg = $category->items->contains(fn ($i) => $i->is_vegetarian);
                    @endphp
                    @if ($category->items->isNotEmpty())
                        <div class="mb-16"
                             x-show="filter === 'all' || (filter === 'signature' && {{ $catHasSignature ? 'true' : 'false' }}) || (filter === 'veg' && {{ $catHasVeg ? 'true' : 'false' }})"
                             x-transition.opacity>
                            <div class="flex items-end justify-between gap-4 border-b border-cream-300 pb-4">
                                <h2 class="font-display text-2xl font-semibold text-wine-800 sm:text-3xl">{{ $category->name() }}</h2>
                                <span class="text-xs font-medium uppercase tracking-[0.15em] text-ink-700/40">{{ $category->items->count() }}</span>
                            </div>

                            <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                                @foreach ($category->items as $item)
                                    <article data-tilt="5" class="reveal group flex flex-col overflow-hidden rounded-2xl border border-cream-300/70 bg-cream-50 shadow-soft transition duration-300 hover:shadow-lift"
                                             style="--reveal-delay: {{ ($loop->index % 3) * 80 }}ms"
                                             x-show="filter === 'all' || (filter === 'signature' && {{ $item->is_featured ? 'true' : 'false' }}) || (filter === 'veg' && {{ $item->is_vegetarian ? 'true' : 'false' }})"
                                             x-transition.opacity>
                                        <div class="relative">
                                            <x-dish-photo :item="$item" class="aspect-[3/2]" />
                                            @if ($item->is_featured)
                                                <span class="absolute left-3 top-3 inline-flex items-center gap-1 rounded-full bg-gold-400 px-2.5 py-1 text-[10px] font-semibold uppercase tracking-wide text-wine-950 shadow-sm">
                                                    <svg class="h-2.5 w-2.5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 15.27 16.18 19l-1.64-7.03L20 7.24l-7.19-.61L10 0 7.19 6.63 0 7.24l5.46 4.73L3.82 19z"/></svg>
                                                    {{ __('site.menu.featured') }}
                                                </span>
                                            @endif
                                        </div>
                                        <div class="flex flex-1 flex-col p-5">
                                            <div class="flex items-start justify-between gap-3">
                                                <h3 class="font-display text-lg font-semibold leading-tight text-ink-900">{{ $item->name() }}</h3>
                                                <span class="whitespace-nowrap font-display text-base font-semibold text-wine-700">€{{ number_format($item->price, 2) }}</span>
                                            </div>
                                            @if ($item->description())
                                                <p class="mt-2 flex-1 text-sm leading-relaxed text-ink-700/80">{{ $item->description() }}</p>
                                            @endif
                                            @if ($item->is_vegetarian || $item->is_spicy)
                                                <div class="mt-4 flex gap-2">
                                                    @if ($item->is_vegetarian)
                                                        <span class="inline-flex rounded-full bg-emerald-100 px-2.5 py-0.5 text-[11px] font-medium text-emerald-800">{{ __('site.menu.vegetarian') }}</span>
                                                    @endif
                                                    @if ($item->is_spicy)
                                                        <span class="inline-flex rounded-full bg-wine-100 px-2.5 py-0.5 text-[11px] font-medium text-wine-800">{{ __('site.menu.spicy') }}</span>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </article>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @empty
                    <p class="text-center text-ink-700/50">{{ __('site.menu.empty') }}</p>
                @endforelse

                <div class="reveal mt-8 rounded-3xl bg-wine-800 px-8 py-12 text-center text-cream-50">
                    <h3 class="font-display text-2xl font-semibold sm:text-3xl">{{ __('site.reservation.title') }}</h3>
                    <a href="{{ route('home') }}#reserve" class="btn-gold mt-6">{{ __('site.nav.reserve') }}</a>
                </div>
            </div>
        </section>
    </div>
</x-layouts.site>
