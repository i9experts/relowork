<?php

use App\Models\Organization;

it('root redirects to login when organization exists', function () {
    Organization::create([
        'registration_number' => 'REG-0001',
        'organization_name' => 'Test Org',
        'email' => 'org@example.com',
        'domain' => 'testorg',
        'active_status' => true,
    ]);

    $response = $this->get('/');

    $response->assertRedirect(route('login'));
});
