<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderFile;
use App\Models\Quote;
use App\Models\Purchase;
use App\Models\OrderMessage;
use App\Models\OrderRevision;
use App\Models\User;
use App\Services\FileService;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Storage;

class CustomerController extends Controller
{
    /**
     * Customer dashboard with overview widgets
     */
    public function dashboard()
    {
        $user = auth()->user();

        $stats = [
            'active_orders' => Order::where('user_id', $user->id)->active()->count(),
            'completed_orders' => Order::where('user_id', $user->id)->where('status', 'completed')->count(),
            'pending_quotes' => Quote::where('user_id', $user->id)->where('status', 'pending')->count(),
            'total_purchases' => Purchase::where('user_id', $user->id)->where('status', 'completed')->count(),
            'total_spent' => Order::where('user_id', $user->id)->where('status', 'completed')->sum('total')
                + Purchase::where('user_id', $user->id)->where('status', 'completed')->sum('amount'),
            'unread_messages' => OrderMessage::where('user_id', '!=', $user->id)
                ->whereIn('order_id', Order::where('user_id', $user->id)->pluck('id'))
                ->where('is_read', false)
                ->count(),
        ];

        $recentOrders = Order::where('user_id', $user->id)
            ->with('service')
            ->latest()
            ->limit(5)
            ->get();

        $recentQuotes = Quote::where('user_id', $user->id)
            ->with('service')
            ->latest()
            ->limit(5)
            ->get();

        $recentPurchases = Purchase::where('user_id', $user->id)
            ->with('product')
            ->latest()
            ->limit(5)
            ->get();

        $notifications = $user->notifications()->latest()->limit(5)->get();
        $unreadNotifications = $user->unreadNotifications()->count();

        return view('customer.dashboard', compact(
            'stats', 'recentOrders', 'recentQuotes', 'recentPurchases',
            'notifications', 'unreadNotifications'
        ));
    }

    /**
     * Customer notifications list with read/unread filter and pagination
     */
    public function notifications(Request $request)
    {
        $user = auth()->user();

        $query = $user->notifications();

        if ($request->input('filter') === 'unread') {
            $query->whereNull('read_at');
        }

        $notifications = $query->latest()->paginate(15)->withQueryString();
        $unreadCount = $user->unreadNotifications()->count();

        return view('customer.notifications', compact('notifications', 'unreadCount'));
    }

    /**
     * Mark a single notification as read and open its target
     */
    public function openNotification(DatabaseNotification $notification)
    {
        if ($notification->notifiable_id !== auth()->id() || $notification->notifiable_type !== User::class) {
            abort(403);
        }

        $notification->markAsRead();

        return redirect($notification->data['url'] ?? route('account.notifications'));
    }

    /**
     * Mark all customer notifications as read
     */
    public function markNotificationsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();

