@extends('layouts.app')

@section('title', 'Setting Form')

@push('page-styles')
<style>
  /* Make all cards in the same row equal height (scoped) */
  .settings-row > [class*='col-'] {
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

    <!-- Dashboard Header -->
    <div class="d-flex justify-content-between align-items-center mb-1 flex-wrap">
        <div>
            <h4 class="fw-bold text-dark mb-1">Settings</h4>
            <p class="text-muted fs-5 mb-0">Manage your application preferences and configurations</p>
        </div>

    </div>


    <hr class="mt-2 mb-2">

    <!-- Settings Form -->
    <div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <!-- General Section -->
                <div class="col-md-12 mt-3">
                    <hr>
                    <p class="text-center fw-bold mb-3"> General Section</p>
                    <hr>
                </div>  
                <h4 class="card-title fw-bold mb-1">Organization Information</h4>
                <p class="text-muted mb-4">Update your organization's basic information</p>
                <div class="row justify-content-center">
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

                                <div class="col-md-6 mb-3">
                                    <input type="organization_name" class="form-control rounded-0 bg-light" id="exampleFormControlInput5" placeholder="Enter Organization Name" name="organization_name" value="">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <input type="email_address" class="form-control rounded-0 bg-light" id="exampleFormControlInput5" placeholder="Enter Email Address" name="email_address" value="">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <input type="phone_number" class="form-control rounded-0 bg-light" id="exampleFormControlInput5" placeholder="Enter Phone Number" name="phone_number" value="">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <input type="address" class="form-control rounded-0 bg-light" id="exampleFormControlInput5" placeholder="Enter Address" name="address" value="">
                                </div>

                                <!-- Notifications Section -->
                                <div class="col-md-12 mt-3">
                                    <hr>
                                    <p class="text-center fw-bold mb-3"> Notifications Section</p>
                                    <hr>
                                </div>  

                               <!-- Notification Preferences Section -->
                                <div class="col-md-12 mt-3">
                                    <h4 class="card-title fw-bold mb-1">Notification Preferences</h4>
                                    <p class="text-muted mb-4">Manage how you receive notifications</p>
                                    <hr>
                                </div>  

                                
                                <div class="col-md-12 mb-3">
                                    <div class="d-flex justify-content-between align-items-center border-bottom py-3">
                                        <div>
                                            <h6 class="mb-1">Email Notifications</h6>
                                            <small class="text-muted">Receive email updates about activities</small>
                                        </div>
                                        <label class="switch switch-icon switch-outline-alt-secondary switch-pill mb-0">
                                            <input type="checkbox" class="switch-input form-check-input" checked>
                                            <span class="switch-label"></span>
                                            <span class="switch-handle"></span>
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <div class="d-flex justify-content-between align-items-center border-bottom py-3">
                                        <div>
                                            <h6 class="mb-1">Push Notifications</h6>
                                            <small class="text-muted">Receive push notifications on your device</small>
                                        </div>
                                        <label class="switch switch-icon switch-outline-alt-secondary switch-pill mb-0">
                                            <input type="checkbox" class="switch-input form-check-input" checked>
                                            <span class="switch-label"></span>
                                            <span class="switch-handle"></span>
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <div class="d-flex justify-content-between align-items-center border-bottom py-3">
                                        <div>
                                            <h6 class="mb-1">Aid approval Alerts</h6>
                                            <small class="text-muted">Get notified when aid requests need approval</small>
                                        </div>
                                        <label class="switch switch-icon switch-outline-alt-secondary switch-pill mb-0">
                                            <input type="checkbox" class="switch-input form-check-input" checked>
                                            <span class="switch-label"></span>
                                            <span class="switch-handle"></span>
                                        </label>
                                    </div>
                                </div>

                                 <!-- Security Section -->
                                <div class="col-md-12 mt-3">
                                    <hr>
                                    <p class="text-center fw-bold mb-3"> Security Section</p>
                                    <hr>
                                </div> 

                                <!-- Security Settings Section -->
                                <div class="col-md-12 mt-3">
                                    <h4 class="card-title fw-bold mb-1">Security Settings</h4>
                                    <p class="text-muted mb-4">Manage your account security preferences</p>
                                    <hr>
                                </div>  
                                
                                <div class="col-md-12 mb-3">
                                    <div class="d-flex justify-content-between align-items-center border-bottom py-3">
                                        <div>
                                            <h6 class="mb-1">Two-Factor Authentication</h6>
                                            <small class="text-muted">Add an extra layer of security to your account</small>
                                        </div>
                                        <label class="switch switch-icon switch-outline-alt-secondary switch-pill mb-0">
                                            <input type="checkbox" class="switch-input form-check-input" checked>
                                            <span class="switch-label"></span>
                                            <span class="switch-handle"></span>
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <div class="d-flex justify-content-between align-items-center border-bottom py-3">
                                        <div>
                                            <h6 class="mb-1">Session Timeout</h6>
                                            <small class="text-muted">Automatically log out after inactivity</small>
                                        </div>
                                        <label class="switch switch-icon switch-outline-alt-secondary switch-pill mb-0">
                                            <input type="checkbox" class="switch-input form-check-input" checked>
                                            <span class="switch-label"></span>
                                            <span class="switch-handle"></span>
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <div class="d-flex justify-content-between align-items-center border-bottom py-3">
                                        <div>
                                            <h6 class="mb-1">Change Password</h6>
                                            <small class="text-muted">Update your account password</small>
                                        </div>
                                        <button type="button" class="btn btn-outline-secondary btn-pill px-4"> Change Password
                                        </button>
                                    </div>
                                </div>

                                <!-- Appearance Section -->
                                <div class="col-md-12 mt-3">
                                    <hr>
                                    <p class="text-center fw-bold mb-3"> Appearance Section</p>
                                    <hr>
                                </div> 

                                <!-- Appearance Preferences Section -->
                                <div class="col-md-12 mt-3">
                                    <h4 class="card-title fw-bold mb-1">Appearance Preferences</h4>
                                    <p class="text-muted mb-4">Customize how the application looks</p>
                                    <hr>
                                </div>  

                                <div class="col-md-12 mb-3">
                                    <h6 class="mb-1">Theme :</h6>
                                    <small class="text-muted d-block mb-3">Choose your preferred theme</small>

                                    <div class="d-flex w-100 gap-2 theme-btn-group">
                                        <button type="button" class="btn btn-outline-secondary flex-fill mr-2 active">Light</button>
                                        <button type="button" class="btn btn-outline-secondary flex-fill">Dark</button>
                                    </div>
                                </div>

                                <!-- Appearance Preferences Section -->
                                <div class="col-md-12 mt-3">
                                    <hr>
                                </div>  

                                <div class="col-md-12 mb-3">
                                    <h6 class="mb-1">Language :</h6>
                                    <small class="text-muted d-block mb-3">Select your preferred language</small>

                                    <div class="d-flex w-100 gap-2 language-btn-group">
                                        <button type="button" class="btn btn-outline-secondary flex-fill mr-2 active">English</button>
                                        <button type="button" class="btn btn-outline-secondary flex-fill">Urdu</button>
                                    </div>
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
<!-- ✅ jQuery Script -->
<script>
$(document).ready(function() {
    // 🔹 For Theme buttons
    $('.theme-btn-group button').click(function() {
        $('.theme-btn-group button')
            .removeClass('active btn-secondary')
            .addClass('btn-outline-secondary');
        $(this)
            .addClass('active btn-secondary')
            .removeClass('btn-outline-secondary');
    });

    // 🔹 For Language buttons
    $('.language-btn-group button').click(function() {
        $('.language-btn-group button')
            .removeClass('active btn-secondary')
            .addClass('btn-outline-secondary');
        $(this)
            .addClass('active btn-secondary')
            .removeClass('btn-outline-secondary');
    });
});
</script>
@endpush
