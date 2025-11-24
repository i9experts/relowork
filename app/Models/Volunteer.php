<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Volunteer extends Model
{
    protected $guarded = [];

    protected $casts = [
        'preferred_project_type_ids' => 'array',
        'assigned_project_type_ids' => 'array',
        'language_ids' => 'array',
        'dob' => 'date',
        'joining_date' => 'date',
    ];

    // Model boot logic moved to controller per request

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function educationLevel()
    {
        return $this->belongsTo(EducationLevel::class);
    }

    public function availability()
    {
        return $this->belongsTo(Availability::class);
    }

    public function volunteerRole()
    {
        return $this->belongsTo(VolunteerRole::class);
    }

    public function families()
    {
        return $this->hasMany(Family::class);
    }

    public function aidPrograms()
    {
        return $this->belongsToMany(AidProgram::class, 'aid_program_volunteer');
    }

    public function surveys()
    {
        return $this->hasMany(Surveys::class, 'enumerator_id');
    }
}
