<?php

/*
|--------------------------------------------------------------------------
| Tenant isolation
|--------------------------------------------------------------------------
|
| Two organizations (A and B) each get a full set of records. Every test acts
| as a user from Org B and tries to read, change or delete Org A's records,
| or checks that Org A's data never shows up in Org B's listings.
|
*/

use App\Models\AidProgram;
use App\Models\EducationLevel;
use App\Models\Family;
use App\Models\FamilyMember;
use App\Models\FinancialManagement;
use App\Models\Organization;
use App\Models\Report;
use App\Models\Surveys;
use App\Models\User;
use App\Models\Volunteer;
use Illuminate\Database\Eloquent\Model;

function makeTenant(string $key): array
{
    return Model::unguarded(fn () => buildTenant($key));
}

function buildTenant(string $key): array
{
    $org = Organization::create([
        'registration_number' => "REG-{$key}",
        'organization_name' => "Org {$key}",
        'email' => "org{$key}@example.com",
        'domain' => "org{$key}",
    ]);

    $admin = User::create([
        'name' => "Admin {$key}",
        'email' => "admin{$key}@example.com",
        'password' => bcrypt('secret'),
        'organization_id' => $org->id,
        'is_admin' => true,
    ]);

    $volunteer = Volunteer::create([
        'organization_id' => $org->id,
        'user_id' => $admin->id,
        'full_name' => "Volunteer-{$key}-Marker",
        'status' => 'Active',
    ]);

    $family = Family::create([
        'organization_id' => $org->id,
        'family_code' => 'FAM-0001',
        'head_of_family_name' => "Family-{$key}-Marker",
        'cnic' => $key === 'A' ? '11111-1111111-1' : '22222-2222222-2',
        'gender' => 'Male',
        'zakat_eligibility' => false,
        'volunteer_id' => $volunteer->id,
    ]);

    $member = FamilyMember::create([
        'organization_id' => $org->id,
        'family_id' => $family->id,
        'name' => "Member-{$key}-Marker",
    ]);

    $program = AidProgram::create([
        'organization_id' => $org->id,
        'program_code' => 'PRG-0001',
        'program_title' => "Program-{$key}-Marker",
    ]);

    $transaction = FinancialManagement::create([
        'organization_id' => $org->id,
        'date' => now()->toDateString(),
        'transaction_type' => 'Donation',
        'amount' => $key === 'A' ? 777001 : 777002,
    ]);

    $survey = Surveys::create([
        'organization_id' => $org->id,
        'survey_code' => "SUR-{$key}-00001",
        'family_id' => $family->id,
        'enumerator_id' => $volunteer->id,
    ]);

    $report = Report::create([
        'organization_id' => $org->id,
        'title' => "Report-{$key}-Marker",
    ]);

    $educationLevel = EducationLevel::create([
        'organization_id' => $org->id,
        'name' => "Education-{$key}-Marker",
    ]);

    return compact('org', 'admin', 'volunteer', 'family', 'member', 'program', 'transaction', 'survey', 'report', 'educationLevel');
}

beforeEach(function () {
    $this->a = makeTenant('A');
    $this->b = makeTenant('B');
    $this->actingAs($this->b['admin']);
});

/*
| Reading another organization's records by ID
*/

it('blocks reading another org\'s records by ID', function (string $route, string $key) {
    $this->get(route($route, $this->a[$key]))->assertNotFound();
})->with([
    'family show' => ['cms.family.show', 'family'],
    'family edit' => ['cms.family.edit', 'family'],
    'family json' => ['cms.family.json', 'family'],
    'aid program show' => ['cms.aid-program.show', 'program'],
    'aid program edit' => ['cms.aid-program.edit', 'program'],
    'transaction show' => ['cms.financial-management.show', 'transaction'],
    'transaction edit' => ['cms.financial-management.edit', 'transaction'],
    'survey show' => ['cms.survey.show', 'survey'],
    'survey edit' => ['cms.survey.edit', 'survey'],
    'volunteer show' => ['cms.volunteer.show', 'volunteer'],
    'volunteer edit' => ['cms.volunteer.edit', 'volunteer'],
    'report show' => ['cms.report.show', 'report'],
]);

