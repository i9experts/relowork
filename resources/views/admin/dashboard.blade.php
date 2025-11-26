@extends('layouts.app')

@section('content')
<div class="wrapper">
  <div class="content-wrapper">

    <!-- Dashboard Header -->
    <div class="mb-1">
      <h4 class="fw-bold text-dark mb-1">Super Admin Dashboard</h4>
      <p class="text-muted fs-5">Platform-wide overview of all organizations. </p>
      <hr class="mt-2 mb-2">
    </div>

    <!-- Top Statistics -->
    <div class="row">
      <!-- First box -->
      <div class="col-xl-4 col-md-6 mb-3">
        <div class="card card-default bg-secondary shadow-sm border-0">
          <div class="d-flex align-items-center p-4 justify-content-between">
            <div class="icon-md bg-white rounded-circle d-flex align-items-center justify-content-center" style="width:50px; height:50px;">
              <i class="mdi mdi-account-plus-outline text-secondary" style="font-size:24px;"></i>
            </div>
            <div class="text-right">
              <p class="text-white mb-1 fw-semibold">Total Organizations</p>
              <span class="h3 d-block text-white mb-0">{{ $organizations }}</span>
              <!-- <small class="text-light opacity-75">+12.5% from last month</small> -->
            </div>
          </div>
        </div>
      </div>

      <!-- Second box -->
      <div class="col-xl-4 col-md-6 mb-3">
        <div class="card card-default bg-success shadow-sm border-0">
          <div class="d-flex align-items-center p-4 justify-content-between">
            <div class="icon-md bg-white rounded-circle d-flex align-items-center justify-content-center" style="width:50px; height:50px;">
              <i class="mdi mdi-table-edit text-success" style="font-size:24px;"></i>
            </div>
            <div class="text-right">
              <p class="text-white mb-1 fw-semibold">Total Users</p>
              <span class="h3 d-block text-white mb-0">{{ $users }}</span>
              <!-- <small class="text-light opacity-75">+8.2% from last month</small> -->
            </div>
          </div>
        </div>
      </div>

      <!-- Third box -->
      <div class="col-xl-4 col-md-6 mb-3">
        <div class="card card-default bg-primary shadow-sm border-0">
          <div class="d-flex align-items-center p-4 justify-content-between">
            <div class="icon-md bg-white rounded-circle d-flex align-items-center justify-content-center" style="width:50px; height:50px;">
              <i class="mdi mdi-content-save-edit-outline text-primary" style="font-size:24px;"></i>
            </div>
            <div class="text-right">
              <p class="text-white mb-1 fw-semibold">Total Families</p>
              <span class="h3 d-block text-white mb-0">{{ $families }}</span>
              <!-- <small class="text-light opacity-75">+15.3% from last month</small> -->
            </div>
          </div>
        </div>
      </div>

      <!-- Fourth box -->
      <div class="col-xl-4 col-md-6 mb-3">
        <div class="card card-default bg-info shadow-sm border-0">
          <div class="d-flex align-items-center p-4 justify-content-between">
            <div class="icon-md bg-white rounded-circle d-flex align-items-center justify-content-center" style="width:50px; height:50px;">
              <i class="mdi mdi-bell text-info" style="font-size:24px;"></i>
            </div>
            <div class="text-right">
              <p class="text-white mb-1 fw-semibold">Total Volunteers</p>
              <span class="h3 d-block text-white mb-0">{{ $volunteers }}</span>
              <!-- <small class="text-light opacity-75">+2 new volunteers</small> -->
            </div>
          </div>
        </div>
      </div>

      <!-- fifth box -->
      <div class="col-xl-4 col-md-6 mb-3">
        <div class="card card-default bg-info shadow-sm border-0">
          <div class="d-flex align-items-center p-4 justify-content-between">
            <div class="icon-md bg-white rounded-circle d-flex align-items-center justify-content-center" style="width:50px; height:50px;">
              <i class="mdi mdi-bell text-info" style="font-size:24px;"></i>
            </div>
            <div class="text-right">
              <p class="text-white mb-1 fw-semibold">Total Aid Programs</p>
              <span class="h3 d-block text-white mb-0">{{ $aidPrograms }}</span>
              <!-- <small class="text-light opacity-75">+2 new volunteers</small> -->
            </div>
          </div>
        </div>
      </div>

      <!-- sixth box -->
      <div class="col-xl-4 col-md-6 mb-3">
        <div class="card card-default bg-info shadow-sm border-0">
          <div class="d-flex align-items-center p-4 justify-content-between">
            <div class="icon-md bg-white rounded-circle d-flex align-items-center justify-content-center" style="width:50px; height:50px;">
              <i class="mdi mdi-bell text-info" style="font-size:24px;"></i>
            </div>
            <div class="text-right">
              <p class="text-white mb-1 fw-semibold">Active Organizations</p>
              <span class="h3 d-block text-white mb-0">{{ $activeOrganizations }}</span>
              <!-- <small class="text-light opacity-75">+2 new volunteers</small> -->
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>

@endsection
