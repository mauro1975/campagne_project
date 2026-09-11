<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmailCampaignResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                  => $this->id,
            'name'                => $this->name,
            'subject'             => $this->subject,
            'body'                => $this->body,
            'status'              => $this->status,
            'filter_product_ids'  => $this->filter_product_ids,
            'filter_category_ids' => $this->filter_category_ids,
            'min_orders'          => $this->min_orders,
            'target_registered'   => $this->target_registered,
            'target_guests'       => $this->target_guests,
            'total_recipients'    => $this->total_recipients,
            'sent_count'          => $this->sent_count,
            'sent_at'             => $this->sent_at,
            'created_at'          => $this->created_at,
            'updated_at'          => $this->updated_at,
        ];
    }
}
