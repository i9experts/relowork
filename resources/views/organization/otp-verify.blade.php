<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <title>Verify Organization Email | ReloWork</title>
  
  <link href="{{ url('assets/images/logo/relowork-dark-logo.png') }}" rel="shortcut icon" />
  <link href="https://fonts.googleapis.com/css?family=Karla:400,700|Roboto" rel="stylesheet">
  <link href="{{ url('assets/plugins/material/css/materialdesignicons.min.css') }}" rel="stylesheet" />
  <link href="{{ url('assets/plugins/simplebar/simplebar.css') }}" rel="stylesheet" />
  <link href="{{ url('assets/plugins/nprogress/nprogress.css') }}" rel="stylesheet" />
  <link id="main-css-href" rel="stylesheet" href="{{ url('assets/css/style.css') }}" />
  <script src="{{ url('assets/plugins/nprogress/nprogress.js') }}"></script>

  <style>
    .otp-input {
      font-size: 24px;
      text-align: center;
      letter-spacing: 8px;
      padding: 10px;
      height: 60px;
      border-radius: 12px;
      border: 2px solid #ced4da;
      transition: 0.3s;
      font-weight: 600;
    }

    .otp-input:focus {
      border-color: #005bea;
      box-shadow: 0 0 8px rgba(0,91,234,0.3);
    }

    .gradient-header {
      /* background: linear-gradient(135deg, var(--primary), var(--secondary)); */
      background-color: var(--secondary);
      height: 6px;
      width: 100%;
      border-radius: 10px 10px 0 0;
    }

    .resend-btn {
      font-size: 15px;
      font-weight: 600;
    }
  </style>
</head>

<body class="bg-light-gray" id="body">
  <div class="container d-flex align-items-center justify-content-center" style="min-height: 100vh">
    <div class="row w-100 justify-content-center">
      <div class="col-md-12 col-lg-5 mt-4">

        <div class="card card-default" style="border-radius:16px;">
          <div class="gradient-header"></div>
          <div class="card-body px-5 pb-5 pt-4">

            <div class="text-center mb-4">
              <img src="{{ url('assets/images/logo/relowork-dark-logo.png') }}" alt="ReloWork"
                style="width:200px; height:auto; display:block; margin:0 auto 20px;">
              <h3 class="text-dark fw-bold mb-1" style="font-size:26px;">Verify Your Email</h3>
              <p class="text-muted mb-2" style="font-size:15px;">
                Enter the 6-digit verification code sent to<br>
                <strong>{{ $organization->email }}</strong>
              </p>
            </div>

            @include('partials.errors')

            @if(session('success'))
              <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form action="{{ route('organization.otp.verify', $organization->id) }}" method="POST">
              @csrf

              <div class="form-group mb-4">
                <label class="form-label fw-semibold">Verification Code (OTP)</label>
                <input type="text" name="otp_code" class="form-control otp-input"
                       placeholder="______" value="{{ old('otp_code') }}" maxlength="6" autofocus>
              </div>

              <div class="d-flex justify-content-center">
               <button type="submit" class="btn btn-primary btn-pill px-5 mt-3">Verify & Countiune </button>
              </div>

              <div class="text-center mt-4">
                <span class="text-muted">Didn't receive the code?</span>
                <button type="submit" formaction="{{ route('organization.otp.resend', $organization->id) }}"
                        class="btn btn-link resend-btn px-0">
                  Resend Code
                </button>
              </div>

            </form>

          </div>
        </div>

      </div>
    </div>
  </div>
</body>
</html>
