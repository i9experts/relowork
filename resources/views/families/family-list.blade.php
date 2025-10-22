@extends('layouts.app')

@section('title', 'family List')

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
            <h4 class="fw-bold text-dark mb-1">Family Management</h4>
            <p class="text-muted fs-5">Manage and track all registered families</p>
        </div>

        <div>
            <button type="button" class="btn btn-pill btn-primary rounded-pill px-3 d-flex align-items-center shadow-sm">
                <i class="mdi mdi-plus-circle-outline mr-1"></i> Add New Family
            </button>
        </div>
    </div>

    <hr class="mt-2 mb-2">

    <!-- Top Statistics -->
    <div class="row">
        <!-- First box -->
        <div class="col-xl-4 col-md-6 mb-3">
            <div class="card card-default shadow-sm border-0 rounded-4">
            <div class="d-flex justify-content-between align-items-center p-4">
                <div class="text-left">
                <span class="h5 d-block mb-0">Total Families</span>
                <p class="mb-1">476</p>
                <small class="text-muted">+12 from last month</small>
                </div>
                <div class="icon-md bg-secondary rounded-circle d-flex align-items-center justify-content-center" style="width:50px; height:50px;">
                <i class="mdi mdi-account-plus-outline text-white" style="font-size:26px;"></i>
                </div>
            </div>
            </div>
        </div>

        <!-- Second box -->
        <div class="col-xl-4 col-md-6 mb-3">
            <div class="card card-default shadow-sm border-0 rounded-4">
            <div class="d-flex justify-content-between align-items-center p-4">
                <div class="text-left">
                <span class="h5 d-block mb-0">Active Recipients</span>
                <p class="mb-1">342</p>
                <small class="text-muted">71.8% of total</small>
                </div>
                <div class="icon-md bg-success rounded-circle d-flex align-items-center justify-content-center" style="width:50px; height:50px;">
                <i class="mdi mdi-content-save-edit-outline text-white" style="font-size:26px;"></i>
                </div>
            </div>
            </div>
        </div>

        <!-- Third box -->
        <div class="col-xl-4 col-md-6 mb-3">
            <div class="card card-default shadow-sm border-0 rounded-4">
            <div class="d-flex justify-content-between align-items-center p-4">
                <div class="text-left">
                <span class="h5 d-block mb-0">Pending Reviews</span>
                <p class="mb-1">89</p>
                <small class="text-muted">Awaiting approval</small>
                </div>
                <div class="icon-md bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width:50px; height:50px;">
                <i class="mdi mdi-timer-sand text-white" style="font-size:26px;"></i>
                </div>
            </div>
            </div>
        </div>
    </div>

    <!-- Table Product -->
    <div class="row">
        <div class="col-12">
        <div class="card card-default">
            <div class="card-body">
            <table id="productsTable" class="table table-hover table-product" style="width:100%">
                <thead>
                <tr>
                    <th>S.no</th>
                    <th>Family Name</th>
                    <th>Head of Family	</th>
                    <th>Members</th>
                    <th>Location</th>
                    <th>Status</th>
                    <th>Last Visit</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>

                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>

                </tr>



                </tbody>
            </table>

            </div>
        </div>
        </div>
    </div>

  </div>
</div>
@endsection

@push('page-scripts')
@endpush
