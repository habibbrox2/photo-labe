<?php

namespace App\Notifications;

use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContactMessageNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public ContactMessage $contactMessage)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'contact_message',
            'title' => 'New Contact Message',
            'message' => "New contact message from {$this->contactMessage->name}: {$this->contactMessage->subject}",
            'contact_message_id' => $this->contactMessage->id,
            'url' => route('admin.contact-messages.show', $this->contactMessage),
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $message = $this->contactMessage;

        return (new MailMessage)
            ->subject("New Contact Message: {$message->subject} — " . config('app.name', 'PhotoLabe'))
            ->replyTo($message->email, $message->name)
            ->view('emails.notification', [
                'title' => 'New Contact Message',
                'greeting' => "Hello {$notifiable->name},",
                'outro' => 'The full message from the sender follows below.',
                'lines' => [
                    'A new message was submitted through the contact form on ' . config('app.name', 'PhotoLabe') . '.',
                ],
                'details' => [
                    'Name' => $message->name,
                    'Email' => $message->email,
                    'Subject' => $message->subject,
                    'Submitted' => $message->created_at->format('M d, Y H:i'),
                    'Message' => $message->message,
                ],
                'actionText' => 'Open in Admin',
                'actionUrl' => route('admin.contact-messages.show', $message),
            ]);
    }
}
