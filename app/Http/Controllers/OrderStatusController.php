<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Enums\OrderStatus;
use App\Http\Resources\OrderResource;
use App\Http\Requests\OrderStatusUpdateRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class OrderStatusController extends Controller
{
    public function update(Order $order, OrderStatusUpdateRequest $request): JsonResponse
    {
        if ($order->cancelled_at || $order->delivered_at) {
            throw ValidationException::withMessages([
                'status' => sprintf(
                    'The %s order cannot be change status.',
                    $order->cancelled_at ? 'canceled' : 'delivered'
                ),
            ]);
        }

        $data = $request->validated();

        if ($data['status'] === OrderStatus::DELIVERED->value) {
            $data['delivered_at'] = now();
        }

        if ($data['status'] === OrderStatus::CANCELLED->value) {
            $data['cancelled_at'] = now();
        }

        $order->update($data);

        return $this->jsonResponse('Success!', OrderResource::make($order));
    }

    public function updateAll(): JsonResponse
    {
        $orders = Order::where('status', OrderStatus::WAITING->value)->get();

        foreach ($orders as $order) {
            $order->update([
                'status' => OrderStatus::CANCELLED,
                'cancelled_at' => now(),
            ]);
        }

        return $this->jsonResponse('Success!');
    }
}
