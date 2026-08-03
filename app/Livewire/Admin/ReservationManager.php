<?php

namespace App\Livewire\Admin;

use App\Models\Reservation;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.dashboard')]
class ReservationManager extends Component
{
    use WithPagination;

    #[Url]
    public string $statusFilter = 'all';

    #[Url]
    public string $search = '';

    #[Url]
    public string $dateFrom = '';

    #[Url]
    public string $dateTo = '';

    public ?int $selectedId = null;

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingDateFrom(): void
    {
        $this->resetPage();
    }

    public function updatingDateTo(): void
    {
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->reset(['statusFilter', 'search', 'dateFrom', 'dateTo']);
        $this->resetPage();
    }

    public function view(int $reservationId): void
    {
        $this->selectedId = $reservationId;
    }

    public function closeDetails(): void
    {
        $this->selectedId = null;
    }

    public function setStatus(int $reservationId, string $status): void
    {
        Reservation::whereKey($reservationId)->update(['status' => $status]);
    }

    public function render()
    {
        $reservations = Reservation::query()
            ->with('table')
            ->when($this->statusFilter !== 'all', fn ($query) => $query->where('status', $this->statusFilter))
            ->when($this->search !== '', function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', "%{$this->search}%")
                        ->orWhere('email', 'like', "%{$this->search}%")
                        ->orWhere('phone', 'like', "%{$this->search}%");
                });
            })
            ->when($this->dateFrom !== '', fn ($query) => $query->whereDate('date', '>=', $this->dateFrom))
            ->when($this->dateTo !== '', fn ($query) => $query->whereDate('date', '<=', $this->dateTo))
            ->orderByDesc('date')
            ->orderByDesc('time')
            ->paginate(12);

        $selected = $this->selectedId ? Reservation::find($this->selectedId) : null;

        return view('livewire.admin.reservation-manager', [
            'reservations' => $reservations,
            'selected' => $selected,
        ]);
    }
}
