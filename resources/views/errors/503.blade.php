@extends('layouts.app')

@section('title', 'Service Unavailable')

@section('content')
<div class="wrapper">
  <div class="content-wrapper">
    <div class="d-flex justify-content-between align-items-center mb-1 flex-wrap">
      <div>
        <h4 class="fw-bold text-dark mb-1">503 — Service Unavailable</h4>
        <p class="text-muted fs-5 mb-0">The service is temporarily unavailable due to maintenance.</p>
      </div>
    </div>
    <hr class="mt-2 mb-2">
    <div class="card">
      <div class="card-body text-center">
        <div class="mb-3">
          <i class="mdi mdi-tools" style="font-size:64px;color:#6696fe;"></i>
        </div>
        <p class="mb-3">Please check back in a little while.</p>
        <a href="{{ route('cms.dashboard.index') }}" class="btn btn-primary btn-pill">Go to Dashboard</a>
      </div>
    </div>
  </div>
</div>
@endsection

