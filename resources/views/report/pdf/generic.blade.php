<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <title>{{ $title ?? 'Report' }}</title>

  @php($colCount = count($columns ?? []))
  <style>
    @page { size: A4; margin: 16mm; }
    @if(($colCount ?? 0) > 12)
    @page { size: A4 landscape; margin: 12mm; }
    @endif
    body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; color: #1f2937; }
    .topbar { height: 6px; background: #9e6de0; border-radius: 8px 8px 0 0; margin-bottom: 10px; }
    .header-row { display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px; }
    .col-left { flex: 0 0 auto; }
    .col-right { flex: 1 1 auto; text-align: right; }
    .logo-img { height: 64px; }
    .fw-bold { font-weight: 700; }
    .mb-0 { margin-bottom: 0; }
    .text-muted { color: #6b7280; }
    table { width: 100%; border-collapse: collapse; table-layout: auto; }
    thead th { background: #f3edf9; color: #3f3f46; font-weight: 600; border: 1px solid #e5e7eb; padding: 6px; text-align: left; white-space: nowrap; }
    tbody td { border: 1px solid #e5e7eb; padding: 6px; overflow-wrap: anywhere; white-space: normal; }
    @if(($colCount ?? 0) > 12)
    thead th, tbody td { padding: 4px; font-size: 10px; }
    @endif
    tbody tr:nth-child(even) { background: #fafafa; }
    .table-wrap { width: 100%; }
    .footer { position: fixed; bottom: 10px; left: 0; right: 0; text-align: center; font-size: 10px; color: #9ca3af; }
  </style>
</head>

<body>
  <div class="topbar"></div>

  <div class="header-row">
      @php($orgId = optional(request()->user())->organization_id ?? session('organization_id'))
      @php($__settings = $orgId ? optional(\App\Models\Setting::where('organization_id', $orgId)->first()) : null)

      <div class="col-left">
        @if($__settings && $__settings->logo_path)
          <img src="{{ public_path('storage/'. $__settings->logo_path) }}" class="logo-img" alt="Logo">
        @endif
      </div>

      <div class="col-right">
        <h3 class="fw-bold mb-0">{{ $title ?? 'Report' }}</h3>
        <p class="mb-0">{{ $subtitle ?? '' }}</p>

        @if($__settings && $__settings->organization_name)
          <p class="mb-0">{{ $__settings->organization_name }}</p>
        @endif

        <p class="mb-0 text-muted">Generated {{ now()->format('Y-m-d H:i') }}</p>
      </div>
  </div>

  <div class="table-wrap">
      <table>
        <thead>
          <tr>
            @foreach(($columns ?? []) as $c)
              <th>{{ $c }}</th>
            @endforeach
          </tr>
        </thead>
        <tbody>
          @forelse(($rows ?? []) as $row)
            <tr>
              @foreach($row as $cell)
                <td>{{ $cell }}</td>
              @endforeach
            </tr>
          @empty
            <tr>
              <td colspan="{{ count($columns ?? []) }}" style="text-align:center;">No data available</td>
            </tr>
          @endforelse
        </tbody>
      </table>
  </div>

  <!-- Footer -->
  <div class="footer">
    Powered by I9 Experts (Pvt) Ltd — {{ optional($__settings)->organization_name ?: '-' }}
  </div>

</body>
</html>
