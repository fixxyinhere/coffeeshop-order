<?php

namespace Database\Seeders;

use App\Models\Table;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tables = ['A1', 'A2', 'A3', 'A4', 'B1', 'B2', 'B3', 'B4'];

        foreach ($tables as $number) {
            Table::create([
                'table_number' => $number,
                'qr_code_token' => Str::random(40),
                'is_active' => true,
            ]);
        }
    }
}
