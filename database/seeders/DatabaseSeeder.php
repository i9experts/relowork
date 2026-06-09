<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ---------------- Super Admin Role ----------------
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

        // ---------------- Roles ----------------
        DB::table('roles')->updateOrInsert(
            ['slug' => 'org_admin'],
            [
                'name' => 'Organization Admin',
                'slug' => 'org_admin',
                'description' => 'Organization Administrator',
                'is_system' => false,
                'organization_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // ---------------- Super Admin User ----------------
        $user = User::query()->updateOrCreate(
            ['email' => 'reliowork@gmail.com'],
            [
                'name' => 'Super Administrator',
                'password' => bcrypt('Reliowork@123'),
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

        // ---------------- Languages ----------------
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
            ['name' => 'English', 'code' => 'en'],
        ];

        foreach ($languages as $lang) {
            DB::table('languages')->updateOrInsert(
                ['name' => $lang['name']],
                ['name' => $lang['name'], 'code' => $lang['code'] ?? null, 'updated_at' => now(), 'created_at' => now()]
            );
        }

        // ---------------- Volunteer Role ----------------
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

        // ---------------- Education Levels ----------------
        $educationLevels = [
            ['name' => 'Primary School', 'description' => 'Basic education from grade 1 to 5'],
            ['name' => 'Middle School', 'description' => 'Education from grade 6 to 8'],
            ['name' => 'High School', 'description' => 'Secondary education, grade 9 to 10'],
            ['name' => 'Intermediate', 'description' => 'Higher secondary education, grade 11 and 12'],
            ['name' => 'Bachelor', 'description' => 'Undergraduate degree (BA, BSc, BCom, etc.)'],
            ['name' => 'Master', 'description' => 'Postgraduate degree (MA, MSc, MCom, etc.)'],
            ['name' => 'PhD', 'description' => 'Doctorate degree for advanced research'],
            ['name' => 'Diploma', 'description' => 'Professional diploma courses'],
            ['name' => 'Certificate', 'description' => 'Short-term certificate courses'],
        ];

        foreach ($educationLevels as $level) {
            DB::table('education_levels')->updateOrInsert(
                ['slug' => Str::slug($level['name'])],
                [
                    'name' => $level['name'],
                    'slug' => Str::slug($level['name']),
                    'organization_id' => null, // yaha specific organization id daal sakte ho
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        // ---------------- Availabilities ----------------
        $availabilities = [
            ['name' => 'Full-Time', 'description' => 'Available 40+ hours per week, Monday to Friday'],
            ['name' => 'Part-Time', 'description' => 'Available 20-30 hours per week, flexible days'],
            ['name' => 'Weekends Only', 'description' => 'Available only on Saturday and Sunday'],
            ['name' => 'Evenings', 'description' => 'Available in the evenings after 5 PM'],
            ['name' => 'Mornings', 'description' => 'Available in the mornings, usually before 12 PM'],
            ['name' => 'Flexible Hours', 'description' => 'Can adjust availability as per project needs'],
            ['name' => 'On Call', 'description' => 'Available on short notice for urgent tasks'],
        ];

        foreach ($availabilities as $avail) {
            DB::table('availabilities')->updateOrInsert(
                ['slug' => Str::slug($avail['name'])],
                [
                    'name' => $avail['name'],
                    'slug' => Str::slug($avail['name']),
                    'organization_id' => null, // agar organization specific ho to id set karo
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        // ---------------- Project Types ----------------
        $projectTypes = [
            ['name' => 'Community Development', 'description' => 'Projects focused on improving local communities and infrastructure'],
            ['name' => 'Education', 'description' => 'Projects related to schools, teaching, literacy, and educational programs'],
            ['name' => 'Healthcare', 'description' => 'Projects providing medical aid, health awareness, and clinics'],
            ['name' => 'Environmental Conservation', 'description' => 'Projects focused on environment, tree plantation, and sustainability'],
            ['name' => 'Disaster Relief', 'description' => 'Emergency aid and relief for natural disasters and crises'],
            ['name' => 'Youth Empowerment', 'description' => 'Projects that develop skills, leadership, and opportunities for youth'],
            ['name' => 'Women Empowerment', 'description' => 'Projects supporting women education, employment, and health'],
            ['name' => 'Arts & Culture', 'description' => 'Projects promoting cultural activities, arts, and heritage preservation'],
            ['name' => 'Sports & Recreation', 'description' => 'Projects related to sports, fitness, and recreational activities'],
        ];

        foreach ($projectTypes as $type) {
            DB::table('project_types')->updateOrInsert(
                ['slug' => Str::slug($type['name'])],
                [
                    'name' => $type['name'],
                    'slug' => Str::slug($type['name']),
                    'is_assignable' => true,
                    'organization_id' => null, // agar organization specific ho to id set kar do
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        // ---------------- Volunteer Roles ----------------
        $volunteerRoles = [
            ['name' => 'Supervisor', 'description' => 'Oversees and manages a team of volunteers'],
            ['name' => 'Team Lead', 'description' => 'Leads a specific project or activity within a team'],
            ['name' => 'Coordinator', 'description' => 'Coordinates between different projects and volunteers'],
            ['name' => 'Field Volunteer', 'description' => 'Works on ground with communities and project activities'],
            ['name' => 'Event Organizer', 'description' => 'Organizes events, campaigns, and awareness programs'],
            ['name' => 'Trainer', 'description' => 'Provides training sessions and skill development for volunteers'],
            ['name' => 'Mentor', 'description' => 'Guides and supports new volunteers in their tasks'],
            ['name' => 'Admin Support', 'description' => 'Provides administrative and logistical support for projects'],
        ];

        foreach ($volunteerRoles as $role) {
            DB::table('volunteer_roles')->updateOrInsert(
                ['slug' => Str::slug($role['name'])],
                [
                    'name' => $role['name'],
                    'slug' => Str::slug($role['name']),
                    'organization_id' => null, // agar organization specific ho to yaha id set kar do
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

    }
}
