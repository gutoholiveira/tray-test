<?php

namespace Database\Seeders;

use App\Models\Sale;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            User::NAME  => 'Tray',
            User::EMAIL => 'tray@test.com',
        ]);

        // Create 5 rows into sellers table.
        Seller::factory(5)->create();

        // Create 30 rows into sales table.
        Sale::factory(30)->create();
    }
}
