<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Enums\OrderStatus;
use App\Http\Resources\OrderResource;
use App\Http\Requests\OrderRequest;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends Controller
{
    public function store(OrderRequest $request): JsonResponse
    {
        $order = null;
        \DB::transaction(function () use ($request, &$order) {
            $order = Order::create([
                'user_id' => $request->user()->id,
                'product_id' => $request->validated('product_id'),
                'status' => OrderStatus::WAITING->value,
            ]);

            $order->optionValues()->sync($request->getOptionValuesId());
        });

        return ! is_null($order)
            ? $this->jsonResponse('Success!', OrderResource::make($order), Response::HTTP_CREATED)
            : $this->jsonResponse('Error!', null, Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function show(Order $order): JsonResponse
    {
        return $this->jsonResponse('Success!', OrderResource::make($order));
    }
}
