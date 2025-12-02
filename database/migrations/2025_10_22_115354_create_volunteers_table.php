<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('volunteers', function (Blueprint $table) {
            $table->id();
            
            // Foreign keys
            $table->foreignId('organization_id')->constrained('organizations')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();

            // Basic info
            $table->string('volunteer_code')->unique()->nullable();
            $table->string('full_name');
            $table->string('cnic', 15)->nullable()->index();
            $table->string('contact_number', 20)->nullable();
            $table->string('email')->nullable();
            $table->enum('gender', ['Male', 'Female', 'Other'])->nullable();
            $table->date('dob')->nullable();
            $table->string('address')->nullable();

            // Education & role info
            $table->foreignId('education_level_id')->nullable()->constrained('education_levels')->nullOnDelete();
            $table->foreignId('availability_id')->nullable()->constrained('availabilities')->nullOnDelete();
            $table->foreignId('volunteer_role_id')->nullable()->constrained('volunteer_roles')->nullOnDelete();

            // Projects & languages
            $table->json('preferred_project_type_ids')->nullable();
            $table->json('assigned_project_type_ids')->nullable();
            $table->json('language_ids')->nullable();

            // Extra info
            $table->text('skills')->nullable();
            $table->string('emergency_contact', 20)->nullable();
            $table->date('joining_date')->nullable();
            $table->string('picture_path')->nullable();
            $table->text('remarks')->nullable();
            $table->enum('status', ['Active', 'Inactive'])->default('Active');

            // Soft deletes & timestamps
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('volunteers');
    }
};
