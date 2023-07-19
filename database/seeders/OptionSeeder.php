<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Option;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Option::insert([
            [
                'name' => 'Milk',
                'created_at' => now(),
            ],
            [
                'name' => 'Size',
                'created_at' => now(),
            ],
            [
                'name' => 'Shots',
                'created_at' => now(),
            ],
            [
                'name' => 'Kind',
                'created_at' => now(),
            ],
            [
                'name' => 'Consume location',
                'created_at' => now(),
            ],
        ]);

        $productOptionsAssignmentsMap = [
            1 => [1, 5],
            2 => [2, 5],
            3 => [5],
            4 => [3, 5],
            5 => [2, 5],
            6 => [4, 5],
        ];

        $products = Product::all();
        foreach ($products as $product) {
            $product->options()->sync($productOptionsAssignmentsMap[$product->id]);
        }
    }
}
