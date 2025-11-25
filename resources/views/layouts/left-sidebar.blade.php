<!-- LEFT SIDEBAR -->
<aside class="left-sidebar sidebar-dark" id="left-sidebar">
  <div id="sidebar" class="sidebar sidebar-with-footer">
    <!-- Application Brand -->
    <div class="app-brand">
      @php($orgId = optional(request()->user())->organization_id ?? session('organization_id'))
      @php($settings = $orgId ? optional(\App\Models\Setting::where('organization_id', $orgId)->first()) : null)
      <a href="{{ route('cms.dashboard.index') }}" class="d-flex align-items-center justify-content-center py-2">
        @if($settings && $settings->logo_path)
          <img src="{{ asset('storage/'.$settings->logo_path) }}" alt="Logo" class="brand-logo" />
        @else
          <span class="brand-name mb-0">Logo</span>
        @endif
      </a>

    </div>

      <!-- Sidebar scrollable area -->
    <div class="sidebar-left" data-simplebar style="height: 100%;">
      <!-- Sidebar menu -->
      @php($user = auth()->user())
      <ul class="nav sidebar-inner" id="sidebar-menu">
        
        @if(!$user || $user->hasPermission('dashboard'))
        <li class="{{ request()->routeIs('cms.dashboard.*') ? 'active' : '' }}">
          <a class="sidenav-item-link {{ request()->routeIs('cms.dashboard.*') ? 'active' : '' }}" href="{{ route('cms.dashboard.index') }}">
            <i class="mdi mdi-view-dashboard"></i>
            <span class="nav-text">Dashboard</span>
          </a>
        </li>
        @endif

        @if(!$user || $user->hasPermission('families'))
        <li class="{{ request()->routeIs('cms.family.*') ? 'active' : '' }}">
          <a class="sidenav-item-link {{ request()->routeIs('cms.family.*') ? 'active' : '' }}" href="{{ route('cms.family.index') }}">
            <i class="mdi mdi-home"></i>
            <span class="nav-text">Families</span>
          </a>
        </li>
        @endif

        @if(!$user || $user->hasPermission('aid_programs'))
        <li class="{{ request()->routeIs('cms.aid-program.*') ? 'active' : '' }}">
          <a class="sidenav-item-link {{ request()->routeIs('cms.aid-program.*') ? 'active' : '' }}" href="{{ route('cms.aid-program.index') }}">
            <i class="mdi mdi-heart"></i>
            <span class="nav-text">Aid Programs</span>
          </a>
        </li>
        @endif

        @if(!$user || $user->hasPermission('financial'))
        <li class="{{ request()->routeIs('cms.financial-management.*') ? 'active' : '' }}">
          <a class="sidenav-item-link {{ request()->routeIs('cms.financial-management.*') ? 'active' : '' }}" href="{{ route('cms.financial-management.index') }}">
            <i class="mdi mdi-cash-multiple"></i>
            <span class="nav-text">Financials</span>
          </a>
        </li>
        @endif

        @if(!$user || $user->hasPermission('surveys'))
        <li class="{{ request()->routeIs('cms.survey.*') ? 'active' : '' }}">
          <a class="sidenav-item-link {{ request()->routeIs('cms.survey.*') ? 'active' : '' }}" href="{{ route('cms.survey.index') }}">
            <i class="mdi mdi-clipboard-text"></i>
            <span class="nav-text">Surveys</span>
          </a>
        </li>
        @endif

        @if(!$user || $user->hasPermission('reports'))
        <li class="{{ request()->routeIs('cms.report.*') ? 'active' : '' }}">
          <a class="sidenav-item-link {{ request()->routeIs('cms.report.*') ? 'active' : '' }}" href="{{ route('cms.report.index') }}">
            <i class="mdi mdi-chart-bar"></i>
            <span class="nav-text">Reports</span>
          </a>
        </li>
        @endif

        @if(!$user || $user->hasPermission('volunteers'))
        <li class="{{ request()->routeIs('cms.volunteer.*') ? 'active' : '' }}">
          <a class="sidenav-item-link {{ request()->routeIs('cms.volunteer.*') ? 'active' : '' }}" href="{{ route('cms.volunteer.index') }}">
            <i class="mdi mdi-account-heart"></i>
            <span class="nav-text">Volunteers</span>
          </a>
        </li>
        @endif

        @if(!$user || $user->hasPermission('settings'))
        <li class="{{ request()->routeIs('cms.setting.*') ? 'active' : '' }}">
          <a class="sidenav-item-link {{ request()->routeIs('cms.setting.*') ? 'active' : '' }}" href="{{ route('cms.setting.index') }}">
            <i class="mdi mdi-settings"></i>
            <span class="nav-text">Settings</span>
          </a>
        </li>
        @endif


      </ul>
    </div>

    <!-- Sidebar footer -->
    <div class="sidebar-footer">
      <div class="sidebar-footer-content">
        <ul class="d-flex">
          <li><a href="{{ route('cms.setting.index') }}" data-toggle="tooltip" title="Profile settings"><i class="mdi mdi-settings"></i></a></li>
          <li><a href="#" data-toggle="tooltip" title="No chat messages"><i class="mdi mdi-chat-processing"></i></a></li>
        </ul>
      </div>
    </div>
  </div>
</aside>