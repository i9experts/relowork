<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SurveyNeed extends Model
{
    protected $guarded = [];

    public function survey()
    {
        return $this->belongsTo(Surveys::class);
    }
}
