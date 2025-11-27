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
                @if(!empty($volunteer) && !empty($volunteer->picture_path))
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
            @php($currentUser = auth()->user())
            <h4 class="card-title fw-bold mb-1">Personal Details</h4>
            <p class="text-muted mb-4">Update your name and email</p>
            <form method="POST" action="{{ route('cms.setting.update-profile') }}">
              @csrf
              <div class="form-row">
                <div class="col-md-6 mb-3">
                  <input type="text" class="form-control rounded-0 bg-light" placeholder="Enter Name" name="name" value="{{ old('name', optional($currentUser)->name) }}">
                </div>
                <div class="col-md-6 mb-3">
                  <input type="email" class="form-control rounded-0 bg-light" placeholder="Enter Email" name="email" value="{{ old('email', optional($currentUser)->email) }}">
                </div>
              </div>
              <button type="submit" class="btn btn-primary btn-pill">
                <i class="mdi mdi-account-check-outline me-1"></i>
                Save Profile
              </button>
            </form>

            <div class="col-md-12 mt-4">
              <hr>
              <p class="text-center fw-bold mb-3">Change Password</p>
              <hr>
            </div>

            <form method="POST" action="{{ route('cms.setting.change-password') }}">
              @csrf
              <div class="form-row">
                <div class="col-md-4 mb-3">
                  <input type="password" class="form-control rounded-0 bg-light" placeholder="Current Password" name="current_password" required>
                </div>
                <div class="col-md-4 mb-3">
                  <input type="password" class="form-control rounded-0 bg-light" placeholder="New Password" name="password" required>
                </div>
                <div class="col-md-4 mb-3">
                  <input type="password" class="form-control rounded-0 bg-light" placeholder="Confirm New Password" name="password_confirmation" required>
                </div>
              </div>
              <button type="submit" class="btn btn-secondary btn-pill">
                <i class="mdi mdi-lock-check me-1"></i>
                Update Password
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
