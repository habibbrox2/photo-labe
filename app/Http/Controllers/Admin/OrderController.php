<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderFile;
use App\Services\FileService;
use App\Services\Payment\PaymentService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('user', 'service');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%");
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $orders = $query->latest()->paginate(15)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load('user', 'service', 'items', 'files', 'messages', 'revisions', 'payments');

        return view('admin.orders.show', compact('order'));
    }

    public function update(Request $request, Order $order, PaymentService $payments)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,paid,processing,quality_check,revision,completed,cancelled',
            'admin_notes' => 'nullable|string|max:2000',
        ]);

        if ($validated['status'] === 'completed' && $order->status !== 'completed') {
            $validated['completed_at'] = now();
        }

        $oldStatus = $order->status;

        $order->update($validated);

        // Confirm payment server-side when the order is marked paid (idempotent)
        if ($validated['status'] === 'paid' && $oldStatus !== 'paid') {
            $payments->confirmPaymentForOrder($order);
        }

        // Notify the customer when the order status actually changes
        if ($order->user && $oldStatus !== $order->status) {
            $order->user->notify(new \App\Notifications\OrderStatusNotification($order, $oldStatus));
        }

        return redirect()->route('admin.orders.show', $order)->with('success', 'Order updated successfully.');
    }

    public function destroy(Order $order)
    {
        $order->delete();

        return redirect()->route('admin.orders.index')->with('success', 'Order deleted successfully.');
    }

    /**
     * Upload a file to an order (input from customer or output/deliverable).
     * Files are stored on the private disk — never publicly accessible.
     */
    public function uploadFile(Request $request, Order $order, FileService $files)
    {
        $validated = $request->validate([
            'file' => ['required', 'file', 'mimes:' . implode(',', \App\Services\FileService::ALLOWED_EXTENSIONS), 'max:51200'],
            'type' => 'required|in:input,output',
        ]);

        $files->storeOrderFile($request->file('file'), $order, $validated['type']);

        return redirect()->route('admin.orders.show', $order)
            ->with('success', ucfirst($validated['type']) . ' file uploaded successfully.');
    }

    /**
     * Download an order file (staff only, admin middleware applies)
     */
    public function downloadFile(Order $order, OrderFile $file, FileService $files)
    {
        if ($file->order_id !== $order->id) {
            abort(404);
        }

        return $files->downloadOrderFile($file);
    }

    /**
     * Delete an order file
     */
    public function deleteFile(Order $order, OrderFile $file, FileService $files)
    {
        if ($file->order_id !== $order->id) {
            abort(404);
        }

        $files->deleteOrderFile($file);

        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'File deleted successfully.');
    }
}
