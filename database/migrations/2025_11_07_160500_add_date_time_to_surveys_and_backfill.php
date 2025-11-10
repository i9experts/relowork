<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('surveys', function (Blueprint $table) {
            if (!Schema::hasColumn('surveys', 'date_time')) {
                $table->dateTime('date_time')->nullable()->after('survey_mode');
            }
        });
        // Backfill date_time from existing ts (if present)
        if (Schema::hasColumn('surveys', 'ts')) {
            DB::statement('UPDATE surveys SET date_time = ts WHERE date_time IS NULL AND ts IS NOT NULL');
        }
    }

    public function down(): void
    {
        // Optionally copy back to ts on rollback
        if (Schema::hasColumn('surveys', 'ts') && Schema::hasColumn('surveys', 'date_time')) {
            DB::statement('UPDATE surveys SET ts = date_time WHERE date_time IS NOT NULL');
        }
        Schema::table('surveys', function (Blueprint $table) {
            if (Schema::hasColumn('surveys', 'date_time')) {
                $table->dropColumn('date_time');
            }
        });
    }
};
