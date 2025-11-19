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
        Schema::table('organizations', function (Blueprint $table) {
            if (!Schema::hasColumn('organizations', 'domain')) {
                $table->string('domain')->unique()->nullable()->after('email');
            }
            if (!Schema::hasColumn('organizations', 'email_verified_at')) {
                $table->timestamp('email_verified_at')->nullable()->after('domain');
            }
            if (!Schema::hasColumn('organizations', 'otp_code')) {
                $table->string('otp_code', 10)->nullable()->after('email_verified_at');
            }
            if (!Schema::hasColumn('organizations', 'otp_expires_at')) {
                $table->timestamp('otp_expires_at')->nullable()->after('otp_code');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            if (Schema::hasColumn('organizations', 'otp_expires_at')) {
                $table->dropColumn('otp_expires_at');
            }
            if (Schema::hasColumn('organizations', 'otp_code')) {
                $table->dropColumn('otp_code');
            }
            if (Schema::hasColumn('organizations', 'email_verified_at')) {
                $table->dropColumn('email_verified_at');
            }
            if (Schema::hasColumn('organizations', 'domain')) {
                $table->dropColumn('domain');
            }
        });
    }
};
