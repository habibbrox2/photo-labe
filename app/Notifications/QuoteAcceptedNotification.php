<?php

namespace App\Notifications;

use App\Models\Quote;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class QuoteAcceptedNotification extends Notification implements ShouldQueue
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
            'type' => 'quote_accepted',
            'title' => 'Quote Accepted',
            'message' => "{$this->quote->name} accepted quote #{$this->quote->id}",
            'quote_id' => $this->quote->id,
            'url' => route('admin.quotes.show', $this->quote),
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $quote = $this->quote;

        return (new MailMessage)
            ->subject("Quote #{$quote->id} Accepted — " . config('app.name', 'PhotoLabe'))
            ->view('emails.notification', [
                'title' => 'Quote Accepted',
                'greeting' => "Hello {$notifiable->name},",
                'lines' => [
                    "{$quote->name} has accepted quote #{$quote->id} and the order has been created automatically.",
                    'You can start working on this project as soon as the order is confirmed.',
                ],
                'details' => [
                    'Quote ID' => "#{$quote->id}",
                    'Customer' => $quote->name,
                    'Email' => $quote->email,
                    'Service' => $quote->service?->title ?? 'General',
                    'Quantity' => $quote->quantity,
                    'Quoted Price' => $quote->quoted_price ? money($quote->quoted_price) : '—',
                ],
                'actionText' => 'View Quote',
                'actionUrl' => route('admin.quotes.show', $quote),
                'outro' => 'The customer is expecting to hear from you soon.',
            ]);
    }
}