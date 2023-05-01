@extends('layouts.admin')
@section('title', 'Adicionar usuários')

@section('css')
@endsection

@section('content')

    <section>
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                   <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="init">
                                        <h5 class="card-title">Adicione um usuário</h5>
                                        <a href="{{ route('Admin.usuarios') }}" class="btnBack btn btn-primary">Voltar</a>
                                    </div>
                                    <form id="userCreation" class="needs-validation" style="margin-top: 15px" oninput='password_confirmation.setCustomValidity(password_confirmation.value != password.value ? "As senhas não coincidem." : "")' novalidate method="POST" action="{{route('Admin.usuario_adicionar')}}"  enctype="multipart/form-data">
                                        @csrf
                                        <div class="mb-3 row">
                                            <div class="col-lg-12  mo-b-15">
                                               <label for="inputN" class="form-label pt-0">Nome</label>
                                               <input name="nome" value="{{ old('nome') }}" class="form-control" type="text" required id="inputN">
                                                <div class="invalid-feedback">
                                                    Preencha o campo nome
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <div class="col-lg-12  mo-b-15">
                                               <label for="inputE" class="form-label pt-0">E-mail</label>
                                               <input name="email" value="{{ old('email') }}" class="form-control" required type="email"  id="inputE">
                                               <div class="invalid-feedback">
                                                    Preencha o campo e-mail
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <div class="col-lg-12  mo-b-15">
                                               <label for="tipo" class="form-label pt-0">Tipo</label>
                                                <select id="tipo" name="tipo" class="form-select select2" required>
                                                    <option {{ old('tipo') == 'agencia' ? 'selected' : '' }} value="agencia">Agência</option>
                                                    <option {{ old('tipo') == 'colaborador' ? 'selected' : '' }} value="colaborador">Colaborador</option>
                                                    <option {{ old('tipo') == 'admin' ? 'selected' : '' }} value="admin">Admin</option>
                                                </select>
                                               <div class="invalid-feedback">
                                                    Preencha o campo tipo
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <div class="col-lg-12  mo-b-15">
                                               <label for="inputS" class="form-label pt-0">Senha</label>
                                               <input name="password" value="" class="form-control" type="password" required  id="inputS">
                                               <div class="invalid-feedback">
                                                    Preencha o campo senha
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <div class="col-lg-12  mo-b-15">
                                               <label for="inputS2" class="form-label pt-0">Confirmar senha</label>
                                               <input name="password_confirmation" value="" class="form-control" type="password" required  id="inputS2">
                                               <div class="invalid-feedback">
                                                   As senhas não coincidem
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3 row agencia">
                                            <div class="col-lg-12  mo-b-15">
                                               <label for="agencia" class="form-label pt-0">Agência</label>
                                               
                                                <select name="agencia_id" id="agencia" class="form-select select2" required>
                                                    @foreach ($agencias as $agencia )
                                                        <option value="{{ $agencia->id }}">{{ $agencia->nome }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="invalid-feedback">
                                                    Preencha o campo agência
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3 row agencia_admin">
                                            <div class="col-lg-12  mo-b-15 alingCheckBox">
                                               <span>Adicionar como admin</span>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input getAdminAgValue" type="checkbox" name="adminAg" value="true">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3 row hidden marcas">
                                            <div class="col-lg-12  mo-b-15">
                                               <label class="form-label pt-0">Marca(s)</label>
                                                <select class="select2-multiple form-control my-select" name="marcas[]"  multiple="multiple"
                                                    id="select2Multiple">
                                                    @foreach ($marcas as $marca )
                                                        <option data-cor="{{ $marca->cor }}" @if (!empty(old('marcas')) && in_array($marca->id, old('marcas'))) selected  @endif value="{{ $marca->id }}">{{ $marca->nome }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="invalid-feedback">
                                                   Preencha o campo marca
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3 row hidden agencias">
                                            <div class="col-lg-12  mo-b-15">
                                               <label class="form-label pt-0">Agência(s)</label>
                                                <select class="select2-multiple-ag form-control my-select" name="agencias_colaboradores[]"  multiple="multiple"
                                                    id="select2Multiple-ag">
                                                    @foreach ($agencias as $agencia )
                                                        <option data-cor="{{ '#222' }}"  @if (!empty(old('agencias_colaboradores')) && in_array($agencia->id, old('agencias_colaboradores'))) selected  @endif  value="{{ $agencia->id }}">{{ $agencia->nome }}</option>
                                                    @endforeach 
                                                </select>
                                                <div class="invalid-feedback">
                                                   Preencha o campo agência
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <div class="col-md-6 mb-3">
                                                <label for="estados" class="form-label pt-0">Estado</label>
                                                <select name="estado_id" id="estados" class="form-select select2" required>
                                                    <option selected="true" value="" disabled="disabled">Escolha um estado</option>    
                                                    @foreach ($estados as $estado )
                                                        <option value="{{ $estado->id }}">{{ $estado->nome }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="invalid-feedback">
                                                   Preencha o campo estado
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="cidades" class="form-label pt-0">Cidade</label>
                                                <select name="cidade_id" id="cidades" class="form-select select2" required></select>
                                                <div class="invalid-feedback">
                                                   Preencha o campo cidade
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <div class="col-lg-12  mo-b-15">
                                               <label for="inputF" class="form-label pt-0">Foto</label>
                                                <input
                                                    id="inputF"
                                                    type="file"
                                                    class="form-control"
                                                    name="logo"
                                                    accept="image/png, image/jpeg, image/jpg" 
                                                />
                                                <p style="margin-top: 15px"><span style="background:#d9ba14; color:white; padding: 6px 3px; border-radius: 4px">TAMANHO!</span> A imagem deve ter no mínimo 128px de largura e 128px de altura.</p>
                                            </div>
                                        </div>
                                        <br/>
                                        <button type="submit" class="btn btn-primary w-lg leftAuto">Criar usuario</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
                <!-- container-fluid -->
            </div>
        </div>
    </section>
@endsection


@section('plugins')
@endsection

@section('scripts')
    <script src="{{ asset('assets/js/select2.js') }}" ></script>
    <script>

        $(document).ready(function() {

            $('.getAdminAgValue').on('click', function() {
                let is_checked = $(this).prop('checked');
                if(is_checked){
                    $('.marcas').removeClass('hidden'); 
                    $('#select2Multiple').attr("required", "true").prop('disabled', false);
                }else{
                    $('.marcas').addClass('hidden'); 
                    $('#select2Multiple').attr("required", "false").prop('disabled', true);
                }
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

            let tipoValue = '{{ old('tipo') }}';
            getFields();
            $('.agencia').removeClass('hidden');

            $('#tipo').on('change', function() {
                tipoValue = $('select[name=tipo] option').filter(':selected').val();
                $('.getAdminAgValue').prop('checked', false);
                getFields();
            });

            function removeFields(){
                $('.agencia').addClass('hidden');
                $('.marcas').addClass('hidden');
                $('.agencias').addClass('hidden');
                $('#select2Multiple').attr("required", "false").prop('disabled', true);
                $('#select2Multiple-ag').attr("required", "false").prop('disabled', true);
                $('#agencia').attr("required", "false").prop('disabled', true);
                $('.agencia_admin').addClass('hidden');
            }
            
           function getFields(){
                if(tipoValue === 'colaborador'){
                    removeFields()
                    
                    $('#select2Multiple').attr("required", "true").prop('disabled', false);
                    $('#select2Multiple-ag').attr("required", "true").prop('disabled', false);
                    $('.marcas').toggleClass('hidden');
                    $('.agencias').toggleClass('hidden');
                   
                }else if(tipoValue === 'agencia'){
                    removeFields()
                    $('.agencia').toggleClass('hidden');
                    $('#agencia').attr("required", "true").prop('disabled', false);
                    $('.marcas').addClass('hidden');
                    $('.agencias').addClass('hidden');
                    $('.agencia_admin').toggleClass('hidden');

                }else if(tipoValue === 'admin'){
                    removeFields()
                }
            }

            $('.select2').select2({
                minimumResultsForSearch: Infinity
            });

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
               
            });
            
            $(".select2-multiple").on("select2:select", function (evt) {
                var element = evt.params.data.element;
                var $element = $(element);

                $element.detach();
                $(this).append($element);
                $(this).trigger("change");
            });

            $('form').validate({
                rules: {
                password: "required",
                password_confirmation: {
                    required: true,
                    equalTo: "#password"
                }
                },
                messages: {
                password_confirmation: {
                    equalTo: "As senhas não coincidem."
                }
                }
            });

           
        });

        
        $('#userCreation').on('submit', function(event) {
            // Realize a validação do formulário
            var form = this;
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            $(form).addClass('was-validated');
        });

    </script>
    
@endsection