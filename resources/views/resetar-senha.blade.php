<!doctype html>
<html lang="pt_BR">

<head>

    <meta charset="utf-8" />
    <title>Resetar senha</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.ico">
    <!-- Bootstrap Css -->
    <link href="assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- Bootstrap Css -->
    <link  href="{{ asset('assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}"/>
     

</head>

<body class="auth-body-bg">
    <div class="adjustResetPassword">
        <div class="container-fluid">
            <!-- Log In page -->
            <div class="row">
                <div class=" pe-0 my-auto">
                    <div class="card mb-0 shadow-none">
                        <div class="card-body adJustCardEmail">
    
                            <div class="px-2 mt-2">
                                <h5 class="text-muted text-center">Atualize sua nova senha.</h5>
    
                                <form class="needs-validation" style="margin-top: 15px" oninput='password_confirmation.setCustomValidity(password_confirmation.value != password.value ? "As senhas não coincidem." : "")' novalidate method="POST" action="{{route('Resetpassword')}}"  enctype="multipart/form-data">
                                    @csrf
                                    <input id="token" value="{{$token}}" name="token" type="hidden" class="form-control" >
                                    <div class="mb-3">
                                        <label class="form-label" for="username">E-mail</label>
                                        <div class="input-group">
                                            <span class="input-group-text" id="basic-addon1"><i class="far fa-user"></i></span>
                                            <input value="{{$email}}" disabled name="email" type="email" class="form-control emailDisabled"  placeholder="E-mail">
                                            <input value="{{$email}}"  name="email" type="hidden" class="form-control">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="userpassword">Senha</label>
                                        <div class="input-group">
                                            <span class="input-group-text" id="basic-addon2"><i class="fa fa-key"></i></span>
                                            <input name="password" value="" class="form-control" type="password" required  id="inputS">
                                            <div class="invalid-feedback">
                                                Preencha o campo senha
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="userpassword">Confirmar Senha</label>
                                        <div class="input-group">
                                            <span class="input-group-text" id="basic-addon2"><i class="fa fa-key"></i></span>
                                            <input name="password_confirmation" value="" class="form-control" type="password" required  id="inputS2">
                                            <div class="invalid-feedback">
                                                As senhas não coincidem
                                            </div>
                                        </div>
                                    </div>
    
    
                                    <div class="mb-3 mb-0 row">
                                        <div class="col-12 mt-2">
                                            <button class="btn btn-primary w-100 waves-effect waves-light btnlogin" type="submit">Atualizar</button>
                                        </div>
                                        <!-- end col -->
                                    </div>
    
                                    @if($errors->any())
                                        <div class="alert alert-danger">
                                            {{ $errors->first() }}
                                        </div>
                                    @endif
    
                                    @if (session('error'))
                                        <div class="alert alert-danger">
                                            {{ session('error') }}
                                        </div>
                                    @endif


                                    @if (session('success'))
                                        <div class="alert alert-success">
                                            {{ session('success') }}
                                        </div>
                                    @endif
                                 
                                    <!-- end row -->
                                </form>
                                <!-- end form -->
                            </div>
                        
                        </div>
                    </div>
                </div>
                <!-- end col -->
                <!-- end col -->
            </div>
            <!-- End Log In page -->
        </div>
    </div>
    



    <!-- JAVASCRIPT -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="assets/libs/jquery/jquery.min.js"></script>
    <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/libs/metismenu/metisMenu.min.js"></script>
    <script src="assets/libs/simplebar/simplebar.min.js"></script>
    <script src="assets/libs/node-waves/waves.min.js"></script>

    <script src="assets/js/app.js"></script>

    <script>
         $(document).ready(function() {
            $('#email, #inputS, #inputS2').click(function() {
                $('.alert-danger').slideUp();
            });
        });

        (function () {
        'use strict'

        var forms = document.querySelectorAll('.needs-validation')
      
        Array.prototype.slice.call(forms)
            .forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
                }

                form.classList.add('was-validated')
            }, false)
            })
        })()
    </script>

</body>

</html>