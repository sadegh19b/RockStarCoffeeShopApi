<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Enums\OrderStatus;
use App\Http\Resources\OrderResource;
use App\Http\Requests\OrderStatusUpdateRequest;
use Illuminate\Http\JsonResponse;

class OrderStatusController extends Controller
{
    public function update(Order $order, OrderStatusUpdateRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['delivered_at'] = null;
        $data['cancelled_at'] = null;

        if ($data['status'] === OrderStatus::DELIVERED->value) {
            $data['delivered_at'] = now();
        }

        $order->update($data);

        return $this->jsonResponse('Success!', OrderResource::make($order));
    }

    public function updateAll(): JsonResponse
    {
        Order::where('status', OrderStatus::WAITING->value)
            ->update([
                'cancelled_at' => now(),
            ]);

        return $this->jsonResponse('Success!');
    }
}
