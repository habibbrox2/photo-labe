<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quote;
use Illuminate\Http\Request;

class QuoteController extends Controller
{
    public function index(Request $request)
    {
        $query = Quote::with('service', 'user');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $quotes = $query->latest()->paginate(15)->withQueryString();

        return view('admin.quotes.index', compact('quotes'));
    }

    public function show(Quote $quote)
    {
        $quote->load('service', 'files', 'user');

        return view('admin.quotes.show', compact('quote'));
    }

    public function update(Request $request, Quote $quote)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,reviewing,quoted,accepted,rejected,expired,converted,cancelled',
            'quoted_price' => 'nullable|numeric|min:0',
            'admin_notes' => 'nullable|string|max:2000',
        ]);

        $quote->update($validated);

        return redirect()->route('admin.quotes.show', $quote)->with('success', 'Quote updated successfully.');
    }

    public function destroy(Quote $quote)
    {
        $quote->delete();

        return redirect()->route('admin.quotes.index')->with('success', 'Quote deleted successfully.');
    }

    /**
     * Convert a quote to an order
     */
    public function convertToOrder(Quote $quote)
    {
        if (in_array($quote->status, ['converted', 'cancelled'])) {
            return back()->with('error', 'This quote has already been processed.');
        }

        \DB::beginTransaction();

        try {
            $order = \App\Models\Order::create([
                'user_id' => $quote->user_id,
                'quote_id' => $quote->id,
                'service_id' => $quote->service_id,
                'subtotal' => $quote->quoted_price ?? 0,
                'total' => $quote->quoted_price ?? 0,
                'currency' => 'USD',
                'quantity' => $quote->quantity,
                'deadline' => $quote->deadline,
                'notes' => $quote->requirements,
                'admin_notes' => $quote->admin_notes,
                'status' => 'pending',
            ]);

            $quote->update(['status' => 'converted']);

            \DB::commit();

            return redirect()->route('admin.orders.show', $order)
                ->with('success', 'Quote converted to order #{$order->order_number} successfully.');
        } catch (\Exception $e) {
            \DB::rollBack();
            return back()->with('error', 'Failed to convert quote: ' . $e->getMessage());
        }
    }
}
