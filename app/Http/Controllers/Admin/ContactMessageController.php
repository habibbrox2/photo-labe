<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\ContactMessageReply;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactMessageController extends Controller
{
    public function index(Request $request)
    {
        $query = ContactMessage::query()->latest();

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%");
            });
        }

        $messages = $query->paginate(15)->withQueryString();
        $newCount = ContactMessage::new()->count();

        return view('admin.contact-messages.index', compact('messages', 'newCount'));
    }

    public function show(ContactMessage $contactMessage)
    {
        $contactMessage->markRead(auth()->user());

        return view('admin.contact-messages.show', ['message' => $contactMessage]);
    }

    public function archive(ContactMessage $contactMessage)
    {
        $contactMessage->update(['status' => 'archived']);

        return redirect()
            ->route('admin.contact-messages.index')
            ->with('success', 'Message archived.');
    }

    /**
     * Send an SMTP reply to the sender directly from the inbox.
     */
    public function reply(Request $request, ContactMessage $contactMessage)
    {
        $validated = $request->validate([
            'reply_body' => 'required|string|max:10000',
        ]);

        Mail::to($contactMessage->email, $contactMessage->name)
            ->send(new ContactMessageReply(
                $contactMessage,
                auth()->user(),
                $validated['reply_body'],
            ));

        $contactMessage->update([
            'replied_by' => auth()->id(),
            'replied_at' => now(),
        ]);

        return redirect()
            ->route('admin.contact-messages.show', $contactMessage)
            ->with('success', 'Reply sent to ' . $contactMessage->email . '.');
    }
}
