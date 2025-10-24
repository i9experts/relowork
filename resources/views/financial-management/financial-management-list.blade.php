@extends('layouts.app')

@section('title', 'Financial Management List')

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
    <div class="d-flex justify-content-between align-items-center mb-1 flex-wrap">
        <div>
            <h4 class="fw-bold text-dark mb-1">Financial Management</h4>
            <p class="text-muted fs-5 mb-0">Track donations, expenses, and financial reports</p>
        </div>

        <!-- Select + Button side by side -->
        <div class="d-flex align-items-center" style="gap: 12px;">
            <select class="form-control rounded-0 bg-light" id="exampleFormControlSelect15" style="width: 160px;">
                <option>This Month</option>
                <option>Last Month</option>
                <option>This Quarter</option>
                <option>This Year</option>
            </select>

            <a href="{{ route('cms.financial-management.create') }}" type="button" class="btn btn-pill btn-primary rounded-pill px-3 d-flex align-items-center shadow-sm">
                <i class="mdi mdi-plus-circle-outline mr-1"></i>  Add New Transaction
            </a>

            <button type="button" class="btn btn-pill btn-primary rounded-pill px-3 d-flex align-items-center shadow-sm">
                <i class="mdi mdi-download me-1"></i> Export Report
            </button>
        </div>

    </div>


    <hr class="mt-2 mb-2">

   <!-- Top Statistics -->
    <div class="row">
        <!-- Total Income -->
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card card-default shadow-sm border-0 rounded-4">
                <div class="d-flex justify-content-between align-items-center p-4">
                    <div class="text-left">
                    <span class="h5 d-block mb-0">Total Income</span>
                    <p class="mb-1 fw-semibold">65,000</p>
                    <small class="text-muted">+15.2% from last month</small>
                    </div>
                    <div class="icon-md bg-info rounded-circle d-flex align-items-center justify-content-center" style="width:50px; height:50px;">
                    <i class="mdi mdi-trending-up text-white" style="font-size:26px;"></i> <!-- Income Icon -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Expenses -->
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card card-default shadow-sm border-0 rounded-4">
                <div class="d-flex justify-content-between align-items-center p-4">
                    <div class="text-left">
                    <span class="h5 d-block mb-0">Total Expenses</span>
                    <p class="mb-1 fw-semibold">18,000</p>
                    <small class="text-muted">+8.4% from last month</small>
                    </div>
                    <div class="icon-md bg-danger rounded-circle d-flex align-items-center justify-content-center" style="width:50px; height:50px;">
                    <i class="mdi mdi-trending-down text-white" style="font-size:26px;"></i> <!-- Expenses Icon -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Net Balance -->
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card card-default shadow-sm border-0 rounded-4">
                <div class="d-flex justify-content-between align-items-center p-4">
                    <div class="text-left">
                    <span class="h5 d-block mb-0">Net Balance</span>
                    <p class="mb-1 fw-semibold">47,000</p>
                    <small class="text-muted">72.3% margin</small>
                    </div>
                    <div class="icon-md bg-warning rounded-circle d-flex align-items-center justify-content-center" style="width:50px; height:50px;">
                    <i class="mdi mdi-wallet text-white" style="font-size:26px;"></i> <!-- Balance Icon -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Transactions -->
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card card-default shadow-sm border-0 rounded-4">
                <div class="d-flex justify-content-between align-items-center p-4">
                    <div class="text-left">
                    <span class="h5 d-block mb-0">Pending Transactions</span>
                    <p class="mb-1 fw-semibold">1</p>
                    <small class="text-muted">Awaiting approval</small>
                    </div>
                    <div class="icon-md bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width:50px; height:50px;">
                    <i class="mdi mdi-timer-sand text-white" style="font-size:26px;"></i> <!-- Pending Icon -->
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-xl-12">
            <!-- Monthly Financial Overview -->
            <div class="card card-default shadow-sm border-0 rounded-4">
                <div class="card-header bg-white border-0 pb-0">
                    <h2 class="fw-bold text-dark mb-0">Monthly Financial Overview</h2>
                    <p class="text-muted fs-6 mt-1 mb-0">Income vs Expenses over the last 6 months</p>
                </div>
                <div class="card-body">
                    <div class="chart-wrapper">
                    <div id="line-chart-4" style="height: 300px;"></div>
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
                    <th>Date</th>
                    <th>Description</th>
                    <th>Type</th>
                    <th>Amount</th>
                    <th>Status</th>
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
