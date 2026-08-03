<div>
    <x-slot name="header">
        <h2 class="font-display text-lg font-semibold text-stone-900">{{ __('site.admin.specials') }}</h2>
    </x-slot>

    <div class="mb-6 flex items-center justify-between gap-4">
        <p class="text-sm text-stone-500">{{ __('site.admin.specials_hint') }}</p>
        <button wire:click="create" class="inline-flex items-center gap-2 rounded-full bg-wine-700 px-4 py-2 text-sm font-semibold text-white transition hover:bg-wine-800">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14"/></svg>
            {{ __('site.admin.add_special') }}
        </button>
    </div>

    <div class="space-y-3">
        @forelse ($specials as $special)
            @php
                $live = $special->is_active
                    && (! $special->starts_on || $special->starts_on->isToday() || $special->starts_on->isPast())
                    && (! $special->ends_on || $special->ends_on->isToday() || $special->ends_on->isFuture());
            @endphp
            <div wire:key="special-{{ $special->id }}" class="card flex flex-wrap items-center justify-between gap-4 p-4">
                <div class="flex items-center gap-3">
                    @if ($special->badge)
                        <span class="inline-flex items-center rounded-md bg-gold-100 px-2.5 py-1 text-xs font-bold text-gold-800">{{ $special->badge }}</span>
                    @endif
                    <div>
                        <p class="font-semibold text-stone-900">{{ $special->title_en }} <span class="text-stone-400">/ {{ $special->title_fr }}</span></p>
                        <p class="text-xs text-stone-500">
                            @if ($special->starts_on || $special->ends_on)
                                {{ $special->starts_on?->format('d/m/Y') ?? '…' }} → {{ $special->ends_on?->format('d/m/Y') ?? '…' }}
                            @else
                                {{ __('site.admin.special_always') }}
                            @endif
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <span @class([
                        'inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium',
                        'bg-emerald-100 text-emerald-800' => $live,
                        'bg-stone-200 text-stone-500' => ! $live,
                    ])>
                        <span @class(['h-1.5 w-1.5 rounded-full', 'bg-emerald-500' => $live, 'bg-stone-400' => ! $live])></span>
                        {{ $live ? __('site.admin.special_live') : __('site.admin.special_off') }}
                    </span>
                    <button wire:click="toggle({{ $special->id }})" class="rounded-lg border border-stone-200 px-3 py-1.5 text-xs font-medium text-stone-600 hover:bg-stone-50">
                        {{ $special->is_active ? __('site.admin.special_disable') : __('site.admin.special_enable') }}
                    </button>
                    <button wire:click="edit({{ $special->id }})" class="rounded-lg px-3 py-1.5 text-xs font-medium text-wine-700 hover:bg-wine-50">{{ __('site.admin.edit') }}</button>
                    <button wire:click="delete({{ $special->id }})" wire:confirm="{{ __('site.admin.confirm_delete') }}" class="rounded-lg px-3 py-1.5 text-xs font-medium text-stone-400 hover:bg-stone-50 hover:text-wine-700">{{ __('site.admin.delete') }}</button>
                </div>
            </div>
        @empty
            <div class="card p-12 text-center text-stone-400">{{ __('site.admin.no_specials') }}</div>
        @endforelse
    </div>

    {{-- Modal --}}
    @if ($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-stone-900/40" wire:click="closeModal"></div>
            <div class="relative w-full max-w-lg rounded-2xl bg-white p-6 shadow-xl">
                <h3 class="font-display text-lg font-semibold text-stone-900">
                    {{ $editingId ? __('site.admin.edit_special') : __('site.admin.add_special') }}
                </h3>

                <form wire:submit="save" class="mt-5 space-y-4">
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-stone-700">{{ __('site.admin.title') }} (EN)</label>
                            <input wire:model="title_en" type="text" class="mt-1 block w-full rounded-lg border-stone-300 text-sm shadow-sm focus:border-wine-500 focus:ring-wine-500">
                            @error('title_en') <p class="mt-1 text-xs text-wine-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-stone-700">{{ __('site.admin.title') }} (FR)</label>
                            <input wire:model="title_fr" type="text" class="mt-1 block w-full rounded-lg border-stone-300 text-sm shadow-sm focus:border-wine-500 focus:ring-wine-500">
                            @error('title_fr') <p class="mt-1 text-xs text-wine-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-stone-700">{{ __('site.reservation.notes') }} (EN)</label>
                            <textarea wire:model="description_en" rows="2" class="mt-1 block w-full rounded-lg border-stone-300 text-sm shadow-sm focus:border-wine-500 focus:ring-wine-500"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-stone-700">{{ __('site.reservation.notes') }} (FR)</label>
                            <textarea wire:model="description_fr" rows="2" class="mt-1 block w-full rounded-lg border-stone-300 text-sm shadow-sm focus:border-wine-500 focus:ring-wine-500"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-stone-700">{{ __('site.admin.special_badge') }}</label>
                            <input wire:model="badge" type="text" placeholder="-20% · Apéro" class="mt-1 block w-full rounded-lg border-stone-300 text-sm shadow-sm focus:border-wine-500 focus:ring-wine-500">
                        </div>
                        <label class="flex items-center gap-2 pt-6 text-sm text-stone-700">
                            <input wire:model="is_active" type="checkbox" class="rounded border-stone-300 text-wine-700 focus:ring-wine-500">
                            {{ __('site.admin.special_active_label') }}
                        </label>
                        <div>
                            <label class="block text-sm font-medium text-stone-700">{{ __('site.admin.from') }}</label>
                            <input wire:model="starts_on" type="date" class="mt-1 block w-full rounded-lg border-stone-300 text-sm shadow-sm focus:border-wine-500 focus:ring-wine-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-stone-700">{{ __('site.admin.to') }}</label>
                            <input wire:model="ends_on" type="date" class="mt-1 block w-full rounded-lg border-stone-300 text-sm shadow-sm focus:border-wine-500 focus:ring-wine-500">
                            @error('ends_on') <p class="mt-1 text-xs text-wine-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" wire:click="closeModal" class="rounded-full px-4 py-2 text-sm font-medium text-stone-500 hover:bg-stone-100">{{ __('site.admin.cancel') }}</button>
                        <button type="submit" class="rounded-full bg-wine-700 px-5 py-2 text-sm font-semibold text-white hover:bg-wine-800">{{ __('site.admin.save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
