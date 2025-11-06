<?php

namespace App\Models;

use App\Models\Surveys;
use App\Models\FamilyMember;
use Illuminate\Database\Eloquent\Model;

class SurveyMember extends Model
{
    protected $guarded = [];

    public function survey()
    {
        return $this->belongsTo(Surveys::class);
    }

    public function person()
    {
        return $this->belongsTo(FamilyMember::class, 'person_id');
    }
}
