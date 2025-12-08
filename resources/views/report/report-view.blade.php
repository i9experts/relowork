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
        @if($user && $user->hasPermission('reports_export_csv'))
        <a href="{{ url('report/export').'?type='.urlencode($type).'&period='.urlencode($period).(request('from')?'&from='.urlencode(request('from')):'').(request('to')?'&to='.urlencode(request('to')):'') }}" class="btn btn-primary btn-pill mr-2">
          <i class="mdi mdi-download me-1"></i> Export CSV
        </a>
        @endif
        @if($user && $user->hasPermission('reports_download_pdf'))
        <a href="{{ url('report/export').'?type='.urlencode($type).'&period='.urlencode($period).(request('from')?'&from='.urlencode(request('from')):'').(request('to')?'&to='.urlencode(request('to')):'').'&format=pdf' }}" class="btn btn-outline-primary btn-pill mr-2">
          <i class="mdi mdi-file-pdf-box me-1"></i> Download PDF
        </a>
        @endif
        <a href="{{ route('cms.report.index') }}" class="btn btn-outline-secondary btn-pill">Back to Reports</a>
      </div>
    </div>
    <hr class="mt-2 mb-3 w-100">

    @if(!empty($kpis))
      <div class="row">
          @foreach($kpis as $label => $value)
          <div class="col-md-3 col-sm-6 mb-3">
              <div class="card card-default shadow-sm border-0 rounded-4">
                  <div class="d-flex justify-content-between align-items-center p-4">
                      <span class="h6 mb-0 text-capitalize">{{ str_replace('_',' ', $label) }}</span>
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
          <table class="table table-striped align-middle" id="reportRecordsTable">
            <thead>
              @if($type === 'Financial')
              <tr>
                <th>S.No</th>
                <th>Date</th>
                <th>Type</th>
                <th>Amount</th>
                <th>Program</th>
                <th>Family</th>
                <th>Recorded By</th>
                <th>Approved By</th>
              </tr>
              @elseif($type === 'Aid Programs')
              <tr>
                <th>S.No</th>
                <th>Program</th>
                <th>Status</th>
                <th>Families</th>
                <th>Volunteers</th>
                <th>Members Total</th>
                <th>Family Codes</th>
                <th>Volunteer Names</th>
                <th>Created</th>
              </tr>
              @elseif($type === 'Survey')
              <tr>
                <th>S.No</th>
                <th>Code</th>
                <th>Family</th>
                <th>Enumerator</th>
                <th>Members</th>
                <th>Uploads</th>
                <th>Created</th>
              </tr>
              @elseif($type === 'Volunteers')
              <tr>
                <th>S.No</th>
                <th>Name</th>
                <th>Email</th>
                <th>Contact</th>
                <th>Joining Date</th>
                <th>Status</th>
                <th>Updated</th>
              </tr>
              @else
              <tr>
                <th>S.No</th>
                <th>Family Code</th>
                <th>Head</th>
                <th>Members</th>
                <th>Volunteer</th>
                <th>Zakat Eligible</th>
                <th>Created</th>
              </tr>
              @endif
            </thead>
            <tbody>
              @forelse($rows as $row)
                @if($type === 'Financial')
                <tr>
                  <td>{{ (($rows->currentPage()-1) * $rows->perPage()) + $loop->iteration }}</td>
                  <td>{{ \Illuminate\Support\Carbon::parse($row->date)->format('Y-m-d') }}</td>
                  <td>{{ $row->transaction_type }}</td>
                  <td>{{ $row->amount }}</td>
                  <td>{{ optional($row->aidProgram)->program_title ?: '-' }}</td>
                  <td>{{ optional($row->family)->family_code ?: '-' }}</td>
                  <td>{{ optional($row->recordedBy)->name ?: '-' }}</td>
                  <td>{{ optional($row->approvedBy)->name ?: '-' }}</td>
                </tr>
                @elseif($type === 'Aid Programs')
                <tr>
                  <td>{{ (($rows->currentPage()-1) * $rows->perPage()) + $loop->iteration }}</td>
                  <td>{{ $row->program_title }}</td>
                  <td>{{ $row->status }}</td>
                  <td>{{ (int)($row->families_count ?? 0) }}</td>
                  <td>{{ (int)($row->volunteers_count ?? 0) }}</td>
                  <td>{{ (int) collect($row->families ?? [])->sum(function($f){ return (int) ($f->family_members_count ?? 0); }) }}</td>
                  <td>{{ collect($row->families ?? [])->pluck('family_code')->filter()->take(5)->join(', ') }}</td>
                  <td>{{ collect($row->volunteers ?? [])->pluck('full_name')->filter()->take(5)->join(', ') }}</td>
                  <td>{{ optional($row->created_at)->format('Y-m-d') }}</td>
                </tr>
                @elseif($type === 'Survey')
                <tr>
                  <td>{{ (($rows->currentPage()-1) * $rows->perPage()) + $loop->iteration }}</td>
                  <td>{{ $row->survey_code ?: ('S-'.$row->id) }}</td>
                  <td>{{ optional($row->family)->family_code ?: '-' }}</td>
                  <td>{{ optional($row->enumerator)->full_name ?: '-' }}</td>
                  <td>{{ $row->members_count }}</td>
                  <td>{{ $row->uploads_count }}</td>
                  <td>{{ optional($row->created_at)->format('Y-m-d') }}</td>
                </tr>
                @elseif($type === 'Volunteers')
                <tr>
                  <td>{{ (($rows->currentPage()-1) * $rows->perPage()) + $loop->iteration }}</td>
                  <td>{{ $row->full_name }}</td>
                  <td>{{ $row->email }}</td>
                  <td>{{ $row->contact_number }}</td>
                  <td>{{ optional($row->joining_date)->format('Y-m-d') }}</td>
                  <td>{{ $row->status }}</td>
                  <td>{{ optional($row->updated_at)->format('Y-m-d') }}</td>
                </tr>
                @else
                <tr>
                  <td>{{ (($rows->currentPage()-1) * $rows->perPage()) + $loop->iteration }}</td>
                  <td>{{ $row->family_code }}</td>
                  <td>{{ $row->head_of_family_name }}</td>
                  <td>{{ $row->members_count }}</td>
                  <td>{{ optional($row->volunteer)->full_name ?: '-' }}</td>
                  <td>{{ $row->zakat_eligibility ? 'Yes' : 'No' }}</td>
                  <td>{{ optional($row->created_at)->format('Y-m-d') }}</td>
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

@push('page-scripts')
<script>
(function($){
  $(function(){
    var $t = $('#reportRecordsTable');
    if ($t.length && $.fn.DataTable) {
      $t.DataTable({
        pageLength: 10,
        lengthChange: false,
        dom: 'frtip',
        ordering: true,
        order: [],
        searching: true,
        autoWidth: false,
        language: {
          search: 'Search:',
          info: 'Showing _START_ to _END_ of _TOTAL_ entries',
          paginate: { previous: 'Prev', next: 'Next' },
          zeroRecords: 'No matching records found'
        }
      });
    }
  });
})(jQuery);
</script>
@endpush
