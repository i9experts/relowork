<?php

use App\Models\Organization;
use App\Models\User;
use App\Models\Volunteer;
use App\Models\Family;
use App\Models\AidProgram;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('generates family codes per org using max+1', function () {
    $orgA = Organization::create(['registration_number' => 'REG-A', 'organization_name' => 'Org A', 'email' => 'orga@example.com']);
    $orgB = Organization::create(['registration_number' => 'REG-B', 'organization_name' => 'Org B', 'email' => 'orgb@example.com']);

    $adminA = User::create(['name' => 'Admin A', 'email' => 'admina@example.com', 'password' => bcrypt('x'), 'organization_id' => $orgA->id, 'is_admin' => true]);
    $volA = Volunteer::create(['organization_id' => $orgA->id, 'user_id' => $adminA->id, 'full_name' => 'Vol A']);

    $adminB = User::create(['name' => 'Admin B', 'email' => 'adminb@example.com', 'password' => bcrypt('x'), 'organization_id' => $orgB->id, 'is_admin' => true]);
    $volB = Volunteer::create(['organization_id' => $orgB->id, 'user_id' => $adminB->id, 'full_name' => 'Vol B']);

    Family::create(['organization_id' => $orgA->id, 'family_code' => 'FAM-0001', 'head_of_family_name' => 'A1', 'cnic' => '11111-1111111-1', 'gender' => 'Male', 'zakat_eligibility' => false, 'volunteer_id' => $volA->id]);
    Family::create(['organization_id' => $orgA->id, 'family_code' => 'FAM-0003', 'head_of_family_name' => 'A3', 'cnic' => '22222-2222222-2', 'gender' => 'Male', 'zakat_eligibility' => false, 'volunteer_id' => $volA->id]);

    actingAs($adminB)
        ->post(route('cms.family.store'), [
            'head_of_family_name' => 'B1',
            'cnic' => '33333-3333333-3',
            'gender' => 'Male',
            'zakat_eligibility' => 'No',
            'volunteer_id' => $volB->id,
        ])->assertRedirect(route('cms.family.index'));

    $bFam = Family::where('organization_id', $orgB->id)->first();
    expect($bFam->family_code)->toBe('FAM-0001');
});

it('generates program codes per org using max+1', function () {
    $orgA = Organization::create(['registration_number' => 'REG-A2', 'organization_name' => 'Org A2', 'email' => 'orga2@example.com']);
    $orgB = Organization::create(['registration_number' => 'REG-B2', 'organization_name' => 'Org B2', 'email' => 'orgb2@example.com']);
    $adminA = User::create(['name' => 'Admin A2', 'email' => 'admina2@example.com', 'password' => bcrypt('x'), 'organization_id' => $orgA->id, 'is_admin' => true]);
    $adminB = User::create(['name' => 'Admin B2', 'email' => 'adminb2@example.com', 'password' => bcrypt('x'), 'organization_id' => $orgB->id, 'is_admin' => true]);
    $volA = Volunteer::create(['organization_id' => $orgA->id, 'user_id' => $adminA->id, 'full_name' => 'Vol A2']);
    $volB = Volunteer::create(['organization_id' => $orgB->id, 'user_id' => $adminB->id, 'full_name' => 'Vol B2']);

    $famB = Family::create(['organization_id' => $orgB->id, 'family_code' => 'FAM-0001', 'head_of_family_name' => 'B HF', 'cnic' => '55555-5555555-5', 'gender' => 'Male', 'zakat_eligibility' => false, 'volunteer_id' => $volB->id]);
    AidProgram::create(['organization_id' => $orgA->id, 'program_code' => 'PRG-0002', 'program_title' => 'A2']);

    actingAs($adminB)
        ->post(route('cms.aid-program.store'), [
            'program_title' => 'B1',
            'volunteer_id' => $volB->id,
            'families' => [$famB->id],
        ])->assertRedirect(route('cms.aid-program.index'));

    $bProg = AidProgram::where('organization_id', $orgB->id)->first();
    expect($bProg->program_code)->toBe('PRG-0001');
});

it('enforces permissions for non-admin users', function () {
    $org = Organization::create(['registration_number' => 'REG-C', 'organization_name' => 'Org C', 'email' => 'orgc@example.com']);
    $user = User::create(['name' => 'U', 'email' => 'u@example.com', 'password' => bcrypt('x'), 'organization_id' => $org->id, 'is_admin' => false, 'permissions' => ['families' => true]]);
    actingAs($user)->get(route('cms.aid-program.index'))->assertStatus(302);
    actingAs($user)->get(route('cms.family.index'))->assertOk();
});
