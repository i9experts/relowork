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

