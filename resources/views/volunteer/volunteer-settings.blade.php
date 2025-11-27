@extends('layouts.app')

@section('title', 'Volunteer Settings')

@section('content')
<div class="wrapper">
  <div class="content-wrapper">

    <div class="row">
      <div class="col-12">
        <div class="card mb-3">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <div>
                <h4 class="card-title fw-bold mb-1">Volunteer Settings</h4>
                <p class="text-muted mb-0 small">Manage lookup values used in the Volunteer Registration form.</p>
              </div>
              <div>
                <a href="{{ route('cms.volunteer.create') }}" class="btn btn-sm btn-outline-secondary">
                  Back to Volunteer Form
                </a>
              </div>
            </div>

            @include('partials.errors')
            @if(session('success'))
              <div class="alert alert-success py-1 px-2 small mb-0 mt-2">{{ session('success') }}</div>
            @endif
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <!-- LEFT: Forms (col-4) -->
      <div class="col-md-4">
        <div class="card mb-3">
          <div class="card-header py-2">
            <h6 class="mb-0 fw-bold">Add / Manage Lookups</h6>
          </div>
          <div class="card-body p-3">

            <!-- Education Level Form -->
            <div class="col-md-12 mb-3">
              <h6 class="mb-1 small text-uppercase text-muted">Education Levels</h6>
              <form method="POST" action="{{ route('cms.volunteer-settings.education-level.store') }}">
                @csrf
                <div class="input-group input-group-sm">
                  <input type="text" name="name" class="form-control rounded-0 bg-light">
                  <div class="input-group-append">
                    <button class="btn btn-secondary btn-sm" type="submit">Add</button>
                  </div>
                </div>
              </form>
            </div>

            <!-- Availability Form -->
            <div class="col-md-12 mb-3">
              <h6 class="mb-1 small text-uppercase text-muted">Availability</h6>
              <form method="POST" action="{{ route('cms.volunteer-settings.availability.store') }}">
                @csrf
                <div class="input-group input-group-sm">
                  <input type="text" name="name" class="form-control rounded-0 bg-light">
                  <div class="input-group-append">
                    <button class="btn btn-secondary btn-sm" type="submit">Add</button>
                  </div>
                </div>
              </form>
            </div>

            <!-- Project Type Form -->
            <div class="col-md-12 mb-3">
              <h6 class="mb-1 small text-uppercase text-muted">Project Types</h6>
              <form method="POST" action="{{ route('cms.volunteer-settings.project-type.store') }}">
                @csrf
                <div class="input-group input-group-sm">
                  <input type="text" name="name" class="form-control rounded-0 bg-light">
                  <div class="input-group-append">
                    <button class="btn btn-secondary btn-sm" type="submit">Add</button>
                  </div>
                </div>
              </form>
            </div>

            <!-- Volunteer Role Form -->
            <div class="col-md-12 mb-3">
              <h6 class="mb-1 small text-uppercase text-muted">Volunteer Roles / Designations</h6>
              <form method="POST" action="{{ route('cms.volunteer-settings.volunteer-role.store') }}">
                @csrf
                <div class="input-group input-group-sm">
                  <input type="text" name="name" class="form-control rounded-0 bg-light">
                  <div class="input-group-append">
                    <button class="btn btn-secondary btn-sm" type="submit">Add</button>
                  </div>
                </div>
              </form>
            </div>

            

          </div>
        </div>
      </div>

      <div class="col-md-8">
        <div class="card mb-3">
          <div class="card-header py-2">
            <h6 class="mb-0 fw-bold">Current Values</h6>
          </div>
          <div class="card-body p-3">
            <div class="row">

              {{-- Education Levels --}}
              <div class="col-md-12 mb-3">
                <div class="table-responsive">
                  <table class="table table-striped table-bordered table-sm">
                    <thead>
                      <tr>
                        <th style="width: 60px;">S.no</th>
                        <th>Education Levels</th>
                        <th style="width: 100px;">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                    @php $sn = 1; @endphp
                    @forelse($educationLevels as $item)
                      <tr>
                        <td>{{ $sn++ }}</td>
                        <td>{{ $item->name }}</td>
                        <td>
                          <div class="dropdown">
                            <button class="btn btn-outline-secondary dropdown-toggle btn-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown">
                              Actions
                            </button>
                            <div class="dropdown-menu">
                              <a class="dropdown-item" href="#">Edit</a>
                              <form action="#" method="POST" onsubmit="return confirm('Are you sure to delete?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="dropdown-item text-danger">Delete</button>
                              </form>
                            </div>
                          </div>
                        </td>
                      </tr>
                    @empty
                      <tr>
                        <td colspan="3" class="text-center text-muted">No data found.</td>
                      </tr>
                    @endforelse
                    </tbody>
                  </table>
                </div>
              </div>

              {{-- Availability --}}
              <div class="col-md-12 mb-3">
                <div class="table-responsive">
                  <table class="table table-striped table-bordered table-sm">
                    <thead>
                      <tr>
                        <th style="width: 60px;">S.no</th>
                        <th>Availability</th>
                        <th style="width: 100px;">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                    @php $sn = 1; @endphp
                    @forelse($availabilities as $item)
                      <tr>
                        <td>{{ $sn++ }}</td>
                        <td>{{ $item->name }}</td>
                        <td>
                          <div class="dropdown">
                            <button class="btn btn-outline-secondary dropdown-toggle btn-sm" type="button" data-toggle="dropdown">
                              Actions
                            </button>
                            <div class="dropdown-menu">
                              <a class="dropdown-item" href="#">Edit</a>
                              <form action="#" method="POST" onsubmit="return confirm('Delete this?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="dropdown-item text-danger">Delete</button>
                              </form>
                            </div>
                          </div>
                        </td>
                      </tr>
                    @empty
                      <tr>
                        <td colspan="3" class="text-center text-muted">No data found.</td>
                      </tr>
                    @endforelse
                    </tbody>
                  </table>
                </div>
              </div>

              {{-- Project Types --}}
              <div class="col-md-12 mb-3">
                <div class="table-responsive">
                  <table class="table table-striped table-bordered table-sm">
                    <thead>
                      <tr>
                        <th style="width: 60px;">S.no</th>
                        <th>Project Types</th>
                        <th style="width: 100px;">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                    @php $sn = 1; @endphp
                    @forelse($projectTypes as $item)
                      <tr>
                        <td>{{ $sn++ }}</td>
                        <td>{{ $item->name }}</td>
                        <td>
                          <div class="dropdown">
                            <button class="btn btn-outline-secondary dropdown-toggle btn-sm" type="button" data-toggle="dropdown">
                              Actions
                            </button>
                            <div class="dropdown-menu">
                              <a class="dropdown-item" href="#">Edit</a>
                              <form action="#" method="POST" onsubmit="return confirm('Delete this?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="dropdown-item text-danger">Delete</button>
                              </form>
                            </div>
                          </div>
                        </td>
                      </tr>
                    @empty
                      <tr>
                        <td colspan="3" class="text-center text-muted">No data found.</td>
                      </tr>
                    @endforelse
                    </tbody>
                  </table>
                </div>
              </div>

              {{-- Volunteer Roles --}}
              <div class="col-md-12 mb-3">
                <div class="table-responsive">
                  <table class="table table-striped table-bordered table-sm">
                    <thead>
                      <tr>
                        <th style="width: 60px;">S.no</th>
                        <th>Volunteer Roles</th>
                        <th style="width: 100px;">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                    @php $sn = 1; @endphp
                    @forelse($volunteerRoles as $item)
                      <tr>
                        <td>{{ $sn++ }}</td>
                        <td>{{ $item->name }}</td>
                        <td>
                          <div class="dropdown">
                            <button class="btn btn-outline-secondary dropdown-toggle btn-sm" type="button" data-toggle="dropdown">
                              Actions
                            </button>
                            <div class="dropdown-menu">
                              <a class="dropdown-item" href="#">Edit</a>
                              <form action="#" method="POST" onsubmit="return confirm('Delete this?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="dropdown-item text-danger">Delete</button>
                              </form>
                            </div>
                          </div>
                        </td>
                      </tr>
                    @empty
                      <tr>
                        <td colspan="3" class="text-center text-muted">No data found.</td>
                      </tr>
                    @endforelse
                    </tbody>
                  </table>
                </div>
              </div>
              
            </div>
          </div>
        </div>
      </div>

    </div>

  </div>
</div>
@endsection
