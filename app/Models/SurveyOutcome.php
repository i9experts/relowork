<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SurveyOutcome extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    public function survey()
    {
        return $this->belongsTo(Surveys::class);
    }
}
