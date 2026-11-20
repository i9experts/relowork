<?php

namespace App\Models;

use App\Models\Concerns\ScopedToOrganizationOrGlobal;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EducationLevel extends Model
{
    use ScopedToOrganizationOrGlobal;
    use SoftDeletes;

    protected $guarded = [];
}