        return back()->with('success', 'All notifications marked as read.');
    }

    /**
     * Customer orders list
     */
    public function orders(Request $request)
    {
        $user = auth()->user();

        $query = Order::where('user_id', $user->id)->with('service');

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('service', fn ($sq) => $sq->where('title', 'like', "%{$search}%"));
            });
        }

        $orders = $query->latest()->paginate(10)->withQueryString();

        return view('customer.orders', compact('orders'));
    }

    /**
     * Customer order detail
     */
    public function orderShow(Order $order)
    {
        $user = auth()->user();

        if ($order->user_id !== $user->id) {
            abort(403);
        }

        $order->load(['service', 'files', 'messages.user', 'revisions.user', 'payments']);

        // Mark messages from the other party as read
        $this->markMessagesRead($order);

        return view('customer.order-show', compact('order'));
    }

    /**
     * Download an order file through an authorized route.
     * Input files (customer's own uploads) are always available; output
     * files are only released after the order is completed.
     */
    public function downloadOrderFile(Order $order, OrderFile $file, FileService $files)
    {
        $user = auth()->user();

        if ($order->user_id !== $user->id) {
            abort(403);
        }

        if ($file->order_id !== $order->id) {
            abort(404);
        }

        if ($file->type === 'output' && $order->status !== 'completed') {
            abort(403);
        }

        return $files->downloadOrderFile($file);
    }

    /**
     * Send message on an order
     */
    public function orderMessage(Request $request, Order $order)
    {
        $user = auth()->user();

        if ($order->user_id !== $user->id) {
            abort(403);
        }

        $validated = $request->validate([
            'message' => 'required|string|max:5000',
        ]);

        OrderMessage::create([
            'order_id' => $order->id,
            'user_id' => $user->id,
            'message' => $validated['message'],
            'is_read' => false,
        ]);

        return redirect()->route('account.orders.show', $order)->with('success', 'Message sent.');
    }

    /**
     * Request a revision on an order
     */
    public function orderRevision(Request $request, Order $order)
    {
        $user = auth()->user();

        if ($order->user_id !== $user->id) {
            abort(403);
        }

        if (!in_array($order->status, ['in_progress', 'revision'])) {
            return back()->with('error', 'Revision requests are only available for in-progress or revision orders.');
        }

        $validated = $request->validate([
            'message' => 'required|string|max:5000',
        ]);

        OrderRevision::create([
            'order_id' => $order->id,
            'user_id' => $user->id,
            'message' => $validated['message'],
            'status' => 'pending',
        ]);

        $order->update(['status' => 'revision']);

        return redirect()->route('account.orders.show', $order)->with('success', 'Revision request submitted.');
    }

    /**
     * Mark order messages as read
     */
    public function markMessagesRead(Order $order)
    {
        $user = auth()->user();

        if ($order->user_id !== $user->id) {
            abort(403);
        }

        OrderMessage::where('order_id', $order->id)
            ->where('user_id', '!=', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return back();
    }

    /**
     * Customer quotes list
     */
    public function quotes(Request $request)
    {
        $user = auth()->user();

        $query = Quote::where('user_id', $user->id)->with('service');

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $quotes = $query->latest()->paginate(10)->withQueryString();

        return view('customer.quotes', compact('quotes'));
    }

    /**
     * Customer quote detail
     */
    public function quoteShow(Quote $quote)
    {
        $user = auth()->user();

        if ($quote->user_id !== $user->id) {
            abort(403);
        }

        $quote->load(['service', 'files', 'order']);

        return view('customer.quote-show', compact('quote'));
    }

    /**
     * Accept a quoted price
     */
    public function quoteAccept(Quote $quote)
    {
        $user = auth()->user();

        if ($quote->user_id !== $user->id) {
            abort(403);
        }

        if ($quote->status !== 'quoted') {
            return back()->with('error', 'This quote is not awaiting acceptance.');
        }

        $quote->update(['status' => 'accepted']);

        // Convert quote to order
        $order = Order::create([
            'user_id' => $user->id,
            'quote_id' => $quote->id,
            'service_id' => $quote->service_id,
            'subtotal' => $quote->quoted_price,
            'total' => $quote->quoted_price,
            'currency' => 'USD',
            'quantity' => $quote->quantity,
            'deadline' => $quote->deadline,
            'notes' => $quote->requirements,
            'status' => 'pending',
        ]);

        $quote->update(['status' => 'converted']);

        // Notify staff that the quote was accepted
        \Illuminate\Support\Facades\Notification::send(
            \App\Models\User::staff()->get(),
            new \App\Notifications\QuoteAcceptedNotification($quote)
        );

        return redirect()->route('account.orders.show', $order)->with('success', 'Quote accepted! Your order has been created.');
    }

    /**
     * Reject a quoted price
     */
    public function quoteReject(Quote $quote)
    {
        $user = auth()->user();

        if ($quote->user_id !== $user->id) {
            abort(403);
        }

        if (!in_array($quote->status, ['quoted', 'reviewing'])) {
            return back()->with('error', 'This quote cannot be rejected at this stage.');
        }

        $quote->update(['status' => 'rejected']);

        return redirect()->route('account.quotes')->with('success', 'Quote rejected.');
    }

    /**
     * Customer purchases list
     */
    public function purchases(Request $request)
    {
        $user = auth()->user();

        $purchases = Purchase::where('user_id', $user->id)
            ->with('product')
            ->latest()
            ->paginate(10);

        return view('customer.purchases', compact('purchases'));
    }

    /**
     * Download a purchased product file
     */
    public function downloadFile(Purchase $purchase, \App\Models\ProductFile $file)
    {
        $user = auth()->user();

        if ($purchase->user_id !== $user->id) {
            abort(403);
        }

        if ($purchase->status !== 'completed') {
            abort(403);
        }

        // Increment download count
        $purchase->increment('download_count');

        return Storage::disk('public')->download(
            $file->file_path,
            $file->file_name
        );
    }

    /**
     * Customer payments list
     */
    public function payments()
    {
        $user = auth()->user();

        $payments = \App\Models\Payment::where('user_id', $user->id)
            ->with('order')
            ->latest()
            ->paginate(10);

        return view('customer.payments', compact('payments'));
    }
}
