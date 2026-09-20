<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;

class NewsletterSubscriberController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'subscribed');

        $subscribers = NewsletterSubscriber::query()
            ->when(in_array($status, ['subscribed', 'unsubscribed']), fn ($q) => $q->where('status', $status))
            ->orderBy('created_at', 'desc')
            ->paginate(50)
            ->withQueryString();

        $stats = [
            'total' => NewsletterSubscriber::count(),
            'subscribed' => NewsletterSubscriber::subscribed()->count(),
            'unsubscribed' => NewsletterSubscriber::where('status', 'unsubscribed')->count(),
        ];

        return view('admin.newsletter-subscribers.index', compact('subscribers', 'stats', 'status'));
    }

    public function export(Request $request)
    {
        $status = $request->query('status', 'subscribed');

        $query = NewsletterSubscriber::query()
            ->when(in_array($status, ['subscribed', 'unsubscribed']), fn ($q) => $q->where('status', $status))
            ->orderBy('created_at', 'desc');

        $filename = 'newsletter-subscribers-'.now()->format('Y-m-d-His').'.csv';

        return response()->streamDownload(function () use ($query) {
            $out = fopen('php://output', 'w');

            fprintf($out, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM for Excel
            fputcsv($out, ['email', 'status', 'subscribed_at', 'unsubscribed_at', 'ip_address']);

            $query->chunk(500, function ($rows) use ($out) {
                foreach ($rows as $row) {
                    fputcsv($out, [
                        $row->email,
                        $row->status,
                        $row->created_at?->toDateTimeString(),
                        $row->unsubscribed_at?->toDateTimeString(),
                        $row->ip_address,
                    ]);
                }
            });

            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
