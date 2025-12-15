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

            @if(($programs ?? collect())->isEmpty() || ($families ?? collect())->isEmpty())
              <div class="alert alert-warning d-flex align-items-start" role="alert">
                <div>
                  @if(($programs ?? collect())->isEmpty())
                    ⚠️ Please create an Aid Program first.
                    <a href="{{ route('cms.aid-program.create') }}" class="Danger-link">Create Aid Program</a><br>
                  @endif
                  @if(($families ?? collect())->isEmpty())
                    ⚠️ Please create a Family first.
                    <a href="{{ route('cms.family.create') }}" class="Danger-link">Create Family</a>
                  @endif
                </div>
              </div>
            @endif


            <div class="row">
              <div class="col-md-12">
                <form method="POST" action="{{ route('cms.financial-management.store') }}" enctype="multipart/form-data">
                  @csrf
                  <div class="form-row">

                    <!-- Date -->
                    <div class="col-md-6 mb-3">
                      <label for="date">Date</label>
                      <input type="date" class="form-control rounded-0 bg-light" id="date" name="date" value="{{ old('date') }}">
                    </div>

                    <!-- Transaction Type -->
                    <div class="col-md-6 mb-3">
                      <label for="transaction_type">Transaction Type</label>
                      <select class="form-control rounded-0 bg-light select2" id="transaction_type" name="transaction_type" style="width: 100%;">
                        <option>Select Transaction Type</option>
                        <option value="Donation" {{ old('transaction_type')=='Donation'?'selected':'' }}>Donation</option>
                        <option value="Expense" {{ old('transaction_type')=='Expense'?'selected':'' }}>Expense</option>
                        <option value="Disbursement" {{ old('transaction_type')=='Disbursement'?'selected':'' }}>Disbursement</option>
                        <option value="Transfer" {{ old('transaction_type')=='Transfer'?'selected':'' }}>Transfer</option>
                      </select>
                    </div>

                    <!-- Category -->
                    <div class="col-md-6 mb-3">
                      <label for="category">Category</label>
                      <select class="form-control rounded-0 bg-light select2" id="category" name="category" style="width: 100%;">
                        <option>Select Category</option>
                        @foreach($categories as $category)
                          <option value="{{ $category }}" {{ old('category')==$category?'selected':'' }}>{{ $category }}</option>
                        @endforeach
                      </select>
                    </div>

                    <!-- Mode of Payment -->
                    <div class="col-md-6 mb-3">
                      <label for="payment_mode">Mode of Payment</label>
                      <select class="form-control rounded-0 bg-light select2" id="payment_mode" name="payment_mode" style="width: 100%;">
                        <option disabled {{ old('payment_mode', $transaction->payment_mode ?? null) ? '' : 'selected' }}>Select Mode of Payment</option>
                        <option value="Cash" {{ old('payment_mode', $transaction->payment_mode ?? '')=='Cash'?'selected':'' }}>Cash</option>
                        <option value="Bank Transfer" {{ old('payment_mode', $transaction->payment_mode ?? '')=='Bank Transfer'?'selected':'' }}>Bank Transfer</option>
                        <option value="Easypaisa" {{ old('payment_mode', $transaction->payment_mode ?? '')=='Easypaisa'?'selected':'' }}>Easypaisa</option>
                        <option value="Cheque" {{ old('payment_mode', $transaction->payment_mode ?? '')=='Cheque'?'selected':'' }}>Cheque</option>
                        <option value="PayPal" {{ old('payment_mode', $transaction->payment_mode ?? '')=='PayPal'?'selected':'' }}>PayPal</option>
                        <option value="Bank Deposit" {{ old('payment_mode', $transaction->payment_mode ?? '')=='Bank Deposit'?'selected':'' }}>Bank Deposit</option>
                        <option value="Mobile Wallet" {{ old('payment_mode', $transaction->payment_mode ?? '')=='Mobile Wallet'?'selected':'' }}>Mobile Wallet</option>
                        <option value="Other" {{ old('payment_mode', $transaction->payment_mode ?? '')=='Other'?'selected':'' }}>Other</option>
                      </select>
                    </div>


                    <!-- Amount -->
                    <div class="col-md-6 mb-3">
                      <label for="amount">Amount (PKR)</label>
                      <input type="number" class="form-control rounded-0 bg-light" id="amount" name="amount" placeholder="Enter Amount" value="{{ old('amount') }}" min="1">
                    </div>

                    <!-- Donor Name -->
                    <div class="col-md-6 mb-3">
                      <label for="donor_name">Donor Name</label>
                      <input type="text" class="form-control rounded-0 bg-light" id="donor_name" name="donor_name" placeholder="Enter Donar Name" value="{{ old('donor_name') }}">
                    </div>

                    <!-- Aid Program -->
                    <div class="col-md-6 mb-3">
                      <label for="aid_program_id">Aid Program</label>
                      <select class="form-control rounded-0 bg-light select2" id="aid_program_id" name="aid_program_id" style="width: 100%;" required>
                        <option>Select Aid Program</option>
                        @foreach($programs as $p)
                          <option value="{{ $p->id }}" {{ old('aid_program_id')==$p->id?'selected':'' }}>{{ $p->program_title }}</option>
                        @endforeach
                      </select>
                    </div>

                    <!-- Family -->
                    <div class="col-md-6 mb-3">
                      <label for="family_id">Families</label>
                      <select class="form-control rounded-0 bg-light select2" id="family_id" name="family_id" style="width: 100%;" required>
                        <option >Select Family</option>
                        @foreach($families as $fam)
                          <option value="{{ $fam->id }}" {{ old('family_id')==$fam->id?'selected':'' }}>{{ (\Illuminate\Support\Str::startsWith($fam->family_code ?? '', 'FAM-') ? ('FAM-' . \Illuminate\Support\Str::afterLast($fam->family_code ?? '', '-')) : ($fam->family_code ?? '')) }} - {{ $fam->head_of_family_name }}</option>
                        @endforeach
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
                      <select class="form-control rounded-0 bg-light select2" id="recorded_by" name="recorded_by" style="width: 100%;" required>
                        <option>Select User</option>
                        @foreach($users as $u)
                          <option value="{{ $u->id }}" {{ old('recorded_by')==$u->id?'selected':'' }}>{{ $u->name }}</option>
                        @endforeach
                      </select>
                    </div>

                    <!-- Approved By -->
                    <div class="col-md-6 mb-3">
                      <label for="approved_by">Approved By</label>
                      <select class="form-control rounded-0 bg-light select2" id="approved_by" name="approved_by" style="width: 100%;">
                        <option>Select Supervisor</option>
                        @foreach($users as $u)
                          <option value="{{ $u->id }}" {{ old('approved_by')==$u->id?'selected':'' }}>{{ $u->name }}</option>
                        @endforeach
                      </select>
                    </div>

                    <!-- Remarks / Notes -->
                    <div class="col-md-12 mb-3">
                      <label for="remarks">Remarks / Notes</label>
                      <textarea class="form-control rounded-0 bg-light" id="remarks" name="remarks" rows="3" placeholder="Enter any justification or notes">{{ old('remarks') }}</textarea>
                    </div>

                  </div>

                  <button type="submit" class="btn btn-secondary btn-pill mt-2" {{ (($programs ?? collect())->isEmpty() || ($families ?? collect())->isEmpty()) ? 'disabled' : '' }}>
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
