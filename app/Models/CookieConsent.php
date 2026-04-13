<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CookieConsent extends Model
{
    protected $fillable = [
        'session_id', 'ip_address', 'locale',
        'analytics', 'marketing', 'choice',
    ];

    protected $casts = [
        'analytics' => 'boolean',
        'marketing' => 'boolean',
    ];
}
