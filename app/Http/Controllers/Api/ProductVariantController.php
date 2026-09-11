<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductVariantResource;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class ProductVariantController extends Controller
{
    public function index(Product $product)
    {
        return ProductVariantResource::collection(
            $product->variants()->orderBy('color')->orderBy('size')->get()
        );
    }

    public function store(Request $request, Product $product)
    {
        $data = $request->validate([
            'color'            => 'required|string|max:255',
            'color_hex'        => 'nullable|string|max:10',
            'size'             => 'required|in:XS,S,M,L',
            'stock'            => 'integer|min:0',
            'price_adjustment' => 'numeric',
            'sku'              => 'nullable|string|max:255|unique:product_variants,sku',
        ]);

        $variant = $product->variants()->create($data);

        return (new ProductVariantResource($variant))->response()->setStatusCode(201);
    }

    public function update(Request $request, Product $product, ProductVariant $variant)
    {
        if ($variant->product_id !== $product->id) {
            return response()->json(['message' => 'Variant does not belong to this product.'], 404);
        }

        $data = $request->validate([
            'color'            => 'string|max:255',
            'color_hex'        => 'nullable|string|max:10',
            'size'             => 'in:XS,S,M,L',
            'stock'            => 'integer|min:0',
            'price_adjustment' => 'numeric',
            'sku'              => 'nullable|string|max:255|unique:product_variants,sku,' . $variant->id,
        ]);

        $variant->update($data);

        return new ProductVariantResource($variant);
    }

    public function destroy(Product $product, ProductVariant $variant)
    {
        if ($variant->product_id !== $product->id) {
            return response()->json(['message' => 'Variant does not belong to this product.'], 404);
        }

        $variant->delete();

        return response()->json(['message' => 'Variant deleted.']);
    }
}
