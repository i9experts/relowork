@extends('layouts.app')

@section('title', 'Dashboard')

@push('page-styles')
<style>
  /* Make all cards in the same row equal height */
  .row > [class*='col-'] {
    display: flex;
  }
  .card {
    flex: 1 1 auto;
  }
  /* Subtitle style */
  .card-header p.small {
    font-size: 0.85rem;
    color: #6c757d;
    margin-top: 4px;
  }
</style>
@endpush

@section('content')
<div class="wrapper">
  <div class="content-wrapper">

    <!-- Dashboard Header -->
    <div class="mb-1">
      <h4 class="fw-bold text-dark mb-1">Admin Dashboard</h4>
      <p class="text-muted fs-5">Welcome back, Administrator </p>
      <hr class="mt-2 mb-2">
    </div>

    <!-- Top Statistics -->
    <div class="row">
      <!-- First box -->
      <div class="col-xl-3 col-md-6 mb-3">
        <div class="card card-default bg-secondary shadow-sm border-0">
          <div class="d-flex align-items-center p-4 justify-content-between">
            <div class="icon-md bg-white rounded-circle d-flex align-items-center justify-content-center" style="width:50px; height:50px;">
              <i class="mdi mdi-account-plus-outline text-secondary" style="font-size:24px;"></i>
            </div>
            <div class="text-right">
              <p class="text-white mb-1 fw-semibold">Total Families</p>
              <span class="h3 d-block text-white mb-0">476</span>
              <small class="text-light opacity-75">+12.5% from last month</small>
            </div>
          </div>
        </div>
      </div>

      <!-- Second box -->
      <div class="col-xl-3 col-md-6 mb-3">
        <div class="card card-default bg-success shadow-sm border-0">
          <div class="d-flex align-items-center p-4 justify-content-between">
            <div class="icon-md bg-white rounded-circle d-flex align-items-center justify-content-center" style="width:50px; height:50px;">
              <i class="mdi mdi-table-edit text-success" style="font-size:24px;"></i>
            </div>
            <div class="text-right">
              <p class="text-white mb-1 fw-semibold">Active Aid Recipients</p>
              <span class="h3 d-block text-white mb-0">342</span>
              <small class="text-light opacity-75">+8.2% from last month</small>
            </div>
          </div>
        </div>
      </div>

      <!-- Third box -->
      <div class="col-xl-3 col-md-6 mb-3">
        <div class="card card-default bg-primary shadow-sm border-0">
          <div class="d-flex align-items-center p-4 justify-content-between">
            <div class="icon-md bg-white rounded-circle d-flex align-items-center justify-content-center" style="width:50px; height:50px;">
              <i class="mdi mdi-content-save-edit-outline text-primary" style="font-size:24px;"></i>
            </div>
            <div class="text-right">
              <p class="text-white mb-1 fw-semibold">Total Distributed</p>
              <span class="h3 d-block text-white mb-0">$67,500</span>
              <small class="text-light opacity-75">+15.3% from last month</small>
            </div>
          </div>
        </div>
      </div>

      <!-- Fourth box -->
      <div class="col-xl-3 col-md-6 mb-3">
        <div class="card card-default bg-info shadow-sm border-0">
          <div class="d-flex align-items-center p-4 justify-content-between">
            <div class="icon-md bg-white rounded-circle d-flex align-items-center justify-content-center" style="width:50px; height:50px;">
              <i class="mdi mdi-bell text-info" style="font-size:24px;"></i>
            </div>
            <div class="text-right">
              <p class="text-white mb-1 fw-semibold">Active Volunteers</p>
              <span class="h3 d-block text-white mb-0">28</span>
              <small class="text-light opacity-75">+2 new volunteers</small>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Income And Expenses row -->
    <div class="row">
      <div class="col-xl-8">
        <!-- Income and Expenses -->
        <div class="card card-default">
          <div class="card-header">
            <h2 class="mb-0">Monthly Aid Distribution</h2>
            <p class="small">Aid distribution trends over the last 6 months</p>
          </div>
          <div class="card-body">
            <div class="chart-wrapper">
              <div id="mixed-chart-1"></div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-xl-4">
        <!-- Pie Chart -->
        <div class="card card-default">
          <div class="card-header">
            <h2 class="mb-0">Family Status Overview</h2>
            <p class="small">Current status distribution</p>
          </div>
          <div class="card-body p-xl-7">
            <div id="simple-pie-chart" class="d-flex justify-content-center"></div>
          </div>
        </div>
      </div>
    </div>

    <!-- Activity and Quick Actions -->
    <div class="row">
      <div class="col-xl-6">
        <!-- Recent Activity -->
        <div class="card card-default">
          <div class="card-header align-items-center d-flex justify-content-between">
            <h2 class="mb-0">Recent Activity</h2>
            <a href="#" class="btn btn-link btn-pill" data-toggle="modal" data-target="#modal-stock">View All</a>
          </div>

          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-3">
              <div>
                <h6 class="mb-1 fw-semibold">New Family Registration</h6>
                <p class="mb-1 text-muted small">Al-Ahmad Family</p>
                <span class="text-secondary small">5 min ago</span>
              </div>
              <span class="badge badge-warning">Pending</span>
            </div>

            <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-3">
              <div>
                <h6 class="mb-1 fw-semibold">Aid Distribution Approved</h6>
                <p class="mb-1 text-muted small">Al-Hassan Family</p>
                <span class="text-secondary small">15 min ago</span>
              </div>
              <span class="badge badge-success">Approved</span>
            </div>
          </div>
        </div>
      </div>

      <div class="col-xl-6">
        <!-- Quick Actions -->
        <div class="card card-default">
          <div class="card-header">
            <h2 class="mb-0">Quick Actions</h2>
            <p class="small">Frequently used actions</p>
          </div>

          <div class="card-body">
            <div class="d-grid gap-2">
              <button type="button" class="btn btn-outline-primary btn-pill btn-block d-flex align-items-center justify-content-center">
                 Add New Family
              </button>

              <button type="button" class="btn btn-outline-primary btn-pill btn-block d-flex align-items-center justify-content-center">
                Approve Aid Requests
              </button>

              <button type="button" class="btn btn-outline-primary btn-pill btn-block d-flex align-items-center justify-content-center">
                Generate Reports
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>
@endsection

@push('page-scripts')
@endpush
