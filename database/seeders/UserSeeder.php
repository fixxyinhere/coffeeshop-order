<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin Utama',
            'email' => 'admin@coffeeshop.com',
            'password' => 'password',
            'role' => 'admin',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Kasir Utama',
            'email' => 'kasir@coffeeshop.com',
            'password' => 'password',
            'role' => 'kasir',
            'is_active' => true,
        ]);
    }
}
