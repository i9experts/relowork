@extends('layouts.app')

@section('title', 'Method Not Allowed')

@section('content')
<div class="wrapper">
  <div class="content-wrapper">
    <div class="d-flex justify-content-between align-items-center mb-1 flex-wrap">
      <div>
        <h4 class="fw-bold text-dark mb-1">405 — Method Not Allowed</h4>
        <p class="text-muted fs-5 mb-0">The HTTP method used is not allowed for this route.</p>
      </div>
    </div>
    <hr class="mt-2 mb-2">
    <div class="card">
      <div class="card-body text-center">
        <div class="mb-3">
          <i class="mdi mdi-block-helper" style="font-size:64px;color:#ff6b6b;"></i>
        </div>
        <a href="{{ route('cms.dashboard.index') }}" class="btn btn-primary btn-pill">Go to Dashboard</a>
      </div>
    </div>
  </div>
</div>
@endsection
