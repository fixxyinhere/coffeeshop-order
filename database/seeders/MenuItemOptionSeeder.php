<?php

namespace Database\Seeders;

use App\Models\MenuItem;
use App\Models\MenuItemOption;
use Illuminate\Database\Seeder;

class MenuItemOptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Dapatkan semua item minuman (kategori Kopi dan Non-Kopi)
        $drinkCategories = [1, 2]; // Kopi, Non-Kopi
        $drinkItems = MenuItem::whereIn('category_id', $drinkCategories)->get();

        $options = [
            [
                'group' => 'Ukuran',
                'values' => [
                    ['value' => 'Regular', 'price_modifier' => 0],
                    ['value' => 'Large', 'price_modifier' => 5000],
                ],
            ],
            [
                'group' => 'Gula',
                'values' => [
                    ['value' => 'Normal', 'price_modifier' => 0],
                    ['value' => 'Sedikit', 'price_modifier' => 0],
                    ['value' => 'Tanpa Gula', 'price_modifier' => 0],
                ],
            ],
            [
                'group' => 'Es',
                'values' => [
                    ['value' => 'Normal', 'price_modifier' => 0],
                    ['value' => 'Sedikit', 'price_modifier' => 0],
                    ['value' => 'Tanpa Es', 'price_modifier' => 0],
                ],
            ],
        ];

        foreach ($drinkItems as $item) {
            foreach ($options as $option) {
                foreach ($option['values'] as $value) {
                    MenuItemOption::create([
                        'menu_item_id' => $item->id,
                        'option_group' => $option['group'],
                        'option_value' => $value['value'],
                        'price_modifier' => $value['price_modifier'],
                    ]);
                }
            }
        }
    }
}
