@extends('layouts.app')

@section('title', 'Volunteer Registration Form')

@push('page-styles')
<link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
<style>
    /* Make cards equal height only when explicitly opted-in */
    .row.equal-height>[class*='col-'] {
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
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h4 class="card-title fw-bold mb-1">Volunteer Registration Form</h4>
                                <p class="text-muted mb-0">
                                    Purpose: To collect, vet, and assign volunteers efficiently.
                                </p>
                            </div>
                            <div>
                                <a href="{{ route('cms.volunteer-settings.index') }}"
                                    class="btn btn-sm btn-outline-secondary">
                                    Volunteer Settings
                                </a>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="w-100 mb-3">
                                    @include('partials.errors')
                                </div>
                                <form method="POST" action="{{ route('cms.volunteer.store') }}"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-row">

                                        <!-- Full Name -->
                                        <div class="col-md-6 mb-3">
                                            <label for="full_name">Full Name</label>
                                            <input type="text" class="form-control rounded-0 bg-light" id="full_name"
                                                name="full_name" placeholder="Enter Full Name">
                                        </div>

                                        <!-- CNIC / ID -->
                                        <div class="col-md-6 mb-3">
                                            <label for="cnic">CNIC / ID</label>
                                            <input type="text" class="form-control rounded-0 bg-light" id="cnic"
                                                name="cnic" placeholder="Enter CNIC / ID Number">
                                        </div>

                                        <!-- Contact Number -->
                                        <div class="col-md-6 mb-3">
                                            <label for="contact_number">Contact Number</label>
                                            <input type="text" class="form-control rounded-0 bg-light"
                                                id="contact_number" name="contact_number" placeholder="Enter Contact Number">
                                        </div>

                                        <!-- Email -->
                                        <div class="col-md-6 mb-3">
                                            <label for="email">Email</label>
                                            <input type="email" class="form-control rounded-0 bg-light" id="email"
                                                name="email" placeholder="Enter Email Address">
                                        </div>

                                        <!-- Gender -->
                                        <div class="col-md-6 mb-3">
                                            <label for="gender" class="d-block mb-2">Gender :</label>

                                            <div class="custom-control custom-radio d-inline-block mr-3 mb-2">
                                                <input type="radio" id="genderMale" name="gender" value="Male"
                                                    class="custom-control-input" {{ old('gender','Male')=='Male'
                                                    ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="genderMale">Male</label>
                                            </div>

                                            <div class="custom-control custom-radio d-inline-block mr-3 mb-2">
                                                <input type="radio" id="genderFemale" name="gender" value="Female"
                                                    class="custom-control-input" {{ old('gender')=='Female' ? 'checked'
                                                    : '' }}>
                                                <label class="custom-control-label" for="genderFemale">Female</label>
                                            </div>

                                            <div class="custom-control custom-radio d-inline-block mr-3 mb-2">
                                                <input type="radio" id="genderOther" name="gender" value="Other"
                                                    class="custom-control-input" {{ old('gender')=='Other' ? 'checked'
                                                    : '' }}>
                                                <label class="custom-control-label" for="genderOther">Other</label>
                                            </div>

                                        </div>

                                        <!-- Date of Birth -->
                                        <div class="col-md-6 mb-3">
                                            <label for="dob">Date of Birth</label>
                                            <input type="date" class="form-control rounded-0 bg-light" id="dob" name="dob">
                                        </div>

                                        <!-- Address -->
                                        <div class="col-md-6 mb-3">
                                            <label for="address">Address</label>
                                            <input type="text" class="form-control rounded-0 bg-light" id="address"
                                                name="address" placeholder="Enter Address">
                                        </div>

                                        <!-- Education Level -->
                                        <div class="col-md-6 mb-3">
                                            <label for="education_level_id">Education Level</label>
                                            <select class="form-control rounded-0 bg-light select2"
                                                id="education_level_id" name="education_level_id" style="width: 100%;">
                                                <option disabled {{ old('education_level_id') ? '' : 'selected' }}>
                                                    Select Education Level</option>
                                                @foreach($educationLevels as $level)
                                                <option value="{{ $level->id }}" {{ old('education_level_id')==$level->
                                                    id ? 'selected' : '' }}>{{ $level->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Skills / Expertise -->
                                        <div class="col-md-6 mb-3">
                                            <label for="skills">Skills / Expertise</label>
                                            <textarea class="form-control rounded-0 bg-light" id="skills" name="skills"
                                                rows="3" placeholder="Enter Skills / Expertise"></textarea>
                                        </div>

                                        <!-- Languages -->
                                        <div class="col-md-6 mb-3">
                                            <label for="language_ids">Languages</label> 
                                            <select class="form-control rounded-0 bg-light select2" id="language_ids"
                                                name="language_ids[]" multiple style="width: 100%;">
                                                @foreach($languages as $language)
                                                    <option value="{{ $language->id }}" {{ old('language_id') == $language->id ? 'selected' : '' }}>
                                                        {{ $language->name }} ({{ $language->code }})
                                                    </option>
                                                @endforeach

                                            </select>
                                        </div>

                                        

                                        <!-- Availability -->
                                        <div class="col-md-6 mb-3">
                                            <label for="availability_id">Availability</label>
                                            <select class="form-control rounded-0 bg-light select2" id="availability_id"
                                                name="availability_id" style="width: 100%;">
                                                <option disabled {{ old('availability_id') ? '' : 'selected' }}>Select
                                                    Availability</option>
                                                @foreach($availabilities as $a)
                                                <option value="{{ $a->id }}" {{ old('availability_id')==$a->id ?
                                                    'selected' : '' }}>{{ $a->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Preferred Project Type -->
                                        <div class="col-md-6 mb-3">
                                            <label for="preferred_project_type_ids">Preferred Project Type</label>
                                            @php $oldPref = collect(old('preferred_project_type_ids', [])); @endphp
                                            <select class="form-control rounded-0 bg-light select2"
                                                id="preferred_project_type_ids" name="preferred_project_type_ids[]"
                                                multiple style="width: 100%;">
                                                @foreach($projectTypes as $p)
                                                <option value="{{ $p->id }}" {{ $oldPref->contains($p->id) ? 'selected'
                                                    : '' }}>{{ $p->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Emergency Contact -->
                                        <div class="col-md-6 mb-3">
                                            <label for="emergency_contact">Emergency Contact</label>
                                            <input type="text" class="form-control rounded-0 bg-light"
                                                id="emergency_contact" name="emergency_contact"
                                                placeholder="Enter Emergency Contact Details">
                                        </div>

                                        <!-- Assigned Projects -->
                                        <div class="col-md-6 mb-3">
                                            <label for="assigned_project_type_ids">Assigned Projects</label>
                                            @php $oldAssigned = collect(old('assigned_project_type_ids', [])); @endphp
                                            <select class="form-control rounded-0 bg-light select2"
                                                id="assigned_project_type_ids" name="assigned_project_type_ids[]"
                                                multiple style="width: 100%;">
                                                @foreach($projectTypes as $p)
                                                <option value="{{ $p->id }}" {{ $oldAssigned->contains($p->id) ?
                                                    'selected' : '' }}>{{ $p->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Role / Designation -->
                                        <div class="col-md-6 mb-3">
                                            <label for="volunteer_role_id">Role / Designation</label>
                                            <select class="form-control rounded-0 bg-light select2"
                                                id="volunteer_role_id" name="volunteer_role_id" style="width: 100%;">
                                                <option disabled {{ old('volunteer_role_id') ? '' : 'selected' }}>Select
                                                    Role</option>
                                                @foreach($volunteerRoles as $r)
                                                <option value="{{ $r->id }}" {{ old('volunteer_role_id')==$r->id ?
                                                    'selected' : '' }}>{{ $r->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Joining Date -->
                                        <div class="col-md-6 mb-3">
                                            <label for="joining_date">Joining Date</label>
                                            <input type="date" class="form-control rounded-0 bg-light" id="joining_date" name="joining_date">
                                        </div>

                                        <!-- ID / Picture -->
                                        <div class="col-md-6 mb-3">
                                            <label for="picture">ID / Picture</label>
                                            <input type="file" class="form-control rounded-0 bg-light" id="picture"
                                                name="picture">
                                            <small class="text-muted">Upload volunteer ID or profile picture</small>
                                        </div>

                                        <!-- Remarks -->
                                        <div class="col-md-6 mb-3">
                                            <label for="remarks">Remarks</label>
                                            <textarea class="form-control rounded-0 bg-light" id="remarks"
                                                name="remarks" rows="3"
                                                placeholder="Enter any remarks or notes"></textarea>
                                        </div>

                                        @if(auth()->user() && auth()->user()->is_admin)
                                            <div class="col-12">
                                                <label class="d-block mb-1">Access Control</label>

                                                <div class="d-flex justify-content-between align-items-center mb-2 border rounded p-2">
                                                    <small class="text-muted mb-0">Full Admin Access (all pages)</small>
                                                    <div class="custom-control custom-checkbox mb-0">
                                                        <input type="hidden" name="is_admin" value="0">
                                                        <input type="checkbox" class="custom-control-input" id="is_admin" name="is_admin" value="1"
                                                            {{ old('is_admin') ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="is_admin"></label>
                                                    </div>
                                                </div>

                                                <small class="text-muted d-block mt-1">
                                                    If not admin, select which modules this volunteer can access:
                                                </small>
                                            </div>

                                            <div class="col-12">

                                                {{-- ----------------------------------}}
                                                {{-- DASHBOARD MODULE --}}
                                                {{-- ----------------------------------}}
                                                <h6 class="mt-3">Dashboard</h6>
                                                <hr class="mt-1 mb-2">

                                                <div class="custom-control custom-checkbox mb-2">
                                                    <input type="checkbox" class="custom-control-input" id="perm_dashboard"
                                                        name="permissions[dashboard]" value="1"
                                                        {{ old('permissions.dashboard') ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="perm_dashboard">Dashboard Access</label>
                                                </div>


                                                {{-- ----------------------------------}}
                                                {{-- FAMILY MODULE --}}
                                                {{-- ----------------------------------}}
                                                <h6 class="mt-4">Family Module</h6>
                                                <hr class="mt-1 mb-2">

                                                <div class="custom-control custom-checkbox mb-2">
                                                    <input type="checkbox" class="custom-control-input" id="perm_families"
                                                        name="permissions[families]" value="1"
                                                        {{ old('permissions.families') ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="perm_families">Families (Access)</label>
                                                </div>

                                                <div class="row">
                                                    <div class="col-4 mb-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="perm_families_create"
                                                                name="permissions[families_create]" value="1"
                                                                {{ old('permissions.families_create') ? 'checked' : '' }}>
                                                            <label class="custom-control-label" for="perm_families_create">Create</label>
                                                        </div>
                                                    </div>

                                                    <div class="col-4 mb-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="perm_families_edit"
                                                                name="permissions[families_edit]" value="1"
                                                                {{ old('permissions.families_edit') ? 'checked' : '' }}>
                                                            <label class="custom-control-label" for="perm_families_edit">Edit</label>
                                                        </div>
                                                    </div>

                                                    <div class="col-4 mb-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="perm_families_delete"
                                                                name="permissions[families_delete]" value="1"
                                                                {{ old('permissions.families_delete') ? 'checked' : '' }}>
                                                            <label class="custom-control-label" for="perm_families_delete">Delete</label>
                                                        </div>
                                                    </div>
                                                </div>



                                                {{-- ----------------------------------}}
                                                {{-- AID PROGRAM MODULE --}}
                                                {{-- ----------------------------------}}
                                                <h6 class="mt-4">Aid Programs</h6>
                                                <hr class="mt-1 mb-2">

                                                <div class="custom-control custom-checkbox mb-2">
                                                    <input type="checkbox" class="custom-control-input" id="perm_aid_programs"
                                                        name="permissions[aid_programs]" value="1"
                                                        {{ old('permissions.aid_programs') ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="perm_aid_programs">Aid Programs (Access)</label>
                                                </div>

                                                <div class="row">
                                                    <div class="col-4 mb-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="perm_aid_programs_create"
                                                                name="permissions[aid_programs_create]" value="1"
                                                                {{ old('permissions.aid_programs_create') ? 'checked' : '' }}>
                                                            <label class="custom-control-label" for="perm_aid_programs_create">Create</label>
                                                        </div>
                                                    </div>

                                                    <div class="col-4 mb-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="perm_aid_programs_edit"
                                                                name="permissions[aid_programs_edit]" value="1"
                                                                {{ old('permissions.aid_programs_edit') ? 'checked' : '' }}>
                                                            <label class="custom-control-label" for="perm_aid_programs_edit">Edit</label>
                                                        </div>
                                                    </div>

                                                    <div class="col-4 mb-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="perm_aid_programs_delete"
                                                                name="permissions[aid_programs_delete]" value="1"
                                                                {{ old('permissions.aid_programs_delete') ? 'checked' : '' }}>
                                                            <label class="custom-control-label" for="perm_aid_programs_delete">Delete</label>
                                                        </div>
                                                    </div>
                                                </div>



                                                {{-- ----------------------------------}}
                                                {{-- FINANCIAL MODULE --}}
                                                {{-- ----------------------------------}}
                                                <h6 class="mt-4">Financial Management</h6>
                                                <hr class="mt-1 mb-2">

                                                <div class="custom-control custom-checkbox mb-2">
                                                    <input type="checkbox" class="custom-control-input" id="perm_financial"
                                                        name="permissions[financial]" value="1"
                                                        {{ old('permissions.financial') ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="perm_financial">Financial (Access)</label>
                                                </div>

                                                <div class="row">
                                                    <div class="col-4 mb-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="perm_financial_create"
                                                                name="permissions[financial_create]" value="1"
                                                                {{ old('permissions.financial_create') ? 'checked' : '' }}>
                                                            <label class="custom-control-label" for="perm_financial_create">Create</label>
                                                        </div>
                                                    </div>

                                                    <div class="col-4 mb-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="perm_financial_edit"
                                                                name="permissions[financial_edit]" value="1"
                                                                {{ old('permissions.financial_edit') ? 'checked' : '' }}>
                                                            <label class="custom-control-label" for="perm_financial_edit">Edit</label>
                                                        </div>
                                                    </div>

                                                    <div class="col-4 mb-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="perm_financial_delete"
                                                                name="permissions[financial_delete]" value="1"
                                                                {{ old('permissions.financial_delete') ? 'checked' : '' }}>
                                                            <label class="custom-control-label" for="perm_financial_delete">Delete</label>
                                                        </div>
                                                    </div>
                                                </div>



                                                {{-- ----------------------------------}}
                                                {{-- SURVEYS MODULE --}}
                                                {{-- ----------------------------------}}
                                                <h6 class="mt-4">Surveys</h6>
                                                <hr class="mt-1 mb-2">

                                                <div class="custom-control custom-checkbox mb-2">
                                                    <input type="checkbox" class="custom-control-input" id="perm_surveys"
                                                        name="permissions[surveys]" value="1"
                                                        {{ old('permissions.surveys') ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="perm_surveys">Surveys (Access)</label>
                                                </div>

                                                <div class="row">
                                                    <div class="col-4 mb-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input"
                                                                id="perm_surveys_create" name="permissions[surveys_create]" value="1"
                                                                {{ old('permissions.surveys_create') ? 'checked' : '' }}>
                                                            <label class="custom-control-label" for="perm_surveys_create">Create</label>
                                                        </div>
                                                    </div>

                                                    <div class="col-4 mb-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input"
                                                                id="perm_surveys_edit" name="permissions[surveys_edit]" value="1"
                                                                {{ old('permissions.surveys_edit') ? 'checked' : '' }}>
                                                            <label class="custom-control-label" for="perm_surveys_edit">Edit</label>
                                                        </div>
                                                    </div>

                                                    <div class="col-4 mb-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input"
                                                                id="perm_surveys_delete" name="permissions[surveys_delete]" value="1"
                                                                {{ old('permissions.surveys_delete') ? 'checked' : '' }}>
                                                            <label class="custom-control-label" for="perm_surveys_delete">Delete</label>
                                                        </div>
                                                    </div>
                                                </div>



                                                {{-- ----------------------------------}}
                                                {{-- REPORTS MODULE --}}
                                                {{-- ----------------------------------}}
                                                <h6 class="mt-4">Reports</h6>
                                                <hr class="mt-1 mb-2">

                                                <div class="custom-control custom-checkbox mb-2">
                                                    <input type="checkbox" class="custom-control-input" id="perm_reports"
                                                        name="permissions[reports]" value="1"
                                                        {{ old('permissions.reports') ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="perm_reports">Reports (Access)</label>
                                                </div>

                                                <div class="row">
                                                    <div class="col-4 mb-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input"
                                                                id="perm_reports_create" name="permissions[reports_create]" value="1"
                                                                {{ old('permissions.reports_create') ? 'checked' : '' }}>
                                                            <label class="custom-control-label" for="perm_reports_create">Create</label>
                                                        </div>
                                                    </div>

                                                    <div class="col-4 mb-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input"
                                                                id="perm_reports_edit" name="permissions[reports_edit]" value="1"
                                                                {{ old('permissions.reports_edit') ? 'checked' : '' }}>
                                                            <label class="custom-control-label" for="perm_reports_edit">Edit</label>
                                                        </div>
                                                    </div>

                                                    <div class="col-4 mb-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input"
                                                                id="perm_reports_delete" name="permissions[reports_delete]" value="1"
                                                                {{ old('permissions.reports_delete') ? 'checked' : '' }}>
                                                            <label class="custom-control-label" for="perm_reports_delete">Delete</label>
                                                        </div>
                                                    </div>
                                                </div>



                                                {{-- ----------------------------------}}
                                                {{-- VOLUNTEERS MODULE --}}
                                                {{-- ----------------------------------}}
                                                <h6 class="mt-4">Volunteers</h6>
                                                <hr class="mt-1 mb-2">

                                                <div class="custom-control custom-checkbox mb-2">
                                                    <input type="checkbox" class="custom-control-input" id="perm_volunteers"
                                                        name="permissions[volunteers]" value="1"
                                                        {{ old('permissions.volunteers') ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="perm_volunteers">Volunteers (Access)</label>
                                                </div>

                                                <div class="row">
                                                    <div class="col-4 mb-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input"
                                                                id="perm_volunteers_create" name="permissions[volunteers_create]" value="1"
                                                                {{ old('permissions.volunteers_create') ? 'checked' : '' }}>
                                                            <label class="custom-control-label" for="perm_volunteers_create">Create</label>
                                                        </div>
                                                    </div>

                                                    <div class="col-4 mb-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input"
                                                                id="perm_volunteers_edit" name="permissions[volunteers_edit]" value="1"
                                                                {{ old('permissions.volunteers_edit') ? 'checked' : '' }}>
                                                            <label class="custom-control-label" for="perm_volunteers_edit">Edit</label>
                                                        </div>
                                                    </div>

                                                    <div class="col-4 mb-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input"
                                                                id="perm_volunteers_delete" name="permissions[volunteers_delete]" value="1"
                                                                {{ old('permissions.volunteers_delete') ? 'checked' : '' }}>
                                                            <label class="custom-control-label" for="perm_volunteers_delete">Delete</label>
                                                        </div>
                                                    </div>
                                                </div>



                                                {{-- ----------------------------------}}
                                                {{-- SETTINGS MODULE --}}
                                                {{-- ----------------------------------}}
                                                <h6 class="mt-4">Settings</h6>
                                                <hr class="mt-1 mb-2">

                                                <div class="custom-control custom-checkbox mb-2">
                                                    <input type="checkbox" class="custom-control-input"
                                                        id="perm_settings" name="permissions[settings]" value="1"
                                                        {{ old('permissions.settings') ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="perm_settings">Settings (Access)</label>
                                                </div>

                                                <div class="row">
                                                    <div class="col-4 mb-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input"
                                                                id="perm_settings_create" name="permissions[settings_create]" value="1"
                                                                {{ old('permissions.settings_create') ? 'checked' : '' }}>
                                                            <label class="custom-control-label" for="perm_settings_create">Create</label>
                                                        </div>
                                                    </div>

                                                    <div class="col-4 mb-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input"
                                                                id="perm_settings_edit" name="permissions[settings_edit]" value="1"
                                                                {{ old('permissions.settings_edit') ? 'checked' : '' }}>
                                                            <label class="custom-control-label" for="perm_settings_edit">Edit / Update</label>
                                                        </div>
                                                    </div>

                                                    <div class="col-4 mb-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input"
                                                                id="perm_settings_delete" name="permissions[settings_delete]" value="1"
                                                                {{ old('permissions.settings_delete') ? 'checked' : '' }}>
                                                            <label class="custom-control-label" for="perm_settings_delete">Delete</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                    </div>

                                    <button type="submit" class="btn btn-secondary btn-pill mt-2">
                                        <i class="mdi mdi-check-circle-outline me-1"></i> Save Volunteer
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
        $('.select2').select2({ width: '100%' });

        var groups = {
            families: ['perm_families_create','perm_families_edit','perm_families_delete'],
            aid_programs: ['perm_aid_programs_create','perm_aid_programs_edit','perm_aid_programs_delete'],
            financial: ['perm_financial_create','perm_financial_edit','perm_financial_delete'],
            surveys: ['perm_surveys_create','perm_surveys_edit','perm_surveys_delete'],
            reports: ['perm_reports_create','perm_reports_edit','perm_reports_delete'],
            volunteers: ['perm_volunteers_create','perm_volunteers_edit','perm_volunteers_delete'],
            settings: ['perm_settings_create','perm_settings_edit','perm_settings_delete']
        };

        function toggleGroup(mod){
            var full = $('#perm_'+mod).is(':checked');
            (groups[mod]||[]).forEach(function(id){
                $('#'+id).closest('.col-4').toggle(!full);
            });
        }

        Object.keys(groups).forEach(function(mod){
            toggleGroup(mod);
            $('#perm_'+mod).on('change', function(){ toggleGroup(mod); });
        });
    });
</script>
@endpush
