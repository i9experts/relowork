<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrganizationPayment extends Model
{
    protected $guarded = [];

    protected $casts = [
        'paid_at' => 'datetime',
        'amount' => 'decimal:2',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}

