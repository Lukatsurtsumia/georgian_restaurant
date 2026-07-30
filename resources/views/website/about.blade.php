<section id="about" class="bg-cream-50 py-24 lg:py-32">
    <div class="mx-auto grid max-w-7xl items-center gap-14 px-6 lg:grid-cols-2 lg:gap-20 lg:px-8">
        <div class="reveal relative order-2 lg:order-1">
            <div class="overflow-hidden rounded-[2rem] shadow-soft">
                <img src="{{ asset('images/about-tbilisi.jpg') }}" alt="Inside Pain et Pinot"
                     class="aspect-[4/3] w-full object-cover" loading="lazy">
            </div>
            {{-- Warm quote chip --}}
            <div class="absolute -bottom-6 right-6 max-w-[15rem] rounded-2xl border border-cream-300/70 bg-cream-50 p-5 shadow-lift lg:right-auto lg:-left-8">
                <p class="font-display text-lg italic leading-snug text-wine-800">&ldquo;{{ __('site.brand.tagline') }}&rdquo;</p>
                <p class="mt-2 text-xs font-semibold uppercase tracking-[0.15em] text-gold-600">Nice · 06000</p>
            </div>
        </div>

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
        </div>
    </div>
</section>
