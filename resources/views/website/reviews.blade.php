@php
    $rating = config('restaurant.reviews.rating');
    $reviewCount = config('restaurant.reviews.count');
    $reviewSource = config('restaurant.reviews.source');
@endphp

<section class="relative overflow-hidden bg-wine-800 py-16 text-cream-50 sm:py-20">
    <div class="absolute inset-0 bg-paper-radial opacity-30"></div>

    <div class="relative mx-auto max-w-4xl px-6 text-center lg:px-8">
        <p class="section-eyebrow reveal justify-center text-gold-300"><span class="rule-gold"></span>{{ __('site.reviews.eyebrow') }}<span class="rule-gold rotate-180"></span></p>
        <h2 class="reveal mt-4 font-display text-3xl font-semibold sm:text-4xl">{{ __('site.reviews.title') }}</h2>

        <div class="reveal mt-10 flex flex-col items-center" style="--reveal-delay: 120ms">
            <div class="font-display text-6xl font-bold leading-none text-gold-300">{{ $rating }}</div>
            <div class="mt-4 flex gap-1.5 text-gold-300">
                @for ($i = 0; $i < 5; $i++)
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true"><path d="M10 15.27 16.18 19l-1.64-7.03L20 7.24l-7.19-.61L10 0 7.19 6.63 0 7.24l5.46 4.73L3.82 19z"/></svg>
                @endfor
            </div>
            <p class="mt-4 text-xs font-medium uppercase tracking-luxe text-cream-50/55">{{ __('site.reviews.out_of') }}</p>
        </div>
    </div>
</section>
