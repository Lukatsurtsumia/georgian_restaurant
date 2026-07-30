@php
    $capacity = (int) config('restaurant.reservation.seats_per_slot');

    // Build up to 6 upcoming *open* date pills from the earliest bookable date.
    $closedDays = array_map('intval', (array) config('restaurant.reservation.closed_days'));
    $pills = [];
    $cursor = \Carbon\Carbon::parse($minDate);
    $guard = 0;
    while (count($pills) < 6 && $guard < 40) {
        if (! in_array($cursor->dayOfWeek, $closedDays, true)) {
            $pills[] = $cursor->copy();
        }
        $cursor->addDay();
        $guard++;
    }
    $today = \Carbon\Carbon::today()->toDateString();
    $tomorrow = \Carbon\Carbon::tomorrow()->toDateString();
@endphp

<div class="card overflow-hidden p-0">
    @if ($submitted)
        {{-- ---------------------------------------------------------------- --}}
        {{-- Success --}}
        {{-- ---------------------------------------------------------------- --}}
        <div class="flex flex-col items-center px-6 py-16 text-center sm:px-10" wire:key="success">
            <div class="flex h-20 w-20 animate-float-slow items-center justify-center rounded-full bg-wine-800 shadow-lift">
                <x-brand-mark tone="cream" class="h-11 w-11" />
            </div>
            <h3 class="mt-7 font-display text-3xl font-semibold text-ink-900">
                {{ __('site.reservation.success_title', ['name' => $confirmedName]) }}
            </h3>
            <p class="mt-3 max-w-md leading-relaxed text-ink-700">
                {{ __('site.reservation.success_body', ['date' => $confirmedDate, 'time' => $confirmedTime]) }}
            </p>

            <div class="mt-8 flex flex-col items-center gap-3 sm:flex-row">
                @if ($confirmedCalendarUrl)
                    <a href="{{ $confirmedCalendarUrl }}" target="_blank" rel="noopener" class="btn-outline">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0V11.25A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/></svg>
                        {{ __('site.reservation.success_add_calendar') }}
                    </a>
                @endif
                <button wire:click="reserveAnother" type="button" class="btn-primary">
                    {{ __('site.reservation.new_reservation') }}
                </button>
            </div>

            @if ($manageUrl)
                <a href="{{ $manageUrl }}" class="mt-6 text-sm font-medium text-wine-700 underline decoration-wine-300 underline-offset-4 hover:text-wine-800">
                    {{ __('site.reservation.manage_link_label') }}
                </a>
            @endif
        </div>
    @else
        {{-- Step indicator --}}
        <div class="flex border-b border-cream-300 bg-cream-100/60">
            @foreach ([1 => __('site.reservation.step1_label'), 2 => __('site.reservation.step2_label')] as $n => $label)
                <div @class(['flex flex-1 items-center gap-3 px-6 py-4', 'border-l border-cream-300' => $n === 2])>
                    <span @class([
                        'flex h-7 w-7 shrink-0 items-center justify-center rounded-full text-xs font-semibold transition',
                        'bg-wine-700 text-cream-50' => $step >= $n,
                        'bg-cream-300 text-ink-700/50' => $step < $n,
                    ])>{{ $n }}</span>
                    <span @class(['text-sm font-medium', 'text-ink-900' => $step === $n, 'text-ink-700/40' => $step !== $n])>
                        {!! $label !!}
                    </span>
                </div>
            @endforeach
        </div>

        <div class="p-6 sm:p-9">
            @if ($step === 1)
                <div class="grid gap-7" wire:key="step1">
                    {{-- Party size stepper --}}
                    <div>
                        <label class="block text-sm font-semibold text-ink-800">{{ __('site.reservation.party_label') }}</label>
                        <div class="mt-3 flex items-center gap-4">
                            <div class="inline-flex items-center rounded-full border border-cream-300 bg-cream-50 p-1">
                                <button type="button" wire:click="$set('guests', {{ max($guests - 1, 1) }})" @disabled($guests <= 1)
                                        class="flex h-10 w-10 items-center justify-center rounded-full text-wine-700 transition hover:bg-wine-700/10 disabled:opacity-30"
                                        aria-label="−">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" d="M5 12h14"/></svg>
                                </button>
                                <span class="w-14 text-center font-display text-2xl font-semibold text-ink-900" wire:loading.class="opacity-40" wire:target="guests">{{ $guests }}</span>
                                <button type="button" wire:click="$set('guests', {{ min($guests + 1, $maxGuests) }})" @disabled($guests >= $maxGuests)
                                        class="flex h-10 w-10 items-center justify-center rounded-full text-wine-700 transition hover:bg-wine-700/10 disabled:opacity-30"
                                        aria-label="+">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" d="M12 5v14M5 12h14"/></svg>
                                </button>
                            </div>
                            <span class="text-sm text-ink-700/70">{{ $guests === 1 ? __('site.reservation.guest_one') : __('site.reservation.guest_many') }}</span>
                        </div>
                        @error('guests') <p class="mt-1.5 text-sm text-wine-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Date pills --}}
                    <div>
                        <label class="block text-sm font-semibold text-ink-800">{{ __('site.reservation.date') }}</label>
                        <div class="mt-3 flex flex-wrap gap-2">
                            @foreach ($pills as $pill)
                                @php $val = $pill->toDateString(); @endphp
                                <button type="button" wire:click="$set('date', '{{ $val }}')"
                                        @class([
                                            'flex min-w-[4.5rem] flex-col items-center rounded-xl border px-3 py-2 text-center transition',
                                            'border-wine-700 bg-wine-700 text-cream-50 shadow-sm' => $date === $val,
                                            'border-cream-300 bg-cream-50 text-ink-800 hover:border-wine-300 hover:bg-wine-50' => $date !== $val,
                                        ])>
                                    <span class="text-[11px] font-medium uppercase tracking-wide {{ $date === $val ? 'text-cream-50/80' : 'text-ink-700/50' }}">
                                        @if ($val === $today) {{ __('site.reservation.pill_tonight') }}
                                        @elseif ($val === $tomorrow) {{ __('site.reservation.pill_tomorrow') }}
                                        @else {{ $pill->translatedFormat('D') }}
                                        @endif
                                    </span>
                                    <span class="font-display text-lg font-semibold leading-tight">{{ $pill->translatedFormat('j') }}</span>
                                    <span class="text-[10px] {{ $date === $val ? 'text-cream-50/70' : 'text-ink-700/40' }}">{{ $pill->translatedFormat('M') }}</span>
                                </button>
                            @endforeach

                            {{-- More dates --}}
                            <label class="flex min-w-[4.5rem] cursor-pointer flex-col items-center justify-center rounded-xl border border-dashed border-cream-400 bg-cream-50 px-3 py-2 text-center text-ink-700/60 transition hover:border-wine-300 hover:text-wine-700">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 9h18M4.5 5.25h15A1.5 1.5 0 0 1 21 6.75v12A1.5 1.5 0 0 1 19.5 20.25h-15A1.5 1.5 0 0 1 3 18.75v-12A1.5 1.5 0 0 1 4.5 5.25Z"/></svg>
                                <span class="mt-0.5 text-[10px] font-medium">{{ __('site.reservation.pick_another_date') }}</span>
                                <input wire:model.live="date" type="date" min="{{ $minDate }}" max="{{ $maxDate }}" class="sr-only">
                            </label>
                        </div>
                        @error('date') <p class="mt-1.5 text-sm text-wine-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Live availability heatmap --}}
                    <div>
                        <div class="flex items-baseline justify-between gap-3">
                            <label class="text-sm font-semibold text-ink-800">{{ __('site.reservation.availability_title') }}</label>
                            @if ($date)
                                <span class="inline-flex items-center gap-1.5 text-xs font-medium text-emerald-700">
                                    <span class="relative flex h-2 w-2"><span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-500 opacity-60"></span><span class="relative inline-flex h-2 w-2 rounded-full bg-emerald-500"></span></span>
                                    {{ __('site.reservation.availability_hint') }}
                                </span>
                            @endif
                        </div>

                        @if (! $date)
                            <p class="mt-3 rounded-xl bg-cream-100 px-4 py-4 text-center text-sm text-ink-700/50">
                                {{ __('site.reservation.select_date_first') }}
                            </p>
                        @else
                            <div class="mt-3 grid grid-cols-3 gap-2.5 sm:grid-cols-4" wire:loading.class="opacity-40" wire:target="date,guests">
                                @foreach ($this->availableSlots as $slot)
                                    @php
                                        $remaining = max(0, (int) $slot['remaining']);
                                        $pct = $capacity > 0 ? max(8, min(100, round($remaining / $capacity * 100))) : 0;
                                        $isSelected = $time === $slot['value'];
                                        if ($slot['full']) { $barColor = '#9c3d44'; }
                                        elseif ($slot['low']) { $barColor = '#e1a53a'; }
                                        else { $barColor = '#059669'; }
                                    @endphp
                                    <button
                                        type="button"
                                        wire:click="$set('time', '{{ $slot['value'] }}')"
                                        @disabled($slot['full'])
                                        @class([
                                            'group relative flex flex-col gap-2 rounded-xl border px-2.5 py-2.5 text-left transition',
                                            'border-wine-700 bg-wine-700 shadow-sm' => $isSelected,
                                            'border-cream-300 bg-cream-50 hover:border-wine-300 hover:bg-wine-50' => ! $isSelected && ! $slot['full'],
                                            'cursor-not-allowed border-cream-200 bg-cream-100' => $slot['full'],
                                        ])>
                                        <span @class([
                                            'font-display text-base font-semibold',
                                            'text-cream-50' => $isSelected,
                                            'text-ink-900' => ! $isSelected && ! $slot['full'],
                                            'text-ink-700/30 line-through' => $slot['full'],
                                        ])>{{ $slot['label'] }}</span>

                                        {{-- availability bar --}}
                                        <span class="block h-1.5 w-full overflow-hidden rounded-full {{ $isSelected ? 'bg-cream-50/25' : 'bg-cream-300' }}">
                                            <span class="block h-full rounded-full transition-all duration-500" style="width: {{ $slot['full'] ? 100 : $pct }}%; background-color: {{ $isSelected ? '#f9edd0' : $barColor }};"></span>
                                        </span>

                                        <span @class([
                                            'text-[10px] font-medium',
                                            'text-cream-50/80' => $isSelected,
                                            'text-wine-600' => ! $isSelected && $slot['full'],
                                            'text-gold-600' => ! $isSelected && ! $slot['full'] && $slot['low'],
                                            'text-emerald-700' => ! $isSelected && ! $slot['full'] && ! $slot['low'],
                                        ])>
                                            @if ($slot['full']) {{ __('site.reservation.sold_out') }}
                                            @elseif ($slot['low']) {{ __('site.reservation.seats_left', ['count' => $remaining]) }}
                                            @else {{ __('site.reservation.good_availability') }}
                                            @endif
                                        </span>
                                    </button>
                                @endforeach
                            </div>
                        @endif
                        @error('time') <p class="mt-2 text-sm text-wine-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Actions --}}
                    <div class="flex flex-col gap-4 border-t border-cream-200 pt-6 sm:flex-row sm:items-center">
                        <button type="button" wire:click="goToDetails" class="btn-primary w-full sm:w-auto">
                            {{ __('site.reservation.continue') }}
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                        </button>

                        @if ($googleEnabled)
                            <span class="text-center text-xs font-medium uppercase tracking-wide text-ink-700/40 sm:text-left">{{ __('site.reservation.or_divider') }}</span>
                            <button type="button" wire:click="continueWithGoogle" wire:loading.attr="disabled" wire:target="continueWithGoogle"
                                    class="inline-flex w-full items-center justify-center gap-2.5 rounded-full border border-cream-300 bg-cream-50 px-6 py-3 text-sm font-semibold text-ink-800 shadow-sm transition hover:bg-cream-100 sm:w-auto">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" aria-hidden="true">
                                    <path fill="#4285F4" d="M23.52 12.27c0-.85-.08-1.67-.22-2.45H12v4.64h6.47c-.28 1.5-1.13 2.78-2.4 3.63v3.02h3.88c2.27-2.09 3.57-5.17 3.57-8.84Z"/>
                                    <path fill="#34A853" d="M12 24c3.24 0 5.95-1.07 7.94-2.9l-3.88-3.02c-1.08.72-2.45 1.15-4.06 1.15-3.12 0-5.77-2.11-6.71-4.94H1.28v3.11C3.26 21.3 7.31 24 12 24Z"/>
                                    <path fill="#FBBC05" d="M5.29 14.29a7.2 7.2 0 0 1 0-4.58V6.6H1.28a11.98 11.98 0 0 0 0 10.8l4.01-3.11Z"/>
                                    <path fill="#EA4335" d="M12 4.77c1.76 0 3.34.6 4.59 1.79l3.44-3.44C17.94 1.19 15.23 0 12 0 7.31 0 3.26 2.7 1.28 6.6l4.01 3.11C6.23 6.88 8.88 4.77 12 4.77Z"/>
                                </svg>
                                {{ __('site.reservation.continue_with_google') }}
                            </button>
                        @endif
                    </div>
                </div>
            @else
                {{-- Step 2 --}}
                <form wire:submit="save" class="grid gap-6" wire:key="step2">
                    <div class="flex items-center justify-between gap-3 rounded-xl bg-cream-100 px-4 py-3.5 text-sm">
                        <div class="flex items-center gap-2.5">
                            <svg class="h-5 w-5 shrink-0 text-wine-700" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 9h18M4.5 5.25h15A1.5 1.5 0 0 1 21 6.75v12a1.5 1.5 0 0 1-1.5 1.5h-15A1.5 1.5 0 0 1 3 18.75v-12a1.5 1.5 0 0 1 1.5-1.5Z"/></svg>
                            <span class="text-ink-800">{{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }} · {{ $time }} · {{ __('site.admin.guests_label', ['count' => $guests]) }}</span>
                        </div>
                        <button type="button" wire:click="backToDateTime" class="shrink-0 font-medium text-wine-700 hover:underline">
                            {{ __('site.reservation.back') }}
                        </button>
                    </div>

                    <div class="grid gap-5 sm:grid-cols-2">
                        <div>
                            <label for="name" class="block text-sm font-medium text-ink-800">{{ __('site.reservation.name') }}</label>
                            <input wire:model="name" id="name" type="text" class="mt-1.5 block w-full rounded-xl border-cream-300 bg-cream-50 shadow-sm focus:border-wine-500 focus:ring-wine-500">
                            @error('name') <p class="mt-1 text-sm text-wine-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-ink-800">{{ __('site.reservation.email') }}</label>
                            <input wire:model="email" id="email" type="email" class="mt-1.5 block w-full rounded-xl border-cream-300 bg-cream-50 shadow-sm focus:border-wine-500 focus:ring-wine-500">
                            @error('email') <p class="mt-1 text-sm text-wine-600">{{ $message }}</p> @enderror
                        </div>
                        <div class="sm:col-span-2">
                            <label for="phone" class="block text-sm font-medium text-ink-800">{{ __('site.reservation.phone') }}</label>
                            <input wire:model="phone" id="phone" type="tel" class="mt-1.5 block w-full rounded-xl border-cream-300 bg-cream-50 shadow-sm focus:border-wine-500 focus:ring-wine-500">
                            @error('phone') <p class="mt-1 text-sm text-wine-600">{{ $message }}</p> @enderror
                        </div>
                        <div class="sm:col-span-2">
                            <label for="notes" class="block text-sm font-medium text-ink-800">{{ __('site.reservation.notes') }}</label>
                            <textarea wire:model="notes" id="notes" rows="3" placeholder="{{ __('site.reservation.notes_placeholder') }}" class="mt-1.5 block w-full rounded-xl border-cream-300 bg-cream-50 shadow-sm focus:border-wine-500 focus:ring-wine-500"></textarea>
                            @error('notes') <p class="mt-1 text-sm text-wine-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <button type="submit" class="btn-primary w-full sm:w-auto" wire:loading.attr="disabled" wire:target="save">
                            <span wire:loading.remove wire:target="save">{{ __('site.reservation.submit') }}</span>
                            <span wire:loading wire:target="save">{{ __('site.reservation.submitting') }}</span>
                        </button>
                    </div>
                </form>
            @endif
        </div>
    @endif
</div>
