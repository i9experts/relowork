<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // families.volunteer_id -> volunteers.id (nullable)
        if (Schema::hasColumn('families', 'volunteer_id')) {
            try {
                Schema::table('families', function (Blueprint $table) {
                    $table->foreign('volunteer_id')->references('id')->on('volunteers')->nullOnDelete();
                });
            } catch (\Throwable $e) {
                // ignore if FK already exists
            }
        }

        // surveys.enumerator_id -> volunteers.id (nullable)
        if (Schema::hasColumn('surveys', 'enumerator_id')) {
            try {
                Schema::table('surveys', function (Blueprint $table) {
                    $table->foreign('enumerator_id')->references('id')->on('volunteers')->nullOnDelete();
                });
            } catch (\Throwable $e) {
                // ignore if FK already exists
            }
        }

        // survey_members.person_id -> family_members.id (nullable)
        if (Schema::hasColumn('survey_members', 'person_id')) {
            try {
                Schema::table('survey_members', function (Blueprint $table) {
                    $table->foreign('person_id')->references('id')->on('family_members')->nullOnDelete();
                });
            } catch (\Throwable $e) {
                // ignore if FK already exists
            }
        }

        // Remove redundant volunteer_id from aid_programs (use pivot table instead)
        if (Schema::hasColumn('aid_programs', 'volunteer_id')) {
            Schema::table('aid_programs', function (Blueprint $table) {
                // drop foreign key if exists then column
                try { $table->dropForeign(['volunteer_id']); } catch (\Throwable $e) {}
                $table->dropColumn('volunteer_id');
            });
        }
    }

    public function down(): void
    {
        // Re-add aid_programs.volunteer_id (nullable, indexed)
        if (!Schema::hasColumn('aid_programs', 'volunteer_id')) {
            Schema::table('aid_programs', function (Blueprint $table) {
                $table->unsignedBigInteger('volunteer_id')->nullable()->index()->after('fund_source');
            });
        }

        // Drop FK survey_members.person_id
        Schema::table('survey_members', function (Blueprint $table) {
            try { $table->dropForeign(['person_id']); } catch (\Throwable $e) {}
        });

        // Drop FK surveys.enumerator_id
        Schema::table('surveys', function (Blueprint $table) {
            try { $table->dropForeign(['enumerator_id']); } catch (\Throwable $e) {}
        });

        // Drop FK families.volunteer_id
        Schema::table('families', function (Blueprint $table) {
            try { $table->dropForeign(['volunteer_id']); } catch (\Throwable $e) {}
        });
    }
};
