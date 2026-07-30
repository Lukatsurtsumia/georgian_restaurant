<section id="menu" class="bg-cream-100 py-24 lg:py-32">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <div class="mx-auto max-w-2xl text-center">
            <p class="section-eyebrow reveal justify-center"><span class="rule-gold"></span>{{ __('site.menu.eyebrow') }}<span class="rule-gold rotate-180"></span></p>
            <h2 class="section-title reveal mt-4">{{ __('site.menu.title') }}</h2>
            <p class="reveal mt-4 text-lg text-ink-700">{{ __('site.menu.lead') }}</p>
        </div>

        @if ($featuredItems->isNotEmpty())
            <div class="mt-16 grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($featuredItems as $item)
                    <article class="reveal group flex flex-col overflow-hidden rounded-3xl border border-cream-300/70 bg-cream-50 shadow-soft transition duration-300 hover:-translate-y-1 hover:shadow-lift"
                             style="--reveal-delay: {{ $loop->index * 90 }}ms">
                        <div class="relative">
                            <x-dish-photo :item="$item" class="aspect-[4/3]" />
                            <span class="absolute left-4 top-4 inline-flex items-center gap-1 rounded-full bg-gold-400 px-3 py-1 text-[11px] font-semibold uppercase tracking-wide text-wine-950 shadow-sm">
                                <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20"><path d="M10 15.27 16.18 19l-1.64-7.03L20 7.24l-7.19-.61L10 0 7.19 6.63 0 7.24l5.46 4.73L3.82 19z"/></svg>
                                {{ __('site.menu.featured') }}
                            </span>
                        </div>
                        <div class="flex flex-1 flex-col p-6">
                            <div class="flex items-start justify-between gap-3">
                                <h3 class="font-display text-xl font-semibold text-ink-900">{{ $item->name() }}</h3>
                                <span class="whitespace-nowrap font-display text-lg font-semibold text-wine-700">€{{ number_format($item->price, 2) }}</span>
                            </div>
                            <p class="mt-1 text-xs font-medium uppercase tracking-wide text-gold-600">{{ $item->category?->name() }}</p>
                            <p class="mt-3 flex-1 text-sm leading-relaxed text-ink-700/80">{{ $item->description() }}</p>
                            @if ($item->is_vegetarian || $item->is_spicy)
                                <div class="mt-4 flex gap-2">
                                    @if ($item->is_vegetarian)
                                        <span class="inline-flex rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-medium text-emerald-800">{{ __('site.menu.vegetarian') }}</span>
                                    @endif
                                    @if ($item->is_spicy)
                                        <span class="inline-flex rounded-full bg-wine-100 px-2.5 py-0.5 text-xs font-medium text-wine-800">{{ __('site.menu.spicy') }}</span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>
        @else
            <p class="mt-16 text-center text-ink-700/50">{{ __('site.menu.empty') }}</p>
        @endif

        <div class="reveal mt-14 text-center">
            <a href="{{ route('menu.index') }}" class="btn-primary">{{ __('site.menu.view_full') }}</a>
        </div>
    </div>
</section>
