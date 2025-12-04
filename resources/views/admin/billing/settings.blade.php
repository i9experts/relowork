@extends('layouts.app')
@section('title', 'Billing Settings')
@section('content')
<div class="wrapper">
  <div class="content-wrapper">
    <div class="row mb-3">
      <div class="col-12">
        <div class="card card-default shadow-sm border-0 rounded-4">
          <div class="card-header d-flex align-items-center justify-content-between">
            <div>
              <h4 class="mb-1">Billing Settings</h4>
              <p class="text-muted mb-0">Update subscription expiry for any organization</p>
            </div>
          </div>
          <div class="card-body">
            @include('partials.errors')
            <div class="table-responsive">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>S. No</th>
                    <th>Name</th>
                    <th>Domain</th>
                    <th>Current Status</th>
                    <th>Current Expiry</th>
                    <th>Update Expiry</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($organizations as $org)
                  <tr>
                    <td>{{ ($organizations->currentPage()-1)*$organizations->perPage() + $loop->iteration }}</td>
                    <td>{{ $org->organization_name }}</td>
                    <td>{{ $org->domain ?? '—' }}</td>
                    <td>
                      @php $bs = $org->billing_status ?? 'active'; @endphp
                      <span class="badge {{ $bs==='expired' ? 'badge-dark' : ($bs==='expiring_1' ? 'badge-danger' : ($bs==='expiring_10' ? 'badge-warning' : 'badge-success')) }}">
                        {{ ucfirst(str_replace('_', ' ', $bs)) }}
                      </span>
                    </td>
                    <td>{{ optional($org->subscription_expires_at)->format('Y-m-d') ?? '—' }}</td>
                    <td>
                      <form method="POST" action="{{ route('cms.admin.billing.settings.update', $org) }}" class="d-flex gap-2 align-items-center">
                        @csrf
                        <input type="date" class="form-control rounded-0 bg-light" name="subscription_expires_at" value="{{ optional($org->subscription_expires_at)->format('Y-m-d') }}" required>
                        <button class="btn btn-secondary btn-pill">Save</button>
                      </form>
                    </td>
                    <td>
                      <a href="{{ route('cms.admin.organizations.invoice', $org->id) }}" class="btn btn-outline-primary btn-sm">Invoice</a>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
            <div class="mt-2">{{ $organizations->links() }}</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

