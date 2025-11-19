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
        Schema::create('financial_management', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained('organizations')->nullOnDelete();
            $table->string('transaction_code')->unique()->nullable();
            $table->date('date');
            $table->enum('transaction_type', ['Donation','Expense','Disbursement','Transfer']);
            $table->enum('category', ['Zakat','Sadaqah','Project Fund','Admin Cost'])->nullable();
            $table->enum('payment_mode', ['Cash','Bank Transfer','Easypaisa','Cheque'])->nullable();
            $table->unsignedBigInteger('amount');
            $table->string('donor_name')->nullable();
            $table->foreignId('aid_program_id')->nullable()->constrained('aid_programs')->nullOnDelete();
            $table->foreignId('family_id')->nullable()->constrained('families')->nullOnDelete();
            $table->string('payment_proof_path')->nullable();
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('remarks')->nullable();
            $table->softDeletes();            
            $table->timestamps();
            $table->index(['aid_program_id','family_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('financial_management');
    }
};
