<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Seed the admin users.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'odin.perica@gmail.com'],
            [
                'name' => 'Odin',
                'password' => Hash::make(env('ADMIN1_PASSWORD')),
            ],
        );

        User::updateOrCreate(
            ['email' => 'admin@judo-bura.com.hr'],
            [
                'name' => 'Damin',
                'password' => Hash::make(env('ADMIN2_PASSWORD')),
            ],
        );
    }
}
