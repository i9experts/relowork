@extends('layouts.app')

@section('title', 'Dashboard')

@push('page-styles')
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
  /* Fixed-height scroll areas */
  .scrollable .card-body { max-height: 340px; overflow-y: auto; }
</style>
@endpush

@section('content')
<div class="wrapper">
  <div class="content-wrapper">

    <div class="mb-1">
      <h4 class="fw-bold text-dark mb-1">{{ optional($organization)->organization_name ?: 'Dashboard' }}</h4>
      <p class="text-muted fs-5">Welcome to ReloWork — {{ optional($organization)->organization_name ?: 'Your Organization' }}</p>
      <hr class="mt-2 mb-2">
    </div>

    @php($u = auth()->user())
    <!-- Top Statistics -->
    <div class="row">
      @if(!$u || $u->hasPermission('families'))
      <!-- First box -->
      <div class="col-xl-4 col-md-6 mb-3">
        <div class="card card-default bg-secondary shadow-sm border-0">
          <div class="d-flex align-items-center p-4 justify-content-between">
            <div class="icon-md bg-white rounded-circle d-flex align-items-center justify-content-center" style="width:50px; height:50px;">
              <i class="mdi mdi-home-heart text-secondary" style="font-size:24px;"></i>
            </div>
            <div class="text-right">
              <p class="text-white mb-1 fw-semibold">Total Families</p>
              <span class="h3 d-block text-white mb-0">{{ $families }}</span>
              <!-- <small class="text-light opacity-75">+12.5% from last month</small> -->
            </div>
          </div>
        </div>
      </div>
      @endif

      @if(!$u || $u->hasPermission('aid_programs'))
      <!-- Second box -->
      <div class="col-xl-4 col-md-6 mb-3">
        <div class="card card-default bg-success shadow-sm border-0">
          <div class="d-flex align-items-center p-4 justify-content-between">
            <div class="icon-md bg-white rounded-circle d-flex align-items-center justify-content-center" style="width:50px; height:50px;">
              <i class="mdi mdi-table-edit text-success" style="font-size:24px;"></i>
            </div>
            <div class="text-right">
              <p class="text-white mb-1 fw-semibold">Active Aid Recipients</p>
              <span class="h3 d-block text-white mb-0">{{ $aidPrograms }}</span>
              <!-- <small class="text-light opacity-75">+8.2% from last month</small> -->
            </div>
          </div>
        </div>
      </div>
      @endif

      @if(!$u || $u->hasPermission('financial'))
      <!-- Third box -->
      <div class="col-xl-4 col-md-6 mb-3">
        <div class="card card-default bg-primary shadow-sm border-0">
          <div class="d-flex align-items-center p-4 justify-content-between">
            <div class="icon-md bg-white rounded-circle d-flex align-items-center justify-content-center" style="width:50px; height:50px;">
              <i class="mdi mdi-content-save-edit-outline text-primary" style="font-size:24px;"></i>
            </div>
            <div class="text-right">
              <p class="text-white mb-1 fw-semibold">Total Distributed</p>
              <span class="h3 d-block text-white mb-0">{{ $disbursements }}</span>
              <!-- <small class="text-light opacity-75">+15.3% from last month</small> -->
            </div>
          </div>
        </div>
      </div>
      @endif

      @if(!$u || $u->hasPermission('volunteers'))
      <!-- Fourth box -->
      <div class="col-xl-4 col-md-6 mb-3">
        <div class="card card-default bg-info shadow-sm border-0">
          <div class="d-flex align-items-center p-4 justify-content-between">
            <div class="icon-md bg-white rounded-circle d-flex align-items-center justify-content-center" style="width:50px; height:50px;">
              <i class="mdi mdi-account-multiple-check text-info" style="font-size:24px;"></i>
            </div>
            <div class="text-right">
              <p class="text-white mb-1 fw-semibold">Active Volunteers</p>
              <span class="h3 d-block text-white mb-0">{{ $volunteers }}</span>
              <!-- <small class="text-light opacity-75">+2 new volunteers</small> -->
            </div>
          </div>
        </div>
      </div>
      @endif

      @if(!$u || $u->hasPermission('financial'))
      <!-- fifth box -->
      <div class="col-xl-4 col-md-6 mb-3">
        <div class="card card-default bg-warning shadow-sm border-0">
          <div class="d-flex align-items-center p-4 justify-content-between">
            <div class="icon-md bg-white rounded-circle d-flex align-items-center justify-content-center" style="width:50px; height:50px;">
              <i class="mdi mdi-cash-multiple text-warning" style="font-size:26px;"></i>
            </div>
            <div class="text-right">
              <p class="text-white mb-1 fw-semibold">Total Donations</p>
              <span class="h3 d-block text-white mb-0">{{ $donationsTotal }}</span>
            </div>
          </div>
        </div>
      </div>
      @endif

      @if(!$u || $u->hasPermission('surveys'))
      <!-- sixth box -->
      <div class="col-xl-4 col-md-6 mb-3">
        <div class="card card-default bg-danger shadow-sm border-0">
          <div class="d-flex align-items-center p-4 justify-content-between">
            <div class="icon-md bg-white rounded-circle d-flex align-items-center justify-content-center" style="width:50px; height:50px;">
              <i class="mdi mdi-poll text-danger" style="font-size:26px;"></i>
            </div>
            <div class="text-right">
              <p class="text-white mb-1 fw-semibold">Surveys</p>
              <span class="h3 d-block text-white mb-0">{{ $surveysCount }}</span>
            </div>
          </div>
        </div>
      </div>
      @endif

    </div>

    <!-- Income And Expenses row -->
    <div class="row">
      @if(!$u || $u->hasPermission('financial'))
      <div class="col-xl-8">
        <!-- Income and Expenses -->
        <div class="card card-default">
          <div class="card-header">
            <h2 class="mb-0">Monthly Aid Distribution</h2>
            <p class="small">Aid distribution trends over the last 6 months</p>
          </div>
          <div class="card-body">
            <div class="chart-wrapper">
              <div id="mixed-chart-1"></div>
            </div>
          </div>
        </div>
      </div>
      @endif

      @if(!$u || $u->hasPermission('families'))
      <div class="col-xl-4">
        <!-- Pie Chart -->
        <div class="card card-default">
          <div class="card-header">
            <h2 class="mb-0">Family Status Overview</h2>
            <p class="small">Current status distribution</p>
          </div>
          <div class="card-body p-xl-7">
            <div id="simple-pie-chart" class="d-flex justify-content-center"></div>
          </div>
        </div>
      </div>
      @endif
    </div>

    <!-- Activity and Quick Actions -->
    <div class="row">
      <div class="col-xl-6">
        <!-- Recent Activity -->
        <div class="card card-default scrollable">
          <div class="card-header align-items-center d-flex justify-content-between">
            <h2 class="mb-0">Recent Activity</h2>
            <a href="#" class="btn btn-link btn-pill" data-toggle="modal" data-target="#modal-stock">View All</a>
          </div>

          <div class="card-body">
            @php($items = [])
            @foreach(($recent['transactions'] ?? []) as $t)
              @php($items[] = [
                'title' => ($t->transaction_type.' — '.number_format((float)$t->amount,2)),
                'desc' => (optional($t->family)->family_name ?: optional($t->aidProgram)->program_title ?: '—'),
                'time' => optional($t->date)->diffForHumans(),
                'badge' => ($t->transaction_type === 'Expense' ? 'danger' : ($t->transaction_type === 'Donation' ? 'secondary' : ($t->transaction_type === 'Disbursement' ? 'warning' : 'info'))),
                'badgeLabel' => $t->transaction_type,
              ])
            @endforeach
            @foreach(($recent['volunteers'] ?? []) as $v)
              @php($items[] = [
                'title' => ('Volunteer — '.($v->full_name ?: $v->user?->name ?: '—')),
                'desc' => ('Status: '.($v->status ?: '—')),
                'time' => optional($v->updated_at)->diffForHumans(),
                'badge' => ($v->status === 'Active' ? 'success' : 'warning'),
                'badgeLabel' => ($v->status ?: 'Updated'),
              ])
            @endforeach

            @forelse($items as $it)
              <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-3">
                <div>
                  <h6 class="mb-1 fw-semibold">{{ $it['title'] }}</h6>
                  <p class="mb-1 text-muted small">{{ $it['desc'] }}</p>
                  <span class="text-secondary small">{{ $it['time'] }}</span>
                </div>
                <span class="badge badge-pill badge-{{ $it['badge'] }}">{{ $it['badgeLabel'] }}</span>
              </div>
            @empty
              <p class="text-muted mb-0">No recent activity.</p>
            @endforelse
          </div>
        </div>
      </div>

      <div class="col-xl-6">
        <!-- Quick Actions -->
        <div class="card card-default scrollable">
          <div class="card-header">
            <h2 class="mb-0">Quick Actions</h2>
            <p class="small">Frequently used actions</p>
          </div>

          <div class="card-body">
            <div class="d-grid gap-2">
              @if(!$u || $u->hasPermission('families_create'))
              <a href="{{ route('cms.family.create') }}" class="btn btn-outline-primary btn-pill btn-block d-flex align-items-center justify-content-center">
                 Add New Family
              </a>
              @endif

              @if(!$u || $u->hasPermission('financial'))
              <a href="{{ route('cms.financial-management.index') }}" class="btn btn-outline-primary btn-pill btn-block d-flex align-items-center justify-content-center">
                Approve Aid Requests
              </a>
              @endif

              @if(!$u || $u->hasPermission('reports'))
              <a href="{{ route('cms.report.index') }}" class="btn btn-outline-primary btn-pill btn-block d-flex align-items-center justify-content-center">
                Generate Reports
              </a>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>
