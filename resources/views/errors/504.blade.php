@extends('layouts.app')

@section('title', 'Gateway Timeout')

@section('content')
<div class="wrapper">
  <div class="content-wrapper">
    <div class="d-flex justify-content-between align-items-center mb-1 flex-wrap">
      <div>
        <h4 class="fw-bold text-dark mb-1">504 — Gateway Timeout</h4>
        <p class="text-muted fs-5 mb-0">The server took too long to respond.</p>
      </div>
    </div>
    <hr class="mt-2 mb-2">
    <div class="card">
      <div class="card-body text-center">
        <div class="mb-3">
          <i class="mdi mdi-timer-off" style="font-size:64px;color:#6696fe;"></i>
        </div>
        <a href="{{ route('cms.dashboard.index') }}" class="btn btn-primary btn-pill">Go to Dashboard</a>
      </div>
    </div>
  </div>
</div>
@endsection
