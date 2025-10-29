<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Volunteer extends Model
{
    protected $guarded = [];

    protected $casts = [
        'preferred_projects' => 'array',
        'dob' => 'date',
        'joining_date' => 'date',
    ];

    // Model boot logic moved to controller per request

    public function user()
    {
        return $this->belongsTo(User::class);
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
