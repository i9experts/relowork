<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <title>ReloWork - @yield('title')</title>
  <meta name="theme-name" content="mono" />

  @stack('page-styles')

  <!-- GOOGLE FONTS -->
  <link href="https://fonts.googleapis.com/css?family=Karla:400,700|Roboto" rel="stylesheet">
  <link href="{{ url('assets/plugins/material/css/materialdesignicons.min.css') }}" rel="stylesheet" />
  <link href="{{ url('assets/plugins/simplebar/simplebar.css') }}" rel="stylesheet" />
  <!-- PLUGINS CSS STYLE -->
  <link href="{{ url('assets/plugins/nprogress/nprogress.css') }}" rel="stylesheet" />
  <link href="{{ url('assets/plugins/DataTables/DataTables-1.10.18/css/jquery.dataTables.min.css') }}"
    rel="stylesheet" />
  <link href="{{ url('assets/plugins/jvectormap/jquery-jvectormap-2.0.3.css') }}" rel="stylesheet" />
  <link href="{{ url('assets/plugins/daterangepicker/daterangepicker.css') }}" rel="stylesheet" />
  <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
  <link href="{{ url('assets/plugins/toaster/toastr.min.css') }}" rel="stylesheet" />

  <!-- MONO CSS -->
  <link id="main-css-href" rel="stylesheet" href="{{ url('assets/css/style.css') }}" />
  <!-- FAVICON -->
  @php
    $orgId = optional(request()->user())->organization_id ?? session('organization_id');
    $__settings = $orgId
      ? optional(\App\Models\Setting::where('organization_id', $orgId)->first())
      : optional(\App\Models\Setting::whereNull('organization_id')->first());
    $u = auth()->user();
    $fallbackTitle = ($u && (method_exists($u,'isSystemAdmin') && $u->isSystemAdmin())) ? 'Super Admin' : 'Your Name';
  @endphp
  @if($__settings && $__settings->favicon_path)
    <link href="{{ asset('storage/'.$__settings->favicon_path) }}" rel="shortcut icon" />
  @else
    <link href="{{ url('assets/images/logo/favicon.png') }}" rel="shortcut icon" />
  @endif

  <style>
    /* Ensure active state applies when <a> has .active too */
    .sidebar .sidebar-inner > li > a.active {
      position: relative;
      z-index: 10;
      color: #ffffff;
      background-color: rgba(158, 109, 224, 0.4);
    }

    /* Sidebar brand logo - professional, responsive, and centered */
    .app-brand a {
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 12px 10px;
    }
    .app-brand .brand-logo {
      display: block;
      max-height: 140px; /* default size in expanded state */
      max-width: 100%;
      width: auto;
      height: auto;
      object-fit: contain; /* keep aspect ratio, no cropping */
    }
    /* Collapsed/minified state: smaller but crisp */
    .sidebar-minified .app-brand .brand-logo {
      max-height: 56px;
    }

    /* Search input icon sizing/alignment */
    #input-group-search .input-group-text {
      height: 100%;            /* Icon span height = input height */
      padding: 0 12px;         /* Left & right padding */
      display: flex;
      align-items: center;      /* Vertical center */
      justify-content: center;  /* Horizontal center */
      /* background-color: #fff; */
      border-right: 0;
    }

    #input-group-search .form-control {
      border-left: 0;
    }

    /* Adjust icon size to match input height */
    #input-group-search .mdi.mdi-magnify {
      font-size: 1.7rem;       /* Adjust as needed; matches input height */
    }

  </style>

  <!--
    HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries
  -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
  <script src="{{ url('assets/plugins/nprogress/nprogress.js') }}"></script>
</head>

