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
        Schema::create('families', function (Blueprint $table) {
            $table->id();
            $table->string('family_code')->unique()->nullable();
            $table->string('head_of_family_name');
            $table->string('cnic', 15)->nullable()->index();
            $table->enum('gender', ['Male', 'Female', 'Other'])->nullable();
            $table->unsignedTinyInteger('age')->nullable();
            $table->enum('marital_status', ['Married', 'Widowed', 'Divorced', 'Single'])->nullable();
            $table->string('contact_number', 20)->nullable();
            $table->text('address')->nullable();
            $table->string('province')->nullable();
            $table->string('city')->nullable();
            $table->string('area')->nullable();
            $table->decimal('gps_lat', 10, 6)->nullable();
            $table->decimal('gps_long', 10, 6)->nullable();
            $table->unsignedSmallInteger('family_members_count')->default(0);
            $table->unsignedInteger('monthly_income')->nullable();
            $table->unsignedInteger('monthly_expense')->nullable();
            $table->string('income_source')->nullable();
            $table->boolean('zakat_eligibility')->default(false);
            $table->text('comment_note')->nullable();
            $table->unsignedBigInteger('volunteer_id')->nullable()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('families');
    }
};
