<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailCampaign extends Model
{
    protected $fillable = [
        'name', 'subject', 'body', 'status',
        'filter_product_ids', 'filter_category_ids',
        'min_orders', 'target_registered', 'target_guests',
        'total_recipients', 'sent_count', 'sent_at',
    ];

    protected $casts = [
        'filter_product_ids'  => 'array',
        'filter_category_ids' => 'array',
        'target_registered'   => 'boolean',
        'target_guests'       => 'boolean',
        'sent_at'             => 'datetime',
    ];

    public function logs()
    {
        return $this->hasMany(EmailCampaignLog::class, 'campaign_id');
    }
}
