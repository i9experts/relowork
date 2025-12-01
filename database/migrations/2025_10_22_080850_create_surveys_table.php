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
        Schema::create('surveys', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained('organizations')->nullOnDelete();
            $table->string('survey_code')->unique()->nullable();
            $table->foreignId('family_id')->nullable()->constrained('families')->nullOnDelete();
            $table->unsignedBigInteger('enumerator_id')->nullable()->index();
            $table->enum('survey_mode', ['In-person', 'Phone', 'WhatsApp', 'Web'])->nullable();
            $table->dateTime('ts')->nullable();
            $table->decimal('lat', 10, 6)->nullable();
            $table->decimal('lon', 10, 6)->nullable();
            $table->string('consent_type')->nullable();
            $table->boolean('revisit_flag')->default(false);
            $table->date('revisit_from')->nullable();
            $table->date('revisit_to')->nullable();
            $table->json('flags_json')->nullable();
            $table->unsignedInteger('score')->nullable();
            $table->json('recommendations_json')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->index(['family_id', 'enumerator_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surveys');
    }
};
