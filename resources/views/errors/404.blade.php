<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>404 — Page Not Found</title>
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
  <style>
    .container-full {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(180deg, #f7f9fc 0%, #ffffff 100%);
    }

    .oops-title {
        font-size: clamp(56px, 12vw, 180px);
        font-weight: 800;
        letter-spacing: -2px;
        margin: 0 0 10px;
        background: linear-gradient(180deg, #fd5190 0%, #9e6de0 100%);
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .subtitle {
        font-size: 22px;
        font-weight: 700;
        color: black; /* Plain black text */
        margin-bottom: 6px;
        -webkit-background-clip: unset;
        background-clip: unset;
        -webkit-text-fill-color: unset;
    }


    .desc {
        color: #6c757d;
        max-width: 780px;
        margin: 0 auto 20px;
        text-align: center;
    }

    .actions .btn {
        padding: 12px 26px;
        border-radius: 999px;
    }
</style>
</head>
<body class="bg-light-gray" id="body">
  <div class="container-full">
    <div class="text-center px-3">
      <div class="oops-title">Oops!</div>
      <div class="subtitle">404 — Page Not Found</div>
      <p class="desc">The page you’re looking for doesn’t exist or was moved.</p>
      <div class="actions d-flex gap-2 justify-content-center flex-wrap">
        <a href="{{ route('cms.dashboard.index') }}" class="btn btn-primary btn-pill mr-2">Go to Dashboard</a>
        <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-pill">Login</a>
      </div>
    </div>
  </div>
</body>
</html>
