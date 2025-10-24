
@extends('layouts.app')

@section('title', 'Survey List')

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
            <h4 class="fw-bold text-dark mb-1">Surveys & Assessments</h4>
            <p class="text-muted fs-5 mb-0">Manage family assessments and survey data</p>
        </div>

        <a href="{{ route('cms.survey.create') }}" type="button" class="btn btn-pill btn-primary rounded-pill px-3 d-flex align-items-center shadow-sm">
            <i class="mdi mdi-plus-circle-outline mr-1"></i> Create New Survey
        </a>

    </div>


    <hr class="mt-2 mb-2">

   <!-- Top Statistics -->
    <div class="row">
        <!-- Active Surveys -->
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card card-default shadow-sm border-0 rounded-4">
                <div class="d-flex justify-content-between align-items-center p-4">
                    <div class="text-left">
                        <span class="h5 d-block mb-0">Active Surveys</span>
                        <p class="mb-1 fw-semibold">2</p>
                        <small class="text-muted">Currently running</small>
                    </div>
                    <div class="icon-md bg-info rounded-circle d-flex align-items-center justify-content-center" style="width:50px; height:50px;">
                        <i class="mdi mdi-poll text-white" style="font-size:26px;"></i> <!-- Active Surveys -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Responses -->
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card card-default shadow-sm border-0 rounded-4">
                <div class="d-flex justify-content-between align-items-center p-4">
                    <div class="text-left">
                        <span class="h5 d-block mb-0">Total Responses</span>
                        <p class="mb-1 fw-semibold">629</p>
                        <small class="text-muted">Target: 800</small>
                    </div>
                    <div class="icon-md bg-danger rounded-circle d-flex align-items-center justify-content-center" style="width:50px; height:50px;">
                        <i class="mdi mdi-message-reply-text text-white" style="font-size:26px;"></i> <!-- Total Responses -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Completion Rate -->
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card card-default shadow-sm border-0 rounded-4">
                <div class="d-flex justify-content-between align-items-center p-4">
                    <div class="text-left">
                        <span class="h5 d-block mb-0">Completion Rate</span>
                        <p class="mb-1 fw-semibold">78.6%</p>
                        <small class="text-muted">Overall progress</small>
                    </div>
                    <div class="icon-md bg-warning rounded-circle d-flex align-items-center justify-content-center" style="width:50px; height:50px;">
                        <i class="mdi mdi-chart-line text-white" style="font-size:26px;"></i> <!-- Completion Rate -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Review -->
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card card-default shadow-sm border-0 rounded-4">
                <div class="d-flex justify-content-between align-items-center p-4">
                    <div class="text-left">
                        <span class="h5 d-block mb-0">Pending Review</span>
                        <p class="mb-1 fw-semibold">45</p>
                        <small class="text-muted">Awaiting verification</small>
                    </div>
                    <div class="icon-md bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width:50px; height:50px;">
                        <i class="mdi mdi-progress-clock text-white" style="font-size:26px;"></i> <!-- Pending Review -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-5">
        <!-- Frist box -->
        <div class="col-lg-12 col-xl-12">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4">
                
                <!-- Title + Status -->
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div>
                        <h5 class="fw-bold mb-1 text-dark">Ramadan Program Feedback</h5>
                        <p class="text-muted mb-0 small">Feedback collection for Ramadan food distribution program</p>
                    </div>
                    <span class="badge badge-pill badge-success">Active</span>
                </div>

                <!-- Budget and Distributed -->
                <div class="d-flex justify-content-between mb-3">
                    <div>
                    <span class="text-muted d-block">Created: 2024-04-15</span>
                    </div>
                    <div class="text-end">
                    <span class="text-muted d-block">Deadline: 2024-09-15</span>
                    </div>
                </div>

                <!-- Beneficiaries and Remaining -->
                <div class="d-flex justify-content-between border-top pt-3 mb-3">
                    <div>
                    <span class="text-muted d-block">Responses: 287 / 300</span>
                    </div>
                </div>
                
                <div class="progress mb-3">
                    <div class="progress-bar" role="progressbar" style="width: 55%" aria-valuenow="55" aria-valuemin="0"
                    aria-valuemax="100">55%</div>
                </div>

                <div class="d-flex justify-content-between mt-3">
                    <button type="button" class="btn btn-outline-primary w-50 mr-2">View Details</button>
                    <button type="button" class="btn btn-outline-primary w-50">Edit</button>
                </div>


                </div>
            </div>
        </div>

    </div>

    <div class="row mb-5">
        <!-- Second box -->
        <div class="col-lg-12 col-xl-12">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4">
                
                <!-- Title + Status -->
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div>
                        <h5 class="fw-bold mb-1 text-dark">Educational Support Survey</h5>
                        <p class="text-muted mb-0 small">Survey to identify families needing educational support</p>
                    </div>
                    <span class="badge badge-pill badge-light">Completed</span>
                </div>

                <!-- Budget and Distributed -->
                <div class="d-flex justify-content-between mb-3">
                    <div>
                    <span class="text-muted d-block">Created: 2024-08-01</span>
                    </div>
                    <div class="text-end">
                    <span class="text-muted d-block">Deadline: 2024-09-15</span>
                    </div>
                </div>

                <!-- Beneficiaries and Remaining -->
                <div class="d-flex justify-content-between border-top pt-3 mb-3">
                    <div>
                    <span class="text-muted d-block">Responses: 450 / 450</span>
                    </div>
                </div>
                
                <div class="progress mb-3">
                    <div class="progress-bar" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0"
                    aria-valuemax="100">100%</div>
                </div>

                <div class="d-flex justify-content-between mt-3">
                    <button type="button" class="btn btn-outline-primary w-50 mr-2">View Details</button>
                    <button type="button" class="btn btn-outline-primary w-50">Edit</button>
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
