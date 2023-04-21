<!doctype html>
<html lang="pt_BR">

<head>

    <meta charset="utf-8" />
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.ico">
    <!-- Bootstrap Css -->
    <link href="assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}"/>


</head>

<body class="auth-body-bg">

    <div class="container-fluid">
        <!-- Log In page -->
        <div class="row">
            <div class="col-lg-3 pe-0 my-auto">
                <div class="card mb-0 shadow-none">
                    <div class="card-body">

                        <div class="px-2 mt-2">
                            <h4 class="font-size-18 mb-2 text-center">Bem-vindo(a) novamente!</h4>
                            <p class="text-muted text-center">Faça cadastro para continuar.</p>

                            <form class="form-horizontal my-4" method="POST" action="{{route('login_action')}}">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label" for="username">E-mail</label>
                                    <div class="input-group">
                                        <span class="input-group-text" id="basic-addon1"><i class="far fa-user"></i></span>
                                        <input id="email" value="{{ old('email') }}" name="email" type="email" class="form-control"  placeholder="E-mail">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="userpassword">Senha</label>
                                    <div class="input-group">
                                        <span class="input-group-text" id="basic-addon2"><i
                                                class="fa fa-key"></i></span>
                                        <input id="password" type="password" class="form-control" name="password"   placeholder="Senha">
                                    </div>
                                </div>
                                

                                <div class="mb-3 mb-0 row">
                                    <div class="col-12 mt-2">
                                        <button class="btn btn-primary w-100 waves-effect waves-light btnlogin" type="submit">Logar <i class="fas fa-sign-in-alt ms-1"></i></button>
                                    </div>
                                    <!-- end col -->
                                </div>

                                @if($errors->any())
                                    <div class="alert alert-danger">
                                        {{ $errors->first() }}
                                    </div>
                                @endif

                                @if (session('success'))
                                    <div class="alert alert-success">
                                        {{ session('success') }}
                                    </div>
                                @endif

                                @if(session('error'))
                                    <div class="alert alert-danger">
                                        {{ session('error') }}
                                    </div>
                                @endif
                                <!-- end row -->
                            </form>
                            <!-- end form -->
                        </div>
                        <div class="m-2 text-center bg-light p-4  ">
                            <h4 class="">Esqueceu sua senha? </h4>
                            <p class="text-muted text-center">Recupere aqui</p>
                            <a href="{{route('forgotPassword')}}" class="btn btn-primary waves-effect waves-light btnlogin">Recuperar senha </a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end col -->

            <div class="col-lg-9 p-0 vh-100  d-flex justify-content-center">
                <div class="accountbg d-flex align-items-center">
                    <div class="account-title text-center text-white">
                        <img style="margin-bottom: 10px" src="{{url('/assets/images/unicasa.png')}}"/>
                        <p class="mt-3 font-size-14">Faça login para ter acesso ao nosso sistema.</p>
                        <div class="borderLogin"></div>
                    </div>
                </div>
            </div>
            <!-- end col -->
        </div>
        <!-- End Log In page -->
    </div>



    <!-- JAVASCRIPT -->
    <script src="assets/libs/jquery/jquery.min.js"></script>
    <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/libs/metismenu/metisMenu.min.js"></script>
    <script src="assets/libs/simplebar/simplebar.min.js"></script>
    <script src="assets/libs/node-waves/waves.min.js"></script>

    <script src="assets/js/app.js"></script>

    <script>
         $(document).ready(function() {
            $('#email, #password').click(function() {
                $('.alert-danger').slideUp();
            });
        });
    </script>

</body>

</html>