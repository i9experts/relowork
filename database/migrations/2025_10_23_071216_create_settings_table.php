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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained('organizations')->nullOnDelete();
            // Organization Information
            $table->string('logo_path')->nullable();
            $table->string('favicon_path')->nullable();
            $table->string('organization_name')->nullable();
            $table->string('email_address')->nullable();
            $table->string('phone_number', 20)->nullable();
            $table->string('address')->nullable();

            // Notification Preferences
            $table->boolean('email_notifications')->default(true);
            $table->boolean('push_notifications')->default(true);
            $table->boolean('aid_approval_alerts')->default(true);

            // Security Settings
            $table->boolean('two_factor_enabled')->default(false);
            $table->boolean('session_timeout_enabled')->default(false);
            $table->integer('session_timeout_minutes')->default(30); // <-- Added field

            // Appearance
            $table->enum('theme', ['Light', 'Dark'])->default('Light');
            $table->enum('language', ['English', 'Urdu'])->default('English');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
