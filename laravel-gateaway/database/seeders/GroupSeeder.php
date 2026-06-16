<?php

namespace Database\Seeders;

use App\Models\Desk;
use App\Models\Group;
use Illuminate\Database\Seeder;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $groups = Group::factory()->count(5)->create();

        // Create some desks
        $desks = Desk::factory()->count(10)->create();

        // Seed the groups_desks pivot table
        $groups->each(function ($group) use ($desks) {
            // Randomly attach 1 to 3 desks to each group
            $group->desks()->attach(
                $desks->random(rand(1, 3))->pluck('id')->toArray()
            );
        });
    }
}
