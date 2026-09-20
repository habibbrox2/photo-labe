<?php

namespace App\Mail;

use App\Http\Controllers\Frontend\NewsletterController;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewsletterWelcome extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public string $email)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Welcome to the '.config('app.name', 'PhotoLabe').' Newsletter 🎉',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.newsletter-welcome',
            with: [
                'email' => $this->email,
                'unsubscribeUrl' => route('newsletter.unsubscribe', [
                    'email' => $this->email,
                    'token' => NewsletterController::tokenFor($this->email),
                ]),
            ],
        );
    }
}
