<?php

namespace Tests\Feature;

use App\Livewire\ReservationForm;
use App\Models\Reservation;
use App\Models\RestaurantTable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ReservationTableTest extends TestCase
{
    use RefreshDatabase;

    private function nextOpenDate(): string
    {
        $closed = array_map('intval', (array) config('restaurant.reservation.closed_days'));
        $date = now()->addDays(3);
        while (in_array($date->dayOfWeek, $closed, true)) {
            $date->addDay();
        }

        return $date->toDateString();
    }

    private function table(string $label, int $seats): RestaurantTable
    {
        return RestaurantTable::create([
            'label' => $label, 'seats' => $seats, 'shape' => 'round',
            'zone' => 'main', 'pos_x' => 20, 'pos_y' => 20, 'sort' => 1,
        ]);
    }

    public function test_available_tables_excludes_too_small_and_already_booked(): void
    {
        $date = $this->nextOpenDate();
        $small = $this->table('S', 2);
        $big = $this->table('B', 6);

        // A party of 4 can only take the 6-top.
        $available = Reservation::availableTables($date, '19:00', 4);
        $this->assertTrue($available->contains('id', $big->id));
        $this->assertFalse($available->contains('id', $small->id));

        // Book the 6-top for that slot; it should drop out of availability.
        Reservation::create([
            'name' => 'Held', 'email' => 'h@example.com', 'phone' => '0',
            'date' => $date, 'time' => '19:00', 'guests' => 4,
            'table_id' => $big->id, 'status' => Reservation::STATUS_CONFIRMED,
        ]);

        $this->assertFalse(Reservation::availableTables($date, '19:00', 4)->contains('id', $big->id));
    }

    public function test_create_with_capacity_check_refuses_a_table_already_taken(): void
    {
        $date = $this->nextOpenDate();
        $table = $this->table('T', 4);

        Reservation::create([
            'name' => 'First', 'email' => 'first@example.com', 'phone' => '0',
            'date' => $date, 'time' => '19:00', 'guests' => 2,
            'table_id' => $table->id, 'status' => Reservation::STATUS_CONFIRMED,
        ]);

        // Plenty of seats remain in the room, but this specific table is taken.
        $result = Reservation::createWithCapacityCheck([
            'name' => 'Second', 'email' => 'second@example.com', 'phone' => '0',
            'date' => $date, 'time' => '19:00', 'guests' => 2, 'table_id' => $table->id,
        ]);

        $this->assertNull($result, 'Booking a table already taken for the slot must be refused.');
        $this->assertDatabaseMissing('reservations', ['email' => 'second@example.com']);
    }

    public function test_cancelling_frees_the_table_again(): void
    {
        $date = $this->nextOpenDate();
        $table = $this->table('T', 4);

        $res = Reservation::create([
            'name' => 'Will Cancel', 'email' => 'c@example.com', 'phone' => '0',
            'date' => $date, 'time' => '19:00', 'guests' => 2,
            'table_id' => $table->id, 'status' => Reservation::STATUS_CONFIRMED,
        ]);

        $this->assertFalse(Reservation::availableTables($date, '19:00', 2)->contains('id', $table->id));

        $res->update(['status' => Reservation::STATUS_CANCELLED]);

        $this->assertTrue(Reservation::availableTables($date, '19:00', 2)->contains('id', $table->id));
    }

    public function test_a_party_cannot_pick_a_table_too_small(): void
    {
        $date = $this->nextOpenDate();
        $small = $this->table('S', 2);

        Livewire::test(ReservationForm::class)
            ->set('date', $date)
            ->set('guests', 4)
            ->set('time', '19:00')
            ->call('goToTables')
            ->assertSet('step', 2)
            ->set('tableId', $small->id)
            ->call('goToDetails')
            ->assertHasErrors('tableId');
    }

    public function test_a_party_can_pick_a_free_table_and_reach_details(): void
    {
        $date = $this->nextOpenDate();
        $big = $this->table('B', 6);

        Livewire::test(ReservationForm::class)
            ->set('date', $date)
            ->set('guests', 4)
            ->set('time', '19:00')
            ->call('goToTables')
            ->set('tableId', $big->id)
            ->call('goToDetails')
            ->assertHasNoErrors()
            ->assertSet('step', 3);
    }
}
