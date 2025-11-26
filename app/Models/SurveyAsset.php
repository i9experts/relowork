<?php

namespace App\Models;

use App\Models\Surveys;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SurveyAsset extends Model
{
    use SoftDeletes;
    protected $guarded = [];

    public function survey()
    {
        return $this->belongsTo(Surveys::class);
    }
}
