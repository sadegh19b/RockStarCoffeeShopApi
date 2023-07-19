<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderChangeStatusNotification extends Notification
{
    use Queueable;

    protected Order $order;
    protected string $oldStatus;

    /**
     * Create a new notification instance.
     */
    public function __construct(Order $order, string $oldStatus)
    {
        $this->order = $order;
        $this->oldStatus = $oldStatus;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $options = [];
        foreach ($this->order->optionValues as $optionValue) {
            $option = $optionValue->firstOption();

            $options[] = "{$option->name}: {$optionValue->name}";
        }

        return (new MailMessage)
            ->subject('Order Status Changed!')
            ->line('Your order status was changed.')
            ->line('Product: ' . $this->order->product->name)
            ->line('Options: ' . implode(' | ', $options))
            ->line('Order Price: ' . $this->order->product->price)
            ->line("Order status: change '{$this->oldStatus}' to '{$this->order->status}'");
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
