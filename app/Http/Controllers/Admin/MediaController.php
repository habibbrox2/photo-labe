<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    public function index(Request $request)
    {
        $query = Media::with('user');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('original_name', 'like', "%{$search}%")
                  ->orWhere('alt_text', 'like', "%{$search}%");
            });
        }

        if ($type = $request->input('type')) {
            $query->where('mime_type', 'like', "%{$type}%");
        }

        $media = $query->latest()->paginate(24)->withQueryString();

        $mediaItems = $media->getCollection()->map(fn($m) => [
            'id' => $m->id,
            'url' => asset('storage/' . $m->file_path),
            'name' => $m->original_name,
            'mime' => $m->mime_type,
            'isImage' => str_starts_with($m->mime_type, 'image/'),
            'size' => $m->file_size >= 1048576 ? round($m->file_size / 1048576, 1) . ' MB' : round($m->file_size / 1024) . ' KB',
            'dimensions' => $m->width && $m->height ? "{$m->width} × {$m->height}" : null,
            'date' => $m->created_at->format('M d, Y'),
            'deleteUrl' => route('admin.media.destroy', $m),
        ])->values();

        return view('admin.media.index', compact('media', 'mediaItems'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'files' => 'required|array|max:10',
            'files.*' => 'file|max:10240|mimes:jpg,jpeg,png,webp,gif,svg,pdf,zip,psd',
            'alt_text' => 'nullable|string|max:255',
            'title' => 'nullable|string|max:255',
        ]);

        $created = [];
        $altText = $request->input('alt_text');
        $title = $request->input('title');

        foreach ($request->file('files') as $file) {
            $originalName = $file->getClientOriginalName();
            $fileName = Str::random(40) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('media', $fileName, 'public');

            $attributes = [
                'user_id' => auth()->id(),
                'original_name' => $originalName,
                'file_name' => $fileName,
                'file_path' => $path,
                'mime_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
                'alt_text' => $altText,
                'title' => $title,
            ];

            // Capture image dimensions for better preview experience
            if (str_starts_with($file->getMimeType(), 'image/') && in_array($file->getClientOriginalExtension(), ['jpg', 'jpeg', 'png', 'webp'])) {
                $dimensions = @getimagesize($file->getRealPath());
                if ($dimensions) {
                    $attributes['width'] = $dimensions[0];
                    $attributes['height'] = $dimensions[1];
                }
            }

            $media = Media::create($attributes);

            $size = $media->file_size;
            $created[] = [
                'id' => $media->id,
                'url' => asset('storage/' . $media->file_path),
                'name' => $media->original_name,
                'mime' => $media->mime_type,
                'isImage' => str_starts_with($media->mime_type, 'image/'),
                'size' => $size >= 1048576 ? round($size / 1048576, 1) . ' MB' : round($size / 1024) . ' KB',
                'date' => $media->created_at->format('M d, Y'),
                'deleteUrl' => route('admin.media.destroy', $media),
            ];
        }

        if ($request->expectsJson()) {
            return response()->json(['uploaded' => $created], 201);
        }

        return redirect()->route('admin.media.index')->with('success', 'Files uploaded successfully.');
    }

    public function preview(Media $media)
    {
        return response()->json([
            'id' => $media->id,
            'url' => asset('storage/' . $media->file_path),
            'name' => $media->original_name,
            'mime' => $media->mime_type,
            'size' => $media->file_size,
            'formattedSize' => $this->formatBytes($media->file_size),
            'dimensions' => $media->width && $media->height ? "{$media->width} × {$media->height}" : null,
            'alt' => $media->alt_text,
            'title' => $media->title,
            'uploaded' => $media->created_at->format('M d, Y \a\t g:i A'),
            'uploader' => $media->user?->name,
            'isImage' => $media->isImage(),
        ]);
    }

    public function update(Request $request, Media $media)
    {
        $validated = $request->validate([
            'alt_text' => 'nullable|string|max:255',
            'title' => 'nullable|string|max:255',
        ]);

        $media->update($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Media metadata updated.',
                'alt' => $media->alt_text,
                'title' => $media->title,
            ]);
        }

        return redirect()->route('admin.media.index')->with('success', 'Media updated successfully.');
    }

    public function download(Media $media)
    {
        $path = storage_path('app/public/' . $media->file_path);

        if (!file_exists($path)) {
            abort(404);
        }

        return response()->download($path, $media->original_name);
    }

    protected function formatBytes(int $bytes): string
    {
        if ($bytes >= 1073741824) {
            return round($bytes / 1073741824, 1) . ' GB';
        }
        if ($bytes >= 1048576) {
            return round($bytes / 1048576, 1) . ' MB';
        }
        if ($bytes >= 1024) {
            return round($bytes / 1024) . ' KB';
        }

        return $bytes . ' B';
    }

    public function destroy(Media $media)
    {
        $media->delete();

        return redirect()->route('admin.media.index')->with('success', 'Media deleted successfully.');
    }

    public function bulkDestroy(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer',
        ]);

        $deleted = Media::whereIn('id', $validated['ids'])->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'deleted' => $deleted,
                'remaining' => Media::count(),
            ]);
        }

        return redirect()->route('admin.media.index')->with('success', $deleted . ' files deleted successfully.');
    }
}
