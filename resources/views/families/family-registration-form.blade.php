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
                    <h4 class="card-title fw-bold mb-1">Family Registration Form</h4>
                    <p class="text-muted mb-4">Purpose: To collect detailed beneficiary family data for eligibility and tracking.</p>
                    <div class="row">
                        <div class="col-md-12">
                            <!-- @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif -->
                            <form method="POST" action="" enctype="multipart/form-data">
                                @csrf
                                <div class="form-row">
                                    <!-- Head of Family Name -->
                                    <div class="col-md-6 mb-3">
                                        <label for="head_of_family_name">Head of Family Name</label>
                                        <input type="text" class="form-control rounded-0 bg-light" id="head_of_family_name"
                                            placeholder="Enter Head of Family Name" name="head_of_family_name">
                                    </div>

                                    <!-- CNIC -->
                                    <div class="col-md-6 mb-3">
                                        <label for="cnic_national_id">CNIC / National ID</label>
                                        <input type="text" class="form-control rounded-0 bg-light" id="cnic_national_id"
                                            placeholder="Enter CNIC" name="cnic_national_id">
                                    </div>

                                    <!-- Gender -->
                                    <div class="col-md-6 mb-3">
                                        <label for="gender" class="d-block mb-2">Gender :</label>

                                        <div class="custom-control custom-radio d-inline-block mr-3 mb-2">
                                            <input type="radio" id="genderMale" name="gender" class="custom-control-input" checked>
                                            <label class="custom-control-label" for="genderMale">Male</label>
                                        </div>

                                        <div class="custom-control custom-radio d-inline-block mr-3 mb-2">
                                            <input type="radio" id="genderFemale" name="gender" class="custom-control-input">
                                            <label class="custom-control-label" for="genderFemale">Female</label>
                                        </div>

                                        <div class="custom-control custom-radio d-inline-block mb-2">
                                            <input type="radio" id="genderOther" name="gender" class="custom-control-input">
                                            <label class="custom-control-label" for="genderOther">Other</label>
                                        </div>
                                    </div>

                                    <!-- Age -->
                                    <div class="col-md-6 mb-3">
                                        <label for="age">Age</label>
                                        <input type="number" class="form-control rounded-0 bg-light" id="age"
                                            placeholder="Enter Age" name="age">
                                    </div>

                                    <!-- Marital Status -->
                                    <div class="col-md-6 mb-3">
                                        <label for="marital_status">Marital Status</label>
                                        <select class="form-control rounded-0 bg-light select2" id="marital_status" name="marital_status" style="width: 100%;">
                                            <option selected disabled>Select Marital Status</option>
                                            <option value="Married">Married</option>
                                            <option value="Widowed">Widowed</option>
                                            <option value="Divorced">Divorced</option>
                                            <option value="Single">Single</option>
                                        </select>
                                    </div>

                                    <!-- Contact Number -->
                                    <div class="col-md-6 mb-3">
                                        <label for="contact_number">Contact Number</label>
                                        <input type="text" class="form-control rounded-0 bg-light" id="contact_number"
                                            placeholder="Enter Contact Number" name="contact_number">
                                    </div>

                                    <!-- Address -->
                                    <div class="col-md-6 mb-3">
                                        <label for="address">Address</label>
                                        <textarea class="form-control rounded-0 bg-light" id="address" name="address" rows="3"
                                            placeholder="Enter Address"></textarea>
                                    </div>

                                    <!-- Province -->
                                    <div class="col-md-6 mb-3">
                                        <label for="province">Province</label>
                                        <select class="form-control rounded-0 bg-light select2" id="province" name="province" style="width: 100%;">
                                            <option selected disabled>Select Province</option>
                                            <option value="Punjab">Punjab</option>
                                            <option value="Sindh">Sindh</option>
                                            <option value="Khyber Pakhtunkhwa">Khyber Pakhtunkhwa</option>
                                            <option value="Balochistan">Balochistan</option>
                                            <option value="Gilgit-Baltistan">Gilgit-Baltistan</option>
                                            <option value="Azad Jammu and Kashmir">Azad Jammu and Kashmir</option>
                                            <option value="Islamabad Capital Territory">Islamabad Capital Territory</option>
                                        </select>
                                    </div>

                                    <!-- Location -->
                                    <div class="col-md-6 mb-3">
                                        <label for="location">Location</label>
                                        <input type="location" class="form-control rounded-0 bg-light" id="location"
                                            placeholder="Enter location" name="location">
                                    </div>

                                    <!-- Family Members Count -->
                                    <div class="col-md-6 mb-3">
                                        <label for="family_members_count">Family Members</label>
                                        <input type="" class="form-control rounded-0 bg-light" id="family_members_count"
                                            placeholder="Enter Family Members" name="family_members_count">
                                    </div>

                                    <!-- Family Member Details -->
                                    <div class="col-md-6 mb-3">
                                        <label for="family_member_details">Family Member Details</label>
                                        <input type="" class="form-control rounded-0 bg-light" id="family_member_details"
                                            placeholder="Enter Family Member Details" name="family_member_details">
                                    </div>

                                    <!-- Monthly Income -->
                                    <div class="col-md-6 mb-3">
                                        <label for="monthly_income">Monthly Income</label>
                                        <input type="" class="form-control rounded-0 bg-light" id="monthly_income"
                                            placeholder="Enter Monthly Income" name="monthly_income">
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
                                        <input type="" class="form-control rounded-0 bg-light" id="monthly_expense"
                                            placeholder="Enter Monthly Expense" name="monthly_expense">
                                    </div>

                                    <!-- Zakat Eligibility -->
                                    <div class="col-md-6 mb-3">
                                        <label for="zakat_eligibility" class="d-block mb-2">Zakat Eligibility :</label>

                                        <div class="custom-control custom-radio d-inline-block mr-3 mb-2">
                                            <input type="radio" name="zakat_eligibility" class="custom-control-input" checked>
                                            <label class="custom-control-label" for="zakat_eligibility">Yes</label>
                                        </div>

                                        <div class="custom-control custom-radio d-inline-block mr-3 mb-2">
                                            <input type="radio" name="zakat_eligibility" class="custom-control-input">
                                            <label class="custom-control-label" for="zakat_eligibility">No</label>
                                        </div>
                                    </div>

                                    <!-- Comments / Notes -->
                                    <div class="col-md-6 mb-3">
                                        <label for="comment_note">Comments / Notes</label>
                                        <textarea class="form-control rounded-0 bg-light" id="comment_note" name="comment_note" rows="3"
                                            placeholder="Enter Comments / Notes"></textarea>
                                    </div>

                                    <!-- Income Source -->
                                    <div class="col-md-6 mb-3">
                                      <label for="volunteer_id" class="form-label fw-semibold">Volunteer</label>
                                      <select class="form-control rounded-0 bg-light select2" id="volunteer_id" name="volunteer_id" style="width: 100%;">
                                        <option selected disabled>Select Volunteer</option>


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
  });
</script>
@endpush
