@extends('layouts.app')

@section('title', 'Volunteer Profile')

@section('content')
<div class="wrapper">
  <div class="content-wrapper">

    @php $currentUser = auth()->user(); @endphp
    <div class="d-flex justify-content-between align-items-center mb-1">
      <div>
        <h4 class="fw-bold text-dark mb-1">Volunteer Profile</h4>
        <p class="text-muted fs-5">Profile and details</p>
      </div>
      <div>
        <a href="{{ route('cms.volunteer.index') }}" class="btn btn-outline-secondary btn-sm">Back</a>
      </div>
    </div>

    <hr class="mt-2 mb-3">

    <div class="row">

      <div class="col-md-4 mb-3">
        <div class="card card-default">
          <div class="card-body text-center">
            @php
              $defaultAvatar = asset('assets/images/user/user-md-01.jpg');
              $avatar = $defaultAvatar;
              if (!empty($volunteer->picture_path)) {
                $publicPath = public_path(trim($volunteer->picture_path, '/'));
                if (file_exists($publicPath)) {
                  $avatar = asset($volunteer->picture_path);
                }
              }
              $isActive = ($volunteer->status ?? 'Active') === 'Active';
            @endphp
            <img class="rounded-circle mb-3" src="{{ $avatar }}" alt="Profile" style="width:96px;height:96px;object-fit:cover;">
            <h5 class="mb-1">{{ $volunteer->full_name }}</h5>
            <div class="mb-2 text-muted">{{ $volunteer->email }}</div>
            <div class="mb-2">{{ $volunteer->contact_number }}</div>
            <span class="badge {{ $isActive ? 'badge-success' : 'badge-light' }}">{{ $isActive ? 'Active' : 'Inactive' }}</span>
          </div>
        </div>

        @if($currentUser)
        <div class="card card-default mt-3">
          <div class="card-body">
            <div class="d-grid gap-2">
              @if($currentUser->hasPermission('volunteers_edit'))
                <a href="{{ route('cms.volunteer.edit', $volunteer->id) }}" class="btn btn-primary btn-sm w-100 mb-3">Edit</a>
              @endif
              @if($currentUser->hasPermission('volunteers_delete'))
              <form action="{{ route('cms.volunteer.destroy', $volunteer->id) }}" method="POST" onsubmit="return confirm('Delete this volunteer?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger btn-sm w-100">Delete</button>
              </form>
              @endif
            </div>
          </div>
        </div>
        @endif
      </div>

      <div class="col-md-8 mb-3">
        <div class="card card-default">
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-striped table-bordered">
                <tbody>
                  <tr>
                    <th style="width:30%">Volunteer Code</th>
                    <td>{{ $volunteer->volunteer_code ?? '—' }}</td>
                  </tr>
                  <tr>
                    <th>CNIC / ID</th>
                    <td>{{ $volunteer->cnic ?? '—' }}</td>
                  </tr>
                  <tr>
                    <th>Gender</th>
                    <td>{{ $volunteer->gender ?? '—' }}</td>
                  </tr>
                  <tr>
                    <th>Date of Birth</th>
                    <td>{{ optional($volunteer->dob)->format('Y-m-d') ?? '—' }}</td>
                  </tr>
                  <tr>
                    <th>Address</th>
                    <td>{{ $volunteer->address ?? '—' }}</td>
                  </tr>
                  <tr>
                    <th>Education Level</th>
                    <td>{{ $volunteer->education_level ?? '—' }}</td>
                  </tr>
                  <tr>
                    <th>Skills</th>
                    <td>{{ $volunteer->skills ?? '—' }}</td>
                  </tr>
                  <tr>
                    <th>Languages</th>
                    <td>{{ $volunteer->languages ?? '—' }}</td>
                  </tr>
                  <tr>
                    <th>Availability</th>
                    <td>{{ $volunteer->availability ?? '—' }}</td>
                  </tr>
                  <tr>
                    <th>Preferred Projects</th>
                    <td>
                      @php $pp = collect($volunteer->preferred_projects ?? []); @endphp
                      {{ $pp->isEmpty() ? '—' : $pp->join(', ') }}
                    </td>
                  </tr>
                  <tr>
                    <th>Emergency Contact</th>
                    <td>{{ $volunteer->emergency_contact ?? '—' }}</td>
                  </tr>
                  <tr>
                    <th>Role</th>
                    <td>{{ $volunteer->role ?? '—' }}</td>
                  </tr>
                  <tr>
                    <th>Joining Date</th>
                    <td>{{ optional($volunteer->joining_date)->format('Y-m-d') ?? '—' }}</td>
                  </tr>
                  <tr>
                    <th>Remarks</th>
                    <td>{{ $volunteer->remarks ?? '—' }}</td>
                  </tr>
                  <tr class="align-middle">
                    <th class="py-3">Last Updated</th>
                    <td class="py-3">{{ optional($volunteer->updated_at)->format('Y-m-d H:i') }}</td>
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

