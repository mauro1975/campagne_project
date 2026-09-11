<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CartResource;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Discount;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CartController extends Controller
{
    private function getOrCreateCart(Request $request): Cart
    {
        if ($request->user()) {
            return Cart::firstOrCreate(['user_id' => $request->user()->id]);
        }

        $sessionId = $request->header('X-Cart-Session') ?? $request->input('session_id') ?? (string) Str::uuid();

        return Cart::firstOrCreate(['session_id' => $sessionId]);
    }

    public function index(Request $request)
    {
        $cart = $this->getOrCreateCart($request);

        return new CartResource($cart->load('items.product'));
    }

    public function add(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'color'      => 'required|string',
            'size'       => 'required|in:XS,S,M,L',
            'quantity'   => 'integer|min:1|max:10',
        ]);

        $product = Product::findOrFail($data['product_id']);
        $variant = ProductVariant::where('product_id', $product->id)
            ->where('color', $data['color'])
            ->where('size', $data['size'])
            ->first();

        $price = $product->final_price + ($variant?->price_adjustment ?? 0);
        $cart = $this->getOrCreateCart($request);

        $existingItem = $cart->items()
            ->where('product_id', $product->id)
            ->where('color', $data['color'])
            ->where('size', $data['size'])
            ->first();

        if ($existingItem) {
            $existingItem->increment('quantity', $data['quantity'] ?? 1);
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'variant_id' => $variant?->id,
                'color'      => $data['color'],
                'size'       => $data['size'],
                'quantity'   => $data['quantity'] ?? 1,
                'price'      => $price,
            ]);
        }

        return new CartResource($cart->fresh()->load('items.product'));
    }

    public function update(Request $request, CartItem $item)
    {
        $data = $request->validate(['quantity' => 'required|integer|min:1|max:10']);
        $item->update($data);

        return response()->json(['message' => 'Cart item updated.']);
    }

    public function remove(CartItem $item)
    {
        $item->delete();

        return response()->json(['message' => 'Item removed.']);
    }

    public function clear(Request $request)
    {
        $cart = $this->getOrCreateCart($request);
        $cart->items()->delete();

        return response()->json(['message' => 'Cart cleared.']);
    }

    public function applyDiscount(Request $request)
    {
        $data = $request->validate(['code' => 'required|string']);
        $discount = Discount::where('code', strtoupper($data['code']))->first();
        $cart = $this->getOrCreateCart($request);
        $subtotal = $cart->getTotal();

        if (!$discount || !$discount->isValid($subtotal)) {
            return response()->json(['message' => 'Invalid or expired discount code.'], 422);
        }

        $discountAmount = $discount->calculate($subtotal);

        return response()->json([
            'discount_code'   => $discount->code,
            'discount_type'   => $discount->type,
            'discount_value'  => $discount->value,
            'discount_amount' => $discountAmount,
            'message'         => "Discount applied! You save €{$discountAmount}",
        ]);
    }
}
