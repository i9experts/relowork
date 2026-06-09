<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Password Reset Instructions | ReloWork</title>
</head>
<body style="margin:0; padding:0; background:#f0f2f5; font-family:'Inter', Arial, sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="padding:40px 0;">
    <tr>
        <td align="center">
            <!-- Email Container -->
            <table width="620" cellpadding="0" cellspacing="0" style="background:#ffffff; border-radius:16px; overflow:hidden; box-shadow:0 10px 32px rgba(0,0,0,0.12);">
                
                <!-- Top Bar / Header -->
                <tr>
                    <td>
                        <div style="height:6px; width:100%; background:#005bea;"></div>
                    </td>
                </tr>

                <!-- Header Text -->
                <tr>
                    <td style="padding:24px 32px 10px; text-align:center;">
                        <h1 style="margin:0; color:#222; font-size:24px; font-weight:700;">Reset Your Password</h1>
                    </td>
                </tr>

                <!-- Body -->
                <tr>
                    <td style="padding:28px 48px 40px; color:#2b2d31; font-size:15px; line-height:1.7;">
                        <p style="margin-top:0;">Assalam-o-Alaikum <strong>{{ $user->name }}</strong>,</p>
                        <p>We received a request to reset the password for your account. You can reset your password using the secure link below:</p>

                        <!-- Reset Link Details -->
                        <div style="margin:25px 0; padding:20px; background:#edf5ff; border:2px dashed #005bea; border-radius:12px; text-align:center;">
                            <p style="margin:5px 0;"><strong>Reset Link:</strong> <a href="{{ $resetUrl }}" target="_blank">{{ $resetUrl }}</a></p>
                            <p style="margin:5px 0;"><strong>Email:</strong> {{ $user->email }}</p>
                        </div>

                        <p>If you did not request this change, you can ignore this email. This link will expire automatically after a short time for security reasons.</p>

                        <!-- Button -->
                        <div style="text-align:center; margin:30px 0;">
                            <a href="{{ $resetUrl }}" style="display:inline-block; padding:12px 32px; background:#005bea; color:#ffffff; font-size:15px; font-weight:600; border-radius:999px; text-decoration:none; box-shadow:0 4px 15px rgba(0,91,234,0.35);">
                                Reset Password
                            </a>
                        </div>

                        <p style="margin-top:30px; font-size:13px; color:#999; text-align:center;">
                            JazakAllah,<br>
                            <strong>Team ReloWork</strong>
                        </p>
                    </td>
                </tr>
                <tr>
                    <td style="background:#f9fafc; padding:25px 45px; text-align:center; font-size:15px; color:#555;">
                        <p style="margin:0;">Need help? Our support team is here for you 🤝<br><strong>support@relowork.com</strong></p>
                    </td>
                </tr>
                <tr>
                    <td style="background:#eceff3; padding:18px 40px; text-align:center; font-size:12px; color:#777;">
                        <p style="margin:0;">If you didn’t request this email, please ignore it.</p>
                        <p style="margin:6px 0 0;">© {{ date('Y') }} ReloWork — All Rights Reserved.</p>
                    </td>
                </tr>
            </table>
            <!-- Email Container End -->
        </td>
    </tr>
</table>
</body>
</html>
