<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('survey_uploads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->nullable()->constrained('organizations')->nullOnDelete();
            $table->foreignId('survey_id')->constrained('surveys')->cascadeOnDelete();
            $table->string('type');
            $table->string('url');
            $table->softDeletes();
            $table->timestamps();
            $table->index(['survey_id','type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('survey_uploads');
    }
};
