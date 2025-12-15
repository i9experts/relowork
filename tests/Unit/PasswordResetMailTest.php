<?php

use App\Mail\PasswordResetMail;
use App\Models\User;

it('builds reset link containing token and email', function () {
    $user = new User([
        'name' => 'Alice',
        'email' => 'alice@skybridge.com',
        'password' => 'password',
        'organization_id' => null,
    ]);

    $m = new PasswordResetMail($user, 'TOKEN123');
    $html = $m->build()->render();

    expect($html)->toContain('/password/reset/TOKEN123?email=alice%40skybridge.com');
});
