@extends('layouts.app')

@section('content')
<div class="wrapper">
  <div class="content-wrapper">

    <!-- Dashboard Header -->
    <div class="mb-1">
      <h4 class="fw-bold text-dark mb-1">Super Admin Dashboard</h4>
      <p class="text-muted fs-5">Platform-wide overview of all organizations. </p>
      <div class="mt-2">
        <a href="{{ route('cms.setting.index') }}" class="btn btn-outline-secondary btn-sm">Settings</a>
      </div>
      <hr class="mt-2 mb-2">
    </div>

      <!-- Top Statistics -->
      <div class="row">

        <!-- Total Organizations -->
        <div class="col-xl-4 col-md-6 mb-3">
          <div class="card card-default bg-secondary shadow-sm border-0">
            <div class="d-flex align-items-center p-4 justify-content-between">
              <div class="icon-md bg-white rounded-circle d-flex align-items-center justify-content-center" style="width:50px; height:50px;">
                <i class="mdi mdi-domain text-secondary" style="font-size:24px;"></i>
              </div>
              <div class="text-right">
                <p class="text-white mb-1 fw-semibold">Total Organizations</p>
                <span class="h3 d-block text-white mb-0">{{ $organizations }}</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Total Users -->
        <div class="col-xl-4 col-md-6 mb-3">
          <div class="card card-default bg-success shadow-sm border-0">
            <div class="d-flex align-items-center p-4 justify-content-between">
              <div class="icon-md bg-white rounded-circle d-flex align-items-center justify-content-center" style="width:50px; height:50px;">
                <i class="mdi mdi-account-group-outline text-success" style="font-size:24px;"></i>
              </div>
              <div class="text-right">
                <p class="text-white mb-1 fw-semibold">Total Users</p>
                <span class="h3 d-block text-white mb-0">{{ $users }}</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Total Families -->
        <div class="col-xl-4 col-md-6 mb-3">
          <div class="card card-default bg-primary shadow-sm border-0">
            <div class="d-flex align-items-center p-4 justify-content-between">
              <div class="icon-md bg-white rounded-circle d-flex align-items-center justify-content-center" style="width:50px; height:50px;">
                <i class="mdi mdi-home-group text-primary" style="font-size:24px;"></i>
              </div>
              <div class="text-right">
                <p class="text-white mb-1 fw-semibold">Total Families</p>
                <span class="h3 d-block text-white mb-0">{{ $families }}</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Total Volunteers -->
        <div class="col-xl-4 col-md-6 mb-3">
          <div class="card card-default bg-warning shadow-sm border-0">
            <div class="d-flex align-items-center p-4 justify-content-between">
              <div class="icon-md bg-white rounded-circle d-flex align-items-center justify-content-center" style="width:50px; height:50px;">
                <i class="mdi mdi-account-heart-outline text-warning" style="font-size:24px;"></i>
              </div>
              <div class="text-right">
                <p class="text-white mb-1 fw-semibold">Total Volunteers</p>
                <span class="h3 d-block text-white mb-0">{{ $volunteers }}</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Total Aid Programs -->
        <div class="col-xl-4 col-md-6 mb-3">
          <div class="card card-default bg-info shadow-sm border-0">
            <div class="d-flex align-items-center p-4 justify-content-between">
              <div class="icon-md bg-white rounded-circle d-flex align-items-center justify-content-center" style="width:50px; height:50px;">
                <i class="mdi mdi-hand-heart text-info" style="font-size:24px;"></i>
              </div>
              <div class="text-right">
                <p class="text-white mb-1 fw-semibold">Total Aid Programs</p>
                <span class="h3 d-block text-white mb-0">{{ $aidPrograms }}</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Active Organizations -->
        <div class="col-xl-4 col-md-6 mb-3">
          <div class="card card-default bg-danger shadow-sm border-0">
            <div class="d-flex align-items-center p-4 justify-content-between">
              <div class="icon-md bg-white rounded-circle d-flex align-items-center justify-content-center" style="width:50px; height:50px;">
                <i class="mdi mdi-check-decagram-outline text-danger" style="font-size:24px;"></i>
              </div>
              <div class="text-right">
                <p class="text-white mb-1 fw-semibold">Active Organizations</p>
                <span class="h3 d-block text-white mb-0">{{ $activeOrganizations }}</span>
              </div>
            </div>
          </div>
        </div>

      </div>

      </div>
    </div>

@endsection
