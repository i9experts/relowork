@extends('layouts.app')
@section('title', 'Billing History')

@push('page-styles')
<link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
@endpush

@section('content')
<div class="wrapper">
  <div class="content-wrapper">

    <div class="row">
      <div class="col-12">
        <div class="card">
          <!-- Header -->
          <div class="card-body pb-0">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
              <div class="mb-2">
                <h4 class="fw-bold text-dark mb-1">Billing History</h4>
                <p class="text-muted fs-5 mb-0">Actual payments per organization</p>
              </div>
              <div>
                <a href="{{ route('cms.admin.billing.history.print', request()->query()) }}" class="btn btn-outline-primary rounded-pill">
                  <i class="mdi mdi-file-pdf"></i> Export PDF
                </a>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-xl-12">
              <div class="card border-0">
                <div class="card-body">
                  <div class="w-100 mb-3">@include('partials.errors')</div>

                  <form action="{{ route('cms.admin.billing.history') }}" method="POST">
                    @csrf
                    <div class="row">
                      <div class="col-md-6 mb-3">
                        <label class="form-label">Organization</label>
                        <select class="form-control rounded-0 bg-light select2" name="organization_id" style="width:100%">
                          <option value="">All</option>
                          @foreach($organizations as $org)
                            <option value="{{ $org->id }}" {{ request('organization_id')==(string)$org->id ? 'selected' : '' }}>{{ $org->organization_name }}</option>
                          @endforeach
                        </select>
                      </div>
                      <div class="col-md-6 mb-3">
                        <label class="form-label">Status</label>
                        @php $st = request('status'); @endphp
                        <select class="form-control rounded-0 bg-light select2" name="status">
                          <option value="">All</option>
                          <option value="paid" {{ $st=='paid' ? 'selected' : '' }}>Paid</option>
                          <option value="pending" {{ $st=='pending' ? 'selected' : '' }}>Pending</option>
                          <option value="failed" {{ $st=='failed' ? 'selected' : '' }}>Failed</option>
                          <option value="refunded" {{ $st=='refunded' ? 'selected' : '' }}>Refunded</option>
                        </select>
                      </div>
                      <div class="col-md-4 mb-3">
                        <label class="form-label">From</label>
                        <input type="date" class="form-control rounded-0 bg-light" name="from" value="{{ request('from') }}">
                      </div>
                      <div class="col-md-4 mb-3">
                        <label class="form-label">To</label>
                        <input type="date" class="form-control rounded-0 bg-light" name="to" value="{{ request('to') }}">
                      </div>
                      <div class="col-md-4 mb-3">
                        <label class="form-label">Search</label>
                        <input type="text" class="form-control rounded-0 bg-light" name="q" placeholder="Method or Reference" value="{{ request('q') }}">
                      </div>
                    </div>
                    <div class="text-end mt-3">
                      <button type="submit" class="btn btn-secondary btn-pill px-4 shadow-sm">
                        <i class="mdi mdi-check-circle-outline me-1"></i> Filter
                      </button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title fw-bold mb-1">Recent Reports</h4>
                  <p class="text-muted mb-4">Previously generated reports</p>
                  @if(!isset($hasFilters) || !$hasFilters)
                    <div class="text-center text-muted py-4">Use the filters above to search payments.</div>
                  @else
                  <div class="table-responsive m-t-20">
                    <table class="table table-hover table-product" style="width:100%">
                      <thead>
                        <tr>
                          <th>S. No</th>
                          <th>Organization</th>
                          <th>Amount</th>
                          <th>Currency</th>
                          <th>Paid At</th>
                          <th>Method</th>
                          <th>Reference</th>
                          <th>Status</th>
                        </tr>
                      </thead>
                      <tbody>
                        @forelse($payments as $p)
                          <tr>
                            <td>{{ ($payments->currentPage()-1) * $payments->perPage() + $loop->iteration }}</td>
                            <td>{{ optional($p->organization)->organization_name ?? '—' }}</td>
                            <td>{{ number_format((float)$p->amount, 2) }}</td>
                            <td>{{ $p->currency }}</td>
                            <td>{{ optional($p->paid_at)->format('Y-m-d') ?? '—' }}</td>
                            <td>{{ $p->method ?? '—' }}</td>
                            <td>{{ $p->reference ?? '—' }}</td>
                            <td>
                              <span class="badge {{ $p->status=='paid' ? 'badge-success' : ($p->status=='pending' ? 'badge-warning' : ($p->status=='failed' ? 'badge-danger' : 'badge-dark')) }}">{{ ucfirst($p->status) }}</span>
                            </td>
                          </tr>
                        @empty
                          <tr>
                            <td colspan="8" class="text-center text-muted py-4">No payments found</td>
                          </tr>
                        @endforelse
                      </tbody>
                    </table>
                  </div>
                  <div class="mt-2">{{ $payments->links() }}</div>
                  @endif
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

@push('page-scripts')
<script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
<script>$(function(){ $('.select2').select2({ width:'100%' }); });</script>
@endpush

