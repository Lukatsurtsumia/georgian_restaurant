@php
    $mapsQuery = urlencode(config('restaurant.address.street').', '.config('restaurant.address.postcode').' '.config('restaurant.address.city'));
@endphp

<section id="hours" class="bg-wine-950 py-24 text-cream-50 lg:py-32">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <div class="mx-auto max-w-2xl text-center">
            <p class="section-eyebrow reveal justify-center text-gold-300"><span class="rule-gold"></span>{{ __('site.hours.eyebrow') }}<span class="rule-gold rotate-180"></span></p>
            <h2 class="reveal mt-3 font-display text-3xl font-semibold sm:text-4xl">{{ __('site.hours.title') }}</h2>
        </div>

        <div class="mt-16 grid gap-10 lg:grid-cols-2 lg:gap-16">
            <div class="reveal space-y-10">
                <div>
                    <h3 class="text-xs font-semibold uppercase tracking-[0.2em] text-gold-300">{{ __('site.hours.hours_title') }}</h3>
                    <dl class="mt-4 divide-y divide-cream-50/10 border-y border-cream-50/10">
                        @foreach (config('restaurant.hours') as $row)
                            @php $isClosed = str_contains(strtolower($row['hours']), 'clos') || str_contains(strtolower($row['hours']), 'ferm'); @endphp
                            <div class="flex items-center justify-between py-3.5 text-sm">
                                <dt class="text-cream-50/70">{{ app()->getLocale() === 'fr' ? $row['days_fr'] : $row['days_en'] }}</dt>
                                <dd @class(['font-medium', 'text-cream-50' => ! $isClosed, 'text-cream-50/40' => $isClosed])>{{ $row['hours'] }}</dd>
                            </div>
                        @endforeach
                    </dl>
                </div>

                <div class="grid gap-8 sm:grid-cols-2">
                    <div>
                        <h3 class="text-xs font-semibold uppercase tracking-[0.2em] text-gold-300">{{ __('site.hours.address_title') }}</h3>
                        <p class="mt-4 text-cream-50/80">
                            {{ config('restaurant.address.street') }}<br>
                            {{ config('restaurant.address.postcode') }} {{ config('restaurant.address.city') }}, {{ config('restaurant.address.country') }}
                        </p>
                        <a href="https://www.google.com/maps/search/?api=1&query={{ $mapsQuery }}" target="_blank" rel="noopener"
                           class="mt-3 inline-flex items-center gap-1.5 text-sm font-medium text-gold-300 hover:text-gold-200">
                            {{ __('site.hours.directions') }}
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                        </a>
                    </div>
                    <div>
                        <h3 class="text-xs font-semibold uppercase tracking-[0.2em] text-gold-300">{{ __('site.hours.contact_title') }}</h3>
                        <p class="mt-4 space-y-1 text-cream-50/80">
                            <a href="tel:{{ config('restaurant.contact.phone_href') }}" class="block hover:text-cream-50">{{ config('restaurant.contact.phone') }}</a>
                            <a href="mailto:{{ config('restaurant.contact.email') }}" class="block hover:text-cream-50">{{ config('restaurant.contact.email') }}</a>
                        </p>
                    </div>
                </div>

                <a href="#reserve" class="btn-gold">{{ __('site.hours.book_cta') }}</a>
            </div>

            <div class="reveal overflow-hidden rounded-3xl border border-cream-50/10 shadow-lift" style="--reveal-delay: 120ms">
                <iframe
                    title="{{ __('site.hours.address_title') }}"
                    class="h-full min-h-[24rem] w-full"
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"
                    src="https://maps.google.com/maps?q={{ config('restaurant.address.lat') }},{{ config('restaurant.address.lng') }}&z=16&output=embed">
                </iframe>
            </div>
        </div>
    </div>
</section>
