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
            <div class="w-100 mb-3">
                @include('partials.errors')
            </div>      
            <h4 class="card-title fw-bold mb-1">Financial Transaction Form</h4>
            <p class="text-muted mb-4">
              Purpose: To record all charity inflows/outflows for audit and reporting.
            </p>

            <div class="row">
              <div class="col-md-12">
                <form method="POST" action="{{ route('cms.financial-management.update', $financialManagement->id) }}" enctype="multipart/form-data">
                  @csrf
                  @method('PUT')
                  <div class="form-row">

                    <!-- Date -->
                    <div class="col-md-6 mb-3">
                      <label for="date">Date</label>
                      <input type="date" class="form-control rounded-0 bg-light" id="date" name="date" value="{{ old('date', $financialManagement->date) }}">
                    </div>

                    <!-- Transaction Type -->
                    <div class="col-md-6 mb-3">
                      <label for="transaction_type">Transaction Type</label>
                      <select class="form-control rounded-0 bg-light select2" id="transaction_type" name="transaction_type" style="width: 100%;">
                        <option>Select Transaction Type</option>
                        @php($trxType = old('transaction_type', $financialManagement->transaction_type))
                        <option value="Donation" {{ $trxType=='Donation'?"selected":'' }}>Donation</option>
                        <option value="Expense" {{ $trxType=='Expense'?"selected":'' }}>Expense</option>
                        <option value="Disbursement" {{ $trxType=='Disbursement'?"selected":'' }}>Disbursement</option>
                        <option value="Transfer" {{ $trxType=='Transfer'?"selected":'' }}>Transfer</option>
                      </select>
                    </div>

                    <!-- Category -->
                    <div class="col-md-6 mb-3">
                      <label for="category">Category</label>
                      <select class="form-control rounded-0 bg-light select2" id="category" name="category" style="width: 100%;">
                        <option>Select Category</option>
                        @php($cat = old('category', $financialManagement->category))
                        @foreach($categories as $category)
                          <option value="{{ $category }}" {{ $cat==$category?'selected':'' }}>{{ $category }}</option>
                        @endforeach
                      </select>
                    </div>

                    <!-- Mode of Payment -->
                    <div class="col-md-6 mb-3">
                      <label for="payment_mode">Mode of Payment</label>
                      <select class="form-control rounded-0 bg-light select2" id="payment_mode" name="payment_mode" style="width: 100%;">
                        <option>Select Mode of Payment</option>
                        @php($pay = old('payment_mode', $financialManagement->payment_mode))
                        <option value="Cash" {{ $pay=='Cash'?'selected':'' }}>Cash</option>
                        <option value="Bank Transfer" {{ $pay=='Bank Transfer'?'selected':'' }}>Bank Transfer</option>
                        <option value="Easypaisa" {{ $pay=='Easypaisa'?'selected':'' }}>Easypaisa</option>
                        <option value="Cheque" {{ $pay=='Cheque'?'selected':'' }}>Cheque</option>
                      </select>
                    </div>

                    <!-- Amount -->
                    <div class="col-md-6 mb-3">
                      <label for="amount">Amount (PKR)</label>
                      <input type="number" class="form-control rounded-0 bg-light" id="amount" name="amount" placeholder="10000" value="{{ old('amount', $financialManagement->amount) }}" min="1">
                    </div>

                    <!-- Donor Name -->
                    <div class="col-md-6 mb-3">
                      <label for="donor_name">Donor Name</label>
                      <input type="text" class="form-control rounded-0 bg-light" id="donor_name" name="donor_name" placeholder="e.g., Mr. Abdul Wahab" value="{{ old('donor_name', $financialManagement->donor_name) }}">
                    </div>

                    <!-- Project / Program Linked -->
                    <div class="col-md-6 mb-3">
                      <label for="aid_program_id">Project / Program Linked</label>
                      <select class="form-control rounded-0 bg-light select2" id="aid_program_id" name="aid_program_id" style="width: 100%;">
                        <option>Select Project / Program</option>
                        @php($aid = old('aid_program_id', $financialManagement->aid_program_id))
                        @foreach($programs as $p)
                          <option value="{{ $p->id }}" {{ (string)$aid===(string)$p->id?'selected':'' }}>{{ $p->program_title }}</option>
                        @endforeach
                      </select>
                    </div>

                    <!-- Beneficiary Family Linked -->
                    <div class="col-md-6 mb-3">
                      <label for="family_id">Beneficiary Family Linked</label>
                      <select class="form-control rounded-0 bg-light select2" id="family_id" name="family_id" style="width: 100%;">
                        <option >Select Family</option>
                        @php($famSel = old('family_id', $financialManagement->family_id))
                        @foreach($families as $fam)
                          <option value="{{ $fam->id }}" {{ (string)$famSel===(string)$fam->id?'selected':'' }}>{{ $fam->family_code }} - {{ $fam->head_of_family_name }}</option>
                        @endforeach
                      </select>
                    </div>

                    <!-- Payment Proof -->
                    <div class="col-md-6 mb-3">
                      <label for="payment_proof">Payment Proof</label>
                      <input type="file" class="form-control rounded-0 bg-light" id="payment_proof" name="payment_proof">
                      <small class="text-muted">Upload receipt or screenshot</small>
                      @if(!empty($financialManagement->payment_proof_path))
                        <div class="mt-2">
                          <a class="btn btn-sm btn-outline-primary" href="{{ asset('storage/'.$financialManagement->payment_proof_path) }}" target="_blank">View Current Proof</a>
                        </div>
                      @endif
                    </div>

                    <!-- Recorded By -->
                    <div class="col-md-6 mb-3">
                      <label for="recorded_by">Recorded By</label>
                      <select class="form-control rounded-0 bg-light select2" id="recorded_by" name="recorded_by" style="width: 100%;">
                        <option>Select User</option>
                        @php($rec = old('recorded_by', $financialManagement->recorded_by))
                        @foreach($users as $u)
                          <option value="{{ $u->id }}" {{ (string)$rec===(string)$u->id?'selected':'' }}>{{ $u->name }}</option>
                        @endforeach
                      </select>
                    </div>

                    <!-- Approved By -->
                    <div class="col-md-6 mb-3">
                      <label for="approved_by">Approved By</label>
                      <select class="form-control rounded-0 bg-light select2" id="approved_by" name="approved_by" style="width: 100%;">
                        <option>Select Supervisor</option>
                        @php($app = old('approved_by', $financialManagement->approved_by))
                        @foreach($users as $u)
                          <option value="{{ $u->id }}" {{ (string)$app===(string)$u->id?'selected':'' }}>{{ $u->name }}</option>
                        @endforeach
                      </select>
                    </div>

                    <!-- Remarks / Notes -->
                    <div class="col-md-12 mb-3">
                      <label for="remarks">Remarks / Notes</label>
                      <textarea class="form-control rounded-0 bg-light" id="remarks" name="remarks" rows="3" placeholder="Enter any justification or notes">{{ old('remarks', $financialManagement->remarks) }}</textarea>
                    </div>

                  </div>

                  <button type="submit" class="btn btn-secondary btn-pill mt-2">
                    <i class="mdi mdi-check-circle-outline me-1"></i> Update Transaction
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
