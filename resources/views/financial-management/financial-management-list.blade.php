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
                <option value="this_month"   {{ ($range ?? 'this_month') === 'this_month' ? 'selected' : '' }}>This Month</option>
                <option value="last_month"   {{ ($range ?? 'this_month') === 'last_month' ? 'selected' : '' }}>Last Month</option>
                <option value="this_quarter" {{ ($range ?? 'this_month') === 'this_quarter' ? 'selected' : '' }}>This Quarter</option>
                <option value="this_year"    {{ ($range ?? 'this_month') === 'this_year' ? 'selected' : '' }}>This Year</option>
                <option value="all_time"     {{ ($range ?? 'this_month') === 'all_time' ? 'selected' : '' }}>All Time</option>
            </select>

            <a href="{{ route('cms.financial-management.create') }}" type="button" class="btn btn-pill btn-primary rounded-pill px-3 d-flex align-items-center shadow-sm">
                <i class="mdi mdi-plus-circle-outline mr-1"></i>  Add New Transaction
            </a>

            <a href="{{ route('cms.financial-management.export', ['range' => $range ?? 'this_month']) }}" class="btn btn-pill btn-primary rounded-pill px-3 d-flex align-items-center shadow-sm">
                <i class="mdi mdi-download me-1"></i> Export Report
            </a>
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
                    <h2 class="fw-bold text-dark mb-0">{{ $chartTitle ?? 'Financial Overview' }}</h2>
                    <p class="text-muted fs-6 mt-1 mb-0">{{ $chartSubtitle ?? 'Income vs Expenses' }}</p>
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
                            <div class="dropdown">
                              <button class="btn btn-outline-secondary dropdown-toggle btn-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-display="static">
                                Actions
                              </button>
                              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="{{ route('cms.financial-management.edit', $transaction->id) }}">Edit</a>
                                <form action="{{ route('cms.financial-management.destroy', $transaction->id) }}" method="POST" onsubmit="return confirm('');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="dropdown-item text-danger">Delete</button>
                                </form>
                              </div>
                            </div>
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
      const el = document.getElementById('exampleFormControlSelect15');
      if (!el) return;
      const rangeKey = @json($range ?? 'this_month');

      // Ensure current selection reflects $range (already set by Blade, but keep safe)
      if (el.value !== rangeKey) {
        el.value = rangeKey;
      }

      // If Select2 is applied, update its value display
      if ($(el).hasClass('select2') && $.fn.select2) {
        $(el).trigger('change.select2');
      }

      // On change, redirect with ?range=
      el.addEventListener('change', function() {
        const key = this.value || 'this_month';
        const url = new URL(window.location.href);
        url.searchParams.set('range', key);
        window.location.href = url.toString();
      });
    })();
  });
</script>
<script>
  (function() {
    let labels = @json($chartLabels ?? []);
    let income = (@json($chartIncome ?? []) || []).map(function(n){ n = Number(n); return isNaN(n) ? 0 : n; });
    let expenses = (@json($chartExpenses ?? []) || []).map(function(n){ n = Number(n); return isNaN(n) ? 0 : n; });

    // Normalize lengths: series should match labels length
    function normalize() {
      const len = Array.isArray(labels) ? labels.length : 0;
      if (!Array.isArray(labels)) labels = [];
      function fix(arr){
        if (!Array.isArray(arr)) arr = [];
        if (arr.length < len) { arr = arr.concat(Array(len - arr.length).fill(0)); }
        if (arr.length > len) { arr = arr.slice(0, len); }
        return arr;
      }
      income = fix(income);
      expenses = fix(expenses);
    }
    normalize();

    function ensureChart() {
      if (!document.getElementById('line-chart-4')) return; // container missing
      if (!window.ApexCharts) return; // library not loaded yet

      // Try updating an existing chart first
      try {
        ApexCharts.exec('financial-overview', 'updateOptions', {
          labels: [],
          xaxis: { categories: labels, axisBorder: { show: false }, axisTicks: { show: false } },
          tooltip: { theme: 'dark', shared: true, intersect: false, x: { show: true } },
          stroke: { width: [2, 3], curve: 'smooth', dashArray: [0, 5] },
          legend: { show: true, position: 'top', horizontalAlign: 'right', markers: { width: 20, height: 5, radius: 0 } },
          colors: ['#9e6de0', '#fec400'],
          yaxis: { min: 0 }
        }, false, true);
        ApexCharts.exec('financial-overview', 'updateSeries', [
          { name: 'Income', data: income },
          { name: 'Expenses', data: expenses },
        ], true);
        window.__fin_overview_ready = true;
        return true;
      } catch (e) {
        // If no existing instance, or update failed, create a new chart aligned to theme
        try {
          const el = document.querySelector('#line-chart-4');
          if (!el) return false;
          el.innerHTML = '';
          const options = {
            chart: { id: 'financial-overview', type: 'line', height: 350, toolbar: { show: false } },
            stroke: { width: [2, 3], curve: 'smooth', dashArray: [0, 5] },
            colors: ['#9e6de0', '#fec400'],
            legend: { show: true, position: 'top', horizontalAlign: 'right', markers: { width: 20, height: 5, radius: 0 } },
            series: [
              { name: 'Income', data: income },
              { name: 'Expenses', data: expenses },
            ],
            xaxis: { categories: labels, axisBorder: { show: false }, axisTicks: { show: false } },
            markers: { size: [5, 0] },
            tooltip: { theme: 'dark', shared: true, intersect: false, x: { show: true } },
            yaxis: { min: 0 },
            noData: { text: 'No data for selected range', align: 'center' }
          };
          const chart = new ApexCharts(el, options);
          chart.render();
          window.__fin_overview_ready = true;
          return true;
        } catch (_) {
          return false;
        }
      }
    }

    // Retry a few times until the chart is ready or created
    (function retry(max = 15, delay = 150) {
      if (labels && labels.length && ensureChart()) return;
      if (max <= 0) return;
      setTimeout(() => retry(max - 1, delay), delay);
    })();
  })();
</script>
@endpush
