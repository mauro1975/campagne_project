<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DiscountResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'code'       => $this->code,
            'type'       => $this->type,
            'value'      => (float) $this->value,
            'min_amount' => (float) $this->min_amount,
            'max_uses'   => $this->max_uses,
            'uses'       => $this->uses,
            'expires_at' => $this->expires_at,
            'is_active'  => $this->is_active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