<body class="navbar-fixed sidebar-fixed" id="body">
  <script>
    NProgress.configure({ showSpinner: false });
    NProgress.start();
  </script>

  <div id="toaster"></div>

  <div id="global-loader" class="position-fixed top-0 start-0 w-100 h-100 bg-white" style="z-index:2000; display:block;">
    <div class="d-flex align-items-center justify-content-center h-100">
      <div class="sk-wonder-cube">
        <div class="cube1"></div>
        <div class="cube2"></div>
      </div>
    </div>
  </div>

  <div class="wrapper">

    @include('layouts/left-sidebar')

    <!-- Main content placeholder -->
    <div class="page-wrapper">

      <!-- Header -->
      <header class="main-header" id="header">
        <nav class="navbar navbar-expand-lg navbar-light" id="navbar">
          <!-- Sidebar toggle button -->
          <button id="sidebar-toggler" class="sidebar-toggle">
            <span class="sr-only">Toggle navigation</span>
          </button>

          <span class="page-title">{{ optional($__settings)->organization_name ?: $fallbackTitle }}</span>

          <div class="navbar-right">

            <!-- search form -->
            <div class="search-form">
              <form action="#" method="get" onsubmit="return false;">
                <div class="input-group input-group-sm" id="input-group-search">
                  <div class="input-group-prepend">
                    <span class="input-group-text">
                      <i class="mdi mdi-magnify"></i>
                    </span>
                  </div>
                  <input type="text" autocomplete="off" name="query" id="search-input" class="form-control"
                    placeholder="Search..." />
                </div>
              </form>
              <ul class="dropdown-menu dropdown-menu-search" style="min-width: 260px;">
                <!-- dynamic search suggestions here -->
              </ul>
            </div>

            <!-- dropdown notify -->
            <ul class="nav navbar-nav">
              @php
                $u = auth()->user();
                $showNotify = (!$u) ? false : (
                  $u->hasPermission('dashboard') ||
                  $u->hasPermission('families') ||
                  $u->hasPermission('families_view') ||
                  $u->hasPermission('families_create') ||
                  $u->hasPermission('families_edit') ||
                  $u->hasPermission('families_delete') ||
                  $u->hasPermission('aid_programs') ||
                  $u->hasPermission('aid_programs_view') ||
                  $u->hasPermission('aid_programs_create') ||
                  $u->hasPermission('aid_programs_edit') ||
                  $u->hasPermission('aid_programs_delete') ||
                  $u->hasPermission('financial') ||
                  $u->hasPermission('financial_create') ||
                  $u->hasPermission('financial_edit') ||
                  $u->hasPermission('financial_export') ||
                  $u->hasPermission('surveys') ||
                  $u->hasPermission('surveys_view') ||
                  $u->hasPermission('surveys_edit') ||
                  $u->hasPermission('reports') ||
                  $u->hasPermission('reports_view') ||
                  $u->hasPermission('reports_download') ||
                  $u->hasPermission('reports_export_csv') ||
                  $u->hasPermission('reports_download_all') ||
                  $u->hasPermission('volunteers') ||
                  $u->hasPermission('volunteers_view') ||
                  $u->hasPermission('volunteers_create') ||
                  $u->hasPermission('volunteers_edit') ||
                  $u->hasPermission('volunteers_delete') ||
                  (method_exists($u,'isSystemAdmin') && $u->isSystemAdmin())
                );
              @endphp
              @if($showNotify)
              <li class="custom-dropdown">
                <button class="notify-toggler custom-dropdown-toggler">
                  <i class="mdi mdi-bell-outline icon"></i>
                  <span class="badge badge-xs rounded-circle">0</span>
                </button>
                <div class="dropdown-notify">

                  <header>
                    <div class="nav nav-underline" id="nav-tab" role="tablist">
                      <a class="nav-item nav-link active" id="all-tabs" data-toggle="tab" href="#all" role="tab"
                        aria-controls="nav-home" aria-selected="true">All (0)</a>
                      <a class="nav-item nav-link" id="message-tab" data-toggle="tab" href="#message" role="tab"
                        aria-controls="nav-profile" aria-selected="false">Msgs (0)</a>
                      <a class="nav-item nav-link" id="other-tab" data-toggle="tab" href="#other" role="tab"
                        aria-controls="nav-contact" aria-selected="false">Others (0)</a>
                    </div>
                  </header>

                  <div class="" data-simplebar style="height: 325px;">
                    <div class="tab-content" id="myTabContent">
                      <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="all-tabs"></div>
                      <div class="tab-pane fade" id="message" role="tabpanel" aria-labelledby="message-tab"></div>
                      <div class="tab-pane fade" id="other" role="tabpanel" aria-labelledby="other-tab"></div>
                    </div>
                  </div>

                  <footer class="border-top dropdown-notify-footer">
                    <div class="d-flex justify-content-between align-items-center py-2 px-4">
                      <span>Last updated 3 min ago</span>
                      <div>
                        <a id="refress-button" href="javascript:" class="btn mdi mdi-cached btn-refress"></a>
                      </div>
                    </div>
                  </footer>
                </div>
              </li>
              @endif

              <!-- User Account -->
              <li class="dropdown user-menu">
                <button class="dropdown-toggle nav-link" data-toggle="dropdown">
                  @php
                    $u = Auth::user();
                    $__profileImage = url('assets/images/user/user-defult-profile.png');
                    if ($u) {
                      $avatar = $u->avatar_path;
                      if (!empty($avatar)) {
                        $__profileImage = asset('storage/' . $avatar);
                      } else {
                        $vol = \App\Models\Volunteer::where('user_id', $u->id)->first();
                        $pic = optional($vol)->picture_path;
                        if (!empty($pic)) {
                          $__profileImage = asset($pic);
                        }
                      }
                    }
                  @endphp
                  <img src="{{ $__profileImage ?? url('assets/images/user/user-defult-profile.png') }}"
                      class="user-image rounded-circle" alt="User Image">
                  <span class="d-none d-lg-inline-block">{{ Auth::user()->full_name ?? Auth::user()->name }}</span>
                </button>

                  <ul class="dropdown-menu dropdown-menu-right">

                      <li class="px-3 py-2 border-bottom text-center"> 
                        <strong>{{ Auth::user()->full_name ?? Auth::user()->name }}</strong><br>
                        <small>{{ Auth::user()->email }}</small>
                      </li>


                      <li>
                          <a class="dropdown-link-item" href="{{ route('cms.profile.index') }}">
                              <i class="mdi mdi-account-outline"></i>
                              <span class="nav-text">Profile</span>
                          </a>
                      </li>

                      @if($u && $u->hasPermission('settings'))
                      <li>
                          <a class="dropdown-link-item" href="{{ route('cms.setting.index') }}">
                              <i class="mdi mdi-settings"></i>
                              <span class="nav-text">Setting</span>
                          </a>
                      </li>
                      @endif

                      <li class="dropdown-footer">
                          <form method="POST" action="{{ route('logout') }}" class="m-0">
                              @csrf
                              <button type="submit" class="dropdown-item d-flex align-items-center">
                                  <i class="mdi mdi-logout mr-2"></i>
                                  <span>Log Out</span>
                              </button>
                          </form>
                      </li>

                  </ul>
              </li>


            </ul>
          </div>
        </nav>


      </header>


      <div class="content-wrapper">
        <div class="content">
            @yield('content')
        </div>
      </div>

      <!-- Footer -->
      <footer class="footer mt-auto">
        <div class="copyright bg-white">
          <p>
            &copy; <span id="copy-year"></span> Powered by <a class="text-primary"
              href="http://www.i9experts.com/" target="_blank">I9 Experts (Pvt) Ltd</a>.
          </p>
        </div>
        <script>
          var d = new Date();
          var year = d.getFullYear();
          document.getElementById("copy-year").innerHTML = year;
        </script>
      </footer>

    </div>
  </div>

  <script src="{{ url('assets/plugins/jquery/jquery.min.js') }}"></script>
  <script src="{{ url('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ url('assets/plugins/simplebar/simplebar.min.js') }}"></script>
  <script src="https://unpkg.com/hotkeys-js/dist/hotkeys.min.js"></script>
  <script src="{{ url('assets/plugins/apexcharts/apexcharts.js') }}"></script>
  <script src="{{ url('assets/plugins/DataTables/DataTables-1.10.18/js/jquery.dataTables.min.js') }}"></script>
  <script src="{{ url('assets/plugins/jvectormap/jquery-jvectormap-2.0.3.min.js') }}"></script>
  <script src="{{ url('assets/plugins/jvectormap/jquery-jvectormap-world-mill.js') }}"></script>
  <script src="{{ url('assets/plugins/jvectormap/jquery-jvectormap-us-aea.js') }}"></script>
  <script src="{{ url('assets/plugins/daterangepicker/moment.min.js') }}"></script>
  <script src="{{ url('assets/plugins/daterangepicker/daterangepicker.js') }}"></script>
  <script>
    jQuery(document).ready(function () {
      jQuery('input[name="dateRange"]').daterangepicker({
        autoUpdateInput: false,
        singleDatePicker: true,
        locale: {
          cancelLabel: 'Clear'
        }
      });
      jQuery('input[name="dateRange"]').on('apply.daterangepicker', function (ev, picker) {
        jQuery(this).val(picker.startDate.format('MM/DD/YYYY'));
      });
      jQuery('input[name="dateRange"]').on('cancel.daterangepicker', function (ev, picker) {
        jQuery(this).val('');
      });
    });
  </script>
  <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
  <script src="{{ url('assets/plugins/toaster/toastr.min.js') }}"></script>
  <script src="{{ url('assets/js/mono.js') }}"></script>
  <script src="{{ url('assets/js/chart.js') }}"></script>
  <script src="{{ url('assets/js/map.js') }}"></script>
  <script src="{{ url('assets/js/custom.js') }}"></script>

  <script>
    (function($){
      $(function(){
        // Show loader on any global AJAX activity
        $(document).ajaxStart(function(){ $('#global-loader').show(); });
        $(document).ajaxStop(function(){ $('#global-loader').hide(); });
        // Show loader on any form submit
        $('form').on('submit', function(){ $('#global-loader').show(); });
        // Ensure hidden once page fully ready
        $(window).on('load', function(){
          $('#global-loader').hide();
          if (window.NProgress && typeof NProgress.done === 'function') { NProgress.done(); }
        });

        // --- Global Client-side Typeahead Search ---
        var $input = $('#search-input');
        var $menu = $('.dropdown-menu-search');
        var index = [];
        var defaults = [];
        function buildIndex(){
          index = [];
          // Collect from left sidebar
          $('#left-sidebar a[href]').each(function(){
            var $a = $(this);
            var label = ($a.text() || '').trim();
            var url = $a.attr('href');
            if (label && url && url !== '#') {
              index.push({ label: label, url: url, key: label.toLowerCase() });
            }
          });
          // Add shortcuts based on permissions (ensure unique by url)
          var extras = [];
          @php $u = auth()->user(); @endphp
          @if($u && (method_exists($u,'isSystemAdmin') && $u->isSystemAdmin()))
            extras.push({ label: 'Admin Dashboard', url: '{{ route('cms.admin.dashboard') }}' });
            extras.push({ label: 'Organizations', url: '{{ route('cms.admin.organizations') }}' });
            extras.push({ label: 'Users', url: '{{ route('cms.admin.users') }}' });
          @endif
          @if(!$u || $u->hasPermission('dashboard'))
            extras.push({ label: 'Dashboard', url: '{{ route('cms.dashboard.index') }}' });
          @endif
          @if(!$u || $u->hasPermission('families'))
            extras.push({ label: 'Families', url: '{{ route('cms.family.index') }}' });
            extras.push({ label: 'New Family', url: '{{ route('cms.family.create') }}' });
          @endif
          @if(!$u || $u->hasPermission('aid_programs'))
            extras.push({ label: 'Aid Programs', url: '{{ route('cms.aid-program.index') }}' });
            extras.push({ label: 'New Aid Program', url: '{{ route('cms.aid-program.create') }}' });
          @endif
          @if(!$u || $u->hasPermission('financial'))
            extras.push({ label: 'Financials', url: '{{ route('cms.financial-management.index') }}' });
            extras.push({ label: 'New Transaction', url: '{{ route('cms.financial-management.create') }}' });
            extras.push({ label: 'Donations', url: '{{ route('cms.financial-management.index') }}' });
          @endif
          @if(!$u || $u->hasPermission('surveys'))
            extras.push({ label: 'Surveys', url: '{{ route('cms.survey.index') }}' });
            extras.push({ label: 'New Survey', url: '{{ route('cms.survey.create') }}' });
          @endif
          @if(!$u || $u->hasPermission('reports'))
            extras.push({ label: 'Reports', url: '{{ route('cms.report.index') }}' });
          @endif
          @if(!$u || $u->hasPermission('volunteers'))
            extras.push({ label: 'Volunteers', url: '{{ route('cms.volunteer.index') }}' });
            extras.push({ label: 'New Volunteer', url: '{{ route('cms.volunteer.create') }}' });
          @endif
          @if(!$u || $u->hasPermission('settings'))
            extras.push({ label: 'Settings', url: '{{ route('cms.setting.index') }}' });
          @endif
          extras.push({ label: 'Profile', url: '{{ route('cms.profile.index') }}' });
          var seen = {};
          index.forEach(function(i){ seen[i.url] = true; });
          defaults = [];
          extras.forEach(function(e){
            if (!seen[e.url]) {
              var item = { label: e.label, url: e.url, key: e.label.toLowerCase() };
              index.push(item);
              defaults.push(item);
            } else {
              // If already in index, still add to defaults for empty-query suggestions
              defaults.push({ label: e.label, url: e.url, key: e.label.toLowerCase() });
            }
          });
        }
        buildIndex();

        // Rebuild when sidebar changes (simple hook; can be enhanced)
        $(document).on('cms:sidebar-updated', buildIndex);

        function filterIndex(q){
          q = q.toLowerCase();
          if (!q) return [];
          // simple contains + starts-with boost
          var res = index
            .map(function(it){
              var pos = it.key.indexOf(q);
              if (pos === -1) return null;
              var score = (pos === 0 ? 0 : 1) + (it.key.length - q.length) * 0.001; // smaller is better
              return { item: it, score: score };
            })
            .filter(Boolean)
            .sort(function(a,b){ return a.score - b.score; })
            .slice(0, 8)
            .map(function(s){ return s.item; });
          return res;
        }

        function render(items, activeIndex){
          $menu.empty();
          if (!items.length) {
            $menu.append('<li class="dropdown-item text-muted">No results found</li>');
          } else {
            items.forEach(function(it, i){
              var a = $('<a/>', { 'class': 'dropdown-item d-flex align-items-center', 'href': it.url })
                .append($('<i class="mdi mdi-magnify mr-2"></i>'))
                .append($('<span/>').text(it.label));
              var li = $('<li/>').append(a);
              if (i === activeIndex) { a.addClass('active'); }
              $menu.append(li);
            });
          }
          $menu.addClass('show').show();
        }

        var debounce;
        var current = [];
        var active = -1;
        function update(){
          var q = ($input.val() || '').trim();
          if (q.length === 0) {
            current = (defaults || []).slice(0, 10);
            active = current.length ? 0 : -1;
            render(current, active);
            return;
          }
          current = filterIndex(q);
          active = current.length ? 0 : -1;
          render(current, active);
        }

        $input.on('focus keyup', function(e){
          // Keyboard navigation
          if (e.type === 'keyup') {
            if (e.key === 'ArrowDown' && current.length) { active = (active + 1) % current.length; render(current, active); return; }
            if (e.key === 'ArrowUp' && current.length) { active = (active - 1 + current.length) % current.length; render(current, active); return; }
            if (e.key === 'Enter') { if (current.length) { window.location.href = current[Math.max(active,0)].url; } return; }
            if (e.key === 'Escape') { $menu.removeClass('show').hide(); return; }
          }
          clearTimeout(debounce);
          debounce = setTimeout(function(){
            update();
          }, 150);
        });

        $(document).on('click', function(e){
          if (!$(e.target).closest('#input-group-search, .dropdown-menu-search').length) {
            $menu.removeClass('show').hide();
          }
        });

        // Ensure mouse click navigates
        $menu.on('click', 'a.dropdown-item', function(e){
          var href = $(this).attr('href');
          if (href && href !== '#') {
            e.preventDefault();
            window.location.href = href;
          }
        });

        // Quick focus with /
        $(document).on('keydown', function(e){ if (e.key === '/' && !$(e.target).is('input,textarea')) { e.preventDefault(); $input.trigger('focus'); } });
        // Open on mouse click handled by anchor; additionally, Enter selects active
      });
    })(jQuery);
  </script>

  <script>
    (function($){
      $(function(){
        var loading = false;
        var userId = @json(optional(auth()->user())->id ?: 'guest');
        var lsKey = 'cms:notif:lastSeen:'+userId;
        function getSince(){
          var v = localStorage.getItem(lsKey);
          return v ? parseInt(v,10) || 0 : 0;
        }
        function setSince(ts){
          localStorage.setItem(lsKey, String(ts || 0));
        }
        function renderList($container, list){
          $container.empty();
          if (!list || !list.length) {
            $container.append('<div class="p-3 text-muted">No notifications</div>');
            return;
          }
          list.forEach(function(it){
            var badge = it.badge || 'info';
            var badgeLabel = it.badgeLabel || '';
            var a = $('<a/>', { href: it.href || '#'});
            var media = $('<div/>', { 'class': 'media media-sm p-4 mb-0' });
            var body = $('<div/>', { 'class': 'media-body' });
            var title = $('<span/>', { 'class': 'title mb-0' }).text(it.title || '');
            var desc = $('<span/>', { 'class': 'discribe' }).text(it.desc || '');
            var time = $('<span/>', { 'class': 'time' }).append($('<time/>').text(it.time || ''));
            var badgeEl = $('<span/>', { 'class': 'badge badge-pill badge-'+badge }).text(badgeLabel);
            body.append(a.append(title).append(desc).append(time));
            media.append(body).append(badgeEl);
            $container.append(media);
          });
        }
        function loadNotifications(){
          if (loading) return;
          loading = true;
          var placeholder = '<div class="p-3 text-center text-muted">Loading...</div>';
          $('#all').html(placeholder);
          $('#message').html(placeholder);
          $('#other').html(placeholder);
          $('#refress-button').addClass('mdi-spin');
          $.ajax({ url: "{{ route('notifications.feed') }}", dataType: 'json', data: { since: getSince() }, global: false })
            .done(function(res){
              var $all = $('#all');
              var $msg = $('#message');
              var $other = $('#other');
              renderList($all, res.all || []);
              renderList($msg, res.message || []);
              renderList($other, res.other || []);
              $('#all-tabs').text('All (' + (res.counts ? res.counts.all : (res.all||[]).length) + ')');
              $('#message-tab').text('Msgs (' + (res.counts ? res.counts.message : (res.message||[]).length) + ')');
              $('#other-tab').text('Others (' + (res.counts ? res.counts.other : (res.other||[]).length) + ')');
              $('.dropdown-notify-footer span').text('Last updated ' + (res.lastUpdated || ''));
              var badge = (res.counts ? res.counts.unread : 0);
              if (!badge || badge < 0) { badge = (res.counts ? res.counts.all : (res.all||[]).length) || 0; }
              $('.notify-toggler .badge').text(badge);
              if (typeof res.nowTs === 'number') { /* no-op */ }
            })
            .fail(function(){
              var err = '<div class="p-3 text-center text-danger">Failed to load notifications</div>';
              $('#all').html(err);
              $('#message').empty();
              $('#other').empty();
            })
            .always(function(){ loading = false; $('#refress-button').removeClass('mdi-spin'); });
        }
        $('.notify-toggler').on('click', function(){ loadNotifications(); });
        $('#refress-button').on('click', function(){ loadNotifications(); });
        $('#mark-seen-button').on('click', function(){ setSince(Math.floor(Date.now()/1000)); loadNotifications(); });
        setInterval(function(){ loadNotifications(); }, 30000);
        $(document).on('keydown', function(e){ if (e.key === 'Escape' && $('.dropdown-notify').is(':visible')) { $('.dropdown-notify').fadeOut(5); } });

        function primeBadge(){
          $.ajax({ url: "{{ route('notifications.feed') }}", dataType: 'json', data: { since: getSince() }, global: false })
            .done(function(res){
              var badge = (res.counts ? res.counts.unread : 0);
              if (!badge || badge < 0) { badge = (res.counts ? res.counts.all : (res.all||[]).length) || 0; }
              $('.notify-toggler .badge').text(badge);
            });
        }
        primeBadge();
      });
    })(jQuery);
  </script>

  <script>
    (function($){
      $(function(){
        try {
          // Only show welcome toast on Dashboard
          var onDashboard = @json(request()->routeIs('cms.dashboard.index'));
          if (!onDashboard) return;

          if (window.toastr) {
            var orgName = @json(optional($__settings)->organization_name ?: 'Organization');
            var userName = @json(optional(auth()->user())->name ?: 'User');
            toastr.options = {
              closeButton: true,
              progressBar: true,
              positionClass: 'toast-top-right',
              timeOut: 3000
            };
            toastr.success('Welcome to ' + orgName + ', ' + userName + '!');
          }
        } catch (e) { /* no-op */ }
      });
    })(jQuery);
  </script>

  <script>
    (function($){
      $(function(){
        try {
          if (!window.toastr) return;
          toastr.options = {
            closeButton: true,
            progressBar: true,
            positionClass: 'toast-top-right',
            timeOut: 3000
          };
          var flash = {
            success: @json(session('success')),
            warning: @json(session('warning')),
            error: @json(session('error')),
            info: @json(session('info'))
          };
          if (flash.success) { toastr.success(flash.success); }
          if (flash.warning) { toastr.warning(flash.warning); }
          if (flash.error) { toastr.error(flash.error); }
          if (flash.info) { toastr.info(flash.info); }
        } catch (e) { /* no-op */ }
      });
    })(jQuery);
  </script>

  @stack('page-scripts')

</body>

</html>
