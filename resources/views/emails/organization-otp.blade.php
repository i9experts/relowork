<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Organization Verification Code</title>
</head>
<body>
    <h2>Verify your organization</h2>
    <p>Assalam-u-Alaikum {{ $organization->organization_name }},</p>
    <p>Aapka verification code hai:</p>
    <h1 style="letter-spacing:4px;">{{ $organization->otp_code }}</h1>
    <p>Yeh code {{ $organization->otp_expires_at?->format('d M Y h:i A') }} tak valid hai.</p>
    <p>Live hone ke baad aapki site kuch is tarah hogi:
        <strong>{{ $organization->domain }}.relowork.com</strong>
    </p>
    <p>Is code ko website par OTP verification page par daal kar apni organization ko activate karein.</p>
</body>
</html>
