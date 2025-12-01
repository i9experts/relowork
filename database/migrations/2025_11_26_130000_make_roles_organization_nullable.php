<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $driver = config('database.default');
        $conn = config("database.connections.$driver.driver");

        if ($conn === 'mysql') {
            DB::statement('ALTER TABLE `roles` MODIFY `organization_id` BIGINT UNSIGNED NULL');
        }
    }

    public function down(): void
    {
        $driver = config('database.default');
        $conn = config("database.connections.$driver.driver");

        if ($conn === 'mysql') {
            DB::statement('ALTER TABLE `roles` MODIFY `organization_id` BIGINT UNSIGNED NOT NULL');
        }
    }
};
