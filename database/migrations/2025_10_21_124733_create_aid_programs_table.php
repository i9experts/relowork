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
        Schema::create('aid_programs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->nullable()->constrained('organizations')->nullOnDelete();
            $table->string('program_code')->unique()->nullable();
            $table->string('program_title');
            $table->enum('program_type', ['Food','Education','Water','Medical','Housing','Emergency'])->nullable();
            $table->text('description')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('location')->nullable();
            $table->unsignedInteger('target_beneficiaries')->nullable();
            $table->enum('status', ['Planned','Active','Completed','On Hold'])->default('Planned');
            $table->unsignedBigInteger('total_budget')->nullable();
            $table->enum('fund_source', ['Zakat','Sadaqah','General Donation','Grant'])->nullable();
            $table->unsignedBigInteger('volunteer_id')->nullable()->index();
            $table->enum('monitoring_frequency', ['Weekly','Monthly','Quarterly'])->nullable();
            $table->text('progress_notes')->nullable();
            $table->softDeletes();            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aid_programs');
    }
};
