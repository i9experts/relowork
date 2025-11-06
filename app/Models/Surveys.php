<?php

namespace App\Models;

use App\Models\Family;
use App\Models\Volunteer;
use App\Models\SurveyNeed;
use App\Models\SurveyAsset;
use App\Models\SurveyMember;
use App\Models\SurveyUpload;
use App\Models\SurveyOutcome;
use Illuminate\Database\Eloquent\Model;

class Surveys extends Model
{
    protected $guarded = [];

    protected $casts = [
        'flags_json' => 'array',
        'recommendations_json' => 'array',
    ];

    public function family()
    {
        return $this->belongsTo(Family::class);
    }

    public function enumerator()
    {
        return $this->belongsTo(Volunteer::class, 'enumerator_id');
    }

    public function members()
    {
        return $this->hasMany(SurveyMember::class, 'survey_id');
    }

    public function needs()
    {
        return $this->hasMany(SurveyNeed::class, 'survey_id');
    }

    public function assets()
    {
        return $this->hasMany(SurveyAsset::class, 'survey_id');
    }

    public function uploads()
    {
        return $this->hasMany(SurveyUpload::class, 'survey_id');
    }

    public function outcomes()
    {
        return $this->hasMany(SurveyOutcome::class, 'survey_id');
    }
}
