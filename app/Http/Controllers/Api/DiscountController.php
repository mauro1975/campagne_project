<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DiscountResource;
use App\Models\Discount;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    public function index()
    {
        return DiscountResource::collection(Discount::latest()->paginate(30));
    }

    public function show(Discount $discount)
    {
        return new DiscountResource($discount);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code'       => 'required|string|unique:discounts',
            'type'       => 'required|in:percent,fixed',
            'value'      => 'required|numeric|min:0',
            'min_amount' => 'numeric|min:0',
            'max_uses'   => 'nullable|integer|min:1',
            'expires_at' => 'nullable|date',
            'is_active'  => 'boolean',
        ]);

        $data['code'] = strtoupper($data['code']);

        $discount = Discount::create($data);

        return (new DiscountResource($discount))->response()->setStatusCode(201);
    }

    public function update(Request $request, Discount $discount)
    {
        $data = $request->validate([
            'code'       => 'string|unique:discounts,code,' . $discount->id,
            'type'       => 'in:percent,fixed',
            'value'      => 'numeric|min:0',
            'min_amount' => 'numeric|min:0',
            'max_uses'   => 'nullable|integer|min:1',
            'expires_at' => 'nullable|date',
            'is_active'  => 'boolean',
        ]);

        if (isset($data['code'])) {
            $data['code'] = strtoupper($data['code']);
        }

        $discount->update($data);

        return new DiscountResource($discount);
    }

    public function destroy(Discount $discount)
    {
        $discount->delete();

        return response()->json(['message' => 'Discount deleted.']);
    }
}
