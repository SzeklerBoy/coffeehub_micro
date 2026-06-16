<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MenuItemFactory extends Factory
{
    public function definition(): array
    {
        $name = $this->faker->unique()->colorName();

        return [
            'quantity' => $this->faker->randomNumber(nbDigits: 2),
            'price' => $this->faker->randomDigit() + 2,
            'image_path' => "https://placehold.co/600x400?text={$name}",
            'ETAinMinutes' => $this->faker->randomDigit(),
        ];
    }
}
