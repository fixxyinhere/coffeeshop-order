<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Kopi', 'slug' => 'kopi', 'sort_order' => 1],
            ['name' => 'Non-Kopi', 'slug' => 'non-kopi', 'sort_order' => 2],
            ['name' => 'Makanan', 'slug' => 'makanan', 'sort_order' => 3],
            ['name' => 'Snack', 'slug' => 'snack', 'sort_order' => 4],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
