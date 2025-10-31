<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AidProgramAttachment extends Model
{
    protected $guarded = [];
    public function aidProgram()
    {
        return $this->belongsTo(AidProgram::class);
    }
}
