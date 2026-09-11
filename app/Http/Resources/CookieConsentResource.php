<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CookieConsentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'session_id' => $this->session_id,
            'ip_address' => $this->ip_address,
            'locale'     => $this->locale,
            'analytics'  => $this->analytics,
            'marketing'  => $this->marketing,
            'choice'     => $this->choice,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
