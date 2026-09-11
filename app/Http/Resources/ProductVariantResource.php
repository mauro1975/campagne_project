<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductVariantResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'product_id'       => $this->product_id,
            'color'            => $this->color,
            'color_hex'        => $this->color_hex,
            'size'             => $this->size,
            'stock'            => $this->stock,
            'price_adjustment' => (float) $this->price_adjustment,
            'sku'              => $this->sku,
        ];
    }
}
