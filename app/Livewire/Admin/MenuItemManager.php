<?php

namespace App\Livewire\Admin;

use App\Models\MenuCategory;
use App\Models\MenuItem;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.dashboard')]
class MenuItemManager extends Component
{
    public bool $showModal = false;

    public ?int $editingId = null;

    public ?int $menu_category_id = null;

    public string $name_en = '';

    public string $name_fr = '';

    public string $description_en = '';

    public string $description_fr = '';

    public string $price = '';

    public bool $is_vegetarian = false;

    public bool $is_spicy = false;

    public bool $is_featured = false;

    protected function rules(): array
    {
        return [
            'menu_category_id' => ['required', 'exists:menu_categories,id'],
            'name_en' => ['required', 'string', 'max:150'],
            'name_fr' => ['required', 'string', 'max:150'],
            'description_en' => ['nullable', 'string', 'max:500'],
            'description_fr' => ['nullable', 'string', 'max:500'],
            'price' => ['required', 'numeric', 'min:0', 'max:9999.99'],
            'is_vegetarian' => ['boolean'],
            'is_spicy' => ['boolean'],
            'is_featured' => ['boolean'],
        ];
    }

    public function create(): void
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit(int $itemId): void
    {
        $item = MenuItem::findOrFail($itemId);

        $this->editingId = $item->id;
        $this->menu_category_id = $item->menu_category_id;
        $this->name_en = $item->name_en;
        $this->name_fr = $item->name_fr;
        $this->description_en = (string) $item->description_en;
        $this->description_fr = (string) $item->description_fr;
        $this->price = (string) $item->price;
        $this->is_vegetarian = $item->is_vegetarian;
        $this->is_spicy = $item->is_spicy;
        $this->is_featured = $item->is_featured;

        $this->showModal = true;
    }

    public function save(): void
    {
        $validated = $this->validate();

        MenuItem::updateOrCreate(['id' => $this->editingId], $validated);

        $this->showModal = false;
        $this->resetForm();
    }

    public function delete(int $itemId): void
    {
        MenuItem::whereKey($itemId)->delete();
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->reset([
            'editingId', 'menu_category_id', 'name_en', 'name_fr',
            'description_en', 'description_fr', 'price',
            'is_vegetarian', 'is_spicy', 'is_featured',
        ]);
        $this->resetErrorBag();
    }

    public function render()
    {
        $categories = MenuCategory::orderBy('sort_order')->get();
        $grouped = MenuItem::with('category')->orderBy('sort_order')->get()->groupBy('menu_category_id');

        return view('livewire.admin.menu-item-manager', [
            'categories' => $categories,
            'grouped' => $grouped,
            'totalItems' => $grouped->flatten()->count(),
        ]);
    }
}
