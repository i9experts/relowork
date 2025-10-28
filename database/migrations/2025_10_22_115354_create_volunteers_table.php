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
            $table->string('volunteer_code')->unique()->nullable();
            $table->string('full_name');
            $table->string('cnic', 15)->nullable()->index();
            $table->string('contact_number', 20)->nullable();
            $table->string('email')->nullable();
            $table->enum('gender', ['Male','Female','Other'])->nullable();
            $table->date('dob')->nullable();
            $table->string('address')->nullable();
            $table->enum('education_level', ['Matric','Intermediate','Graduate','Post-Graduate'])->nullable();
            $table->text('skills')->nullable();
            $table->string('languages')->nullable();
            $table->enum('availability', ['Full-time','Part-time','Event-based'])->nullable();
            $table->json('preferred_projects')->nullable();
            $table->string('emergency_contact', 20)->nullable();
            $table->enum('role', ['Field Volunteer','Data Verifier','Supervisor'])->nullable();
            $table->date('joining_date')->nullable();
            $table->string('picture_path')->nullable();
            $table->text('remarks')->nullable();
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
