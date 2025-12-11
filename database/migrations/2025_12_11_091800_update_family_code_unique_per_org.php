<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('families', function (Blueprint $table) {
            // Drop global unique index on family_code
            $table->dropUnique('families_family_code_unique');

            // Add composite unique index per organization
            $table->unique(['organization_id', 'family_code']);
        });
    }

    public function down(): void
    {
        Schema::table('families', function (Blueprint $table) {
            // Revert to global unique index
            $table->dropUnique(['organization_id', 'family_code']);
            $table->unique('family_code');
        });
    }
};

