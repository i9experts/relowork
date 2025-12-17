<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>429 — Too Many Requests</title>
  @php
    $orgId = optional(request()->user())->organization_id ?? session('organization_id');
    $__settings = $orgId
      ? optional(\App\Models\Setting::where('organization_id', $orgId)->first())
      : optional(\App\Models\Setting::whereNull('organization_id')->first());
  @endphp
  @if($__settings && $__settings->favicon_path)
    <link href="{{ asset('storage/'.$__settings->favicon_path) }}" rel="shortcut icon" />
  @else
    <link href="{{ url('assets/images/logo/favicon.png') }}" rel="shortcut icon" />
  @endif
  <link href="https://fonts.googleapis.com/css?family=Karla:400,700|Roboto" rel="stylesheet">
  <link href="{{ url('assets/plugins/material/css/materialdesignicons.min.css') }}" rel="stylesheet" />
  <link href="{{ url('assets/plugins/simplebar/simplebar.css') }}" rel="stylesheet" />
  <link href="{{ url('assets/plugins/nprogress/nprogress.css') }}" rel="stylesheet" />
  <link id="main-css-href" rel="stylesheet" href="{{ url('assets/css/style.css') }}" />
  <script src="{{ url('assets/plugins/nprogress/nprogress.js') }}"></script>
</head>
<body class="bg-light-gray" id="body">
  <div class="container" style="min-height:100vh; display:flex; align-items:center; justify-content:center;">
    <div class="row w-100 justify-content-center">
      <div class="col-12 col-md-10 col-lg-8">
        <div class="card card-default" style="border-radius:16px;">
          <div class="gradient-header"></div>
          <div class="card-body px-4 py-5 text-center">
            <div class="mb-3">
              <i class="mdi mdi-speedometer-slow" style="font-size:64px;color:#46c79e;"></i>
            </div>
            <h4 class="text-dark fw-bold mb-2">429 — Too Many Requests</h4>
            <p class="text-muted mb-3">Please wait a moment and try again.</p>
            <div class="d-flex gap-2 justify-content-center">
              <a href="{{ route('cms.dashboard.index') }}" class="btn btn-primary btn-pill">Go to Dashboard</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
