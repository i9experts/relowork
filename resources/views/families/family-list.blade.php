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

    @php
        $user = auth()->user();
    @endphp

    <!-- Dashboard Header -->
    <div class="d-flex justify-content-between align-items-center mb-1">
        <div>
            <h4 class="fw-bold text-dark mb-1">Family Management</h4>
            <p class="text-muted fs-5">Manage and track all registered families</p>
        </div>

        @if($user && $user->hasPermission('families'))
        <div>
            <a href="{{ route('cms.family.create') }}" class="btn btn-pill btn-primary rounded-pill px-3 d-flex align-items-center shadow-sm">
                <i class="mdi mdi-plus-circle-outline mr-1"></i> Add New Family
            </a>
        </div>
        @endif
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
                <p class="mb-1">{{ $total_families ?? 0 }}</p>
                <small class="text-muted">Total registered</small>
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
                <p class="mb-1">{{ $active_recipients ?? 0 }}</p>
                <small class="text-muted">Zakat eligible</small>
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
                <p class="mb-1">{{ $pending_reviews ?? 0 }}</p>
                <small class="text-muted">Awaiting approval</small>
                </div>
                <div class="icon-md bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width:50px; height:50px;">
                <i class="mdi mdi-timer-sand text-white" style="font-size:26px;"></i>
                </div>
            </div>
            </div>
        </div>
    </div>

     <!-- Families Table -->
     <div class="row">
        <div class="col-12">
            <div class="card card-default">
                <div class="card-body">
                    <table id="familiesTable" class="table table-hover table-product" style="width:100%">
                        <thead>
                            <tr>
                                <th>S.no</th>
                                <th>Family Code</th>
                                <th>Head of Family</th>
                                <th>Members</th>
                                <th>Location</th>
                                <th>Zakat Eligibility</th>
                                <th>Volunteer</th>
                                <th>Marital Status</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($families as $family)
                                <tr>
                                    <!-- <td>{{ $loop->iteration }}</td> -->
                                    <td>{{ $family->family_code ?? '—' }}</td>
                                    <td>{{ $family->head_of_family_name }}</td>
                                    <td>{{ $family->members_count ?? 0 }}</td>

                                    <td>
                                        @php
                                            $loc = array_filter([$family->province, $family->city, $family->area]);
                                        @endphp
                                        {{ implode(' / ', $loc) }}
                                    </td>

                                    <td>{{ $family->zakat_eligibility ? 'Eligible' : 'Not Eligible' }}</td>

                                    <td>{{ optional($family->volunteer)->name ?? '—' }}</td>
                                    <td>{{ $family->marital_status ?? '—' }}</td>
                                    <td>
                                        @if($family->deleted_at)
                                            <span class="badge badge-danger">Deleted</span>
                                        @else
                                            <span class="badge badge-success">Active</span>
                                        @endif
                                    </td>


                                    <td>
                                        @if(auth()->check() && auth()->user()->hasPermission('families'))
                                            <div class="dropdown">
                                                <button class="btn btn-outline-secondary dropdown-toggle btn-sm" type="button"
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    Actions
                                                </button>

                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item"
                                                        href="{{ route('cms.family.edit', $family->id) }}">Edit</a>

                                                    <form action="{{ route('cms.family.destroy', $family->id) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Delete this Family?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger">
                                                            Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center text-muted">No families found.</td>
                                </tr>
                            @endforeach
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
