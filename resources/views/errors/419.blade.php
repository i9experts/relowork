@extends('layouts.app')

@section('title', 'Page Expired')

@section('content')
<div class="wrapper">
  <div class="content-wrapper">
    <div class="d-flex justify-content-between align-items-center mb-1 flex-wrap">
      <div>
        <h4 class="fw-bold text-dark mb-1">419 — Page Expired</h4>
        <p class="text-muted fs-5 mb-0">Your session has expired. Please log in and try again.</p>
      </div>
    </div>
    <hr class="mt-2 mb-2">
    <div class="card">
      <div class="card-body text-center">
        <div class="mb-3">
          <i class="mdi mdi-timer-sand" style="font-size:64px;color:#005bea;"></i>
        </div>
        <a href="{{ route('login') }}" class="btn btn-primary btn-pill">Go to Login</a>
      </div>
    </div>
  </div>
</div>
@endsection
