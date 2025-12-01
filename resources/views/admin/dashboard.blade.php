@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="wrapper">
  <div class="content-wrapper">

    <div class="mb-1">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <h4 class="fw-bold text-dark mb-1">Super Admin Dashboard</h4>
          <p class="text-muted fs-5">Platform-wide overview of all organizations.</p>
        </div>

        <a href="{{ route('cms.setting.index') }}" class="btn btn-outline-secondary btn-sm ms-auto">
          Settings
        </a>
      </div>

      <hr class="mt-2 mb-2">
    </div>


      <!-- Top Statistics -->
      <div class="row">

        <!-- Total Organizations -->
        <div class="col-xl-4 col-md-6 mb-3">
          <div class="card card-default bg-secondary shadow-sm border-0">
            <div class="d-flex align-items-center p-4 justify-content-between">
              <div class="icon-md bg-white rounded-circle d-flex align-items-center justify-content-center" style="width:50px; height:50px;">
                <i class="mdi mdi-domain text-secondary" style="font-size:24px;"></i>
              </div>
              <div class="text-right">
                <p class="text-white mb-1 fw-semibold">Total Organizations</p>
                <span class="h3 d-block text-white mb-0">{{ $organizations }}</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Total Users -->
        <div class="col-xl-4 col-md-6 mb-3">
          <div class="card card-default bg-success shadow-sm border-0">
            <div class="d-flex align-items-center p-4 justify-content-between">
              <div class="icon-md bg-white rounded-circle d-flex align-items-center justify-content-center" style="width:50px; height:50px;">
                <i class="mdi mdi-account-group-outline text-success" style="font-size:24px;"></i>
              </div>
              <div class="text-right">
                <p class="text-white mb-1 fw-semibold">Total Users</p>
                <span class="h3 d-block text-white mb-0">{{ $users }}</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Total Families -->
        <div class="col-xl-4 col-md-6 mb-3">
          <div class="card card-default bg-primary shadow-sm border-0">
            <div class="d-flex align-items-center p-4 justify-content-between">
              <div class="icon-md bg-white rounded-circle d-flex align-items-center justify-content-center" style="width:50px; height:50px;">
                <i class="mdi mdi-home-group text-primary" style="font-size:24px;"></i>
              </div>
              <div class="text-right">
                <p class="text-white mb-1 fw-semibold">Total Families</p>
                <span class="h3 d-block text-white mb-0">{{ $families }}</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Total Volunteers -->
        <div class="col-xl-4 col-md-6 mb-3">
          <div class="card card-default bg-warning shadow-sm border-0">
            <div class="d-flex align-items-center p-4 justify-content-between">
              <div class="icon-md bg-white rounded-circle d-flex align-items-center justify-content-center" style="width:50px; height:50px;">
                <i class="mdi mdi-account-heart-outline text-warning" style="font-size:24px;"></i>
              </div>
              <div class="text-right">
                <p class="text-white mb-1 fw-semibold">Total Volunteers</p>
                <span class="h3 d-block text-white mb-0">{{ $volunteers }}</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Total Aid Programs -->
        <div class="col-xl-4 col-md-6 mb-3">
          <div class="card card-default bg-info shadow-sm border-0">
            <div class="d-flex align-items-center p-4 justify-content-between">
              <div class="icon-md bg-white rounded-circle d-flex align-items-center justify-content-center" style="width:50px; height:50px;">
                <i class="mdi mdi-hand-heart text-info" style="font-size:24px;"></i>
              </div>
              <div class="text-right">
                <p class="text-white mb-1 fw-semibold">Total Aid Programs</p>
                <span class="h3 d-block text-white mb-0">{{ $aidPrograms }}</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Active Organizations -->
        <div class="col-xl-4 col-md-6 mb-3">
          <div class="card card-default bg-danger shadow-sm border-0">
            <div class="d-flex align-items-center p-4 justify-content-between">
              <div class="icon-md bg-white rounded-circle d-flex align-items-center justify-content-center" style="width:50px; height:50px;">
                <i class="mdi mdi-check-decagram-outline text-danger" style="font-size:24px;"></i>
              </div>
              <div class="text-right">
                <p class="text-white mb-1 fw-semibold">Active Organizations</p>
                <span class="h3 d-block text-white mb-0">{{ $activeOrganizations }}</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Inactive Organizations -->
        <div class="col-xl-4 col-md-6 mb-3">
          <div class="card card-default bg-dark shadow-sm border-0">
            <div class="d-flex align-items-center p-4 justify-content-between">
              <div class="icon-md bg-white rounded-circle d-flex align-items-center justify-content-center" style="width:50px; height:50px;">
                <i class="mdi mdi-cancel text-dark" style="font-size:24px;"></i>
              </div>
              <div class="text-right">
                <p class="text-white mb-1 fw-semibold">Inactive Organizations</p>
                <span class="h3 d-block text-white mb-0">{{ $inactiveOrganizations }}</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Financial Summary -->
        <div class="col-xl-4 col-md-6 mb-3">
          <div class="card card-default shadow-sm border-0">
            <div class="d-flex align-items-center p-4 justify-content-between">
              <div class="icon-md bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width:50px; height:50px;">
                <i class="mdi mdi-cash text-white" style="font-size:24px;"></i>
              </div>
              <div class="text-right">
                <p class="mb-1">Donations</p>
                <span class="h5 d-block mb-0">{{ number_format($donationsTotal) }}</span>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-4 col-md-6 mb-3">
          <div class="card card-default shadow-sm border-0">
            <div class="d-flex align-items-center p-4 justify-content-between">
              <div class="icon-md bg-danger rounded-circle d-flex align-items-center justify-content-center" style="width:50px; height:50px;">
                <i class="mdi mdi-credit-card text-white" style="font-size:24px;"></i>
              </div>
              <div class="text-right">
                <p class="mb-1">Expenses + Disbursements</p>
                <span class="h5 d-block mb-0">{{ number_format($expensesTotal) }}</span>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-4 col-md-6 mb-3">
          <div class="card card-default shadow-sm border-0">
            <div class="d-flex align-items-center p-4 justify-content-between">
              <div class="icon-md bg-success rounded-circle d-flex align-items-center justify-content-center" style="width:50px; height:50px;">
                <i class="mdi mdi-chart-line text-white" style="font-size:24px;"></i>
              </div>
              <div class="text-right">
                <p class="mb-1">Net Balance</p>
                <span class="h5 d-block mb-0">{{ number_format($netBalance) }}</span>
              </div>
            </div>
          </div>
        </div>

      </div>

      <!-- Charts -->
      <div class="row">
        <div class="col-lg-6 mb-3">
          <div class="card card-default shadow-sm border-0 rounded-4">
            <div class="card-header">
              <h6 class="mb-0">Monthly Organization Registrations</h6>
            </div>
            <div class="card-body">
              <div id="orgRegChart" style="min-height:320px;"></div>
            </div>
          </div>
        </div>
        <div class="col-lg-6 mb-3">
          <div class="card card-default shadow-sm border-0 rounded-4">
            <div class="card-header">
              <h6 class="mb-0">Monthly Aid Distribution</h6>
            </div>
            <div class="card-body">
              <div id="aidDistChart" style="min-height:320px;"></div>
            </div>
          </div>
        </div>
      </div>

      <!-- Recent Organizations -->
      <div class="row">
        <div class="col-12 mb-3">
          <div class="card card-default shadow-sm border-0 rounded-4">
            <div class="card-header d-flex justify-content-between align-items-center">
              <h6 class="mb-0">Recent Organizations</h6>
              <a href="{{ route('cms.admin.organizations') }}" class="btn btn-sm btn-outline-secondary">View All</a>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Name</th>
                      <th>Email</th>
                      <th>Status</th>
                      <th>Registered</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($recentOrganizations as $o)
                    <tr>
                      <td>{{ $loop->iteration }}</td>
                      <td>{{ $o->organization_name }}</td>
                      <td>{{ $o->email }}</td>
                      <td>
                        <span class="badge {{ $o->active_status ? 'badge-success' : 'badge-dark' }}">{{ $o->active_status ? 'Active' : 'Inactive' }}</span>
                      </td>
                      <td>{{ optional($o->created_at)->format('Y-m-d') }}</td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Recent Activity -->
      <div class="row">
        <div class="col-12 mb-3">
          <div class="card card-default shadow-sm border-0 rounded-4">
            <div class="card-header d-flex justify-content-between align-items-center">
              <h6 class="mb-0">Recent Activity</h6>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Name</th>
                      <th>Email</th>
                      <th>Organization</th>
                      <th>Joined</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach(($recentUsers ?? []) as $u)
                    <tr>
                      <td>{{ $loop->iteration }}</td>
                      <td>{{ $u->name }}</td>
                      <td>{{ $u->email }}</td>
                      <td>{{ $u->organization_id }}</td>
                      <td>{{ optional($u->created_at)->format('Y-m-d') }}</td>
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
    </div>

@endsection

@push('page-scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
(function(){
  var months = @json($months ?? []);
  var orgRegs = @json($orgRegistrations ?? []);
  var aidDist = @json($aidDistribution ?? []);

  if (document.getElementById('orgRegChart')) {
    var o = {
      chart: { type: 'bar', height: 320, toolbar: { show: false } },
      series: [{ name: 'Registrations', data: orgRegs }],
      colors: ['#6c9ef0'],
      xaxis: { categories: months },
      dataLabels: { enabled: false },
      legend: { show: false }
    };
    try { new ApexCharts(document.querySelector('#orgRegChart'), o).render(); } catch(e){}
  }

  if (document.getElementById('aidDistChart')) {
    var a = {
      chart: { type: 'area', height: 320, toolbar: { show: false } },
      series: [{ name: 'Aid Distributed', data: aidDist }],
      colors: ['#faafca'],
      xaxis: { categories: months },
      dataLabels: { enabled: false },
      stroke: { curve: 'smooth', width: 2 },
      fill: { opacity: 0.3 },
      legend: { show: false }
    };
    try { new ApexCharts(document.querySelector('#aidDistChart'), a).render(); } catch(e){}
  }
})();
</script>
@endpush
