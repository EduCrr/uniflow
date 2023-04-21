@php
    if ($loggedUser->tipo == 'agencia') {
        $layout = 'layouts.agencia';
    } elseif ($loggedUser->tipo == 'colaborador') {
        $layout = 'layouts.colaborador';
    } elseif ($loggedUser->tipo == 'admin') {
        $layout = 'layouts.admin';
    }
@endphp

@extends($layout)

@section('title', 'Meu perfil')
@section('css')
@endsection

@section('content')
    <section>
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="text-center">
                                        <img
                                           src="{{url('/assets/images/users')}}/{{$user->avatar }}"
                                            alt=""
                                            class="rounded-circle img-thumbnail avatar-xl"
                                        />
                                        <div class="online-circle">
                                            <i class="fa fa-circle text-success"></i>
                                        </div>
                                        <h4 class="mt-3">{{ $user->nome }}</h4>
                                        <p class="text-muted font-size-13">
                                            @if ($user->tipo === 'agencia')
                                                Agência
                                            @elseif($user->tipo == 'colaborador')
                                                Colaborador
                                            @elseif($user->tipo == 'admin')
                                                Admin
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="card">
                                <div class="card-body">
                                     <div class="">
                                        <form class="form-horizontal form-material mb-0 needs-validation" method="POST" action="{{route('Usuario.editar_action', ['id' => $user->id])}}" enctype="multipart/form-data" oninput='password_confirmation.setCustomValidity(password_confirmation.value != password.value ? "As senhas não coincidem." : "")' novalidate>
                                            @csrf
                                            <div class="col-md-12 mb-3">
                                                <label for="inputN" class="form-label pt-0">Nome</label>
                                                <input
                                                    id="inputN"
                                                    type="text"
                                                    placeholder="Nome"
                                                    name="nome"
                                                    value="{{ $user->nome }}"
                                                    class="form-control"
                                                    required
                                                />
                                                <div class="invalid-feedback">
                                                    Preencha o campo nome
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label pt-0">Email</label>
                                                    <input
                                                    type="email"
                                                    value="{{ $user->email }}"
                                                    placeholder="Email"
                                                    class="form-control emailUser"
                                                    disabled
                                                    />
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <label for="inputS" class="form-label pt-0">Senha</label>
                                                    <input
                                                    id="inputS"
                                                    type="password"
                                                    placeholder="Nova senha"
                                                    name="password"
                                                    class="form-control"
                                                    />
                                                    <div class="invalid-feedback">
                                                        Preencha o campo senha
                                                    </div>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <label for="inputS2" class="form-label pt-0">Confirmar Senha</label>
                                                    <input
                                                    id="inputS2"
                                                    type="password"
                                                    name="password_confirmation"
                                                    placeholder="Confirmar senha"
                                                    class="form-control"
                                                    />
                                                    <div class="invalid-feedback">
                                                        As senhas não coincidem
                                                    </div>
                                                </div>
                                            </div>
                                             <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label for="estados" class="form-label pt-0">Estado</label>
                                                    <select name="estado_id" id="estados" class="select2 form-control" required>
                                                        @foreach ($estados as $estado )
                                                            <option @if($user['estado'][0]->id == $estado->id) selected @endif value="{{ $estado->id }}">{{ $estado->nome }}</option>
                                                        @endforeach
                                                    </select>
                                                    <div class="invalid-feedback">
                                                        Preencha o campo estado
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label for="cidades" class="form-label pt-0">Cidade</label>
                                                    <select name="cidade_id" id="cidades" class="select2 form-control" required>
                                                       @foreach ($cidades as $cidade )
                                                            <option @if($user['cidade'][0]->id == $cidade->id) selected @endif value="{{ $cidade->id }}">{{ $cidade->nome }}</option>
                                                        @endforeach
                                                    </select>
                                                    <div class="invalid-feedback">
                                                        Preencha o campo cidade
                                                    </div>
                                                </div>
                                            </div> 
                                            @if($loggedUser->tipo === 'agencia' )
                                                <div class="row">
                                                    <div class="col-md-12 mb-3">
                                                        <label for="agencia" class="form-label pt-0">Agência</label>
                                                        <select id="agencia" name="agencia_id" class="select2 form-control" required>
                                                            @if(count($user['usuariosAgencias']) > 0)
                                                                <option value="{{ $user['usuariosAgencias'][0]->id }}">{{ $user['usuariosAgencias'][0]->nome }}</option>
                                                            @endif
                                                        </select>
                                                        <div class="invalid-feedback">
                                                            Preencha o campo agência
                                                        </div>
                                                    </div> 
                                                </div>
                                            @endif
                                            <div class="row">
                                                <div class="col-md-12 mb-3">
                                                    <label for="avatar" class="form-label pt-0">Foto</label>
                                                    <input
                                                        type="file"
                                                        class="form-control"
                                                        id="avatar"
                                                        name="avatar"
                                                        accept="image/png, image/jpeg, image/jpg" 
                                                    />
                                                </div>
                                            </div>
                                            @if($loggedUser->tipo === 'colaborador')
                                                <div class="row">
                                                    <div class="col-md-12 mb-7 removeSelect">
                                                        <label class="col-sm-2 form-label">Marcas</label>
                                                    </div>
                                                    
                                                    <div style="margin-top: 10px">
                                                        @foreach ($user['marcas'] as $marca )
                                                            <span style="background: {{ $marca->cor }}" class="borderPautas">{{ $marca->nome }}</span>
                                                        @endforeach
                                                    </div>
                                                </div>
                                              @endif
                                            <div class="mb-3">
                                                <button type="submit" class="btn btn-primary w-lg leftAuto">Atualizar</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>     
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('plugins')
@endsection


@section('scripts')
    <script src="{{ asset('assets/js/select2.js') }}" ></script>
    <script>
        let imgSrc = $('.img-thumbnail').attr('src');

        $("#avatar").change(function () {
            const file = this.files[0];
            if (file) {
                let reader = new FileReader();
                reader.onload = function (event) {
                    $(".img-thumbnail")
                        .attr("src", event.target.result);
                };
                reader.readAsDataURL(file);
            }else if(file === null || file === undefined){
                $(".img-thumbnail").attr("src", imgSrc);
            }
        });

        $(document).ready(function() {

            $('.select2').select2({
                minimumResultsForSearch: Infinity
            });
              
            $('#estados').on('change', function() {
                id = this.value;

                $("#cidades").find("option").remove();
                
                $.ajax({
                    url: "/estados/" + id,
                    type: "get",
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    },
                    success: function (response) {
                        let len = 0;
                        if (response["data"] != null) {
                            len = response["data"].length;
                        }

                        if (len > 0) {
                            for (let i = 0; i < len; i++) {
                                let id = response["data"][i].id;
                                let name = response["data"][i].nome;

                                let option =
                                    "<option value='" + id + "'>" + name + "</option>";

                                $("#cidades").append(option);
                            }
                        }
                    },
                });

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
@endsection

