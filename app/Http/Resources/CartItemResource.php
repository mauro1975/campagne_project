<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'cart_id'    => $this->cart_id,
            'product_id' => $this->product_id,
            'variant_id' => $this->variant_id,
            'color'      => $this->color,
            'size'       => $this->size,
            'quantity'   => $this->quantity,
            'price'      => (float) $this->price,
            'subtotal'   => (float) ($this->price * $this->quantity),
            'product'    => new ProductResource($this->whenLoaded('product')),
        ];
    }
}
