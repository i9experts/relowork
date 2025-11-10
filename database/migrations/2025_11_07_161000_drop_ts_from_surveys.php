<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('surveys', 'ts')) {
            Schema::table('surveys', function (Blueprint $table) {
                $table->dropColumn('ts');
            });
        }
    }

    public function down(): void
    {
        // Restore ts as nullable dateTime if rolled back
        if (!Schema::hasColumn('surveys', 'ts')) {
            Schema::table('surveys', function (Blueprint $table) {
                $table->dateTime('ts')->nullable()->after('survey_mode');
            });
        }
    }
};
