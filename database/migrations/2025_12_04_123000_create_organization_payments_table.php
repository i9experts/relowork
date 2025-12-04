<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organization_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('organization_id');
            $table->decimal('amount', 12, 2);
            $table->string('currency', 8)->default('PKR');
            $table->timestamp('paid_at')->nullable();
            $table->string('method', 50)->nullable();
            $table->string('reference', 100)->nullable();
            $table->string('status', 30)->default('paid');
            $table->timestamps();

            $table->index(['organization_id', 'status', 'paid_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organization_payments');
    }
};

