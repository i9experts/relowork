<?php

use App\Models\AidProgram;
use App\Models\Family;
use App\Models\FinancialManagement;
use App\Models\Organization;
use App\Models\User;
use App\Models\Volunteer;

it('returns scoped notifications for an org user with permissions', function () {
    $org = Organization::create([
        'registration_number' => 'REG-0002',
        'organization_name' => 'Org A',
        'email' => 'orga@example.com',
        'domain' => 'orga',
        'active_status' => true,
    ]);

    $user = User::create([
        'name' => 'Alice',
        'email' => 'alice@example.com',
        'password' => 'password',
        'organization_id' => $org->id,
        'is_admin' => false,
        'permissions' => [
            'families' => true,
            'aid_programs' => true,
            'financial' => true,
            'volunteers' => true,
        ],
    ]);

    $this->actingAs($user);

    $family = Family::create([
        'organization_id' => $org->id,
        'head_of_family_name' => 'Head Person',
        'cnic' => '12345-1234567-1',
    ]);

    $program = AidProgram::create([
        'organization_id' => $org->id,
        'program_title' => 'Food Support',
        'status' => 'Active',
    ]);

    $volunteer = Volunteer::create([
        'organization_id' => $org->id,
        'full_name' => 'Bob Volunteer',
        'status' => 'Active',
    ]);

    $tx = FinancialManagement::create([
        'organization_id' => $org->id,
        'date' => now()->format('Y-m-d'),
        'transaction_type' => 'Donation',
        'amount' => 1000,
    ]);

    $response = $this->get(route('notifications.feed', ['since' => 0]));

    $response->assertOk();
    $response->assertJsonPath('counts.all', 4);
    $response->assertJsonPath('counts.message', 1);
    $response->assertJsonPath('counts.other', 3);
});

it('shows notifications for super admin without granular permissions', function () {
    $orgA = Organization::create([
        'registration_number' => 'REG-SA-1',
        'organization_name' => 'Org SA 1',
        'email' => 'orga1@example.com',
        'domain' => 'orga1',
        'active_status' => true,
    ]);

    $family = Family::create([
        'organization_id' => $orgA->id,
        'head_of_family_name' => 'HF',
        'cnic' => '12345-1234567-1',
    ]);
    $program = AidProgram::create([
        'organization_id' => $orgA->id,
        'program_title' => 'P',
        'status' => 'Active',
    ]);
    $volunteer = Volunteer::create([
        'organization_id' => $orgA->id,
        'full_name' => 'V',
        'status' => 'Active',
    ]);
    $tx = FinancialManagement::create([
        'organization_id' => $orgA->id,
        'date' => now()->format('Y-m-d'),
        'transaction_type' => 'Donation',
        'amount' => 200000,
    ]);

    $super = User::create([
        'name' => 'Super',
        'email' => 'super@example.com',
        'password' => 'password',
        'is_admin' => false,
    ]);
    $role = \App\Models\Role::create(['name' => 'System Admin', 'slug' => 'system-admin', 'is_system' => true]);
    \DB::table('role_user')->insert(['user_id' => $super->id, 'role_id' => $role->id]);

    $this->actingAs($super);

    $response = $this->get(route('notifications.feed', ['since' => 0]));
    $response->assertOk();
    $json = $response->json();
    expect(collect($json['all'])->pluck('title')->all())
        ->toContain('Organization Registered')
        ->toContain('High-Value Transaction');
    expect(collect($json['all'])->pluck('title')->all())
        ->not->toContain('Family')
        ->not->toContain('Aid Program')
        ->not->toContain('Volunteer')
        ->not->toContain('Survey');
});
