<?php

namespace App\Notifications;

use App\Models\Order;
use App\Models\Quote;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class QuoteConvertedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Quote $quote, public Order $order)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'quote_converted',
            'title' => 'Quote Converted to Order',
            'message' => "Your quote #{$this->quote->id} was converted to order {$this->order->order_number}",
            'quote_id' => $this->quote->id,
            'order_id' => $this->order->id,
            'url' => route('account.orders.show', $this->order),
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $quote = $this->quote;
        $order = $this->order;

        return (new MailMessage)
            ->subject("Your Order {$order->order_number} Has Been Created — " . config('app.name', 'PhotoLabe'))
            ->view('emails.notification', [
                'title' => 'Your Quote Was Converted to an Order',
                'greeting' => "Hello {$notifiable->name},",
                'lines' => [
                    "Great news! Your quote #{$quote->id} has been converted into order {$order->order_number}.",
                    'Our team will begin working on your project shortly. You can track progress, send messages, and request revisions from your dashboard.',
                ],
                'details' => [
                    'Order Number' => $order->order_number,
                    'Service' => $order->service?->title ?? 'General',
                    'Quantity' => $order->quantity,
                    'Total' => money($order->total, $order->currency),
                    'Deadline' => $order->deadline?->format('M d, Y') ?? 'Flexible',
                ],
                'actionText' => 'View Order',
                'actionUrl' => route('account.orders.show', $order),
                'outro' => 'We will notify you as soon as your order status changes.',
            ]);
    }
}