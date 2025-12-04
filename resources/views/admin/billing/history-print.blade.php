<!DOCTYPE html>
<html lang="en"><head><meta charset="UTF-8"><title>Billing History | ReloWork</title>
<style>
  body{margin:0;padding:0;background:#f0f2f5;font-family:'Inter', Arial, sans-serif;color:#2b2d31}
  .container{max-width:820px;margin:40px auto;background:#fff;border-radius:16px;overflow:hidden;box-shadow:0 10px 32px rgba(0,0,0,.12)}
  .topbar{height:6px;background:#005bea}
  .header{padding:24px 32px 10px;text-align:center}
  .header h1{margin:0;color:#222;font-size:22px;font-weight:700}
  .sub{margin:4px 0 0;color:#6c757d;font-size:13px}
  .content{padding:24px 32px}
  table{width:100%;border-collapse:collapse}
  th,td{border:1px solid #e5e7eb;padding:10px 12px;font-size:13px}
  th{background:#f9fafc;text-align:left;color:#3a3a3a}
  .footer{background:#f9fafc;padding:16px 24px;text-align:center;font-size:12px;color:#777}
  @media print{body{background:#fff}.container{box-shadow:none;margin:0;border-radius:0}}
</style></head>
<body>
  <div class="container">
    <div class="topbar"></div>
    <div class="header">
      <h1>Billing History</h1>
      <p class="sub">Export generated from Admin Billing History</p>
    </div>
    <div class="content">
  <table>
    <thead>
      <tr>
        <th>#</th><th>Organization</th><th>Amount</th><th>Currency</th><th>Paid At</th><th>Method</th><th>Reference</th><th>Status</th>
      </tr>
    </thead>
    <tbody>
      @foreach($payments as $p)
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td>{{ optional($p->organization)->organization_name ?? '—' }}</td>
          <td>{{ number_format((float)$p->amount, 2) }}</td>
          <td>{{ $p->currency }}</td>
          <td>{{ optional($p->paid_at)->format('Y-m-d') ?? '—' }}</td>
          <td>{{ $p->method ?? '—' }}</td>
          <td>{{ $p->reference ?? '—' }}</td>
          <td>{{ ucfirst($p->status) }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
    </div>
    <div class="footer">© {{ date('Y') }} ReloWork — All Rights Reserved.</div>
  </div>
</body></html>
