<?php

namespace Database\Factories;

use App\Models\Desk;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class OrderFactory extends Factory
{
    public function definition(): array
    {
        return [
            'uuid' => $this->faker->uuid(),
            'desk_id' => Desk::first()->id,
            'group_id' => null,
            'ordered_at' => Carbon::now(),
            'completed_at' => null,
            'description' => 'Auto generated',
            'waiter_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
            'totalPrepTime' => 0,
        ];
    }
}
