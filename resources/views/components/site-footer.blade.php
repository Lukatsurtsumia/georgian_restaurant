@php
    $instagram = config('restaurant.social.instagram');
    $facebook = config('restaurant.social.facebook');
@endphp

<footer class="bg-wine-950 text-cream-50/70">
    <div class="mx-auto max-w-7xl px-6 py-16 lg:px-8">
        <div class="grid gap-10 sm:grid-cols-2 lg:grid-cols-4">
            <div class="sm:col-span-2 lg:col-span-1">
                <div class="flex items-center gap-2.5">
                    <x-brand-mark tone="cream" class="h-9 w-9" />
                    <span class="font-display text-2xl font-semibold text-cream-50">{{ __('site.brand.name') }}</span>
                </div>
                <p class="mt-4 max-w-xs text-sm leading-relaxed">{{ __('site.footer.made_with') }}</p>

                @if ($instagram || $facebook)
                    <div class="mt-6">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-gold-300">{{ __('site.footer.follow') }}</p>
                        <div class="mt-3 flex gap-3">
                            @if ($instagram)
                                <a href="{{ $instagram }}" target="_blank" rel="noopener" aria-label="Instagram"
                                   class="flex h-10 w-10 items-center justify-center rounded-full border border-cream-50/15 transition hover:border-gold-300 hover:text-gold-300">
                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2.2c3.2 0 3.6 0 4.9.07 1.2.05 1.8.25 2.2.42.55.2.95.48 1.37.9.42.42.7.82.9 1.37.17.4.37 1 .42 2.2.06 1.3.07 1.7.07 4.9s0 3.6-.07 4.9c-.05 1.2-.25 1.8-.42 2.2-.2.55-.48.95-.9 1.37-.42.42-.82.7-1.37.9-.4.17-1 .37-2.2.42-1.3.06-1.7.07-4.9.07s-3.6 0-4.9-.07c-1.2-.05-1.8-.25-2.2-.42-.55-.2-.95-.48-1.37-.9-.42-.42-.7-.82-.9-1.37-.17-.4-.37-1-.42-2.2C2.2 15.6 2.2 15.2 2.2 12s0-3.6.07-4.9c.05-1.2.25-1.8.42-2.2.2-.55.48-.95.9-1.37.42-.42.82-.7 1.37-.9.4-.17 1-.37 2.2-.42C8.4 2.2 8.8 2.2 12 2.2Zm0 1.8c-3.15 0-3.5 0-4.75.07-.9.04-1.4.2-1.72.32-.43.17-.74.37-1.06.7-.32.31-.52.62-.7 1.05-.12.33-.28.83-.32 1.72C3.2 8.5 3.2 8.85 3.2 12s0 3.5.07 4.75c.04.9.2 1.4.32 1.72.17.43.37.74.7 1.06.31.32.62.52 1.05.7.33.12.83.28 1.72.32C8.5 20.8 8.85 20.8 12 20.8s3.5 0 4.75-.07c.9-.04 1.4-.2 1.72-.32.43-.17.74-.37 1.06-.7.32-.31.52-.62.7-1.05.12-.33.28-.83.32-1.72.06-1.24.07-1.6.07-4.75s0-3.5-.07-4.75c-.04-.9-.2-1.4-.32-1.72a2.85 2.85 0 0 0-.7-1.06 2.85 2.85 0 0 0-1.05-.7c-.33-.12-.83-.28-1.72-.32C15.5 4 15.15 4 12 4Zm0 3.06A4.94 4.94 0 1 1 12 17a4.94 4.94 0 0 1 0-9.88Zm0 1.8a3.14 3.14 0 1 0 0 6.28 3.14 3.14 0 0 0 0-6.28Zm5.13-.9a1.15 1.15 0 1 1-2.3 0 1.15 1.15 0 0 1 2.3 0Z"/></svg>
                                </a>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <div>
                <h3 class="text-xs font-semibold uppercase tracking-[0.2em] text-gold-300">{{ __('site.hours.address_title') }}</h3>
                <p class="mt-4 text-sm leading-relaxed">
                    {{ config('restaurant.address.street') }}<br>
                    {{ config('restaurant.address.postcode') }} {{ config('restaurant.address.city') }}, {{ config('restaurant.address.country') }}
                </p>
            </div>

            <div>
                <h3 class="text-xs font-semibold uppercase tracking-[0.2em] text-gold-300">{{ __('site.hours.hours_title') }}</h3>
                <dl class="mt-4 space-y-1.5 text-sm">
                    @foreach (config('restaurant.hours') as $row)
                        <div class="flex justify-between gap-4">
                            <dt class="text-cream-50/60">{{ app()->getLocale() === 'fr' ? $row['days_fr'] : $row['days_en'] }}</dt>
                            <dd class="text-right text-cream-50">{{ $row['hours'] }}</dd>
                        </div>
                    @endforeach
                </dl>
            </div>

            <div>
                <h3 class="text-xs font-semibold uppercase tracking-[0.2em] text-gold-300">{{ __('site.hours.contact_title') }}</h3>
                <p class="mt-4 space-y-1 text-sm">
                    <a href="tel:{{ config('restaurant.contact.phone_href') }}" class="block transition hover:text-cream-50">{{ config('restaurant.contact.phone') }}</a>
                    <a href="mailto:{{ config('restaurant.contact.email') }}" class="block transition hover:text-cream-50">{{ config('restaurant.contact.email') }}</a>
                </p>
                <a href="{{ route('home') }}#reserve" class="btn-gold mt-5 !px-5 !py-2.5 text-xs">{{ __('site.nav.reserve') }}</a>
            </div>
        </div>

        <div class="mt-12 border-t border-cream-50/10 pt-6 text-xs">
            &copy; {{ now()->year }} {{ __('site.brand.name') }}. {{ __('site.footer.rights') }}
        </div>
    </div>
</footer>
