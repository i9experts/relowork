@extends('layouts.app')

@section('title', 'financial_transaction_form')

@push('page-styles')
<link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
<style>
  .row > [class*='col-'] {
    display: flex;
  }
  .card {
    flex: 1 1 auto;
  }
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
            <h4 class="card-title fw-bold mb-1">Financial Transaction Form</h4>
            <p class="text-muted mb-4">
              Purpose: To record all charity inflows/outflows for audit and reporting.
            </p>

            <div class="row">
              <div class="col-md-12">
                <form method="POST" action="" enctype="multipart/form-data">
                  @csrf
                  <div class="form-row">

                    <!-- Date -->
                    <div class="col-md-6 mb-3">
                      <label for="date">Date</label>
                      <input type="date" class="form-control rounded-0 bg-light" id="date" name="date">
                    </div>

                    <!-- Transaction Type -->
                    <div class="col-md-6 mb-3">
                      <label for="transaction_type">Transaction Type</label>
                      <select class="form-control rounded-0 bg-light select2" id="transaction_type" name="transaction_type" style="width: 100%;">
                        <option selected disabled>Select Transaction Type</option>
                        <option value="Donation">Donation</option>
                        <option value="Expense">Expense</option>
                        <option value="Disbursement">Disbursement</option>
                        <option value="Transfer">Transfer</option>
                      </select>
                    </div>

                    <!-- Category -->
                    <div class="col-md-6 mb-3">
                      <label for="category">Category</label>
                      <select class="form-control rounded-0 bg-light select2" id="category" name="category" style="width: 100%;">
                        <option selected disabled>Select Category</option>
                        <option value="Zakat">Zakat</option>
                        <option value="Sadaqah">Sadaqah</option>
                        <option value="Project Fund">Project Fund</option>
                        <option value="Admin Cost">Admin Cost</option>
                      </select>
                    </div>

                    <!-- Mode of Payment -->
                    <div class="col-md-6 mb-3">
                      <label for="payment_mode">Mode of Payment</label>
                      <select class="form-control rounded-0 bg-light select2" id="payment_mode" name="payment_mode" style="width: 100%;">
                        <option selected disabled>Select Mode of Payment</option>
                        <option value="Cash">Cash</option>
                        <option value="Bank Transfer">Bank Transfer</option>
                        <option value="Easypaisa">Easypaisa</option>
                        <option value="Cheque">Cheque</option>
                      </select>
                    </div>

                    <!-- Amount -->
                    <div class="col-md-6 mb-3">
                      <label for="amount">Amount (PKR)</label>
                      <input type="number" class="form-control rounded-0 bg-light" id="amount" name="amount" placeholder="10000">
                    </div>

                    <!-- Donor Name -->
                    <div class="col-md-6 mb-3">
                      <label for="donor_name">Donor Name</label>
                      <input type="text" class="form-control rounded-0 bg-light" id="donor_name" name="donor_name" placeholder="e.g., Mr. Abdul Wahab">
                    </div>

                    <!-- Project / Program Linked -->
                    <div class="col-md-6 mb-3">
                      <label for="project_linked">Project / Program Linked</label>
                      <select class="form-control rounded-0 bg-light select2" id="project_linked" name="project_linked" style="width: 100%;">
                        <option selected disabled>Select Project / Program</option>
                        <option value="Education">Education Program</option>
                        <option value="Food">Food Distribution</option>
                        <option value="Medical">Medical Camp</option>
                        <option value="Ramadan">Ramadan Relief</option>
                      </select>
                    </div>

                    <!-- Beneficiary Family Linked -->
                    <div class="col-md-6 mb-3">
                      <label for="beneficiary_linked">Beneficiary Family Linked</label>
                      <select class="form-control rounded-0 bg-light select2" id="beneficiary_linked" name="beneficiary_linked" style="width: 100%;">
                        <option selected disabled>Select Family</option>
                        <option value="Family 001">Family 001</option>
                        <option value="Family 002">Family 002</option>
                        <option value="Family 003">Family 003</option>
                      </select>
                    </div>

                    <!-- Payment Proof -->
                    <div class="col-md-6 mb-3">
                      <label for="payment_proof">Payment Proof</label>
                      <input type="file" class="form-control rounded-0 bg-light" id="payment_proof" name="payment_proof">
                      <small class="text-muted">Upload receipt or screenshot</small>
                    </div>

                    <!-- Recorded By -->
                    <div class="col-md-6 mb-3">
                      <label for="recorded_by">Recorded By</label>
                      <select class="form-control rounded-0 bg-light select2" id="recorded_by" name="recorded_by" style="width: 100%;">
                        <option selected disabled>Select User</option>
                        <option value="Volunteer">Volunteer</option>
                        <option value="Admin">Admin</option>
                      </select>
                    </div>

                    <!-- Approved By -->
                    <div class="col-md-6 mb-3">
                      <label for="approved_by">Approved By</label>
                      <select class="form-control rounded-0 bg-light select2" id="approved_by" name="approved_by" style="width: 100%;">
                        <option selected disabled>Select Supervisor</option>
                        <option value="Supervisor A">Supervisor A</option>
                        <option value="Supervisor B">Supervisor B</option>
                      </select>
                    </div>

                    <!-- Remarks / Notes -->
                    <div class="col-md-12 mb-3">
                      <label for="remarks">Remarks / Notes</label>
                      <textarea class="form-control rounded-0 bg-light" id="remarks" name="remarks" rows="3" placeholder="Enter any justification or notes"></textarea>
                    </div>

                  </div>

                  <button type="submit" class="btn btn-secondary btn-pill mt-2">
                    <i class="mdi mdi-check-circle-outline me-1"></i> Save Transaction
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
