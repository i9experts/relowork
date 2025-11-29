@extends('layouts.app')

@section('title', 'Page Not Found')

@section('content')
<div class="wrapper">
  <div class="content-wrapper">
    <div class="d-flex justify-content-between align-items-center mb-1 flex-wrap">
      <div>
        <h4 class="fw-bold text-dark mb-1">404 — Page Not Found</h4>
        <p class="text-muted fs-5 mb-0">The page you’re looking for doesn’t exist or was moved.</p>
      </div>
    </div>
    <hr class="mt-2 mb-2">
    <div class="card">
      <div class="card-body text-center">
        <div class="mb-3">
          <i class="mdi mdi-alert-circle-outline" style="font-size:64px;color:#9e6de0;"></i>
        </div>
        <p class="mb-3">Please check the URL or return to the dashboard.</p>
        <a href="{{ route('cms.dashboard.index') }}" class="btn btn-primary btn-pill">Go to Dashboard</a>
        <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-pill">Login</a>
      </div>
    </div>
  </div>
</div>
@endsection

