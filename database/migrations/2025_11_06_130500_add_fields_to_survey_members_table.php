<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('survey_members', function (Blueprint $table) {
            if (!Schema::hasColumn('survey_members', 'person_id')) {
                $table->unsignedBigInteger('person_id')->nullable()->after('survey_id');
            }
            if (!Schema::hasColumn('survey_members', 'age')) {
                $table->unsignedTinyInteger('age')->nullable()->after('name');
            }
            if (!Schema::hasColumn('survey_members', 'gender')) {
                $table->enum('gender', ['Male','Female','Other'])->nullable()->after('age');
            }
            if (!Schema::hasColumn('survey_members', 'relation')) {
                $table->string('relation')->nullable()->after('gender');
            }
            if (!Schema::hasColumn('survey_members', 'cnic')) {
                $table->string('cnic', 15)->nullable()->after('relation');
            }
            if (!Schema::hasColumn('survey_members', 'disability')) {
                $table->string('disability')->nullable()->after('cnic');
            }
            if (!Schema::hasColumn('survey_members', 'school_work')) {
                $table->string('school_work')->nullable()->after('disability');
            }
        });
    }

    public function down(): void
    {
        Schema::table('survey_members', function (Blueprint $table) {
            if (Schema::hasColumn('survey_members', 'school_work')) {
                $table->dropColumn('school_work');
            }
            if (Schema::hasColumn('survey_members', 'disability')) {
                $table->dropColumn('disability');
            }
            if (Schema::hasColumn('survey_members', 'cnic')) {
                $table->dropColumn('cnic');
            }
            if (Schema::hasColumn('survey_members', 'relation')) {
                $table->dropColumn('relation');
            }
            if (Schema::hasColumn('survey_members', 'gender')) {
                $table->dropColumn('gender');
            }
            if (Schema::hasColumn('survey_members', 'age')) {
                $table->dropColumn('age');
            }
            if (Schema::hasColumn('survey_members', 'person_id')) {
                $table->dropColumn('person_id');
            }
        });
    }
};