it('still lets an org read its own records', function (string $route, string $key) {
    $this->get(route($route, $this->b[$key]))->assertOk();
})->with([
    'family show' => ['cms.family.show', 'family'],
    'family json' => ['cms.family.json', 'family'],
    'aid program show' => ['cms.aid-program.show', 'program'],
    'survey show' => ['cms.survey.show', 'survey'],
    'volunteer show' => ['cms.volunteer.show', 'volunteer'],
]);

/*
| Changing another organization's records
*/

it('blocks updating another org\'s family', function () {
    $this->put(route('cms.family.update', $this->a['family']), [
        'head_of_family_name' => 'Hijacked',
        'cnic' => '11111-1111111-1',
        'gender' => 'Male',
        'zakat_eligibility' => 'No',
    ])->assertNotFound();

    expect($this->a['family']->fresh()->head_of_family_name)->toBe('Family-A-Marker');
});

it('blocks updating another org\'s aid program', function () {
    $this->put(route('cms.aid-program.update', $this->a['program']), [
        'program_title' => 'Hijacked',
    ])->assertNotFound();

    expect($this->a['program']->fresh()->program_title)->toBe('Program-A-Marker');
});

it('blocks updating another org\'s transaction', function () {
    $this->put(route('cms.financial-management.update', $this->a['transaction']), [
        'date' => now()->toDateString(),
        'transaction_type' => 'Expense',
        'amount' => 1,
    ])->assertNotFound();

    expect((int) $this->a['transaction']->fresh()->amount)->toBe(777001);
});

it('blocks updating another org\'s volunteer', function () {
    $this->put(route('cms.volunteer.update', $this->a['volunteer']), [
        'full_name' => 'Hijacked',
    ])->assertNotFound();

    expect($this->a['volunteer']->fresh()->full_name)->toBe('Volunteer-A-Marker');
});

it('blocks adding a member to another org\'s family', function () {
    $this->postJson(route('cms.family.members.add', $this->a['family']), [
        'name' => 'Intruder',
    ])->assertNotFound();

    expect(FamilyMember::where('name', 'Intruder')->exists())->toBeFalse();
});

it('blocks renaming another org\'s education level', function () {
    $this->patch(route('cms.volunteer-settings.education-level.update', $this->a['educationLevel']), [
        'name' => 'Hijacked',
    ])->assertNotFound();

    expect($this->a['educationLevel']->fresh()->name)->toBe('Education-A-Marker');
});

/*
| Deleting another organization's records
*/

it('blocks deleting another org\'s records', function (string $route, string $key, string $model) {
    $this->delete(route($route, $this->a[$key]))->assertNotFound();

    expect($model::find($this->a[$key]->id))->not->toBeNull();
})->with([
    'family' => ['cms.family.destroy', 'family', Family::class],
    'family member' => ['cms.family.members.delete', 'member', FamilyMember::class],
    'aid program' => ['cms.aid-program.destroy', 'program', AidProgram::class],
    'transaction' => ['cms.financial-management.destroy', 'transaction', FinancialManagement::class],
    'volunteer' => ['cms.volunteer.destroy', 'volunteer', Volunteer::class],
    'education level' => ['cms.volunteer-settings.education-level.destroy', 'educationLevel', EducationLevel::class],
]);

it('blocks deleting another org\'s survey', function () {
    $this->delete(route('cms.survey.destroy', $this->a['survey']))->assertNotFound();

    expect(Surveys::find($this->a['survey']->id))->not->toBeNull();
});

it('lets an org delete its own survey', function () {
    $this->delete(route('cms.survey.destroy', $this->b['survey']))
        ->assertRedirect(route('cms.survey.index'));

    expect(Surveys::find($this->b['survey']->id))->toBeNull();
});

/*
| Shared settings options (organization_id = null) are used by every org
*/

it('shows shared settings options alongside the org\'s own', function () {
    EducationLevel::create(['name' => 'Shared-Education-Marker']);

    $this->get(route('cms.volunteer-settings.index'))
        ->assertOk()
        ->assertSee('Shared-Education-Marker')
        ->assertSee('Education-B-Marker');
});

it('blocks an org admin from changing a shared settings option', function () {
    $shared = EducationLevel::create(['name' => 'Shared-Education-Marker']);

    $this->patch(route('cms.volunteer-settings.education-level.update', $shared), ['name' => 'Hijacked'])
        ->assertForbidden();
    $this->delete(route('cms.volunteer-settings.education-level.destroy', $shared))
        ->assertForbidden();

    expect($shared->fresh()->name)->toBe('Shared-Education-Marker');
});

