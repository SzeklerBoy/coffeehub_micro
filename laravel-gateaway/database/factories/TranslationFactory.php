<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TranslationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->colorName(),
            'description' => $this->faker->text(),
            'category' => $this->faker->randomElement(['coffee', 'tea', 'food', 'snack', 'alcohol', 'refreshment']),
        ];
    }
}
