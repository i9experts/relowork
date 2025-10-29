<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed default roles
        $roles = [
            ['name' => 'Admin', 'slug' => 'admin', 'description' => 'System Administrator', 'is_system' => true, 'created_at' => now(), 'updated_at' => now()],
        ];
        foreach ($roles as $role) {
            DB::table('roles')->updateOrInsert(['slug' => $role['slug']], $role);
        }

        // Create/update only the Admin user (volunteers will be created by Admin later)
        $user = User::query()->updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Administrator',
                // Laravel 12 will hash via casts on the User model
                'password' => 'password',
            ]
        );

        // Attach Admin role to test user
        $adminRoleId = DB::table('roles')->where('slug', 'admin')->value('id');
        if ($adminRoleId && $user) {
            DB::table('role_user')->updateOrInsert(
                ['user_id' => $user->id, 'role_id' => $adminRoleId],
                ['created_at' => now(), 'updated_at' => now()]
            );
        }
    }
}
