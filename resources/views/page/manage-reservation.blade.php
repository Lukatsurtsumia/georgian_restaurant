<x-layouts.site :title="__('site.manage.title').' — '.__('site.brand.name')">
    <section class="relative isolate overflow-hidden bg-cream-100 bg-paper-radial pb-14 pt-32 text-center sm:pt-36">
        <div class="mx-auto flex flex-col items-center">
            <x-brand-mark class="h-11 w-11" />
            <p class="section-eyebrow mt-4 justify-center">{{ __('site.brand.name') }}</p>
            <h1 class="mt-3 font-display text-4xl font-semibold text-ink-900 sm:text-5xl">{{ __('site.manage.title') }}</h1>
        </div>
    </section>

    <section class="bg-cream-100 py-20">
        <div class="mx-auto max-w-xl px-6 lg:px-8">
            @if (session('just_booked'))
                <div class="mb-6 flex items-start gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 p-5">
                    <svg class="mt-0.5 h-5 w-5 shrink-0 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                    </svg>
                    <div>
                        <p class="font-semibold text-emerald-900">{{ __('site.manage.just_booked_title') }}</p>
                        <p class="mt-0.5 text-sm text-emerald-700">{{ __('site.manage.just_booked_body') }}</p>
                    </div>
                </div>
            @endif

            @if (! $reservation)
                <div class="card p-8 text-center">
                    <h2 class="font-display text-xl font-semibold text-stone-900">{{ __('site.manage.not_found_title') }}</h2>
                    <p class="mt-3 text-stone-600">{{ __('site.manage.not_found_body') }}</p>
                    <a href="{{ route('home') }}" class="btn-primary mt-8 inline-flex">{{ __('site.manage.back_home') }}</a>
                </div>
            @else
                <div class="card p-8">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-sm text-stone-500">{{ __('site.manage.subtitle', ['id' => $reservation->id]) }}</p>
                            <h2 class="mt-1 font-display text-2xl font-semibold text-stone-900">{{ $reservation->name }}</h2>
                        </div>
                        <span @class([
                            'inline-flex shrink-0 rounded-full px-3 py-1 text-xs font-medium',
                            'bg-amber-100 text-amber-800' => $reservation->status === 'pending',
                            'bg-emerald-100 text-emerald-800' => $reservation->status === 'confirmed',
                            'bg-stone-200 text-stone-600' => $reservation->status === 'cancelled',
                        ])>
                            {{ __('site.admin.status_'.$reservation->status) }}
                        </span>
                    </div>

                    <dl class="mt-8 divide-y divide-stone-100 border-y border-stone-100 text-sm">
                        <div class="flex justify-between py-3">
                            <dt class="text-stone-500">{{ __('site.reservation.date') }}</dt>
                            <dd class="font-medium text-stone-900">{{ $reservation->date->translatedFormat('d F Y') }}</dd>
                        </div>
                        <div class="flex justify-between py-3">
                            <dt class="text-stone-500">{{ __('site.reservation.time') }}</dt>
                            <dd class="font-medium text-stone-900">{{ \Carbon\Carbon::parse($reservation->time)->format('H:i') }}</dd>
                        </div>
                        <div class="flex justify-between py-3">
                            <dt class="text-stone-500">{{ __('site.reservation.guests') }}</dt>
                            <dd class="font-medium text-stone-900">{{ $reservation->guests }}</dd>
                        </div>
                        @if ($reservation->table)
                            <div class="flex justify-between py-3">
                                <dt class="text-stone-500">{{ __('site.reservation.table_step_label') }}</dt>
                                <dd class="font-medium text-stone-900">{{ $reservation->table->label }} · {{ __('site.reservation.zone_'.$reservation->table->zone) }}</dd>
                            </div>
                        @endif
                        <div class="flex justify-between py-3">
                            <dt class="text-stone-500">{{ __('site.reservation.notes') }}</dt>
                            <dd class="font-medium text-stone-900">{{ $reservation->notes ?: __('site.manage.no_notes') }}</dd>
                        </div>
                    </dl>

                    @if ($reservation->status === 'cancelled')
                        <p class="mt-6 rounded-lg bg-stone-50 px-4 py-3 text-sm text-stone-600">
                            {{ __('site.manage.already_cancelled') }}
                        </p>
                    @else
                        <form method="POST" action="{{ route('reservations.cancel', $token) }}" class="mt-8"
                              onsubmit="return confirm('{{ __('site.manage.cancel_confirm') }}')">
                            @csrf
                            <button type="submit" class="w-full rounded-full border border-wine-300 px-6 py-3 text-sm font-semibold text-wine-700 transition hover:bg-wine-50">
                                {{ __('site.manage.cancel_button') }}
                            </button>
                        </form>
                        <p class="mt-4 text-center text-xs text-stone-400">{{ __('site.manage.contact_us') }}</p>
                    @endif
                </div>
            @endif
        </div>
    </section>
</x-layouts.site>
