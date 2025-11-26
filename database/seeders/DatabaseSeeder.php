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
        $role = [
            'name' => 'Admin',
            'slug' => 'admin',
            'description' => 'System Administrator',
            'is_system' => true,
            'organization_id' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        DB::table('roles')->updateOrInsert(['slug' => $role['slug']], $role);

        $user = User::query()->updateOrCreate(
            ['email' => 'relowork@gmail.com'],
            [
                'name' => 'Super Administrator',
                'password' => bcrypt('Relowork@123'),
                'is_admin' => true,
                'permissions' => ['admin' => true],
                'organization_id' => null,
            ]
        );

        // Attach Admin role to super admin
        $adminRoleId = DB::table('roles')->where('slug', 'admin')->value('id');
        if ($adminRoleId && $user) {
            DB::table('role_user')->updateOrInsert(
                ['user_id' => $user->id, 'role_id' => $adminRoleId],
                ['created_at' => now(), 'updated_at' => now()]
            );
        }
    }
}
