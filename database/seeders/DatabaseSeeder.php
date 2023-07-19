<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'is_admin' => true,
        ]);

        \App\Models\User::factory()->create([
            'name' => 'Test Customer',
            'email' => 'test@example.com',
        ]);

        $this->call(ProductSeeder::class);
        $this->call(OptionSeeder::class);
        $this->call(OptionValueSeeder::class);
    }
}
