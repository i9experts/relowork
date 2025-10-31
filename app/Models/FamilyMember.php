<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FamilyMember extends Model
{
    protected $guarded = [];
    public function family()
    {
        return $this->belongsTo(Family::class);
    }
}
