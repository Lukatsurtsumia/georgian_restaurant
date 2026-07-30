<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::updateOrCreate(
            ['email' => config('restaurant.admin_email')],
            [
                'name' => 'Tiflis Admin',
                'password' => Hash::make(config('restaurant.admin_password')),
                'email_verified_at' => now(),
            ]
        );

        // is_admin is intentionally not mass-assignable — forceFill it explicitly
        // here, the one trusted place admin accounts are ever created.
        $user->forceFill(['is_admin' => true])->save();
    }
}
