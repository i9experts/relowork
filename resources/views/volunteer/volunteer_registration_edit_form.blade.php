@extends('layouts.app')

@section('title', 'Edit Volunteer')

@push('page-styles')
<link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
<style>
  .row.equal-height > [class*='col-'] { display: flex; }
  .card { flex: 1 1 auto; }
  .card-header p.small { font-size: 0.85rem; color: #6c757d; margin-top: 4px; }
</style>
@endpush

@section('content')
<div class="wrapper">
  <div class="content-wrapper">

  <div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title fw-bold mb-1">Edit Volunteer</h4>
        <p class="text-muted mb-4">Update volunteer details.</p>

        <div class="row">
          <div class="col-md-12">
            <div class="w-100 mb-3">
              @include('partials.errors')
            </div>
            <form method="POST" action="{{ route('cms.volunteer.update', $volunteer->id) }}" enctype="multipart/form-data">
              @csrf
              @method('PUT')
              <div class="form-row">

                <!-- Full Name -->
                <div class="col-md-6 mb-3">
                  <label for="full_name">Full Name</label>
                  <input type="text" class="form-control rounded-0 bg-light" id="full_name"
                         name="full_name" value="{{ old('full_name', $volunteer->full_name) }}" placeholder="Enter Full Name">
                </div>

                <!-- CNIC / ID -->
                <div class="col-md-6 mb-3">
                  <label for="cnic">CNIC / ID</label>
                  <input type="text" class="form-control rounded-0 bg-light" id="cnic"
                         name="cnic" value="{{ old('cnic', $volunteer->cnic) }}" placeholder="42201-1234567-9">
                </div>

                <!-- Contact Number -->
                <div class="col-md-6 mb-3">
                  <label for="contact_number">Contact Number</label>
                  <input type="text" class="form-control rounded-0 bg-light" id="contact_number"
                         name="contact_number" value="{{ old('contact_number', $volunteer->contact_number) }}" placeholder="+92-322-7654321">
                </div>

                <!-- Email -->
                <div class="col-md-6 mb-3">
                  <label for="email">Email</label>
                  <input type="email" class="form-control rounded-0 bg-light" id="email"
                         name="email" value="{{ old('email', $volunteer->email) }}" placeholder="ahmed@example.com">
                </div>

                <!-- Gender -->
                <div class="col-md-6 mb-3">
                    <label for="gender" class="d-block mb-2">Gender :</label>

                    @php $g = old('gender', $volunteer->gender); @endphp
                    <div class="custom-control custom-radio d-inline-block mr-3 mb-2">
                        <input type="radio" id="genderMale" name="gender" value="Male" class="custom-control-input" {{ ($g ?? 'Male')=='Male' ? 'checked' : '' }}>
                        <label class="custom-control-label" for="genderMale">Male</label>
                    </div>

                    <div class="custom-control custom-radio d-inline-block mr-3 mb-2">
                        <input type="radio" id="genderFemale" name="gender" value="Female" class="custom-control-input" {{ ($g)=='Female' ? 'checked' : '' }}>
                        <label class="custom-control-label" for="genderFemale">Female</label>
                    </div>

                    <div class="custom-control custom-radio d-inline-block mr-3 mb-2">
                        <input type="radio" id="genderOther" name="gender" value="Other" class="custom-control-input" {{ ($g)=='Other' ? 'checked' : '' }}>
                        <label class="custom-control-label" for="genderOther">Other</label>
                    </div>

                </div>

                <!-- Date of Birth -->
                <div class="col-md-6 mb-3">
                  <label for="dob">Date of Birth</label>
                  <input type="date" class="form-control rounded-0 bg-light" id="dob" name="dob" value="{{ old('dob', optional($volunteer->dob)->format('Y-m-d')) }}">
                </div>

                <!-- Address -->
                <div class="col-md-6 mb-3">
                  <label for="address">Address</label>
                  <input type="text" class="form-control rounded-0 bg-light" id="address"
                         name="address" value="{{ old('address', $volunteer->address) }}" placeholder="Enter Address (e.g., Karachi)">
                </div>

                <!-- Education Level -->
                <div class="col-md-6 mb-3">
                  <label for="education_level">Education Level</label>
                  @php $edu = old('education_level', $volunteer->education_level); @endphp
                  <select class="form-control rounded-0 bg-light select2" id="education_level" name="education_level" style="width: 100%;">
                    <option disabled {{ $edu ? '' : 'selected' }}>Select Education Level</option>
                    <option value="Matric" {{ $edu=='Matric' ? 'selected' : '' }}>Matric</option>
                    <option value="Intermediate" {{ $edu=='Intermediate' ? 'selected' : '' }}>Intermediate</option>
                    <option value="Graduate" {{ $edu=='Graduate' ? 'selected' : '' }}>Graduate</option>
                    <option value="Post-Graduate" {{ $edu=='Post-Graduate' ? 'selected' : '' }}>Post-Graduate</option>
                  </select>
                </div>

                <!-- Skills / Expertise -->
                <div class="col-md-6 mb-3">
                  <label for="skills">Skills / Expertise</label>
                  <textarea class="form-control rounded-0 bg-light" id="skills" name="skills" rows="3"
                            placeholder="e.g., Management, Data Entry, Field Work">{{ old('skills', $volunteer->skills) }}</textarea>
                </div>

                <!-- Languages -->
                <div class="col-md-6 mb-3">
                  <label for="languages">Languages</label>
                  <input type="text" class="form-control rounded-0 bg-light" id="languages"
                         name="languages" value="{{ old('languages', $volunteer->languages) }}" placeholder="e.g., Urdu, Sindhi, English">
                </div>

                <!-- Availability -->
                <div class="col-md-6 mb-3">
                  <label for="availability">Availability</label>
                  @php $av = old('availability', $volunteer->availability); @endphp
                  <select class="form-control rounded-0 bg-light select2" id="availability" name="availability" style="width: 100%;">
                    <option disabled {{ $av ? '' : 'selected' }}>Select Availability</option>
                    <option value="Full-time" {{ $av=='Full-time' ? 'selected' : '' }}>Full-time</option>
                    <option value="Part-time" {{ $av=='Part-time' ? 'selected' : '' }}>Part-time</option>
                    <option value="Event-based" {{ $av=='Event-based' ? 'selected' : '' }}>Event-based</option>
                  </select>
                </div>

                <!-- Preferred Project Type -->
                <div class="col-md-6 mb-3">
                  <label for="preferred_projects">Preferred Project Type</label>
                  @php $pp = collect(old('preferred_projects', $volunteer->preferred_projects ?? [])); @endphp
                  <select class="form-control rounded-0 bg-light select2" id="preferred_projects" name="preferred_projects[]" multiple style="width: 100%;">
                    <option value="Education" {{ $pp->contains('Education') ? 'selected' : '' }}>Education</option>
                    <option value="Food" {{ $pp->contains('Food') ? 'selected' : '' }}>Food</option>
                    <option value="Medical" {{ $pp->contains('Medical') ? 'selected' : '' }}>Medical</option>
                  </select>
                </div>

                <!-- Emergency Contact -->
                <div class="col-md-6 mb-3">
                  <label for="emergency_contact">Emergency Contact</label>
                  <input type="text" class="form-control rounded-0 bg-light" id="emergency_contact"
                         name="emergency_contact" value="{{ old('emergency_contact', $volunteer->emergency_contact) }}" placeholder="0333-9876543">
                </div>

                <!-- Assigned Projects (placeholder same as create) -->
                <div class="col-md-6 mb-3">
                  <label for="assigned_projects">Assigned Projects</label>
                    <select class="form-control rounded-0 bg-light select2" id="assigned_projects" name="assigned_projects[]" multiple style="width: 100%;">
                        <option value="Aid">Aid Program</option>
                        <option value="Cid">Cid Program</option>
                    </select>
                </div>

                <!-- Role / Designation -->
                <div class="col-md-6 mb-3">
                  <label for="role">Role / Designation</label>
                  @php $role = old('role', $volunteer->role); @endphp
                  <select class="form-control rounded-0 bg-light select2" id="role" name="role" style="width: 100%;">
                    <option disabled {{ $role ? '' : 'selected' }}>Select Role</option>
                    <option value="Field Volunteer" {{ $role=='Field Volunteer' ? 'selected' : '' }}>Field Volunteer</option>
                    <option value="Data Verifier" {{ $role=='Data Verifier' ? 'selected' : '' }}>Data Verifier</option>
                    <option value="Supervisor" {{ $role=='Supervisor' ? 'selected' : '' }}>Supervisor</option>
                  </select>
                </div>

                <!-- Joining Date -->
                <div class="col-md-6 mb-3">
                  <label for="joining_date">Joining Date</label>
                  <input type="date" class="form-control rounded-0 bg-light" id="joining_date" name="joining_date" value="{{ old('joining_date', optional($volunteer->joining_date)->format('Y-m-d')) }}">
                </div>

                <!-- ID / Picture -->
                <div class="col-md-6 mb-3">
                  <label for="picture">ID / Picture</label>
                  <input type="file" class="form-control rounded-0 bg-light" id="picture" name="picture">
                  <small class="text-muted">Upload volunteer ID or profile picture</small>
                </div>

                <!-- Remarks -->
                <div class="col-md-6 mb-3">
                  <label for="remarks">Remarks</label>
                  <textarea class="form-control rounded-0 bg-light" id="remarks" name="remarks" rows="3"
                            placeholder="Enter any remarks or notes">{{ old('remarks', $volunteer->remarks) }}</textarea>
                </div>

                <!-- Status -->
                <div class="col-md-6 mb-3">
                  <label for="status">Status</label>
                  @php $currentStatus = old('status', $volunteer->status ?? 'Active'); @endphp
                  <select class="form-control rounded-0 bg-light select2" id="status" name="status">
                    <option value="Active" {{ $currentStatus=='Active' ? 'selected' : '' }}>Active</option>
                    <option value="Inactive" {{ $currentStatus=='Inactive' ? 'selected' : '' }}>Inactive</option>
                  </select>
                </div>

              </div>

              <button type="submit" class="btn btn-secondary btn-pill mt-2">
                <i class="mdi mdi-check-circle-outline me-1"></i> Update Volunteer
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
