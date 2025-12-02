<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surveys', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained('organizations')->cascadeOnDelete();
            $table->string('survey_code')->unique()->nullable();
            $table->foreignId('family_id')->nullable()->constrained('families')->nullOnDelete();

            // ✅ New addition: enumerator
            $table->unsignedBigInteger('enumerator_id')->nullable()->index();
            $table->foreign('enumerator_id')->references('id')->on('volunteers')->nullOnDelete();

            $table->enum('survey_mode', ['In-person', 'Phone', 'WhatsApp', 'Web'])->nullable();
            $table->decimal('lat', 10, 6)->nullable();
            $table->decimal('lon', 10, 6)->nullable();
            $table->string('consent_type')->nullable();
            $table->boolean('revisit_flag')->default(false);
            $table->date('revisit_from')->nullable();
            $table->date('revisit_to')->nullable();
            $table->dateTime('date_time')->nullable();
            $table->json('flags_json')->nullable();
            $table->unsignedInteger('score')->nullable();
            $table->json('recommendations_json')->nullable();
            $table->softDeletes();
            $table->timestamps();

            // ✅ Composite index
            $table->index(['family_id', 'enumerator_id']);
        });

        // ✅ Drop ts column directly here
        if (Schema::hasColumn('surveys', 'ts')) {
            Schema::table('surveys', function (Blueprint $table) {
                $table->dropColumn('ts');
            });
        }
    }

    public function down(): void
    {
        Schema::table('surveys', function(Blueprint $table){
            $table->dropForeign(['enumerator_id']);
        });

        Schema::dropIfExists('surveys');
    }
};
