<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Your Password | ReloWork</title>
</head>
<body style="margin:0; padding:0; background:#f0f2f5; font-family: Arial, sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="padding:40px 0;">
    <tr>
        <td align="center">
            <table width="620" cellpadding="0" cellspacing="0" style="background:#ffffff; border-radius:16px; overflow:hidden; box-shadow:0 10px 32px rgba(0,0,0,0.12);">
                <tr>
                    <td>
                        <div style="height:6px; width:100%; background:#005bea;"></div>
                    </td>
                </tr>
                <tr>
                    <td style="padding:24px 32px 10px; text-align:center;">
                        <h1 style="margin:0; color:#222; font-size:24px; font-weight:700;">Reset Your Password</h1>
                    </td>
                </tr>
                <tr>
                    <td style="padding:28px 48px 40px; color:#2b2d31; font-size:15px; line-height:1.7;">
                        <p style="margin-top:0;">Hello <strong>{{ $user->name }}</strong>,</p>
                        <p>You recently requested to reset your password for your ReloWork account.</p>
                        <p>Please click the button below to set a new password:</p>
                        <div style="text-align:center; margin:30px 0;">
                            <a href="{{ url('/password/reset/' . $token . '?email=' . urlencode($user->email)) }}" style="display:inline-block; padding:12px 32px; background:#005bea; color:#ffffff; font-size:15px; font-weight:600; border-radius:999px; text-decoration:none; box-shadow:0 4px 15px rgba(0,91,234,0.35);">
                                Reset Password
                            </a>
                        </div>
                        <p>If you did not request a password reset, you can safely ignore this email.</p>
                        <p style="margin-top:30px; font-size:13px; color:#999; text-align:center;">
                            Thank you,<br>
                            <strong>Team ReloWork</strong>
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
