<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use App\Support\FormTimeTrap;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $honeypot = trim((string) $request->input('website', ''));
        $timeToken = $request->input(FormTimeTrap::FIELD);

        // Bots get a success-looking response with zero side effects.
        if ($honeypot !== '' || ! FormTimeTrap::passes($timeToken)) {
            return back()->with('success', 'Thanks for subscribing! Please check your inbox.');
        }

        $validated = $request->validate([
            'email' => 'required|email:rfc|max:255',
        ]);

        $subscriber = NewsletterSubscriber::firstOrNew(['email' => $validated['email']]);

        if ($subscriber->exists && $subscriber->status === 'subscribed') {
            return back()->with('info', 'You are already subscribed to our newsletter.');
        }

        $subscriber->status = 'subscribed';
        $subscriber->unsubscribed_at = null;
        $subscriber->ip_address = $request->ip();
        $subscriber->save();

        // Fresh (re)subscription — send the queued welcome email.
        if ($subscriber->wasRecentlyCreated || $subscriber->wasChanged('status')) {
            \Mail::to($subscriber->email)->queue(new \App\Mail\NewsletterWelcome($subscriber->email));
        }

        return back()->with('success', 'Thanks for subscribing! You will hear from us soon.');
    }

    public function unsubscribe(Request $request, string $email)
    {
        if (! hash_equals((string) $request->query('token', ''), $this->tokenFor($email))) {
            abort(403, 'Invalid unsubscribe link.');
        }

        $subscriber = NewsletterSubscriber::where('email', $email)->first();

        if ($subscriber) {
            $subscriber->update([
                'status' => 'unsubscribed',
                'unsubscribed_at' => now(),
            ]);
        }

        return view('frontend.newsletter.unsubscribed', ['email' => $email]);
    }

    public static function tokenFor(string $email): string
    {
        return hash_hmac('sha256', $email, config('app.key'));
    }
}
