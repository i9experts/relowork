<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SurveyMember extends Model
{
    public function survey()
    {
        return $this->belongsTo(Surveys::class);
    }

    public function person()
    {
        return $this->belongsTo(FamilyMember::class, 'person_id');
    }
}
