@extends('layouts.admin')
@section('title', 'Usuário '.$user->id)
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
                                    <div class="init">
                                        <h5 class="card-title">Editar usuário</h5>
                                        <a href="{{ route('Admin.usuarios') }}" class="btnBack btn btn-primary">Voltar</a>
                                    </div>
                                    <div class="text-center">
                                        <img
                                           src="{{url('/assets/images/users')}}/{{$user->avatar }}"
                                            alt=""
                                            class="rounded-circle img-thumbnail avatar-xl"
                                        />
                                        <h4 class="mt-3">{{ $user->nome }}</h4>
                                        <p class="text-muted font-size-13">
                                            @if($user->tipo === 'agencia')
                                                Agência
                                            @elseif($user->tipo === 'colaborador')
                                                Colaborador
                                            @else
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
                                        <form class="needs-validation" style="margin-top: 15px" oninput='password_confirmation.setCustomValidity(password_confirmation.value != password.value ? "As senhas não coincidem." : "")' novalidate method="POST"  action="{{ route('Admin.usuario_editar_action', ['id' => $user->id]) }}"  enctype="multipart/form-data">
                                            @csrf
                                            <div class="col-md-12 mb-3">
                                                <label for="inputN" class="form-label pt-0">Nome</label>
                                                <input
                                                    id="inputN"
                                                    type="text"
                                                    placeholder="Nome"
                                                    name="nome"
                                                    value="{{ $user->nome }}"
                                                    required
                                                    class="form-control"
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
                                                    <select name="estado_id" id="estados" class="form-control select2" required>
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
                                                    <select name="cidade_id" id="cidades" class="form-control select2" required>
                                                       @foreach ($cidades as $cidade )
                                                            <option @if($user['cidade'][0]->id == $cidade->id) selected @endif value="{{ $cidade->id }}">{{ $cidade->nome }}</option>
                                                        @endforeach
                                                    </select>
                                                    <div class="invalid-feedback">
                                                        Preencha o campo cidade
                                                    </div>
                                                </div>
                                            </div> 

                                            @if(session('error-ambas'))
                                                <div class="alert alert-danger">
                                                    {{ session('error-ambas') }}
                                                </div>
                                            @endif
                                           
                                            @if($user->tipo == 'agencia')
                                                @if(session('error-ag'))
                                                    <div class="alert alert-danger">
                                                        {{ session('error-ag') }}
                                                    </div>
                                                @endif
                                                <div class="row">
                                                    <div class="col-md-12 mb-3">
                                                        <label for="agencia" class="form-label pt-0">Agência</label>
                                                        <select name="agencia" id="agencia" class="form-control select2">
                                                                @foreach ($agencias as $item )
                                                                   <option @if($user['usuariosAgencias'][0]->id == $item->id) selected @endif value="{{ $item->id }}">{{ $item->nome }}</option>
                                                                @endforeach
                                                        </select>
                                                    </div> 
                                                </div>
                                                <br/>
                                                <div class="mb-1 row agencia_admin">
                                                    <div class="col-lg-12  mo-b-15 alingCheckBox">
                                                        <span>Adicionar como admin</span>
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input getAdminAgValue" type="checkbox"  @if($user->count_userAg > 0) checked @endif name="adminAg" value="true">
                                                        </div>
                                                    </div>
                                                </div>
                                                <br/>
                                            @endif
                                            @if($user->tipo == 'colaborador')
                                                @if(session('error-ag-marca'))
                                                    <div class="alert alert-danger">
                                                        {{ session('error-ag-marca') }}
                                                    </div>
                                                @endif
                                                <div class="row">
                                                    <div class="col-md-12 mb-7 removeSelect">
                                                        <label class="col-sm-2 form-label">Marca(s)</label>
                                                    </div>
                                                    <div style="margin-top: 10px">
                                                       <select required  class="select2-multiple form-control" name="marcas[]" multiple="multiple"
                                                            id="select2Multiple">
                                                            @foreach ($marcas as $marca )
                                                                <option data-cor="{{ $marca->cor }}"  @if (!empty(old('marcas')) && in_array($marca->id, old('marcas'))) selected  @endif value="{{ $marca->id }}">{{ $marca->nome }}</option>
                                                            @endforeach
                                                        </select>
                                                        <div class="invalid-feedback">
                                                            Preencha o campo marca
                                                        </div>
                                                    </div>
                                                </div>
                                                <br/>
                                                @if(session('error-ag'))
                                                    <div class="alert alert-danger">
                                                        {{ session('error-ag') }}
                                                    </div>
                                                @endif
                                                <div class="row">
                                                    <div class="col-md-12 mb-7 removeSelect">
                                                        <label class="col-sm-2 form-label">Agência(s)</label>
                                                    </div>
                                                    <div style="margin-top: 10px">
                                                       <select required  class="select2-multiple form-control" name="agencias_colaboradores[]" multiple="multiple"
                                                            id="select2Multiple-ag">
                                                            @foreach ($agencias as $agencia )
                                                                <option  data-cor="{{ '#222' }}"  @if (!empty(old('agencias_colaboradores')) && in_array($marca->id, old('agencias_colaboradores'))) selected  @endif value="{{ $agencia->id }}">{{ $agencia->nome }}</option>
                                                            @endforeach
                                                        </select>
                                                        <div class="invalid-feedback">
                                                            Preencha o campo agência
                                                        </div>
                                                    </div>
                                                   
                                                </div>
                                                <br/>
                                            @endif
                                            @if($user->tipo == 'agencia')
                                                @if(session('error-ag-marca'))
                                                    <div class="alert alert-danger">
                                                        {{ session('error-ag-marca') }}
                                                    </div>
                                                @endif
                                                <div class="row marcas">
                                                    <div class="col-md-12 mb-7 removeSelect">
                                                        <label class="col-sm-2 form-label">Marca(s)</label>
                                                    </div>
                                                    <div style="margin-top: 10px">
                                                       <select required  class="select2-multiple form-control" name="marcas[]" multiple="multiple"
                                                            id="select2Multiple">
                                                            @foreach ($marcas as $marca )
                                                                <option data-cor="{{ $marca->cor }}"  @if (!empty(old('marcas')) && in_array($marca->id, old('marcas'))) selected  @endif value="{{ $marca->id }}">{{ $marca->nome }}</option>
                                                            @endforeach
                                                        </select>
                                                        <div class="invalid-feedback">
                                                            Preencha o campo marca
                                                        </div>
                                                    </div>
                                                </div>
                                                <br/>
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
                                                    <p style="margin-top: 15px"><span style="background:#d9ba14; color:white; padding: 6px 3px; border-radius: 4px">TAMANHO!</span> A imagem deve ter no mínimo 128px de largura e 128px de altura.</p>
                                                </div>
                                            </div> 
                                             
                                            <div class="mb-3">
                                               <button type="submit" class="btn btn-primary w-lg leftAuto">Editar usuario</button>
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

        $(document).ready(function() {
            let userAg = @json($user->tipo);

            if(userAg === 'agencia'){
                const verifySwitch = (is_checked) =>{
                    if(is_checked){
                        $('.marcas').removeClass('hidden'); 
                        $('#select2Multiple').attr("required", "true").prop('disabled', false);
                    }else{
                        $('.marcas').addClass('hidden'); 
                        $('#select2Multiple').attr("required", "false").prop('disabled', true);
                    }
                }

                let isAdminAg = @json($user->count_userAg);
                let is_checked = isAdminAg === 0 ? false : true;
                verifySwitch(is_checked);

                $('.getAdminAgValue').on('click', function() {
                    is_checked = $(this).prop('checked');
                    verifySwitch(is_checked);
                });
            }
            
            $('.select2').select2({
                minimumResultsForSearch: Infinity
            });
            
            // Select2 Multiple
            $('.select2-multiple').select2({
                placeholder: "Selecionar marca(s)",
                allowClear: true,
                templateSelection: function (data, container) {
                    var cor = $(data.element).data('cor'); // pega a cor do data-cor
                    $(container).css("background-color", cor); // define a cor de fundo do option
                    return data.text;
                },
            });

            $('.select2-multiple-ag').select2({
                placeholder: "Selecionar agência(s)",
                allowClear: true,
                templateSelection: function (data, container) {
                    var cor = $(data.element).data('cor'); // pega a cor do data-cor
                    $(container).css("background-color", cor); // define a cor de fundo do option
                    return data.text;
                },
            })

            let ids = @json($idsBrands);
            $('#select2Multiple').val(ids).trigger('change');

            let idsAg = @json($idsAgencys);
            $('#select2Multiple-ag').val(idsAg).trigger('change');

            let imgSrc = $('.img-thumbnail').attr('src');
              
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

           
        });
    </script>
@endsection

