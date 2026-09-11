<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PageVisitResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'path'        => $this->path,
            'page_title'  => $this->page_title,
            'user_id'     => $this->user_id,
            'session_id'  => $this->session_id,
            'ip_address'  => $this->ip_address,
            'user_agent'  => $this->user_agent,
            'device_type' => $this->device_type,
            'referer'     => $this->referer,
            'created_at'  => $this->created_at,
        ];
    }
}
