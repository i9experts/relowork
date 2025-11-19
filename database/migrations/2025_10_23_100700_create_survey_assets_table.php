<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('survey_assets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained('organizations')->nullOnDelete();
            $table->foreignId('survey_id')->constrained('surveys')->cascadeOnDelete();
            $table->string('asset');
            $table->boolean('present')->default(false);
            $table->softDeletes();            
            $table->timestamps();
            $table->index(['survey_id','asset']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('survey_assets');
    }
};
