<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <title>Charity Management - @yield('title')</title>
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
  @php($__settings = optional(\App\Models\Setting::first()))
  @if($__settings && $__settings->favicon_path)
    <link href="{{ asset('storage/'.$__settings->favicon_path) }}" rel="shortcut icon" />
  @else
    <link href="{{ url('assets/images/favicon.png') }}" rel="shortcut icon" />
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

          <span class="page-title">{{ optional(\App\Models\Setting::first())->organization_name ?: 'Your Name' }}</span>

          <div class="navbar-right">

            <!-- search form -->
            <div class="search-form">
              <form action="#" method="get" onsubmit="return false;">
                <div class="input-group input-group-sm" id="input-group-search">
                  <input type="text" autocomplete="off" name="query" id="search-input" class="form-control"
                    placeholder="Search..." />
                  <div class="input-group-append">
                    <button class="btn" type="button" id="search-kbd">/</button>
                  </div>
                </div>
              </form>
              <ul class="dropdown-menu dropdown-menu-search" style="min-width: 260px;">
                <!-- dynamic search suggestions here -->
              </ul>

            </div>

            <ul class="nav navbar-nav">
              <li class="custom-dropdown">
                <button class="notify-toggler custom-dropdown-toggler">
                  <i class="mdi mdi-bell-outline icon"></i>
                  <span class="badge badge-xs rounded-circle">21</span>
                </button>
                <div class="dropdown-notify">

                  <header>
                    <div class="nav nav-underline" id="nav-tab" role="tablist">
                      <a class="nav-item nav-link active" id="all-tabs" data-toggle="tab" href="#all" role="tab"
                        aria-controls="nav-home" aria-selected="true">All (5)</a>
                      <a class="nav-item nav-link" id="message-tab" data-toggle="tab" href="#message" role="tab"
                        aria-controls="nav-profile" aria-selected="false">Msgs (4)</a>
                      <a class="nav-item nav-link" id="other-tab" data-toggle="tab" href="#other" role="tab"
                        aria-controls="nav-contact" aria-selected="false">Others (3)</a>
                    </div>
                  </header>

                  <div class="" data-simplebar style="height: 325px;">
                    <div class="tab-content" id="myTabContent">

                      <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="all-tabs">

                        <div class="media media-sm bg-warning-10 p-4 mb-0">
                          <div class="media-sm-wrapper">
                            <a href="user-profile.html">
                              <img src="images/user/user-sm-02.jpg" alt="User Image">
                            </a>
                          </div>
                          <div class="media-body">
                            <a href="user-profile.html">
                              <span class="title mb-0">John Doe</span>
                              <span class="discribe">Extremity sweetness difficult behaviour he of. On disposal of as
                                landlord horrible. Afraid at highly months do things on at.</span>
                              <span class="time">
                                <time>Just now</time>...
                              </span>
                            </a>
                          </div>
                        </div>

                        <div class="media media-sm p-4 bg-light mb-0">
                          <div class="media-sm-wrapper bg-primary">
                            <a href="user-profile.html">
                              <i class="mdi mdi-calendar-check-outline"></i>
                            </a>
                          </div>
                          <div class="media-body">
                            <a href="user-profile.html">
                              <span class="title mb-0">New event added</span>
                              <span class="discribe">1/3/2014 (1pm - 2pm)</span>
                              <span class="time">
                                <time>10 min ago...</time>...
                              </span>
                            </a>
                          </div>
                        </div>

                        <div class="media media-sm p-4 mb-0">
                          <div class="media-sm-wrapper">
                            <a href="user-profile.html">
                              <img src="images/user/user-sm-03.jpg" alt="User Image">
                            </a>
                          </div>
                          <div class="media-body">
                            <a href="user-profile.html">
                              <span class="title mb-0">Sagge Hudson</span>
                              <span class="discribe">On disposal of as landlord Afraid at highly months do things on
                                at.</span>
                              <span class="time">
                                <time>1 hrs ago</time>...
                              </span>
                            </a>
                          </div>
                        </div>

                        <div class="media media-sm p-4 mb-0">
                          <div class="media-sm-wrapper bg-info-dark">
                            <a href="user-profile.html">
                              <i class="mdi mdi-account-multiple-check"></i>
                            </a>
                          </div>
                          <div class="media-body">
                            <a href="user-profile.html">
                              <span class="title mb-0">Add request</span>
                              <span class="discribe">Add Dany Jones as your contact.</span>
                              <div class="buttons">
                                <a href="#" class="btn btn-sm btn-success shadow-none text-white">accept</a>
                                <a href="#" class="btn btn-sm shadow-none">delete</a>
                              </div>
                              <span class="time">
                                <time>6 hrs ago</time>...
                              </span>
                            </a>
                          </div>
                        </div>

                        <div class="media media-sm p-4 mb-0">
                          <div class="media-sm-wrapper bg-info">
                            <a href="user-profile.html">
                              <i class="mdi mdi-playlist-check"></i>
                            </a>
                          </div>
                          <div class="media-body">
                            <a href="user-profile.html">
                              <span class="title mb-0">Task complete</span>
                              <span class="discribe">Afraid at highly months do things on at.</span>
                              <span class="time">
                                <time>1 hrs ago</time>...
                              </span>
                            </a>
                          </div>
                        </div>

                      </div>

                      <div class="tab-pane fade" id="message" role="tabpanel" aria-labelledby="message-tab">

                        <div class="media media-sm p-4 mb-0">
                          <div class="media-sm-wrapper">
                            <a href="user-profile.html">
                              <img src="{{ url('assets/images/user/user-sm-01.jpg') }}" alt="User Image">
                            </a>
                          </div>
                          <div class="media-body">
                            <a href="user-profile.html">
                              <span class="title mb-0">Selena Wagner</span>
                              <span class="discribe">Lorem ipsum dolor sit amet, consectetur adipisicing elit.</span>
                              <span class="time">
                                <time>15 min ago</time>...
                              </span>
                            </a>
                          </div>
                        </div>

                        <div class="media media-sm p-4 mb-0">
                          <div class="media-sm-wrapper">
                            <a href="user-profile.html">
                              <img src="{{ url('assets/images/user/user-sm-03.jpg') }}" alt="User Image">
                            </a>
                          </div>
                          <div class="media-body">
                            <a href="user-profile.html">
                              <span class="title mb-0">Sagge Hudson</span>
                              <span class="discribe">On disposal of as landlord Afraid at highly months do things on
                                at.</span>
                              <span class="time">
                                <time>1 hrs ago</time>...
                              </span>
                            </a>
                          </div>
                        </div>

                        <div class="media media-sm bg-warning-10 p-4 mb-0">
                          <div class="media-sm-wrapper">
                            <a href="user-profile.html">
                              <img src="{{ url('assets/images/user/user-sm-02.jpg') }}" alt="User Image">
                            </a>
                          </div>
                          <div class="media-body">
                            <a href="user-profile.html">
                              <span class="title mb-0">John Doe</span>
                              <span class="discribe">Extremity sweetness difficult behaviour he of. On disposal of as
                                landlord horrible. Afraid
                                at highly months do things on at.</span>
                              <span class="time">
                                <time>Just now</time>...
                              </span>
                            </a>
                          </div>
                        </div>

                        <div class="media media-sm p-4 mb-0">
                          <div class="media-sm-wrapper">
                            <a href="user-profile.html">
                              <img src="{{ url('assets/images/user/user-sm-04.jpg') }}" alt="User Image">
                            </a>
                          </div>
                          <div class="media-body">
                            <a href="user-profile.html">
                              <span class="title mb-0">Albrecht Straub</span>
                              <span class="discribe"> Beatae quia natus assumenda laboriosam, nisi perferendis aliquid
                                consectetur expedita non tenetur.</span>
                              <span class="time">
                                <time>Just now</time>...
                              </span>
                            </a>
                          </div>
                        </div>

                      </div>
                      <div class="tab-pane fade" id="other" role="tabpanel" aria-labelledby="contact-tab">

                        <div class="media media-sm p-4 bg-light mb-0">
                          <div class="media-sm-wrapper bg-primary">
                            <a href="user-profile.html">
                              <i class="mdi mdi-calendar-check-outline"></i>
                            </a>
                          </div>
                          <div class="media-body">
                            <a href="user-profile.html">
                              <span class="title mb-0">New event added</span>
                              <span class="discribe">1/3/2014 (1pm - 2pm)</span>
                              <span class="time">
                                <time>10 min ago...</time>...
                              </span>
                            </a>
                          </div>
                        </div>

                        <div class="media media-sm p-4 mb-0">
                          <div class="media-sm-wrapper bg-info-dark">
                            <a href="user-profile.html">
                              <i class="mdi mdi-account-multiple-check"></i>
                            </a>
                          </div>
                          <div class="media-body">
                            <a href="user-profile.html">
                              <span class="title mb-0">Add request</span>
                              <span class="discribe">Add Dany Jones as your contact.</span>
                              <div class="buttons">
                                <a href="#" class="btn btn-sm btn-success shadow-none text-white">accept</a>
                                <a href="#" class="btn btn-sm shadow-none">delete</a>
                              </div>
                              <span class="time">
                                <time>6 hrs ago</time>...
                              </span>
                            </a>
                          </div>
                        </div>

                        <div class="media media-sm p-4 mb-0">
                          <div class="media-sm-wrapper bg-info">
                            <a href="user-profile.html">
                              <i class="mdi mdi-playlist-check"></i>
                            </a>
                          </div>
                          <div class="media-body">
                            <a href="user-profile.html">
                              <span class="title mb-0">Task complete</span>
                              <span class="discribe">Afraid at highly months do things on at.</span>
                              <span class="time">
                                <time>1 hrs ago</time>...
                              </span>
                            </a>
                          </div>
                        </div>

                      </div>
                    </div>
                  </div>

                  <footer class="border-top dropdown-notify-footer">
                    <div class="d-flex justify-content-between align-items-center py-2 px-4">
                      <span>Last updated 3 min ago</span>
                      <a id="refress-button" href="javascript:" class="btn mdi mdi-cached btn-refress"></a>
                    </div>
                  </footer>
                </div>
              </li>
              <!-- User Account -->
              <li class="dropdown user-menu">
                <button class="dropdown-toggle nav-link" data-toggle="dropdown">
                  <img src="{{ url('assets/images/user/user-xs-01.jpg')}}" class="user-image rounded-circle"
                    alt="User Image" />
                  <span class="d-none d-lg-inline-block">Admin</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-right">
                  <li>
                    <a class="dropdown-link-item" href="#">
                      <i class="mdi mdi-account-outline"></i>
                      <span class="nav-text">Profile</span>
                    </a>
                  </li>
                  <li>
                    <a class="dropdown-link-item" href="{{ route('cms.setting.index') }}">
                      <i class="mdi mdi-settings"></i>
                      <span class="nav-text">Setting</span>
                    </a>
                  </li>

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
          // Add common shortcuts (ensure unique by url)
          var extras = [
            { label: 'Dashboard', url: '{{ route('cms.dashboard.index') }}' },
            { label: 'Families', url: '{{ route('cms.family.index') }}' },
            { label: 'Aid Programs', url: '{{ route('cms.aid-program.index') }}' },
            { label: 'Financials', url: '{{ route('cms.financial-management.index') }}' },
            { label: 'Surveys', url: '{{ route('cms.survey.index') }}' },
            { label: 'Reports', url: '{{ route('cms.report.index') }}' },
            { label: 'Volunteers', url: '{{ route('cms.volunteer.index') }}' },
            { label: 'Settings', url: '{{ route('cms.setting.index') }}' },
          ];
          var seen = {};
          index.forEach(function(i){ seen[i.url] = true; });
          extras.forEach(function(e){ if (!seen[e.url]) { index.push({ label: e.label, url: e.url, key: e.label.toLowerCase() }); } });
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
            if (($input.val() || '').trim().length === 0) { $menu.removeClass('show').hide(); return; }
            update();
          }, 150);
        });

        $(document).on('click', function(e){
          if (!$(e.target).closest('#input-group-search, .dropdown-menu-search').length) {
            $menu.removeClass('show').hide();
          }
        });

        // Quick focus with /
        $('#search-kbd').on('click', function(){ $input.trigger('focus'); });
        $(document).on('keydown', function(e){ if (e.key === '/' && !$(e.target).is('input,textarea')) { e.preventDefault(); $input.trigger('focus'); } });
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
            var orgName = @json(optional(\App\Models\Setting::first())->organization_name ?: 'Organization');
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