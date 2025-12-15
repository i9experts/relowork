@extends('layouts.app')

@section('title', 'Aid Program Form')

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
  <div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
      <div class="w-100 mb-3">
          @include('partials.errors')
      </div>
     @if(($volunteers ?? collect())->isEmpty() || ($families ?? collect())->isEmpty())
        <div class="alert alert-warning" role="alert">
          @if(($volunteers ?? collect())->isEmpty())
            ⚠️ Please create a Volunteer first.
            <a href="{{ route('cms.volunteer.create') }}" class="danger-link">Create Volunteer</a> <br>
          @endif

          @if(($families ?? collect())->isEmpty())
            ⚠️ Please create a Family first.
            <a href="{{ route('cms.family.create') }}" class="danger-link">Create Family</a>
          @endif
        </div>
      @endif


        
        <h4 class="card-title fw-bold mb-1">Aid Program Form</h4>
        <p class="text-muted mb-4">
          Purpose: To define, categorize, and track ongoing or past charity projects.
        </p>

        

        <div class="row">
          <div class="col-md-12">
            <form method="POST" action="{{ route('cms.aid-program.store') }}" enctype="multipart/form-data">
              @csrf
              <div class="form-row">

                <!-- Program Title -->
                <div class="col-md-6 mb-3">
                  <label for="program_title">Program Title</label>
                  <input type="text" class="form-control rounded-0 bg-light" id="program_title"
                         name="program_title" placeholder="Enter Program Title" value="{{ old('program_title') }}">
                </div>

                <!-- Program Type -->
                <div class="col-md-6 mb-3">
                    <label for="program_type">Program Type</label>
                    <select class="form-control rounded-0 bg-light select2" id="program_type" name="program_type" style="width: 100%;">

                        <option disabled {{ old('program_type') ? '' : 'selected' }}> Select Program Type </option>

                        <!-- Basic Aid -->
                        <option value="Food Assistance" {{ old('program_type')=='Food Assistance'?'selected':'' }}> Food Assistance</option>
                        <option value="Education Support" {{ old('program_type')=='Education Support'?'selected':'' }}>Education Support</option>
                        <option value="Medical Aid" {{ old('program_type')=='Medical Aid'?'selected':'' }}> Medical Aid</option>
                        <option value="Clean Water & Sanitation" {{ old('program_type')=='Clean Water & Sanitation'?'selected':'' }}> Clean Water & Sanitation</option>
                        <option value="Shelter / Housing" {{ old('program_type')=='Shelter / Housing'?'selected':'' }}> Shelter / Housing</option>

                        <!-- Emergency & Relief -->
                        <option value="Emergency Relief" {{ old('program_type')=='Emergency Relief'?'selected':'' }}> Emergency Relief</option>
                        <option value="Disaster Response" {{ old('program_type')=='Disaster Response'?'selected':'' }}> Disaster Response</option>

                        <!-- Vulnerable Groups -->
                        <option value="Orphan Support" {{ old('program_type')=='Orphan Support'?'selected':'' }}> Orphan Support</option>
                        <option value="Widow Support" {{ old('program_type')=='Widow Support'?'selected':'' }}> Widow Support</option>
                        <option value="Disability Support" {{ old('program_type')=='Disability Support'?'selected':'' }}> Disability Support</option>
                        <option value="Elderly Care" {{ old('program_type')=='Elderly Care'?'selected':'' }}> Elderly Care</option>

                        <!-- Livelihood & Development -->
                        <option value="Livelihood Support" {{ old('program_type')=='Livelihood Support'?'selected':'' }}> Livelihood / Income Support</option>
                        <option value="Skill Development" {{ old('program_type')=='Skill Development'?'selected':'' }}> Skill Development</option>
                        <option value="Microfinance" {{ old('program_type')=='Microfinance'?'selected':'' }}> Microfinance / Interest-Free Loan</option>
                        <option value="Community Development" {{ old('program_type')=='Community Development'?'selected':'' }}> Community Development</option>

                        <!-- Seasonal / Special -->
                        <option value="Ramadan Program" {{ old('program_type')=='Ramadan Program'?'selected':'' }}> Ramadan Program</option>
                        <option value="Winter Relief" {{ old('program_type')=='Winter Relief'?'selected':'' }}> Winter Relief</option>
                        <option value="Qurbani Program" {{ old('program_type')=='Qurbani Program'?'selected':'' }}> Qurbani Program</option>

                        <!-- Health & Awareness -->
                        <option value="Health Awareness" {{ old('program_type')=='Health Awareness'?'selected':'' }}> Health Awareness</option>
                        <option value="Nutrition Program" {{ old('program_type')=='Nutrition Program'?'selected':'' }}> Nutrition Program</option>

                    </select>
                </div>


                <!-- Description -->
                <div class="col-md-6 mb-3">
                  <label for="description">Description</label>
                  <textarea class="form-control rounded-0 bg-light" id="description" name="description" rows="3"
                            placeholder="Enter detailed program description">{{ old('description') }}</textarea>
                </div>

                <!-- Start Date -->
                <div class="col-md-6 mb-3">
                  <label for="start_date">Start Date</label>
                  <input type="date" class="form-control rounded-0 bg-light" id="start_date" name="start_date" value="{{ old('start_date') }}">
                </div>

                <!-- End Date -->
                <div class="col-md-6 mb-3">
                  <label for="end_date">End Date</label>
                  <input type="date" class="form-control rounded-0 bg-light" id="end_date" name="end_date" value="{{ old('end_date') }}">
                </div>

                <!-- Location -->
                <div class="col-md-6 mb-3">
                  <label for="location">Location</label>
                  <input type="text" class="form-control rounded-0 bg-light" id="location"
                         name="location" placeholder="Enter Location (e.g., Tharparkar, Sindh)" value="{{ old('location') }}">
                </div>

                <!-- Target Beneficiaries -->
                <div class="col-md-6 mb-3">
                  <label for="target_beneficiaries">Target Beneficiaries</label>
                  <input type="number" class="form-control rounded-0 bg-light" id="target_beneficiaries"
                         name="target_beneficiaries" placeholder="e.g., 50 families" value="{{ old('target_beneficiaries') }}" min="0">
                </div>

                <!-- Status -->
                <div class="col-md-6 mb-3">
                  <label for="status">Status</label>
                  <select class="form-control rounded-0 bg-light select2" id="status" name="status" style="width: 100%;">
                    <option disabled {{ old('status') ? '' : 'selected' }}>Select Status</option>
                    <option value="Planned" {{ old('status')=='Planned'?'selected':'' }}>Planned</option>
                    <option value="Active" {{ old('status')=='Active'?'selected':'' }}>Active</option>
                    <option value="Completed" {{ old('status')=='Completed'?'selected':'' }}>Completed</option>
                    <option value="On Hold" {{ old('status')=='On Hold'?'selected':'' }}>On Hold</option>
                  </select>
                </div>

                <!-- Total Budget -->
                <div class="col-md-6 mb-3">
                  <label for="total_budget">Total Budget (PKR)</label>
                  <input type="number" class="form-control rounded-0 bg-light" id="total_budget"
                         name="total_budget" placeholder="Enter Total Budget (PKR)" value="{{ old('total_budget') }}" min="0">
                </div>

                <!-- Fund Source -->
                <div class="col-md-6 mb-3">
                  <label for="fund_source">Fund Source</label>
                  <select class="form-control rounded-0 bg-light select2" id="fund_source" name="fund_source" style="width: 100%;">
                    <option disabled {{ old('fund_source') ? '' : 'selected' }}>Select Fund Source</option>
                    <option value="Zakat" {{ old('fund_source')=='Zakat'?'selected':'' }}>Zakat</option>
                    <option value="Sadaqah" {{ old('fund_source')=='Sadaqah'?'selected':'' }}>Sadaqah</option>
                    <option value="Sadqa-e-Jariyah" {{ old('fund_source')=='Sadqa-e-Jariyah'?'selected':'' }}>Sadqa-e-Jariyah</option>
                    <option value="General Donation" {{ old('fund_source')=='General Donation'?'selected':'' }}>General Donation</option>
                    <option value="Grant" {{ old('fund_source')=='Grant'?'selected':'' }}>Grant</option>
                    <option value="Corporate CSR" {{ old('fund_source')=='Corporate CSR'?'selected':'' }}>Corporate CSR</option>
                    <option value="Government Funding" {{ old('fund_source')=='Government Funding'?'selected':'' }}>Government Funding</option>
                    <option value="Individual Donor" {{ old('fund_source')=='Individual Donor'?'selected':'' }}>Individual Donor</option>
                    <option value="Online Campaign" {{ old('fund_source')=='Online Campaign'?'selected':'' }}>Online Campaign</option>
                    <option value="Crowdfunding" {{ old('fund_source')=='Crowdfunding'?'selected':'' }}>Crowdfunding</option>
                    <option value="Waqf" {{ old('fund_source')=='Waqf'?'selected':'' }}>Waqf</option>
                    <option value="International NGO" {{ old('fund_source')=='International NGO'?'selected':'' }}>International NGO</option>
                    <option value="Local NGO" {{ old('fund_source')=='Local NGO'?'selected':'' }}>Local NGO</option>
                    <option value="Event Fundraising" {{ old('fund_source')=='Event Fundraising'?'selected':'' }}>Event Fundraising</option>
                    <option value="Anonymous Donor" {{ old('fund_source')=='Anonymous Donor'?'selected':'' }}>Anonymous Donor</option>
                  </select>
                </div>


                <!-- Responsible Volunteer / Supervisor -->
                <div class="col-md-6 mb-3">
                  <label for="volunteer_id">Responsible Volunteer / Supervisor</label>
                  <select class="form-control rounded-0 bg-light select2" id="volunteer_id" name="volunteer_id" style="width: 100%;" required>
                    <option value="">Select Volunteer</option>
                    @foreach($volunteers as $volunteer)
                      <option value="{{ $volunteer->id }}"{{ (old('volunteer_id') == $volunteer->id) ? 'selected' : '' }}>{{ $volunteer->full_name }}</option>
                    @endforeach
                  </select>
                </div>

                <!-- Associated Families -->
                <div class="col-md-6 mb-3">
                  <label for="families">Associated Families</label>
                    <div class="form-group">
                    @php $oldFamilies = old('families', []); @endphp
                    <select class="form-control select2" name="families[]" multiple="multiple" style="width: 100%;" required>
                        @foreach($families as $fam)
                          <option value="{{ $fam->id }}" {{ in_array($fam->id, (array)$oldFamilies) ? 'selected' : '' }}>
                            {{ (\Illuminate\Support\Str::startsWith($fam->family_code ?? '', 'FAM-') ? ('FAM-' . \Illuminate\Support\Str::afterLast($fam->family_code ?? '', '-')) : ($fam->family_code ?? '')) }} - {{ $fam->head_of_family_name }}
                          </option>
                        @endforeach
                    </select>
                    </div>
                </div>

                <!-- Monitoring Frequency -->
                <div class="col-md-6 mb-3">
                  <label for="monitoring_frequency">Monitoring Frequency</label>
                  <select class="form-control rounded-0 bg-light select2" id="monitoring_frequency" name="monitoring_frequency" style="width: 100%;">
                    <option disabled {{ old('monitoring_frequency') ? '' : 'selected' }}>Select Frequency</option>
                    <option value="Daily" {{ old('monitoring_frequency')=='Daily'?'selected':'' }}>Daily</option>
                    <option value="Weekly" {{ old('monitoring_frequency')=='Weekly'?'selected':'' }}>Weekly</option>
                    <option value="Bi-Weekly" {{ old('monitoring_frequency')=='Bi-Weekly'?'selected':'' }}>Bi-Weekly</option>
                    <option value="Monthly" {{ old('monitoring_frequency')=='Monthly'?'selected':'' }}>Monthly</option>
                    <option value="Bi-Monthly" {{ old('monitoring_frequency')=='Bi-Monthly'?'selected':'' }}>Bi-Monthly</option>
                    <option value="Quarterly" {{ old('monitoring_frequency')=='Quarterly'?'selected':'' }}>Quarterly</option>
                    <option value="Bi-Annual" {{ old('monitoring_frequency')=='Bi-Annual'?'selected':'' }}>Bi-Annual</option>
                    <option value="Annual" {{ old('monitoring_frequency')=='Annual'?'selected':'' }}>Annual</option>
                    <option value="On Completion" {{ old('monitoring_frequency')=='On Completion'?'selected':'' }}>On Completion</option>
                    <option value="As Required" {{ old('monitoring_frequency')=='As Required'?'selected':'' }}>As Required</option>
                  </select>
                </div>


                <!-- Progress Notes -->
                <div class="col-md-6 mb-3">
                  <label for="progress_notes">Progress Notes</label>
                  <textarea class="form-control rounded-0 bg-light" id="progress_notes" name="progress_notes" rows="3"
                            placeholder="Enter progress or inspection notes">{{ old('progress_notes') }}</textarea>
                </div>

                <!-- Attachments -->
                <div class="col-md-6 mb-3">
                  <label for="attachments">Attachments</label>
                  <input type="file" class="form-control rounded-0 bg-light" id="attachments" name="attachments[]" multiple>
                  <small class="text-muted">Upload photos, reports, or receipts.</small>
                </div>

              </div>

              <button type="submit" class="btn btn-secondary btn-pill mt-2" {{ (($volunteers ?? collect())->isEmpty() || ($families ?? collect())->isEmpty()) ? 'disabled' : '' }}>
                <i class="mdi mdi-check-circle-outline me-1"></i> Save Program
              </button>
            </form>
          </div>
        </div>
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
    $('.select2').select2({
      width: '100%'
    });
  });
</script>
@endpush
