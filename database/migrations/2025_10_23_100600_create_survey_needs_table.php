<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('survey_needs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->nullable()->constrained('organizations')->nullOnDelete();
            $table->foreignId('survey_id')->constrained('surveys')->cascadeOnDelete();
            $table->string('sector');
            $table->unsignedTinyInteger('severity')->default(0); // 0-3
            $table->text('details')->nullable();
            $table->enum('priority', ['High','Medium','Low'])->nullable();
            $table->softDeletes();            
            $table->timestamps();
            $table->index(['survey_id','sector']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('survey_needs');
    }
};
