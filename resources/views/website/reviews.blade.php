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

        <dl class="reveal mx-auto mt-12 grid max-w-2xl grid-cols-1 gap-10 sm:grid-cols-3 sm:gap-0" style="--reveal-delay: 120ms">
            {{-- Rating --}}
            <div class="flex flex-col items-center justify-center sm:border-r sm:border-cream-50/15">
                <dd class="font-display text-6xl font-bold leading-none text-gold-300">{{ $rating }}</dd>
                <div class="mt-3 flex gap-1 text-gold-300">
                    @for ($i = 0; $i < 5; $i++)
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true"><path d="M10 15.27 16.18 19l-1.64-7.03L20 7.24l-7.19-.61L10 0 7.19 6.63 0 7.24l5.46 4.73L3.82 19z"/></svg>
                    @endfor
                </div>
                <dt class="mt-3 text-xs font-medium uppercase tracking-luxe text-cream-50/55">{{ __('site.reviews.out_of') }}</dt>
            </div>

            {{-- Review count --}}
            <div class="flex flex-col items-center justify-center sm:border-r sm:border-cream-50/15">
                <dd class="font-display text-6xl font-bold leading-none text-cream-50">{{ $reviewCount }}</dd>
                <dt class="mt-3 text-xs font-medium uppercase tracking-luxe text-cream-50/55">{{ __('site.reviews.count_label') }}</dt>
            </div>

            {{-- Source --}}
            <div class="flex flex-col items-center justify-center">
                <dd class="flex items-center gap-2.5">
                    <svg class="h-9 w-9" viewBox="0 0 24 24" aria-hidden="true">
                        <path fill="#4285F4" d="M23.52 12.27c0-.85-.08-1.67-.22-2.45H12v4.64h6.47c-.28 1.5-1.13 2.78-2.4 3.63v3.02h3.88c2.27-2.09 3.57-5.17 3.57-8.84Z"/>
                        <path fill="#34A853" d="M12 24c3.24 0 5.95-1.07 7.94-2.9l-3.88-3.02c-1.08.72-2.45 1.15-4.06 1.15-3.12 0-5.77-2.11-6.71-4.94H1.28v3.11C3.26 21.3 7.31 24 12 24Z"/>
                        <path fill="#FBBC05" d="M5.29 14.29a7.2 7.2 0 0 1 0-4.58V6.6H1.28a11.98 11.98 0 0 0 0 10.8l4.01-3.11Z"/>
                        <path fill="#EA4335" d="M12 4.77c1.76 0 3.34.6 4.59 1.79l3.44-3.44C17.94 1.19 15.23 0 12 0 7.31 0 3.26 2.7 1.28 6.6l4.01 3.11C6.23 6.88 8.88 4.77 12 4.77Z"/>
                    </svg>
                    <span class="font-display text-2xl font-semibold text-cream-50">{{ $reviewSource }}</span>
                </dd>
                <dt class="mt-3 text-xs font-medium uppercase tracking-luxe text-cream-50/55">{{ __('site.reviews.verified') }}</dt>
            </div>
        </dl>
    </div>
</section>
