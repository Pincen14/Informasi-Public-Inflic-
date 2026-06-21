<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::create([
            'name' => 'Admin Inflic',
            'username' => 'admin',
            'email' => 'admin@admin.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'phone' => '081234567890',
            'role' => 'admin',
        ]);

        \App\Models\User::create([
            'name' => 'User Inflic',
            'username' => 'user',
            'email' => 'user@student.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'phone' => '081234567891',
            'role' => 'user',
        ]);

        $this->call(ItemSeeder::class);
    }
}
