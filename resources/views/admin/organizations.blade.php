@extends('layouts.app')

@section('title', 'Organizations')

@section('content')
<div class="wrapper">
  <div class="content-wrapper">
    <div class="row mb-3">
      <div class="col-12">
        <h4 class="mb-2">Organizations</h4>
        <p>Overview of all organizations with core metrics.</p>
      </div>
    </div>

    <div class="card">
      <div class="card-body">
        <div class="table-responsive">
          <table class="table" id="adminOrganizationsTable">
            <thead>
              <tr>
                <th>S. No</th>
                <th>Name</th>
                <th>Email</th>
                <th>Domain</th>
                <th>Billing Status</th>
                <th>Plan Expires</th>
                <th>Families</th>
                <th>Volunteers</th>
                <th>Aid Programs</th>
                <th>Surveys</th>
                <th>Donations</th>
                <th>Expenses</th>
                <th>Invoice</th>
              </tr>
            </thead>
            <tbody>
              @foreach($rows as $r)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $r['name'] }}</td>
                  <td>{{ $r['email'] }}</td>
                  <td>{{ $r['domain'] ?? '-' }}</td>
                  <td>
                    @php $bs = $r['billing_status'] ?? 'active'; @endphp
                    <span class="badge {{ $bs==='expired' ? 'badge-dark' : ($bs==='expiring_1' ? 'badge-danger' : ($bs==='expiring_10' ? 'badge-warning' : 'badge-success')) }}">
                      {{ ucfirst(str_replace('_', ' ', $bs)) }}
                    </span>
                  </td>
                  <td>{{ $r['subscription_expires_at'] ?? '—' }}</td>
                  <td>{{ $r['families'] }}</td>
                  <td>{{ $r['volunteers'] }}</td>
                  <td>{{ $r['aid_programs'] }}</td>
                  <td>{{ $r['surveys'] }}</td>
                  <td>{{ number_format($r['donations']) }}</td>
                  <td>{{ number_format($r['expenses']) }}</td>
                  <td>
                    <a href="{{ route('cms.admin.organizations.invoice', $r['id']) }}" class="btn btn-sm btn-outline-primary">View</a>
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
