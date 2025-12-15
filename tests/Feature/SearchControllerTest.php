<?php

use App\Models\User;

it('super admin search redirects to admin pages', function () {
    $super = User::create([
        'name' => 'Super',
        'email' => 'super@example.com',
        'password' => bcrypt('x'),
        'is_admin' => false,
    ]);
    $role = \App\Models\Role::create(['name' => 'System Admin', 'slug' => 'system-admin', 'is_system' => true]);
    \DB::table('role_user')->insert(['user_id' => $super->id, 'role_id' => $role->id]);

    $this->actingAs($super);

    $this->get(route('search', ['q' => 'organizations']))
        ->assertRedirect(route('cms.admin.organizations'));

    $this->get(route('search', ['q' => 'billing']))
        ->assertRedirect(route('cms.admin.billing.history'));
});

it('org user search respects permissions', function () {
    $user = User::create([
        'name' => 'Alice',
        'email' => 'alice@example.com',
        'password' => bcrypt('x'),
        'is_admin' => false,
        'permissions' => ['families' => true],
    ]);

    $this->actingAs($user);

    $this->get(route('search', ['q' => 'families']))
        ->assertRedirect(route('cms.family.index'));

    $this->withHeader('referer', route('cms.dashboard.index'))
        ->get(route('search', ['q' => 'admin']))
        ->assertRedirect(route('cms.dashboard.index'));
});

it('volunteer-permitted user sees volunteer pages', function () {
    $user = User::create([
        'name' => 'Vol',
        'email' => 'vol@example.com',
        'password' => bcrypt('x'),
        'is_admin' => false,
        'permissions' => ['volunteers' => true],
    ]);

    $this->actingAs($user);

    $this->get(route('search', ['q' => 'volunteers']))
        ->assertRedirect(route('cms.volunteer.index'));
});
