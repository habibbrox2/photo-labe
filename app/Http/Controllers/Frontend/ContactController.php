<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\User;
use App\Notifications\ContactMessageNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class ContactController extends Controller
{
    public function show()
    {
        return view('frontend.contact');
    }

    public function store(Request $request)
    {
        // Honeypot: real users never see or fill the "website" field.
        // Silently accept the submission so bots think they succeeded.
        if (filled($request->input('website'))) {
            return back()->with('success', 'Your message has been sent successfully! We will get back to you soon.');
        }

        // Time-trap: reject submissions faster than a human could fill the form.
        if (! \App\Support\FormTimeTrap::passes($request->input(\App\Support\FormTimeTrap::FIELD))) {
            return back()->with('success', 'Your message has been sent successfully! We will get back to you soon.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        // Persist the message so staff can review it in the admin inbox
        $message = ContactMessage::create($validated);

        // Notify staff about the new contact message
        Notification::send(
            User::staff()->get(),
            new ContactMessageNotification($message)
        );

        return back()->with('success', 'Your message has been sent successfully! We will get back to you soon.');
    }
}
