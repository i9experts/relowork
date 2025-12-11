@extends('layouts.app')

@section('title', 'Bad Gateway')

@section('content')
<div class="wrapper">
  <div class="content-wrapper">
    <div class="d-flex justify-content-between align-items-center mb-1 flex-wrap">
      <div>
        <h4 class="fw-bold text-dark mb-1">502 — Bad Gateway</h4>
        <p class="text-muted fs-5 mb-0">A server upstream responded with an invalid response.</p>
      </div>
    </div>
    <hr class="mt-2 mb-2">
    <div class="card">
      <div class="card-body text-center">
        <div class="mb-3">
          <i class="mdi mdi-cloud-alert" style="font-size:64px;color:#9e6de0;"></i>
        </div>
        <a href="{{ route('cms.dashboard.index') }}" class="btn btn-primary btn-pill">Go to Dashboard</a>
      </div>
    </div>
  </div>
</div>
@endsection
