@include('layouts.admin.header')
<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            @php
                $setting=App\Setting::first();
                $website_lang=App\ManageText::all();
            @endphp
            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('home') }}">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="{{ $setting->sidebar_header_icon }}"></i>
                </div>
                <div class="sidebar-brand-text mx-3">{{ $setting->sidebar_header_name }}</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item {{ Route::is('agent.dashboard')?'active':'' }}">
                <a class="nav-link" href="{{ route('agent.dashboard') }}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>{{ $website_lang->where('lang_key','dashboard')->first()->custom_lang }}</span>
                </a>
        
            </li>
            <li class="text-center text-white"><b>{{ $mytime = Carbon\Carbon::now()->format('m-d / g:i a') }}</b></li>


            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                {{ $website_lang->where('lang_key','interface')->first()->custom_lang }}
            </div>
            

            <!-- Nav Item - Charts -->
            <li class="nav-item {{ Route::is('agent.today.appointment') || Route::is('agent.meet') || Route::is('agent.edit-prescription') ?'active':'' }}">
                <a class="nav-link" href="{{ route('agent.today.appointment') }}">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>{{ $website_lang->where('lang_key','today_appointment')->first()->custom_lang }}</span></a>
            </li>
             <!-- Nav Item - Manage Appts Collapse Menu -->
             <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages"
                    aria-expanded="true" aria-controls="collapsePages">
                    <i class="fas fa-fw fa-folder"></i>
                    <span>{{ $website_lang->where('lang_key','manage_appointment')->first()->custom_lang }}</span>
                </a>
                <div id="collapsePages" class="collapse {{ Route::is('agent.new.appointment') || Route::is('agent.all.appointment') || Route::is('agent.already.treatment') || Route::is('agent.already-meet') ?'show':'' }}" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item {{ Route::is('agent.new.appointment')?'active':'' }}" href="{{ route('agent.new.appointment') }}">{{ $website_lang->where('lang_key','new_appointment')->first()->custom_lang }}</a>
                        <a class="collapse-item {{ Route::is('agent.all.appointment') || Route::is('agent.already-meet') ?'active':'' }}" href="{{ route('agent.all.appointment') }}">{{ $website_lang->where('lang_key','appointment_history')->first()->custom_lang }}</a>


                    </div>
                </div>
            </li>


            <!-- Nav Item - Live Consultation Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#live-consultant"
                    aria-expanded="true" aria-controls="live-consultant">
                    <i class="fas fa-fw fa-folder"></i>
                    <span>{{ $website_lang->where('lang_key','live_consult')->first()->custom_lang }}</span>
                </a>
                <div id="live-consultant" class="collapse {{
                    Route::is('agent.zoom-credential') || Route::is('agent.zoom-meetings') || Route::is('agent.create-zoom-meeting') || Route::is('agent.edit-zoom-meeting') || Route::is('agent.upcomming-meeting') || Route::is('agent.meeting-history') ? 'show':'' }}" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item {{ Route::is('agent.zoom-meetings') || Route::is('agent.create-zoom-meeting') || Route::is('agent.edit-zoom-meeting')?'active':'' }}" href="{{ route('agent.zoom-meetings') }}">{{ $website_lang->where('lang_key','meeting')->first()->custom_lang }}</a>

                        <a class="collapse-item {{ Route::is('agent.meeting-history') ?'active':'' }}" href="{{ route('agent.meeting-history') }}">{{ $website_lang->where('lang_key','meeting_history')->first()->custom_lang }}</a>
                        <a class="collapse-item {{ Route::is('agent.upcomming-meeting') ?'active':'' }}" href="{{ route('agent.upcomming-meeting') }}">{{ $website_lang->where('lang_key','upcoming_meeting')->first()->custom_lang }}</a>


                        <a class="collapse-item {{ Route::is('agent.zoom-meetings')?'active':'' }}" href="{{ route('agent.zoom-meetings') }}">{{ $website_lang->where('lang_key','zoom_meeting')->first()->custom_lang }}</a>


                    </div>
                </div>
            </li>

             <!-- Nav Item - Charts -->
            <li class="nav-item {{ Route::is('agent.leave.index')?'active':'' }}">
                <a class="nav-link" href="{{ route('agent.leave.index') }}">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>{{ $website_lang->where('lang_key','manage_leave')->first()->custom_lang }}</span></a>
            </li>

             <!-- Nav Item - Charts -->
            <li class="nav-item {{ Route::is('agent.payment.history')?'active':'' }}">
                <a class="nav-link" href="{{ route('agent.payment.history') }}">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>{{ $website_lang->where('lang_key','payment_history')->first()->custom_lang }}</span></a>
            </li>
             <!-- Nav Item - Charts -->
            <li class="nav-item {{ Route::is('agent.schedule')?'active':'' }}">
                <a class="nav-link" href="{{ route('agent.schedule') }}">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>{{ $website_lang->where('lang_key','my_schedule')->first()->custom_lang }}</span></a>
            </li>

             <!-- Nav Item - Charts -->
            <li class="nav-item">
                <a class="nav-link" href="{{ route('agent.message.index') }}">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>{{ $website_lang->where('lang_key','message')->first()->custom_lang }}</span></a>
            </li>




            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fas fa-bars"></i>
                    </button>
                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                        <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                                aria-labelledby="searchDropdown">
                                <form class="form-inline mr-auto w-100 navbar-search">
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-light border-0 small"
                                            placeholder="Search for..." aria-label="Search"
                                            aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button">
                                                <i class="fas fa-search fa-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </li>


                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                @php
                                    $doctorInfo=Auth::guard('agent')->user();
                                @endphp
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ ucfirst($doctorInfo->name) }}</span>
                                @if ($doctorInfo->image)
                                    <img class="img-profile rounded-circle"
                                    src="{{ url($doctorInfo->image) }}">
                                @else
                                    <img class="img-profile rounded-circle"
                                    src="{{ url('default-user.jpg') }}">
                                @endif

                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="{{ route('agent.profile') }}">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    {{ $website_lang->where('lang_key','profile')->first()->custom_lang }}
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('agent.logout') }}">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    {{ $website_lang->where('lang_key','logout')->first()->custom_lang }}
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                   @yield('agent-content')

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->



        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>


@include('layouts.admin.footer')
