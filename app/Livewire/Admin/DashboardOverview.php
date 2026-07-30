<?php

namespace App\Livewire\Admin;

use App\Models\Reservation;
use Carbon\Carbon;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.dashboard')]
class DashboardOverview extends Component
{
    #[Computed]
    public function todayReservations()
    {
        return Reservation::whereDate('date', today())
            ->where('status', '!=', Reservation::STATUS_CANCELLED)
            ->get();
    }

    #[Computed]
    public function pendingCount(): int
    {
        return Reservation::where('status', Reservation::STATUS_PENDING)->count();
    }

    #[Computed]
    public function weekCount(): int
    {
        return Reservation::whereBetween('date', [today()->toDateString(), today()->addDays(6)->toDateString()])
            ->where('status', '!=', Reservation::STATUS_CANCELLED)
            ->count();
    }

    #[Computed]
    public function occupancy(): array
    {
        $today = $this->todayReservations;
        $capacity = config('restaurant.reservation.seats_per_slot');
        $rows = [];

        foreach (config('restaurant.reservation.service_hours') as $period) {
            [$start, $end] = $period;
            $cursor = Carbon::createFromFormat('H:i', $start);
            $endTime = Carbon::createFromFormat('H:i', $end);

            while ($cursor->lte($endTime)) {
                $value = $cursor->format('H:i');
                $booked = $today->filter(fn ($r) => Carbon::parse($r->time)->format('H:i') === $value)->sum('guests');

                if ($booked > 0) {
                    $rows[] = [
                        'time' => $value,
                        'booked' => $booked,
                        'capacity' => $capacity,
                        'percent' => min(100, round(($booked / $capacity) * 100)),
                    ];
                }

                $cursor->addMinutes(30);
            }
        }

        return $rows;
    }

    #[Computed]
    public function upcoming()
    {
        return Reservation::where('status', '!=', Reservation::STATUS_CANCELLED)
            ->where('date', '>=', today()->toDateString())
            ->orderBy('date')
            ->orderBy('time')
            ->limit(8)
            ->get();
    }

    public function render()
    {
        return view('livewire.admin.dashboard-overview', [
            'todayGuests' => $this->todayReservations->sum('guests'),
        ]);
    }
}
