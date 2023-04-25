<!DOCTYPE html>
<html lang="pt-BR">
<head>
    
    <meta charset="utf-8" />
    <title>@yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="_token" content="{{csrf_token()}}" />
    <!-- App favicon -->

    @yield('css')

    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">
    <link href="{{ asset('assets/js/jquery-ajax.js') }}" rel="preload" as="script"/>
    <link href="{{ asset('/assets/js/functions.js') }}" rel="preload" as="script"/>
    <!-- Bootstrap Css -->
    <link  href="{{ asset('assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />

    <link href="{{ asset('assets/css/app.min.css') }}"  id="app-style" rel="stylesheet" type="text/css" />
    {{-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /> --}}
    <link href="{{ asset('assets/css/select2.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}"/>
</head>
<body class="sidebar-enable vertical-collpsed">
    <header id="page-topbar">
        <div class="navbar-header">
            <div class="d-flex">
                <!-- LOGO -->
                <div class="navbar-brand-box">
                    <a href="{{ route('Pautas.home')}}" class="logo logo-dark">
                        <span class="logo-sm">
                            <img src="{{url('assets/images/logo-sm.png')}}"  alt="" height="22">
                        </span>
                        <span class="logo-lg">
                            <img src="{{url('assets/images/logo-dark.png')}}"  alt="" height="17">
                        </span>
                    </a>

                    <a href="index.html" class="logo logo-light">
                        <span class="logo-sm">
                            <img src="{{url('assets/images/logo-sm.png')}}"  alt="" height="22">
                        </span>
                        <span class="logo-lg">
                            <img src="{{url('assets/images/logo-light.png')}}"  alt="" height="17">
                        </span>
                    </a>
                </div>

                <button type="button" class="btn btn-sm px-3 font-size-24 header-item waves-effect" id="vertical-menu-btn">
                    <i class="mdi mdi-menu"></i>
                </button>

            </div>

            <!-- Search input -->
            <div class="search-wrap" id="search-wrap">
                <div class="search-bar">
                    <input class="search-input form-control" placeholder="Search" />
                    <a href="#" class="close-search toggle-search" data-target="#search-wrap">
                        <i class="mdi mdi-close-circle"></i>
                    </a>
                </div>
            </div>

            <div class="d-flex">
                <!-- User -->
                <div class="dropdown d-inline-block">
                    <button type="button" class="btn header-item waves-effect user-step" id="page-header-user-dropdown"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img class="rounded-circle header-profile-user"  src="{{url('/assets/images/users')}}/{{$loggedUser->avatar }}" 
                            alt="Header Avatar">
                        <span class="d-none d-xl-inline-block ms-1">{{ $loggedUser->nome }}</span>
                        <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <a class="dropdown-item" href="{{route('index')}}"><i class="dripicons-user d-inline-block text-muted me-2"></i>
                           Home  
                        </a>
                        <a class="dropdown-item d-block" href="#"><i
                            class="dripicons-gear d-inline-block text-muted me-2"></i>Meu perfil  
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item"  href="{{route('logout')}}"><i class="dripicons-exit d-inline-block text-muted me-2"></i>
                            Sair</a>
                    </div>
                </div>

            </div>
        </div>
    </header>
    <!-- ========== Left Sidebar Start ========== -->
    <div class="vertical-menu">

        <div data-simplebar class="h-100">

            <!--- Sidemenu -->
            <div id="sidebar-menu">
                <!-- Left Menu Start -->
                <ul class="metismenu list-unstyled" id="side-menu">
                    <li class="menu-title">Menu</li>

                    <li>
                        <a href="{{ route('Pautas.home') }}" class="waves-effect">
                            <i class="mdi mdi-speedometer"></i>
                            <span>Home</span>
                        </a>
                    </li>

                    {{-- <li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class="mdi mdi-email-variant"></i>
                            <span>Jobs</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            @if ($loggedUser->tipo === 'admin' )
                                <li><a href="email-inbox.html">Jobs Criados</a></li>
                            @endif
                            <li><a href="email-read.html">Meus jobs</a></li>
                            <li><a href="email-compose.html">Meu perfil</a></li>
                        </ul>
                    </li> --}}
                    <!-- Calender -->
                </ul>
            </div>
            <!-- Sidebar -->
        </div>
    </div>
     <!-- Left Sidebar End -->
    <main>
        @yield('content')
    </main>

    <footer class="footer">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <script>document.write(new Date().getFullYear())</script> © Teste.
                </div>
                <div class="col-sm-6">
                    <div class="text-sm-end d-none d-sm-block">
                        PROJETADO POR: MKT UNICASA TEAM
                    </div>
                </div>
            </div>
        </div>
    </footer>

    @include('sweetalert::alert')

    <!-- PLUGINS SCRIPTS --> 
    <script src="{{ asset('assets/js/jquery-ajax.js') }}" ></script>
    <script src="{{ asset('assets/js/sweetalert2.js') }}" ></script>

     <!-- JAVASCRIPT -->
    <script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/libs/metismenu/metisMenu.min.js') }}"></script>
    <script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/libs/node-waves/waves.min.js') }}"></script>
    <!--Morris Chart-->
   
    <script src="{{ asset('assets/js/pages/dashboard.init.js') }}" ></script>
    <script src="{{ asset('assets/js/app.js') }}" ></script>
    <script src="{{ asset('assets/js/functions.js') }}" ></script>

    <!-- PAGE SCRIPTS --> 
   @yield('plugins')
   @yield('scripts')

</body>
</html>