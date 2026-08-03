@php
    $rating = config('restaurant.reviews.rating');
    $reviewCount = config('restaurant.reviews.count');
@endphp

<section id="about" class="relative overflow-hidden bg-cream-50 py-24 lg:py-32">
    <div class="mx-auto grid max-w-7xl items-center gap-14 px-6 lg:grid-cols-2 lg:gap-20 lg:px-8">
        {{-- Interior photo + mascot badge --}}
        <div class="reveal order-2 lg:order-1">
            <div class="relative mx-auto max-w-md">
                <div class="aspect-[4/5] overflow-hidden rounded-[2rem] border-4 border-cream-100 shadow-soft">
                    <img data-parallax="0.14" src="{{ asset('images/photos/interior.jpg') }}" alt="Inside Pain et Pinot"
                         class="h-full w-full object-cover" loading="lazy">
                </div>
                {{-- floating mascot badge --}}
                <div class="absolute right-2 top-2 flex h-20 w-20 animate-float-slow items-center justify-center rounded-full border-4 border-cream-50 bg-cream-100 shadow-lift sm:-right-4 sm:-top-6 sm:h-24 sm:w-24">
                    <x-brand-logo variant="mark" class="h-12 w-12 sm:h-16 sm:w-16" />
                </div>
                {{-- rating chip --}}
                <div class="absolute -bottom-6 left-1/2 flex -translate-x-1/2 items-center gap-3 rounded-full border border-cream-300/70 bg-cream-50 px-5 py-3 shadow-lift">
                    <div class="flex items-center gap-0.5 text-gold-400">
                        @for ($i = 0; $i < 5; $i++)
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10 15.27 16.18 19l-1.64-7.03L20 7.24l-7.19-.61L10 0 7.19 6.63 0 7.24l5.46 4.73L3.82 19z"/></svg>
                        @endfor
                    </div>
                    <span class="text-sm font-semibold text-ink-900">{{ $rating }}</span>
                    <span class="text-sm text-ink-700/60">· {{ __('site.hero.reviews_count', ['count' => $reviewCount]) }}</span>
                </div>
            </div>
        </div>

        {{-- Story --}}
        <div class="order-1 lg:order-2">
            <p class="section-eyebrow reveal"><span class="rule-gold"></span>{{ __('site.about.eyebrow') }}</p>
            <h2 class="section-title reveal mt-4">{{ __('site.about.title') }}</h2>
            <p class="reveal mt-6 text-lg leading-relaxed text-ink-700">{{ __('site.about.body') }}</p>

            <dl class="mt-10 grid gap-6 sm:grid-cols-3">
                @foreach ([1, 2, 3] as $i)
                    <div class="reveal" style="--reveal-delay: {{ $i * 100 }}ms">
                        <div class="flex h-11 w-11 items-center justify-center rounded-full bg-wine-700/10 text-wine-700">
                            @if ($i === 1)
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12a9 9 0 1 0 18 0 9 9 0 0 0-18 0Zm6-3 6 6m0-6-6 6"/></svg>
                            @elseif ($i === 2)
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8 3h8l-1 8a3 3 0 0 1-6 0L8 3Zm4 11v7m-3 0h6"/></svg>
                            @else
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21s-7-4.35-7-9a4 4 0 0 1 7-2.65A4 4 0 0 1 19 12c0 4.65-7 9-7 9Z"/></svg>
                            @endif
                        </div>
                        <dt class="mt-4 font-display text-base font-semibold text-wine-800">{{ __('site.about.point_'.$i.'_title') }}</dt>
                        <dd class="mt-1.5 text-sm leading-relaxed text-ink-700/80">{{ __('site.about.point_'.$i.'_body') }}</dd>
                    </div>
                @endforeach
            </dl>

            <div class="reveal mt-10 flex flex-wrap items-center gap-x-6 gap-y-3 border-t border-cream-300 pt-6 text-sm text-ink-700/80">
                <span class="inline-flex items-center gap-2">
                    <svg class="h-4 w-4 text-wine-700" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>
                    {{ config('restaurant.address.street') }}, {{ config('restaurant.address.city') }}
                </span>
                <a href="tel:{{ config('restaurant.contact.phone_href') }}" class="inline-flex items-center gap-2 font-medium text-wine-700 hover:text-wine-800">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z"/></svg>
                    {{ config('restaurant.contact.phone') }}
                </a>
            </div>
        </div>
    </div>
</section>
