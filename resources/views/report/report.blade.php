@extends('layouts.app')

@section('title', 'Report')

@push('page-styles')
<link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
<style>
    /* Make all cards in the same row equal height */
    .row>[class*='col-'] {
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
                <h4 class="fw-bold text-dark mb-1">Reports & Analytics</h4>
                <p class="text-muted fs-5 mb-0">Generate and view comprehensive reports</p>
            </div>

            <div class="d-flex align-items-center gap-2">
                <a href="{{ url('report/download-all') }}" class="btn btn-outline-primary btn-pill mb-0 d-flex align-items-center mr-2 shadow-sm px-3">
                    <i class="mdi mdi-download me-1"></i> Download All
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
                            <span class="h5 d-block mb-0">Financial Summary Report</span>
                            <small class="text-muted">Complete overview of income, expenses, and balance</small>
                        </div>
                        <div class="icon-md bg-info rounded-circle d-flex align-items-center justify-content-center"
                            style="width:70px; height:50px;">
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
                            <span class="h5 d-block mb-0">Aid Distribution Report</span>
                            <small class="text-muted">Summary of all aid programs and beneficiaries</small>
                        </div>
                        <div class="icon-md bg-danger rounded-circle d-flex align-items-center justify-content-center"
                            style="width:70px; height:50px;">
                            <i class="mdi mdi-trending-down text-white" style="font-size:26px;"></i>
                            <!-- Expenses Icon -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Net Balance -->
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card card-default shadow-sm border-0 rounded-4">
                    <div class="d-flex justify-content-between align-items-center p-4">
                        <div class="text-left">
                            <span class="h5 d-block mb-0">Family Assessment Report</span>
                            <small class="text-muted">Detailed analysis of family needs and status</small>
                        </div>
                        <div class="icon-md bg-warning rounded-circle d-flex align-items-center justify-content-center"
                            style="width:70px; height:50px;">
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
                            <span class="h5 d-block mb-0">Volunteer Activity Report</span>
                            <small class="text-muted">Overview of volunteer contributions and hours</small>
                        </div>
                        <div class="icon-md bg-primary rounded-circle d-flex align-items-center justify-content-center"
                            style="width:70px; height:50px;">
                            <i class="mdi mdi-timer-sand text-white" style="font-size:26px;"></i> <!-- Pending Icon -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-5">
            <div class="col-xl-12">
                <div class="card border-0">
                    <div class="card-body">
                        <h4 class="card-title fw-bold mb-1">Generate Custom Report</h4>
                        <p class="text-muted mb-4">Select report type and period to generate</p>
                        <div class="w-100 mb-3">
                            @include('partials.errors')
                        </div>

                        <form action="{{ url('reports/generate') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <!-- <label for="reportType" class="form-label fw-semibold mb-1">Report Type</label> -->
                                    <select class="form-control rounded-0 bg-light select2" id="reportType" name="type"
                                        style="width: 100%;">
                                        <option value="">Select Report Type</option>
                                        <option value="Families" {{ (data_get($filters,'type')=='Families')?'selected':'' }}>Family Report</option>
                                        <option value="Aid Programs" {{ (data_get($filters,'type')=='Aid Programs')?'selected':'' }}>Aid Program Report</option>
                                        <option value="Financial" {{ (data_get($filters,'type')=='Financial')?'selected':'' }}>Financial Management Report</option>
                                        <option value="Survey" {{ (data_get($filters,'type')=='Survey')?'selected':'' }}>Survey Report</option>
                                        <option value="Volunteers" {{ (data_get($filters,'type')=='Volunteers')?'selected':'' }}>Volunteer Report</option>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <!-- <label for="period" class="form-label fw-semibold mb-1">Period</label> -->
                                    <select class="form-control rounded-0 bg-light select2" id="period" name="period"
                                        style="width: 100%;">
                                        <option value="">Select Period</option>
                                        <option value="Last 7 Days" {{ (data_get($filters,'period')=='Last 7 Days')?'selected':'' }}>Last 7 Days</option>
                                        <option value="Last 30 Days" {{ (data_get($filters,'period')=='Last 30 Days')?'selected':'' }}>Last 30 Days</option>
                                        <option value="This Month" {{ (data_get($filters,'period')=='This Month')?'selected':'' }}>This Month</option>
                                        <option value="Last Month" {{ (data_get($filters,'period')=='Last Month')?'selected':'' }}>Last Month</option>
                                        <option value="This Quarter" {{ (data_get($filters,'period')=='This Quarter')?'selected':'' }}>This Quarter</option>
                                        <option value="This Year" {{ (data_get($filters,'period')=='This Year')?'selected':'' }}>This Year</option>
                                        <option value="Custom Range" {{ (data_get($filters,'period')=='Custom Range')?'selected':'' }}>Custom Range</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row" id="customRangeFields" style="display:none;">
                                <!-- From Date -->
                                <div class="col-md-6 mb-3">
                                    <input type="date" class="form-control rounded-0 bg-light" id="from_date" name="from_date" value="{{ data_get($filters,'from_date') }}" placeholder="From date">
                                </div>

                                <!-- To Date -->
                                <div class="col-md-6 mb-3">
                                    <input type="date" class="form-control rounded-0 bg-light" id="to_date" name="to_date"  value="{{ data_get($filters,'to_date') }}"  placeholder="To date">
                                </div>
                            </div>

                            <div class="text-end mt-3">
                                <button type="submit" class="btn btn-secondary btn-pill px-4 shadow-sm">
                                    <i class="mdi mdi-check-circle-outline me-1"></i> Generate New Report
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>

        @if(!empty($should_load_report_list1))
        <div class="row">
          <div class="col-12">
              <div class="card">
                  <div class="card-body">                   
                      <h4 class="card-title fw-bold mb-1">Recent Reports</h4>
                      <p class="text-muted mb-4">Previously generated reports</p>
                      <div class="table-responsive m-t-20">
                          <table id="data_table"
                              class="table table-striped table-bordered nowrap w-100"
                              cellspacing="0" width="100%">
                              <tbody id="tbl_data">
                                  <tr>
                                      @php($user = auth()->user())
                                      <td>
                                        <div class="rounded-circle d-flex align-items-center justify-content-center bg-primary" style="width:40px; height:40px;">
                                          <i class="mdi mdi-file-chart text-white" style="font-size:20px;"></i>
                                        </div>
                                      </td>
                                      <td>
                                        <h5 class="fw-semibold">{{ data_get($filters,'type') ? data_get($filters,'type').' Report' : 'All Reports' }}</h5>
                                        <small class="text-muted d-block">{{ data_get($filters,'type') ?: 'All' }} • {{ data_get($filters,'period') ?: 'All' }} • {{ now()->toDateTimeString() }}</small>
                                      </td>
                                      <td class="text-end">
                                        @if($user && $user->hasPermission('reports'))
                                        <a href="{{ url('report/view').'?type='.urlencode(data_get($filters,'type')).'&period='.urlencode(data_get($filters,'period')).(data_get($filters,'from_date')?'&from='.urlencode(data_get($filters,'from_date')):'').(data_get($filters,'to_date')?'&to='.urlencode(data_get($filters,'to_date')):'') }}" class="btn btn-sm btn-outline-primary me-2">View Report</a>
                                        <a href="{{ url('report/export').'?type='.urlencode(data_get($filters,'type')).'&period='.urlencode(data_get($filters,'period')).(data_get($filters,'from_date')?'&from='.urlencode(data_get($filters,'from_date')):'').(data_get($filters,'to_date')?'&to='.urlencode(data_get($filters,'to_date')):'') }}" class="btn btn-sm btn-primary me-2"><i class="mdi mdi-download"></i> Download</a>
                                        @endif
                                      </td>
                                  </tr>
                              </tbody>
                          </table>
                      </div>
                  </div>
              </div>
          </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('page-scripts')
<script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
<script>
    $(function () {
        $('#reportType, #period').select2({
            width: '100%'
        });

        function toggleCustomRange(){
            var show = ($('#period').val() === 'Custom Range');
            $('#customRangeFields').css('display', show ? '' : 'none');
        }
        $('#period').on('change', toggleCustomRange);
        toggleCustomRange();
    });

    function exportWithFilters() {
        var type = $('#reportType').val() || '';
        var period = $('#period').val() || '';
        var url = new URL("{{ url('report/export') }}", window.location.origin);
        if (type) url.searchParams.set('type', type);
        if (period) url.searchParams.set('period', period);
        var from = document.getElementById('from_date')?.value;
        var to = document.getElementById('to_date')?.value;
        if (from) url.searchParams.set('from', from);
        if (to) url.searchParams.set('to', to);
        window.location.href = url.toString();
    }
</script>
@endpush
