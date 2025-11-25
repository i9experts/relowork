@extends('layouts.app')

@section('title', ($type ? $type.' Report' : 'Report').' - Detail')

@section('content')
<div class="wrapper">
  <div class="content-wrapper">

    @php($user = auth()->user())

    <div class="d-flex justify-content-between align-items-center mb-2">
      <div>
        <h4 class="fw-bold text-dark mb-1">{{ $type ? $type.' Report' : 'Report' }}</h4>
        <p class="text-muted mb-0">Period: {{ $period ?: 'All' }}</p>
      </div>
      <div class="d-flex gap-2">
        @if($user && $user->hasPermission('reports'))
        <a href="{{ url('report/export').'?type='.urlencode($type).'&period='.urlencode($period) }}" class="btn btn-primary btn-pill mr-2">
          <i class="mdi mdi-download me-1"></i> Export CSV
        </a>
        @endif
        <a href="{{ route('cms.report.index') }}" class="btn btn-outline-secondary btn-pill">Back to Reports</a>
      </div>
    </div>

    @if(!empty($kpis))
      @php
          $count = count($kpis);
          if ($count == 1) {
              $colClass = 'col-12';
          } elseif ($count == 2) {
              $colClass = 'col-md-6';
          } elseif ($count == 3) {
              $colClass = 'col-md-4';
          } else {
              $colClass = 'col-md-3';
          }
      @endphp

      <div class="row">
          @foreach($kpis as $label => $value)
          <div class="{{ $colClass }} mb-3">
              <div class="card card-default shadow-sm border-0 rounded-4">
                  <div class="d-flex justify-content-between align-items-center p-4">
                      <!-- Left Side: Label -->
                      <span class="h6 mb-0 text-capitalize">{{ str_replace('_',' ', $label) }}</span>
                      <!-- Right Side: Value -->
                      <span class="fw-semibold text-end">{{ $value }}</span>
                  </div>
              </div>
          </div>
          @endforeach
      </div>
    @endif



    <div class="card border-0">
      <div class="card-body">
        <h5 class="fw-bold mb-3">Records</h5>
        <div class="table-responsive">
          <table class="table table-striped align-middle">
            <thead>
              @if($type === 'Financial')
              <tr>
                <th>Date</th>
                <th>Type</th>
                <th>Amount</th>
                <th>Program</th>
                <th>Family</th>
                <th>Recorded By</th>
              </tr>
              @elseif($type === 'Survey')
              <tr>
                <th>ID</th>
                <th>Code</th>
                <th>Family</th>
                <th>Enumerator</th>
                <th>Members</th>
                <th>Uploads</th>
                <th>Created</th>
              </tr>
              @elseif($type === 'Attendance' || $type === 'Performance')
              <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Joining Date</th>
                <th>Updated</th>
              </tr>
              @else
              <tr>
                <th>ID</th>
                <th>Family Code</th>
                <th>Head</th>
                <th>Members</th>
                <th>Volunteer</th>
                <th>Created</th>
              </tr>
              @endif
            </thead>
            <tbody>
              @forelse($rows as $row)
                @if($type === 'Financial')
                <tr>
                  <td>{{ \Illuminate\Support\Carbon::parse($row->date)->format('Y-m-d') }}</td>
                  <td>{{ $row->transaction_type }}</td>
                  <td>{{ $row->amount }}</td>
                  <td>{{ optional($row->aidProgram)->program_title ?: '-' }}</td>
                  <td>{{ optional($row->family)->family_code ?: '-' }}</td>
                  <td>{{ optional($row->recordedBy)->name ?: '-' }}</td>
                </tr>
                @elseif($type === 'Survey')
                <tr>
                  <td>{{ $row->id }}</td>
                  <td>{{ $row->survey_code ?: ('S-'.$row->id) }}</td>
                  <td>{{ optional($row->family)->family_code ?: '-' }}</td>
                  <td>{{ optional($row->enumerator)->full_name ?: '-' }}</td>
                  <td>{{ $row->members_count }}</td>
                  <td>{{ $row->uploads_count }}</td>
                  <td>{{ $row->created_at?->format('Y-m-d') }}</td>
                </tr>
                @elseif($type === 'Attendance' || $type === 'Performance')
                <tr>
                  <td>{{ $row->id }}</td>
                  <td>{{ $row->full_name }}</td>
                  <td>{{ $row->joining_date?->format('Y-m-d') }}</td>
                  <td>{{ $row->updated_at?->format('Y-m-d') }}</td>
                </tr>
                @else
                <tr>
                  <td>{{ $row->id }}</td>
                  <td>{{ $row->family_code }}</td>
                  <td>{{ $row->head_of_family_name }}</td>
                  <td>{{ $row->members_count }}</td>
                  <td>{{ optional($row->volunteer)->full_name ?: '-' }}</td>
                  <td>{{ $row->created_at?->format('Y-m-d') }}</td>
                </tr>
                @endif
              @empty
                <tr><td colspan="8" class="text-center text-muted">No records found.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
        <div>
          {{ $rows->links() }}
        </div>
      </div>
    </div>

  </div>
</div>
@endsection