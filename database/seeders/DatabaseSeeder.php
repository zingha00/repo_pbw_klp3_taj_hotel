<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoomSeeder::class,
        ]);

        // Create admin user
        \App\Models\User::create([
            'name' => 'Admin Hotel',
            'email' => 'admin@hotel.com',
            'password' => bcrypt('password'),
            'role' => 'admin'
        ]);

        // Create test customer
        \App\Models\User::create([
            'name' => 'Test User',
            'email' => 'user@example.com',
            'password' => bcrypt('password'),
            'role' => 'customer'
        ]);
    }
}