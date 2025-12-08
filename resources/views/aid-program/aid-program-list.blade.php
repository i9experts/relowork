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

    @php
        $user = auth()->user();
    @endphp

    <!-- Dashboard Header -->
    <div class="d-flex justify-content-between align-items-center mb-1">
        <div>
            <h4 class="fw-bold text-dark mb-1">Aid Programs</h4>
            <p class="text-muted fs-5">Manage and track all aid distribution programs</p>
        </div>

        @if($user && $user->hasPermission('aid_programs_create'))
        <div>
            <a href="{{ route('cms.aid-program.create') }}" type="button" class="btn btn-pill btn-primary rounded-pill px-3 d-flex align-items-center shadow-sm">
                <i class="mdi mdi-plus-circle-outline mr-1"></i> Create New Program
            </a>
        </div>
        @endif
    </div>

    <hr class="mt-2 mb-2">

    <!-- Top Statistics -->
    <div class="row">
        <!-- First box -->
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card card-default shadow-sm border-0 rounded-4">
                <div class="d-flex justify-content-between align-items-center p-4">
                    <div class="text-left">
                        <span class="h5 d-block mb-0">Active Program</span>
                        <p class="mb-1">{{ $active_programs ?? 0 }}</p>
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
                        <p class="mb-1">{{ number_format($total_budget_active ?? 0) }}</p>
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
                        <p class="mb-1">{{ number_format(0) }}</p>
                        <small class="text-muted">—</small>
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
                        <p class="mb-1">{{ $beneficiaries_total ?? 0 }}</p>
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
            <div class="btn-group" role="group" aria-label="Filter by status">
              @php $st = $current_status; @endphp
              <a href="{{ route('cms.aid-program.index', ['status' => 'Planned']) }}" class="btn {{ $st==='Planned' ? 'btn-primary' : 'btn-outline-primary' }} btn-lg px-4 fw-semibold">Planned</a>
              <a href="{{ route('cms.aid-program.index', ['status' => 'Active']) }}" class="btn {{ $st==='Active' ? 'btn-primary' : 'btn-outline-primary' }} btn-lg px-4 fw-semibold">Active</a>
              <a href="{{ route('cms.aid-program.index', ['status' => 'Completed']) }}" class="btn {{ $st==='Completed' ? 'btn-primary' : 'btn-outline-primary' }} btn-lg px-4 fw-semibold">Completed</a>
              <a href="{{ route('cms.aid-program.index', ['status' => 'On Hold']) }}" class="btn {{ $st==='On Hold' ? 'btn-primary' : 'btn-outline-primary' }} btn-lg px-4 fw-semibold">On Hold</a>
              <a href="{{ route('cms.aid-program.index') }}" class="btn {{ $st ? 'btn-outline-primary' : 'btn-primary' }} btn-lg px-4 fw-semibold">All</a>
            </div>
        </div>
    </div>

    <div class="row mb-5">
        @forelse($programs as $program)
        <div class="col-lg-6 col-xl-6 mb-3">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4">

                <!-- Title + Status -->
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="fw-bold mb-0 text-dark">{{ $program->program_title }}</h5>
                    @php
                      $badge = 'badge-secondary';
                      if ($program->status === 'Planned') $badge = 'badge-info';
                      elseif ($program->status === 'Active') $badge = 'badge-success';
                      elseif ($program->status === 'Completed') $badge = 'badge-primary';
                      elseif ($program->status === 'On Hold') $badge = 'badge-warning';
                    @endphp
                    <span class="badge badge-pill {{ $badge }}">{{ $program->status ?? 'Planned' }}</span>
                </div>

                <!-- Code + Date Range -->
                <p class="text-muted mb-2 fw-semibold">{{ $program->program_code }} &nbsp;·&nbsp; {{ $program->start_date ?? '—' }} — {{ $program->end_date ?? '—' }}</p>

                @php
                  $budget = (float)($program->total_budget ?? 0);
                  $disbursed = (float)($program->disbursed_amount ?? 0);
                  $pct = $budget > 0 ? max(0, min(100, (int) round(($disbursed / $budget) * 100))) : 0;
                @endphp
                <div class="progress mb-3">
                    <div class="progress-bar" role="progressbar" style="width: {{ $pct }}%" aria-valuenow="{{ $pct }}" aria-valuemin="0"
                    aria-valuemax="100">{{ $pct }}%</div>
                </div>

                <!-- Budget and Families/Volunteers -->
                <div class="d-flex justify-content-between mb-3">
                    <div>
                    <span class="text-muted d-block">Budget</span>
                    <h6 class="mb-0 text-dark fw-bold">PKR {{ number_format($program->total_budget ?? 0) }}</h6>
                    </div>
                    <div class="text-end">
                    <span class="text-muted d-block">Families / Volunteers</span>
                    <h6 class="mb-0 text-dark fw-bold">{{ $program->families_count }} / {{ $program->volunteers_count }}</h6>
                    </div>
                </div>

                <!-- Location and Attachments -->
                <div class="d-flex justify-content-between border-top pt-3">
                    <div>
                    <span class="text-muted d-block">Location</span>
                    <h6 class="mb-0 text-dark fw-bold">{{ $program->location ?? '—' }}</h6>
                    </div>
                    <div class="text-end">
                    <span class="text-muted d-block">Attachments</span>
                    <h6 class="mb-0 text-dark fw-bold">{{ $program->attachments_count }}</h6>
                    </div>
                </div>

                @if($user && $user->hasPermission('aid_programs_view'))
                <a href="{{ route('cms.aid-program.show', $program->id) }}" class="btn btn-outline-primary w-100 mt-3">View Details</a>
                @endif

                </div>
            </div>
        </div>
        @empty
          <div class="col-12">
            <div class="alert alert-light text-center border">No aid programs found.</div>
          </div>
        @endforelse
    </div>
  </div>
</div>
@endsection

@push('page-scripts')
@endpush
