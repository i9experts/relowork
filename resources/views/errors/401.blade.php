@extends('layouts.app')

@section('title', 'Authentication Required')

@section('content')
<div class="wrapper">
  <div class="content-wrapper">
    <div class="d-flex justify-content-between align-items-center mb-1 flex-wrap">
      <div>
        <h4 class="fw-bold text-dark mb-1">401 — Authentication Required</h4>
        <p class="text-muted fs-5 mb-0">Please log in to continue.</p>
      </div>
    </div>
    <hr class="mt-2 mb-2">
    <div class="card">
      <div class="card-body text-center">
        <div class="mb-3">
          <i class="mdi mdi-account-alert-outline" style="font-size:64px;color:#005bea;"></i>
        </div>
        <a href="{{ route('login') }}" class="btn btn-primary btn-pill">Go to Login</a>
      </div>
    </div>
  </div>
</div>
@endsection

