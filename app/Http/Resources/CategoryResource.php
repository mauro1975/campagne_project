<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'name_en'     => $this->name_en,
            'slug'        => $this->slug,
            'description' => $this->description,
            'description_en' => $this->description_en,
            'image'       => $this->image,
            'image_url'   => $this->image ? asset($this->image) : null,
            'is_active'   => $this->is_active,
            'sort_order'  => $this->sort_order,
            'products'    => ProductResource::collection($this->whenLoaded('products')),
            'created_at'  => $this->created_at,
            'updated_at'  => $this->updated_at,
        ];
    }
}
