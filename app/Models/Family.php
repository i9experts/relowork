<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Family extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    public function volunteer()
    {
        return $this->belongsTo(Volunteer::class, 'volunteer_id');
    }

    public function members()
    {
        return $this->hasMany(FamilyMember::class);
    }

    public function aidPrograms()
    {
        return $this->belongsToMany(AidProgram::class, 'aid_program_family');
    }

    public function surveys()
    {
        return $this->hasMany(Surveys::class);
    }

    public function transactions()
    {
        return $this->hasMany(FinancialManagement::class, 'family_id');
    }
}
