<?php

namespace Database\Seeders;

use App\Models\Option;
use App\Models\OptionValue;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OptionValueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        OptionValue::insert([
            [
                'name' => 'skim',
                'created_at' => now(),
            ],
            [
                'name' => 'semi',
                'created_at' => now(),
            ],
            [
                'name' => 'whole',
                'created_at' => now(),
            ],
            [
                'name' => 'small',
                'created_at' => now(),
            ],
            [
                'name' => 'medium',
                'created_at' => now(),
            ],
            [
                'name' => 'large',
                'created_at' => now(),
            ],
            [
                'name' => 'single',
                'created_at' => now(),
            ],
            [
                'name' => 'double',
                'created_at' => now(),
            ],
            [
                'name' => 'triple',
                'created_at' => now(),
            ],
            [
                'name' => 'chocolate chip',
                'created_at' => now(),
            ],
            [
                'name' => 'ginger',
                'created_at' => now(),
            ],
            [
                'name' => 'take away',
                'created_at' => now(),
            ],
            [
                'name' => 'in shop',
                'created_at' => now(),
            ],
        ]);

        $optionValuesAssignmentsMap = [
            1 => [1, 2, 3],
            2 => [4, 5, 6],
            3 => [7, 8, 9],
            4 => [10, 11],
            5 => [12, 13],
        ];

        $options = Option::all();
        foreach ($options as $option) {
            $option->values()->sync($optionValuesAssignmentsMap[$option->id]);
        }
    }
}
