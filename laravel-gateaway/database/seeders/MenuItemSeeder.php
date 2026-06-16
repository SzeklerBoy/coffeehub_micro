<?php

namespace Database\Seeders;

use App\Models\MenuItem;
use App\Models\Translation;
use Illuminate\Database\Seeder;

class MenuItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menuItems = [
            ['en' => ['name' => 'Espresso', 'description' => 'Strong black coffee shot', 'category' => 'Coffee'],
                'hu' => ['name' => 'Eszpresszó', 'description' => 'Erős fekete kávé', 'category' => 'Kávé']],
            ['en' => ['name' => 'Cappuccino', 'description' => 'Espresso with steamed milk foam', 'category' => 'Coffee'],
                'hu' => ['name' => 'Cappuccino', 'description' => 'Eszpresszó gőzölt tejhabbal', 'category' => 'Kávé']],
            ['en' => ['name' => 'Latte', 'description' => 'Mild coffee with milk', 'category' => 'Coffee'],
                'hu' => ['name' => 'Latte', 'description' => 'Lágy kávé tejjel', 'category' => 'Kávé']],
            ['en' => ['name' => 'Americano', 'description' => 'Diluted espresso with hot water', 'category' => 'Coffee'],
                'hu' => ['name' => 'Amerikai kávé', 'description' => 'Hígított eszpresszó forró vízzel', 'category' => 'Kávé']],
            ['en' => ['name' => 'Iced Coffee', 'description' => 'Cold brewed coffee with ice', 'category' => 'Cold Drinks'],
                'hu' => ['name' => 'Jeges kávé', 'description' => 'Hideg főzésű kávé jéggel', 'category' => 'Hideg italok']],
            ['en' => ['name' => 'Croissant', 'description' => 'Buttery French pastry', 'category' => 'Pastry'],
                'hu' => ['name' => 'Croissant', 'description' => 'Vajas francia péksütemény', 'category' => 'Péksütemény']],
            ['en' => ['name' => 'Muffin', 'description' => 'Soft baked dessert in various flavors', 'category' => 'Pastry'],
                'hu' => ['name' => 'Muffin', 'description' => 'Puha sült desszert különféle ízekben', 'category' => 'Péksütemény']],
            ['en' => ['name' => 'Tea', 'description' => 'Choice of herbal, green or black tea', 'category' => 'Hot Drinks'],
                'hu' => ['name' => 'Tea', 'description' => 'Gyógy-, zöld- vagy fekete tea választása', 'category' => 'Forró italok']],
            ['en' => ['name' => 'Hot Chocolate', 'description' => 'Rich chocolate drink with milk', 'category' => 'Hot Drinks'],
                'hu' => ['name' => 'Forró csokoládé', 'description' => 'Gazdag csokoládéital tejjel', 'category' => 'Forró italok']],
            ['en' => ['name' => 'Lemonade', 'description' => 'Freshly squeezed lemon drink', 'category' => 'Cold Drinks'],
                'hu' => ['name' => 'Limonádé', 'description' => 'Frissen facsart citromos ital', 'category' => 'Hideg italok']],
        ];

        foreach ($menuItems as $item) {
            $menuItem = MenuItem::create([
                'quantity' => rand(1, 10),
                'price' => rand(300, 1200) / 100, // e.g., 3.00 - 12.00
                'image_path' => null,
                'ETAinMinutes' => rand(2, 8),
            ]);

            Translation::create([
                'menu_item_id' => $menuItem->id,
                'language_id' => 1, // English
                'name' => $item['en']['name'],
                'description' => $item['en']['description'],
                'category' => $item['en']['category'],
            ]);

            Translation::create([
                'menu_item_id' => $menuItem->id,
                'language_id' => 2, // Hungarian
                'name' => $item['hu']['name'],
                'description' => $item['hu']['description'],
                'category' => $item['hu']['category'],
            ]);
        }
    }
}
