<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('volunteers', function (Blueprint $table) {
            $table->enum('status', ['Active','Inactive'])->default('Active')->after('remarks');
        });

        // Backfill existing rows
        DB::table('volunteers')->whereNull('status')->update(['status' => 'Active']);
    }

    public function down(): void
    {
        Schema::table('volunteers', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
