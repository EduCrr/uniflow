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
    <link href="{{ asset('assets/libs/metrojs/release/MetroJs.Full/MetroJs.min.css') }}" rel="stylesheet" type="text/css" />
    <link  href="{{ asset('assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/filepond.css') }}" rel="stylesheet" type="text/css" />

    <link href="{{ asset('assets/css/app.min.css') }}"  id="app-style" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/select2.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}"/>
     <link rel="stylesheet" href="{{ asset('/assets/slick/slick.css') }}"  type="text/css">
    <link rel="stylesheet" href="{{ asset('/assets/slick/slick-theme.css') }}"  type="text/css">
	
</head>
<body class="sidebar-enable vertical-collpsed">
    <header id="page-topbar">
        <div class="navbar-header">
            <div class="d-flex">
                <!-- LOGO -->
                <div class="navbar-brand-box">
                    <a href="{{ route('index')}}" class="logo logo-dark">
                        <span class="logo-sm">
                            <img src="{{url('/assets/images/agency')}}/{{$agenciaLogged['usuariosAgencias'][0]->logo }}"   alt="" height="22">
                        </span>
                        <span class="logo-lg">
                            <img src="{{url('/assets/images/agency')}}/{{$agenciaLogged['usuariosAgencias'][0]->logo }}"   alt="" height="22">
                        </span>
                    </a>
                    <a href="index.html" class="logo logo-light">
                        <span class="logo-sm">
                            <img src="{{url('/assets/images/agency')}}/{{$agenciaLogged['usuariosAgencias'][0]->logo }}"   alt="" height="22">
                        </span>
                        <span class="logo-lg">
                            <img src="{{url('/assets/images/agency')}}/{{$agenciaLogged['usuariosAgencias'][0]->logo }}"   alt="" height="22">
                        </span>
                    </a>
                </div>
                <button type="button" class="btn btn-sm px-3 font-size-24 header-item waves-effect" id="vertical-menu-btn">
                    <i class="mdi mdi-menu"></i>
                </button>
                <div class="d-none d-lg-inline-block">
                    <button
                        type="button"
                        class="btn header-item noti-icon waves-effect"
                        data-bs-target="#search-wrap"
                    >
                        <i class="mdi mdi-home me-2 font-size-16"></i> <a class="{{ Request::is('/') ? 'btnActive' : 'btnNotActive'  }}" href="{{route('index')}}">Home</a>
                    </button>
                </div>
                <div class="d-none d-lg-inline-block">
                    <button
                        type="button"
                        class="btn header-item noti-icon waves-effect"
                        data-bs-target="#search-wrap"
                    >
                        <i class="mdi mdi-home me-2 font-size-16"></i> <a class="{{ Request::is('minhas-pautas') ? 'btnActive' : 'btnNotActive'  }}" href="{{route('Pautas')}}">Jobs</a>
                    </button>
                </div>
                @if($isAdminAg > 0)
                    <div class="d-none d-sm-block ms-1">
                        <div class="dropdown">
                            <button type="button" class="btn header-item waves-effect" data-bs-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <i class="mdi mdi-plus-box-multiple"></i>
                                <span class="d-none d-xl-inline-block ms-1 {{ Request::is('dashboard/jobs', 'dashboard/criar','dashboard/job/*' ) ? 'btnActive' : 'btnNotActive'  }}">Criar Jobs</span>
                                <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                            </button>
                            <div class="dropdown-menu">
                                <a href="{{route('Job.criar')}}" class="dropdown-item">Criar novo job </a>
                                <a href="{{route('Jobs')}}" class="dropdown-item">Filtrar jobs </a>
                                <a href="{{route('Etapas')}}" class="dropdown-item">Etapa 2 </a>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="d-none d-lg-inline-block">
                    <button
                        type="button"
                        class="btn header-item noti-icon waves-effect"
                        data-bs-target="#search-wrap"
                    >
                        <i class="mdi mdi-cog me-2 font-size-16"></i> <a class="{{ Request::is('meu-perfil') ? 'btnActive' : 'btnNotActive'  }}" href="{{route('Usuario')}}">Meu perfil</a>
                    </button>
                </div>
               
            </div>

            <!-- Search input -->
            <div class="search-wrap" id="search-wrap">
                <div class="search-bar">
                    <form method="get" action="{{route('Pautas')}}">
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
                <div class="dropdown d-inline-block">
                    <button type="button" class="btn header-item noti-icon waves-effect notification-step"
                        id="page-header-notifications-dropdown" data-bs-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        <i class="mdi mdi-bell-outline"></i>
                        @if($notificationsCount > 0)
                            <span class="badge bg-danger rounded-pill">{{$notificationsCount}}</span>
                        @endif
                    </button>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                        aria-labelledby="page-header-notifications-dropdown">
                        <div class="p-3">
                            @if($notificationsCount > 0)
                            <h6 class="m-0">Notificações ({{$notificationsCount}}) </h6>
                            @endif
                        </div>

                        <div data-simplebar style="max-height: 425px;">
                            @if(count($notificationsMenu) > 0)
                                @foreach ($notificationsMenu as $item )
                                    <form method="POST" action="{{route('Notification.action.single', ['id' => $item->id])}}">
                                        @csrf
                                        <input type="hidden" name="demandaId" value="{{$item->demanda_id}}">
                                        <button type="submit" class="text-reset notification-item notifyMenu">
                                            <div class="d-flex align-items-start {{ $item->visualizada == 1 ? 'notifyContent' : '' }}">
                                                <div class="avatar-xs me-3">
                                                    <span class="avatar-title bg-primary rounded-circle font-size-16 avatar-notify">
                                                        @if($item->tipo === 'criada')
                                                            <i class="mdi mdi-check text-primary"></i>
                                                        @elseif($item->tipo === 'pauta')
                                                            <i class="mdi mdi-check text-primary"></i>
                                                        @elseif($item->tipo === 'entregue')
                                                            <i class="mdi mdi-check text-primary"></i>
                                                        @elseif($item->tipo === 'finalizado')
                                                            <i class="mdi mdi-check text-primary"></i>
                                                        @elseif($item->tipo === 'reaberto')
                                                            <i class="mdi mdi-check text-primary"></i>
                                                        @elseif($item->tipo === 'questionamento')
                                                            <i class="mdi mdi-alert-outline text-danger"></i>
                                                        @elseif($item->tipo === 'observacao')
                                                            <i class="mdi mdi-comment-outline text-info"></i>
                                                        @elseif($item->tipo === 'alterado')
                                                            <i class="mdi mdi-comment-outline text-info"></i>
                                                        @endif
                                                    </span>
                                                </div>
                                                <div class="flex-1">
                                                    <h6 class="mb-1 font-size-15">Job: {{$item->demanda->titulo}}</h6>
                                                    <div class="text-muted">
                                                        <p class="mb-1 font-size-12">{{ $item->conteudo }}</p>
                                                    </div>
                                                    <p class="notifyDate">{{ Carbon\Carbon::parse($item->criado)->diffForHumans()}}</p>
                                                </div>
                                            </div>
                                        </button>
                                    </form>
                                @endforeach
                            @else
                                <h6 class="text-center" style="margin-bottom: 20px">Nenhuma notificação foi encontrada!</h6>
                            @endif
                        </div>
                        <div class="p-2 border-top d-grid">
                            <a class="btn btn-sm btn-link font-size-14 btn-block text-center" href="{{route('Notification')}}">
                                <i class="mdi mdi-arrow-right-circle me-1"></i> Veja mais 
                            </a>
                        </div>
                    </div>
                </div>

                <!-- User -->
                <div class="dropdown d-inline-block">
                    <button type="button" class="btn header-item waves-effect user-step" id="page-header-user-dropdown"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img class="rounded-circle header-profile-user" src="{{url('/assets/images/users')}}/{{$loggedUser->avatar }}" 
                            alt="Header Avatar">
                        <span class="d-none d-xl-inline-block ms-1">{{ $loggedUser->nome }}</span>
                        <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
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
                        <a href="{{ route('index') }}" class="waves-effect">
                            <i class="mdi mdi-home me-2 font-size-16"></i> 
                            <span>Home</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('Pautas') }}" class="waves-effect">
                            <i class="mdi mdi-book me-2 font-size-16"></i> 
                            <span>Jobs</span>
                        </a>
                    </li>

                    @if($isAdminAg > 0)
                        <li>
                            <a href="javascript: void(0);" class="has-arrow waves-effect">
                                <i class="mdi mdi-book me-2 font-size-16"></i>
                                <span>Criar job</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li><a href="{{route('Job.criar')}}" class="dropdown-item">Criar novo job </a></li>
                                <li> <a href="{{route('Jobs')}}" class="dropdown-item">Filtrar jobs </a></li>
                                <li> <a href="{{route('Etapas')}}" class="dropdown-item">Etapa 2 </a></li>
                            </ul>
                        </li>
                    @endif
                    <li>
                        <a href="{{ route('Usuario') }}" class="waves-effect">
                            <i class="mdi mdi-cog me-2 font-size-16"></i> 
                            <span>Meu perfil</span>
                        </a>
                    </li>
                </ul>
            </div>
            <!-- Sidebar -->
        </div>
    </div>
     <!-- Left Sidebar End -->
     <main>
        @yield('content')
    </main>
    @if($isAdminAg > 0)
        <a href="{{ route('Job.criar')}}" class="floatingBtn">
            <i class="mdi mdi-plus-box-multiple"></i>
        </a>
    @endif
    <footer class="footer">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    PROJETADO POR: MKT UNICASA TEAM
                    {{-- <script>document.write(new Date().getFullYear())</script> © UNICASA --}}
                </div>
                <div class="col-sm-6">
                    
                </div>
            </div>
        </div>
    </footer>

    @include('sweetalert::alert')
    {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> --}}
    <script src="{{ asset('assets/js/jquery-ajax.js') }}" ></script>

    <script src="{{ asset('assets/js/sweetalert2.js') }}" ></script>


    <!-- include FilePond plugins -->
    <script src="{{ asset('assets/js/filepond.js') }}" ></script>
    <script src="{{ asset('assets/js/filepond-image.js') }}" ></script>
    <!-- include FilePond jQuery adapter -->
    
    <!-- JAVASCRIPT -->
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
   @if(session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '{{ session('error') }}',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 5000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            })
        </script>
    @endif

</body>
</html>