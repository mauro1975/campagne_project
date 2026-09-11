<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();

        return CategoryResource::collection($categories);
    }

    public function show(Category $category)
    {
        return new CategoryResource($category->load(['products' => fn ($q) => $q->where('is_active', true)]));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'           => 'required|string|max:100',
            'name_en'        => 'nullable|string|max:100',
            'description'    => 'nullable|string',
            'description_en' => 'nullable|string',
            'image'          => 'nullable|string',
            'sort_order'     => 'integer',
            'is_active'      => 'boolean',
        ]);

        $data['slug'] = Str::slug($data['name']);

        $category = Category::create($data);

        return (new CategoryResource($category))->response()->setStatusCode(201);
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name'           => 'string|max:100',
            'name_en'        => 'nullable|string|max:100',
            'description'    => 'nullable|string',
            'description_en' => 'nullable|string',
            'image'          => 'nullable|string',
            'is_active'      => 'boolean',
            'sort_order'     => 'integer',
        ]);

        if (isset($data['name'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $category->update($data);

        return new CategoryResource($category);
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return response()->json(['message' => 'Category deleted.']);
    }
}
