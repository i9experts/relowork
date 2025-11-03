@extends('layouts.app')

@section('title', 'Aid Program Details')

@push('page-styles')
<style>
  .detail-label { color: #6c757d; font-weight: 600; }
</style>
@endpush

@section('content')
<div class="wrapper">
  <div class="content-wrapper">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <div>
        <h4 class="fw-bold text-dark mb-1">{{ $aidProgram->program_title }}</h4>
        <p class="text-muted mb-0">Code: {{ $aidProgram->program_code }} · {{ $aidProgram->status }}</p>
      </div>
      <div class="d-flex gap-2">
        <a href="{{ route('cms.aid-program.edit', $aidProgram->id) }}" class="btn btn-primary mr-2">Edit</a>
        <form method="POST" action="{{ route('cms.aid-program.destroy', $aidProgram->id) }}" onsubmit="return confirm('Are you sure you want to delete this program?');">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-outline-danger">Delete</button>
        </form>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-8 mb-3">
        <div class="card shadow-sm border-0 rounded-4">
          <div class="card-body p-4">
            @php
              $budget = (int)($aidProgram->total_budget ?? 0);
              $disbursed = (int)($aidProgram->disbursed_amount ?? 0);
              $pct = $budget > 0 ? min(100, (int) round(($disbursed / $budget) * 100)) : 0;
            @endphp
            <div class="d-flex justify-content-between align-items-center mb-2">
              <div>
                <span class="detail-label d-block">Budget</span>
                <h6 class="mb-0 fw-bold">PKR {{ number_format($budget) }}</h6>
              </div>
              <div class="text-end">
                <span class="detail-label d-block">Disbursed</span>
                <h6 class="mb-0 fw-bold">PKR {{ number_format($disbursed) }}</h6>
              </div>
            </div>
            <div class="progress mb-3">
              <div class="progress-bar" role="progressbar" style="width: {{ $pct }}%" aria-valuenow="{{ $pct }}" aria-valuemin="0" aria-valuemax="100">{{ $pct }}%</div>
            </div>

            <div class="row g-3">
              <div class="col-md-6">
                <span class="detail-label d-block">Type</span>
                <div>{{ $aidProgram->program_type ?? '—' }}</div>
              </div>
              <div class="col-md-6">
                <span class="detail-label d-block">Fund Source</span>
                <div>{{ $aidProgram->fund_source ?? '—' }}</div>
              </div>
              <div class="col-md-6">
                <span class="detail-label d-block">Dates</span>
                <div>{{ $aidProgram->start_date ?? '—' }} — {{ $aidProgram->end_date ?? '—' }}</div>
              </div>
              <div class="col-md-6">
                <span class="detail-label d-block">Location</span>
                <div>{{ $aidProgram->location ?? '—' }}</div>
              </div>
              <div class="col-12">
                <span class="detail-label d-block">Description</span>
                <div>{{ $aidProgram->description ?? '—' }}</div>
              </div>
              <div class="col-12">
                <span class="detail-label d-block">Progress Notes</span>
                <div>{{ $aidProgram->progress_notes ?? '—' }}</div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-4 mb-3">
        <div class="card shadow-sm border-0 rounded-4 mb-3">
          <div class="card-body p-4">
            <span class="detail-label d-block">Counts</span>
            <div class="d-flex justify-content-between mt-2">
              <div>Families</div>
              <div class="fw-bold">{{ $aidProgram->families_count }}</div>
            </div>
            <div class="d-flex justify-content-between">
              <div>Volunteers</div>
              <div class="fw-bold">{{ $aidProgram->volunteers_count }}</div>
            </div>
            <div class="d-flex justify-content-between">
              <div>Attachments</div>
              <div class="fw-bold">{{ $aidProgram->attachments_count }}</div>
            </div>
          </div>
        </div>

        <div class="card shadow-sm border-0 rounded-4">
          <div class="card-body p-4">
            <a href="{{ route('cms.aid-program.index') }}" class="btn btn-outline-secondary w-100">Back to List</a>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-6 mb-3">
        <div class="card shadow-sm border-0 rounded-4">
          <div class="card-body p-4">
            <h6 class="fw-bold mb-3">Families</h6>
            @if($aidProgram->families->isEmpty())
              <div class="text-muted">No families linked.</div>
            @else
              <ul class="mb-0">
                @foreach($aidProgram->families as $fam)
                  <li>{{ $fam->family_code }} — {{ $fam->head_of_family_name }}</li>
                @endforeach
              </ul>
            @endif
          </div>
        </div>
      </div>
      <div class="col-lg-6 mb-3">
        <div class="card shadow-sm border-0 rounded-4">
          <div class="card-body p-4">
            <h6 class="fw-bold mb-3">Volunteers</h6>
            @if($aidProgram->volunteers->isEmpty())
              <div class="text-muted">No volunteers linked.</div>
            @else
              <ul class="mb-0">
                @foreach($aidProgram->volunteers as $vol)
                  <li>{{ $vol->full_name }}</li>
                @endforeach
              </ul>
            @endif
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-12 mb-3">
        <div class="card shadow-sm border-0 rounded-4">
          <div class="card-body p-4">
            <h6 class="fw-bold mb-3">Attachments</h6>
            @if($aidProgram->attachments->isEmpty())
              <div class="text-muted">No attachments uploaded.</div>
            @else
              <div class="row">
                @foreach($aidProgram->attachments as $att)
                  <div class="col-md-3 mb-3">
                    <div class="border rounded p-2 text-center">
                      @php 
                        $isImage = isset($att->type) && str_starts_with($att->type, 'image/');
                        $p = $att->path ?? '';
                        if (str_starts_with($p, 'public/')) { $p = substr($p, 7); }
                        $url = Storage::disk('public')->url($p);
                      @endphp
                      @if($isImage)
                        <a href="{{ $url }}" target="_blank">
                          <img src="{{ $url }}" alt="{{ $att->original_name }}" class="img-fluid rounded" style="max-height: 160px; object-fit: cover;">
                        </a>
                        <div class="small text-muted mt-1 text-truncate">{{ $att->original_name }}</div>
                      @else
                        <div class="small text-muted">{{ $att->original_name }}</div>
                        <a href="{{ $url }}" target="_blank" class="d-inline-block mt-1">View</a>
                      @endif
                    </div>
                  </div>
                @endforeach
              </div>
            @endif
          </div>
        </div>
      </div>
    </div>

  </div>
</div>
@endsection

@push('page-scripts')
@endpush
