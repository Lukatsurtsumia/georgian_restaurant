<section class="bg-cream-100 py-24 lg:py-32">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <div class="mx-auto max-w-2xl text-center">
            <p class="section-eyebrow reveal justify-center"><span class="rule-gold"></span>{{ __('site.kitchen_drink.eyebrow') }}<span class="rule-gold rotate-180"></span></p>
            <h2 class="section-title reveal mt-4">{{ __('site.kitchen_drink.title') }}</h2>
        </div>

        <div class="mt-14 grid gap-8 lg:grid-cols-2">
            {{-- The Kitchen — photo card --}}
            <a href="{{ route('menu.index') }}" class="reveal group relative flex min-h-[24rem] items-end overflow-hidden rounded-[2rem] shadow-soft lg:min-h-[30rem]">
                <img src="{{ asset('images/gallery/mtsvadi.jpg') }}" alt="{{ __('site.kitchen_drink.kitchen_title') }}"
                     class="absolute inset-0 h-full w-full object-cover transition duration-700 group-hover:scale-105" loading="lazy">
                <div class="absolute inset-0 bg-gradient-to-t from-wine-950/90 via-wine-950/40 to-transparent"></div>
                <div class="relative p-8 lg:p-10">
                    <h3 class="font-display text-3xl font-semibold text-cream-50 sm:text-4xl">{{ __('site.kitchen_drink.kitchen_title') }}</h3>
                    <p class="mt-3 max-w-md text-cream-50/85">{{ __('site.kitchen_drink.kitchen_body') }}</p>
                    <span class="mt-6 inline-flex items-center gap-2 text-sm font-semibold text-gold-300 transition group-hover:gap-3">
                        {{ __('site.kitchen_drink.kitchen_cta') }}
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                    </span>
                </div>
            </a>

            {{-- The Cellar — wine-coloured card with a poured-glass motif --}}
            <a href="{{ route('menu.index') }}" class="reveal group relative flex min-h-[24rem] items-end overflow-hidden rounded-[2rem] bg-gradient-to-br from-wine-700 via-wine-900 to-wine-950 shadow-soft lg:min-h-[30rem]">
                <div class="absolute inset-0 bg-paper-radial opacity-60"></div>
                {{-- decorative wine glass --}}
                <svg class="absolute -right-6 top-6 h-64 w-64 text-gold-300/15 transition duration-700 group-hover:scale-105 lg:h-80 lg:w-80" viewBox="0 0 100 100" fill="none" aria-hidden="true">
                    <path d="M30 18h40c0 18-8 29-18 31v25" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M34 22h32c-1 12-6 20-16 22-10-2-15-10-16-22Z" fill="currentColor" fill-opacity="0.5"/>
                    <path d="M40 74h24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
                    <circle cx="76" cy="30" r="3.5" fill="currentColor" fill-opacity="0.6"/>
                    <circle cx="82" cy="38" r="2.5" fill="currentColor" fill-opacity="0.5"/>
                    <circle cx="72" cy="40" r="2.5" fill="currentColor" fill-opacity="0.4"/>
                </svg>
                <div class="relative p-8 lg:p-10">
                    <h3 class="font-display text-3xl font-semibold text-cream-50 sm:text-4xl">{{ __('site.kitchen_drink.cellar_title') }}</h3>
                    <p class="mt-3 max-w-md text-cream-50/85">{{ __('site.kitchen_drink.cellar_body') }}</p>
                    <span class="mt-6 inline-flex items-center gap-2 text-sm font-semibold text-gold-300 transition group-hover:gap-3">
                        {{ __('site.kitchen_drink.cellar_cta') }}
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                    </span>
                </div>
            </a>
        </div>
    </div>
</section>
