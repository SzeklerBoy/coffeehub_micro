<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        // TODO: Uncomment the following lines when the respective seeders are ready
        // $this->call(DeskSeeder::class);
        // $this->call(OrderSeeder::class);

        $this->call(LanguageSeeder::class);
        $this->call(MenuItemSeeder::class);

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'phone' => '0123456789',
            'is_active' => true,
        ]);

        User::factory()->create([
            'name' => 'Test Waitress',
            'email' => 'waitress@example.com',
            'password' => bcrypt('password'),
            'phone' => '0755555555',
            'role' => 'waitress',
            'is_active' => true,
        ]);

        User::factory()->create([
            'name' => 'Test Janitor',
            'email' => 'janitor@example.com',
            'password' => bcrypt('password'),
            'phone' => '0744444444',
            'role' => 'janitor',
            'is_active' => false,
        ]);
    }
}
