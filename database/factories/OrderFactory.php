<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Product;
use App\Enums\OrderStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => fake()->randomElement(User::all()),
            'product_id' => fake()->randomElement(Product::all()),
            'status' => OrderStatus::WAITING->value,
        ];
    }

    /**
     * Configure the model factory.
     */
    public function configure(): static
    {
        return $this->afterCreating(function (Order $order) {
            $values = [];
            foreach ($order->product->options ?? [] as $option) {
                $values[] = fake()->randomElement($option->values()->pluck('id')->toArray());
            }

            $order->optionValues()->sync($values);
        });
    }
}
