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
                <div class="row">
                    <div class="col-12">
                        <label class="d-block mb-1">Access Control</label>

                        <div class="d-flex justify-content-between align-items-center mb-2 border rounded p-2">
                            <small class="text-muted mb-0">Full Admin Access (all pages)</small>
                            <div class="custom-control custom-checkbox mb-0">
                                <input type="hidden" name="is_admin" value="0">
                                <input type="checkbox" class="custom-control-input" id="is_admin"
                                      name="is_admin" value="1" {{ old('is_admin', optional($volunteer->user)->is_admin) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_admin"></label>
                            </div>
                        </div>

                        <small class="text-muted d-block mt-1">
                            If not admin, select which modules this volunteer can access:
                        </small>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-12 d-flex flex-wrap">

                        <div class="col-6 mb-1">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input"
                                      id="perm_dashboard" name="permissions[dashboard]" value="1"
                                      {{ old('permissions.dashboard', optional($volunteer->user)->permissions['dashboard'] ?? null) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="perm_dashboard">Dashboard</label>
                            </div>
                        </div>

                        <div class="col-6 mb-1">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input"
                                      id="perm_families" name="permissions[families]" value="1"
                                      {{ old('permissions.families', optional($volunteer->user)->permissions['families'] ?? null) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="perm_families">Families</label>
                            </div>
                        </div>

                        <div class="col-6 mb-1">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input"
                                      id="perm_aid_programs" name="permissions[aid_programs]" value="1"
                                      {{ old('permissions.aid_programs', optional($volunteer->user)->permissions['aid_programs'] ?? null) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="perm_aid_programs">Aid Programs</label>
                            </div>
                        </div>

                        <div class="col-6 mb-1">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input"
                                      id="perm_financial" name="permissions[financial]" value="1"
                                      {{ old('permissions.financial', optional($volunteer->user)->permissions['financial'] ?? null) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="perm_financial">Financial Management</label>
                            </div>
                        </div>

                        <div class="col-6 mb-1">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input"
                                      id="perm_surveys" name="permissions[surveys]" value="1"
                                      {{ old('permissions.surveys', optional($volunteer->user)->permissions['surveys'] ?? null) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="perm_surveys">Surveys</label>
                            </div>
                        </div>

                        <div class="col-6 mb-1">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input"
                                      id="perm_reports" name="permissions[reports]" value="1"
                                      {{ old('permissions.reports', optional($volunteer->user)->permissions['reports'] ?? null) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="perm_reports">Reports</label>
                            </div>
                        </div>

                        <div class="col-6 mb-1">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input"
                                      id="perm_volunteers" name="permissions[volunteers]" value="1"
                                      {{ old('permissions.volunteers', optional($volunteer->user)->permissions['volunteers'] ?? null) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="perm_volunteers">Volunteers (access module)</label>
                            </div>
                        </div>

                        <div class="col-6 mb-1">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input"
                                      id="perm_volunteers_create" name="permissions[volunteers_create]" value="1"
                                      {{ old('permissions.volunteers_create', optional($volunteer->user)->permissions['volunteers_create'] ?? null) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="perm_volunteers_create">Volunteers - Create / Add</label>
                            </div>
                        </div>

                        <div class="col-6 mb-1">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input"
                                      id="perm_volunteers_edit" name="permissions[volunteers_edit]" value="1"
                                      {{ old('permissions.volunteers_edit', optional($volunteer->user)->permissions['volunteers_edit'] ?? null) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="perm_volunteers_edit">Volunteers - Edit</label>
                            </div>
                        </div>

                        <div class="col-6 mb-1">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input"
                                      id="perm_volunteers_delete" name="permissions[volunteers_delete]" value="1"
                                      {{ old('permissions.volunteers_delete', optional($volunteer->user)->permissions['volunteers_delete'] ?? null) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="perm_volunteers_delete">Volunteers - Delete</label>
                            </div>
                        </div>

                        <div class="col-6 mb-1">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input"
                                      id="perm_settings" name="permissions[settings]" value="1"
                                      {{ old('permissions.settings', optional($volunteer->user)->permissions['settings'] ?? null) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="perm_settings">Settings</label>
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
