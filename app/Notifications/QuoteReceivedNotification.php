<?php

namespace App\Notifications;

use App\Models\Quote;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class QuoteReceivedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Quote $quote)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'quote_received',
            'title' => 'New Quote Request',
            'message' => "New quote request #{$this->quote->id} from {$this->quote->name}",
            'quote_id' => $this->quote->id,
            'url' => route('admin.quotes.show', $this->quote),
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $quote = $this->quote;

        return (new MailMessage)
            ->subject("New Quote Request #{$quote->id} — " . config('app.name', 'PhotoLabe'))
            ->view('emails.notification', [
                'title' => 'New Quote Request Received',
                'greeting' => "Hello {$notifiable->name},",
                'lines' => [
                    "A new quote request has been submitted. Please review it and respond to the customer.",
                ],
                'details' => [
                    'Quote ID' => "#{$quote->id}",
                    'Customer' => $quote->name,
                    'Email' => $quote->email,
                    'Phone' => $quote->phone ?? '—',
                    'Service' => $quote->service?->title ?? 'General',
                    'Quantity' => $quote->quantity,
                    'Deadline' => $quote->deadline?->format('M d, Y') ?? 'Flexible',
                    'Submitted' => $quote->created_at->format('M d, Y H:i'),
                ],
                'actionText' => 'View Quote',
                'actionUrl' => route('admin.quotes.show', $quote),
                'outro' => 'Respond promptly — customers expect a quote within 24 hours.',
            ]);
    }
}