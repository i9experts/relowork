<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Reset Password | ReloWork</title>
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

            <h4 class="text-dark fw-bold mb-2">Set a New Password</h4>
            <p class="text-muted mb-0">Enter your new password and confirm it below.</p>
          </div>

          @include('partials.errors')

          <form action="{{ route('password.update') }}" method="POST">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email }}">

            <div class="form-group mb-3">
              <label for="password" class="fw-bold">New Password</label>
              <div class="input-group">
                <input type="password" class="form-control input-lg" id="password" name="password" required>
                <div class="input-group-append">
                  <button class="btn btn-outline-secondary" type="button" id="toggle-reset-password" tabindex="-1">
                    <i class="mdi mdi-eye-off"></i>
                  </button>
                </div>
              </div>
              <small class="text-muted">
                Password must be at least 8 characters and include uppercase, lowercase, numeric and special characters.
              </small>
            </div>

            <div class="form-group mb-4">
              <label for="password_confirmation" class="fw-bold">Confirm Password</label>
              <div class="input-group">
                <input type="password" class="form-control input-lg" id="password_confirmation" name="password_confirmation" required>
                <div class="input-group-append">
                  <button class="btn btn-outline-secondary" type="button" id="toggle-reset-password-confirm" tabindex="-1">
                    <i class="mdi mdi-eye-off"></i>
                  </button>
                </div>
              </div>
              <small class="text-danger d-none" id="reset-password-mismatch">Passwords do not match. Please try again.</small>
              <small class="text-success d-none" id="reset-password-match">Passwords match ✔️</small>
            </div>

            <button type="submit" class="btn btn-primary btn-pill w-100 mb-3">Update Password</button>

            <div class="text-center">
              <a href="{{ route('login') }}" class="text-color fw-bold">Back to login</a>
            </div>
          </form>

        </div>
      </div>
    </div>
  </div>
</div>

<script>
  (function () {
    var password = document.getElementById('password');
    var passwordConfirmation = document.getElementById('password_confirmation');
    var mismatchText = document.getElementById('reset-password-mismatch');
    var matchText = document.getElementById('reset-password-match');

    function checkPasswordMatch() {
      if (!password || !passwordConfirmation || !mismatchText || !matchText) return;

      if (passwordConfirmation.value === '') {
        mismatchText.classList.add('d-none');
        matchText.classList.add('d-none');
        return;
      }

      if (password.value !== passwordConfirmation.value) {
        mismatchText.classList.remove('d-none');
        matchText.classList.add('d-none');
      } else {
        mismatchText.classList.add('d-none');
        matchText.classList.remove('d-none');
      }
    }

    if (password && passwordConfirmation) {
      password.addEventListener('input', checkPasswordMatch);
      passwordConfirmation.addEventListener('input', checkPasswordMatch);
    }

    function setupToggle(inputId, buttonId) {
      var input = document.getElementById(inputId);
      var btn = document.getElementById(buttonId);
      if (!input || !btn) return;

      btn.addEventListener('click', function () {
        var icon = this.querySelector('i');
        if (input.type === 'password') {
          input.type = 'text';
          if (icon) {
            icon.classList.remove('mdi-eye-off');
            icon.classList.add('mdi-eye');
          }
        } else {
          input.type = 'password';
          if (icon) {
            icon.classList.remove('mdi-eye');
            icon.classList.add('mdi-eye-off');
          }
        }
      });
    }

    setupToggle('password', 'toggle-reset-password');
    setupToggle('password_confirmation', 'toggle-reset-password-confirm');
  })();
</script>

</body>
</html>
