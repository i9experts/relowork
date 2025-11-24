<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Education levels
        Schema::create('education_levels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->nullable()->constrained('organizations')->nullOnDelete();
            $table->string('name');
            $table->string('slug')->nullable();
            $table->timestamps();
        });

        // Volunteer availability options
        Schema::create('availabilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->nullable()->constrained('organizations')->nullOnDelete();
            $table->string('name');
            $table->string('slug')->nullable();
            $table->timestamps();
        });

        // Project types (for preferred/assigned projects)
        Schema::create('project_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->nullable()->constrained('organizations')->nullOnDelete();
            $table->string('name');
            $table->string('slug')->nullable();
            $table->boolean('is_assignable')->default(true);
            $table->timestamps();
        });

        // Volunteer roles/designations
        Schema::create('volunteer_roles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->nullable()->constrained('organizations')->nullOnDelete();
            $table->string('name');
            $table->string('slug')->nullable();
            $table->timestamps();
        });

        // Languages master (for Pakistan and others)
        Schema::create('languages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('languages');
        Schema::dropIfExists('volunteer_roles');
        Schema::dropIfExists('project_types');
        Schema::dropIfExists('availabilities');
        Schema::dropIfExists('education_levels');
    }
};
