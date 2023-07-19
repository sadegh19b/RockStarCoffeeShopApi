<?php

use App\Models\User;
use App\Models\Order;
use App\Enums\OrderStatus;
use App\Notifications\OrderChangeStatusNotification;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use LazilyRefreshDatabase;

    protected User $admin;
    protected User $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed();

        $this->admin = User::find(1);
        $this->user = User::find(2);
    }

    /** @test */
    public function the_user_cannot_order_a_product_when_data_is_incorrect(): void
    {
        $this->actingAs($this->user);

        // Missing one of the options
        $this->postJson(
            route('api.v1.order.store'),
            [
                'product_id' => '1',
                'option_1_option_value_id' => '2',
            ]
        )->assertUnprocessable();

        // Incorrect options value
        $this->postJson(
            route('api.v1.order.store'),
            [
                'product_id' => '1',
                'option_1_option_value_id' => '0',
                'option_5_option_value_id' => '0',
            ]
        )->assertUnprocessable();

        // Missing product id
        $this->postJson(
            route('api.v1.order.store'),
            [
                'option_1_option_value_id' => '2',
                'option_5_option_value_id' => '13',
            ]
        )->assertUnprocessable();

        // Incorrect product id
        $this->postJson(
            route('api.v1.order.store'),
            [
                'product_id' => '0',
                'option_1_option_value_id' => '2',
                'option_5_option_value_id' => '13',
            ]
        )->assertUnprocessable();
    }

    /** @test */
    public function the_user_can_order_a_product(): void
    {
        $this->actingAs($this->user);

        $response = $this->postJson(
            route('api.v1.order.store'),
            [
                'product_id' => '1',
                'option_1_option_value_id' => '2',
                'option_5_option_value_id' => '13',
            ]
        );

        $response->assertCreated();
        $this->assertDatabaseHas('orders', [
            'user_id' => $this->user->id,
            'product_id' => '1',
            'status' => OrderStatus::WAITING->value,
            'delivered_at' => null,
        ]);
        $this->assertDatabaseHas('order_option_value_assignments', [
            'order_id' => '1',
            'option_value_id' => '2',
        ]);
        $this->assertDatabaseHas('order_option_value_assignments', [
            'order_id' => '1',
            'option_value_id' => '13',
        ]);
    }

    /** @test */
    public function the_user_can_view_his_order(): void
    {
        $this->actingAs($this->user);
        $order = Order::factory()->create(['user_id' => $this->user->id]);

        $response = $this->getJson(route('api.v1.order.show', $order->id));

        $response->assertOk();
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message')
                ->has('data', function (AssertableJson $json) {
                    $json->has('id')
                        ->has('user_id')
                        ->has('product_id')
                        ->has('options')
                        ->has('status')
                        ->etc();
                });
        });
    }

    /** @test */
    public function the_user_cannot_view_another_users_order_only_admin_can_view(): void
    {
        $orderUser = Order::factory()->create(['user_id' => $this->user->id]);
        $orderAdmin = Order::factory()->create(['user_id' => $this->admin->id]);

        $this->actingAs($this->user)
            ->getJson(route('api.v1.order.show', $orderAdmin->id))
            ->assertForbidden();

        $this->actingAs($this->admin)
            ->getJson(route('api.v1.order.show', $orderUser->id))
            ->assertOk();
    }

    /** @test */
    public function the_user_cannot_change_order_status(): void
    {
        $this->actingAs($this->user);
        $order = Order::factory()->create();

        $this->patchJson(route('api.v1.order.status.update', $order->id), [
            'status' => OrderStatus::PREPARATION->value
        ])->assertForbidden();

        // Also, User cannot cancel all waiting orders
        $this->patchJson(route('api.v1.orders.status.update.all'))->assertForbidden();
    }

    /** @test */
    public function the_admin_can_change_order_status(): void
    {
        \Notification::fake();
        $this->actingAs($this->admin);
        $order = Order::factory()->create();

        $response = $this->patchJson(route('api.v1.order.status.update', $order->id), [
            'status' => OrderStatus::DELIVERED->value
        ]);

        $response->assertOk();
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'product_id' => $order->product_id,
            'status' => OrderStatus::DELIVERED->value,
            'delivered_at' => $order->fresh()->delivered_at,
        ]);

        \Notification::assertSentTo([$order->user], OrderChangeStatusNotification::class);
    }

    /** @test */
    public function the_admin_cannot_change_order_status_when_incorrect_data(): void
    {
        $this->actingAs($this->admin);
        $order = Order::factory()->create();

        $this->patchJson(route('api.v1.order.status.update', $order->id), [
            'status' => '::test::'
        ])->assertUnprocessable();

        // Without status field
        $this->patchJson(route('api.v1.order.status.update', $order->id))->assertUnprocessable();
    }

    /** @test */
    public function the_admin_cannot_change_order_status_when_the_order_delivered_or_cancelled(): void
    {
        $this->actingAs($this->admin);
        $orderDelivered = Order::factory()->status(OrderStatus::DELIVERED)->create();
        $orderCancelled = Order::factory()->status(OrderStatus::CANCELLED)->create();

        $this->patchJson(route('api.v1.order.status.update', $orderDelivered->id), [
            'status' => OrderStatus::WAITING->value
        ])->assertUnprocessable();

        $this->patchJson(route('api.v1.order.status.update', $orderCancelled->id), [
            'status' => OrderStatus::WAITING->value
        ])->assertUnprocessable();
    }

    /** @test */
    public function the_admin_can_cancel_waiting_orders_status(): void
    {
        \Notification::fake();
        $this->actingAs($this->admin);
        $orderWaiting1 = Order::factory()->status(OrderStatus::WAITING)->create();
        $orderWaiting2 = Order::factory()->status(OrderStatus::WAITING)->create();
        $orderReady = Order::factory()->status(OrderStatus::READY)->create();

        $response = $this->patchJson(route('api.v1.orders.status.update.all'));

        $response->assertOk();
        $this->assertDatabaseHas('orders', [
            'id' => $orderWaiting1->id,
            'status' => OrderStatus::CANCELLED->value,
            'cancelled_at' => $orderWaiting1->fresh()->cancelled_at,
        ]);
        $this->assertDatabaseHas('orders', [
            'id' => $orderWaiting2->id,
            'status' => OrderStatus::CANCELLED->value,
            'cancelled_at' => $orderWaiting2->fresh()->cancelled_at,
        ]);
        $this->assertDatabaseHas('orders', [
            'id' => $orderReady->id,
            'status' => $orderReady->status,
            'cancelled_at' => null,
        ]);

        \Notification::assertSentTo(
            [$orderWaiting1->user, $orderWaiting2->user],
            OrderChangeStatusNotification::class
        );
    }
}
