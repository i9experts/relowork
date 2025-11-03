<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinancialManagement extends Model
{
    protected $table = 'financial_management';
    protected $guarded = [];
    public function aidProgram()
    {
        return $this->belongsTo(AidProgram::class, 'aid_program_id');
    }

    public function family()
    {
        return $this->belongsTo(Family::class, 'family_id');
    }

    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
