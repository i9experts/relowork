<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $role = [
            'name' => 'Super Admin',
            'slug' => 'super_admin',
            'description' => 'Platform Super Administrator',
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
                'is_admin' => false,
                'permissions' => ['super_admin' => true],
                'organization_id' => null,
            ]
        );

        // Attach Admin role to super admin
        $adminRoleId = DB::table('roles')->where('slug', 'super_admin')->value('id');
        if ($adminRoleId && $user) {
            DB::table('role_user')->updateOrInsert(
                ['user_id' => $user->id, 'role_id' => $adminRoleId],
                ['created_at' => now(), 'updated_at' => now()]
            );
        }

        // Seed Pakistani languages
        $languages = [
            ['name' => 'Urdu', 'code' => 'ur'],
            ['name' => 'Punjabi', 'code' => 'pa'],
            ['name' => 'Sindhi', 'code' => 'sd'],
            ['name' => 'Pashto', 'code' => 'ps'],
            ['name' => 'Balochi', 'code' => 'bal'],
            ['name' => 'Saraiki', 'code' => 'skr'],
            ['name' => 'Kashmiri', 'code' => 'ks'],
            ['name' => 'Hindko', 'code' => 'hnd'],
            ['name' => 'Brahui', 'code' => 'brh'],
            ['name' => 'Shina', 'code' => 'scl'],
            ['name' => 'Balti', 'code' => 'bft'],
            ['name' => 'Khowar', 'code' => 'khw'],
            ['name' => 'Wakhi', 'code' => 'wbl'],
            ['name' => 'Burushaski', 'code' => 'bsk'],
            ['name' => 'Marwari', 'code' => 'mwr'],
            ['name' => 'Gujarati', 'code' => 'gu'],
            ['name' => 'Dhatki', 'code' => 'dhi'],
            ['name' => 'Kalasha', 'code' => 'kls'],
            ['name' => 'Pahari-Potwari', 'code' => 'phr'],
            // Add English
            ['name' => 'English', 'code' => 'en'],
        ];

        foreach ($languages as $lang) {
            DB::table('languages')->updateOrInsert(
                ['name' => $lang['name']],
                ['name' => $lang['name'], 'code' => $lang['code'] ?? null, 'updated_at' => now(), 'created_at' => now()]
            );
        }

        // Ensure Volunteer role is not marked as system role
        DB::table('roles')->updateOrInsert(
            ['slug' => 'volunteer'],
            [
                'name' => 'Volunteer',
                'slug' => 'volunteer',
                'description' => 'Volunteer User',
                'is_system' => false,
                'organization_id' => null,
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );

    }
}
