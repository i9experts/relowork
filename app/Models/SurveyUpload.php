<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SurveyUpload extends Model
{
    public function survey()
    {
        return $this->belongsTo(Surveys::class);
    }
}
