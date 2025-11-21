<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Forgot Password | ReloWork</title>
  @php($__settings = isset($organization) && $organization ? \App\Models\Setting::where('organization_id', $organization->id)->first() : null)
  @if($__settings && $__settings->favicon_path)
    <link href="{{ asset('storage/'.$__settings->favicon_path) }}" rel="shortcut icon" />
  @else
    <link href="-" rel="shortcut icon" />
  @endif
  <link href="https://fonts.googleapis.com/css?family=Karla:400,700|Roboto" rel="stylesheet">
  <link href="{{ url('assets/plugins/material/css/materialdesignicons.min.css') }}" rel="stylesheet" />
  <link href="{{ url('assets/plugins/simplebar/simplebar.css') }}" rel="stylesheet" />
  <link href="{{ url('assets/plugins/nprogress/nprogress.css') }}" rel="stylesheet" />
  <link id="main-css-href" rel="stylesheet" href="{{ url('assets/css/style.css') }}" />
  <script src="{{ url('assets/plugins/nprogress/nprogress.js') }}"></script>
  <style>
    .gradient-header {
      background-color: var(--secondary);
      height: 6px;
      width: 100%;
      border-radius: 10px 10px 0 0;
    }
  </style>
</head>
<body class="bg-light-gray" id="body">

  <div class="container" style="min-height:100vh; display:flex; align-items:center; justify-content:center;">
    <div class="row w-100 justify-content-center">
      <div class="col-12 col-md-8 col-lg-6">
        <div class="card card-default mb-0" style="border-radius:16px;">
          <div class="gradient-header"></div>
          <div class="card-body px-4 py-5">

            <div class="text-center mb-4">
              <div class="app-brand w-100 d-flex justify-content-center border-bottom-0 mb-3">
                <a class="w-auto pl-0 d-flex flex-column align-items-center" href="#">
                  @if(isset($organization) && $organization && $organization->logo)
                    <img src="{{ asset('storage/' . $organization->logo) }}" alt="{{ $organization->organization_name }} Logo" style="max-width:180px; height:auto; display:block; margin-bottom:8px;">
                  @elseif(isset($organization) && $organization)
                    <span class="brand-name text-dark" style="font-size:22px; font-weight:700;">
                      {{ $organization->organization_name }}
                    </span>
                    <small class="text-muted">Logo</small>
                  @else
                    <span class="brand-name text-dark" style="font-size:22px; font-weight:700;">ReloWork</span>
                  @endif
                </a>
              </div>

              <h4 class="text-dark fw-bold mb-2">Forgot your password?</h4>
              <p class="text-muted mb-0">Enter your email and we’ll send you a reset link.</p>
            </div>

            @include('partials.errors')

            @if(session('status'))
              <div class="alert alert-success">{{ session('status') }}</div>
            @endif

            <form action="{{ route('password.email') }}" method="POST">
              @csrf
              <div class="form-group mb-4">
                <label for="email" class="fw-bold">Email Address</label>
                <input type="email" class="form-control input-lg" id="email" name="email" placeholder="Enter your email" required>
              </div>

              <button type="submit" class="btn btn-primary btn-pill w-100 mb-3">Send Reset Link</button>

              <div class="text-center">
                <a href="{{ route('login') }}" class="text-color fw-bold">Back to login</a>
              </div>
            </form>

          </div>
        </div>
      </div>
    </div>
  </div>

</body>
</html>
