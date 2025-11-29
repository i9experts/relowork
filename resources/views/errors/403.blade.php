@extends('layouts.app')

@section('title', 'Unauthorized')

@section('content')
<div class="wrapper">
  <div class="content-wrapper">
    <div class="d-flex justify-content-between align-items-center mb-1 flex-wrap">
      <div>
        <h4 class="fw-bold text-dark mb-1">403 — Unauthorized</h4>
        <p class="text-muted fs-5 mb-0">You don’t have permission to view this page.</p>
      </div>
    </div>
    <hr class="mt-2 mb-2">
    <div class="card">
      <div class="card-body text-center">
        <div class="mb-3">
          <i class="mdi mdi-lock-outline" style="font-size:64px;color:#faafca;"></i>
        </div>
        <p class="mb-3">Try returning to your dashboard or contact your administrator.</p>
        <a href="{{ route('cms.dashboard.index') }}" class="btn btn-primary btn-pill">Go to Dashboard</a>
      </div>
    </div>
  </div>
</div>
@endsection

