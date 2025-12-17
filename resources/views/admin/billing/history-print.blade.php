<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Billing History | ReloWork</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        :root {
            --primary: #fd5190;
            --bg: #f0f2f5;
            --border: #e5e7eb;
            --text-dark: #2b2d31;
            --text-muted: #6c757d;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            background: var(--bg);
            font-family: 'Inter', Arial, sans-serif;
            color: var(--text-dark);
        }

        .container {
            max-width: 1000px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 32px rgba(0, 0, 0, 0.12);
        }

        .topbar {
            height: 6px;
            background: var(--primary);
        }

        .header {
            padding: 24px 32px 16px;
            text-align: center;
            border-bottom: 1px solid var(--border);
        }

        .header h1 {
            margin: 0;
            font-size: 22px;
            font-weight: 700;
            color: #222;
        }

        .header .sub {
            margin-top: 6px;
            font-size: 13px;
            color: var(--text-muted);
        }

        .content {
            padding: 24px 32px;
        }

        .table-wrapper {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 800px;
        }

        th,
        td {
            border: 1px solid var(--border);
            padding: 12px 14px;
            font-size: 13px;
            text-align: left;
            white-space: nowrap;
        }

        th {
            background: #f9fafc;
            font-weight: 600;
            color: #3a3a3a;
        }

        tbody tr:nth-child(even) {
            background: #fafafa;
        }

        tbody tr:hover {
            background: #f1f5f9;
        }

        .footer {
            background: #f9fafc;
            padding: 16px 24px;
            text-align: center;
            font-size: 12px;
            color: #777;
            border-top: 1px solid var(--border);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .container {
                margin: 20px 12px;
                border-radius: 12px;
            }

            .header {
                padding: 20px;
            }

            .content {
                padding: 16px;
            }

            .header h1 {
                font-size: 20px;
            }
        }

        /* Print */
        @media print {
            body {
                background: #fff;
            }

            .container {
                margin: 0;
                box-shadow: none;
                border-radius: 0;
            }
        }
    </style>
</head>

<body>

<div class="container">
    <div class="topbar"></div>

    <div class="header">
        <h1>Billing History</h1>
        <p class="sub">Export generated from Admin Billing History</p>
    </div>

    <div class="content">
        <div class="table-wrapper">
            <table>
                <thead>
                <tr>
                    <th>#</th>
                    <th>Organization</th>
                    <th>Amount</th>
                    <th>Currency</th>
                    <th>Paid At</th>
                    <th>Method</th>
                    <th>Reference</th>
                    <th>Status</th>
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
    </div>

    <div class="footer">
        © {{ date('Y') }} ReloWork — All Rights Reserved.
    </div>
</div>

</body>
</html>
