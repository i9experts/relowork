@extends('layouts.app')

@section('title', 'Volunteer Management List')

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
            <h4 class="fw-bold text-dark mb-1">Volunteer Management</h4>
            <p class="text-muted fs-5">Manage volunteers and track their contributions</p>
        </div>

        <div>
            <a href="{{ route('cms.volunteer.create') }}" type="button" class="btn btn-pill btn-primary rounded-pill px-3 d-flex align-items-center shadow-sm">
                <i class="mdi mdi-plus-circle-outline mr-1"></i> Add New Volunteer
            </a>
        </div>
    </div>

    <hr class="mt-2 mb-2">

    <!-- Top Statistics -->
    <div class="row">
        <!-- Total Volunteers -->
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card shadow-sm border-0 rounded-4">
            <div class="d-flex justify-content-between align-items-center p-4">
                <div class="text-left">
                <span class="h5 d-block mb-0">Total Volunteers</span>
                <p class="mb-1">5</p>
                <small class="text-muted">+3 new this month</small>
                </div>
                <div class="bg-info rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:50px; height:50px;">
                <i class="mdi mdi-account-multiple text-white fs-4" style="font-size:26px;"></i> <!-- Volunteer Group -->
                </div>
            </div>
            </div>
        </div>

        <!-- Active This Month -->
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card shadow-sm border-0 rounded-4">
            <div class="d-flex justify-content-between align-items-center p-4">
                <div class="text-left">
                <span class="h5 d-block mb-0">Active This Month</span>
                <p class="mb-1">5</p>
                <small class="text-muted">100% participation</small>
                </div>
                <div class="bg-success rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:50px; height:50px;">
                <i class="mdi mdi-check-circle-outline text-white fs-4" style="font-size:26px;"></i> <!-- Active -->
                </div>
            </div>
            </div>
        </div>

        <!-- Total Hours -->
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card shadow-sm border-0 rounded-4">
            <div class="d-flex justify-content-between align-items-center p-4">
                <div class="text-left">
                <span class="h5 d-block mb-0">Total Hours</span>
                <p class="mb-1">178</p>
                <small class="text-muted">This month</small>
                </div>
                <div class="bg-warning rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:50px; height:50px;">
                <i class="mdi mdi-clock-outline text-white" style="font-size:26px;"></i> <!-- Time -->
                </div>
            </div>
            </div>
        </div>

        <!-- Average Hours per Volunteer -->
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card shadow-sm border-0 rounded-4">
            <div class="d-flex justify-content-between align-items-center p-4">
                <div class="text-left">
                <span class="h5 d-block mb-0">Avg Hours/Volunteer</span>
                <p class="mb-1">35.6</p>
                <small class="text-muted">Per volunteer</small>
                </div>
                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:50px; height:50px;">
                <i class="mdi mdi-chart-line text-white fs-4" style="font-size:26px;"></i> <!-- Analytics -->
                </div>
            </div>
            </div>
        </div>
    </div>

    <div class="row">

        <div class="col-6">
            <div class="card">
                <div class="card-body">                   
                    <h4 class="card-title fw-bold mb-2">Top Volunteers This Month</h4>
                    <div class="table-responsive m-t-20">
                        <table id="data_table"
                            class="table table-striped table-bordered nowrap w-100"
                            cellspacing="0" width="100%">
                            <tbody id="tbl_data">
                                <tr>
                                    <td>
                                        <div class="d-flex justify-content-between">
                                            <span class="fw-semibold text-dark">Ahmed Mohammed</span>
                                            <small class="text-muted">45 hours</small>
                                        </div>
                                        <div>
                                            <small class="text-primary">850 points • Gold</small>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6">
            <div class="card">
                <div class="card-body">                   
                    <h4 class="card-title fw-bold mb-2">Recent Activity</h4>
                    <div class="table-responsive m-t-20">
                        <table id="data_table"
                            class="table table-striped table-bordered nowrap w-100"
                            cellspacing="0" width="100%">
                            <tbody id="tbl_data">
                                <tr>
                                    <td>
                                        <div class="d-flex justify-content-between">
                                            <span class="fw-semibold text-dark">Ahmed Mohammed</span>
                                            <small class="text-muted">4h</small>
                                        </div>
                                        <div>
                                            <small class="text-primary">Food distribution - District 12 • 2024-10-13</small>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
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
