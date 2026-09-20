<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderPlacedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Order $order)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $order = $this->order;

        return (new MailMessage)
            ->subject("Order Confirmation — {$order->order_number}")
            ->view('emails.notification', [
                'title' => 'Thank You For Your Order!',
                'greeting' => 'Hello'.($order->user?->name ? " {$order->user->name}" : '').',',
                'lines' => [
                    "We've received your order and started processing it right away.",
                    'You will receive another email as soon as your files are ready for download.',
                    '',
                    ...$order->items->map(fn ($item) => '• '.$item->quantity.' × '.$item->name.' — $'.number_format((float) $item->total_price, 2))->all(),
                ],
                'details' => [
                    'Order Number' => $order->order_number,
                    'Items' => $order->items->sum('quantity'),
                    'Total' => '$'.number_format((float) $order->total, 2),
                    'Placed At' => $order->created_at?->format('M d, Y H:i'),
                ],

                'actionText' => 'View My Order',
                'actionUrl' => $order->user
                    ? route('account.orders.show', $order)
                    : route('account.purchases'),
                'outro' => 'If you have any questions about this order, simply reply to this email.',
            ]);
    }
}
