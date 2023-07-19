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

    public function status(OrderStatus $orderStatus): static
    {
        $data['status'] = $orderStatus->value;

        if ($orderStatus === OrderStatus::DELIVERED) {
            $data['delivered_at'] = now();
        }

        if ($orderStatus === OrderStatus::CANCELLED) {
            $data['cancelled_at'] = now();
        }

        return $this->state(fn (array $attributes) => $data);
    }
}
