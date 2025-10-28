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
                        @include('partials.errors')
                        @php($current = optional($settings->first()))
                        <form method="POST" action="{{ $current->id ? route('cms.setting.update', $current->id) : route('cms.setting.store') }}" enctype="multipart/form-data">
                            @csrf
                            @if($current->id)
                              @method('PUT')
                            @endif
                            <div class="form-row">

                                <div class="col-md-6 mb-3">
                                    <input type="text" class="form-control rounded-0 bg-light" placeholder="Enter Organization Name" name="organization_name" value="{{ old('organization_name', optional($current)->organization_name) }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <input type="email" class="form-control rounded-0 bg-light" placeholder="Enter Email Address" name="email_address" value="{{ old('email_address', optional($current)->email_address) }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <input type="text" class="form-control rounded-0 bg-light" placeholder="Enter Phone Number" name="phone_number" value="{{ old('phone_number', optional($current)->phone_number) }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <input type="text" class="form-control rounded-0 bg-light" placeholder="Enter Address" name="address" value="{{ old('address', optional($current)->address) }}">
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
                                            <input type="hidden" name="email_notifications" value="0">
                                            <input type="checkbox" class="switch-input form-check-input" name="email_notifications" value="1" {{ old('email_notifications', optional($current)->email_notifications) ? 'checked' : '' }}>
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
                                            <input type="hidden" name="push_notifications" value="0">
                                            <input type="checkbox" class="switch-input form-check-input" name="push_notifications" value="1" {{ old('push_notifications', optional($current)->push_notifications) ? 'checked' : '' }}>
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
                                            <input type="hidden" name="aid_approval_alerts" value="0">
                                            <input type="checkbox" class="switch-input form-check-input" name="aid_approval_alerts" value="1" {{ old('aid_approval_alerts', optional($current)->aid_approval_alerts) ? 'checked' : '' }}>
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

                                <!-- Tooltip Modal -->
                                <div class="col-md-12 mb-3">
                                    <div class="d-flex justify-content-between align-items-center border-bottom py-3">
                                        <div>
                                            <h6 class="mb-1">Change Password</h6>
                                            <small class="text-muted">Update your account password</small>
                                        </div>
                                        <button type="button" class="btn btn-outline-secondary btn-pill px-4" data-toggle="modal" data-target="#exampleModalTooltip"> Change Password
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

                                    <input type="hidden" name="theme" id="themeHidden" value="{{ old('theme', optional($current)->theme ?? 'Light') }}">
                                    <div class="d-flex w-100 gap-2 theme-btn-group">
                                        <button type="button" class="btn btn-outline-secondary flex-fill mr-2" data-value="Light">Light</button>
                                        <button type="button" class="btn btn-outline-secondary flex-fill" data-value="Dark">Dark</button>
                                    </div>
                                </div>

                                <!-- Appearance Preferences Section -->
                                <div class="col-md-12 mt-3">
                                    <hr>
                                </div>  

                                <div class="col-md-12 mb-3">
                                    <h6 class="mb-1">Language :</h6>
                                    <small class="text-muted d-block mb-3">Select your preferred language</small>

                                    <input type="hidden" name="language" id="languageHidden" value="{{ old('language', optional($current)->language ?? 'English') }}">
                                    <div class="d-flex w-100 gap-2 language-btn-group">
                                        <button type="button" class="btn btn-outline-secondary flex-fill mr-2" data-value="English">English</button>
                                        <button type="button" class="btn btn-outline-secondary flex-fill" data-value="Urdu">Urdu</button>
                                    </div>
                                </div> 





                            </div>    

                                
                                <button type="submit" class="btn btn-secondary btn-pill">
                                  <i class="mdi mdi-check-circle-outline me-1"></i>
                                  {{ $current && $current->id ? 'Update' : 'Save' }}
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

<div class="modal fade" id="exampleModalTooltip" tabindex="-1" role="dialog" aria-labelledby="exampleModalTooltip"
  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <form method="POST" action="{{ route('cms.setting.change-password') }}" class="modal-content">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle2">Change Password</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        @include('partials.errors')
        <div class="col-md-12 mb-3">
            <input type="password" class="form-control rounded-0 bg-light" placeholder="Enter Current Password" name="current_password" required>
        </div>
        <div class="col-md-12 mb-3">
            <input type="password" class="form-control rounded-0 bg-light" placeholder="Enter New Password" name="password" required>
        </div>
        <div class="col-md-12">
            <input type="password" class="form-control rounded-0 bg-light" placeholder="Confirm New Password" name="password_confirmation" required>
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger btn-pill" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary btn-pill">Save Changes</button>
      </div>
    </form>
  </div>
</div>  
@endsection

@push('page-scripts')
<!-- ✅ jQuery Script -->
<script>
$(document).ready(function() {
    // Initialize Theme buttons based on hidden value
    var themeVal = $('#themeHidden').val() || 'Light';
    $('.theme-btn-group button').each(function(){
        var v = $(this).data('value');
        if (v === themeVal) { $(this).addClass('active btn-secondary').removeClass('btn-outline-secondary'); }
        else { $(this).removeClass('active btn-secondary').addClass('btn-outline-secondary'); }
    });
    $('.theme-btn-group button').click(function() {
        var v = $(this).data('value');
        $('#themeHidden').val(v);
        $('.theme-btn-group button').removeClass('active btn-secondary').addClass('btn-outline-secondary');
        $(this).addClass('active btn-secondary').removeClass('btn-outline-secondary');
    });

    // Initialize Language buttons based on hidden value
    var langVal = $('#languageHidden').val() || 'English';
    $('.language-btn-group button').each(function(){
        var v = $(this).data('value');
        if (v === langVal) { $(this).addClass('active btn-secondary').removeClass('btn-outline-secondary'); }
        else { $(this).removeClass('active btn-secondary').addClass('btn-outline-secondary'); }
    });
    $('.language-btn-group button').click(function() {
        var v = $(this).data('value');
        $('#languageHidden').val(v);
        $('.language-btn-group button').removeClass('active btn-secondary').addClass('btn-outline-secondary');
        $(this).addClass('active btn-secondary').removeClass('btn-outline-secondary');
    });
});
</script>
@endpush