it('lets a super admin change a shared settings option', function () {
    $shared = EducationLevel::create(['name' => 'Shared-Education-Marker']);
    $super = User::create([
        'name' => 'Super',
        'email' => 'super-settings@example.com',
        'password' => bcrypt('secret'),
        'permissions' => ['super_admin' => true],
    ]);

    $this->actingAs($super)
        ->patch(route('cms.volunteer-settings.education-level.update', $shared), ['name' => 'Renamed'])
        ->assertRedirect();

    expect($shared->fresh()->name)->toBe('Renamed');
});

/*
| Listing pages only show the current organization's data
*/

it('only lists the current org\'s records', function (string $route, string $marker) {
    $this->get(route($route))
        ->assertOk()
        ->assertSee(str_replace('?', 'B', $marker))
        ->assertDontSee(str_replace('?', 'A', $marker));
})->with([
    'families' => ['cms.family.index', 'Family-?-Marker'],
    'aid programs' => ['cms.aid-program.index', 'Program-?-Marker'],
    'volunteers' => ['cms.volunteer.index', 'Volunteer-?-Marker'],
    'volunteer settings' => ['cms.volunteer-settings.index', 'Education-?-Marker'],
]);

it('only lists the current org\'s transactions', function () {
    $this->get(route('cms.financial-management.index'))
        ->assertOk()
        ->assertDontSee('777,001')
        ->assertDontSee('777001');
});

it('does not offer another org\'s families or volunteers in create forms', function (string $route) {
    $this->get(route($route))
        ->assertOk()
        ->assertDontSee('Family-A-Marker')
        ->assertDontSee('Volunteer-A-Marker');
})->with([
    'family create' => ['cms.family.create'],
    'aid program create' => ['cms.aid-program.create'],
    'survey create' => ['cms.survey.create'],
    'transaction create' => ['cms.financial-management.create'],
]);

it('does not show another org\'s settings options on the volunteer edit form', function () {
    $this->get(route('cms.volunteer.edit', $this->b['volunteer']))
        ->assertOk()
        ->assertDontSee('Education-A-Marker');
});

/*
| Creating records that point at another organization's data
*/

it('rejects a family assigned to another org\'s volunteer', function () {
    $this->post(route('cms.family.store'), [
        'head_of_family_name' => 'Cross-link',
        'cnic' => '33333-3333333-3',
        'gender' => 'Male',
        'zakat_eligibility' => 'No',
        'volunteer_id' => $this->a['volunteer']->id,
    ])->assertSessionHasErrors('volunteer_id');

    expect(Family::where('head_of_family_name', 'Cross-link')->exists())->toBeFalse();
});

it('rejects an aid program that includes another org\'s family', function () {
    $this->post(route('cms.aid-program.store'), [
        'program_title' => 'Cross-link',
        'volunteer_id' => $this->b['volunteer']->id,
        'families' => [$this->a['family']->id],
    ])->assertSessionHasErrors();

    expect(AidProgram::where('program_title', 'Cross-link')->exists())->toBeFalse();
});

it('rejects a transaction linked to another org\'s program or family', function () {
    $this->post(route('cms.financial-management.store'), [
        'date' => now()->toDateString(),
        'transaction_type' => 'Donation',
        'amount' => 555,
        'aid_program_id' => $this->a['program']->id,
        'family_id' => $this->a['family']->id,
    ])->assertSessionHasErrors();

    expect(FinancialManagement::where('amount', 555)->exists())->toBeFalse();
});

/*
| Subdomain / tenant context
*/

it('redirects a user away from another org\'s subdomain', function () {
    $this->get(route('cms.family.index', ['subdomain' => 'orgA']))
        ->assertRedirectContains('subdomain=orgB');
});

it('returns 404 for an unknown subdomain', function () {
    $this->get(route('cms.family.index', ['subdomain' => 'does-not-exist']))
        ->assertNotFound();
});

it('lets a super admin open any org\'s records', function () {
    $super = User::create([
        'name' => 'Super',
        'email' => 'super@example.com',
        'password' => bcrypt('secret'),
        'permissions' => ['super_admin' => true],
    ]);

    $this->actingAs($super)
        ->get(route('cms.family.show', $this->a['family']))
        ->assertOk();
});
