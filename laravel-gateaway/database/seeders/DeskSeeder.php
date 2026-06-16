<?php

namespace Database\Seeders;

use App\Models\Desk;
use Illuminate\Database\Seeder;

class DeskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Desk::factory()->create([
            'is_occupied' => true,
            'number_of_seats' => fake()->numberBetween(1, 5),
            'description' => fake()->sentence(),
            'code' => fake()->randomNumber(5),
        ]);

        Desk::factory()
            ->count(10)
            ->create();
    }
}
