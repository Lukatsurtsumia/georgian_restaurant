@php
    $rating = config('restaurant.reviews.rating');
    $reviewCount = config('restaurant.reviews.count');
    $reviewSource = config('restaurant.reviews.source');
@endphp

<section class="bg-wine-800 py-16 text-cream-50">
    <div class="mx-auto flex max-w-6xl flex-col items-center gap-8 px-6 text-center lg:flex-row lg:justify-between lg:text-left lg:px-8">
        <div class="reveal">
            <p class="section-eyebrow justify-center text-gold-300 lg:justify-start">{{ __('site.reviews.eyebrow') }}</p>
            <h2 class="mt-3 font-display text-3xl font-semibold sm:text-4xl">{{ __('site.reviews.title') }}</h2>
        </div>

        <div class="reveal flex items-center gap-5" style="--reveal-delay: 120ms">
            <div class="text-center">
                <div class="font-display text-6xl font-bold leading-none text-gold-300">{{ $rating }}</div>
                <div class="mt-2 flex justify-center gap-0.5 text-gold-300">
                    @for ($i = 0; $i < 5; $i++)
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true"><path d="M10 15.27 16.18 19l-1.64-7.03L20 7.24l-7.19-.61L10 0 7.19 6.63 0 7.24l5.46 4.73L3.82 19z"/></svg>
                    @endfor
                </div>
            </div>
            <div class="h-16 w-px bg-cream-50/20"></div>
            <p class="max-w-[16rem] text-left text-sm leading-relaxed text-cream-50/80">
                {{ __('site.reviews.lead', ['rating' => $rating, 'count' => $reviewCount, 'source' => $reviewSource]) }}
            </p>
        </div>
    </div>
</section>
