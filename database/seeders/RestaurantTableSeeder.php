<?php

namespace Database\Seeders;

use App\Models\RestaurantTable;
use Illuminate\Database\Seeder;

class RestaurantTableSeeder extends Seeder
{
    public function run(): void
    {
        // An organic-looking room: window line along the top, the main floor in
        // the middle, a bar column on the right, a terrace along the bottom.
        // pos_x / pos_y are percentages of the floor-plan box.
        $tables = [
            // label, seats, shape, zone, x, y
            ['T1', 2, 'round', 'window', 14, 16],
            ['T2', 2, 'round', 'window', 33, 14],
            ['T3', 4, 'square', 'window', 54, 16],
            ['T4', 2, 'round', 'window', 74, 14],

            ['T5', 4, 'round', 'main', 18, 44],
            ['T6', 4, 'square', 'main', 39, 46],
            ['T7', 6, 'round', 'main', 61, 44],
            ['T8', 2, 'round', 'main', 80, 46],

            ['T9', 2, 'round', 'main', 20, 67],
            ['T10', 4, 'round', 'main', 42, 68],
            ['T11', 4, 'square', 'main', 63, 67],

            ['B1', 2, 'square', 'bar', 93, 30],
            ['B2', 2, 'square', 'bar', 93, 55],

            ['P1', 4, 'round', 'terrace', 30, 88],
            ['P2', 6, 'round', 'terrace', 58, 88],
            ['P3', 4, 'round', 'terrace', 80, 88],
        ];

        foreach ($tables as $i => [$label, $seats, $shape, $zone, $x, $y]) {
            RestaurantTable::updateOrCreate(
                ['label' => $label],
                ['seats' => $seats, 'shape' => $shape, 'zone' => $zone, 'pos_x' => $x, 'pos_y' => $y, 'sort' => $i],
            );
        }
    }
}
