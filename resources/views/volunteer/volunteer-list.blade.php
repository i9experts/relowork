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

    @php
        $user = auth()->user();
        $canViewVolunteers = $user && $user->hasPermission('volunteers');
        $canCreateVolunteers = $user && $user->hasPermission('volunteers_create');
        $canEditVolunteers = $user && $user->hasPermission('volunteers_edit');
        $canDeleteVolunteers = $user && $user->hasPermission('volunteers_delete');
    @endphp

    <!-- Dashboard Header -->
    <div class="d-flex justify-content-between align-items-center mb-1">
        <div>
            <h4 class="fw-bold text-dark mb-1">Volunteer Management</h4>
            <p class="text-muted fs-5">Manage volunteers and track their contributions</p>
        </div>

        @if($canCreateVolunteers)
        <div>
            <a href="{{ route('cms.volunteer.create') }}" type="button" class="btn btn-pill btn-primary rounded-pill px-3 d-flex align-items-center shadow-sm">
                <i class="mdi mdi-plus-circle-outline mr-1"></i> Add New Volunteer
            </a>
        </div>
        @endif
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
                <p class="mb-1">{{ number_format($totalVolunteers) }}</p>
                <small class="text-muted">Active this month: {{ number_format($activeThisMonth) }}</small>
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
                <p class="mb-1">{{ number_format($activeThisMonth) }}</p>
                <small class="text-muted">Participation</small>
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
                <p class="mb-1">{{ number_format($totalHours) }}</p>
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
                <p class="mb-1">{{ number_format($avgHours, 1) }}</p>
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
                                @forelse($topVolunteers as $v)
                                <tr>
                                    <td>
                                        <div class="d-flex justify-content-between">
                                            <span class="fw-semibold text-dark">{{ $v->full_name }}</span>
                                            <small class="text-muted">Joined: {{ optional($v->joining_date)->format('Y-m-d') }}</small>
                                        </div>
                                        <div>
                                            <small class="text-primary">Last update • {{ optional($v->updated_at)->diffForHumans() }}</small>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr><td class="text-muted">No data</td></tr>
                                @endforelse
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
                                @forelse($recentActivity as $v)
                                <tr>
                                    <td>
        							<div class="d-flex justify-content-between">
                                            <span class="fw-semibold text-dark">{{ $v->full_name }}</span>
                                            <small class="text-muted">{{ optional($v->updated_at)->diffForHumans() }}</small>
                                        </div>
                                        <div>
                                            <small class="text-primary">Last updated • {{ optional($v->updated_at)->format('Y-m-d H:i') }}</small>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr><td class="text-muted">No recent activity</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Total Volunteers List --> 
    <div class="row mt-3">
        <div class="col-12">
            <div class="card card-default">
                <div class="card-body">
                    <table id="productsTable" class="table table-product" style="width:100%">
                        <thead>
                        <tr>
                            <th>S.no</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Contact Number</th>
                            <th>Address</th>
                            <th>Availability</th>
                            <th>Role</th>
                            <th>Language</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php $sn = 1 @endphp
                        @forelse($volunteers as $vol)
                            <tr>
                                <td>{{ $sn++ }}</td>

                                <!-- Name -->
                                <td>{{ $vol->full_name }}</td>

                                <!-- Email -->
                                <td>{{ $vol->email }}</td>

                                <!-- Contact Number -->
                                <td>{{ $vol->contact_number ?? '—' }}</td>

                                <!-- Address -->
                                <td>{{ $vol->address ?? '—' }}</td>

                                <!-- Availability -->
                                <td>{{ optional($vol->availability)->name ?? '—' }}</td>

                                <!-- Role -->
                                <td>{{ optional($vol->volunteerRole)->name ?? '—' }}</td>

                                <!-- Language (JSON array) -->
                                <td>
                                    @if($vol->language_ids)
                                        @php
                                            $languageNames = \App\Models\Language::whereIn('id', $vol->language_ids)->pluck('name')->toArray();
                                        @endphp
                                        {{ implode(', ', $languageNames) }}
                                    @else
                                        —
                                    @endif
                                </td>

                                <!-- Status -->
                                <td>
                                    @php $isActive = ($vol->status ?? 'Active') === 'Active' @endphp
                                    <span class="badge {{ $isActive ? 'badge-success' : 'badge-light' }}">
                                        {{ $isActive ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>

                                <!-- Actions -->
                                <td>
                                    <div class="dropdown">
                                    <button class="btn btn-outline-secondary dropdown-toggle btn-sm" type="button" id="dropdownMenuButton{{ $vol->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-display="static">
                                        Actions
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton{{ $vol->id }}">
                                        @if($canViewVolunteers)
                                          <a class="dropdown-item" href="{{ route('cms.volunteer.show', $vol->id) }}">View</a>
                                        @endif
                                        @if($canEditVolunteers)
                                          <a class="dropdown-item" href="{{ route('cms.volunteer.edit', $vol->id) }}">Edit</a>
                                        @endif
                                        @if($canDeleteVolunteers)
                                          <form action="{{ route('cms.volunteer.destroy', $vol->id) }}" method="POST" onsubmit="return confirm('Delete this volunteer?');">
                                              @csrf
                                              @method('DELETE')
                                              <button type="submit" class="dropdown-item text-danger">Delete</button>
                                          </form>
                                        @endif
                                    </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-muted">No volunteers found</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>

                    <div class="mt-2">{{ method_exists($volunteers,'links') ? $volunteers->links() : '' }}</div>
                </div>
            </div>
        </div>
    </div>


  </div>
</div>
@endsection

@push('page-scripts')
@endpush
