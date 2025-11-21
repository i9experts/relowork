<!DOCTYPE html>

<html lang="en">
<head>
  <head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <title>Login page</title>

  @php($__settings = isset($organization) && $organization ? \App\Models\Setting::where('organization_id', $organization->id)->first() : null)
  @if($__settings && $__settings->favicon_path)
    <link href="{{ asset('storage/'.$__settings->favicon_path) }}" rel="shortcut icon" />
  @else
    <link href="-" rel="shortcut icon" />
  @endif

  <!-- GOOGLE FONTS -->
  <link href="https://fonts.googleapis.com/css?family=Karla:400,700|Roboto" rel="stylesheet">
  <link href="{{ url('assets/plugins/material/css/materialdesignicons.min.css') }}" rel="stylesheet" />
  <link href="{{ url('assets/plugins/simplebar/simplebar.css') }}" rel="stylesheet" />

  <!-- PLUGINS CSS STYLE -->
  <link href="{{ url('assets/plugins/nprogress/nprogress.css') }}" rel="stylesheet" />
  
  <!-- MONO CSS -->
  <link id="main-css-href" rel="stylesheet" href="{{ url('assets/css/style.css') }}" />

  


  <!--
    HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries
  -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
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

</head>
  <body class="bg-light-gray" id="body">
          <div class="container d-flex align-items-center justify-content-center" style="min-height: 100vh">
          <div class="d-flex flex-column justify-content-between">
            <div class="row justify-content-center">
              <div class="col-lg-6 col-md-10">
                <div class="card card-default mb-0" style="border-radius:16px;">
                  <div class="gradient-header"></div>
                  <div class="card-body px-5 pb-5 pt-4">

                    <div class="text-center mb-4">
                      <div class="app-brand w-100 d-flex justify-content-center border-bottom-0">
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
                    </div>

                    <h4 class="text-dark text-center mb-3">Sign in to your account</h4>

                    @include('partials.errors')

                    <form action="{{ route('login.post') }}" method="POST">
                      @csrf
                      <div class="row">
                        <div class="form-group col-md-12 mb-4">
                          <input type="email" class="form-control input-lg" id="email" name="email" aria-describedby="emailHelp"
                            placeholder="Enter email" value="{{ old('email') }}" required>
                        </div>
                        <div class="form-group col-md-12 ">
                          <div class="input-group">
                            <input type="password" class="form-control input-lg" id="password" name="password" placeholder="Enter Password" required>
                            <div class="input-group-append">
                              <button class="btn btn-outline-secondary" type="button" id="toggle-login-password" tabindex="-1">
                                <i class="mdi mdi-eye-off"></i>
                              </button>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-12">

                          <div class="d-flex justify-content-between mb-3">

                            <div class="custom-control custom-checkbox mr-3 mb-3">
                              <input type="checkbox" class="custom-control-input" id="customCheck2" name="remember">
                              <label class="custom-control-label" for="customCheck2">Remember me</label>
                            </div>

                            <a class="text-color" href="{{ route('password.request') }}"> Forgot password? </a>

                          </div>

                          <button type="submit" class="btn btn-primary btn-pill mb-4">Sign In</button>

                        </div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

    <script>
      (function () {
        var input = document.getElementById('password');
        var btn = document.getElementById('toggle-login-password');
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
      })();
    </script>

</body>
</html>
