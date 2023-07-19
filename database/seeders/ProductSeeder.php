<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::insert([
            [
                'name' => 'Latte',
                'description' => fake()->sentence,
                'price' => random_int(1, 9) . '0000',
                'created_at' => now(),
            ],
            [
                'name' => 'Cappuccino',
                'description' => fake()->sentence,
                'price' => random_int(1, 9) . '0000',
                'created_at' => now(),
            ],
            [
                'name' => 'Espresso',
                'description' => fake()->sentence,
                'price' => random_int(1, 9) . '0000',
                'created_at' => now(),
            ],
            [
                'name' => 'Tea',
                'description' => fake()->sentence,
                'price' => random_int(1, 9) . '0000',
                'created_at' => now(),
            ],
            [
                'name' => 'Hot Chocolate',
                'description' => fake()->sentence,
                'price' => random_int(1, 9) . '0000',
                'created_at' => now(),
            ],
            [
                'name' => 'Cookie',
                'description' => fake()->sentence,
                'price' => random_int(1, 9) . '0000',
                'created_at' => now(),
            ],
        ]);
    }
}
