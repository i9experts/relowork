@extends('layouts.app')

@section('title', 'Aid Program List')

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
    <div class="d-flex justify-content-between align-items-center mb-1">
        <div>
            <h4 class="fw-bold text-dark mb-1">Aid Programs</h4>
            <p class="text-muted fs-5">Manage and track all aid distribution programs</p>
        </div>

        <div>
            <button type="button" class="btn btn-pill btn-primary rounded-pill px-3 d-flex align-items-center shadow-sm">
                <i class="mdi mdi-plus-circle-outline mr-1"></i> Create New Program
            </button>
        </div>
    </div>

    <hr class="mt-2 mb-2">

    <!-- Top Statistics -->
    <div class="row">
        <!-- First box -->
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card card-default shadow-sm border-0 rounded-4">
                <div class="d-flex justify-content-between align-items-center p-4">
                    <div class="text-left">
                        <span class="h5 d-block mb-0">Active prog</span>
                        <p class="mb-1">3</p>
                        <small class="text-muted">Currently running</small>
                    </div>
                    <div class="icon-md bg-info rounded-circle d-flex align-items-center justify-content-center" style="width:50px; height:50px;">
                        <i class="mdi mdi-run text-white" style="font-size:26px;"></i> <!-- Active Program -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Second box -->
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card card-default shadow-sm border-0 rounded-4">
                <div class="d-flex justify-content-between align-items-center p-4">
                    <div class="text-left">
                        <span class="h5 d-block mb-0">Total Budget</span>
                        <p class="mb-1">670,000</p>
                        <small class="text-muted">Active programs</small>
                    </div>
                    <div class="icon-md bg-success rounded-circle d-flex align-items-center justify-content-center" style="width:50px; height:50px;">
                        <i class="mdi mdi-cash-multiple text-white" style="font-size:26px;"></i> <!-- Total Budget -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Third box -->
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card card-default shadow-sm border-0 rounded-4">
                <div class="d-flex justify-content-between align-items-center p-4">
                    <div class="text-left">
                        <span class="h5 d-block mb-0">Distributed</span>
                        <p class="mb-1">500,000</p>
                        <small class="text-muted">74.6% of budget</small>
                    </div>
                    <div class="icon-md bg-warning rounded-circle d-flex align-items-center justify-content-center" style="width:50px; height:50px;">
                        <i class="mdi mdi-send text-white" style="font-size:26px;"></i> <!-- Distributed -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Fourth box -->
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card card-default shadow-sm border-0 rounded-4">
                <div class="d-flex justify-content-between align-items-center p-4">
                    <div class="text-left">
                        <span class="h5 d-block mb-0">Beneficiaries</span>
                        <p class="mb-1">772</p>
                        <small class="text-muted">Total recipients</small>
                    </div>
                    <div class="icon-md bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width:50px; height:50px;">
                        <i class="mdi mdi-account-group text-white" style="font-size:26px;"></i> <!-- Beneficiaries -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Buttons (Optional) -->
    <div class="row mt-0 mb-5">
        <div class="col text-center">
            <div class="btn-group" role="group" aria-label="Basic example">
            <button type="button" class="btn btn-primary btn-lg px-4 fw-semibold">Active</button>
            <button type="button" class="btn btn-primary btn-lg px-4 fw-semibold">Upcoming</button>
            <button type="button" class="btn btn-primary btn-lg px-4 fw-semibold">Completed</button>
            </div>
        </div>
    </div>

    <div class="row mb-5">
        <!-- Frist box -->
        <div class="col-lg-6 col-xl-6">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4">
                
                <!-- Title + Status -->
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="fw-bold mb-0 text-dark">Ramadan Food Baskets 2024</h5>
                    <span class="badge badge-pill badge-success">Active</span>
                </div>

                <!-- Date Range -->
                <p class="text-muted mb-3 fw-semibold">2024-03-01 — 2024-04-10</p>

                <div class="progress mb-3">
                    <div class="progress-bar" role="progressbar" style="width: 55%" aria-valuenow="25" aria-valuemin="0"
                    aria-valuemax="100">25%</div>
                </div>

                <!-- Budget and Distributed -->
                <div class="d-flex justify-content-between mb-3">
                    <div>
                    <span class="text-muted d-block">Budget</span>
                    <h6 class="mb-0 text-dark fw-bold">$250,000</h6>
                    </div>
                    <div class="text-end">
                    <span class="text-muted d-block">Distributed</span>
                    <h6 class="mb-0 text-dark fw-bold">$180,000</h6>
                    </div>
                </div>

                <!-- Beneficiaries and Remaining -->
                <div class="d-flex justify-content-between border-top pt-3">
                    <div>
                    <span class="text-muted d-block">Beneficiaries</span>
                    <h6 class="mb-0 text-dark fw-bold">342 / 500</h6>
                    </div>
                    <div class="text-end">
                    <span class="text-muted d-block">Remaining</span>
                    <h6 class="mb-0 text-dark fw-bold">$70,000</h6>
                    </div>
                </div>

                <button type="button" class="btn btn-outline-primary w-100 mt-3">View Details</button>

                </div>
            </div>
        </div>

    </div>

  </div>
</div>
@endsection

@push('page-scripts')
@endpush
