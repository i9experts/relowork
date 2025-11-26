<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AidProgramAttachment extends Model
{
    use SoftDeletes;
    protected $guarded = [];
    public function aidProgram()
    {
        return $this->belongsTo(AidProgram::class);
    }
}
