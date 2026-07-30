<div wire:poll.60s>
    <x-slot name="header">
        <h2 class="font-display text-lg font-semibold text-stone-900">{{ __('site.admin.dashboard') }}</h2>
    </x-slot>

    <div class="mb-8">
        <h1 class="font-display text-2xl font-semibold text-stone-900">{{ __('site.admin.welcome_back') }}, {{ auth()->user()->name }}</h1>
        <p class="mt-1 text-sm text-stone-500">{{ __('site.admin.today_overview') }}</p>
    </div>

    {{-- KPI cards --}}
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="card p-5">
            <div class="flex items-center justify-between">
                <p class="text-xs font-semibold uppercase tracking-wide text-stone-500">{{ __('site.admin.kpi_today_reservations') }}</p>
                <span class="flex h-9 w-9 items-center justify-center rounded-full bg-wine-100 text-wine-700">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" /></svg>
                </span>
            </div>
            <p class="mt-3 font-display text-3xl font-semibold text-stone-900">{{ $this->todayReservations->count() }}</p>
        </div>

        <div class="card p-5">
            <div class="flex items-center justify-between">
                <p class="text-xs font-semibold uppercase tracking-wide text-stone-500">{{ __('site.admin.kpi_today_guests') }}</p>
                <span class="flex h-9 w-9 items-center justify-center rounded-full bg-gold-100 text-gold-700">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" /></svg>
                </span>
            </div>
            <p class="mt-3 font-display text-3xl font-semibold text-stone-900">{{ $todayGuests }}</p>
        </div>

        <div class="card p-5">
            <div class="flex items-center justify-between">
                <p class="text-xs font-semibold uppercase tracking-wide text-stone-500">{{ __('site.admin.kpi_pending') }}</p>
                <span class="flex h-9 w-9 items-center justify-center rounded-full bg-amber-100 text-amber-700">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M12 15.75h.007v.008H12v-.008Z" /></svg>
                </span>
            </div>
            <p class="mt-3 font-display text-3xl font-semibold text-stone-900">{{ $this->pendingCount }}</p>
        </div>

        <div class="card p-5">
            <div class="flex items-center justify-between">
                <p class="text-xs font-semibold uppercase tracking-wide text-stone-500">{{ __('site.admin.kpi_week') }}</p>
                <span class="flex h-9 w-9 items-center justify-center rounded-full bg-emerald-100 text-emerald-700">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l6-6m0 0l6 6m-6-6v18" transform="rotate(90 12 12)" /></svg>
                </span>
            </div>
            <p class="mt-3 font-display text-3xl font-semibold text-stone-900">{{ $this->weekCount }}</p>
        </div>
    </div>

    <div class="mt-8 grid gap-6 lg:grid-cols-5">
        {{-- Occupancy --}}
        <div class="card p-6 lg:col-span-2">
            <h3 class="font-display text-base font-semibold text-stone-900">{{ __('site.admin.occupancy_title') }}</h3>

            @if (empty($this->occupancy))
                <p class="mt-6 text-sm text-stone-400">{{ __('site.admin.occupancy_empty') }}</p>
            @else
                <div class="mt-5 space-y-4">
                    @foreach ($this->occupancy as $row)
                        <div>
                            <div class="flex justify-between text-xs font-medium text-stone-600">
                                <span>{{ $row['time'] }}</span>
                                <span>{{ __('site.admin.seats_of', ['booked' => $row['booked'], 'total' => $row['capacity']]) }}</span>
                            </div>
                            <div class="mt-1.5 h-2 w-full overflow-hidden rounded-full bg-stone-100">
                                <div class="h-full rounded-full {{ $row['percent'] >= 90 ? 'bg-wine-700' : 'bg-gold-400' }}" style="width: {{ $row['percent'] }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Upcoming --}}
        <div class="card p-6 lg:col-span-3">
            <div class="flex items-center justify-between">
                <h3 class="font-display text-base font-semibold text-stone-900">{{ __('site.admin.upcoming_title') }}</h3>
                <a href="{{ route('admin.reservations') }}" class="text-xs font-medium text-wine-700 hover:underline">{{ __('site.admin.reservations') }} →</a>
            </div>

            @if ($this->upcoming->isEmpty())
                <p class="mt-6 text-sm text-stone-400">{{ __('site.admin.upcoming_empty') }}</p>
            @else
                <div class="mt-4 divide-y divide-stone-100">
                    @foreach ($this->upcoming as $reservation)
                        <div class="flex items-center justify-between py-3 text-sm">
                            <div>
                                <p class="font-medium text-stone-900">{{ $reservation->name }}</p>
                                <p class="text-xs text-stone-400">{{ $reservation->date->format('d/m/Y') }} · {{ \Carbon\Carbon::parse($reservation->time)->format('H:i') }} · {{ __('site.admin.guests_label', ['count' => $reservation->guests]) }}</p>
                            </div>
                            <span @class([
                                'inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium',
                                'bg-amber-100 text-amber-800' => $reservation->status === 'pending',
                                'bg-emerald-100 text-emerald-800' => $reservation->status === 'confirmed',
                            ])>
                                {{ __('site.admin.status_'.$reservation->status) }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
