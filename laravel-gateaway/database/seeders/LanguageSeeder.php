<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    public function run(): void
    {
        Language::factory()->create([
            'name' => 'English',
            'code' => 'en',
        ]);

        Language::factory()->create([
            'name' => 'Hungarian',
            'code' => 'hu',
        ]);

        Language::factory()->create([
            'name' => 'Romanian',
            'code' => 'ro',
        ]);
    }
}
