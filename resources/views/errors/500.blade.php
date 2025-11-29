@extends('layouts.app')

@section('title', 'Server Error')

@section('content')
<div class="wrapper">
  <div class="content-wrapper">
    <div class="d-flex justify-content-between align-items-center mb-1 flex-wrap">
      <div>
        <h4 class="fw-bold text-dark mb-1">500 — Server Error</h4>
        <p class="text-muted fs-5 mb-0">Something went wrong on our side.</p>
      </div>
    </div>
    <hr class="mt-2 mb-2">
    <div class="card">
      <div class="card-body text-center">
        <div class="mb-3">
          <i class="mdi mdi-server" style="font-size:64px;color:#f2e052;"></i>
        </div>
        <p class="mb-3">Please try again later or return to your dashboard.</p>
        <a href="{{ route('cms.dashboard.index') }}" class="btn btn-primary btn-pill">Go to Dashboard</a>
      </div>
    </div>
  </div>
</div>
@endsection

