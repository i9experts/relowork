@extends('layouts.app')

@section('title', 'Profile')

@section('content')
<div class="wrapper">
  <div class="content-wrapper">
    <div class="d-flex justify-content-between align-items-center mb-1 flex-wrap">
      <div>
        <h4 class="fw-bold text-dark mb-1">Profile</h4>
        <p class="text-muted fs-5 mb-0">Update your personal information and password</p>
      </div>
    </div>

    <hr class="mt-2 mb-2">

    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <div class="d-flex align-items-center mb-4">
              <div class="mr-3">
                @php $avatar = optional($user)->avatar_path; @endphp
                @if($avatar)
                  <img src="{{ asset('storage/'.$avatar) }}" alt="Avatar" style="width:64px;height:64px;border-radius:50%;object-fit:cover;">
                @elseif(!empty($volunteer) && !empty($volunteer->picture_path))
                  <img src="{{ asset($volunteer->picture_path) }}" alt="Avatar" style="width:64px;height:64px;border-radius:50%;object-fit:cover;">
                @else
                  <div style="width:64px;height:64px;border-radius:50%;background:#ddd;display:flex;align-items:center;justify-content:center;font-weight:bold;color:#555;">
                    {{ \Illuminate\Support\Str::of(optional($user)->name ?? 'U')->trim()->substr(0,1)->upper() }}
                  </div>
                @endif
              </div>
              <div>
                <h5 class="mb-1">{{ optional($user)->name }}</h5>
                <p class="mb-0 text-muted">{{ optional($user)->email }}</p>
                <p class="mb-0 text-muted">{{ optional($organization)->organization_name ?? '—' }}</p>
                <span class="badge badge-secondary mt-1">
                  @if(method_exists($user,'isSystemAdmin') && $user->isSystemAdmin()) Super Admin
                  @elseif($user && $user->is_admin) Admin
                  @else Volunteer
                  @endif
                </span>
              </div>
            </div>
            @include('partials.errors')
            @php $currentUser = auth()->user(); @endphp
            <h4 class="card-title fw-bold mb-1">Personal Details</h4>
            <p class="text-muted mb-4">Update your name and email</p>
            <form method="POST" action="{{ route('cms.setting.update-profile') }}" enctype="multipart/form-data">
              @csrf
              <div class="form-row">
                <div class="col-md-6 mb-3">
                  <input type="text" class="form-control rounded-0 bg-light" placeholder="Enter Name" name="name" value="{{ old('name', optional($currentUser)->name) }}">
                </div>
                <div class="col-md-6 mb-3">
                  <input type="email" class="form-control rounded-0 bg-light" placeholder="Enter Email" name="email" value="{{ old('email', optional($currentUser)->email) }}">
                </div>
              </div>
              <div class="form-row">
                <div class="col-md-6 mb-3">
                  <input type="file" class="form-control rounded-0 bg-light" name="avatar" accept="image/*">
                  @if($errors->has('avatar'))
                    <small class="text-danger">{{ $errors->first('avatar') }}</small>
                  @endif
                </div>
              </div>
              <button type="submit" class="btn btn-primary btn-pill">
                <i class="mdi mdi-account-check-outline me-1"></i>
                Save Profile
              </button>
            </form>

            <div class="col-md-12 mt-4">
              <hr>
              <p class="fw-bold mb-3">Change Password</p>
              <hr>
            </div>

            <form method="POST" action="{{ route('cms.setting.change-password') }}" id="change-password-form">
                @csrf
                <div class="form-row">
                  <div class="col-md-4 mb-3">
                    <div class="input-group">
                      <input type="password" class="form-control rounded-0 bg-light" placeholder="Current Password" name="current_password" id="current_password" required>
                      <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="button" id="toggle-profile-current-password" tabindex="-1">
                          <i class="mdi mdi-eye-off"></i>
                        </button>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4 mb-1">
                    <div class="input-group">
                      <input type="password" class="form-control rounded-0 bg-light" placeholder="New Password" name="password" id="reset-password" required>
                      <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="button" id="toggle-profile-password" tabindex="-1">
                          <i class="mdi mdi-eye-off"></i>
                        </button>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4 mb-1">
                    <div class="input-group">
                      <input type="password" class="form-control rounded-0 bg-light" placeholder="Confirm New Password" name="password_confirmation" id="reset-password-confirm" required>
                      <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="button" id="toggle-profile-password-confirm" tabindex="-1">
                          <i class="mdi mdi-eye-off"></i>
                        </button>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-12 mb-1">
                    <small class="text-danger d-none" id="reset-password-mismatch">Passwords do not match. Please try again.</small>
                    <small class="text-success d-none" id="reset-password-match">Passwords match ✔️</small>
                  </div>
                  <div class="col-md-12 mb-2">
                    <small class="text-muted">Password must be at least 8 characters and include uppercase, lowercase, numeric and special characters.</small>
                  </div>
                </div>
                <button type="submit" class="btn btn-secondary btn-pill" id="change-password-submit">
                  <i class="mdi mdi-lock-check me-1"></i>
                  Update Password
                </button>
              </form>

            @if($volunteer)
            <div class="col-md-12 mt-4">
              <hr>
              <p class="text-center fw-bold mb-3">Volunteer Details</p>
              <hr>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="card">
                  <div class="card-body">
                    <div class="table-responsive">
                      <table class="table table-striped">
                        <tbody>
                          <tr><th>Full Name</th><td>{{ $volunteer->full_name ?? '—' }}</td></tr>
                          <tr><th>CNIC / ID</th><td>{{ $volunteer->cnic ?? '—' }}</td></tr>
                          <tr><th>Gender</th><td>{{ $volunteer->gender ?? '—' }}</td></tr>
                          <tr><th>Date of Birth</th><td>{{ $volunteer->dob ? \Illuminate\Support\Carbon::parse($volunteer->dob)->format('Y-m-d') : '—' }}</td></tr>
                          <tr><th>Address</th><td>{{ $volunteer->address ?? '—' }}</td></tr>
                          <tr><th>Education Level</th><td>{{ $volunteer->education_level ?? '—' }}</td></tr>
                          <tr><th>Skills</th><td>{{ $volunteer->skills ?? '—' }}</td></tr>
                          <tr><th>Languages</th><td>{{ $volunteer->languages ?? '—' }}</td></tr>
                          <tr><th>Availability</th><td>{{ $volunteer->availability ?? '—' }}</td></tr>
                          <tr>
                            <th>Preferred Projects</th>
                            <td>
                              @php $pp = collect($volunteer->preferred_projects ?? []); @endphp
                              {{ $pp->isEmpty() ? '—' : $pp->join(', ') }}
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('page-scripts')
<script>
  (function(){
    var p1 = document.getElementById('reset-password');
    var p2 = document.getElementById('reset-password-confirm');
    var msgMatch = document.getElementById('reset-password-match');
    var msgMismatch = document.getElementById('reset-password-mismatch');
  var submitBtn = document.getElementById('change-password-submit');

    function isStrong(s){
      if (!s || s.length < 8) return false;
      var hasUpper = /[A-Z]/.test(s);
      var hasLower = /[a-z]/.test(s);
      var hasNum = /[0-9]/.test(s);
      var hasSpec = /[^A-Za-z0-9]/.test(s);
      return hasUpper && hasLower && hasNum && hasSpec;
    }

    function updateState(){
      var v1 = p1 ? p1.value : '';
      var v2 = p2 ? p2.value : '';
      var match = v1.length > 0 && v1 === v2;
      if (msgMatch && msgMismatch){
        if (match){
          msgMatch.classList.remove('d-none');
          msgMismatch.classList.add('d-none');
        } else {
          msgMatch.classList.add('d-none');
          if (v2.length > 0) msgMismatch.classList.remove('d-none'); else msgMismatch.classList.add('d-none');
        }
      }
      var valid = match && isStrong(v1);
      if (submitBtn){ submitBtn.disabled = !valid; }
    }

    if (p1) p1.addEventListener('input', updateState);
    if (p2) p2.addEventListener('input', updateState);
    updateState();

    function setupToggle(inputId, buttonId){
      var input = document.getElementById(inputId);
      var btn = document.getElementById(buttonId);
      if (!input || !btn) return;
      btn.addEventListener('click', function(){
        var icon = this.querySelector('i');
        if (input.type === 'password'){
          input.type = 'text';
          if (icon){ icon.classList.remove('mdi-eye-off'); icon.classList.add('mdi-eye'); }
        } else {
          input.type = 'password';
          if (icon){ icon.classList.remove('mdi-eye'); icon.classList.add('mdi-eye-off'); }
        }
      });
    }
    setupToggle('current_password','toggle-profile-current-password');
    setupToggle('reset-password','toggle-profile-password');
    setupToggle('reset-password-confirm','toggle-profile-password-confirm');
  })();
</script>
@endpush
