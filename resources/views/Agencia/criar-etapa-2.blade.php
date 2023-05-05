@php
    $layout = $isAdminAg > 0 ? 'layouts.agencia' : 'layouts.colaborador';
@endphp

@extends($layout)
@section('title', 'Criar etapa 2')

@section('css')
@endsection

@section('content')

    <section>
          <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                   <div class="row">
                        <div class="">
                            <div class="custom-tab tab-profile">
                                <div class="tab-content pt-4">
                                    <div class="tab-pane active" id="job" role="tabpanel" >
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <h5 class="card-title">Etapa 2</h5>
                                                        <br/>
                                                        <p style="margin-top: 0px; margin-bottom: 0px">Numeração do JOB: {{$demanda->id}}</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <form id="formEdit" style="margin-top: 15px" method="POST" action="{{route('Agencia.criar_action_stage_2', ['id' => $demanda->id])}}" enctype="multipart/form-data" class="needs-validation" novalidate>
                                                @csrf
                                                <div class="col-sm-12">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <div class="adjustBriefing">
                                                                <h5 class="card-title comments">Etapa 1</h5>
                                                                <a class="arounded" data-bs-toggle="collapse" href="#collapseEtapa1" role="button" aria-expanded="false" aria-controls="collapseBriefing">
                                                                    <i style="cursor: pointer" class="fas fa-angle-down"></i>
                                                                </a>
                                                            </div>
                                                            <div class="collapse" id="collapseEtapa1">
                                                                <div class="mb-3 row">
                                                                    <div class="col-lg-4">
                                                                        <label for="inputT" class="form-label pt-0">Título</label>
                                                                        <div class="">
                                                                            <input name="titulo" value="{{$demanda->titulo}}" class="form-control" type="text" required id="inputT">
                                                                            <div class="invalid-feedback">
                                                                                Preencha o campo título
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-4">
                                                                        <label for="inputS" class="col-sm-2 form-label">Usuario(s)</label>
                                                                        <div class="">
                                                                            <select class="select2-multiple-user form-control" name="users[]" multiple="multiple" required
                                                                                id="select2MultipleUser">
                                                                                @foreach ($users['agenciasUsuarios'] as $user )
                                                                                    <option value="{{ $user->id }}" data-cor="#222">{{ $user->nome }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                            <div class="invalid-feedback">
                                                                                Preencha o campo marca
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-4">
                                                                        <label for="inputC" class="col-sm-2 form-label">Colaborador</label>
                                                                        <div class="">
                                                                            <select id="inputC" name="colaborador" class="form-select select2" required>
                                                                                @foreach ($colaboradores as $colaborador)
                                                                                    <option  @if (!empty(old('colaborador')) && in_array($colaborador->id, old('colaborador'))) selected  @elseif($demanda->criador_id === $colaborador->id) selected @endif value="{{ $colaborador->id }}">{{ $colaborador->nome }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                            <div class="invalid-feedback">
                                                                                Preencha o campo colaborador
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="mb-3 row">
                                                                    <div class="col-lg-6">
                                                                        <label for="inputS" class="col-sm-2 form-label">Marcas</label>
                                                                        <div class="">
                                                                            <select class="select2-multiple form-control" name="marcas[]" multiple="multiple" required
                                                                                id="select2Multiple">
                                                                                @foreach ($marcas as $marca )
                                                                                    <option value="{{ $marca->id }}" data-cor="{{ $marca->cor }}">{{ $marca->nome }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                            <div class="invalid-feedback">
                                                                                Preencha o campo marca
                                                                            </div>
                                                                        </div>
                                                                        
                                                                    </div>
                                                                    <div class="col-lg-6">
                                                                        <label for="inputP" class="col-sm-2 form-label">Prioridade</label>
                                                                        <div class="">
                                                                            <select id="inputP" name="prioridade" class="form-select select2" required>
                                                                                <option @if($demanda->prioridade === 1) selected @endif  value="1">Baixa</option>
                                                                                <option @if($demanda->prioridade === 5) selected @endif value="5">Média</option>
                                                                                <option @if($demanda->prioridade === 7) selected @endif value="7">Alta</option>
                                                                                <option @if($demanda->prioridade === 10) selected @endif value="10">Urgente</option>
                                                                            </select>
                                                                            <div class="invalid-feedback">
                                                                                Preencha o prioridade
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="mb-3 row">
                                                                    <div class="col-lg-6  mo-b-15">
                                                                        <label for="inputI"
                                                                            class="col-sm-2 form-label">Data inicial</label>
                                                                        <div class="">
                                                                            <input value="{{ $demanda->inicio }}" class="form-control" name="inicio" type="datetime-local" required
                                                                            id="inputI">
                                                                            <div class="invalid-feedback">
                                                                                Preencha o campo data inicial
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-6">
                                                                        <label for="inputF"
                                                                            class="col-sm-2 form-label">Data de entrega</label>
                                                                        <div class="">
                                                                            <input name="final" value="{{ $demanda->final }}" class="form-control" type="datetime-local" required
                                                                                id="inputF">
                                                                            <div class="invalid-feedback">
                                                                                Preencha o campo data de entrega
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <h5 class="card-title comments">Etapa 2</h5>
                                                            <br/>
                                                            <div class="mb-3 row">
                                                                <div class="col-lg-12  mo-b-15">
                                                                    <label for="inputD" class="col-sm-2 form-label">OneDrive</label>
                                                                    <div class="">
                                                                        <input name="drive" class="form-control" type="text" id="inputD">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            {{-- <div class="mb-3 row">
                                                                <div class="col-lg-12">
                                                                <label for="inputA" class="col-sm-2 form-label">Novos anexos</label>
                                                                    <div class="">
                                                                    <input id="inputA" type="file" name="arquivos[]" class="form-control" multiple/>
                                                                    </div>
                                                                </div>
                                                            </div> --}}
                                                            <div class="d-flex justify-content-center" style="height: 15px;">
                                                                <div style="margin-top: 20px;" class="spinner-border" role="status">
                                                                    <br/>
                                                                    <span class="sr-only">Carregando...</span>
                                                                </div>
                                                            </div>
                                                            <div class="showBriefing">
                                                                <div class="mb-3 row">
                                                                    <div class="col-lg-12  mo-b-15">
                                                                        <label for="example-datetime-local-input" class="col-sm-2 form-label">Briefing</label>
                                                                        <textarea class="elm1" id="briefing" required name="briefing">{{ $demanda->briefing }}</textarea>
                                                                            <div class="invalid-feedback">
                                                                            Preencha o briefing
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <button id="submitButtonEdit" type="submit" class="btn btn-primary w-lg leftAuto">Finalizar etapa 2</button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 col-xl-12">
                                                    <div class="card">
                                                        <div class="card-body">
                                                             <form method="post" action="{{route('Imagem.upload', ['id' => $demanda->id])}}" enctype="multipart/form-data"  method="post">
                                                                @csrf
                                                                <div class="row gx-3">
                                                                    <div class="col-md-12">
                                                                        <div class="mb-3">
                                                                            <input data-url="{{route('Imagem.upload', ":id")}}" type="file" id="file-epata-2" name="file[]" multiple />
                                                                            <input type="hidden" id="textbox_id" value="{{ $demanda->id }}"/>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                            <div class="col-xl-12">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <h5 class="card-title">Arquivo(s) anexado(s)</h5>
                                                        @foreach ( $demanda['imagens'] as $item )
                                                            <div class="dropdown">
                                                                <button style="padding:0px" type="button" class="btn header-item waves-effect" data-bs-toggle="dropdown"
                                                                    aria-haspopup="true" aria-expanded="false">
                                                                    <span class="d-none d-xl-inline-block ms-1">{{ $item->imagem }}</span>
                                                                    <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                                                                </button>
                                                                <div class="dropdown-menu">
                                                                    <a href="{{ route('download.image', $item->id) }}" class="dropdown-item">Download</a>
                                                                    <form action="{{ route('Imagem.delete', $item->id) }}" method="post">
                                                                        @csrf
                                                                        <button type="submit" class="dropdown-item deleteArq" >Excluir</button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
    {{-- <script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script> --}}
    <script src="{{ asset('assets/libs/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/form-editor.init.js') }}"></script>
@endsection


@section('scripts')
    {{-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> --}}
    {{-- <script src="{{ asset('assets/js/pages/form-repeater.init.js')}}"></script>
    <script src="{{ asset('assets/libs/jquery.repeater/jquery.repeater.min.js')}}"></script> --}}
    <script src="{{ asset('assets/js/select2.js') }}" ></script>
    <script>

        setTimeout(function() {
            $(".showBriefing").css("height", 'auto');
            $(".showBriefing").css("opacity", '1');
            $(".spinner-border").css("display", 'none');
            
        }, 800);

        $('.text-muted-tiny').each(function(){
             var txt = $(this).text();
            $(this).html(txt);
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

        $(document).ready(function() {
            $('.select2').select2({
                minimumResultsForSearch: Infinity
            });

            const form = $("#formEdit");
            const submitButton = $("#submitButtonEdit");
            submitButton.click(function(event) {
                if (form[0].checkValidity()) {
                    event.preventDefault();

                    Swal.fire({
                        title: 'Aguarde',
                        html: 'Enviando dados...',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // envia o formulário após um pequeno intervalo de tempo
                    setTimeout(() => {
                    form.submit();
                    }, 1000);
                } else {
                    form.addClass("was-validated");
                }
            });
            
            // Select2 Multiple
            $('.select2-multiple').select2({
                placeholder: "Selecione seu(s) setor(es)",
                allowClear: true,
                templateSelection: function (data, container) {
                    var cor = $(data.element).data('cor'); // pega a cor do data-cor
                    $(container).css("background-color", cor); // define a cor de fundo do option
                    return data.text;
                },
            });

            $('.select2-multiple-user').select2({
                placeholder: "Selecione seu(s) usuario(s)",
                allowClear: true,
                templateSelection: function (data, container) {
                    var cor = $(data.element).data('cor'); // pega a cor do data-cor
                    $(container).css("background-color", cor); // define a cor de fundo do option
                    return data.text;
                },
            });

            // //setores pré-selecionado

            let ids = @json($marcasIds);
            let idsUser = @json($usersIds);

            let demandaInicio = @json($demanda->inicio);
            let demandaFinal = @json($demanda->final);

            $('#select2Multiple').val(ids).trigger('change');
            $('#select2MultipleUser').val(idsUser).trigger('change');
            
            //calendário

            var inputF = $('#inputF');
            var inputI = $('#inputI');

            function validarDataInicial(inputI, demandaInicio) {
                inputI.on('change', function() {
                    inputF.val('')
                    var value = inputI.val();
                    var selectedDate = new Date(value);
                    var currentDate = new Date();
                    currentDate.setHours('');
                    var day = selectedDate.getDay();
                    if (day === 0 || day === 6) {
                        inputI.val(demandaInicio);
                        Swal.fire({
                            icon: 'warning',
                            title: 'Data inválida',
                            text: 'Favor, selecione uma data em um dia útil.',
                        });
                    } else if (selectedDate < currentDate ) {
                        inputI.val(demandaInicio);
                        Swal.fire({
                            icon: 'warning',
                            title: 'Data inválida',
                            text: 'Favor, selecione em dias úteis e após a data atual',
                        });
                    }
                });
            }

            function validarDataFinal(inputI, inputF) {
                inputF.on('change', function() {
                    var valueI = inputI.val();
                    var valueF = inputF.val();
                    var dateI = new Date(valueI);
                    var dateF = new Date(valueF);
                    var day = dateF.getDay();

                    if (day === 0 || day === 6) {
                        inputF.val('');
                        Swal.fire({
                            icon: 'warning',
                            title: 'Data inválida',
                            text: 'Favor, selecione uma data final em um dia útil.',
                        });
                        return;
                    } else if (dateF < dateI) {
                        inputF.val('');
                        Swal.fire({
                            icon: 'warning',
                            title: 'Data inválida',
                            text: 'A data final não pode ser anterior à data inicial.',
                        });
                        return;
                    }

                    $.ajax({
                        url: "/jobs/date",
                        type: "post",
                        dataType: "json",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        },
                        data: {
                            final: inputF.val(),
                        },
                        success: function (response) {
                            Swal.fire({
                                confirmButtonColor: '#3dbb3d',
                                toast: true,
                                position: 'top-end',
                                title: 'Você tem '+response+' jobs '+'cadastrado nessa data sugerida.',
                                showConfirmButton: true,
                                timer: 5000,
                            })
                        }
                    });
                });
            }

            validarDataInicial(inputI, demandaInicio);
            validarDataFinal(inputI, inputF);
           
        });
    </script>
@endsection