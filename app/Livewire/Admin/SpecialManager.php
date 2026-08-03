<?php

namespace App\Livewire\Admin;

use App\Models\Special;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.dashboard')]
class SpecialManager extends Component
{
    public bool $showModal = false;

    public ?int $editingId = null;

    public string $title_en = '';

    public string $title_fr = '';

    public string $description_en = '';

    public string $description_fr = '';

    public string $badge = '';

    public string $starts_on = '';

    public string $ends_on = '';

    public bool $is_active = true;

    protected function rules(): array
    {
        return [
            'title_en' => ['required', 'string', 'max:150'],
            'title_fr' => ['required', 'string', 'max:150'],
            'description_en' => ['nullable', 'string', 'max:500'],
            'description_fr' => ['nullable', 'string', 'max:500'],
            'badge' => ['nullable', 'string', 'max:40'],
            'starts_on' => ['nullable', 'date'],
            'ends_on' => ['nullable', 'date', 'after_or_equal:starts_on'],
            'is_active' => ['boolean'],
        ];
    }

    public function create(): void
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit(int $id): void
    {
        $special = Special::findOrFail($id);

        $this->editingId = $special->id;
        $this->title_en = $special->title_en;
        $this->title_fr = $special->title_fr;
        $this->description_en = (string) $special->description_en;
        $this->description_fr = (string) $special->description_fr;
        $this->badge = (string) $special->badge;
        $this->starts_on = $special->starts_on?->toDateString() ?? '';
        $this->ends_on = $special->ends_on?->toDateString() ?? '';
        $this->is_active = $special->is_active;

        $this->showModal = true;
    }

    public function save(): void
    {
        $validated = $this->validate();
        $validated['starts_on'] = $validated['starts_on'] ?: null;
        $validated['ends_on'] = $validated['ends_on'] ?: null;
        $validated['badge'] = $validated['badge'] ?: null;

        Special::updateOrCreate(['id' => $this->editingId], $validated);

        $this->showModal = false;
        $this->resetForm();
    }

    public function toggle(int $id): void
    {
        $special = Special::findOrFail($id);
        $special->update(['is_active' => ! $special->is_active]);
    }

    public function delete(int $id): void
    {
        Special::whereKey($id)->delete();
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->reset([
            'editingId', 'title_en', 'title_fr', 'description_en', 'description_fr',
            'badge', 'starts_on', 'ends_on', 'is_active',
        ]);
        $this->is_active = true;
        $this->resetErrorBag();
    }

    public function render()
    {
        return view('livewire.admin.special-manager', [
            'specials' => Special::orderBy('sort')->orderByDesc('id')->get(),
        ]);
    }
}
