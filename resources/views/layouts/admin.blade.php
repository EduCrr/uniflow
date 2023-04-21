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

    {{-- <link href="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js" rel="preload" as="script"/> --}}
    <link href="{{ asset('assets/js/jquery-ajax.js') }}" rel="preload" as="script"/>

    <link href="{{ asset('/assets/js/functions.js') }}" rel="preload" as="script"/>
   
    <link href="{{ asset('assets/libs/metrojs/release/MetroJs.Full/MetroJs.min.css') }}" rel="stylesheet" type="text/css" />
    <link  href="{{ asset('assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/filepond.css') }}" rel="stylesheet" type="text/css" />

    <link href="{{ asset('assets/css/app.min.css') }}"  id="app-style" rel="stylesheet" type="text/css" />
    {{-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /> --}}
    <link href="{{ asset('assets/css/select2.css') }}" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}"/>
    <link rel="stylesheet" href="{{ asset('/assets/slick/slick.css') }}"  type="text/css">
    <link rel="stylesheet" href="{{ asset('/assets/slick/slick-theme.css') }}"  type="text/css">
	
</head>
<body class="sidebar-enable vertical-collpsed">
    <header id="page-topbar">
        <div class="navbar-header">
            <div class="d-flex">
                <div class="navbar-brand-box">
                    <a href="{{ route('Admin')}}" class="logo logo-dark">
                        <img src="{{url('/assets/images/unicasa.png')}}"  alt="" height="22">    
                    </a>
                </div>

                <button type="button" class="btn btn-sm px-3 font-size-24 header-item waves-effect" id="vertical-menu-btn">
                    <i class="mdi mdi-menu"></i>
                </button>
                
                <div class="d-none d-sm-block ms-1">
                    <div class="dropdown">
                        <button type="button" class="btn header-item waves-effect" data-bs-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            <i class="mdi mdi-plus-box-multiple"></i>
                            <span class="d-none d-xl-inline-block ms-1 {{  Request::is('admin/jobs', 'admin/etapas') ? 'btnActive' : 'btnNotActive' }} ">Jobs</span>
                            <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                        </button>
                        <div class="dropdown-menu">
                            <a href="{{route('Admin.jobs')}}" class="dropdown-item">Jobs </a>
                            <a href="{{route('Admin.Etapas')}}" class="dropdown-item">Etapas </a>
                        </div>
                        
                    </div>
                </div>
                <div class="d-none d-sm-block ms-1">
                    <div class="dropdown">
                        <button type="button" class="btn header-item waves-effect" data-bs-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            <i class="mdi mdi-plus-box-multiple"></i>
                            <span class="d-none d-xl-inline-block ms-1 {{  Request::is('admin/agencias', 'admin/agencia/*') ? 'btnActive' : 'btnNotActive' }} ">Agências</span>
                            <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                        </button>
                        <div class="dropdown-menu">
                            <a href="{{route('Admin.agencias')}}" class="dropdown-item">Agências </a>
                            <a href="{{route('Admin.agencia_adicionar')}}" class="dropdown-item">Adicionar agência </a>
                        </div>
                        
                    </div>
                </div>
                <div class="d-none d-sm-block ms-1">
                    <div class="dropdown">
                        <button type="button" class="btn header-item waves-effect" data-bs-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            <i class="mdi mdi-plus-box-multiple"></i>
                            <span class="d-none d-xl-inline-block ms-1 {{  Request::is('admin/marcas', 'admin/marca/*') ? 'btnActive' : 'btnNotActive' }} ">Marcas</span>
                            <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                        </button>
                        <div class="dropdown-menu">
                            <a href="{{route('Admin.marcas')}}" class="dropdown-item">Marcas </a>
                            <a href="{{route('Admin.marca_adicionar')}}" class="dropdown-item">Adicionar marca </a>
                        </div>
                        
                    </div>
                </div>
                <div class="d-none d-sm-block ms-1">
                    <div class="dropdown">
                        <button type="button" class="btn header-item waves-effect" data-bs-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            <i class="mdi mdi-plus-box-multiple"></i>
                            <span class="d-none d-xl-inline-block ms-1 {{  Request::is('admin/usuarios', 'admin/usuario/*') ? 'btnActive' : 'btnNotActive' }} ">Usuários</span>
                            <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                        </button>
                        <div class="dropdown-menu">
                            <a href="{{route('Admin.usuarios')}}" class="dropdown-item">Usuários </a>
                            <a href="{{route('Admin.usuario_adicionar')}}" class="dropdown-item">Adicionar usuário</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search input -->
            <div class="search-wrap" id="search-wrap">
                <div class="search-bar">
                    <form method="get" action="{{route('Admin.jobs')}}">
                        <input class="search-input form-control" name="search" placeholder="Pesquisar...">
                        <a href="#" class="close-search toggle-search" data-target="#search-wrap">
                            <i class="mdi mdi-close-circle"></i>
                        </a>
                    </form>
                </div>
            </div>

            <div class="d-flex">
                <div class="dropdown d-none d-lg-inline-block">
                    <button type="button" class="btn header-item toggle-search noti-icon waves-effect"
                        data-target="#search-wrap">
                        <i class="mdi mdi-magnify"></i>
                    </button>
                </div>

                <div class="dropdown d-inline-block d-lg-none ms-2">
                    <button type="button" class="btn header-item noti-icon waves-effect" id="page-header-search-dropdown"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="mdi mdi-magnify"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                        aria-labelledby="page-header-search-dropdown">

                        <form class="p-3">
                            <div class="m-0">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Search ..."
                                        aria-label="Recipient's username">
                                    <button class="btn btn-primary" type="submit"><i class="mdi mdi-magnify"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Notification -->
                {{-- <div class="dropdown d-inline-block">
                    <button type="button" class="btn header-item noti-icon waves-effect notification-step"
                        id="page-header-notifications-dropdown" data-bs-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        <i class="mdi mdi-bell-outline"></i>
                        <span class="badge bg-danger rounded-pill">2</span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                        aria-labelledby="page-header-notifications-dropdown">
                        <div class="p-3">
                            <h6 class="m-0">Notifications (258) </h6>
                        </div>

                        <div data-simplebar style="max-height: 230px;">
                            <a href="" class="text-reset notification-item">
                                <div class="d-flex align-items-start">
                                    <div class="avatar-xs me-3">
                                        <span class="avatar-title bg-primary rounded-circle font-size-16">
                                            <i class="mdi mdi-cart-outline"></i>
                                        </span>
                                    </div>
                                    <div class="flex-1">
                                        <h6 class="mb-1 font-size-15">Your order is placed</h6>
                                        <div class="text-muted">
                                            <p class="mb-1 font-size-12">Dummy text of the printing and typesetting
                                                industry.</p>
                                        </div>
                                    </div>
                                </div>
                            </a>

                            <a href="" class="text-reset notification-item">
                                <div class="d-flex align-items-start">
                                    <div class="avatar-xs me-3">
                                        <span class="avatar-title bg-warning rounded-circle font-size-16">
                                            <i class="mdi mdi-message"></i>
                                        </span>
                                    </div>
                                    <div class="flex-1">
                                        <h6 class="mb-1 font-size-15">New Message received</h6>
                                        <div class="text-muted">
                                            <p class="mb-1 font-size-12">You have 87 unread messages</p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="p-2 border-top d-grid">
                            <a class="btn btn-sm btn-link font-size-14 btn-block text-center" href="javascript:void(0)">
                                <i class="mdi mdi-arrow-right-circle me-1"></i> View all
                            </a>
                        </div>
                    </div>
                </div> --}}

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
                    
                        <a class="dropdown-item" href="{{route('Admin.jobs')}}"><i class="mdi mdi-book me-2 font-size-16"></i>
                            Jobs    
                        </a>
                        <a class="dropdown-item d-block"href="{{route('Usuario')}}">
                            <i class="mdi mdi-cog me-2 font-size-16"></i>Meu perfil  
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item"  href="{{route('logout')}}"><i class="mdi mdi-location-exit me-2 font-size-16"></i>
                            Sair
                         </a>
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
                        <a href="{{ route('Admin') }}" class="waves-effect">
                            <i class="mdi mdi-home me-2 font-size-16"></i> 
                            <span>Home</span>
                        </a>
                    </li>
                    <li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class="mdi mdi-book me-2 font-size-16"></i> 
                            <span>Jobs</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li><a href="{{route('Admin.jobs')}}" class="dropdown-item">Jobs </a></li>
                            <li><a href="{{route('Admin.Etapas')}}" class="dropdown-item">Etapas </a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class="mdi mdi-alpha-a-box me-2 font-size-16"></i>
                            <span>Agências</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li><a href="{{route('Admin.agencias')}}" class="dropdown-item">Agências </a></li>
                            <li> <a href="{{route('Admin.agencia_adicionar')}}" class="dropdown-item">Adicionar agência </a></li>
                        </ul>
                    </li>
                     <li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class="mdi mdi-alpha-m-circle me-2 font-size-16"></i>
                            <span>Marcas</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li><a href="{{route('Admin.marcas')}}" class="dropdown-item">Marcas </a></li>
                            <li> <a href="{{route('Admin.marca_adicionar')}}" class="dropdown-item">Adicionar marca </a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class="mdi mdi-account me-2 font-size-16"></i>
                            <span>Usuários</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li><a href="{{route('Admin.usuarios')}}" class="dropdown-item">Usuários </a></li>
                            <li> <a href="{{route('Admin.usuario_adicionar')}}" class="dropdown-item">Adicionar usuários </a></li>
                        </ul>
                    </li>
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
                    <script>document.write(new Date().getFullYear())</script> © UNICASA
                </div>
                <div class="col-sm-6">
                    {{-- <div class="text-sm-end d-none d-sm-block">
                        Criado por: 8poroito
                    </div> --}}
                </div>
            </div>
        </div>
    </footer>

    @include('sweetalert::alert')

    {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> --}}
    <script src="{{ asset('assets/js/jquery-ajax.js') }}" ></script>

    {{-- <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}
    <script src="{{ asset('assets/js/sweetalert2.js') }}" ></script>

    <!-- include FilePond plugins -->
    <script src="{{ asset('assets/js/filepond.js') }}" ></script>
    <script src="{{ asset('assets/js/filepond-image.js') }}" ></script>
    <!-- include FilePond jQuery adapter -->
    
    <!-- JAVASCRIPT -->
    {{-- <script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script> --}}
    <script src="{{ asset('assets/slick/slick.min.js') }}" ></script>
    <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/libs/metismenu/metisMenu.min.js') }}"></script>
    <script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/libs/node-waves/waves.min.js') }}"></script>
    <!--Morris Chart-->
    <script src="{{ asset('assets/libs/morris.js/morris.min.js') }}" ></script>
    <script src="{{ asset('assets/libs/raphael/raphael.min.js') }}"></script>
    <script src="{{ asset('assets/libs/jquery-knob/jquery.knob.min.js') }}"></script>
    <script src="{{ asset('assets/libs/metrojs/release/MetroJs.Full/MetroJs.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/dashboard.init.js') }}" ></script>
    <script src="{{ asset('assets/js/app.js') }}" ></script>
    <script src="{{ asset('assets/js/functions.js') }}" ></script>
    <!-- PAGE SCRIPTS --> 
    @yield('plugins')
    @yield('scripts')

</body>
</html>