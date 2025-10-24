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
        <h4 class="card-title fw-bold mb-1">Aid Program Form</h4>
        <p class="text-muted mb-4">
          Purpose: To define, categorize, and track ongoing or past charity projects.
        </p>

        <div class="row">
          <div class="col-md-12">
            <form method="POST" action="" enctype="multipart/form-data">
              @csrf
              <div class="form-row">

                <!-- Program Title -->
                <div class="col-md-6 mb-3">
                  <label for="program_title">Program Title</label>
                  <input type="text" class="form-control rounded-0 bg-light" id="program_title"
                         name="program_title" placeholder="Enter Program Title">
                </div>

                <!-- Program Type -->
                <div class="col-md-6 mb-3">
                  <label for="program_type">Program Type</label>
                  <select class="form-control rounded-0 bg-light select2" id="program_type" name="program_type" style="width: 100%;">
                    <option selected disabled>Select Program Type</option>
                    <option value="Food">Food</option>
                    <option value="Education">Education</option>
                    <option value="Water">Water</option>
                    <option value="Medical">Medical</option>
                    <option value="Housing">Housing</option>
                    <option value="Emergency">Emergency</option>
                  </select>
                </div>

                <!-- Description -->
                <div class="col-md-6 mb-3">
                  <label for="description">Description</label>
                  <textarea class="form-control rounded-0 bg-light" id="description" name="description" rows="3"
                            placeholder="Enter detailed program description"></textarea>
                </div>

                <!-- Start Date -->
                <div class="col-md-6 mb-3">
                  <label for="start_date">Start Date</label>
                  <input type="date" class="form-control rounded-0 bg-light" id="start_date" name="start_date">
                </div>

                <!-- End Date -->
                <div class="col-md-6 mb-3">
                  <label for="end_date">End Date</label>
                  <input type="date" class="form-control rounded-0 bg-light" id="end_date" name="end_date">
                </div>

                <!-- Location -->
                <div class="col-md-6 mb-3">
                  <label for="location">Location</label>
                  <input type="text" class="form-control rounded-0 bg-light" id="location"
                         name="location" placeholder="Enter Location (e.g., Tharparkar, Sindh)">
                </div>

                <!-- Target Beneficiaries -->
                <div class="col-md-6 mb-3">
                  <label for="target_beneficiaries">Target Beneficiaries</label>
                  <input type="number" class="form-control rounded-0 bg-light" id="target_beneficiaries"
                         name="target_beneficiaries" placeholder="e.g., 50 families">
                </div>

                <!-- Status -->
                <div class="col-md-6 mb-3">
                  <label for="status">Status</label>
                  <select class="form-control rounded-0 bg-light select2" id="status" name="status" style="width: 100%;">
                    <option selected disabled>Select Status</option>
                    <option value="Planned">Planned</option>
                    <option value="Active">Active</option>
                    <option value="Completed">Completed</option>
                    <option value="On Hold">On Hold</option>
                  </select>
                </div>

                <!-- Total Budget -->
                <div class="col-md-6 mb-3">
                  <label for="total_budget">Total Budget (PKR)</label>
                  <input type="number" class="form-control rounded-0 bg-light" id="total_budget"
                         name="total_budget" placeholder="Enter Total Budget (PKR)">
                </div>

                <!-- Fund Source -->
                <div class="col-md-6 mb-3">
                  <label for="fund_source">Fund Source</label>
                  <select class="form-control rounded-0 bg-light select2" id="fund_source" name="fund_source" style="width: 100%;">
                    <option selected disabled>Select Fund Source</option>
                    <option value="Zakat">Zakat</option>
                    <option value="Sadaqah">Sadaqah</option>
                    <option value="General Donation">General Donation</option>
                    <option value="Grant">Grant</option>
                  </select>
                </div>

                <!-- Responsible Volunteer / Supervisor -->
                <div class="col-md-6 mb-3">
                  <label for="volunteer_id">Responsible Volunteer / Supervisor</label>
                  <select class="form-control rounded-0 bg-light select2" id="volunteer_id" name="volunteer_id" style="width: 100%;">
                    <option selected disabled>Select Volunteer / Supervisor</option>
                    <!-- Populate from volunteer list -->
                  </select>
                </div>

                <!-- Associated Families -->
                <div class="col-md-6 mb-3">
                  <label for="families">Associated Families</label>
                    <div class="form-group">
                    <select class="js-example-basic-multiple form-control" name="families[]" multiple="multiple" style="width: 100%;">
                        <!-- <option selected disabled>Select Associated Families</option> -->
                        <option value="AL">Alabana</option>
                        <option value="NY">New York</option>
                        <option value="VR">Virginia</option>
                        <option value="WA">Washington</option>
                        <option value="CA">California</option>
                        <option value="WY">Wyoming</option>
                    </select>
                    </div>
                </div>

                <!-- Monitoring Frequency -->
                <div class="col-md-6 mb-3">
                  <label for="monitoring_frequency">Monitoring Frequency</label>
                  <select class="form-control rounded-0 bg-light select2" id="monitoring_frequency" name="monitoring_frequency" style="width: 100%;">
                    <option selected disabled>Select Frequency</option>
                    <option value="Weekly">Weekly</option>
                    <option value="Monthly">Monthly</option>
                    <option value="Quarterly">Quarterly</option>
                  </select>
                </div>

                <!-- Progress Notes -->
                <div class="col-md-6 mb-3">
                  <label for="progress_notes">Progress Notes</label>
                  <textarea class="form-control rounded-0 bg-light" id="progress_notes" name="progress_notes" rows="3"
                            placeholder="Enter progress or inspection notes"></textarea>
                </div>

                <!-- Attachments -->
                <div class="col-md-6 mb-3">
                  <label for="attachments">Attachments</label>
                  <input type="file" class="form-control rounded-0 bg-light" id="attachments" name="attachments[]" multiple>
                  <small class="text-muted">Upload photos, reports, or receipts.</small>
                </div>

              </div>

              <button type="submit" class="btn btn-secondary btn-pill mt-2">
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
        $('#program_type, #status, #fund_source, #volunteer_id, #monitoring_frequency').select2({
            width: '100%'
        });
    });
</script>
@endpush
