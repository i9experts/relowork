@extends('layouts.app')

@section('content')
<div class="wrapper">
  <div class="content-wrapper">
    <div class="row mb-3">
      <div class="col-12">
        <h4 class="mb-2">Users</h4>
        <p>All users grouped by organization.</p>
      </div>
    </div>

    <div class="card">
      <div class="card-body">
        <div class="table-responsive">
          <table class="table" id="adminUsersTable">
            <thead>
              <tr>
                <th>S. No</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Organization</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach($rows as $r)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $r->name }}</td>
                  <td>{{ $r->email }}</td>
                  <td>{{ ($r->is_system_admin ?? 0) ? 'Super Admin' : (($r->is_admin ?? 0) ? 'Admin' : 'User') }}</td>
                  <td>{{ $r->org_name ?? '-' }}</td>
                  <td>
                    @if($r->org_id)
                    <a href="{{ route('cms.admin.organizations.invoice', $r->org_id) }}" class="btn btn-sm btn-outline-secondary">Org</a>
                    @endif
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
