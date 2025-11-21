<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <title>Organization Form</title>
  
  <link href="{{ url('assets/images/logo/relowork-dark-logo.png') }}" rel="shortcut icon" />
  <!-- GOOGLE FONTS -->
  <link href="https://fonts.googleapis.com/css?family=Karla:400,700|Roboto" rel="stylesheet">
  <link href="{{ url('assets/plugins/material/css/materialdesignicons.min.css') }}" rel="stylesheet" />
  <link href="{{ url('assets/plugins/simplebar/simplebar.css') }}" rel="stylesheet" />

  <!-- PLUGINS CSS STYLE -->
  <link href="{{ url('assets/plugins/nprogress/nprogress.css') }}" rel="stylesheet" />

  <!-- MAIN CSS -->
  <link id="main-css-href" rel="stylesheet" href="{{ url('assets/css/style.css') }}" />

  <script src="{{ url('assets/plugins/nprogress/nprogress.js') }}"></script>
  <style>
    .gradient-header {
      /* background: linear-gradient(135deg, var(--primary), var(--secondary)); */
      background-color: var(--secondary);
      height: 6px;
      width: 100%;
      border-radius: 10px 10px 0 0;
    }
  </style>
</head>

<body class="bg-light-gray" id="body">

  <div class="container d-flex align-items-center justify-content-center" style="min-height: 100vh">
    <div class="row w-100 justify-content-center">
      <div class="col-md-12 col-lg-8 mt-5">

      <div class="card card-default" style="border-radius:16px;">
        <div class="gradient-header"></div>
          <div class="card-body px-5 pb-5 pt-4">

          <div class="text-center mb-4">
              <img src="{{ url('assets/images/logo/relowork-dark-logo.png') }}" alt="ReloWork" style="width:250px; height:auto; display:block; margin:0 auto 20px;">
              <h4 class="text-dark fw-bold mb-1">Welcome to ReloWork!</h4>
              <p class="text-muted fw-bold mb-1">Let’s set up your organization and get started.</p>
          </div>


            @include('partials.errors')

            <form action="{{ route('organization.register.store') }}" method="POST" enctype="multipart/form-data" id="organization-form">
              @csrf

              <div class="form-row">

                <div class="form-group col-md-6 mb-3">
                  <label class="form-label">Organization Name <span class="text-danger">*</span></label>
                  <input type="text" name="organization_name" class="form-control" value="{{ old('organization_name') }}">
                </div>

                <div class="form-group col-md-6 mb-3">
                  <label class="form-label">Email <span class="text-danger">*</span></label>
                  <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                </div>

                <div class="form-group col-md-6 mb-3">
                  <label class="form-label">Organization Domain <span class="text-danger">*</span></label>
                  <input type="text" name="domain" id="domain" class="form-control" value="{{ old('domain') }}" placeholder="">
                  <small class="text-muted">
                    Your organization subdomain: <strong><span id="domain-preview">example</span>.relowork</strong>
                  </small>

                  <small class="text-danger d-none" id="domain-error">
                    This domain is already taken. Please choose another.
                  </small>


                </div>

                <div class="form-group col-md-6 mb-3">
                  <label class="form-label">Phone</label>
                  <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                </div>

                <div class="form-group col-md-12 mb-3">
                  <label class="form-label">Address</label>
                  <input type="text" name="address" class="form-control" value="{{ old('address') }}">
                </div>

                <div class="form-group col-md-6 mb-3">
                  <label class="form-label">Website</label>
                  <input type="text" name="website" class="form-control" value="{{ old('website') }}">
                </div>

                <div class="form-group col-md-6 mb-3">
                  <label class="form-label">Logo</label>
                  <input type="file" name="logo" class="form-control">
                </div>

                <div class="form-group col-md-6 mb-3">
                  <label class="form-label">Password <span class="text-danger">*</span></label>
                  <div class="input-group">
                    <input type="password" name="password" id="password" class="form-control" autocomplete="new-password">
                    <div class="input-group-append">
                      <button class="btn btn-outline-secondary" type="button" id="toggle-password" tabindex="-1">
                        <i class="mdi mdi-eye-off"></i>
                      </button>
                    </div>
                  </div>
                  <small class="text-muted">
                    Password must be at least 8 characters and include uppercase, lowercase, numeric and special characters.
                  </small>

                </div>

                <div class="form-group col-md-6 mb-3">
                  <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                  <div class="input-group">
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" autocomplete="new-password">
                    <div class="input-group-append">
                      <button class="btn btn-outline-secondary" type="button" id="toggle-password-confirmation" tabindex="-1">
                        <i class="mdi mdi-eye-off"></i>
                      </button>
                    </div>
                  </div>
                  <small class="text-danger d-none" id="password-mismatch">Passwords do not match. Please try again.</small>
                  <small class="text-success d-none" id="password-match">Passwords match ✔️</small>

                </div>

              </div>

              <div class="d-flex justify-content-center">
                <button type="submit" class="btn btn-primary btn-pill px-5 mt-3">Save</button>
              </div>

            </form>

          </div>
        </div>

      </div>
    </div>
  </div>

  <script>
    (function () {
      const password = document.getElementById('password');
      const passwordConfirmation = document.getElementById('password_confirmation');

      const mismatchText = document.getElementById('password-mismatch');
      const matchText = document.getElementById('password-match');

      const domainInput = document.getElementById('domain');
      const domainPreview = document.getElementById('domain-preview');
      const domainError = document.getElementById('domain-error');

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

      // Password toggle
      function bindPasswordToggle(inputId, toggleBtnId) {
        const input = document.getElementById(inputId);
        const btn = document.getElementById(toggleBtnId);
        if (!input || !btn) return;

        btn.addEventListener('click', function () {
          const icon = this.querySelector('i');
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

      bindPasswordToggle('password', 'toggle-password');
      bindPasswordToggle('password_confirmation', 'toggle-password-confirmation');

      // Domain preview
      if (domainInput && domainPreview) {
        const updatePreview = () => {
          const value = domainInput.value || 'example';
          domainPreview.textContent = value;
        };
        domainInput.addEventListener('input', updatePreview);
        updatePreview();
      }

      // Domain duplicate check
      if (window.fetch && domainInput && domainError) {
        let timeoutId;
        domainInput.addEventListener('input', function () {
          domainError.classList.add('d-none');
          const value = this.value.trim();
          if (!value) return;

          clearTimeout(timeoutId);
          timeoutId = setTimeout(() => {
            fetch("{{ url('/api/organizations/check-domain') }}?domain=" + encodeURIComponent(value), {
              headers: { 'Accept': 'application/json' }
            })
              .then(r => r.ok ? r.json() : null)
              .then(data => {
                if (data && data.exists) {
                  domainError.classList.remove('d-none');
                } else {
                  domainError.classList.add('d-none');
                }
              })
              .catch(() => {});
          }, 400);
        });
      }
    })();
</script>



</body>
</html>
