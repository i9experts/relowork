@extends('layouts.app')

@section('title', 'Family Registration Form')

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
                    <!-- <div class="alert alert-warning mb-3" role="alert">
                        ⚠️ Please ensure a Volunteer is created first. You cannot save this Family Registration Form without selecting or creating a Volunteer.
                    </div> -->
                    <div class="d-flex justi  fy-content-between align-items-center">
                      <div>
                        <h4 class="card-title fw-bold mb-1">Family Registration Form</h4>
                        <p class="text-muted mb-4">Purpose: To collect detailed beneficiary family data for eligibility and tracking.</p>
                      </div>
                      <div>
                        <span class="badge badge-secondary" style="font-size:0.95rem;">{{ $next_family_code ?? '' }}</span>
                      </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <form method="POST" action="{{ route('cms.family.store') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="form-row">
                                    <!-- Head of Family Name -->
                                    <div class="col-md-6 mb-3">
                                        <label for="head_of_family_name">Head of Family Name</label>
                                        <input type="text" class="form-control rounded-0 bg-light" id="head_of_family_name"
                                            placeholder="Enter Head of Family Name" name="head_of_family_name" value="{{ old('head_of_family_name') }}">
                                    </div>

                                    <!-- CNIC -->
                                    <div class="col-md-6 mb-3">
                                        <label for="cnic">CNIC / National ID</label>
                                        <input type="text" class="form-control rounded-0 bg-light" id="cnic"
                                            placeholder="42201-1234567-8" name="cnic" value="{{ old('cnic') }}">
                                    </div>

                                    <!-- Gender -->
                                    <div class="col-md-6 mb-3">
                                        <label for="gender" class="d-block mb-2">Gender :</label>

                                        <div class="custom-control custom-radio d-inline-block mr-3 mb-2">
                                            <input type="radio" id="genderMale" name="gender" class="custom-control-input" value="Male" {{ old('gender', 'Male') == 'Male' ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="genderMale">Male</label>
                                        </div>

                                        <div class="custom-control custom-radio d-inline-block mr-3 mb-2">
                                            <input type="radio" id="genderFemale" name="gender" class="custom-control-input" value="Female" {{ old('gender') == 'Female' ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="genderFemale">Female</label>
                                        </div>

                                    </div>

                                    <!-- Age -->
                                    <div class="col-md-6 mb-3">
                                        <label for="age">Age</label>
                                        <input type="number" class="form-control rounded-0 bg-light" id="age" placeholder="Enter Age" name="age" value="{{ old('age') }}" min="0">
                                    </div>

                                    <!-- Marital Status -->
                                    <div class="col-md-6 mb-3">
                                        <label for="marital_status">Marital Status</label>
                                        <select class="form-control rounded-0 bg-light select2" id="marital_status" name="marital_status" style="width: 100%;">
                                            <option disabled {{ old('marital_status') ? '' : 'selected' }}>Select Marital Status</option>
                                            <option value="Married" {{ old('marital_status')=='Married'?'selected':'' }}>Married</option>
                                            <option value="Widowed" {{ old('marital_status')=='Widowed'?'selected':'' }}>Widowed</option>
                                            <option value="Divorced" {{ old('marital_status')=='Divorced'?'selected':'' }}>Divorced</option>
                                            <option value="Single" {{ old('marital_status')=='Single'?'selected':'' }}>Single</option>
                                        </select>
                                    </div>

                                    <!-- Contact Number -->
                                    <div class="col-md-6 mb-3">
                                        <label for="contact_number">Contact Number</label>
                                        <input type="text" class="form-control rounded-0 bg-light" id="contact_number"
                                            placeholder="+92-300-1234567" name="contact_number" value="{{ old('contact_number') }}">
                                    </div>

                                    <!-- Address -->
                                    <div class="col-md-6 mb-3">
                                        <label for="address">Address</label>
                                        <textarea class="form-control rounded-0 bg-light" id="address" name="address" rows="3"
                                            placeholder="Enter Address">{{ old('address') }}</textarea>
                                    </div>

                                    <!-- Province -->
                                    <div class="col-md-6 mb-3">
                                        <label for="province">Province</label>
                                        <select class="form-control rounded-0 bg-light select2" id="province" name="province" style="width: 100%;">
                                            <option disabled {{ old('province') ? '' : 'selected' }}>Select Province</option>
                                            <option value="Punjab" {{ old('province')=='Punjab'?'selected':'' }}>Punjab</option>
                                            <option value="Sindh" {{ old('province')=='Sindh'?'selected':'' }}>Sindh</option>
                                            <option value="Khyber Pakhtunkhwa" {{ old('province')=='Khyber Pakhtunkhwa'?'selected':'' }}>Khyber Pakhtunkhwa</option>
                                            <option value="Balochistan" {{ old('province')=='Balochistan'?'selected':'' }}>Balochistan</option>
                                            <option value="Gilgit-Baltistan" {{ old('province')=='Gilgit-Baltistan'?'selected':'' }}>Gilgit-Baltistan</option>
                                            <option value="Azad Jammu and Kashmir" {{ old('province')=='Azad Jammu and Kashmir'?'selected':'' }}>Azad Jammu and Kashmir</option>
                                            <option value="Islamabad Capital Territory" {{ old('province')=='Islamabad Capital Territory'?'selected':'' }}>Islamabad Capital Territory</option>
                                        </select>
                                    </div>

                                    <!-- City -->
                                    <div class="col-md-6 mb-3">
                                        <label for="city">City</label>
                                        <input type="text" class="form-control rounded-0 bg-light" id="city"
                                            placeholder="Enter City" name="city" value="{{ old('city') }}">
                                    </div>

                                    <!-- Area -->
                                    <div class="col-md-6 mb-3">
                                        <label for="area">Area</label>
                                        <input type="text" class="form-control rounded-0 bg-light" id="area"
                                            placeholder="Enter Area" name="area" value="{{ old('area') }}">
                                    </div>

                                    <!-- GPS Location (optional) -->
                                    <div class="col-md-3 mb-3">
                                        <label for="gps_lat">GPS Latitude</label>
                                        <input type="number" step="0.000001" class="form-control rounded-0 bg-light" id="gps_lat" name="gps_lat" value="{{ old('gps_lat') }}" placeholder="24.8607">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="gps_long">GPS Longitude</label>
                                        <input type="number" step="0.000001" class="form-control rounded-0 bg-light" id="gps_long" name="gps_long" value="{{ old('gps_long') }}" placeholder="67.0011">
                                    </div>

                                    <!-- Family Members Count -->
                                    <div class="col-md-6 mb-3">
                                        <label for="family_members_count">Family Members</label>
                                        <input type="number" class="form-control rounded-0 bg-light" id="family_members_count"
                                            placeholder="Enter Family Members" name="family_members_count" value="{{ old('family_members_count') }}" min="0">
                                    </div>

                                    <div class="col-12 mb-3">
                                        <label class="d-block mb-2">Family Member Details</label>
                                        <div class="table-responsive">
                                            <table class="table table-bordered mb-2" id="membersTable">
                                                <thead>
                                                    <tr>
                                                        <th style="min-width:180px;">Name</th>
                                                        <th style="min-width:90px;">Age</th>
                                                        <th style="min-width:140px;">Relation</th>
                                                        <th style="min-width:140px;">Education</th>
                                                        <th style="min-width:140px;">Occupation</th>
                                                        <th style="min-width:170px;">CNIC</th>
                                                        <th style="width:60px;">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php $oldMembers = old('members', []); @endphp
                                                    @if(is_array($oldMembers) && count($oldMembers))
                                                        @foreach($oldMembers as $idx => $m)
                                                            <tr>
                                                                <td><input type="text" name="members[{{ $idx }}][name]" class="form-control rounded-0 bg-light" value="{{ $m['name'] ?? '' }}"></td>
                                                                <td><input type="number" name="members[{{ $idx }}][age]" class="form-control rounded-0 bg-light" value="{{ $m['age'] ?? '' }}" min="0"></td>
                                                                <td><input type="text" name="members[{{ $idx }}][relation]" class="form-control rounded-0 bg-light" value="{{ $m['relation'] ?? '' }}"></td>
                                                                <td><input type="text" name="members[{{ $idx }}][education]" class="form-control rounded-0 bg-light" value="{{ $m['education'] ?? '' }}"></td>
                                                                <td><input type="text" name="members[{{ $idx }}][occupation]" class="form-control rounded-0 bg-light" value="{{ $m['occupation'] ?? '' }}"></td>
                                                                <td><input type="text" name="members[{{ $idx }}][cnic]" class="form-control rounded-0 bg-light" value="{{ $m['cnic'] ?? '' }}" ></td>
                                                                <td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger remove-member">Remove</button></td>
                                                            </tr>
                                                        @endforeach
                                                    @else
                                                        <tr>
                                                            <td><input type="text" name="members[0][name]" class="form-control rounded-0 bg-light"></td>
                                                            <td><input type="number" name="members[0][age]" class="form-control rounded-0 bg-light" min="0"></td>
                                                            <td><input type="text" name="members[0][relation]" class="form-control rounded-0 bg-light"></td>
                                                            <td><input type="text" name="members[0][education]" class="form-control rounded-0 bg-light"></td>
                                                            <td><input type="text" name="members[0][occupation]" class="form-control rounded-0 bg-light"></td>
                                                            <td><input type="text" name="members[0][cnic]" class="form-control rounded-0 bg-light"></td>
                                                            <td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger remove-member">Remove</button></td>
                                                        </tr>
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                        <button type="button" class="btn btn-outline-secondary" id="addMemberBtn">Add Member</button>
                                    </div>

                                    <!-- Monthly Income -->
                                    <div class="col-md-6 mb-3">
                                        <label for="monthly_income">Monthly Income</label>
                                        <input type="number" class="form-control rounded-0 bg-light" id="monthly_income"
                                            placeholder="Enter Monthly Income" name="monthly_income" value="{{ old('monthly_income') }}" min="0">
                                    </div>

                                    <!-- Income Source -->
                                    <div class="col-md-6 mb-3">
                                      <label for="income_source" class="form-label fw-semibold">Income Source</label>
                                      <select class="form-control rounded-0 bg-light select2" id="income_source" name="income_source" style="width: 100%;">
                                        <option selected disabled>Select Income Source</option>

                                        <optgroup label="Employment & Services">
                                          <option value="labor">Labor / Daily Wage Worker</option>
                                          <option value="driver">Driver / Rickshaw / Taxi</option>
                                          <option value="domestic_worker">Domestic Worker (Maid / Cook)</option>
                                          <option value="teacher">Teacher / Tutor</option>
                                          <option value="security_guard">Security Guard</option>
                                          <option value="office_worker">Office / Clerk Job</option>
                                        </optgroup>

                                        <optgroup label="Business & Self-Employment">
                                          <option value="small_business">Small Business / Shopkeeper</option>
                                          <option value="street_vendor">Street Vendor / Hawker</option>
                                          <option value="freelancer">Freelancer / Online Work</option>
                                          <option value="agriculture">Agriculture / Farmer</option>
                                          <option value="fisherman">Fisherman</option>
                                          <option value="artisan">Handicraft / Artisan</option>
                                        </optgroup>

                                        <optgroup label="Other Sources">
                                          <option value="pension">Pension / Retirement Fund</option>
                                          <option value="remittance">Remittance from Abroad</option>
                                          <option value="rental_income">Rental Income</option>
                                          <option value="none">No Stable Source / Unemployed</option>
                                          <option value="student">Student (Dependent)</option>
                                        </optgroup>
                                      </select>
                                    </div>

                                    <!-- Monthly Expense -->
                                    <div class="col-md-6 mb-3">
                                        <label for="monthly_expense">Monthly Expense</label>
                                        <input type="number" class="form-control rounded-0 bg-light" id="monthly_expense"
                                            placeholder="Enter Monthly Expense" name="monthly_expense" value="{{ old('monthly_expense') }}" min="0">
                                    </div>

                                    <!-- Zakat Eligibility -->
                                    <div class="col-md-6 mb-3">
                                        <label for="zakat_eligibility" class="d-block mb-2">Zakat Eligibility :</label>

                                        <div class="custom-control custom-radio d-inline-block mr-3 mb-2">
                                            <input type="radio" id="zakatYes" name="zakat_eligibility" class="custom-control-input" value="Yes" {{ old('zakat_eligibility', 'Yes')=='Yes'?'checked':'' }}>
                                            <label class="custom-control-label" for="zakatYes">Yes</label>
                                        </div>

                                        <div class="custom-control custom-radio d-inline-block mr-3 mb-2">
                                            <input type="radio" id="zakatNo" name="zakat_eligibility" class="custom-control-input" value="No" {{ old('zakat_eligibility')=='No'?'checked':'' }}>
                                            <label class="custom-control-label" for="zakatNo">No</label>
                                        </div>
                                    </div>

                                    <!-- Comments / Notes -->
                                    <div class="col-md-6 mb-3">
                                        <label for="comment_note">Comments / Notes</label>
                                        <textarea class="form-control rounded-0 bg-light" id="comment_note" name="comment_note" rows="3"
                                            placeholder="Enter Comments / Notes">{{ old('comment_note') }}</textarea>
                                    </div>

                                    <!-- Income Source -->
                                    <div class="col-md-6 mb-3">
                                      <label for="volunteer_id" class="form-label fw-semibold">Volunteer</label>
                                      <select class="form-control rounded-0 bg-light select2" id="volunteer_id" name="volunteer_id" style="width: 100%;">
                                        <option value="">Select Volunteer</option>
                                        @foreach($volunteer as $volunteer)
                                          <option value="{{ $volunteer->id }}"{{ (old('volunteer_id') == $volunteer->id) ? 'selected' : '' }}>{{ $volunteer->full_name }}</option>
                                        @endforeach
                                      </select>
                                    </div>

                                </div>

                                <button type="submit" class="btn btn-secondary btn-pill"><i class="mdi mdi-check-circle-outline me-1"></i> Save</button>

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

    const membersTable = $('#membersTable tbody');
    const addBtn = $('#addMemberBtn');

    function nextIndex() {
      let max = -1;
      membersTable.find('tr').each(function(){
        const firstInput = $(this).find('input[name^="members["]')[0];
        if (!firstInput) return;
        const name = $(firstInput).attr('name');
        const match = name.match(/members\[(\d+)\]/);
        if (match) { const i = parseInt(match[1], 10); if (i > max) max = i; }
      });
      return max + 1;
    }

    function rowTemplate(i){
      return `
        <tr>
          <td><input type="text" name="members[${i}][name]" class="form-control rounded-0 bg-light"></td>
          <td><input type="number" name="members[${i}][age]" class="form-control rounded-0 bg-light" min="0"></td>
          <td><input type="text" name="members[${i}][relation]" class="form-control rounded-0 bg-light"></td>
          <td><input type="text" name="members[${i}][education]" class="form-control rounded-0 bg-light"></td>
          <td><input type="text" name="members[${i}][occupation]" class="form-control rounded-0 bg-light"></td>
          <td><input type="text" name="members[${i}][cnic]" class="form-control rounded-0 bg-light"></td>
          <td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger remove-member">Remove</button></td>
        </tr>`;
    }

    addBtn.on('click', function(){
      const i = nextIndex();
      membersTable.append(rowTemplate(i));
    });

    membersTable.on('click', '.remove-member', function(){
      const rows = membersTable.find('tr');
      if (rows.length > 1) {
        $(this).closest('tr').remove();
      }
    });
  });
</script>
@endpush
