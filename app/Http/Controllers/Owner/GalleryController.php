<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\GalleryItem;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index(Request $request)
    {
        $dojoId = currentDojo();
        
        $query = GalleryItem::where('dojo_id', $dojoId);

        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        if ($request->has('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        $items = $query->orderBy('display_order')->paginate(20);

        return response()->json($items);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image_path' => 'required|string',
            'category' => 'nullable|string',
            'display_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['dojo_id'] = currentDojo();
        $item = GalleryItem::create($validated);

        return response()->json($item, 201);
    }

    public function show(GalleryItem $galleryItem)
    {
        return response()->json($galleryItem);
    }

    public function update(Request $request, GalleryItem $galleryItem)
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'image_path' => 'sometimes|required|string',
            'category' => 'nullable|string',
            'display_order' => 'nullable|integer',
            'is_active' => 'sometimes|boolean',
        ]);

        $galleryItem->update($validated);

        return response()->json($galleryItem);
    }

    public function destroy(GalleryItem $galleryItem)
    {
        $galleryItem->delete();
        return response()->json(['message' => 'Gallery item deleted successfully']);
    }
}
