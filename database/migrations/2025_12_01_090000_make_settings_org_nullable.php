<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('settings')) {
            try {
                DB::statement('ALTER TABLE `settings` DROP FOREIGN KEY `settings_organization_id_foreign`');
            } catch (\Throwable $e) { /* ignore */
            }

            try {
                DB::statement('ALTER TABLE `settings` MODIFY `organization_id` BIGINT UNSIGNED NULL');
            } catch (\Throwable $e) { /* ignore */
            }

            try {
                DB::statement('ALTER TABLE `settings` ADD CONSTRAINT `settings_organization_id_foreign` FOREIGN KEY (`organization_id`) REFERENCES `organizations`(`id`) ON DELETE SET NULL');
            } catch (\Throwable $e) { /* ignore */
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('settings')) {
            try {
                DB::statement('ALTER TABLE `settings` DROP FOREIGN KEY `settings_organization_id_foreign`');
            } catch (\Throwable $e) { /* ignore */
            }

            try {
                DB::statement('ALTER TABLE `settings` MODIFY `organization_id` BIGINT UNSIGNED NOT NULL');
            } catch (\Throwable $e) { /* ignore */
            }

            try {
                DB::statement('ALTER TABLE `settings` ADD CONSTRAINT `settings_organization_id_foreign` FOREIGN KEY (`organization_id`) REFERENCES `organizations`(`id`) ON DELETE CASCADE');
            } catch (\Throwable $e) { /* ignore */
            }
        }
    }
};
