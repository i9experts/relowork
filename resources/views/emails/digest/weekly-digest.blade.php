<!DOCTYPE html>
<html lang="en"><head><meta charset="UTF-8"><title>Weekly Global Digest | ReloWork</title></head>
<body style="margin:0; padding:0; background:#f0f2f5; font-family:'Inter', Arial, sans-serif;">
  <table width="100%" cellpadding="0" cellspacing="0" style="padding:40px 0;">
    <tr>
      <td align="center">
        <table width="620" cellpadding="0" cellspacing="0" style="background:#ffffff; border-radius:16px; overflow:hidden; box-shadow:0 10px 32px rgba(0,0,0,0.12);">
          <tr>
            <td><div style="height:6px; width:100%; background:#005bea;"></div></td>
          </tr>
          <tr>
            <td style="padding:24px 32px 10px; text-align:center;">
              <h1 style="margin:0; color:#222; font-size:24px; font-weight:700;">Weekly Global Digest</h1>
              <p style="margin:8px 0 0; color:#6c757d; font-size:14px;">System-wide KPIs</p>
            </td>
          </tr>
          <tr>
            <td style="padding:28px 48px 16px; color:#2b2d31; font-size:15px; line-height:1.7;">
              <ul style="margin:0; padding-left:18px;">
                <li>Total active organizations: {{ $active_orgs ?? 0 }}</li>
                <li>Donations summary: {{ number_format((float)($donations_total ?? 0)) }}</li>
                <li>Total aid distributed: {{ number_format((float)($disbursements_total ?? 0)) }}</li>
                <li>New families this week: {{ $new_families ?? 0 }}</li>
                <li>Pending approvals count: {{ $pending_approvals ?? 0 }}</li>
              </ul>
              <p style="margin-top:12px;"><strong>Top KPIs:</strong> {{ $top_kpis ?? '-' }}</p>
              <div style="text-align:center; margin-top:24px;">
                <a href="{{ $actionUrl ?? route('cms.admin.dashboard') }}" style="display:inline-block; padding:12px 32px; background:#005bea; color:#ffffff; font-size:15px; font-weight:600; border-radius:999px; text-decoration:none; box-shadow:0 4px 15px rgba(0,91,234,0.35);">Open Admin Dashboard</a>
              </div>
              <p style="margin-top:30px; font-size:13px; color:#999; text-align:center;">JazakAllah,<br><strong>Team ReloWork</strong></p>
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
      </td>
    </tr>
  </table>
</body></html>

