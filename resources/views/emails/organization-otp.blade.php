<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Team ReloWork</title>

</head>

<body style="margin:0; padding:0; background:#f0f2f5; font-family:'Inter', Arial, sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0" style="padding:40px 0;">
    <tr>
        <td align="center">

            <!-- Email Container -->
            <table width="620" cellpadding="0" cellspacing="0" style="background:#ffffff; border-radius:16px; overflow:hidden; box-shadow:0 10px 32px rgba(0,0,0,0.12);">

                <tr>
                    <td>
                        <!-- Thin gradient-style header bar to match app theme -->
                        <div style="height:6px; width:100%; background:#005bea;"></div>
                    </td>
                </tr>

                <tr>
                    <td style="padding:24px 32px 10px; text-align:center;">
                        <h1 style="margin:0; color:#222; font-size:26px; font-weight:700;">
                            ReloWork – Organization Verification
                        </h1>
                        <p style="margin:8px 0 0; color:#6c757d; font-size:14px;">
                            Lets set up your organization and get started.
                        </p>
                    </td>
                </tr>

                <!-- Body -->
                <tr>
                    <td style="padding:28px 48px 40px; color:#2b2d31; font-size:16px; line-height:1.8;">

                        <p style="margin-top:0;">
                            Hello <strong>{{ $organization->organization_name }}</strong>,
                        </p>

                        <p>
                            We’re excited to welcome you to <strong>ReloWork</strong>.  
                            To proceed with your account setup, please verify your organization using the OTP below:
                        </p>

                        <!-- OTP Box -->
                        <div style="text-align:center; margin:32px 0;">
                            <div style="display:inline-block; padding:20px 32px; background:#edf5ff; border:2px dashed #005bea; border-radius:12px;">
                                <span style="font-size:38px; font-weight:800; color:#005bea; letter-spacing:12px;">
                                    {{ $organization->otp_code }}
                                </span>
                            </div>
                        </div>

                        <p style="text-align:center; font-size:13px; color:#666; margin-top:4px;">
                            OTP Expires on:
                            <strong>{{ $organization->otp_expires_at?->format('d M Y h:i A') }}</strong>
                        </p>

                        <p style="margin:24px 0 6px;">
                            Once activated, your organization dashboard will be accessible at:
                        </p>

                        <p style="font-size:18px; font-weight:700; text-align:center; margin:6px 0 0; color:#111;">
                            {{ $organization->domain }}.relowork.com
                        </p>

                        <!-- Button -->
                        <div style="text-align:center; margin-top:32px;">
                            <a href="#" style="display:inline-block; padding:12px 36px; background:#005bea; color:#ffffff; font-size:15px; font-weight:600; border-radius:999px; text-decoration:none; box-shadow:0 4px 15px rgba(0,91,234,0.35);">
                                Verify & Activate Organization
                            </a>
                        </div>
                    </td>
                </tr>

                <!-- Support Section -->
                <tr>
                    <td style="background:#f9fafc; padding:25px 45px; text-align:center; font-size:15px; color:#555;">
                        <p style="margin:0;">
                            Need help? Our support team is here for you 🤝<br>
                            <strong>support@relowork.com</strong>
                        </p>
                    </td>
                </tr>

                <!-- Footer -->
                <tr>
                    <td style="background:#eceff3; padding:18px 40px; text-align:center; font-size:12px; color:#777;">
                        <p style="margin:0;">
                            If you didn’t request this email, please ignore it.
                        </p>
                        <p style="margin:6px 0 0;">
                            © {{ date('Y') }} ReloWork — All Rights Reserved.
                        </p>
                    </td>
                </tr>

            </table>
            <!-- Email Container End -->

        </td>
    </tr>
</table>

</body>
</html>
