<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category', 'variants')->where('is_active', true);

        if ($request->filled('category')) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $request->category));
        }
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        if ($request->boolean('best_sellers')) {
            $query->where('is_best_seller', true);
        }
        if ($request->boolean('featured')) {
            $query->where('is_featured', true);
        }

        return ProductResource::collection($query->paginate(12));
    }

    public function show(Product $product)
    {
        return new ProductResource($product->load('category', 'variants'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id'      => 'required|exists:categories,id',
            'name'             => 'required|string|max:255',
            'name_en'          => 'nullable|string|max:255',
            'description'      => 'nullable|string',
            'description_en'   => 'nullable|string',
            'price'            => 'required|numeric|min:0',
            'compare_price'    => 'nullable|numeric|min:0',
            'discount_percent' => 'integer|min:0|max:100',
            'stock'            => 'integer|min:0',
            'sku'              => 'nullable|string|max:255|unique:products,sku',
            'is_featured'      => 'boolean',
            'is_best_seller'   => 'boolean',
            'is_active'        => 'boolean',
            'available_colors' => 'nullable|array',
            'available_sizes'  => 'nullable|array',
            'seo_title'        => 'nullable|string|max:255',
            'seo_description'  => 'nullable|string',
            'variants'         => 'nullable|array',
        ]);

        $data['slug'] = Str::slug($data['name']) . '-' . uniqid();
        $product = Product::create($data);

        if (!empty($data['variants'])) {
            foreach ($data['variants'] as $variant) {
                $product->variants()->create($variant);
            }
        }

        return (new ProductResource($product->load('variants')))->response()->setStatusCode(201);
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'category_id'      => 'exists:categories,id',
            'name'             => 'string|max:255',
            'name_en'          => 'nullable|string|max:255',
            'description'      => 'nullable|string',
            'description_en'   => 'nullable|string',
            'price'            => 'numeric|min:0',
            'compare_price'    => 'nullable|numeric|min:0',
            'discount_percent' => 'integer|min:0|max:100',
            'stock'            => 'integer|min:0',
            'sku'              => 'nullable|string|max:255|unique:products,sku,' . $product->id,
            'is_active'        => 'boolean',
            'is_featured'      => 'boolean',
            'is_best_seller'   => 'boolean',
            'available_colors' => 'nullable|array',
            'available_sizes'  => 'nullable|array',
            'seo_title'        => 'nullable|string|max:255',
            'seo_description'  => 'nullable|string',
        ]);

        if (isset($data['name'])) {
            $data['slug'] = Str::slug($data['name']) . '-' . uniqid();
        }

        $product->update($data);

        return new ProductResource($product->load('category', 'variants'));
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json(['message' => 'Product deleted.']);
    }

    public function uploadImage(Request $request, Product $product)
    {
        $request->validate(['image' => 'required|image|max:2048']);
        $path = $request->file('image')->store('products', 'public');
        $product->update(['image' => 'storage/' . $path]);

        return response()->json([
            'image'     => $product->image,
            'image_url' => asset($product->image),
        ]);
    }
}
