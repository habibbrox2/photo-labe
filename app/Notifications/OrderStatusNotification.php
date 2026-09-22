<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /** The order status at the time the notification was created. */
    public string $status;

    public function __construct(public Order $order, public ?string $oldStatus = null)
    {
        // Freeze the status so queued processing reports the state at creation.
        $this->status = $order->status;
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'order_status_changed',
            'title' => $this->isCompleted() ? 'Order Completed' : 'Order Status Updated',
            'message' => $this->buildMessage(),
            'order_id' => $this->order->id,
            'url' => route('account.orders.show', $this->order),
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $order = $this->order;

        return (new MailMessage)
            ->subject($this->isCompleted()
                ? "Your Order {$order->order_number} Is Completed 🎉"
                : "Order {$order->order_number} Status Update — " . config('app.name', 'PhotoLabe'))
            ->view('emails.notification', [
                'title' => $this->isCompleted() ? 'Your Order Is Complete!' : 'Order Status Update',
                'greeting' => "Hello {$notifiable->name},",
                'lines' => [$this->buildMessage()],
                'details' => [
                    'Order Number' => $order->order_number,
                    'Service' => $order->service?->title ?? 'General',
                    'Status' => ucfirst(str_replace('_', ' ', $this->status)),
                    'Total' => money($order->total, $order->currency),
                    'Completed At' => $order->completed_at?->format('M d, Y H:i') ?? null,
                ],
                'actionText' => 'View Order',
                'actionUrl' => route('account.orders.show', $order),
                'outro' => $this->isCompleted()
                    ? 'Thank you for choosing us! If you need any revisions, you can request them from your order page.'
                    : 'We will keep you updated as your order progresses.',
            ]);
    }

    protected function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    protected function buildMessage(): string
    {
        $order = $this->order;

        return match ($this->status) {
            'confirmed' => "Your order {$order->order_number} has been confirmed.",
            'paid' => "Payment for order {$order->order_number} has been received.",
            'processing' => "We've started working on order {$order->order_number}.",
            'quality_check' => "Order {$order->order_number} is in quality check — final review before delivery.",
            'revision' => "A revision was requested on order {$order->order_number}.",
            'completed' => "Your order {$order->order_number} is complete! Your edited files are ready for download.",
            'cancelled' => "Order {$order->order_number} has been cancelled.",
            default => "The status of order {$order->order_number} was updated to " . ucfirst(str_replace('_', ' ', $this->status)) . '.',
        };
    }
}