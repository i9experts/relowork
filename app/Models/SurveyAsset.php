<?php

namespace App\Models;

use App\Models\Surveys;
use Illuminate\Database\Eloquent\Model;

class SurveyAsset extends Model
{
    protected $guarded = [];

    public function survey()
    {
        return $this->belongsTo(Surveys::class);
    }
}
