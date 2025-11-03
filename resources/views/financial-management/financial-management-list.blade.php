@extends('layouts.app')

@section('title', 'Financial Management List')

@push('page-styles')
<link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
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

    <!-- Dashboard Header -->
    <div class="d-flex justify-content-between align-items-center mb-1 flex-wrap">
        <div>
            <h4 class="fw-bold text-dark mb-1">Financial Management</h4>
            <p class="text-muted fs-5 mb-0">Track donations, expenses, and financial reports</p>
        </div>

        <!-- Select + Button side by side -->
        <div class="d-flex align-items-center" style="gap: 12px;">
            <select class="form-control rounded-0 bg-light" id="exampleFormControlSelect15" style="width: 160px;">
                <option>This Month</option>
                <option>Last Month</option>
                <option>This Quarter</option>
                <option>This Year</option>
            </select>

            <a href="{{ route('cms.financial-management.create') }}" type="button" class="btn btn-pill btn-primary rounded-pill px-3 d-flex align-items-center shadow-sm">
                <i class="mdi mdi-plus-circle-outline mr-1"></i>  Add New Transaction
            </a>

            <button type="button" class="btn btn-pill btn-primary rounded-pill px-3 d-flex align-items-center shadow-sm">
                <i class="mdi mdi-download me-1"></i> Export Report
            </button>
        </div>

    </div>


    <hr class="mt-2 mb-2">

   <!-- Top Statistics -->
    <div class="row">
        <!-- Total Income -->
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card card-default shadow-sm border-0 rounded-4">
                <div class="d-flex justify-content-between align-items-center p-4">
                    <div class="text-left">
                    <span class="h5 d-block mb-0">Total Income</span>
                    <p class="mb-1 fw-semibold">{{ number_format($totalIncome ?? 0) }}</p>
                    <small class="text-muted">Selected range</small>
                    </div>
                    <div class="icon-md bg-info rounded-circle d-flex align-items-center justify-content-center" style="width:50px; height:50px;">
                    <i class="mdi mdi-trending-up text-white" style="font-size:26px;"></i> <!-- Income Icon -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Expenses -->
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card card-default shadow-sm border-0 rounded-4">
                <div class="d-flex justify-content-between align-items-center p-4">
                    <div class="text-left">
                    <span class="h5 d-block mb-0">Total Expenses</span>
                    <p class="mb-1 fw-semibold">{{ number_format($totalExpenses ?? 0) }}</p>
                    <small class="text-muted">Selected range</small>
                    </div>
                    <div class="icon-md bg-danger rounded-circle d-flex align-items-center justify-content-center" style="width:50px; height:50px;">
                    <i class="mdi mdi-trending-down text-white" style="font-size:26px;"></i> <!-- Expenses Icon -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Net Balance -->
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card card-default shadow-sm border-0 rounded-4">
                <div class="d-flex justify-content-between align-items-center p-4">
                    <div class="text-left">
                    <span class="h5 d-block mb-0">Net Balance</span>
                    <p class="mb-1 fw-semibold">{{ number_format(($netBalance ?? 0)) }}</p>
                    <small class="text-muted">Income - Expenses</small>
                    </div>
                    <div class="icon-md bg-warning rounded-circle d-flex align-items-center justify-content-center" style="width:50px; height:50px;">
                    <i class="mdi mdi-wallet text-white" style="font-size:26px;"></i> <!-- Balance Icon -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Transactions -->
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card card-default shadow-sm border-0 rounded-4">
                <div class="d-flex justify-content-between align-items-center p-4">
                    <div class="text-left">
                    <span class="h5 d-block mb-0">Pending Transactions</span>
                    <p class="mb-1 fw-semibold">{{ $pendingCount ?? 0 }}</p>
                    <small class="text-muted">Awaiting approval</small>
                    </div>
                    <div class="icon-md bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width:50px; height:50px;">
                    <i class="mdi mdi-timer-sand text-white" style="font-size:26px;"></i> <!-- Pending Icon -->
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-xl-12">
            <!-- Monthly Financial Overview -->
            <div class="card card-default shadow-sm border-0 rounded-4">
                <div class="card-header bg-white border-0 pb-0">
                    <h2 class="fw-bold text-dark mb-0">Monthly Financial Overview</h2>
                    <p class="text-muted fs-6 mt-1 mb-0">Income vs Expenses over the last 6 months</p>
                </div>
                <div class="card-body">
                    <div class="chart-wrapper">
                    <div id="line-chart-4" style="height: 300px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Product -->
    <div class="row">
        <div class="col-12">
        <div class="card card-default">
            <div class="card-body">
            <table id="productsTable" class="table table-hover table-product" style="width:100%">
                <thead>
                <tr>
                    <th>S.no</th>
                    <th>Transaction Code </th>
                    <th>Date</th>
                    <th>Donation Type</th>
                    <th>Payment Mode</th>
                    <th>Donar Name</th>
                    <th>Amount</th>
                    <th>Aid Program Name</th>
                    <th>Family Name</th>
                    <th>Recorded By</th>
                    <th>Approved By</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                    @php $sn = ($financialTransactions->currentPage() - 1) * $financialTransactions->perPage() + 1; @endphp
                    @foreach($financialTransactions as $transaction)
                    <tr>
                        <td>{{ $sn++ }}</td>
                        <td>{{ $transaction->transaction_code ?? '-' }}</td>
                        <td>{{ $transaction->date ?? '-' }}</td>
                        <td>{{ $transaction->transaction_type ?? '-' }}</td>
                        <td>{{ $transaction->payment_mode ?? '-' }}</td>
                        <td>{{ $transaction->donor_name ?? '-' }}</td>
                        <td>{{ number_format($transaction->amount ?? 0) }}</td>
                        <td>{{ optional($transaction->aidProgram)->program_title ?? '-' }}</td>
                        <td>{{ optional($transaction->family)->head_of_family_name ?? '-' }}</td>
                        <td>{{ optional($transaction->recordedBy)->name ?? '-' }}</td>
                        <td>{{ optional($transaction->approvedBy)->name ?? '-' }}</td>
                        <td>

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
</div>
@endsection

@push('page-scripts')
<script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
<script>
  $(function () {
    // Initialize Select2 if class present
    if ($.fn.select2) {
      $('.select2').select2({ width: '100%', minimumResultsForSearch: Infinity });
    }

    // Map dropdown text to range keys and sync with controller
    (function() {
      const rangeKey = @json($range ?? 'this_month');
      const el = document.getElementById('exampleFormControlSelect15');
      if (!el) return;
      const toKey = {
        'This Month': 'this_month',
        'Last Month': 'last_month',
        'This Quarter': 'this_quarter',
        'This Year': 'this_year',
      };
      const fromKey = {
        'this_month': 'This Month',
        'last_month': 'Last Month',
        'this_quarter': 'This Quarter',
        'this_year': 'This Year',
      };

      // Set current selection from $range
      const wantText = fromKey[rangeKey] || 'This Month';
      Array.from(el.options).forEach(opt => { opt.selected = (opt.text.trim() === wantText); });

      // If Select2 is applied, update its value display
      if ($(el).hasClass('select2') && $.fn.select2) {
        $(el).trigger('change.select2');
      }

      // On change, redirect with ?range=
      el.addEventListener('change', function() {
        const key = toKey[this.value] || 'this_month';
        const url = new URL(window.location.href);
        url.searchParams.set('range', key);
        window.location.href = url.toString();
      });
    })();
  });
</script>
@endpush
