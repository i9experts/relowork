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
            <h4 class="card-title fw-bold mb-1">Survey / Needs-Assessment Form</h4>
            <p class="text-muted mb-4">Purpose: Planning & projections — capture household & multi-sector needs, geo, consent and media.</p>

            <form method="POST" action="{{ route('cms.survey.store') }}" enctype="multipart/form-data" id="surveyForm">
              @csrf
              <div class="form-row">

                <!-- A) Survey Metadata -->
                <div class="col-md-12 mt-3">
                    <hr>
                    <p class="text-center fw-bold mb-3">A) Survey Metadata</p>
                    <hr>
                </div> 

                <div class="col-md-6 mb-3">
                  <label for="enumerator_id">Enumerator (Volunteer)</label>
                  <select id="enumerator_id" name="enumerator_id" class="form-control select2 rounded-0 bg-light" style="width:100%;">
                    <option disabled selected>Select Enumerator</option>
                    @foreach($volunteers as $vol)
                    <option value="{{ $vol->id }}" {{ (old('vol_id') == $vol->id) ? 'selected' : '' }}>{{ $vol->volunteer_code ?? '' }} - {{ $vol->full_name }}</option>
                    @endforeach
                  </select>
                </div>

                <div class="col-md-6 mb-3">
                  <label for="survey_mode">Survey Mode</label>
                  <select id="survey_mode" name="survey_mode" class="form-control select2 rounded-0 bg-light" style="width:100%;">
                    <option disabled selected>Select Mode</option>
                    <option value="In-person">In-person</option>
                    <option value="Phone">Phone</option>
                    <option value="WhatsApp">WhatsApp</option>
                    <option value="Web">Web</option>
                  </select>
                </div>

                <div class="col-md-6 mb-3">
                  <label for="datetime">Date & Time</label>
                  <input type="datetime-local" id="datetime" name="datetime" class="form-control rounded-0 bg-light">
                </div>

                <div class="col-md-6 mb-3">
                  <label>GPS Location <small class="small-muted"> (click to capture)</small></label>
                  <div class="input-group">
                    <input type="text" id="gps_lat" name="gps_lat" class="form-control rounded-0 bg-light mr-2" placeholder="Latitude" readonly>
                    <input type="text" id="gps_long" name="gps_long" class="form-control rounded-0 bg-light mr-2" placeholder="Longitude" readonly>
                    <button class="btn btn-outline-secondary" type="button" id="btnCaptureGeo">Capture</button>
                  </div>
                  <small id="geoAccuracy" class="small-muted"></small>
                </div>

                <div class="col-md-6 mb-3">
                  <label for="photos">Photo Proof / Site Images <span class="required">*</span></label>
                  <input type="file" id="photos" name="photos[]" accept="image/*" multiple class="form-control rounded-0 bg-light">
                  <small class="small-muted">Minimum 1 photo required. (jpg/png)</small>
                </div>

                <div class="col-md-6 mb-3">
                  <label for="audio_note">Audio Note (optional)</label>
                  <input type="file" id="audio_note" name="audio_note" accept="audio/*" class="form-control rounded-0 bg-light">
                  <small class="small-muted">Recommended 60–180 sec. We will warn if duration > 180s</small>
                  <br>
                  <small id="audioWarning" class="small-muted" style="color:#d9534f;"></small>
                </div>

                <div class="col-md-6 mb-3">
                <label for="consent_captured" class="form-label mb-0 mr-2">Consent Captured?</label>
                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="consent_captured" name="consent_captured">
                    <label class="custom-control-label" for="consent_captured">Yes</label>
                  </div>

                  <div id="consentDetails" style="display:none; margin-top:8px;">
                    <select id="consent_type" name="consent_type" class="form-control select2 rounded-0 bg-light" style="width:100%;">
                      <option disabled selected>Consent Type</option>
                      <option value="Text">Text</option>
                      <option value="Voice">Voice</option>
                      <option value="Signature Photo">Signature Photo</option>
                    </select>
                    <div class="mt-2">
                      <input type="file" id="consent_attachment" name="consent_attachment" class="form-control rounded-0 bg-light">
                      <small class="small-muted">If voice/signature/photo selected, upload proof.</small>
                    </div>
                  </div>
                </div>

                <div class="col-md-6 mb-3">
                  <label>Revisit Needed?</label>

                  <div class="custom-control custom-radio d-inline-block mr-3 mb-3">
                    <input type="radio" id="revisit_yes" name="revisit_needed" class="custom-control-input" value="yes">
                    <label class="custom-control-label" for="revisit_yes">Yes</label>
                  </div>

                  <div class="custom-control custom-radio d-inline-block mr-3 mb-3">
                    <input type="radio" id="revisit_no" name="revisit_needed" class="custom-control-input" value="no" checked>
                    <label class="custom-control-label" for="revisit_no">No</label>
                  </div>

                  <div id="revisitBlock" style="display:none; margin-top:8px;">
                    <input type="text" name="revisit_reason" class="form-control rounded-0 bg-light mb-2" placeholder="Reason for revisit">
                    <div class="d-flex gap-2">
                      <input type="date" name="revisit_from" class="form-control rounded-0 bg-light">
                      <input type="date" name="revisit_to" class="form-control rounded-0 bg-light">
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
                  <label for="family_id">Linked Family</label>
                  <select id="family_id" name="family_id" class="form-control select2 rounded-0 bg-light" style="width:100%;">
                    <option value="new" selected>New family</option>
                    @foreach($families as $fam)
                    <option value="{{ $fam->id }}" {{ (old('fam_id') == $fam->id) ? 'selected' : '' }}>{{ $fam->family_code ?? '' }} - {{ $fam->head_of_family_name }}</option>
                    @endforeach
                  </select>
                </div>

                <div class="col-md-6 mb-3">
                  <label for="head_of_family">Head of Family (Name)</label>
                  <input type="text" id="head_of_family" name="head_of_family" class="form-control rounded-0 bg-light">
                </div>

                <div class="col-md-6 mb-3">
                  <label for="survey_cnic">CNIC / National ID</label>
                  <input type="text" id="survey_cnic" name="survey_cnic" class="form-control rounded-0 bg-light" placeholder="42201-1234567-9">
                </div>

                <div class="col-md-6 mb-3">
                  <label for="household_size">Household Size</label>
                  <input type="number" id="household_size" name="household_size" class="form-control rounded-0 bg-light" min="1">
                </div>

                <!-- Members detail (repeatable) -->
                <div class="col-md-12 mb-3">
                  <label>Members Detail</label>
                  <div id="membersList">
                    <!-- template row appended here -->
                  </div>
                  <button type="button" id="addMemberBtn" class="btn btn-outline-secondary btn-sm mt-1"><i class="mdi mdi-plus mr-1"></i> Add Member</button>
                </div>

                <div class="col-md-6 mb-3">
                  <label for="address_full">Address</label>
                  <textarea id="address_full" name="address_full" class="form-control rounded-0 bg-light" rows="2"></textarea>
                </div>

                <div class="col-md-6 mb-3">
                  <label for="province">Province</label>
                  <select id="province" name="province" class="form-control select2 rounded-0 bg-light" style="width:100%;">
                    <option disabled selected>Select Province</option>
                    <option>Punjab</option><option>Sindh</option><option>Khyber Pakhtunkhwa</option><option>Balochistan</option>
                  </select>
                </div>

                <div class="col-md-6 mb-3">
                  <label for="district">District</label>
                  <input type="text" id="district" name="district" class="form-control rounded-0 bg-light">
                </div>

                <div class="col-md-6 mb-3">
                  <label for="tehsil">Tehsil</label>
                  <input type="text" id="tehsil" name="tehsil" class="form-control rounded-0 bg-light">
                </div>

                <div class="col-md-6 mb-3">
                  <label for="uc">Union Council</label>
                  <input type="text" id="uc" name="uc" class="form-control rounded-0 bg-light">
                </div>

                <div class="col-md-6 mb-3">
                  <label for="settlement_type">Settlement Type</label>
                  <select id="settlement_type" name="settlement_type" class="form-control select2 rounded-0 bg-light" style="width:100%;">
                    <option disabled selected>Select</option>
                    <option>Urban</option><option>Peri-urban</option><option>Rural</option><option>Informal</option>
                  </select>
                </div>

                <div class="col-md-6 mb-3">
                  <label for="tenure_type">Tenure Type</label>
                  <select id="tenure_type" name="tenure_type" class="form-control select2 rounded-0 bg-light" style="width:100%;">
                    <option disabled selected>Select</option>
                    <option>Owned</option><option>Rented</option><option>Informal</option><option>IDP</option><option>Host</option>
                  </select>
                </div>

                <div class="col-md-6 mb-3">
                  <label for="displacement_status">Displacement Status</label>
                  <select id="displacement_status" name="displacement_status" class="form-control select2 rounded-0 bg-light" style="width:100%;">
                    <option disabled selected>Select</option>
                    <option>None</option><option>IDP</option><option>Returnee</option><option>Migrant</option>
                  </select>
                </div>

                <div class="col-md-6 mb-3">
                  <label for="primary_languages">Primary Language(s)</label>
                  <select id="primary_languages" name="primary_languages[]" class="form-control select2 rounded-0 bg-light" multiple style="width:100%;">
                    <option>Urdu</option><option>Sindhi</option><option>Punjabi</option><option>Pashto</option><option>English</option>
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
                    <option disabled selected>Select</option>
                    <option>Labor</option><option>Small business</option><option>Agriculture</option><option>Transport</option><option>Domestic work</option><option>None</option>
                  </select>
                </div>

                <div class="col-md-6 mb-3">
                  <label for="monthly_income">Monthly Income (PKR)</label>
                  <input type="number" id="monthly_income" name="monthly_income" class="form-control rounded-0 bg-light" min="0">
                </div>

                <div class="col-md-6 mb-3">
                  <label for="monthly_expenses">Monthly Expenses (PKR)</label>
                  <input type="number" id="monthly_expenses" name="monthly_expenses" class="form-control rounded-0 bg-light" min="0">
                </div>

                <div class="col-md-6 mb-3">
                  <label for="debt_outstanding">Debt Outstanding (PKR)</label>
                  <input type="number" id="debt_outstanding" name="debt_outstanding" class="form-control rounded-0 bg-light" min="0">
                </div>

                <div class="col-md-6 mb-3">
                  <label for="debt_source">Debt Source</label>
                  <select id="debt_source" name="debt_source" class="form-control select2 rounded-0 bg-light" style="width:100%;">
                    <option disabled selected>Select</option>
                    <option>Informal</option><option>MFI</option><option>Bank</option>
                  </select>
                </div>

                <div class="col-md-6 mb-3">
                  <label>Asset Ownership</label>

                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="asset_ls" name="assets[]" value="Livestock">
                    <label class="custom-control-label" for="asset_ls">Livestock</label>
                  </div>

                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="asset_mc" name="assets[]" value="Motorcycle">
                    <label class="custom-control-label" for="asset_mc">Motorcycle</label>
                  </div>

                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="asset_sp" name="assets[]" value="Smartphone">
                    <label class="custom-control-label" for="asset_sp">Smartphone</label>
                  </div>

                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="asset_rf" name="assets[]" value="Refrigerator">
                    <label class="custom-control-label" for="asset_rf">Refrigerator</label>
                  </div>
                </div>


                <div class="col-md-6 mb-3">
                  <label for="banking_access">Banking Access</label>
                  <select id="banking_access" name="banking_access" class="form-control select2 rounded-0 bg-light" style="width:100%;">
                    <option disabled selected>Select</option>
                    <option>Bank</option><option>Mobile wallet</option><option>None</option>
                  </select>
                </div>

                <div class="col-md-6 mb-3">
                  <label for="digital_literacy">Digital Literacy Level</label>
                  <select id="digital_literacy" name="digital_literacy" class="form-control select2 rounded-0 bg-light" style="width:100%;">
                    <option disabled selected>Select</option>
                    <option>None</option><option>Basic</option><option>Intermediate</option><option>Advanced</option>
                  </select>
                </div>

                <div class="col-md-6 mb-3">
                  <label for="female_led">Female-Led Household</label>
                  <select id="female_led" name="female_led" class="form-control select2 rounded-0 bg-light" style="width:100%;">
                    <option value="no" selected>No</option><option value="yes">Yes</option>
                  </select>
                </div>

                <div class="col-md-6 mb-3">
                  <label for="pwd_count">Persons with Disability (count)</label>
                  <input type="number" id="pwd_count" name="pwd_count" class="form-control rounded-0 bg-light" min="0">
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
                  <input type="number" id="meals_per_day" name="meals_per_day" class="form-control rounded-0 bg-light" min="0">
                </div>
                <div class="col-md-6 mb-3">
                  <label for="food_stocks">Food stocks remaining (days)</label>
                  <input type="number" id="food_stocks" name="food_stocks" class="form-control rounded-0 bg-light" min="0">
                </div>
                <div class="col-md-6 mb-3">
                  <label for="child_maln">Any child &lt;5 malnutrition signs?</label>
                  <select id="child_maln" name="child_maln" class="form-control select2 rounded-0 bg-light" style="width:100%;">
                    <option value="unknown" selected>Unknown</option><option value="yes">Yes</option><option value="no">No</option>
                  </select>
                </div>
                <div class="col-md-6 mb-3">
                  <label for="food_needs">Specific needs</label>
                  <select id="food_needs" name="food_needs[]" class="form-control select2 rounded-0 bg-light" multiple style="width:100%;">
                    <option>Ration</option><option>Cash for food</option><option>Nutrition support</option>
                  </select>
                </div>

                <!-- D2 WASH -->
                <div class="col-md-12 mb-2 mt-2"><strong>D2. WASH</strong></div>
                <div class="col-md-6 mb-3">
                  <label for="water_source">Primary water source</label>
                  <select id="water_source" name="water_source" class="form-control select2 rounded-0 bg-light" style="width:100%;">
                    <option>Piped</option><option>Bore</option><option>Tanker</option><option>Surface</option>
                  </select>
                </div>
                <div class="col-md-6 mb-3">
                  <label for="water_distance">Distance to water (mins one-way)</label>
                  <input type="number" id="water_distance" name="water_distance" class="form-control rounded-0 bg-light" min="0">
                </div>
                <div class="col-md-6 mb-3">
                  <label for="water_quality">Water quality concerns?</label>
                  <select id="water_quality" name="water_quality" class="form-control select2 rounded-0 bg-light" style="width:100%;">
                    <option value="no" selected>No</option><option value="yes">Yes</option>
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
                    <label class="custom-control-label" for=  "sp_w">Widow</label>
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
                  <select id="pref_assistance" name="pref_assistance[]" class="form-control select2 rounded-0 bg-light" multiple style="width:100%;">
                    <option>Cash</option><option>In-kind</option><option>Services</option><option>Vouchers</option>
                  </select>
                </div>

                <div class="col-md-6 mb-3">
                  <label for="program_pref">Program Type Preference</label>
                  <select id="program_pref" name="program_pref[]" class="form-control select2 rounded-0 bg-light" multiple style="width:100%;">
                    <option>Food</option><option>Water</option><option>Education</option><option>Medical</option><option>Livelihood</option>
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
                    <option value="no" selected>No</option><option value="yes">Yes</option>
                  </select>
                </div>

                <div class="col-md-6 mb-3">
                  <label for="aid_details">Aid details (brief)</label>
                  <input type="text" id="aid_details" name="aid_details" class="form-control rounded-0 bg-light" placeholder="Date, Provider, Type, Value">
                </div>

                <hr class="my-3">

                <!-- Submission -->
                <div class="col-md-6 mb-3">

                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="purpose_explained" name="purpose_explained">
                    <label class="custom-control-label" for="purpose_explained">
                      Purpose explained to respondent
                    </label>
                  </div>

                  <div class="custom-control custom-checkbox mt-1">
                    <input type="checkbox" class="custom-control-input" id="consent_statement" name="consent_statement">
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
    $('#consent_captured').change(function(){
      $('#consentDetails').toggle(this.checked);
    });

    // Revisit toggle
    $('input[name="revisit_needed"]').change(function(){
      if ($(this).val() === 'yes') $('#revisitBlock').show(); else $('#revisitBlock').hide();
    });

    // Members repeatable
    let memberIdx = 0;
    const memberTemplate = (i) => `
      <div class="member-row">
        <div class="d-flex justify-content-between">
          <strong>Member</strong>
        </div>
        <div class="row mt-2">
          <div class="col-md-3 mb-2"><input name="members[${i}][name]" class="form-control rounded-0 bg-light" placeholder="Name"></div>
          <div class="col-md-3 mb-2"><input name="members[${i}][age]" type="number" class="form-control rounded-0 bg-light" placeholder="Age"></div>
          <div class="col-md-3 mb-2">
            <select name="members[${i}][gender]" class="form-control select2 rounded-0 bg-light" style="width:100%;">
              <option>Male</option><option>Female</option><option>Other</option>
            </select>
          </div>
          <div class="col-md-3 mb-2"><input name="members[${i}][relation]" class="form-control rounded-0 bg-light" placeholder="Relation"></div>
          <div class="col-md-3 mb-2"><input name="members[${i}][cnic]" class="form-control rounded-0 bg-light" placeholder="CNIC (if 18+)"></div>
          <div class="col-md-3 mb-2">
            <input name="members[${i}][disability]" class="form-control rounded-0 bg-light" placeholder="Disability?">
          </div>
          <div class="col-md-3 mb-2"><input name="members[${i}][school_work]" class="form-control rounded-0 bg-light" placeholder="School/Work status"></div>
      
          <div class="col-md-3 mb-2 text-end">
            <button type="button" class="btn btn-outline-danger btn-sm removeMemberBtn"><i class="mdi mdi-delete"></i> Remove</button>
            </div>
        </div>
      </div>`;

    $('#addMemberBtn').click(function(){
      const idx = memberIdx++;
      const $row = $(memberTemplate(idx));
      $('#membersList').append($row);
      $row.find('.select2').select2({ width: '100%' });
    });
    $(document).on('click','.removeMemberBtn', function(){ $(this).closest('.member-row').remove(); });

    // Geo capture
    $('#btnCaptureGeo').click(function(){
      if (!navigator.geolocation) { $('#geoAccuracy').text('Geolocation not supported'); return; }
      navigator.geolocation.getCurrentPosition(function(pos){
        $('#gps_lat').val(pos.coords.latitude.toFixed(6));
        $('#gps_long').val(pos.coords.longitude.toFixed(6));
        $('#geoAccuracy').text('Accuracy: ' + (pos.coords.accuracy || 'n/a') + ' meters');
      }, function(err){ $('#geoAccuracy').text('Geo error: ' + err.message); }, { enableHighAccuracy: true, timeout: 10000 });
    });

    // Audio duration check
    $('#audio_note').on('change', function(){
      const file = this.files[0];
      if (!file) return;
      const url = URL.createObjectURL(file);
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


  });
</script>
@endpush
