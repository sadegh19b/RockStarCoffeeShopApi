<?php

namespace App\Observers;

use App\Models\Order;
use App\Notifications\OrderChangeStatusNotification;

class OrderObserver
{
    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        if ($order->wasChanged('status')) {
            $order->user->notify(new OrderChangeStatusNotification($order, $order->getOriginal('status')));
        }
    }
}
