<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderFile;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

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

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,paid,processing,quality_check,revision,completed,cancelled',
            'admin_notes' => 'nullable|string|max:2000',
        ]);

        if ($validated['status'] === 'completed' && $order->status !== 'completed') {
            $validated['completed_at'] = now();
        }

        $order->update($validated);

        return redirect()->route('admin.orders.show', $order)->with('success', 'Order updated successfully.');
    }

    public function destroy(Order $order)
    {
        $order->delete();

        return redirect()->route('admin.orders.index')->with('success', 'Order deleted successfully.');
    }

    /**
     * Upload a file to an order (input from customer or output/deliverable)
     */
    public function uploadFile(Request $request, Order $order)
    {
        $validated = $request->validate([
            'file' => 'required|file|max:51200', // 50MB max
            'type' => 'required|in:input,output',
        ]);

        $file = $request->file('file');
        $type = $validated['type'];

        // Store in order-specific directory
        $directory = "order-files/{$order->id}";
        $storedName = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $file->storeAs($directory, $storedName, 'public');

        OrderFile::create([
            'order_id' => $order->id,
            'original_name' => $file->getClientOriginalName(),
            'stored_name' => $storedName,
            'file_path' => "{$directory}/{$storedName}",
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'type' => $type,
        ]);

        return redirect()->route('admin.orders.show', $order)
            ->with('success', ucfirst($type) . ' file uploaded successfully.');
    }

    /**
     * Delete an order file
     */
    public function deleteFile(Order $order, OrderFile $file)
    {
        if ($file->order_id !== $order->id) {
            abort(404);
        }

        // Delete from storage
        Storage::disk('public')->delete($file->file_path);

        $file->delete();

        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'File deleted successfully.');
    }
}
