<?php

namespace App\Mail;

use App\Models\ContactMessage;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactMessageReply extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public ContactMessage $contactMessage,
        public User $staff,
        public string $replyBody,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Re: ' . $this->contactMessage->subject . ' — ' . config('app.name', 'PhotoLabe'),
            replyTo: [$this->staff->email],
        );
    }

    public function content(): Content
    {
        return new Content('emails.contact-reply', [
            'contactMessage' => $this->contactMessage,
            'staff' => $this->staff,
            'replyBody' => $this->replyBody,
        ]);
    }
}
