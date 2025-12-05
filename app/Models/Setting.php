<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Setting extends Model
{
    use SoftDeletes;

    protected $table = 'settings';

    protected $guarded = [];

    protected $casts = [
        'email_notifications' => 'boolean',
        'push_notifications' => 'boolean',
        'aid_approval_alerts' => 'boolean',
        'two_factor_enabled' => 'boolean',
        'session_timeout_enabled' => 'boolean',
        'session_timeout_minutes' => 'integer',
    ];
}
