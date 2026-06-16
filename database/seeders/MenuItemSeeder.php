<?php

namespace Database\Seeders;

use App\Models\MenuItem;
use Illuminate\Database\Seeder;

class MenuItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $placeholder = 'https://placehold.co/400x300';

        $items = [
            // Kopi (category_id: 1)
            [
                'category_id' => 1,
                'name' => 'Espresso',
                'slug' => 'espresso',
                'description' => 'Kopi hitam pekat yang dibuat dengan menekan air panas melalui bubuk kopi halus. Cita rasa kuat dan intense.',
                'price' => 18000,
                'image' => $placeholder,
                'sort_order' => 1,
            ],
            [
                'category_id' => 1,
                'name' => 'Americano',
                'slug' => 'americano',
                'description' => 'Espresso yang ditambahkan air panas, menghasilkan kopi dengan rasa lebih ringan namun tetap kaya.',
                'price' => 22000,
                'image' => $placeholder,
                'sort_order' => 2,
            ],
            [
                'category_id' => 1,
                'name' => 'Cappuccino',
                'slug' => 'cappuccino',
                'description' => 'Perpaduan espresso, susu steamed, dan foam susu yang lembut di atasnya.',
                'price' => 28000,
                'image' => $placeholder,
                'sort_order' => 3,
            ],
            [
                'category_id' => 1,
                'name' => 'Caramel Latte',
                'slug' => 'caramel-latte',
                'description' => 'Latte dengan tambahan sirup caramel manis yang creamy dan memanjakan lidah.',
                'price' => 32000,
                'image' => $placeholder,
                'sort_order' => 4,
            ],
            [
                'category_id' => 1,
                'name' => 'Kopi Susu Gula Aren',
                'slug' => 'kopi-susu-gula-aren',
                'description' => 'Kopi susu kekinian dengan gula aren asli, manis legit dan creamy.',
                'price' => 28000,
                'image' => $placeholder,
                'sort_order' => 5,
            ],
            // Non-Kopi (category_id: 2)
            [
                'category_id' => 2,
                'name' => 'Matcha Latte',
                'slug' => 'matcha-latte',
                'description' => 'Minuman berbasis matcha Jepang berkualitas tinggi, dipadukan dengan susu segar.',
                'price' => 30000,
                'image' => $placeholder,
                'sort_order' => 1,
            ],
            [
                'category_id' => 2,
                'name' => 'Teh Tarik',
                'slug' => 'teh-tarik',
                'description' => 'Teh susu khas Nusantara yang disajikan dengan teknik ditarik, menghasilkan rasa creamy dan legit.',
                'price' => 18000,
                'image' => $placeholder,
                'sort_order' => 2,
            ],
            [
                'category_id' => 2,
                'name' => 'Cokelat Panas',
                'slug' => 'cokelat-panas',
                'description' => 'Minuman cokelat panas yang rich dan creamy, sempurna untuk menemani hari santai.',
                'price' => 25000,
                'image' => $placeholder,
                'sort_order' => 3,
            ],
            // Makanan (category_id: 3)
            [
                'category_id' => 3,
                'name' => 'Roti Bakar Keju',
                'slug' => 'roti-bakar-keju',
                'description' => 'Roti tawar panggang dengan lelehan keju mozzarella yang gurih dan nikmat.',
                'price' => 22000,
                'image' => $placeholder,
                'sort_order' => 1,
            ],
            [
                'category_id' => 3,
                'name' => 'Croissant Butter',
                'slug' => 'croissant-butter',
                'description' => 'Croissant panggang renyah dengan olesan butter berkualitas. Lembut di dalam, renyah di luar.',
                'price' => 25000,
                'image' => $placeholder,
                'sort_order' => 2,
            ],
            // Snack (category_id: 4)
            [
                'category_id' => 4,
                'name' => 'Kentang Goreng',
                'slug' => 'kentang-goreng',
                'description' => 'Kentang goreng renyah dengan taburan garam dan bumbu pilihan. Cocok sebagai teman ngopi.',
                'price' => 20000,
                'image' => $placeholder,
                'sort_order' => 1,
            ],
            [
                'category_id' => 4,
                'name' => 'Pisang Goreng Keju',
                'slug' => 'pisang-goreng-keju',
                'description' => 'Pisang goreng crispy dengan topping keju parut dan taburan meses. Manis dan gurih!',
                'price' => 18000,
                'image' => $placeholder,
                'sort_order' => 2,
            ],
        ];

        foreach ($items as $item) {
            MenuItem::create($item);
        }
    }
}
