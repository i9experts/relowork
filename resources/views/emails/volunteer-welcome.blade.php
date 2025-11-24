<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Volunteer Account Details</title>
</head>
<body>
    <p>Assalam-o-Alaikum {{ $user->name }},</p>

    <p>Your volunteer account has been created. You can log in using the details below:</p>

    <p>
        <strong>Login URL:</strong>
        <a href="{{ $loginUrl }}" target="_blank">{{ $loginUrl }}</a><br>
        <strong>Email:</strong> {{ $user->email }}<br>
        <strong>Password:</strong> {{ $plainPassword }}
    </p>

    <p>For security, please log in and change your password after first login.</p>

    <p>JazakAllah,<br>
    {{ config('app.name') }}</p>
</body>
</html>
