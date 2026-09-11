<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
            'user_id'         => $this->user_id,
            'order_number'    => $this->order_number,
            'email'           => $this->email,
            'name'            => $this->name,
            'phone'           => $this->phone,
            'address'         => $this->address,
            'city'            => $this->city,
            'postal_code'     => $this->postal_code,
            'country'         => $this->country,
            'subtotal'        => (float) $this->subtotal,
            'discount_amount' => (float) $this->discount_amount,
            'discount_code'   => $this->discount_code,
            'shipping'        => (float) $this->shipping,
            'total'           => (float) $this->total,
            'payment_method'  => $this->payment_method,
            'payment_status'  => $this->payment_status,
            'payment_id'      => $this->payment_id,
            'status'          => $this->status,
            'notes'           => $this->notes,
            'items'           => OrderItemResource::collection($this->whenLoaded('items')),
            'user'            => new UserResource($this->whenLoaded('user')),
            'created_at'      => $this->created_at,
            'updated_at'      => $this->updated_at,
        ];
    }
}
