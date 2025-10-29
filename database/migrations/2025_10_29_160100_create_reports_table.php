<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->enum('report_type', ['financial','aid_distribution','family_assessment','volunteer_activity','survey_summary','custom'])->default('custom');
            $table->date('period_start')->nullable();
            $table->date('period_end')->nullable();
            $table->json('filters_json')->nullable();
            $table->string('file_path')->nullable();
            $table->enum('status', ['queued','processing','ready','failed'])->default('queued');
            $table->unsignedBigInteger('size_bytes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->index(['report_type','status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
