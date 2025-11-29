<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <title>{{ $title ?? 'Report' }}</title>
  <style>
    body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; color: #222; }
    h1 { font-size: 20px; margin: 0 0 4px 0; }
    h2 { font-size: 14px; margin: 0 0 16px 0; color: #666; }
    table { width: 100%; border-collapse: collapse; }
    th, td { border: 1px solid #ddd; padding: 6px 8px; }
    th { background: #f8f9fa; text-align: left; }
    .header { display:flex; align-items:center; justify-content: space-between; margin-bottom: 12px; }
    .brand { display:flex; align-items:center; gap:10px; }
    .brand img { height: 40px; }
    .meta { font-size: 11px; color: #555; }
    .footer { position: fixed; bottom: 10px; left: 0; right: 0; text-align: center; font-size: 10px; color: #888; }
  </style>
</head>
<body>
  <div class="header">
    <div class="brand">
      @php($orgId = optional(request()->user())->organization_id ?? session('organization_id'))
      @php($__settings = $orgId ? optional(\App\Models\Setting::where('organization_id', $orgId)->first()) : null)
      @if($__settings && $__settings->logo_path)
        <img src="{{ public_path('storage/'. $__settings->logo_path) }}" alt="Logo" />
      @endif
      <div>
        <h1>{{ $title ?? 'Report' }}</h1>
        <h2>{{ $subtitle ?? '' }}</h2>
        <div class="meta">Generated at {{ now()->format('Y-m-d H:i') }}</div>
      </div>
    </div>
  </div>

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

  <div class="footer">Powered by Charity CMS</div>
</body>
</html>

