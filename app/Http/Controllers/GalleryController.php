<?php

namespace App\Http\Controllers;

use App\Models\GalleryPhoto;
use App\Services\ImageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        abort_unless(Auth::user()->isTechnical(), 403);

        $q = GalleryPhoto::with(['event:id,name', 'uploader:id,name'])
            ->latest();

        if ($request->event_id) {
            $q->where('event_id', $request->event_id);
        }

        $perPage = (int) ($request->per_page ?? 30);
        $photos  = $q->paginate($perPage);

        return response()->json([
            'success' => true,
            'data'    => $photos->items(),
            'meta'    => [
                'total'        => $photos->total(),
                'current_page' => $photos->currentPage(),
                'last_page'    => $photos->lastPage(),
            ],
        ]);
    }

    public function stats(): JsonResponse
    {
        abort_unless(Auth::user()->isTechnical(), 403);

        return response()->json([
            'success' => true,
            'data'    => [
                'total'      => GalleryPhoto::count(),
                'this_month' => GalleryPhoto::whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->count(),
                'total_size' => GalleryPhoto::sum('size'),
            ],
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        abort_unless(Auth::user()->isTechnical(), 403);

        $request->validate([
            'photos'    => ['required', 'array', 'min:1', 'max:20'],
            'photos.*'  => ['required', 'file', 'image', 'mimes:jpeg,jpg,png,webp', 'max:5120'],
            'event_id'  => ['nullable', 'exists:events,id'],
            'caption'   => ['nullable', 'string', 'max:255'],
        ]);

        $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
        $uploaded = [];

        foreach ($request->file('photos') as $file) {
            // Validate actual MIME type from file content (not just extension)
            if (! in_array($file->getMimeType(), $allowedMimes, true)) {
                return response()->json(['success' => false, 'message' => 'Type de fichier non autorisé.'], 422);
            }
            $path     = ImageService::storeOptimized($file, 'gallery', 1400, 80);
            $fullPath = Storage::disk('public')->path($path);
            $size     = filesize($fullPath) ?: $file->getSize();
            $photo = GalleryPhoto::create([
                'path'          => $path,
                'original_name' => mb_substr(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME), 0, 100),
                'caption'       => $request->caption,
                'event_id'      => $request->event_id,
                'uploaded_by'   => Auth::id(),
                'size'          => $size ?? $file->getSize(),
            ]);

            $uploaded[] = [
                'id'             => $photo->id,
                'url'            => $photo->url,
                'original_name'  => $photo->original_name,
                'caption'        => $photo->caption,
                'size_formatted' => $photo->size_formatted,
                'created_at'     => $photo->created_at->format('d/m/Y'),
            ];
        }

        return response()->json([
            'success' => true,
            'message' => count($uploaded) . ' photo(s) uploadée(s).',
            'data'    => $uploaded,
        ], 201);
    }

    public function update(Request $request, GalleryPhoto $photo): JsonResponse
    {
        abort_unless(Auth::user()->isTechnical(), 403);

        $data = $request->validate([
            'caption'  => ['nullable', 'string', 'max:255'],
            'event_id' => ['nullable', 'exists:events,id'],
        ]);

        $photo->update($data);

        return response()->json(['success' => true, 'message' => 'Photo mise à jour.', 'data' => $photo]);
    }

    public function destroy(GalleryPhoto $photo): JsonResponse
    {
        abort_unless(Auth::user()->isTechnical(), 403);

        Storage::disk('public')->delete($photo->path);
        $photo->delete();

        return response()->json(['success' => true, 'message' => 'Photo supprimée.']);
    }

    public function bulkDestroy(Request $request): JsonResponse
    {
        abort_unless(Auth::user()->isTechnical(), 403);

        $ids    = $request->validate(['ids' => ['required', 'array']])['ids'];
        $photos = GalleryPhoto::whereIn('id', $ids)->get();

        foreach ($photos as $photo) {
            Storage::disk('public')->delete($photo->path);
            $photo->delete();
        }

        return response()->json([
            'success' => true,
            'message' => count($photos) . ' photo(s) supprimée(s).',
        ]);
    }

}
