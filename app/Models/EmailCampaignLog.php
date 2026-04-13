<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailCampaignLog extends Model
{
    protected $fillable = ['campaign_id', 'email', 'name', 'status', 'error'];

    public function campaign()
    {
        return $this->belongsTo(EmailCampaign::class, 'campaign_id');
    }
}
