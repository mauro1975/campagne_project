<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'category_id'      => $this->category_id,
            'name'             => $this->name,
            'name_en'          => $this->name_en,
            'slug'             => $this->slug,
            'sku'              => $this->sku,
            'description'      => $this->description,
            'description_en'   => $this->description_en,
            'price'            => (float) $this->price,
            'final_price'      => $this->final_price,
            'compare_price'    => $this->compare_price ? (float) $this->compare_price : null,
            'discount_percent' => $this->discount_percent,
            'stock'            => $this->stock,
            'image'            => $this->image,
            'image_url'        => $this->image ? asset($this->image) : null,
            'images'           => $this->images,
            'available_colors' => $this->available_colors,
            'available_sizes'  => $this->available_sizes,
            'is_active'        => $this->is_active,
            'is_featured'      => $this->is_featured,
            'is_best_seller'   => $this->is_best_seller,
            'seo_title'        => $this->seo_title,
            'seo_description'  => $this->seo_description,
            'category'         => new CategoryResource($this->whenLoaded('category')),
            'variants'         => ProductVariantResource::collection($this->whenLoaded('variants')),
            'created_at'       => $this->created_at,
            'updated_at'       => $this->updated_at,
        ];
    }
}
