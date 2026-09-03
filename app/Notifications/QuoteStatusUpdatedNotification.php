<?php

namespace App\Notifications;

use App\Models\Quote;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class QuoteStatusUpdatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /** The quote status at the time the notification was created. */
    public string $status;

    public function __construct(public Quote $quote)
    {
        // Freeze the status so queued processing reports the state at creation,
        // not whatever the quote looks like when the job eventually runs.
        $this->status = $quote->status;
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toDatabase(object $notifiable): array
    {
        $title = match ($this->status) {
            'quoted' => 'Your Quote Is Ready',
            'rejected' => 'Quote Rejected',
            'expired' => 'Quote Expired',
            'cancelled' => 'Quote Cancelled',
            default => 'Quote Updated',
        };

        return [
            'type' => 'quote_status_updated',
            'title' => $title,
            'message' => $this->buildMessage(),
            'quote_id' => $this->quote->id,
            'url' => route('account.quotes.show', $this->quote),
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $quote = $this->quote;

        return (new MailMessage)
            ->subject($this->subjectLine() . ' — ' . config('app.name', 'PhotoLabe'))
            ->view('emails.notification', [
                'title' => $this->subjectLine(),
                'greeting' => "Hello {$notifiable->name},",
                'lines' => [$this->buildMessage()],
                'details' => array_filter([
                    'Quote ID' => "#{$quote->id}",
                    'Service' => $quote->service?->title ?? 'General',
                    'Status' => ucfirst($quote->status),
                    'Quoted Price' => $quote->quoted_price ? '$' . number_format((float) $quote->quoted_price, 2) : null,
                    'Admin Notes' => $quote->admin_notes,
                ]),
                'actionText' => $quote->status === 'quoted' ? 'Review & Accept Quote' : 'View Quote',
                'actionUrl' => route('account.quotes.show', $quote),
                'outro' => $quote->status === 'quoted'
                    ? 'You can accept or reject this quote from your dashboard. If you accept, your order will be created automatically.'
                    : 'If you have any questions, please contact our support team.',
            ]);
    }

    protected function subjectLine(): string
    {
        return match ($this->status) {
            'quoted' => 'Your Quote Is Ready',
            'rejected' => 'Your Quote Was Rejected',
            'expired' => 'Your Quote Has Expired',
            'cancelled' => 'Your Quote Was Cancelled',
            default => 'Your Quote Was Updated',
        };
    }

    protected function buildMessage(): string
    {
        return match ($this->status) {
            'quoted' => "Your quote request #{$this->quote->id} has been reviewed. We've prepared a quote of $" . number_format((float) ($this->quote->quoted_price ?? 0), 2) . ' — take a look and let us know if you accept it.',
            'rejected' => "Your quote request #{$this->quote->id} was not accepted. Please contact us if you'd like to discuss alternatives.",
            'expired' => "Your quote request #{$this->quote->id} has expired. Please submit a new request if you still need our services.",
            'cancelled' => "Your quote request #{$this->quote->id} has been cancelled.",
            default => "Your quote request #{$this->quote->id} has been updated.",
        };
    }
}