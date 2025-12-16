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
                              <button type="button" class="dropdown-item btn-edit-lookup" data-toggle="modal" data-target="#editLookupModal" data-type="education_level" data-id="{{ $item->id }}" data-name="{{ $item->name }}">Edit</button>
                              <form action="{{ route('cms.volunteer-settings.education-level.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Are you sure to delete?');">
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
                              <button type="button" class="dropdown-item btn-edit-lookup" data-toggle="modal" data-target="#editLookupModal" data-type="availability" data-id="{{ $item->id }}" data-name="{{ $item->name }}">Edit</button>
                              <form action="{{ route('cms.volunteer-settings.availability.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Delete this?');">
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
                              <button type="button" class="dropdown-item btn-edit-lookup" data-toggle="modal" data-target="#editLookupModal" data-type="project_type" data-id="{{ $item->id }}" data-name="{{ $item->name }}">Edit</button>
                              <form action="{{ route('cms.volunteer-settings.project-type.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Delete this?');">
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
                              <button type="button" class="dropdown-item btn-edit-lookup" data-toggle="modal" data-target="#editLookupModal" data-type="volunteer_role" data-id="{{ $item->id }}" data-name="{{ $item->name }}">Edit</button>
                              <form action="{{ route('cms.volunteer-settings.volunteer-role.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Delete this?');">
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
<div class="modal fade" id="editLookupModal" tabindex="-1" role="dialog" aria-labelledby="editLookupModalTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editLookupModalTitle">Edit</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <form method="POST" id="editLookupForm">
        @csrf
        @method('PATCH')
        <div class="modal-body">
          <div class="form-group">
            <label id="lookupNameLabel">Name</label>
            <input type="text" class="form-control rounded-0 bg-light" name="name" id="lookupNameInput" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger btn-pill" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary btn-pill">Save Changes</button>
        </div>
      </form>
    </div>
  </div>
</div>
<script>
  (function () {
    var form = document.getElementById('editLookupForm');
    var titleEl = document.getElementById('editLookupModalTitle');
    var inputEl = document.getElementById('lookupNameInput');
    var labelEl = document.getElementById('lookupNameLabel');
    var routes = {
      education_level: "{{ route('cms.volunteer-settings.education-level.update', '__ID__') }}",
      availability: "{{ route('cms.volunteer-settings.availability.update', '__ID__') }}",
      project_type: "{{ route('cms.volunteer-settings.project-type.update', '__ID__') }}",
      volunteer_role: "{{ route('cms.volunteer-settings.volunteer-role.update', '__ID__') }}",
    };
    var labels = {
      education_level: "Education Level",
      availability: "Availability",
      project_type: "Project Type",
      volunteer_role: "Volunteer Role"
    };
    document.querySelectorAll('.btn-edit-lookup').forEach(function (btn) {
      btn.addEventListener('click', function () {
        var type = btn.getAttribute('data-type');
        var id = btn.getAttribute('data-id');
        var name = btn.getAttribute('data-name');
        var label = labels[type] || 'Name';
        titleEl.textContent = 'Edit ' + label;
        labelEl.textContent = label;
        inputEl.value = name || '';
        var action = (routes[type] || '').replace('__ID__', id);
        form.setAttribute('action', action);
      });
    });
  })();
</script>
@endsection
