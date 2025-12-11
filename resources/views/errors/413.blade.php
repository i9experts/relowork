@extends('layouts.app')

@section('title', 'Payload Too Large')

@section('content')
<div class="wrapper">
  <div class="content-wrapper">
    <div class="d-flex justify-content-between align-items-center mb-1 flex-wrap">
      <div>
        <h4 class="fw-bold text-dark mb-1">413 — Payload Too Large</h4>
        <p class="text-muted fs-5 mb-0">The request payload is too large.</p>
      </div>
    </div>
    <hr class="mt-2 mb-2">
    <div class="card">
      <div class="card-body text-center">
        <div class="mb-3">
          <i class="mdi mdi-upload" style="font-size:64px;color:#00b894;"></i>
        </div>
        <p class="mb-3">Reduce file size and try again.</p>
        <a href="{{ route('cms.dashboard.index') }}" class="btn btn-primary btn-pill">Go to Dashboard</a>
      </div>
    </div>
  </div>
</div>
@endsection
