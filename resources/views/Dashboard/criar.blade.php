@php
    $layout = $isAdminAg > 0 ? 'layouts.agencia' : 'layouts.colaborador';
@endphp

@extends($layout)
@section('title', 'Criar etapa 1')

@section('css')
    {{-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /> --}}
    {{-- <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.0/themes/smoothness/jquery-ui.css"> --}}
    <link href="{{ asset('assets/css/jqueryui.css') }}" rel="stylesheet" type="text/css" />
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
                                    <h5 class="card-title">Criação de job: Etapa 1</h5>
                                    <form id="formCreate" style="margin-top: 15px" method="POST" action="{{route('Job.criar_action')}}" enctype="multipart/form-data" class="needs-validation" novalidate>
                                        @csrf
                                        <div class="mb-3 row">
                                            <div class="col-lg-6  mo-b-15">
                                               <label for="inputT" class="form-label pt-0">Título</label>
                                                <div class="">
                                                    <input name="titulo" value="{{ old('titulo') }}" class="form-control" type="text" required  id="inputT">
                                                    <div class="invalid-feedback">
                                                        Preencha o campo título
                                                    </div>
                                                </div>
                                            </div>
                                            @if($isAdminAg > 0)
                                                <div class="col-lg-6">
                                                    <label class="col-sm-2 form-label">Usuario(s)</label>
                                                    <div class="">
                                                        <select class="select2-multiple-users form-control my-select" name="agencia[]" multiple="multiple" required id="select2MultipleUsers">
                                                            @foreach ($users['agenciasUsuarios'] as $userAg)
                                                                <option  data-cor="{{ '#222' }}"  @if (!empty(old('agencia')) && in_array($marca->id, old('agencia'))) selected  @endif value="{{ $userAg->id }}">{{ $userAg->nome }}</option>
                                                            @endforeach
                                                        </select>
                                                        <div class="invalid-feedback">
                                                            Preencha o campo usuario
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="col-lg-6">
                                                    <label for="agencia" class="col-sm-2 form-label">Agência</label>
                                                    <div class="">
                                                        <select id="agencia" name="agencia" class="form-select select2" required>
                                                            @foreach ($userInfos['colaboradoresAgencias'] as $agencia )
                                                                <option value="{{ $agencia->id }}">{{ $agencia->nome }}</option>
                                                            @endforeach
                                                        </select>
                                                        <div class="invalid-feedback">
                                                            Preencha o campo agência
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="mb-3 row">
                                            <div class="col-lg-6">
                                                <label for="inputP" class="col-sm-2 form-label">Prioridade</label>
                                                <div class="">
                                                    <select id="inputP" name="prioridade" class="form-select select2" required>
                                                        <option value="1" {{ old('prioridade') == 1 ? 'selected' : '' }}>Baixa</option>
                                                        <option value="5" {{ old('prioridade') == 5 ? 'selected' : '' }}>Média</option>
                                                        <option value="7" {{ old('prioridade') == 7 ? 'selected' : '' }}>Alta</option>
                                                        <option value="10" {{ old('prioridade') == 10 ? 'selected' : '' }}>Urgente</option>
                                                    </select>
                                                    <div class="invalid-feedback">
                                                        Preencha o campo prioridade
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <label class="col-sm-2 form-label">Marca</label>
                                                <div class="">
                                                    <select class="select2-multiple form-control my-select" name="marcas[]" multiple="multiple" required id="select2Multiple">
                                                        @foreach ($userInfos['marcas'] as $marca )
                                                            <option @if (!empty(old('marcas')) && in_array($marca->id, old('marcas'))) selected  @endif value="{{ $marca->id }}" data-cor="{{ $marca->cor }}">{{ $marca->nome }}</option>
                                                        @endforeach
                                                    </select>
                                                    <div class="invalid-feedback">
                                                        Preencha o campo marca
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <div class="col-lg-6  mo-b-15">
                                                <label for="inputI"
                                                    class="col-sm-2 form-label">Data inicial</label>
                                                <div class="">
                                                    <input class="form-control" value="{{$dataAtual}}" name="inicio" type="datetime-local" required id="inputI">
                                                    <div class="invalid-feedback">
                                                        Preencha o campo data inicial
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="showInfojobs">
                                                    <label for="inputF" class="col-sm-2 form-label">Data de entrega</label>
                                                </div>
                                              
                                                <div class="">
                                                    <input name="final" value="{{ old('final') }}" class="form-control" type="datetime-local" id="inputF" required>
                                                    <div class="invalid-feedback">
                                                        Preencha o campo data entrega
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary w-lg leftAuto" id="submitButtonCreate">Criar etapa 1</button>
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
    <script src="{{ asset('assets/libs/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/form-editor.init.js') }}"></script>
@endsection

@section('scripts')
    <script src="{{ asset('assets/js/jqueryui.js') }}" ></script>
    <script src="{{ asset('assets/js/select2.js') }}" ></script>

    <script>
        

        $(document).ready(function() {

            $('.select2').select2({
                minimumResultsForSearch: Infinity
            });
            
            const form = $("#formCreate");
            const submitButton = $("#submitButtonCreate");
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

            $('.select2-multiple').select2({
                placeholder: "Selecione seu(s) setor(es)",
                allowClear: true,
                templateSelection: function (data, container) {
                    var cor = $(data.element).data('cor'); // pega a cor do data-cor
                    $(container).css("background-color", cor); // define a cor de fundo do option
                    return data.text;
                },
            });

            $('.select2-multiple-users').select2({
                placeholder: "Selecione seu(s) usuario(s)",
                allowClear: true,
                templateSelection: function (data, container) {
                    var cor = $(data.element).data('cor'); // pega a cor do data-cor
                    $(container).css("background-color", cor); // define a cor de fundo do option
                    return data.text;
                },
            });

            //calendário

            var inputF = $('#inputF');
            var inputI = $('#inputI');

            function validarDataInicial(inputI) {
                $('#jobs').text('').css('display','none');
                inputI.on('change', function() {
                    inputF.val('');
                    var value = inputI.val();
                    var selectedDate = new Date(value);
                    var currentDate = new Date();
                    var day = selectedDate.getDay();
                    currentDate.setHours('');
                    // Verificar se a data selecionada é um fim de semana (sábado ou domingo)
                    if (day === 0 || day === 6) {
                    inputI.val('');
                    Swal.fire({
                        icon: 'warning',
                        title: 'Data inválida',
                        text: 'Favor, selecione uma data em um dia útil.',
                    });
                    } else if (selectedDate < currentDate) {
                    inputI.val('');
                    Swal.fire({
                        icon: 'warning',
                        title: 'Data inválida',
                        text: 'Favor, selecione uma data posterior ou igual à data atual.',
                    });
                    }
                });
            }
                     
            function validarDataFinal(inputI, inputF) {
                
                inputF.on('change', function() {
                    if(inputI.val() === ''){
                        inputF.val('');

                        Swal.fire({
                            icon: 'warning',
                            title: 'Data inválida',
                            text: 'Favor, preencha a data inicial.',
                        });
                        return;
                    }
                    var valueI = inputI.val();
                    var valueF = inputF.val();
                    var dateI = new Date(valueI);
                    var dateF = new Date(valueF);
                    var day = dateF.getDay();

                    // Verificar se a data final é um fim de semana (sábado ou domingo)
                    if (day === 0 || day === 6) {
                        inputF.val('');
                        Swal.fire({
                            icon: 'warning',
                            title: 'Data inválida',
                            text: 'Favor, selecione uma data final em um dia útil.',
                        });
                        return;
                    } else if (dateF <= dateI) {
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

            validarDataInicial(inputI);
            validarDataFinal(inputI, inputF);
 
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
