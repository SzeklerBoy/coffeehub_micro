<?php

namespace Database\Seeders;

use App\Models\Translation;
use Illuminate\Database\Seeder;

class TranslationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Translation::factory()->create([
            'menu_item_id' => 1,
            'language_id' => 1,
            'name' => 'Espresso',
            'description' => 'Very good coffee',
            'category' => 'coffee',
        ]);

        Translation::factory()->create([
            'menu_item_id' => 2,
            'language_id' => 1,
            'name' => 'Latte',
            'description' => 'Smooth and creamy coffee',
            'category' => 'coffee',
        ]);

    }
}
