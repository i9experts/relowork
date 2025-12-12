<?php

use App\Mail\PasswordResetMail;
use App\Models\Organization;
use App\Models\User;

it('builds reset link containing token and email', function () {

    $org = Organization::create([
        'organization_name' => 'Skybridge',
        'email' => 'owner@skybridge.com',
        'domain' => 'skybridge',
        'active_status' => true,
    ]);

    $user = User::create([
        'name' => 'Alice',
        'email' => 'alice@skybridge.com',
        'password' => 'password',
        'organization_id' => $org->id,
    ]);

    $m = new PasswordResetMail($user, 'TOKEN123');
    $html = $m->build()->render();

    expect($html)->toContain('/password/reset/TOKEN123?email=alice%40skybridge.com');
});
