
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
  /* Make progress bar clearly visible */
  .progress { height: 8px; background-color: #eee; }
  .progress-bar { background-color: #6f42c1 !important; }
</style>
@endpush

@section('content')
<div class="wrapper">
  <div class="content-wrapper">

    @php
        $user = auth()->user();
    @endphp

    <!-- Dashboard Header -->
    <div class="d-flex justify-content-between align-items-center mb-1 flex-wrap">
        <div>
            <h4 class="fw-bold text-dark mb-1">Surveys & Assessments</h4>
            <p class="text-muted fs-5 mb-0">Manage family assessments and survey data</p>
        </div>

        @if($user && $user->hasPermission('surveys'))
        <a href="{{ route('cms.survey.create') }}" type="button" class="btn btn-pill btn-primary rounded-pill px-3 d-flex align-items-center shadow-sm">
            <i class="mdi mdi-plus-circle-outline mr-1"></i> Create New Survey
        </a>
        @endif

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
                        <p class="mb-1 fw-semibold">{{ $stats['active_surveys'] ?? 0 }}</p>
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
                        <p class="mb-1 fw-semibold">{{ $stats['total_responses'] ?? 0 }}</p>
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
                        <p class="mb-1 fw-semibold">{{ $stats['completion_rate'] ?? 0 }}%</p>
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
                        <p class="mb-1 fw-semibold">{{ $stats['pending_review'] ?? 0 }}</p>
                        <small class="text-muted">Awaiting verification</small>
                    </div>
                    <div class="icon-md bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width:50px; height:50px;">
                        <i class="mdi mdi-progress-clock text-white" style="font-size:26px;"></i> <!-- Pending Review -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    @forelse($surveys as $survey)
            <div class="row mb-4">
                <div class="col-lg-12 col-xl-12">
                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="card-body p-4">

                           <!-- Title + Status --> 
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    <h5 class="fw-bold mb-1 text-dark">{{ $survey->survey_code ?? 'Survey #'.$survey->id }}</h5>
                                    <p class="text-muted mb-0 small">
                                        @php
                                            $familyName = optional($survey->family)->family_name ?? optional($survey->family)->head_of_family_name;
                                            $familyId = optional($survey->family)->id;
                                            $enumName = optional($survey->enumerator)->full_name ?? optional($survey->enumerator)->name;
                                            $enumId = optional($survey->enumerator)->id;
                                        @endphp

                                        @if($survey->family)
                                            Family Name: {{ $familyName ?? '-' }} | Family ID: {{ $familyId ? '#'.$familyId : '-' }} <br>
                                        @endif

                                        @if($survey->enumerator)
                                            Volunteer Name: {{ $enumName ?? '-' }} | Volunteer ID: {{ $enumId ? '#'.$enumId : '-' }}
                                        @endif
                                    </p>
                                </div>

                                @if($survey->revisit_flag)
                                    <span class="badge badge-pill badge-success">Active</span>
                                @else
                                    <span class="badge badge-pill badge-light">Completed</span>
                                @endif
                            </div>

                            <!-- Dates -->
                            <div class="d-flex justify-content-between mb-3">
                                <div>
                                    <span class="text-muted d-block">
                                        Created:
                                        {{ $survey->revisit_from ? \Illuminate\Support\Carbon::parse($survey->revisit_from)->format('Y-m-d') : ($survey->created_at ? \Illuminate\Support\Carbon::parse($survey->created_at)->format('Y-m-d') : '-') }}
                                    </span>
                                </div>
                                <div class="text-end">
                                    @php
                                        $deadlineRaw = $survey->revisit_to ?: data_get($survey->flags_json, 'revisit_to');
                                    @endphp
                                    @if($deadlineRaw)
                                        <span class="text-muted d-block">Deadline: {{ \Illuminate\Support\Carbon::parse($deadlineRaw)->format('Y-m-d') }}</span>
                                    @endif
                                </div>
                            </div>

                            @php
                                $startDate = $survey->revisit_from
                                    ? \Illuminate\Support\Carbon::parse($survey->revisit_from)
                                    : ($survey->created_at ? \Illuminate\Support\Carbon::parse($survey->created_at) : null);
                                $endDate = $deadlineRaw ? \Illuminate\Support\Carbon::parse($deadlineRaw) : null;
                                $progress = 0; $elapsed = 0; $displayWidth = 0;
                                if ($startDate && $endDate && $endDate->greaterThan($startDate)) {
                                    $total = $endDate->diffInSeconds($startDate);
                                    $nowTs = now();
                                    // Clamp 'now' in [start, end]
                                    if ($nowTs->lessThan($startDate)) {
                                        $elapsed = 0;
                                    } elseif ($nowTs->greaterThanOrEqualTo($endDate)) {
                                        $elapsed = $total;
                                    } else {
                                        $elapsed = $nowTs->diffInSeconds($startDate);
                                    }
                                    $progress = $total > 0 ? round(($elapsed / $total) * 100) : 0;
                                    $progress = max(0, min(100, $progress));
                                    // Ensure at least 1% visible while in window and progress between 0 and 100
                                    $displayWidth = ($elapsed > 0 && $elapsed < $total) ? max(1, $progress) : $progress;
                                }
                            @endphp

                            @if($startDate && $endDate)
                                <div class="progress mb-3">
                                    <div class="progress-bar" role="progressbar" style="width: {{ $displayWidth }}%" aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100">{{ $progress }}%</div>
                                </div>
                            @endif

                            <!-- Counts -->
                            <div class="d-flex justify-content-between border-top pt-3 mb-3">
                                <div>
                                    <span class="text-muted d-block">Members: {{ $survey->members_count ?? 0 }}</span>
                                </div>
                                <div class="text-end">
                                    <span class="text-muted d-block">Uploads: {{ $survey->uploads_count ?? 0 }}</span>
                                </div>
                            </div>

                            @if($user && $user->hasPermission('surveys'))
                            <div class="d-flex justify-content-between mt-3">
                                <a href="{{ route('cms.survey.show', $survey->id) }}" class="btn btn-outline-primary w-50 mr-2">View Details</a>
                                <a href="{{ route('cms.survey.edit', $survey->id) }}" class="btn btn-outline-primary w-50">Edit</a>
                            </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
    @empty
        <div class="alert alert-light border">No surveys found.</div>
    @endforelse

    @if(isset($surveys) && method_exists($surveys, 'links'))
        <div class="d-flex justify-content-center mt-2">
            {{ $surveys->links() }}
        </div>
    @endif


  </div>
</div>
@endsection

@push('page-scripts')
@endpush
