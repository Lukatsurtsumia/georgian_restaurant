<div>
    <x-slot name="header">
        <h2 class="font-display text-lg font-semibold text-stone-900">{{ __('site.admin.reservations') }}</h2>
    </x-slot>

    {{-- Filters --}}
    <div class="card mb-6 p-4">
        <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-[1fr_auto_auto_auto]">
            <div class="relative">
                <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-stone-400" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" /></svg>
                <input wire:model.live.debounce.400ms="search" type="text" placeholder="{{ __('site.admin.search_placeholder') }}"
                       class="w-full rounded-lg border-stone-300 pl-9 text-sm shadow-sm focus:border-wine-500 focus:ring-wine-500">
            </div>
            <div class="flex items-center gap-2">
                <label class="text-xs font-medium text-stone-500">{{ __('site.admin.from') }}</label>
                <input wire:model.live="dateFrom" type="date" class="rounded-lg border-stone-300 text-sm shadow-sm focus:border-wine-500 focus:ring-wine-500">
            </div>
            <div class="flex items-center gap-2">
                <label class="text-xs font-medium text-stone-500">{{ __('site.admin.to') }}</label>
                <input wire:model.live="dateTo" type="date" class="rounded-lg border-stone-300 text-sm shadow-sm focus:border-wine-500 focus:ring-wine-500">
            </div>
            <button wire:click="clearFilters" type="button" class="rounded-lg border border-stone-200 px-3 py-2 text-xs font-medium text-stone-500 hover:bg-stone-50">
                {{ __('site.admin.clear_filters') }}
            </button>
        </div>

        <div class="mt-3 flex gap-2">
            @foreach (['all' => __('site.admin.all_statuses'), 'pending' => __('site.admin.status_pending'), 'confirmed' => __('site.admin.status_confirmed'), 'cancelled' => __('site.admin.status_cancelled')] as $value => $label)
                <button wire:click="$set('statusFilter', '{{ $value }}')"
                    class="rounded-full px-4 py-1.5 text-sm font-medium transition {{ $statusFilter === $value ? 'bg-wine-700 text-white' : 'bg-stone-100 text-stone-600 hover:bg-stone-200' }}">
                    {{ $label }}
                </button>
            @endforeach
        </div>
    </div>

    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-stone-200 text-sm">
                <thead class="bg-stone-50">
                    <tr class="text-left text-xs font-semibold uppercase tracking-wide text-stone-500">
                        <th class="px-4 py-3">Guest</th>
                        <th class="px-4 py-3">Date &amp; Time</th>
                        <th class="px-4 py-3">{{ __('site.reservation.guests') }}</th>
                        <th class="px-4 py-3">{{ __('site.reservation.table_step_label') }}</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @forelse ($reservations as $reservation)
                        <tr wire:key="reservation-{{ $reservation->id }}" class="hover:bg-stone-50/60">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-1.5 font-medium text-stone-900">
                                    {{ $reservation->name }}
                                    @if ($reservation->source === 'google')
                                        <svg class="h-3.5 w-3.5 shrink-0" viewBox="0 0 24 24" aria-hidden="true">
                                            <title>Booked via Google</title>
                                            <path fill="#4285F4" d="M23.52 12.27c0-.85-.08-1.67-.22-2.45H12v4.64h6.47c-.28 1.5-1.13 2.78-2.4 3.63v3.02h3.88c2.27-2.09 3.57-5.17 3.57-8.84Z"/>
                                            <path fill="#34A853" d="M12 24c3.24 0 5.95-1.07 7.94-2.9l-3.88-3.02c-1.08.72-2.45 1.15-4.06 1.15-3.12 0-5.77-2.11-6.71-4.94H1.28v3.11C3.26 21.3 7.31 24 12 24Z"/>
                                            <path fill="#FBBC05" d="M5.29 14.29a7.2 7.2 0 0 1 0-4.58V6.6H1.28a11.98 11.98 0 0 0 0 10.8l4.01-3.11Z"/>
                                            <path fill="#EA4335" d="M12 4.77c1.76 0 3.34.6 4.59 1.79l3.44-3.44C17.94 1.19 15.23 0 12 0 7.31 0 3.26 2.7 1.28 6.6l4.01 3.11C6.23 6.88 8.88 4.77 12 4.77Z"/>
                                        </svg>
                                    @endif
                                </div>
                                <div class="text-xs text-stone-400">{{ $reservation->email }}</div>
                            </td>
                            <td class="px-4 py-3 text-stone-600">
                                {{ $reservation->date->format('d/m/Y') }}
                                <span class="text-stone-400">{{ \Carbon\Carbon::parse($reservation->time)->format('H:i') }}</span>
                            </td>
                            <td class="px-4 py-3 text-stone-600">{{ $reservation->guests }}</td>
                            <td class="px-4 py-3">
                                @if ($reservation->table)
                                    <span class="inline-flex items-center gap-1 rounded-md bg-wine-50 px-2.5 py-1 text-xs font-bold text-wine-700">
                                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><circle cx="12" cy="12" r="9"/></svg>
                                        {{ $reservation->table->label }}
                                    </span>
                                @else
                                    <span class="text-xs text-stone-300">{{ __('site.reservation.table_none') }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <span @class([
                                    'inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium',
                                    'bg-amber-100 text-amber-800' => $reservation->status === 'pending',
                                    'bg-emerald-100 text-emerald-800' => $reservation->status === 'confirmed',
                                    'bg-stone-200 text-stone-600' => $reservation->status === 'cancelled',
                                ])>
                                    {{ __('site.admin.status_'.$reservation->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <button wire:click="view({{ $reservation->id }})" class="text-xs font-medium text-wine-700 hover:underline">
                                    {{ __('site.admin.view_details') }}
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-12 text-center text-stone-400">{{ __('site.admin.no_reservations') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($reservations->hasPages())
            <div class="border-t border-stone-100 px-4 py-3">
                {{ $reservations->links() }}
            </div>
        @endif
    </div>

    {{-- Detail slide-over --}}
    @if ($selected)
        <div class="fixed inset-0 z-50 flex justify-end" wire:key="detail-{{ $selected->id }}">
            <div class="absolute inset-0 bg-stone-900/40" wire:click="closeDetails"></div>
            <div class="relative flex h-full w-full max-w-md flex-col overflow-y-auto bg-white shadow-xl">
                <div class="flex items-center justify-between border-b border-stone-200 px-6 py-5">
                    <h3 class="font-display text-lg font-semibold text-stone-900">{{ __('site.admin.details_title') }}</h3>
                    <button wire:click="closeDetails" class="text-stone-400 hover:text-stone-600">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <div class="flex-1 px-6 py-6">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="font-display text-xl font-semibold text-stone-900">{{ $selected->name }}</p>
                            <p class="text-sm text-stone-500">#{{ $selected->id }}</p>
                        </div>
                        <span @class([
                            'inline-flex rounded-full px-3 py-1 text-xs font-medium',
                            'bg-amber-100 text-amber-800' => $selected->status === 'pending',
                            'bg-emerald-100 text-emerald-800' => $selected->status === 'confirmed',
                            'bg-stone-200 text-stone-600' => $selected->status === 'cancelled',
                        ])>
                            {{ __('site.admin.status_'.$selected->status) }}
                        </span>
                    </div>

                    <dl class="mt-6 divide-y divide-stone-100 border-y border-stone-100 text-sm">
                        <div class="flex justify-between py-3">
                            <dt class="text-stone-500">{{ __('site.reservation.date') }}</dt>
                            <dd class="font-medium text-stone-900">{{ $selected->date->translatedFormat('d F Y') }}</dd>
                        </div>
                        <div class="flex justify-between py-3">
                            <dt class="text-stone-500">{{ __('site.reservation.time') }}</dt>
                            <dd class="font-medium text-stone-900">{{ \Carbon\Carbon::parse($selected->time)->format('H:i') }}</dd>
                        </div>
                        <div class="flex justify-between py-3">
                            <dt class="text-stone-500">{{ __('site.reservation.guests') }}</dt>
                            <dd class="font-medium text-stone-900">{{ $selected->guests }}</dd>
                        </div>
                        <div class="flex justify-between py-3">
                            <dt class="text-stone-500">{{ __('site.reservation.table_step_label') }}</dt>
                            <dd class="font-semibold text-wine-700">{{ $selected->table ? $selected->table->label.' · '.__('site.reservation.zone_'.$selected->table->zone) : __('site.reservation.table_none') }}</dd>
                        </div>
                        <div class="flex justify-between py-3">
                            <dt class="text-stone-500">{{ __('site.reservation.email') }}</dt>
                            <dd class="font-medium text-stone-900">{{ $selected->email }}</dd>
                        </div>
                        <div class="flex justify-between py-3">
                            <dt class="text-stone-500">{{ __('site.reservation.phone') }}</dt>
                            <dd class="font-medium text-stone-900">{{ $selected->phone ?: '—' }}</dd>
                        </div>
                    </dl>

                    <div class="mt-4">
                        <p class="text-xs font-semibold uppercase tracking-wide text-stone-400">{{ __('site.reservation.notes') }}</p>
                        <p class="mt-1.5 text-sm text-stone-600">{{ $selected->notes ?: __('site.manage.no_notes') }}</p>
                    </div>

                    <p class="mt-6 text-xs text-stone-400">{{ __('site.admin.booked_on', ['date' => $selected->created_at->format('d/m/Y H:i')]) }}</p>
                </div>

                <div class="flex gap-3 border-t border-stone-200 px-6 py-5">
                    @if ($selected->status !== 'confirmed')
                        <button wire:click="setStatus({{ $selected->id }}, 'confirmed')" class="flex-1 rounded-full bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-700">
                            {{ __('site.admin.mark_confirmed') }}
                        </button>
                    @endif
                    @if ($selected->status !== 'cancelled')
                        <button wire:click="setStatus({{ $selected->id }}, 'cancelled')" class="flex-1 rounded-full border border-wine-300 px-4 py-2.5 text-sm font-semibold text-wine-700 transition hover:bg-wine-50">
                            {{ __('site.admin.mark_cancelled') }}
                        </button>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>