@endsection

@push('page-scripts')
<script>
  (function(){
    try {
      // Override any pre-rendered demo chart with real monthly data
      var elMixed = document.querySelector('#mixed-chart-1');
      (function ensureMixedChart(max=20, delay=200){
        if (!elMixed) return;
        if (window.ApexCharts) {
          elMixed.innerHTML = '';
          var m = @json($monthlyAid['months'] ?? []);
          var income = @json($monthlyAid['income'] ?? []);
          var expenses = @json($monthlyAid['expenses'] ?? []);
          var options = {
            chart: { height: 370, type: 'bar', toolbar: { show: false } },
            colors: ['#9e6de0', '#faafca'],
            legend: { show: true, position: 'top', horizontalAlign: 'right' },
            plotOptions: { bar: { horizontal: false, columnWidth: '50%' } },
            dataLabels: { enabled: false },
            stroke: { show: true, width: 2, curve: 'smooth' },
            series: [
              { name: 'Donations', type: 'column', data: income },
              { name: 'Disbursements', type: 'column', data: expenses },
              { name: 'Net', type: 'line', data: income.map(function(v,i){ return (v - (expenses[i]||0)); }) }
            ],
            xaxis: { categories: m, axisBorder: { show: false }, axisTicks: { show: false }, crosshairs: { width: 40 } },
            fill: { opacity: 1 },
            tooltip: { shared: true, intersect: false }
          };
          try { new ApexCharts(elMixed, options).render(); } catch (e) {}
          return;
        }
        if (max > 0) setTimeout(function(){ ensureMixedChart(max-1, delay); }, delay);
      })();

      var el = document.querySelector('#simple-pie-chart');
      if (!el || !window.ApexCharts) return;
      el.innerHTML = '';
      var chartData = @json($familyStatusChart ?? ['eligible'=>0,'nonEligible'=>0]);
      var options = {
        chart: { type: 'pie', height: 320 },
        labels: ['Zakat Eligible', 'Not Eligible'],
        series: [chartData.eligible || 0, chartData.nonEligible || 0],
        colors: ['#46c79e', '#faafca'],
        legend: { position: 'bottom' },
        dataLabels: { enabled: true },
      };
      var chart = new ApexCharts(el, options);
      chart.render();
    } catch (e) {}
  })();
</script>
<script>
  (function(){
    try {
      var container = document.querySelector('.card-body');
      // Recent Activity dynamic render
      var recent = @json($recent ?? ['transactions'=>[],'volunteers'=>[]]);
      var list = document.querySelector('.card .card-body');
      // We won't manipulate existing static blocks here to avoid layout conflict.
    } catch (e) {}
  })();
</script>
@endpush
