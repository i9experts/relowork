<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <title>Organization Form</title>

  <!-- GOOGLE FONTS -->
  <link href="https://fonts.googleapis.com/css?family=Karla:400,700|Roboto" rel="stylesheet">
  <link href="{{ url('assets/plugins/material/css/materialdesignicons.min.css') }}" rel="stylesheet" />
  <link href="{{ url('assets/plugins/simplebar/simplebar.css') }}" rel="stylesheet" />

  <!-- PLUGINS CSS STYLE -->
  <link href="{{ url('assets/plugins/nprogress/nprogress.css') }}" rel="stylesheet" />

  <!-- MAIN CSS -->
  <link id="main-css-href" rel="stylesheet" href="{{ url('assets/css/style.css') }}" />

  <script src="{{ url('assets/plugins/nprogress/nprogress.js') }}"></script>
</head>

<body class="bg-light-gray" id="body">

  <div class="container d-flex align-items-center justify-content-center" style="min-height: 100vh">
    <div class="row w-100 justify-content-center">
      <div class="col-md-12 col-lg-8 mt-5">

        <div class="card card-default">
          <div class="card-body px-5 pb-5 pt-4">

            <div class="text-center mb-4">
                <img src="{{ url('assets/images/logo/relowork-dark-logo.png') }}" alt="ReloWork" style="width:250px; height:auto; display:block; margin:0 auto 20px;">
                <h4 class="text-dark">Setup Your Organization</h4>
            </div>

            @include('partials.errors')

            <form action="{{ route('cms.organization-form.store') }}" method="POST" enctype="multipart/form-data">
              @csrf

              <div class="form-row">

                <div class="form-group col-md-6 mb-3">
                  <label class="form-label">Registration Number <span class="text-danger">*</span></label>
                  <input type="text" name="registration_number" class="form-control" value="{{ old('registration_number') }}">
                </div>

                <div class="form-group col-md-6 mb-3">
                  <label class="form-label">Organization Name <span class="text-danger">*</span></label>
                  <input type="text" name="organization_name" class="form-control" value="{{ old('organization_name') }}">
                </div>

                <div class="form-group col-md-6 mb-3">
                  <label class="form-label">Email <span class="text-danger">*</span></label>
                  <input type="email" name="email" class="form-control" value="{{ old('email') }}">
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
                  <label class="form-label">Password</label>
                  <input type="password" name="password" class="form-control">
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

</body>
</html>
