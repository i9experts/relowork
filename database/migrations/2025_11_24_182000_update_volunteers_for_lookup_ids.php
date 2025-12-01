<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('volunteers', function (Blueprint $table) {
            if (! Schema::hasColumn('volunteers', 'education_level_id')) {
                $table->foreignId('education_level_id')->nullable()->after('address')->constrained('education_levels')->nullOnDelete();
            }
            if (! Schema::hasColumn('volunteers', 'availability_id')) {
                $table->foreignId('availability_id')->nullable()->after('education_level_id')->constrained('availabilities')->nullOnDelete();
            }
            if (! Schema::hasColumn('volunteers', 'volunteer_role_id')) {
                $table->foreignId('volunteer_role_id')->nullable()->after('availability_id')->constrained('volunteer_roles')->nullOnDelete();
            }
            if (! Schema::hasColumn('volunteers', 'preferred_project_type_ids')) {
                $table->json('preferred_project_type_ids')->nullable()->after('preferred_projects');
            }
            if (! Schema::hasColumn('volunteers', 'assigned_project_type_ids')) {
                $table->json('assigned_project_type_ids')->nullable()->after('preferred_project_type_ids');
            }
            if (! Schema::hasColumn('volunteers', 'language_ids')) {
                $table->json('language_ids')->nullable()->after('languages');
            }
        });

        // Optional: basic backfill from name to id where possible
        try {
            $levels = DB::table('education_levels')->pluck('id', 'name');
            $avails = DB::table('availabilities')->pluck('id', 'name');
            $roles = DB::table('volunteer_roles')->pluck('id', 'name');

            DB::table('volunteers')->orderBy('id')->chunkById(100, function ($rows) use ($levels, $avails, $roles) {
                foreach ($rows as $row) {
                    $update = [];
                    if (! empty($row->education_level) && isset($levels[$row->education_level])) {
                        $update['education_level_id'] = $levels[$row->education_level];
                    }
                    if (! empty($row->availability) && isset($avails[$row->availability])) {
                        $update['availability_id'] = $avails[$row->availability];
                    }
                    if (! empty($row->role) && isset($roles[$row->role])) {
                        $update['volunteer_role_id'] = $roles[$row->role];
                    }
                    if (! empty($update)) {
                        DB::table('volunteers')->where('id', $row->id)->update($update);
                    }
                }
            });
        } catch (\Throwable $e) {
            // silently ignore backfill errors so migration still runs
        }

        Schema::table('volunteers', function (Blueprint $table) {
            if (Schema::hasColumn('volunteers', 'education_level')) {
                $table->dropColumn('education_level');
            }
            if (Schema::hasColumn('volunteers', 'availability')) {
                $table->dropColumn('availability');
            }
            if (Schema::hasColumn('volunteers', 'role')) {
                $table->dropColumn('role');
            }
            if (Schema::hasColumn('volunteers', 'languages')) {
                $table->dropColumn('languages');
            }
            if (Schema::hasColumn('volunteers', 'preferred_projects')) {
                $table->dropColumn('preferred_projects');
            }
        });
    }

    public function down(): void
    {
        Schema::table('volunteers', function (Blueprint $table) {
            if (! Schema::hasColumn('volunteers', 'education_level')) {
                $table->enum('education_level', ['Matric', 'Intermediate', 'Graduate', 'Post-Graduate'])->nullable();
            }
            if (! Schema::hasColumn('volunteers', 'availability')) {
                $table->enum('availability', ['Full-time', 'Part-time', 'Event-based'])->nullable();
            }
            if (! Schema::hasColumn('volunteers', 'role')) {
                $table->enum('role', ['Field Volunteer', 'Data Verifier', 'Supervisor'])->nullable();
            }
            if (! Schema::hasColumn('volunteers', 'languages')) {
                $table->string('languages')->nullable();
            }
            if (! Schema::hasColumn('volunteers', 'preferred_projects')) {
                $table->json('preferred_projects')->nullable();
            }

            if (Schema::hasColumn('volunteers', 'education_level_id')) {
                $table->dropConstrainedForeignId('education_level_id');
            }
            if (Schema::hasColumn('volunteers', 'availability_id')) {
                $table->dropConstrainedForeignId('availability_id');
            }
            if (Schema::hasColumn('volunteers', 'volunteer_role_id')) {
                $table->dropConstrainedForeignId('volunteer_role_id');
            }
            if (Schema::hasColumn('volunteers', 'preferred_project_type_ids')) {
                $table->dropColumn('preferred_project_type_ids');
            }
            if (Schema::hasColumn('volunteers', 'assigned_project_type_ids')) {
                $table->dropColumn('assigned_project_type_ids');
            }
            if (Schema::hasColumn('volunteers', 'language_ids')) {
                $table->dropColumn('language_ids');
            }
        });
    }
};
