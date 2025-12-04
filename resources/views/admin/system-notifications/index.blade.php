@extends('layouts.app')
@section('title', 'System Notifications')
@section('content')
<div class="wrapper">
  <div class="content-wrapper">
    <div class="row mb-3">
      <div class="col-12">
        <div class="card card-default shadow-sm border-0 rounded-4">
          <div class="card-header d-flex align-items-center justify-content-between">
            <div>
              <h4 class="mb-1">System Notifications</h4>
              <p class="text-muted mb-0">Super Admin receives only system-level alerts</p>
            </div>
          </div>
          <div class="card-body">
            <div class="row g-3">
              <div class="col-md-4">
                <div class="p-3 border rounded-3 h-100">
                  <div class="d-flex align-items-center mb-2"><i class="mdi mdi-domain text-primary me-2" style="font-size:22px;"></i><strong>Organizations</strong></div>
                  <ul class="mb-0 text-muted">
                    <li>New Organization Registered</li>
                    <li>Organization Activated</li>
                    <li>Organization Deactivated</li>
                  </ul>
                </div>
              </div>
              <div class="col-md-4">
                <div class="p-3 border rounded-3 h-100">
                  <div class="d-flex align-items-center mb-2"><i class="mdi mdi-credit-card-alert text-primary me-2" style="font-size:22px;"></i><strong>Billing</strong></div>
                  <ul class="mb-0 text-muted">
                    <li>Subscription Failure</li>
                    <li>Plan Expiry</li>
                  </ul>
                </div>
              </div>
              <div class="col-md-4">
                <div class="p-3 border rounded-3 h-100">
                  <div class="d-flex align-items-center mb-2"><i class="mdi mdi-shield-alert text-primary me-2" style="font-size:22px;"></i><strong>Security</strong></div>
                  <ul class="mb-0 text-muted">
                    <li>High-Value Transactions</li>
                    <li>Multiple Failed Logins</li>
                    <li>Unusual Activity</li>
                  </ul>
                </div>
              </div>
              <div class="col-md-6">
                <div class="p-3 border rounded-3 h-100">
                  <div class="d-flex align-items-center mb-2"><i class="mdi mdi-bug text-primary me-2" style="font-size:22px;"></i><strong>System</strong></div>
                  <ul class="mb-0 text-muted">
                    <li>System Errors</li>
                    <li>Mail Delivery Failures</li>
                  </ul>
                </div>
              </div>
              <div class="col-md-6">
                <div class="p-3 border rounded-3 h-100">
                  <div class="d-flex align-items-center mb-2"><i class="mdi mdi-newspaper-variant-outline text-primary me-2" style="font-size:22px;"></i><strong>Digest</strong></div>
                  <ul class="mb-0 text-muted">
                    <li>Weekly Global Digest (Auto)</li>
                    <li>Core KPIs & Trends</li>
                  </ul>
                </div>
              </div>
            </div>

            <div class="mt-4 d-flex gap-2">
              <a href="{{ route('cms.admin.organizations') }}" class="btn btn-primary rounded-pill px-4">Manage Organizations</a>
              <a href="{{ route('cms.admin.users') }}" class="btn btn-outline-primary rounded-pill px-4">View Users</a>
              <a href="{{ route('cms.financial-management.index') }}" class="btn btn-outline-primary rounded-pill px-4">Financials</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
