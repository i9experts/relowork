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
                  <label for="education_level_id">Education Level</label>
                  @php $edu = old('education_level_id', $volunteer->education_level_id); @endphp
                  <select class="form-control rounded-0 bg-light select2" id="education_level_id" name="education_level_id" style="width: 100%;">
                    <option disabled {{ $edu ? '' : 'selected' }}>Select Education Level</option>
                    @foreach($educationLevels as $level)
                      <option value="{{ $level->id }}" {{ (string)$edu === (string)$level->id ? 'selected' : '' }}>{{ $level->name }}</option>
                    @endforeach
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
                  <label for="language_ids">Languages</label>
                  @php $lng = collect(old('language_ids', $volunteer->language_ids ?? [])); @endphp
                  <select class="form-control rounded-0 bg-light select2" id="language_ids" name="language_ids[]" multiple style="width: 100%;">
                    @foreach($languages as $lang)
                      <option value="{{ $lang->id }}" {{ $lng->contains($lang->id) ? 'selected' : '' }}>{{ $lang->name }}</option>
                    @endforeach
                  </select>
                </div>

                

                <!-- Availability -->
                <div class="col-md-6 mb-3">
                  <label for="availability_id">Availability</label>
                  @php $av = old('availability_id', $volunteer->availability_id); @endphp
                  <select class="form-control rounded-0 bg-light select2" id="availability_id" name="availability_id" style="width: 100%;">
                    <option disabled {{ $av ? '' : 'selected' }}>Select Availability</option>
                    @foreach($availabilities as $a)
                      <option value="{{ $a->id }}" {{ (string)$av === (string)$a->id ? 'selected' : '' }}>{{ $a->name }}</option>
                    @endforeach
                  </select>
                </div>

                <!-- Preferred Project Type -->
                <div class="col-md-6 mb-3">
                  <label for="preferred_project_type_ids">Preferred Project Type</label>
                  @php $pp = collect(old('preferred_project_type_ids', $volunteer->preferred_project_type_ids ?? [])); @endphp
                  <select class="form-control rounded-0 bg-light select2" id="preferred_project_type_ids" name="preferred_project_type_ids[]" multiple style="width: 100%;">
                    @foreach($projectTypes as $p)
                      <option value="{{ $p->id }}" {{ $pp->contains($p->id) ? 'selected' : '' }}>{{ $p->name }}</option>
                    @endforeach
                  </select>
                </div>

                <!-- Emergency Contact -->
                <div class="col-md-6 mb-3">
                  <label for="emergency_contact">Emergency Contact</label>
                  <input type="text" class="form-control rounded-0 bg-light" id="emergency_contact"
                         name="emergency_contact" value="{{ old('emergency_contact', $volunteer->emergency_contact) }}" placeholder="0333-9876543">
                </div>

                <!-- Assigned Projects -->
                <div class="col-md-6 mb-3">
                  <label for="assigned_project_type_ids">Assigned Projects</label>
                  @php $ap = collect(old('assigned_project_type_ids', $volunteer->assigned_project_type_ids ?? [])); @endphp
                  <select class="form-control rounded-0 bg-light select2" id="assigned_project_type_ids" name="assigned_project_type_ids[]" multiple style="width: 100%;">
                    @foreach($projectTypes as $p)
                      <option value="{{ $p->id }}" {{ $ap->contains($p->id) ? 'selected' : '' }}>{{ $p->name }}</option>
                    @endforeach
                  </select>
                </div>

                <!-- Role / Designation -->
                <div class="col-md-6 mb-3">
                  <label for="volunteer_role_id">Role / Designation</label>
                  @php $roleId = old('volunteer_role_id', $volunteer->volunteer_role_id); @endphp
                  <select class="form-control rounded-0 bg-light select2" id="volunteer_role_id" name="volunteer_role_id" style="width: 100%;">
                    <option disabled {{ $roleId ? '' : 'selected' }}>Select Role</option>
                    @foreach($volunteerRoles as $r)
                      <option value="{{ $r->id }}" {{ (string)$roleId === (string)$r->id ? 'selected' : '' }}>{{ $r->name }}</option>
                    @endforeach
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

                @if(auth()->user() && auth()->user()->is_admin)

                    <div class="col-12">
                        <label class="d-block mb-1">Access Control</label>

                        <div class="d-flex justify-content-between align-items-center mb-2 border rounded p-2">
                            <small class="text-muted mb-0">Full Admin Access (all pages)</small>
                            <div class="custom-control custom-checkbox mb-0">
                                <input type="hidden" name="is_admin" value="0">
                                <input type="checkbox" class="custom-control-input" id="is_admin"
                                      name="is_admin" value="1"
                                      {{ old('is_admin', optional($volunteer->user)->is_admin) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_admin"></label>
                            </div>
                        </div>

                        <small class="text-muted d-block mt-1">
                            If not admin, select which modules this volunteer can access:
                        </small>
                    </div>

                    <div class="col-12">

                        {{-- ----------------------------------}}
                        {{-- FAMILY MODULE --}}
                        {{-- ----------------------------------}}
                        <h6 class="mt-4">Family Module</h6>
                        <hr class="mt-1">
                        <div class="row">
                        </div>
                        <div class="row">
                            <div class="col-4 mb-2">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="perm_families_create"
                                        name="permissions[families_create]" value="1"
                                        {{ old('permissions.families_create', $volunteer->user->permissions['families_create'] ?? null) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="perm_families_create">Create</label>
                                </div>
                            </div>

                            <div class="col-4 mb-2">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="perm_families_edit"
                                        name="permissions[families_edit]" value="1"
                                        {{ old('permissions.families_edit', $volunteer->user->permissions['families_edit'] ?? null) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="perm_families_edit">Edit</label>
                                </div>
                            </div>

                            <div class="col-4 mb-2">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="perm_families_delete"
                                        name="permissions[families_delete]" value="1"
                                        {{ old('permissions.families_delete', $volunteer->user->permissions['families_delete'] ?? null) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="perm_families_delete">Delete</label>
                                </div>
                            </div>
                        </div>



                        {{-- ----------------------------------}}
                        {{-- AID PROGRAM MODULE --}}
                        {{-- ----------------------------------}}
                        <h6 class="mt-4">Aid Programs</h6>
                        <hr class="mt-1">
                        <div class="row">
                            <div class="col-4 mb-2">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="perm_aid_programs_view"
                                        name="permissions[aid_programs_view]" value="1"
                                        {{ old('permissions.aid_programs_view', $volunteer->user->permissions['aid_programs_view'] ?? null) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="perm_aid_programs_view">View</label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-4 mb-2">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="perm_aid_programs_create"
                                        name="permissions[aid_programs_create]" value="1"
                                        {{ old('permissions.aid_programs_create', $volunteer->user->permissions['aid_programs_create'] ?? null) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="perm_aid_programs_create">Create</label>
                                </div>
                            </div>

                            <div class="col-4 mb-2">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="perm_aid_programs_edit"
                                        name="permissions[aid_programs_edit]" value="1"
                                        {{ old('permissions.aid_programs_edit', $volunteer->user->permissions['aid_programs_edit'] ?? null) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="perm_aid_programs_edit">Edit</label>
                                </div>
                            </div>

                            <div class="col-4 mb-2">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="perm_aid_programs_delete"
                                        name="permissions[aid_programs_delete]" value="1"
                                        {{ old('permissions.aid_programs_delete', $volunteer->user->permissions['aid_programs_delete'] ?? null) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="perm_aid_programs_delete">Delete</label>
                                </div>
                            </div>
                        </div>



                        {{-- ----------------------------------}}
                        {{-- FINANCIAL MODULE --}}
                        {{-- ----------------------------------}}
                        <h6 class="mt-4">Financial Management</h6>
                        <hr class="mt-1">
                        <div class="row">
                        </div>
                        <div class="row">
                            <div class="col-4 mb-2">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="perm_financial_create"
                                        name="permissions[financial_create]" value="1"
                                        {{ old('permissions.financial_create', $volunteer->user->permissions['financial_create'] ?? null) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="perm_financial_create">Create</label>
                                </div>
                            </div>

                            <div class="col-4 mb-2">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="perm_financial_edit"
                                        name="permissions[financial_edit]" value="1"
                                        {{ old('permissions.financial_edit', $volunteer->user->permissions['financial_edit'] ?? null) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="perm_financial_edit">Edit</label>
                                </div>
                            </div>

                            <div class="col-4 mb-2">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="perm_financial_delete"
                                        name="permissions[financial_delete]" value="1"
                                        {{ old('permissions.financial_delete', $volunteer->user->permissions['financial_delete'] ?? null) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="perm_financial_delete">Delete</label>
                                </div>
                            </div>
                            <div class="col-4 mb-2">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="perm_financial_export"
                                        name="permissions[financial_export]" value="1"
                                        {{ old('permissions.financial_export', $volunteer->user->permissions['financial_export'] ?? null) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="perm_financial_export">Export</label>
                                </div>
                            </div>
                        </div>



                        {{-- ----------------------------------}}
                        {{-- SURVEYS --}}
                        {{-- ----------------------------------}}
                        <h6 class="mt-4">Surveys</h6>
                        <hr class="mt-1">
                        <div class="row">
                            <div class="col-4 mb-2">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="perm_surveys_view"
                                        name="permissions[surveys_view]" value="1"
                                        {{ old('permissions.surveys_view', $volunteer->user->permissions['surveys_view'] ?? null) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="perm_surveys_view">View</label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-4 mb-2">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="perm_surveys_create"
                                        name="permissions[surveys_create]" value="1"
                                        {{ old('permissions.surveys_create', $volunteer->user->permissions['surveys_create'] ?? null) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="perm_surveys_create">Create</label>
                                </div>
                            </div>
                            <div class="col-4 mb-2">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="perm_surveys_edit"
                                        name="permissions[surveys_edit]" value="1"
                                        {{ old('permissions.surveys_edit', $volunteer->user->permissions['surveys_edit'] ?? null) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="perm_surveys_edit">Edit</label>
                                </div>
                            </div>
                            <div class="col-4 mb-2">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="perm_surveys_delete"
                                        name="permissions[surveys_delete]" value="1"
                                        {{ old('permissions.surveys_delete', $volunteer->user->permissions['surveys_delete'] ?? null) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="perm_surveys_delete">Delete</label>
                                </div>
                            </div>
                        </div>



                        {{-- ----------------------------------}} 
                        {{-- REPORTS --}} 
                        {{-- ----------------------------------}} 
                        <h6 class="mt-4">Reports</h6> 
                        <hr class="mt-1"> 
                        <div class="row"> 
                            <div class="col-4 mb-2"> 
                                <div class="custom-control custom-checkbox"> 
                                    <input type="checkbox" class="custom-control-input" id="perm_reports_view" 
                                        name="permissions[reports_view]" value="1" 
                                        {{ old('permissions.reports_view', $volunteer->user->permissions['reports_view'] ?? null) ? 'checked' : '' }}> 
                                    <label class="custom-control-label" for="perm_reports_view">View Report</label> 
                                </div> 
                            </div> 
                            <div class="col-4 mb-2"> 
                                <div class="custom-control custom-checkbox"> 
                                    <input type="checkbox" class="custom-control-input" id="perm_reports_download" 
                                        name="permissions[reports_download]" value="1" 
                                        {{ old('permissions.reports_download', $volunteer->user->permissions['reports_download'] ?? null) ? 'checked' : '' }}> 
                                    <label class="custom-control-label" for="perm_reports_download">Download</label> 
                                </div> 
                            </div> 
                            <div class="col-4 mb-2"> 
                                <div class="custom-control custom-checkbox"> 
                                    <input type="checkbox" class="custom-control-input" id="perm_reports_export_csv" 
                                        name="permissions[reports_export_csv]" value="1" 
                                        {{ old('permissions.reports_export_csv', $volunteer->user->permissions['reports_export_csv'] ?? null) ? 'checked' : '' }}> 
                                    <label class="custom-control-label" for="perm_reports_export_csv">Export CSV</label> 
                                </div> 
                            </div> 
                            <div class="col-4 mb-2"> 
                                <div class="custom-control custom-checkbox"> 
                                    <input type="checkbox" class="custom-control-input" id="perm_reports_download_pdf" 
                                        name="permissions[reports_download_pdf]" value="1" 
                                        {{ old('permissions.reports_download_pdf', $volunteer->user->permissions['reports_download_pdf'] ?? null) ? 'checked' : '' }}> 
                                    <label class="custom-control-label" for="perm_reports_download_pdf">Download PDF</label> 
                                </div> 
                            </div> 
                            <div class="col-4 mb-2"> 
                                <div class="custom-control custom-checkbox"> 
                                    <input type="checkbox" class="custom-control-input" id="perm_reports_download_all" 
                                        name="permissions[reports_download_all]" value="1" 
                                        {{ old('permissions.reports_download_all', $volunteer->user->permissions['reports_download_all'] ?? null) ? 'checked' : '' }}> 
                                    <label class="custom-control-label" for="perm_reports_download_all">Download All</label> 
                                </div> 
                            </div> 
                        </div> 



                        {{-- ----------------------------------}}
                        {{-- VOLUNTEERS --}}
                        {{-- ----------------------------------}}
                        <h6 class="mt-4">Volunteers</h6>
                        <hr class="mt-1">
                        <div class="row">
                            <div class="col-4 mb-2">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="perm_volunteers_view"
                                        name="permissions[volunteers_view]" value="1"
                                        {{ old('permissions.volunteers_view', $volunteer->user->permissions['volunteers_view'] ?? null) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="perm_volunteers_view">View</label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-4 mb-2">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="perm_volunteers_create"
                                        name="permissions[volunteers_create]" value="1"
                                        {{ old('permissions.volunteers_create', $volunteer->user->permissions['volunteers_create'] ?? null) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="perm_volunteers_create">Create</label>
                                </div>
                            </div>

                            <div class="col-4 mb-2">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="perm_volunteers_edit"
                                        name="permissions[volunteers_edit]" value="1"
                                        {{ old('permissions.volunteers_edit', $volunteer->user->permissions['volunteers_edit'] ?? null) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="perm_volunteers_edit">Edit</label>
                                </div>
                            </div>

                            <div class="col-4 mb-2">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="perm_volunteers_delete"
                                        name="permissions[volunteers_delete]" value="1"
                                        {{ old('permissions.volunteers_delete', $volunteer->user->permissions['volunteers_delete'] ?? null) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="perm_volunteers_delete">Delete</label>
                                </div>
                            </div>
                        </div>



                        {{-- ----------------------------------}}
                        {{-- SETTINGS --}}
                        {{-- ----------------------------------}}
                        <h6 class="mt-4">Settings</h6>
                        <hr class="mt-1">
                        <div class="row">
                            <div class="col-4 mb-2">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="perm_settings"
                                        name="permissions[settings]" value="1"
                                        {{ old('permissions.settings', $volunteer->user->permissions['settings'] ?? null) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="perm_settings">Access</label>
                                </div>
                            </div>
                        </div>

                    </div>

                @endif



                

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
