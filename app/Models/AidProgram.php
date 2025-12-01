<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AidProgram extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    public function families()
    {
        return $this->belongsToMany(Family::class, 'aid_program_family');
    }

    public function volunteers()
    {
        return $this->belongsToMany(Volunteer::class, 'aid_program_volunteer');
    }

    public function attachments()
    {
        return $this->hasMany(AidProgramAttachment::class);
    }

    public function transactions()
    {
        return $this->hasMany(FinancialManagement::class, 'aid_program_id');
    }
}
