<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        return response()->json(Category::where('is_active', true)->orderBy('sort_order')->get());
    }

    public function show(Category $category)
    {
        return response()->json($category->load('products'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:100',
            'description' => 'nullable|string',
            'image'       => 'nullable|string',
            'sort_order'  => 'integer',
        ]);
        $data['slug'] = \Str::slug($data['name']);
        return response()->json(Category::create($data), 201);
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name'        => 'string|max:100',
            'description' => 'nullable|string',
            'image'       => 'nullable|string',
            'is_active'   => 'boolean',
            'sort_order'  => 'integer',
        ]);
        if (isset($data['name'])) $data['slug'] = \Str::slug($data['name']);
        $category->update($data);
        return response()->json($category);
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return response()->json(['message' => 'Deleted.']);
    }
}
