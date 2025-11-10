@extends('layouts.app')

@section('title', 'Survey Needs Assessment Form')

@push('page-styles')
<link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
<style>
  .row > [class*='col-'] { display:flex; }
  .card { flex:1 1 auto; }
  .card-header p.small { font-size:0.85rem; color:#6c757d; margin-top:4px; }
  .member-row { border:1px dashed #e3e3e3; padding:10px; margin-bottom:8px; border-radius:6px; background:#fafafa; }
  .small-muted { font-size:0.85rem; color:#6c757d; }
  .required { color: #d9534f; }
</style>
@endpush

@section('content')
<div class="wrapper">
  <div class="content-wrapper">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <div class="w-100 mb-3">
                @include('partials.errors')
            </div>  
            <h4 class="card-title fw-bold mb-1">Edit Survey / Needs-Assessment Form</h4>
            <p class="text-muted mb-4">Purpose: Planning & projections — capture household & multi-sector needs, geo, consent and media.</p>
            <p class="text-muted mb-3 small">
              @php
                $familyName = optional($survey->family)->family_name ?? optional($survey->family)->head_of_family_name;
                $familyId = optional($survey->family)->id;
                $enumName = optional($survey->enumerator)->full_name ?? optional($survey->enumerator)->name;
                $enumId = optional($survey->enumerator)->id;
              @endphp
              @if($survey->family)
                Family name & Id : {{ $familyName ?? '-' }} | {{ $familyId ? '#'.$familyId : '-' }}
              @endif
              @if($survey->enumerator)
                {{ $survey->family ? ' • ' : '' }}Volunteer Name & Id : {{ $enumName ?? '-' }} | {{ $enumId ? '#'.$enumId : '-' }}
              @endif
            </p>

            <form method="POST" action="{{ route('cms.survey.update', $survey->id) }}" enctype="multipart/form-data" id="surveyForm">
              @csrf
              @method('PUT')
              <div class="form-row">

                <!-- A) Survey Metadata -->
                <div class="col-md-12 mt-3">
                    <hr>
                    <p class="text-center fw-bold mb-3">A) Survey Metadata</p>
                    <hr>
                </div> 

                <div class="col-md-6 mb-3">
                  <label for="enumerator_id">Volunteer</label>
                  <select id="enumerator_id" name="enumerator_id" class="form-control select2 rounded-0 bg-light" style="width:100%;">
                    <option disabled {{ $survey->enumerator_id ? '' : 'selected' }}>Select Volunteer</option>
                    @foreach($volunteers as $vol)
                    <option value="{{ $vol->id }}" {{ (string)($survey->enumerator_id) === (string)$vol->id ? 'selected' : '' }}>{{ $vol->volunteer_code ?? '' }} - {{ $vol->full_name }}</option>
                    @endforeach
                  </select>
                </div>

                <div class="col-md-6 mb-3">
                  <label for="survey_mode">Survey Mode</label>
                  <select id="survey_mode" name="survey_mode" class="form-control select2 rounded-0 bg-light" style="width:100%;">
                    <option disabled {{ $survey->survey_mode ? '' : 'selected' }}>Select Mode</option>
                    <option value="In-person" {{ $survey->survey_mode==='In-person' ? 'selected' : '' }}>In-person</option>
                    <option value="Phone" {{ $survey->survey_mode==='Phone' ? 'selected' : '' }}>Phone</option>
                    <option value="WhatsApp" {{ $survey->survey_mode==='WhatsApp' ? 'selected' : '' }}>WhatsApp</option>
                    <option value="Web" {{ $survey->survey_mode==='Web' ? 'selected' : '' }}>Web</option>
                  </select>
                </div>

                <div class="col-md-6 mb-3">
                  <label for="datetime">Date & Time</label>
                  <input type="datetime-local" id="datetime" name="datetime" class="form-control rounded-0 bg-light" value="{{ optional($survey->date_time)->format('Y-m-d\\TH:i') }}">
                </div>

                <div class="col-md-6 mb-3">
                  <label>GPS Location <small class="small-muted"> (click to capture)</small></label>
                  <div class="input-group">
                    <input type="text" id="gps_lat" name="gps_lat" class="form-control rounded-0 bg-light mr-2" placeholder="Latitude" value="{{ $survey->lat }}" readonly>
                    <input type="text" id="gps_long" name="gps_long" class="form-control rounded-0 bg-light mr-2" placeholder="Longitude" value="{{ $survey->lon }}" readonly>
                    <button class="btn btn-outline-secondary" type="button" id="btnCaptureGeo">Capture</button>
                  </div>
                  <small id="geoAccuracy" class="small-muted"></small>
                </div>

                <div class="col-md-6 mb-3">
                  <label for="photos">Photo Proof / Site Images <span class="required">*</span></label>
                  <input type="file" id="photos" name="photos[]" accept="image/*" multiple class="form-control rounded-0 bg-light">
                  <small class="small-muted">Minimum 1 photo required. (jpg/png)</small>
                  @php $existingPhotos = $survey->uploads->where('type','photo'); @endphp
                  @if($existingPhotos->count())
                  <div class="row mt-2">
                    @foreach($existingPhotos as $ph)
                      <div class="col-4 mb-2">
                        <img src="{{ '/storage/'.$ph->url }}" alt="photo" class="img-fluid rounded border">
                      </div>
                    @endforeach
                  </div>
                  @endif
                  <div id="photosPreview" class="row mt-2"></div>
                </div>

                <div class="col-md-6 mb-3">
                  <label for="audio_note">Audio Note (optional)</label>
                  <input type="file" id="audio_note" name="audio_note" accept="audio/*" class="form-control rounded-0 bg-light">
                  <small class="small-muted">Recommended 60–180 sec. We will warn if duration > 180s</small>
                  <br>
                  <small id="audioWarning" class="small-muted" style="color:#d9534f;"></small>
                  @php $existingAudios = $survey->uploads->where('type','audio'); @endphp
                  @if($existingAudios->count())
                  <div class="mt-2">
                    @foreach($existingAudios as $au)
                      <audio controls class="w-100 mb-2" src="{{ '/storage/'.$au->url }}"></audio>
                    @endforeach
                  </div>
                  @endif
                  <div id="audioPreview" class="mt-2"></div>
                </div>

                <div class="col-md-6 mb-3">
                <label for="consent_captured" class="form-label mb-0 mr-2">Consent Captured?</label>
                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="consent_captured" name="consent_captured" {{ $survey->consent_type ? 'checked' : '' }}>
                    <label class="custom-control-label" for="consent_captured">Yes</label>
                  </div>

                  <div id="consentDetails" style="display:none; margin-top:8px;">
                    <select id="consent_type" name="consent_type" class="form-control select2 rounded-0 bg-light" style="width:100%;">
                      <option disabled {{ $survey->consent_type ? '' : 'selected' }}>Consent Type</option>
                      <option value="Text" {{ $survey->consent_type==='Text' ? 'selected' : '' }}>Text</option>
                      <option value="Voice" {{ $survey->consent_type==='Voice' ? 'selected' : '' }}>Voice</option>
                      <option value="Signature Photo" {{ $survey->consent_type==='Signature Photo' ? 'selected' : '' }}>Signature Photo</option>
                    </select>
                    <div class="mt-2">
                      <input type="file" id="consent_attachment" name="consent_attachment" class="form-control rounded-0 bg-light">
                      <small class="small-muted">If voice/signature/photo selected, upload proof.</small>
                      @php $existingConsent = $survey->uploads->firstWhere('type','consent'); @endphp
                      @if($existingConsent)
                      <div class="mt-2">
                        @php $ext = strtolower(pathinfo($existingConsent->url, PATHINFO_EXTENSION)); @endphp
                        @if(in_array($ext, ['mp3','wav','ogg','m4a']))
                          <audio controls class="w-100" src="{{ '/storage/'.$existingConsent->url }}"></audio>
                        @else
                          <img src="{{ '/storage/'.$existingConsent->url }}" alt="consent" class="img-fluid rounded border">
                        @endif
                      </div>
                      @endif
                      <div id="consentPreviewNew" class="mt-2"></div>
                    </div>
                  </div>
                </div>

                <div class="col-md-6 mb-3">
                  <label>Revisit Needed?</label>

                  <div class="custom-control custom-radio d-inline-block mr-3 mb-3">
                    <input type="radio" id="revisit_yes" name="revisit_needed" class="custom-control-input" value="yes" {{ $survey->revisit_flag ? 'checked' : '' }}>
                    <label class="custom-control-label" for="revisit_yes">Yes</label>
                  </div>

                  <div class="custom-control custom-radio d-inline-block mr-3 mb-3">
                    <input type="radio" id="revisit_no" name="revisit_needed" class="custom-control-input" value="no" {{ $survey->revisit_flag ? '' : 'checked' }}>
                    <label class="custom-control-label" for="revisit_no">No</label>
                  </div>

                  <div id="revisitBlock" style="display: {{ $survey->revisit_flag ? 'block' : 'none' }}; margin-top:8px;">
                    <input type="text" name="revisit_reason" class="form-control rounded-0 bg-light mb-2" placeholder="Reason for revisit" value="{{ data_get($survey->flags_json,'revisit_reason') }}">
                    <div class="d-flex gap-2">
                      <input type="date" name="revisit_from" class="form-control rounded-0 bg-light" value="{{ optional($survey->revisit_from)->format('Y-m-d') }}">
                      <input type="date" name="revisit_to" class="form-control rounded-0 bg-light" value="{{ optional($survey->revisit_to)->format('Y-m-d') }}">
                    </div>
                  </div>
                </div>

                <!-- B) Household & Location Profile -->
                <div class="col-md-12 mt-3">
                    <hr>
                    <p class="text-center fw-bold mb-3">B) Household & Location Profile</p>
                    <hr>
                </div> 

                <div class="col-md-6 mb-3">
                  <label for="family_id">Family</label>
                  <select id="family_id" name="family_id" class="form-control select2 rounded-0 bg-light" style="width:100%;">
                    <option value="new" {{ $survey->family_id ? '' : 'selected' }}>New family</option>
                    @foreach($families as $fam)
                    <option value="{{ $fam->id }}" {{ (string)$survey->family_id === (string)$fam->id ? 'selected' : '' }}>{{ $fam->family_code ?? '' }} - {{ $fam->head_of_family_name }}</option>
                    @endforeach
                  </select>
                </div>

                <div class="col-md-6 mb-3">
                  <label for="head_of_family">Head of Family (Name)</label>
                  <input type="text" id="head_of_family" name="head_of_family" class="form-control rounded-0 bg-light" value="{{ data_get($survey->flags_json,'head_of_family') }}">
                </div>

                <div class="col-md-6 mb-3">
                  <label for="survey_cnic">CNIC / National ID</label>
                  <input type="text" id="survey_cnic" name="survey_cnic" class="form-control rounded-0 bg-light" placeholder="42201-1234567-9" value="{{ data_get($survey->flags_json,'survey_cnic') }}">
                </div>

                <div class="col-md-6 mb-3">
                  <label for="household_size">Household Size</label>
                  <input type="number" id="household_size" name="household_size" class="form-control rounded-0 bg-light" min="1" value="{{ data_get($survey->flags_json,'household_size') }}">
                </div>

                <!-- Gender -->
                @php $genderVal = old('gender', data_get($survey->flags_json,'gender','Male')); @endphp
                <div class="col-md-6 mb-3">
                  <label for="gender" class="d-block mb-2">Gender :</label>
                  <div class="custom-control custom-radio d-inline-block mr-3 mb-2">
                    <input type="radio" id="genderMale" name="gender" class="custom-control-input" value="Male" {{ $genderVal=='Male' ? 'checked' : '' }}>
                    <label class="custom-control-label" for="genderMale">Male</label>
                  </div>
                  <div class="custom-control custom-radio d-inline-block mr-3 mb-2">
                    <input type="radio" id="genderFemale" name="gender" class="custom-control-input" value="Female" {{ $genderVal=='Female' ? 'checked' : '' }}>
                    <label class="custom-control-label" for="genderFemale">Female</label>
                  </div>
                </div>

                <!-- Age -->
                <div class="col-md-6 mb-3">
                  <label for="age">Age</label>
                  <input type="number" class="form-control rounded-0 bg-light" id="age" placeholder="Enter Age" name="age" value="{{ old('age', data_get($survey->flags_json,'age')) }}" min="0">
                </div>

                <!-- Marital Status -->
                @php $msVal = old('marital_status', data_get($survey->flags_json,'marital_status')); @endphp
                <div class="col-md-6 mb-3">
                  <label for="marital_status">Marital Status</label>
                  <select class="form-control rounded-0 bg-light select2" id="marital_status" name="marital_status" style="width: 100%;">
                    <option disabled {{ $msVal ? '' : 'selected' }}>Select Marital Status</option>
                    <option value="Married" {{ $msVal=='Married'?'selected':'' }}>Married</option>
                    <option value="Widowed" {{ $msVal=='Widowed'?'selected':'' }}>Widowed</option>
                    <option value="Divorced" {{ $msVal=='Divorced'?'selected':'' }}>Divorced</option>
                    <option value="Single" {{ $msVal=='Single'?'selected':'' }}>Single</option>
                  </select>
                </div>

                <!-- Contact Number -->
                <div class="col-md-6 mb-3">
                  <label for="contact_number">Contact Number</label>
                  <input type="text" class="form-control rounded-0 bg-light" id="contact_number" placeholder="+92-300-1234567" name="contact_number" value="{{ old('contact_number', data_get($survey->flags_json,'contact_number')) }}">
                </div>

                <!-- Members detail (repeatable) -->
                <div class="col-md-12 mb-3">
                  <label>Members Detail</label>
                  <div id="membersList">
                    @foreach($survey->members as $i => $m)
                      <div class="member-row">
                        <div class="d-flex justify-content-between">
                          <strong>Member</strong>
                        </div>
                        <div class="row mt-2">
                          <div class="col-md-3 mb-2"><input name="members[{{ $i }}][name]" class="form-control rounded-0 bg-light" placeholder="Name" value="{{ $m->name }}"></div>
                          <div class="col-md-3 mb-2"><input name="members[{{ $i }}][age]" type="number" class="form-control rounded-0 bg-light" placeholder="Age" value="{{ $m->age }}"></div>
                          <div class="col-md-3 mb-2">
                            <select name="members[{{ $i }}][gender]" class="form-control select2 rounded-0 bg-light" style="width:100%;">
                              <option {{ $m->gender==='Male' ? 'selected' : '' }}>Male</option>
                              <option {{ $m->gender==='Female' ? 'selected' : '' }}>Female</option>
                              <option {{ $m->gender==='Other' ? 'selected' : '' }}>Other</option>
                            </select>
                          </div>
                          <div class="col-md-3 mb-2"><input name="members[{{ $i }}][relation]" class="form-control rounded-0 bg-light" placeholder="Relation" value="{{ $m->relation }}"></div>
                          <div class="col-md-3 mb-2"><input name="members[{{ $i }}][cnic]" class="form-control rounded-0 bg-light" placeholder="CNIC (if 18+)" value="{{ $m->cnic }}"></div>
                          <div class="col-md-3 mb-2">
                            <input name="members[{{ $i }}][disability]" class="form-control rounded-0 bg-light" placeholder="Disability?" value="{{ $m->disability }}">
                          </div>
                          <div class="col-md-3 mb-2"><input name="members[{{ $i }}][school_work]" class="form-control rounded-0 bg-light" placeholder="School/Work status" value="{{ $m->school_work }}"></div>
                          <div class="col-md-3 mb-2 text-end">
                            <button type="button" class="btn btn-outline-danger btn-sm removeMemberBtn"><i class="mdi mdi-delete"></i> Remove</button>
                          </div>
                        </div>
                      </div>
                    @endforeach
                  </div>
                  <button type="button" id="addMemberBtn" class="btn btn-outline-secondary btn-sm mt-1"><i class="mdi mdi-plus mr-1"></i> Add Member</button>
                </div>

                <div class="col-md-6 mb-3">
                  <label for="address_full">Address</label>
                  <textarea id="address_full" name="address_full" class="form-control rounded-0 bg-light" rows="2">{{ data_get($survey->flags_json,'address_full') }}</textarea>
                </div>

                <div class="col-md-6 mb-3">
                  <label for="province">Province</label>
                  <select id="province" name="province" class="form-control select2 rounded-0 bg-light" style="width:100%;">
                    <option disabled {{ data_get($survey->flags_json,'province') ? '' : 'selected' }}>Select Province</option>
                    @php $pv = data_get($survey->flags_json,'province'); @endphp
                    <option {{ $pv==='Punjab' ? 'selected' : '' }}>Punjab</option><option {{ $pv==='Sindh' ? 'selected' : '' }}>Sindh</option><option {{ $pv==='Khyber Pakhtunkhwa' ? 'selected' : '' }}>Khyber Pakhtunkhwa</option><option {{ $pv==='Balochistan' ? 'selected' : '' }}>Balochistan</option>
                  </select>
                </div>

                <div class="col-md-6 mb-3">
                  <label for="district">District</label>
                  <input type="text" id="district" name="district" class="form-control rounded-0 bg-light" value="{{ data_get($survey->flags_json,'district') }}">
                </div>

                <div class="col-md-6 mb-3">
                  <label for="tehsil">Tehsil</label>
                  <input type="text" id="tehsil" name="tehsil" class="form-control rounded-0 bg-light" value="{{ data_get($survey->flags_json,'tehsil') }}">
                </div>

                <div class="col-md-6 mb-3">
                  <label for="uc">Union Council</label>
                  <input type="text" id="uc" name="uc" class="form-control rounded-0 bg-light" value="{{ data_get($survey->flags_json,'uc') }}">
                </div>

                <div class="col-md-6 mb-3">
                  <label for="settlement_type">Settlement Type</label>
                  <select id="settlement_type" name="settlement_type" class="form-control select2 rounded-0 bg-light" style="width:100%;">
                    <option disabled {{ data_get($survey->flags_json,'settlement_type') ? '' : 'selected' }}>Select</option>
                    @php $st = data_get($survey->flags_json,'settlement_type'); @endphp
                    <option {{ $st==='Urban' ? 'selected' : '' }}>Urban</option><option {{ $st==='Peri-urban' ? 'selected' : '' }}>Peri-urban</option><option {{ $st==='Rural' ? 'selected' : '' }}>Rural</option><option {{ $st==='Informal' ? 'selected' : '' }}>Informal</option>
                  </select>
                </div>

                <div class="col-md-6 mb-3">
                  <label for="tenure_type">Tenure Type</label>
                  <select id="tenure_type" name="tenure_type" class="form-control select2 rounded-0 bg-light" style="width:100%;">
                    <option disabled {{ data_get($survey->flags_json,'tenure_type') ? '' : 'selected' }}>Select</option>
                    @php $tt = data_get($survey->flags_json,'tenure_type'); @endphp
                    <option {{ $tt==='Owned' ? 'selected' : '' }}>Owned</option><option {{ $tt==='Rented' ? 'selected' : '' }}>Rented</option><option {{ $tt==='Informal' ? 'selected' : '' }}>Informal</option><option {{ $tt==='IDP' ? 'selected' : '' }}>IDP</option><option {{ $tt==='Host' ? 'selected' : '' }}>Host</option>
                  </select>
                </div>

                <div class="col-md-6 mb-3">
                  <label for="displacement_status">Displacement Status</label>
                  <select id="displacement_status" name="displacement_status" class="form-control select2 rounded-0 bg-light" style="width:100%;">
                    <option disabled {{ data_get($survey->flags_json,'displacement_status') ? '' : 'selected' }}>Select</option>
                    @php $ds = data_get($survey->flags_json,'displacement_status'); @endphp
                    <option {{ $ds==='None' ? 'selected' : '' }}>None</option><option {{ $ds==='IDP' ? 'selected' : '' }}>IDP</option><option {{ $ds==='Returnee' ? 'selected' : '' }}>Returnee</option><option {{ $ds==='Migrant' ? 'selected' : '' }}>Migrant</option>
                  </select>
                </div>

                <div class="col-md-6 mb-3">
                  <label for="primary_languages">Primary Language(s)</label>
                  <select id="primary_languages" name="primary_languages[]" class="form-control select2 rounded-0 bg-light" multiple style="width:100%;">
                    @php $langs = collect(data_get($survey->flags_json,'primary_languages',[])); @endphp
                    <option {{ $langs->contains('Urdu') ? 'selected' : '' }}>Urdu</option><option {{ $langs->contains('Sindhi') ? 'selected' : '' }}>Sindhi</option><option {{ $langs->contains('Punjabi') ? 'selected' : '' }}>Punjabi</option><option {{ $langs->contains('Pashto') ? 'selected' : '' }}>Pashto</option><option {{ $langs->contains('English') ? 'selected' : '' }}>English</option>
                  </select>
                </div>

                <!-- C) Socio-Economic Snapshot -->
                <div class="col-md-12 mt-3">
                    <hr>
                    <p class="text-center fw-bold mb-3">C) Socio-Economic Snapshot</p>
                    <hr>
                </div> 

                <div class="col-md-6 mb-3">
                  <label for="primary_income">Primary Income Source</label>
                  <select id="primary_income" name="primary_income" class="form-control select2 rounded-0 bg-light" style="width:100%;">
                    <option disabled {{ data_get($survey->flags_json,'primary_income') ? '' : 'selected' }}>Select</option>
                    @php $pi = data_get($survey->flags_json,'primary_income'); @endphp
                    <option {{ $pi==='Labor' ? 'selected' : '' }}>Labor</option><option {{ $pi==='Small business' ? 'selected' : '' }}>Small business</option><option {{ $pi==='Agriculture' ? 'selected' : '' }}>Agriculture</option><option {{ $pi==='Transport' ? 'selected' : '' }}>Transport</option><option {{ $pi==='Domestic work' ? 'selected' : '' }}>Domestic work</option><option {{ $pi==='None' ? 'selected' : '' }}>None</option>
                  </select>
                </div>

                <div class="col-md-6 mb-3">
                  <label for="monthly_income">Monthly Income (PKR)</label>
                  <input type="number" id="monthly_income" name="monthly_income" class="form-control rounded-0 bg-light" min="0" value="{{ data_get($survey->flags_json,'monthly_income') }}">
                </div>

                <div class="col-md-6 mb-3">
                  <label for="monthly_expenses">Monthly Expenses (PKR)</label>
                  <input type="number" id="monthly_expenses" name="monthly_expenses" class="form-control rounded-0 bg-light" min="0" value="{{ data_get($survey->flags_json,'monthly_expenses') }}">
                </div>

                <div class="col-md-6 mb-3">
                  <label for="debt_outstanding">Debt Outstanding (PKR)</label>
                  <input type="number" id="debt_outstanding" name="debt_outstanding" class="form-control rounded-0 bg-light" min="0" value="{{ data_get($survey->flags_json,'debt_outstanding') }}">
                </div>

                <div class="col-md-6 mb-3">
                  <label for="debt_source">Debt Source</label>
                  <select id="debt_source" name="debt_source" class="form-control select2 rounded-0 bg-light" style="width:100%;">
                    <option disabled {{ data_get($survey->flags_json,'debt_source') ? '' : 'selected' }}>Select</option>
                    @php $ds2 = data_get($survey->flags_json,'debt_source'); @endphp
                    <option {{ $ds2==='Informal' ? 'selected' : '' }}>Informal</option><option {{ $ds2==='MFI' ? 'selected' : '' }}>MFI</option><option {{ $ds2==='Bank' ? 'selected' : '' }}>Bank</option>
                  </select>
                </div>

                <div class="col-md-6 mb-3">
                  <label>Asset Ownership</label>

                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="asset_ls" name="assets[]" value="Livestock" {{ $survey->assets->pluck('asset')->contains('Livestock') ? 'checked' : '' }}>
                    <label class="custom-control-label" for="asset_ls">Livestock</label>
                  </div>

                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="asset_mc" name="assets[]" value="Motorcycle" {{ $survey->assets->pluck('asset')->contains('Motorcycle') ? 'checked' : '' }}>
                    <label class="custom-control-label" for="asset_mc">Motorcycle</label>
                  </div>

                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="asset_sp" name="assets[]" value="Smartphone" {{ $survey->assets->pluck('asset')->contains('Smartphone') ? 'checked' : '' }}>
                    <label class="custom-control-label" for="asset_sp">Smartphone</label>
                  </div>

                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="asset_rf" name="assets[]" value="Refrigerator" {{ $survey->assets->pluck('asset')->contains('Refrigerator') ? 'checked' : '' }}>
                    <label class="custom-control-label" for="asset_rf">Refrigerator</label>
                  </div>
                </div>


                <div class="col-md-6 mb-3">
                  <label for="banking_access">Banking Access</label>
                  <select id="banking_access" name="banking_access" class="form-control select2 rounded-0 bg-light" style="width:100%;">
                    <option disabled {{ data_get($survey->flags_json,'banking_access') ? '' : 'selected' }}>Select</option>
                    @php $ba = data_get($survey->flags_json,'banking_access'); @endphp
                    <option {{ $ba==='Bank' ? 'selected' : '' }}>Bank</option><option {{ $ba==='Mobile wallet' ? 'selected' : '' }}>Mobile wallet</option><option {{ $ba==='None' ? 'selected' : '' }}>None</option>
                  </select>
                </div>

                <div class="col-md-6 mb-3">
                  <label for="digital_literacy">Digital Literacy Level</label>
                  <select id="digital_literacy" name="digital_literacy" class="form-control select2 rounded-0 bg-light" style="width:100%;">
                    <option disabled {{ data_get($survey->flags_json,'digital_literacy') ? '' : 'selected' }}>Select</option>
                    @php $dl = data_get($survey->flags_json,'digital_literacy'); @endphp
                    <option {{ $dl==='None' ? 'selected' : '' }}>None</option><option {{ $dl==='Basic' ? 'selected' : '' }}>Basic</option><option {{ $dl==='Intermediate' ? 'selected' : '' }}>Intermediate</option><option {{ $dl==='Advanced' ? 'selected' : '' }}>Advanced</option>
                  </select>
                </div>

                <div class="col-md-6 mb-3">
                  <label for="female_led">Female-Led Household</label>
                  <select id="female_led" name="female_led" class="form-control select2 rounded-0 bg-light" style="width:100%;">
                    @php $fl = data_get($survey->flags_json,'female_led','no'); @endphp
                    <option value="no" {{ $fl==='no' ? 'selected' : '' }}>No</option><option value="yes" {{ $fl==='yes' ? 'selected' : '' }}>Yes</option>
                  </select>
                </div>

                <div class="col-md-6 mb-3">
                  <label for="pwd_count">Persons with Disability (count)</label>
                  <input type="number" id="pwd_count" name="pwd_count" class="form-control rounded-0 bg-light" min="0" value="{{ data_get($survey->flags_json,'pwd_count') }}">
                </div>

                <!-- D) Multi-Sector Needs - Due to length, provide main fields; conditional UI can be expanded -->
                <div class="col-md-12 mt-3">
                    <hr>
                    <p class="text-center fw-bold mb-3">D) Multi-Sector Needs (Last 30–90 days)</p>
                    <hr>
                </div> 

                <!-- D1 Food -->
                <div class="col-md-12 mb-2"><strong>D1. Food Security & Nutrition</strong></div>
                <div class="col-md-6 mb-3">
                  <label for="meals_per_day">Meals / day</label>
                  <input type="number" id="meals_per_day" name="meals_per_day" class="form-control rounded-0 bg-light" min="0" value="{{ data_get($survey->flags_json,'meals_per_day') }}">
                </div>
                <div class="col-md-6 mb-3">
                  <label for="food_stocks">Food stocks remaining (days)</label>
                  <input type="number" id="food_stocks" name="food_stocks" class="form-control rounded-0 bg-light" min="0" value="{{ data_get($survey->flags_json,'food_stocks') }}">
                </div>
                <div class="col-md-6 mb-3">
                  <label for="child_maln">Any child &lt;5 malnutrition signs?</label>
                  <select id="child_maln" name="child_maln" class="form-control select2 rounded-0 bg-light" style="width:100%;">
                    @php $cm = data_get($survey->flags_json,'child_maln','unknown'); @endphp
                    <option value="unknown" {{ $cm==='unknown' ? 'selected' : '' }}>Unknown</option><option value="yes" {{ $cm==='yes' ? 'selected' : '' }}>Yes</option><option value="no" {{ $cm==='no' ? 'selected' : '' }}>No</option>
                  </select>
                </div>
                <div class="col-md-6 mb-3">
                  <label for="food_needs">Specific needs</label>
                  <select id="food_needs" name="food_needs[]" class="form-control select2 rounded-0 bg-light" multiple style="width:100%;">
                    @php $fn = collect(data_get($survey->flags_json,'food_needs',[])); @endphp
                    <option {{ $fn->contains('Ration') ? 'selected' : '' }}>Ration</option><option {{ $fn->contains('Cash for food') ? 'selected' : '' }}>Cash for food</option><option {{ $fn->contains('Nutrition support') ? 'selected' : '' }}>Nutrition support</option>
                  </select>
                </div>

                <!-- D2 WASH -->
                <div class="col-md-12 mb-2 mt-2"><strong>D2. WASH</strong></div>
                <div class="col-md-6 mb-3">
                  <label for="water_source">Primary water source</label>
                  <select id="water_source" name="water_source" class="form-control select2 rounded-0 bg-light" style="width:100%;">
                    @php $ws = data_get($survey->flags_json,'water_source'); @endphp
                    <option {{ $ws==='Piped' ? 'selected' : '' }}>Piped</option><option {{ $ws==='Bore' ? 'selected' : '' }}>Bore</option><option {{ $ws==='Tanker' ? 'selected' : '' }}>Tanker</option><option {{ $ws==='Surface' ? 'selected' : '' }}>Surface</option>
                  </select>
                </div>
                <div class="col-md-6 mb-3">
                  <label for="water_distance">Distance to water (mins one-way)</label>
                  <input type="number" id="water_distance" name="water_distance" class="form-control rounded-0 bg-light" min="0" value="{{ data_get($survey->flags_json,'water_distance') }}">
                </div>
                <div class="col-md-6 mb-3">
                  <label for="water_quality">Water quality concerns?</label>
                  <select id="water_quality" name="water_quality" class="form-control select2 rounded-0 bg-light" style="width:100%;">
                    @php $wq = data_get($survey->flags_json,'water_quality','no'); @endphp
                    <option value="no" {{ $wq==='no' ? 'selected' : '' }}>No</option><option value="yes" {{ $wq==='yes' ? 'selected' : '' }}>Yes</option>
                  </select>
                </div>

                <!-- ... Add D3..D8 fields similarly (omitted here for brevity but you can expand) -->

                <!-- E) Vulnerability & Flags -->
                <div class="col-md-12 mt-3">
                    <hr>
                    <p class="text-center fw-bold mb-3">E) Vulnerability & Eligibility Flags</p>
                    <hr>
                </div> 
                <div class="col-md-6 mb-3">
                  <label for="zakat_elig">Zakat Eligibility</label>
                  <select id="zakat_elig" name="zakat_elig" class="form-control select2 rounded-0 bg-light" style="width:100%;">
                    <option value="no" selected>No</option><option value="yes">Yes</option><option value="unsure">Unsure</option>
                  </select>
                </div>

                <div class="col-md-6 mb-3">
                  <label for="below_poverty">Below Poverty Line (auto)</label>
                  <select id="below_poverty" name="below_poverty" class="form-control select2 rounded-0 bg-light" style="width:100%;">
                    <option value="no" selected>No</option><option value="yes">Yes</option>
                  </select>
                </div>

                <div class="col-md-6 mb-3">
                  <label>Special Categories</label>

                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="sp_w" name="special_categories[]" value="Widow">
                    <label class="custom-control-label" for="sp_w">Widow</label>
                  </div>

                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="sp_o" name="special_categories[]" value="Orphan">
                    <label class="custom-control-label" for="sp_o">Orphan(s)</label>
                  </div>

                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="sp_p" name="special_categories[]" value="PWD">
                    <label class="custom-control-label" for="sp_p">PWD</label>
                  </div>
                </div>


                <!-- F) Assistance Preferences -->
                <div class="col-md-12 mt-3">
                    <hr>
                    <p class="text-center fw-bold mb-3">F) Assistance Preferences & Constraints</p>
                    <hr>
                </div> 

                <div class="col-md-6 mb-3">
                  <label for="pref_assistance">Preferred Assistance Modality</label>
                  @php $prefAssistance = (array) data_get($survey->flags_json,'pref_assistance',[]); @endphp
                  <select id="pref_assistance" name="pref_assistance[]" class="form-control select2 rounded-0 bg-light" multiple style="width:100%;">
                    <option value="Cash" {{ in_array('Cash',$prefAssistance) ? 'selected' : '' }}>Cash</option>
                    <option value="In-kind" {{ in_array('In-kind',$prefAssistance) ? 'selected' : '' }}>In-kind</option>
                    <option value="Services" {{ in_array('Services',$prefAssistance) ? 'selected' : '' }}>Services</option>
                    <option value="Vouchers" {{ in_array('Vouchers',$prefAssistance) ? 'selected' : '' }}>Vouchers</option>
                  </select>
                </div>

                <div class="col-md-6 mb-3">
                  <label for="program_pref">Program Type Preference</label>
                  @php $programPref = (array) data_get($survey->flags_json,'program_pref',[]); @endphp
                  <select id="program_pref" name="program_pref[]" class="form-control select2 rounded-0 bg-light" multiple style="width:100%;">
                    <option value="Food" {{ in_array('Food',$programPref) ? 'selected' : '' }}>Food</option>
                    <option value="Water" {{ in_array('Water',$programPref) ? 'selected' : '' }}>Water</option>
                    <option value="Education" {{ in_array('Education',$programPref) ? 'selected' : '' }}>Education</option>
                    <option value="Medical" {{ in_array('Medical',$programPref) ? 'selected' : '' }}>Medical</option>
                    <option value="Livelihood" {{ in_array('Livelihood',$programPref) ? 'selected' : '' }}>Livelihood</option>
                  </select>
                </div>

                <!-- G,H,I,J,K condensed inputs -->
                <div class="col-md-12 mt-3">
                    <hr>
                    <p class="text-center fw-bold mb-3">G) Past Aid & Duplication</p>
                    <hr>
                </div> 

                <hr class="mt-1 mb-3">
                <div class="col-md-6 mb-3">
                  <label for="received_aid_6m">Received aid last 6 months?</label>
                  <select id="received_aid_6m" name="received_aid_6m" class="form-control select2 rounded-0 bg-light" style="width:100%;">
                    @php $ra = data_get($survey->flags_json,'received_aid_6m','no'); @endphp
                    <option value="no" {{ $ra==='no' ? 'selected' : '' }}>No</option><option value="yes" {{ $ra==='yes' ? 'selected' : '' }}>Yes</option>
                  </select>
                </div>

                <div class="col-md-6 mb-3">
                  <label for="aid_details">Aid details (brief)</label>
                  <input type="text" id="aid_details" name="aid_details" class="form-control rounded-0 bg-light" placeholder="Date, Provider, Type, Value" value="{{ data_get($survey->flags_json,'aid_details') }}">
                </div>

                <hr class="my-3">

                <!-- Submission -->
                <div class="col-md-6 mb-3">

                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="purpose_explained" name="purpose_explained" {{ data_get($survey->flags_json,'purpose_explained') ? 'checked' : '' }}>
                    <label class="custom-control-label" for="purpose_explained">
                      Purpose explained to respondent
                    </label>
                  </div>

                  <div class="custom-control custom-checkbox mt-1">
                    <input type="checkbox" class="custom-control-input" id="consent_statement" name="consent_statement" {{ data_get($survey->flags_json,'consent_statement') ? 'checked' : '' }}>
                    <label class="custom-control-label" for="consent_statement">
                      Consent statement acknowledged
                    </label>
                  </div>
                </div>

              </div>

              <button type="submit" class="btn btn-secondary btn-pill mt-2">
                <i class="mdi mdi-check-circle-outline me-1"></i> Save Survey
              </button>
            </form>

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
    // Init select2
    $('.select2').select2({ width: '100%' });

    // Consent toggle
    // Initialize consent block state
    $('#consentDetails').toggle($('#consent_captured').is(':checked'));
    $('#consent_captured').change(function(){ $('#consentDetails').toggle(this.checked); });

    // Revisit toggle
    // Initialize revisit block state
    $('input[name="revisit_needed"]').change(function(){ if ($(this).val() === 'yes') $('#revisitBlock').show(); else $('#revisitBlock').hide(); });


  // Members repeatable + Family auto-fill (no immediate DB save)
    let memberIdx = 0;
    const csrf = $('form#surveyForm input[name="_token"]').val();
    const endpoints = {
      familyJson: (id) => "{{ url('family') }}/"+id+"/json"
    };

    const memberTemplate = (i, data = {}) => {
      const pid = data.id || '';
      const name = data.name || '';
      const age = data.age || '';
      const relation = data.relation || '';
      const education = data.education || '';
      const occupation = data.occupation || '';
      const cnic = data.cnic || '';
      return `
      <div class="member-row" data-person-id="${pid}">
        <div class="d-flex justify-content-between">
          <strong>Member</strong>
        </div>
        <div class="row mt-2">
          <div class="col-md-3 mb-2"><input name="members[${i}][name]" class="form-control rounded-0 bg-light" placeholder="Name" value="${name}"></div>
          <div class="col-md-3 mb-2"><input name="members[${i}][age]" type="number" class="form-control rounded-0 bg-light" placeholder="Age" value="${age}"></div>
          <div class="col-md-3 mb-2">
            <select name="members[${i}][gender]" class="form-control select2 rounded-0 bg-light" style="width:100%;">
              <option>Male</option><option>Female</option><option>Other</option>
            </select>
          </div>
          <div class="col-md-3 mb-2"><input name="members[${i}][relation]" class="form-control rounded-0 bg-light" placeholder="Relation" value="${relation}"></div>
          <div class="col-md-3 mb-2"><input name="members[${i}][education]" class="form-control rounded-0 bg-light" placeholder="Education" value="${education}"></div>
          <div class="col-md-3 mb-2"><input name="members[${i}][occupation]" class="form-control rounded-0 bg-light" placeholder="Occupation" value="${occupation}"></div>
          <div class="col-md-3 mb-2"><input name="members[${i}][cnic]" class="form-control rounded-0 bg-light" placeholder="CNIC (if 18+)" value="${cnic}"></div>
          <div class="col-md-3 mb-2">
            <input name="members[${i}][disability]" class="form-control rounded-0 bg-light" placeholder="Disability?">
          </div>
          <div class="col-md-3 mb-2"><input name="members[${i}][school_work]" class="form-control rounded-0 bg-light" placeholder="School/Work status"></div>
          <div class="col-md-3 mb-2 text-end">
            <button type="button" class="btn btn-outline-danger btn-sm removeMemberBtn"><i class="mdi mdi-delete"></i> Remove</button>
          </div>
        </div>
      </div>`;
    }

    function mapZakatToSelect(val){
      if (val === true || val === 'Yes' || val === 'yes') return 'yes';
      if (val === false || val === 'No' || val === 'no') return 'no';
      return 'unsure';
    }

    function renderMembers(list){
      $('#membersList').empty();
      memberIdx = 0;
      (list || []).forEach(function(m){
        const $row = $(memberTemplate(memberIdx++, m));
        $('#membersList').append($row);
        $row.find('.select2').select2({ width: '100%' });
      });
    }

    function fillFromFamily(f){
      $('#head_of_family').val(f.head_of_family_name || '');
      $('#survey_cnic').val(f.cnic || '');
      $('#household_size').val((f.family_members_count!=null? f.family_members_count : ''));
      $('#zakat_elig').val(mapZakatToSelect(f.zakat_eligibility)).trigger('change');
      // Extra profile fields
      if (f.gender === 'Male' || f.gender === 'Female') {
        $("input[name='gender'][value='"+f.gender+"']").prop('checked', true);
      }
      if (typeof f.age !== 'undefined' && f.age !== null) { $('#age').val(f.age); }
      if (f.marital_status) { $('#marital_status').val(f.marital_status).trigger('change'); }
      $('#contact_number').val(f.contact_number || '');
      if (typeof f.monthly_income !== 'undefined' && f.monthly_income !== null) $('#monthly_income').val(f.monthly_income);
      if (typeof f.monthly_expense !== 'undefined' && f.monthly_expense !== null) $('#monthly_expenses').val(f.monthly_expense);
      const addr = [f.address, f.area, f.city, f.province].filter(Boolean).join(', ');
      $('#address_full').val(addr);
      if (f.province) { $('#province').val(f.province).trigger('change'); }
      // Only render members if none exist yet (avoid overwriting edited/saved members)
      if ($('#membersList .member-row').length === 0) {
        const members = (f.members || []).map(function(m){ return { id:m.id, name:m.name, age:m.age, relation:m.relation, education:m.education, occupation:m.occupation, cnic:m.cnic }; });
        renderMembers(members);
      }
    }

    $('#family_id').on('change', function(){
      const famId = $(this).val();
      if (!famId || famId === 'new') { return; }
      $.get(endpoints.familyJson(famId))
        .done(function(f){ fillFromFamily(f); })
        .fail(function(){ if (window.toastr) toastr.error('Failed to load family'); });
    });

    // Auto-load if family is already selected on edit
    const initFam = $('#family_id').val();
    if (initFam && initFam !== 'new') {
      $.get(endpoints.familyJson(initFam)).done(fillFromFamily);
    }

    $('#addMemberBtn').click(function(){
      const $row = $(memberTemplate(memberIdx++));
      $('#membersList').append($row);
      $row.find('.select2').select2({ width: '100%' });
    });

    $(document).on('click','.removeMemberBtn', function(){
      const $row = $(this).closest('.member-row');
      if (!confirm('Are you sure to delete this record?')) return;
      $row.remove();
    });

    // Geo capture
    $('#btnCaptureGeo').click(function(){
      if (!navigator.geolocation) { $('#geoAccuracy').text('Geolocation not supported'); return; }
      navigator.geolocation.getCurrentPosition(function(pos){
        $('#gps_lat').val(pos.coords.latitude.toFixed(6));
        $('#gps_long').val(pos.coords.longitude.toFixed(6));
        $('#geoAccuracy').text('Accuracy: ' + (pos.coords.accuracy || 'n/a') + ' meters');
      }, function(err){ $('#geoAccuracy').text('Geo error: ' + err.message); }, { enableHighAccuracy: true, timeout: 10000 });
    });

    // Photos preview
    $('#photos').on('change', function(){
      const $wrap = $('#photosPreview').empty();
      const files = this.files || [];
      Array.from(files).forEach(f => {
        if (!f.type.startsWith('image/')) return;
        const url = URL.createObjectURL(f);
        const col = $('<div class="col-4 mb-2"></div>');
        const img = $('<img class="img-fluid rounded border" alt="preview">').attr('src', url);
        img.on('load', () => URL.revokeObjectURL(url));
        col.append(img);
        $wrap.append(col);
      });
    });

    // Audio preview + duration check
    $('#audio_note').on('change', function(){
      const file = this.files[0];
      const $prev = $('#audioPreview').empty();
      if (!file) { $('#audioWarning').text(''); return; }
      const url = URL.createObjectURL(file);
      // add player
      const player = $('<audio controls class="w-100 mb-2"></audio>').attr('src', url);
      $prev.append(player);
      // duration check
      const audio = new Audio(url);
      audio.addEventListener('loadedmetadata', function(){
        const dur = audio.duration;
        if (dur > 180) {
          $('#audioWarning').text('Audio longer than 180 seconds. Please trim.');
        } else {
          $('#audioWarning').text('');
        }
        URL.revokeObjectURL(url);
      });
    });

    // Consent file preview
    $('#consent_attachment').on('change', function(){
      const $prev = $('#consentPreviewNew').empty();
      const file = this.files[0];
      if (!file) return;
      const url = URL.createObjectURL(file);
      if (file.type.startsWith('image/')) {
        const img = $('<img class="img-fluid rounded border" alt="consent preview">').attr('src', url);
        img.on('load', () => URL.revokeObjectURL(url));
        $prev.append(img);
      } else if (file.type.startsWith('audio/')) {
        const audio = $('<audio controls class="w-100"></audio>').attr('src', url);
        $prev.append(audio);
      } else {
        const link = $('<a target="_blank">Open selected file</a>').attr('href', url);
        $prev.append(link);
      }
    });


  });
</script>
@endpush
