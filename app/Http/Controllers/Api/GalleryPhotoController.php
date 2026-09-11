<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\GalleryPhotoResource;
use App\Models\GalleryPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryPhotoController extends Controller
{
    public function index()
    {
        $photos = GalleryPhoto::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return GalleryPhotoResource::collection($photos);
    }

    public function adminIndex()
    {
        $photos = GalleryPhoto::orderBy('sort_order')->orderBy('id')->paginate(30);
        return GalleryPhotoResource::collection($photos);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'image'       => 'required|image|max:4096',
            'alt'         => 'nullable|string|max:255',
            'sort_order'  => 'integer|min:0',
            'is_active'   => 'boolean',
        ]);

        $path = $request->file('image')->store('gallery', 'public');

        $photo = GalleryPhoto::create([
            'path'       => 'storage/' . $path,
            'alt'        => $data['alt'] ?? null,
            'sort_order' => $data['sort_order'] ?? 0,
            'is_active'  => $data['is_active'] ?? true,
        ]);

        return (new GalleryPhotoResource($photo))->response()->setStatusCode(201);
    }

    public function update(Request $request, GalleryPhoto $galleryPhoto)
    {
        $data = $request->validate([
            'alt'        => 'nullable|string|max:255',
            'sort_order' => 'integer|min:0',
            'is_active'  => 'boolean',
        ]);

        $galleryPhoto->update($data);

        return new GalleryPhotoResource($galleryPhoto);
    }

    public function destroy(GalleryPhoto $galleryPhoto)
    {
        if ($galleryPhoto->path && str_starts_with($galleryPhoto->path, 'storage/')) {
            Storage::disk('public')->delete(str_replace('storage/', '', $galleryPhoto->path));
        }

        $galleryPhoto->delete();

        return response()->json(['message' => 'Gallery photo deleted.']);
    }
}
