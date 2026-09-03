<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderFile;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileService
{
    /**
     * Allowed file types for order files (master prompt §35).
     */
    public const ALLOWED_EXTENSIONS = ['jpg', 'jpeg', 'png', 'webp', 'tiff', 'zip', 'psd'];

    /**
     * Store an order file on the private disk (never publicly accessible).
     *
     * Storage layout: storage/app/private/orders/{order-id}/{input|output}/{uuid}.{ext}
     */
    public function storeOrderFile(UploadedFile $file, Order $order, string $type): OrderFile
    {
        $directory = "private/orders/{$order->id}/{$type}";
        $storedName = Str::uuid() . '.' . $file->getClientOriginalExtension();

        $file->storeAs($directory, $storedName, 'local');

        return $order->files()->create([
            'original_name' => $file->getClientOriginalName(),
            'stored_name' => $storedName,
            'file_path' => "{$directory}/{$storedName}",
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'type' => $type,
        ]);
    }

    /**
     * Stream an order file as a download. Reads from the private disk with a
     * fallback to the public disk for legacy files uploaded before this change.
     */
    public function downloadOrderFile(OrderFile $file)
    {
        $disk = Storage::disk('local');

        if (! $disk->exists($file->file_path)) {
            $disk = Storage::disk('public');

            if (! $disk->exists($file->file_path)) {
                abort(404, 'File not found.');
            }
        }

        return $disk->download($file->file_path, $file->original_name);
    }

    /**
     * Delete an order file from storage (both disks, for legacy files).
     */
    public function deleteOrderFile(OrderFile $file): void
    {
        Storage::disk('local')->delete($file->file_path);
        Storage::disk('public')->delete($file->file_path);

        $file->delete();
    }
}