<div>
    <x-slot name="header">
        <h2 class="font-display text-lg font-semibold text-stone-900">{{ __('site.admin.menu_items') }}</h2>
    </x-slot>

    <div class="mb-6 flex items-center justify-between">
        <p class="text-sm text-stone-500">{{ $totalItems }} {{ \Illuminate\Support\Str::plural('item', $totalItems) }} · {{ $categories->count() }} {{ \Illuminate\Support\Str::plural('category', $categories->count()) }}</p>
        <button wire:click="create" class="btn-primary">{{ __('site.admin.add_item') }}</button>
    </div>

    <div class="space-y-6">
        @forelse ($categories as $category)
            @php $categoryItems = $grouped->get($category->id, collect()); @endphp
            <div class="card overflow-hidden">
                <div class="flex items-center justify-between border-b border-stone-100 bg-stone-50 px-5 py-3">
                    <h3 class="font-display text-sm font-semibold text-stone-900">{{ $category->name_en }}</h3>
                    <span class="text-xs text-stone-400">{{ $categoryItems->count() }} {{ Str::plural('item', $categoryItems->count()) }}</span>
                </div>

                @if ($categoryItems->isEmpty())
                    <p class="px-5 py-6 text-sm text-stone-400">{{ __('site.menu.empty') }}</p>
                @else
                    <table class="min-w-full divide-y divide-stone-100 text-sm">
                        <tbody class="divide-y divide-stone-100">
                            @foreach ($categoryItems as $item)
                                <tr wire:key="item-{{ $item->id }}" class="hover:bg-stone-50/60">
                                    <td class="px-5 py-3">
                                        <div class="font-medium text-stone-900">{{ $item->name_en }}</div>
                                        <div class="text-xs text-stone-400">{{ $item->name_fr }}</div>
                                    </td>
                                    <td class="px-5 py-3 text-stone-600">€{{ number_format($item->price, 2) }}</td>
                                    <td class="px-5 py-3 space-x-1">
                                        @if ($item->is_featured)
                                            <span class="inline-flex rounded-full bg-gold-100 px-2 py-0.5 text-xs font-medium text-gold-800">★</span>
                                        @endif
                                        @if ($item->is_vegetarian)
                                            <span class="inline-flex rounded-full bg-emerald-100 px-2 py-0.5 text-xs font-medium text-emerald-800">{{ __('site.admin.vegetarian_short') }}</span>
                                        @endif
                                        @if ($item->is_spicy)
                                            <span class="inline-flex rounded-full bg-wine-100 px-2 py-0.5 text-xs font-medium text-wine-800">{{ __('site.admin.spicy_short') }}</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-3 text-right space-x-3 whitespace-nowrap">
                                        <button wire:click="edit({{ $item->id }})" class="text-xs font-medium text-wine-700 hover:underline">Edit</button>
                                        <button wire:click="delete({{ $item->id }})" wire:confirm="{{ __('site.admin.confirm_delete') }}" class="text-xs font-medium text-stone-400 hover:text-stone-600 hover:underline">{{ __('site.admin.delete') }}</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        @empty
            <div class="card p-12 text-center text-stone-400">{{ __('site.menu.empty') }}</div>
        @endforelse
    </div>

    @if ($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-stone-900/50 p-4" wire:key="modal">
            <div class="max-h-[90vh] w-full max-w-lg overflow-y-auto rounded-2xl bg-white p-6 shadow-xl">
                <h3 class="font-display text-xl font-semibold text-stone-900">
                    {{ $editingId ? __('site.admin.edit_item') : __('site.admin.add_item') }}
                </h3>

                <form wire:submit="save" class="mt-4 grid gap-4">
                    <div>
                        <label class="block text-sm font-medium text-stone-700">{{ __('site.admin.category') }}</label>
                        <select wire:model="menu_category_id" class="mt-1.5 block w-full rounded-lg border-stone-300 shadow-sm focus:border-wine-500 focus:ring-wine-500">
                            <option value="">—</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name_en }}</option>
                            @endforeach
                        </select>
                        @error('menu_category_id') <p class="mt-1 text-sm text-wine-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-stone-700">Name (EN)</label>
                            <input wire:model="name_en" type="text" class="mt-1.5 block w-full rounded-lg border-stone-300 shadow-sm focus:border-wine-500 focus:ring-wine-500">
                            @error('name_en') <p class="mt-1 text-sm text-wine-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-stone-700">Nom (FR)</label>
                            <input wire:model="name_fr" type="text" class="mt-1.5 block w-full rounded-lg border-stone-300 shadow-sm focus:border-wine-500 focus:ring-wine-500">
                            @error('name_fr') <p class="mt-1 text-sm text-wine-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-stone-700">Description (EN)</label>
                            <textarea wire:model="description_en" rows="2" class="mt-1.5 block w-full rounded-lg border-stone-300 shadow-sm focus:border-wine-500 focus:ring-wine-500"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-stone-700">Description (FR)</label>
                            <textarea wire:model="description_fr" rows="2" class="mt-1.5 block w-full rounded-lg border-stone-300 shadow-sm focus:border-wine-500 focus:ring-wine-500"></textarea>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-stone-700">{{ __('site.admin.price') }} (€)</label>
                        <input wire:model="price" type="number" step="0.01" min="0" class="mt-1.5 block w-full rounded-lg border-stone-300 shadow-sm focus:border-wine-500 focus:ring-wine-500">
                        @error('price') <p class="mt-1 text-sm text-wine-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex gap-6">
                        <label class="inline-flex items-center gap-2 text-sm text-stone-700">
                            <input wire:model="is_vegetarian" type="checkbox" class="rounded border-stone-300 text-wine-700 focus:ring-wine-500">
                            {{ __('site.menu.vegetarian') }}
                        </label>
                        <label class="inline-flex items-center gap-2 text-sm text-stone-700">
                            <input wire:model="is_spicy" type="checkbox" class="rounded border-stone-300 text-wine-700 focus:ring-wine-500">
                            {{ __('site.menu.spicy') }}
                        </label>
                        <label class="inline-flex items-center gap-2 text-sm text-stone-700">
                            <input wire:model="is_featured" type="checkbox" class="rounded border-stone-300 text-wine-700 focus:ring-wine-500">
                            {{ __('site.admin.featured') }}
                        </label>
                    </div>

                    <div class="mt-2 flex justify-end gap-3">
                        <button type="button" wire:click="closeModal" class="rounded-full px-5 py-2.5 text-sm font-medium text-stone-600 hover:bg-stone-100">
                            {{ __('site.admin.cancel') }}
                        </button>
                        <button type="submit" class="btn-primary">{{ __('site.admin.save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
