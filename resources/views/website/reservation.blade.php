<section id="reserve" class="relative isolate overflow-hidden bg-cream-100 bg-paper-radial py-24 lg:py-32">
    <div class="mx-auto max-w-3xl px-6 lg:px-8">
        <div class="text-center">
            <p class="section-eyebrow reveal justify-center"><span class="rule-gold"></span>{{ __('site.reservation.eyebrow') }}<span class="rule-gold rotate-180"></span></p>
            <h2 class="section-title reveal mt-4">{{ __('site.reservation.title') }}</h2>
            <p class="reveal mx-auto mt-4 max-w-xl text-lg text-ink-700">{{ __('site.reservation.lead', ['max' => config('restaurant.reservation.max_guests')]) }}</p>
        </div>

        <div class="reveal mt-12" style="--reveal-delay: 100ms">
            <livewire:reservation-form />
        </div>
    </div>
</section>
