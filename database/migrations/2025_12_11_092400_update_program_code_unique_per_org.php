<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('aid_programs', function (Blueprint $table) {
            $table->dropUnique('aid_programs_program_code_unique');
            $table->unique(['organization_id', 'program_code']);
        });
    }

    public function down(): void
    {
        Schema::table('aid_programs', function (Blueprint $table) {
            $table->dropUnique(['organization_id', 'program_code']);
            $table->unique('program_code');
        });
    }
};

