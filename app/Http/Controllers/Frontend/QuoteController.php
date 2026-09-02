<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Quote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuoteController extends Controller
{
    public function create()
    {
        $services = Service::active()->ordered()->get();

        return view('frontend.quote.create', compact('services'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:30',
            'service_id' => 'nullable|exists:services,id',
            'quantity' => 'required|integer|min:1|max:10000',
            'deadline' => 'nullable|date|after:today',
            'requirements' => 'required|string|max:5000',
            'files' => 'nullable|array|max:5',
            'files.*' => 'file|mimes:jpg,jpeg,png,webp,tiff,zip,psd|max:10240',
        ]);

        $quote = DB::transaction(function () use ($validated, $request) {
            $quote = Quote::create([
                'user_id' => auth()->id(),
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'service_id' => $validated['service_id'] ?? null,
                'quantity' => $validated['quantity'],
                'deadline' => $validated['deadline'] ?? null,
                'requirements' => $validated['requirements'],
                'status' => 'pending',
            ]);

            // Handle file uploads
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    $storedName = \Illuminate\Support\Str::random(40) . '.' . $file->getClientOriginalExtension();
                    $file->storeAs('private/quotes', $storedName);

                    $quote->files()->create([
                        'original_name' => $file->getClientOriginalName(),
                        'stored_name' => $storedName,
                        'file_path' => 'private/quotes/' . $storedName,
                        'mime_type' => $file->getMimeType(),
                        'file_size' => $file->getSize(),
                    ]);
                }
            }

            return $quote;
        });

        return redirect()
            ->route('home')
            ->with('success', 'Your quote request has been submitted successfully! We will get back to you within 24 hours.');
    }
}
