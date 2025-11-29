@extends('layouts.app')

@section('title', 'Too Many Requests')

@section('content')
<div class="wrapper">
  <div class="content-wrapper">
    <div class="d-flex justify-content-between align-items-center mb-1 flex-wrap">
      <div>
        <h4 class="fw-bold text-dark mb-1">429 — Too Many Requests</h4>
        <p class="text-muted fs-5 mb-0">You’ve made too many requests in a short time.</p>
      </div>
    </div>
    <hr class="mt-2 mb-2">
    <div class="card">
      <div class="card-body text-center">
        <div class="mb-3">
          <i class="mdi mdi-speedometer-slow" style="font-size:64px;color:#46c79e;"></i>
        </div>
        <p class="mb-3">Please wait a moment and try again.</p>
        <a href="{{ route('cms.dashboard.index') }}" class="btn btn-primary btn-pill">Go to Dashboard</a>
      </div>
    </div>
  </div>
</div>
@endsection

