<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <title>Verify Organization Email</title>

  <link href="https://fonts.googleapis.com/css?family=Karla:400,700|Roboto" rel="stylesheet">
  <link href="{{ url('assets/plugins/material/css/materialdesignicons.min.css') }}" rel="stylesheet" />
  <link href="{{ url('assets/plugins/simplebar/simplebar.css') }}" rel="stylesheet" />
  <link href="{{ url('assets/plugins/nprogress/nprogress.css') }}" rel="stylesheet" />
  <link id="main-css-href" rel="stylesheet" href="{{ url('assets/css/style.css') }}" />
  <script src="{{ url('assets/plugins/nprogress/nprogress.js') }}"></script>
</head>

<body class="bg-light-gray" id="body">
  <div class="container d-flex align-items-center justify-content-center" style="min-height: 100vh">
    <div class="row w-100 justify-content-center">
      <div class="col-md-12 col-lg-6 mt-5">
        <div class="card card-default">
          <div class="card-body px-5 pb-5 pt-4">

            <div class="text-center mb-4">
              <img src="{{ url('assets/images/logo/relowork-dark-logo.png') }}" alt="ReloWork" style="width:200px; height:auto; display:block; margin:0 auto 20px;">
              <h4 class="text-dark mb-1">Verify Your Email</h4>
              <p class="text-muted mb-0">We have sent a verification code to <strong>{{ $organization->email }}</strong></p>
            </div>

            @include('partials.errors')

            @if(session('success'))
              <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form action="{{ route('organization.otp.verify', $organization->id) }}" method="POST">
              @csrf
              <div class="form-group mb-3">
                <label class="form-label">Verification Code (OTP)</label>
                <input type="text" name="otp_code" class="form-control" placeholder="6 digit code" value="{{ old('otp_code') }}" autofocus>
              </div>

              <div class="d-flex justify-content-between align-items-center mt-3">
                <button type="submit" class="btn btn-primary btn-pill px-4">Verify</button>

                <button type="submit" formaction="{{ route('organization.otp.resend', $organization->id) }}" class="btn btn-link px-0">Resend Code</button>
              </div>
            </form>

          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
