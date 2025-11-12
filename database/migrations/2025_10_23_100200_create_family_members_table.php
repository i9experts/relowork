<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('family_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->nullable()->constrained('organizations')->nullOnDelete();
            $table->foreignId('family_id')->constrained('families')->cascadeOnDelete();
            $table->string('name');
            $table->unsignedTinyInteger('age')->nullable();
            $table->string('relation')->nullable();
            $table->string('education')->nullable();
            $table->string('occupation')->nullable();
            $table->string('cnic', 15)->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->index(['family_id','relation']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('family_members');
    }
};
