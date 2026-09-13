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

        return view('admin.media.index', compact('media'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'files' => 'required|array|max:10',
            'files.*' => 'file|max:10240|mimes:jpg,jpeg,png,webp,gif,svg,pdf,zip,psd',
        ]);

        $created = [];

        foreach ($request->file('files') as $file) {
            $originalName = $file->getClientOriginalName();
            $fileName = Str::random(40) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('media', $fileName, 'public');

            $media = Media::create([
                'user_id' => auth()->id(),
                'original_name' => $originalName,
                'file_name' => $fileName,
                'file_path' => $path,
                'mime_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
            ]);

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
