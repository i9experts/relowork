@extends('layouts.app')

@section('title', 'Survey Detail')

@section('content')
<div class="wrapper">
  <div class="content-wrapper">
    <div class="d-flex justify-content-between align-items-center mb-2">
      <div>
        <h4 class="fw-bold mb-1">Survey: {{ $survey->survey_code ?? ('#'.$survey->id) }}</h4>
        <p class="text-muted mb-0">Full details and attachments</p>
      </div>
      <div>
        <a href="{{ route('cms.survey.edit', $survey->id) }}" class="btn btn-outline-primary mr-2">Edit</a>
        <a href="{{ route('cms.survey.index') }}" class="btn btn-secondary">Back</a>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-12 mb-3">
        <div class="card shadow-sm">
          <div class="card-header"><strong>Overview</strong></div>
          <div class="card-body">
            <div class="row">
              <div class="col-6">
                <div class="d-flex justify-content-between">
                  <div class="text-muted">Status</div>
                  <div>
                    @if($survey->revisit_flag)
                      <span class="badge badge-success">Active</span>
                    @else
                      <span class="badge badge-light">Completed</span>
                    @endif
                  </div>
                </div>
              </div>
              <div class="col-6">
                <div class="d-flex justify-content-between">
                  <div class="text-muted">Mode</div>
                  <div>{{ $survey->survey_mode ?? '-' }}</div>
                </div>
              </div>
              <div class="col-6 mt-2">
                <div class="d-flex justify-content-between">
                  <div class="text-muted">Created</div>
                  <div>{{ optional($survey->created_at)->format('Y-m-d H:i') }}</div>
                </div>
              </div>
              <div class="col-6 mt-2">
                <div class="d-flex justify-content-between">
                  <div class="text-muted">Survey Time</div>
                  <div>{{ optional($survey->date_time)->format('Y-m-d H:i') }}</div>
                </div>
              </div>
              <div class="col-6 mt-2">
                <div class="d-flex justify-content-between">
                  <div class="text-muted">Revisit From</div>
                  <div>{{ $survey->revisit_from ? \Illuminate\Support\Carbon::parse($survey->revisit_from)->format('Y-m-d') : '-' }}</div>
                </div>
              </div>
              <div class="col-6 mt-2">
                <div class="d-flex justify-content-between">
                  <div class="text-muted">Deadline</div>
                  @php $deadline = $survey->revisit_to ?: data_get($survey->flags_json,'revisit_to'); @endphp
                  <div>{{ $deadline ? \Illuminate\Support\Carbon::parse($deadline)->format('Y-m-d') : '-' }}</div>
                </div>
              </div>
              <div class="col-6 mt-2">
                <div class="d-flex justify-content-between">
                  <div class="text-muted">Enumerator</div>
                  <div>{{ optional($survey->enumerator)->full_name ?? optional($survey->enumerator)->name ?? '-' }}</div>
                </div>
              </div>
              <div class="col-6 mt-2">
                <div class="d-flex justify-content-between">
                  <div class="text-muted">Family</div>
                  <div>{{ optional($survey->family)->family_name ?? optional($survey->family)->head_of_family_name ?? (optional($survey->family)->id ? '#'.optional($survey->family)->id : '-') }}</div>
                </div>
              </div>
              <div class="col-6 mt-2">
                <div class="d-flex justify-content-between">
                  <div class="text-muted">Consent Type</div>
                  <div>{{ $survey->consent_type ?? '-' }}</div>
                </div>
              </div>
              <div class="col-6 mt-2">
                <div class="d-flex justify-content-between">
                  <div class="text-muted">GPS</div>
                  <div>
                    @if(!is_null($survey->lat) && !is_null($survey->lon))
                      {{ $survey->lat }}, {{ $survey->lon }}
                    @else
                      -
                    @endif
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-12 mb-3">
        <div class="card shadow-sm">
          <div class="card-header"><strong>Household & Flags</strong></div>
          <div class="card-body">
            @php $f = $survey->flags_json ?? []; @endphp
            <div class="row">
              <div class="col-6 mb-2"><div class="d-flex justify-content-between"><div class="text-muted">Head of Family</div><div>{{ data_get($f,'head_of_family','-') }}</div></div></div>
              <div class="col-6 mb-2"><div class="d-flex justify-content-between"><div class="text-muted">CNIC</div><div>{{ data_get($f,'survey_cnic','-') }}</div></div></div>
              <div class="col-6 mb-2"><div class="d-flex justify-content-between"><div class="text-muted">Household Size</div><div>{{ data_get($f,'household_size','-') }}</div></div></div>
              <div class="col-12 mb-2"><div class="d-flex justify-content-between"><div class="text-muted">Address</div><div>{{ data_get($f,'address_full','-') }}</div></div></div>
              <div class="col-6 mb-2"><div class="d-flex justify-content-between"><div class="text-muted">Province</div><div>{{ data_get($f,'province','-') }}</div></div></div>
              <div class="col-6 mb-2"><div class="d-flex justify-content-between"><div class="text-muted">District</div><div>{{ data_get($f,'district','-') }}</div></div></div>
              <div class="col-6 mb-2"><div class="d-flex justify-content-between"><div class="text-muted">Tehsil</div><div>{{ data_get($f,'tehsil','-') }}</div></div></div>
              <div class="col-6 mb-2"><div class="d-flex justify-content-between"><div class="text-muted">UC</div><div>{{ data_get($f,'uc','-') }}</div></div></div>
              <div class="col-6 mb-2"><div class="d-flex justify-content-between"><div class="text-muted">Settlement Type</div><div>{{ data_get($f,'settlement_type','-') }}</div></div></div>
              <div class="col-6 mb-2"><div class="d-flex justify-content-between"><div class="text-muted">Tenure Type</div><div>{{ data_get($f,'tenure_type','-') }}</div></div></div>
              <div class="col-6 mb-2"><div class="d-flex justify-content-between"><div class="text-muted">Displacement</div><div>{{ data_get($f,'displacement_status','-') }}</div></div></div>
              <div class="col-6 mb-2"><div class="d-flex justify-content-between"><div class="text-muted">Primary Languages</div><div>{{ collect(data_get($f,'primary_languages',[]))->filter()->implode(', ') ?: '-' }}</div></div></div>
              <div class="col-6 mb-2"><div class="d-flex justify-content-between"><div class="text-muted">Primary Income</div><div>{{ data_get($f,'primary_income','-') }}</div></div></div>
              <div class="col-6 mb-2"><div class="d-flex justify-content-between"><div class="text-muted">Monthly Income</div><div>{{ data_get($f,'monthly_income','-') }}</div></div></div>
              <div class="col-6 mb-2"><div class="d-flex justify-content-between"><div class="text-muted">Monthly Expenses</div><div>{{ data_get($f,'monthly_expenses','-') }}</div></div></div>
              <div class="col-6 mb-2"><div class="d-flex justify-content-between"><div class="text-muted">Debt Outstanding</div><div>{{ data_get($f,'debt_outstanding','-') }}</div></div></div>
              <div class="col-6 mb-2"><div class="d-flex justify-content-between"><div class="text-muted">Debt Source</div><div>{{ data_get($f,'debt_source','-') }}</div></div></div>
              <div class="col-6 mb-2"><div class="d-flex justify-content-between"><div class="text-muted">Banking Access</div><div>{{ data_get($f,'banking_access','-') }}</div></div></div>
              <div class="col-6 mb-2"><div class="d-flex justify-content-between"><div class="text-muted">Digital Literacy</div><div>{{ data_get($f,'digital_literacy','-') }}</div></div></div>
              <div class="col-6 mb-2"><div class="d-flex justify-content-between"><div class="text-muted">Female-led</div><div>{{ data_get($f,'female_led','-') }}</div></div></div>
              <div class="col-6 mb-2"><div class="d-flex justify-content-between"><div class="text-muted">PWD Count</div><div>{{ data_get($f,'pwd_count','-') }}</div></div></div>
              <div class="col-6 mb-2"><div class="d-flex justify-content-between"><div class="text-muted">Meals / day</div><div>{{ data_get($f,'meals_per_day','-') }}</div></div></div>
              <div class="col-6 mb-2"><div class="d-flex justify-content-between"><div class="text-muted">Food stocks (days)</div><div>{{ data_get($f,'food_stocks','-') }}</div></div></div>
              <div class="col-6 mb-2"><div class="d-flex justify-content-between"><div class="text-muted">Child malnutrition</div><div>{{ data_get($f,'child_maln','-') }}</div></div></div>
              <div class="col-6 mb-2"><div class="d-flex justify-content-between"><div class="text-muted">Food needs</div><div>{{ collect(data_get($f,'food_needs',[]))->filter()->implode(', ') ?: '-' }}</div></div></div>
              <div class="col-6 mb-2"><div class="d-flex justify-content-between"><div class="text-muted">Water source</div><div>{{ data_get($f,'water_source','-') }}</div></div></div>
              <div class="col-6 mb-2"><div class="d-flex justify-content-between"><div class="text-muted">Water distance</div><div>{{ data_get($f,'water_distance','-') }}</div></div></div>
              <div class="col-6 mb-2"><div class="d-flex justify-content-between"><div class="text-muted">Water quality issue</div><div>{{ data_get($f,'water_quality','-') }}</div></div></div>
              <div class="col-6 mb-2"><div class="d-flex justify-content-between"><div class="text-muted">Received aid (6m)</div><div>{{ data_get($f,'received_aid_6m','-') }}</div></div></div>
              <div class="col-12 mb-2"><div class="d-flex justify-content-between"><div class="text-muted">Aid details</div><div>{{ data_get($f,'aid_details','-') }}</div></div></div>
              <div class="col-6 mb-2"><div class="d-flex justify-content-between"><div class="text-muted">Purpose explained</div><div>{{ data_get($f,'purpose_explained') ? 'Yes' : 'No' }}</div></div></div>
              <div class="col-6 mb-2"><div class="d-flex justify-content-between"><div class="text-muted">Consent statement</div><div>{{ data_get($f,'consent_statement') ? 'Yes' : 'No' }}</div></div></div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-12 mb-3">
        <div class="card shadow-sm">
          <div class="card-header"><strong>Members ({{ $survey->members_count ?? $survey->members->count() }})</strong></div>
          <div class="card-body">
            @if($survey->members->count())
              <div class="table-responsive">
                <table class="table table-sm">
                  <thead><tr><th>Name</th><th>Age</th><th>Gender</th><th>Relation</th><th>CNIC</th><th>School/Work</th><th>Disability</th></tr></thead>
                  <tbody>
                  @foreach($survey->members as $m)
                    <tr>
                      <td>{{ $m->name }}</td>
                      <td>{{ $m->age }}</td>
                      <td>{{ $m->gender }}</td>
                      <td>{{ $m->relation }}</td>
                      <td>{{ $m->cnic }}</td>
                      <td>{{ $m->school_work }}</td>
                      <td>{{ $m->disability }}</td>
                    </tr>
                  @endforeach
                  </tbody>
                </table>
              </div>
            @else
              <div class="text-muted">No members added.</div>
            @endif
          </div>
        </div>
      </div>

      <div class="col-lg-12 mb-3">
        <div class="card shadow-sm">
          <div class="card-header"><strong>Assets</strong></div>
          <div class="card-body">
            @if($survey->assets->count())
              <ul class="mb-0">
                @foreach($survey->assets as $a)
                  <li>{{ $a->asset }} @if(!$a->present)(not present)@endif</li>
                @endforeach
              </ul>
            @else
              <div class="text-muted">No assets recorded.</div>
            @endif
          </div>
        </div>
      </div>
      
      <div class="col-lg-12 mb-3">
        <div class="card shadow-sm">
          <div class="card-header"><strong>Uploads</strong></div>
          <div class="card-body">
            @php $consent = $survey->uploads->firstWhere('type','consent'); @endphp
            <div class="mb-3">
              <div class="small text-muted mb-1">Consent</div>
              @if($consent)
                @php $ext = strtolower(pathinfo($consent->url, PATHINFO_EXTENSION)); @endphp
                @if(in_array($ext, ['jpg','jpeg','png','gif','webp']))
                  <img src="{{ '/storage/'.$consent->url }}" class="img-fluid rounded border" alt="consent">
                @elseif(in_array($ext, ['mp3','m4a','wav','ogg']))
                  <audio controls class="w-100" src="{{ '/storage/'.$consent->url }}"></audio>
                @else
                  <a href="{{ '/storage/'.$consent->url }}" target="_blank">Open consent file</a>
                @endif
              @else
                <div class="border rounded p-3 text-center text-muted">Proof Img</div>
              @endif
            </div>

            @php $otherUploads = $survey->uploads->where('type','!=','consent'); @endphp
            @if($otherUploads->count())
              <div class="row">
                @foreach($otherUploads as $u)
                  <div class="col-md-6 mb-3">
                    <div class="border rounded p-2 h-100">
                      <div class="small text-muted mb-1">{{ strtoupper($u->type) }}</div>
                      @if($u->type === 'photo')
                        <img src="{{ '/storage/'.$u->url }}" class="img-fluid rounded mt-1" alt="photo">
                      @elseif($u->type === 'audio')
                        <audio controls class="w-100 mt-1" src="{{ '/storage/'.$u->url }}"></audio>
                      @else
                        <a href="{{ '/storage/'.$u->url }}" target="_blank">View file</a>
                      @endif
                    </div>
                  </div>
                @endforeach
              </div>
            @else
              <div class="text-muted">No other uploads.</div>
            @endif
          </div>
        </div>
      </div>

      <div class="col-lg-12 mb-3">
        <div class="card shadow-sm">
          <div class="card-header"><strong>Needs & Outcomes</strong></div>
          <div class="card-body">
            <div class="mb-3">
              <div class="text-muted mb-1">Needs</div>
              @if($survey->needs->count())
                <ul class="mb-0">
                  @foreach($survey->needs as $n)
                    <li>{{ $n->need ?? json_encode($n->toArray()) }}</li>
                  @endforeach
                </ul>
              @else
                <div class="text-muted">No needs recorded.</div>
              @endif
            </div>
            <div>
              <div class="text-muted mb-1">Outcomes</div>
              @if($survey->outcomes->count())
                <ul class="mb-0">
                  @foreach($survey->outcomes as $o)
                    <li>{{ $o->outcome ?? json_encode($o->toArray()) }}</li>
                  @endforeach
                </ul>
              @else
                <div class="text-muted">No outcomes recorded.</div>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>
@endsection