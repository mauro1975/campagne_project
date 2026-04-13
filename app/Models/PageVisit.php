<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageVisit extends Model
{
    protected $fillable = ['path', 'page_title', 'user_id', 'session_id', 'ip_address', 'user_agent', 'device_type', 'referer'];
}
