<?php

namespace Tests\Feature\Admin;

use App\Livewire\Admin\ReservationManager;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ReservationManagerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_filters_reservations_by_search_term(): void
    {
        $admin = User::factory()->create();

        Reservation::create([
            'name' => 'Giorgi Beridze', 'email' => 'giorgi@example.com', 'phone' => '000',
            'date' => now()->addDays(2)->toDateString(), 'time' => '19:00', 'guests' => 2,
        ]);
        Reservation::create([
            'name' => 'Ana Kapanadze', 'email' => 'ana@example.com', 'phone' => '000',
            'date' => now()->addDays(2)->toDateString(), 'time' => '19:00', 'guests' => 2,
        ]);

        Livewire::actingAs($admin)
            ->test(ReservationManager::class)
            ->set('search', 'Giorgi')
            ->assertSee('Giorgi Beridze')
            ->assertDontSee('Ana Kapanadze');
    }

    public function test_it_filters_reservations_by_status(): void
    {
        $admin = User::factory()->create();

        Reservation::create([
            'name' => 'Pending One', 'email' => 'p@example.com', 'phone' => '000',
            'date' => now()->addDays(2)->toDateString(), 'time' => '19:00', 'guests' => 2,
            'status' => Reservation::STATUS_PENDING,
        ]);
        Reservation::create([
            'name' => 'Cancelled One', 'email' => 'c@example.com', 'phone' => '000',
            'date' => now()->addDays(2)->toDateString(), 'time' => '19:00', 'guests' => 2,
            'status' => Reservation::STATUS_CANCELLED,
        ]);

        Livewire::actingAs($admin)
            ->test(ReservationManager::class)
            ->set('statusFilter', 'cancelled')
            ->assertSee('Cancelled One')
            ->assertDontSee('Pending One');
    }

    public function test_admin_can_confirm_a_pending_reservation(): void
    {
        $admin = User::factory()->create();

        $reservation = Reservation::create([
            'name' => 'To Confirm', 'email' => 'confirm@example.com', 'phone' => '000',
            'date' => now()->addDays(2)->toDateString(), 'time' => '19:00', 'guests' => 2,
            'status' => Reservation::STATUS_PENDING,
        ]);

        Livewire::actingAs($admin)
            ->test(ReservationManager::class)
            ->call('setStatus', $reservation->id, Reservation::STATUS_CONFIRMED)
            ->assertOk();

        $this->assertSame(Reservation::STATUS_CONFIRMED, $reservation->fresh()->status);
    }

    public function test_view_action_selects_a_reservation_for_the_detail_panel(): void
    {
        $admin = User::factory()->create();

        $reservation = Reservation::create([
            'name' => 'Detail Target', 'email' => 'detail@example.com', 'phone' => '000',
            'date' => now()->addDays(2)->toDateString(), 'time' => '19:00', 'guests' => 2,
        ]);

        Livewire::actingAs($admin)
            ->test(ReservationManager::class)
            ->call('view', $reservation->id)
            ->assertSet('selectedId', $reservation->id)
            ->assertSee('Detail Target');
    }
}
