<?php
// database/seeders/UserSeeder.php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Usuario dueño
        User::create([
            'name' => 'Dueño Kiosko',
            'email' => 'dueno@kiosko.com',
            'password' => Hash::make('password'),
            'role' => 'owner',
            'is_active' => true,
        ]);

        // Usuario empleado
        User::create([
            'name' => 'Empleado Kiosko',
            'email' => 'empleado@kiosko.com',
            'password' => Hash::make('password'),
            'role' => 'employee',
            'is_active' => true,
        ]);
    }
}