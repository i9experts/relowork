@extends('layouts.app')

@section('title', 'Organization Invoice')

@section('content')
<div class="wrapper">
  <div class="content-wrapper">
    @if(request()->query('download'))
      <script>
        document.addEventListener('DOMContentLoaded', function(){ window.print(); });
      </script>
      <style>
        @page { size: A4; margin: 16mm; }
        @media print {
          header, footer, nav, .btn, .no-print { display: none !important; }
          .card { box-shadow: none !important; border: 1px solid #ddd; }
        }
      </style>
    @endif
    <div class="row mb-3">
      <div class="col-12">
        <h4 class="mb-2">Organization Invoice</h4>
        <p>Summary for {{ $organization->organization_name }}</p>
      </div>
    </div>

    <div class="card mb-3">
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
            <h6 class="mb-1">Organization</h6>
            <div>{{ $organization->organization_name }}</div>
            <div>{{ $organization->email }}</div>
            <div>{{ $organization->address }}</div>
            <div>{{ $organization->domain ?? '-' }}</div>
          </div>
          <div class="col-md-6 text-md-end mt-3 mt-md-0">
            <h6 class="mb-1">Totals</h6>
            <div>Families: {{ $families }}</div>
            <div>Volunteers: {{ $volunteers }}</div>
            <div>Aid Programs: {{ $aidPrograms }}</div>
            <div>Surveys: {{ $surveys }}</div>
          </div>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-body">
        <div class="row">
          <div class="col-md-4">
            <div class="border rounded p-3 mb-2">
              <div class="fw-semibold">Total Donations</div>
              <div class="h5 mb-0">{{ number_format($donationsTotal) }}</div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="border rounded p-3 mb-2">
              <div class="fw-semibold">Total Expenses</div>
              <div class="h5 mb-0">{{ number_format($expensesTotal) }}</div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="border rounded p-3 mb-2">
              <div class="fw-semibold">Net Balance</div>
              <div class="h5 mb-0">{{ number_format($netBalance) }}</div>
            </div>
          </div>
        </div>

        <div class="mt-3">
          <a href="{{ route('cms.admin.organizations.invoice', $organization->id) }}?download=1" target="_blank" class="btn btn-primary">Download PDF</a>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

