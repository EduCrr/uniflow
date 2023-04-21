@php
    $layout = $loggedUser->tipo == 'agencia' ? 'layouts.agencia' : 'layouts.colaborador';
@endphp

@extends($layout)

@section('title', 'Job '. $demanda->id)

@section('css')
    {{-- <link rel="stylesheet" type="text/css" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css">
    <link href="https://unpkg.com/filepond@^4/dist/filepond.css" rel="stylesheet" /> --}}
@endsection

@section('content')
    <section>
        
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="">
                                <div class="custom-tab tab-profile">
                                    <!-- Nav tabs -->
                                    <ul class="nav nav-tabs nav-tabs-custom customResponsiveUl" role="tablist">
                                        <li class="nav-item">
                                            <a id="projectLink" class="nav-link active pb-3 pt-0" data-bs-toggle="tab" href="#projects"
                                                role="tab"><i class="fas fa-check-circle me-2"></i>Job</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link pb-3 pt-0" data-bs-toggle="tab" href="#anexos"
                                                role="tab"><i class="fas fa-suitcase me-2"></i>Anexos</a>
                                        </li>
                                        <li class="nav-item">
                                            <a id="pautasLink" class="nav-link pb-3 pt-0" data-bs-toggle="tab" href="#pautas"
                                                role="tab"><i class="fas fa-calendar-alt  me-2"></i>Pautas</a>
                                        </li>
                                        @if ($loggedUser->id === $demanda->criador_id)
                                            <div class="btnCreate">
                                                <a href="{{route('Job.editar' , ['id' => $demanda->id])}}" class="btn ">Editar</a>
                                            </div>
                                        @endif
                                    </ul>
                                    
                                    <div class="tab-content pt-4">
                                        <div class="tab-pane active" id="projects" role="tabpanel">
                                            <div class="row">
                                                <div class="progressiveBar">
                                                    <small class="float-end ms-2 font-size-12">{{$demanda->porcentagem}}%</small>
                                                    <div class="progress" style="height: 5px">
                                                        <div
                                                        class="progress-bar bg-primary"
                                                        role="progressbar"
                                                        style="width: {{$demanda->porcentagem}}%"
                                                        aria-valuenow="{{$demanda->porcentagem}}"
                                                        aria-valuemin="0"
                                                        aria-valuemax="100"
                                                        ></div>
                                                    </div>
                                                   
                                                </div>
                                                @if(count($lineTime) > 0)
                                                <div class="col-xl-12">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <div class="lineTime">
                                                                <div class="d-flex justify-content-center" style="height: 15px;">
                                                                    <div class="spinner-border" role="status">
                                                                        <span class="sr-only">Carregando...</span>
                                                                    </div>
                                                                </div>
                                                                <ul class="timeline" id="timeline">
                                                                    <div class="carousel">
                                                                        @foreach ($lineTime as $line )
                                                                        <li class="li complete">
                                                                            <div class="timestamp">
                                                                                <span class="author">{{ $line->usuario->nome }}</span>
                                                                                <span class="date"> {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $line->criado)->format('d/m/Y H:i'); }}<span>
                                                                            </div>
                                                                            <div class="status">
                                                                                @if ($line->code == 'questionamento')
                                                                                    <img class="iconStatus" src="{{url('assets/images/atention.png')}}" >
                                                                                    @elseif($line->code == 'reaberto')
                                                                                    <img class="iconStatus" src="{{url('assets/images/reload.png')}}" >
                                                                                    @elseif($line->code == 'alteracao')
                                                                                    <img class="iconStatus" src="{{url('assets/images/alteration.png')}}" >
                                                                                    @elseif($line->code == 'removido')
                                                                                    <img class="iconStatus" src="{{url('assets/images/delete.png')}}" >
                                                                                    @else
                                                                                    <img class="iconStatus" src="{{url('assets/images/verify.png')}}" >
                                                                                @endif
                                                                                <h6> {{ $line->status }} </h6>
                                                                            </div>
                                                                        </li>
                                                                        @endforeach
                                                                        @if($demanda->finalizada != 1)
                                                                        <li class="li" style="margin-top:39px">
                                                                            <div class="timestamp">
                                                                                <span class="author"></span>
                                                                                <span class="date"><span>
                                                                            </div>
                                                                            <div class="status status-final">
                                                                                <h6>Aguardando próxima etapa </h6>
                                                                            </div>
                                                                        </li>
                                                                        @endif
                                                                    </div>
                                                                </ul> 
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                                <div class="col-xl-12">
                                                    
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <div style="display: flex">
                                                                @if($demanda->em_pauta == '0' && $demanda->recebido == 1 && $demanda->finalizada == 0 && $demanda->entregue_recebido == 0 && $demanda->entregue == 0 && $demanda->em_alteracao == 0 && $demanda->pausado == 0)
                                                                    <div class="showStatus" style="background-color: #6c757d">
                                                                        <p>STATUS: RECEBIDO</p>
                                                                    </div>
                                                                @endif

                                                                @if($demanda->em_pauta == '1' && $demanda->pausado == '0')
                                                                    <div class="showStatus" style="background-color: #ff6a30">
                                                                        <p>STATUS: EM PAUTA</p>
                                                                    </div>
                                                                @endif
                                                                
                                                                @if($demanda->entregue == '1' && $demanda->pausado == '0')
                                                                    <div class="showStatus"  style="background-color: #44a2d2">
                                                                        <p>STATUS: ENTREGUE</p>
                                                                    </div>
                                                                @endif

                                                                @if($demanda->finalizada == '1')
                                                                    <div class="showStatus" style="background-color: #3dbb3d">
                                                                        <p>STATUS: FINALIZADO</p>
                                                                    </div>
                                                                @endif

                                                                @if($demanda->pausado == '1')
                                                                    <div class="showStatus" style="background-color: #b3e5ff">
                                                                        <p>STATUS: CONGELADO</p>
                                                                    </div>
                                                                @endif

                                                                @if ($demanda->finalizada === 1)
                                                                    <span data-bs-toggle="modal" style="background-color: #34495E; cursor: pointer;" data-bs-target="#modalReabirJob" class="" id="reopenJob">Reabrir job</span>
                                                                    <div class="card">
                                                                        <div class="modal fade" id="modalReabirJob" tabindex="-1" role="dialog">
                                                                            <div class="modal-dialog" role="document">
                                                                                <div class="modal-content">
                                                                                    <form method="POST"action="{{route('reaberto', ['id' => $demanda->id])}}">
                                                                                        @csrf
                                                                                        <div class="modal-header">
                                                                                            <h5 class="modal-title align-self-center"
                                                                                                id="modalReabirJob">Sugira a nova para data para a entrega do job reaberto</h5>
                                                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                                        </div>
                                                                                        <div class="modal-body">
                                                                                            <div class="row">
                                                                                                <div class="col-md-12">
                                                                                                    <div class="mb-3 no-margin">
                                                                                                        <input required name="sugerido_reaberto" value="{{ old('sugerido_reaberto') }}" class="form-control sugerido"  type="datetime-local" />
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="modal-footer">
                                                                                            <button type="button" class="btn btn-light"
                                                                                                data-bs-dismiss="modal">Fechar</button>
                                                                                            <button type="submit" class="btn btn-primary submitModal">Confirmar</button>
                                                                                        </div>
                                                                                    </form>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            </div>

                                                            <div class="initalResume">
                                                                <div class="nameJob">
                                                                    <h5>{{ $demanda->titulo }}</h5>
                                                                    @if ($showAg)
                                                                        <span data-bs-toggle="modal" data-bs-target="#modalMudarNomeJob"><i style="cursor: pointer" class="fas fa-edit"></i></span>
                                                                        <div class="card" style="position: relative">       
                                                                            <div class="modal fade" id="modalMudarNomeJob" tabindex="-1" role="dialog">
                                                                                <div class="modal-dialog" role="document">
                                                                                    <div class="modal-content">
                                                                                        <form method="POST" action="{{route('Demanda_titulo', ['id' => $demanda->id])}}">
                                                                                            @csrf
                                                                                            <div class="modal-header">
                                                                                                <h5 class="modal-title align-self-center"
                                                                                                    id="modalMudarNomeJob">Editar título do job</h5>
                                                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                                            </div>
                                                                                            <div class="modal-body">
                                                                                                <div class="row">
                                                                                                    <div class="col-md-12">
                                                                                                        <div class="mb-3 no-margin">
                                                                                                            <input class="form-control" value="{{$demanda->titulo}}" required type="text" name="titulo" />
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="modal-footer">
                                                                                                <button type="button" class="btn btn-light"
                                                                                                    data-bs-dismiss="modal">Fechar</button>
                                                                                                <button type="submit" class="btn btn-primary submitModal">Atualizar</button>
                                                                                            </div>
                                                                                        </form>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                    
                                                                </div>
                                                                
                                                                @if ($loggedUser->id === $demanda->criador_id)
                                                                    @if($demanda->finalizada == 0)
                                                                        @if($demanda->entregue == 1 && $demanda->entregue_recebido == 0)
                                                                            <div class="form-check-inline my-2">
                                                                                <div class="form-check">
                                                                                    <form  method="POST" action="{{route('Receber_alteracoes', ['id' => $demanda->id])}}">
                                                                                        @csrf
                                                                                        <div class="checkbox my-2">
                                                                                            <div class="form-check adjustStatus">
                                                                                                <button type="submit"  class="form-control reopenJob fin blockBtn submitQuest">Receber pautas</button>
                                                                                            </div>
                                                                                        </div>
                                                                                    </form>
                                                                                </div>
                                                                            </div>
                                                                        @else
                                                                            @if($demanda->pausado == 1)
                                                                                <div class="form-check-inline my-2">
                                                                                    <div class="form-check">
                                                                                        <form  method="POST" action="{{route('Retomar_action', ['id' => $demanda->id])}}">
                                                                                            @csrf
                                                                                            @if($dataAtual->greaterThan($demanda->final)) 
                                                                                                <div class="checkbox my-2">
                                                                                                    <div class="form-check adjustStatus">
                                                                                                        <span data-bs-toggle="modal"  data-bs-target="#modalRetomarJob" class="form-control reopenJob fin blockBtn">Retomar job</span>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="col-md-6">
                                                                                                    <div class="card">
                                                                                                        <div class="modal fade" id="modalRetomarJob" tabindex="-1" role="dialog">
                                                                                                            <div class="modal-dialog" role="document">
                                                                                                                <div class="modal-content">
                                                                                                                    <div class="modal-header">
                                                                                                                        <h5 class="modal-title align-self-center"
                                                                                                                            id="modalRetomarJob">Novo Prazo final</h5>
                                                                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                                                                    </div>
                                                                                                                    <div class="modal-body">
                                                                                                                        <div class="row">
                                                                                                                            <div class="col-md-12">
                                                                                                                                <div class="mb-3 no-margin">
                                                                                                                                    <input name="newFinalDate" required id="newFinalDate" value="{{ old('newFinalDate') }}" class="form-control" type="datetime-local" />
                                                                                                                                </div>
                                                                                                                            </div>
                                                                                                                        </div>
                                                                                                                    </div>
                                                                                                                    <div class="modal-footer">
                                                                                                                        <button type="button" class="btn btn-light"
                                                                                                                            data-bs-dismiss="modal">Fechar</button>
                                                                                                                        <button type="submit" class="btn btn-primary sendPauta submitModal">Novo prazo</button>
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            @else
                                                                                            <div class="checkbox my-2">
                                                                                                <div class="form-check adjustStatus">
                                                                                                    <button type="submit"  class="form-control reopenJob fin blockBtn submitQuest">Retomar job</button>
                                                                                                </div>
                                                                                            </div>
                                                                                            @endif
                                                                                        </form>
                                                                                    </div>
                                                                                </div>
                                                                            @else
                                                                                <div class="form-check-inline my-2">
                                                                                    <div class="form-check adjustStatus">
                                                                                        <form  method="POST" action="{{route('Pausar_action', ['id' => $demanda->id])}}">
                                                                                            @csrf
                                                                                            <div class="checkbox my-2">
                                                                                                <button type="submit"  class="stopJob submitQuest">Congelar job</button>
                                                                                            </div>
                                                                                        </form>
                                                                                        <form  method="POST" action="{{route('Finalizar_action', ['id' => $demanda->id])}}">
                                                                                            @csrf
                                                                                            <div class="checkbox my-2">
                                                                                                <div class="form-check adjustStatus" style="padding-left: 0px">
                                                                                                    <a href="#alteracao" class="form-control reopenJob alt">Solicitar alteração</a>
                                                                                                    @if($demanda->entregue_recebido == 1)
                                                                                                    <button type="submit"  class="form-control reopenJob fin blockBtn submitFinalize">Finalizar</button>
                                                                                                    @endif
                                                                                                </div>
                                                                                            </div>
                                                                                        </form>
                                                                                    </div>
                                                                                </div>
                                                                            @endif
                                                                        @endif
                                                                    @endif
                                                                @endif
                                                                @if ($showAg && $demanda->recebido == 0 && $demanda->pausado == 0)
                                                                    <form class="changePauta" method="post" action="{{route('Pauta.receber', ['id' => $demanda->id])}}">
                                                                        @csrf
                                                                        <button type="submit"  class="form-control reopenJob fin blockBtn submitQuest">Receber job</button>
                                                                    </form>
                                                                @endif
                                                                @if ($showAg && $demanda->finalizada == 0 && $demanda->recebido == 1 && $demanda->pausado == 0)
                                                                    <div class="iniciateJob">
                                                                        @if($demanda->em_pauta == 0 && $demanda->entregue == 0 && $demanda->em_alteracao == 0)
                                                                            <span data-bs-toggle="modal" data-bs-target="#modalCriarTempoPauta" class="form-control reopenJob fin" id="pautaModal">Iniciar a pauta</span>
                                                                            <form class="changePauta" method="post" action="{{route('Pauta.criar_tempo', ['id' => $demanda->id])}}">
                                                                                @csrf
                                                                                <div class="col-md-6">
                                                                                    <div class="card">
                                                                                        <div class="modal fade" id="modalCriarTempoPauta" tabindex="-1" role="dialog">
                                                                                            <div class="modal-dialog" role="document">
                                                                                                <div class="modal-content">
                                                                                                
                                                                                                    <div class="modal-header">
                                                                                                        <h5 class="modal-title align-self-center"
                                                                                                            id="modalCriarTempoPauta">Prazo sugerido para entrega</h5>
                                                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                                                    </div>
                                                                                                    <div class="modal-body">
                                                                                                        <div class="row">
                                                                                                            <div class="col-md-12">
                                                                                                                <div class="mb-3 no-margin">
                                                                                                                    <input name="sugeridoAg" required id="sugeridoAg" value="{{ old('sugeridoAg') }}" class="form-control" type="datetime-local" />
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div class="modal-footer">
                                                                                                        <button type="button" class="btn btn-light"
                                                                                                            data-bs-dismiss="modal">Fechar</button>
                                                                                                        <button type="submit" class="btn btn-primary sendPauta submitModal">Adicionar pauta</button>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </form>
                                                                        @endif
                                                                        {{-- @if ($demanda->entregue == 1)
                                                                            <h6>Aguardando uma avalição do criador.</h6>
                                                                        @endif --}}
                                                                        <a href="#alteracao" class="form-control reopenJob alt">Questionar</a>
                                                                    </div>
                                                                    
                                                                @endif
                                                            </div>
                                                            
                                                            <div class="contenJob">
                                                                <div class="contentJobSingle">
                                                                    <h6>Prazo inicial</h6>
                                                                    <p>{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $demanda->inicio)->format('d/m/Y H:i'); }}</p>
                                                                </div>
                                                                <div class="contentJobSingle">
                                                                    <h6>Prazo de entrega  
                                                                        <span class="noneSpan" id="tooltip-container">
                                                                            <span class="noneSpan" data-bs-toggle="tooltip"
                                                                                data-bs-placement="right" data-bs-container="#tooltip-container"
                                                                                title="Essa data poderá sofrer alteração caso seja criada uma pauta em que a nova data seja posterior ao prazo sugerido.">
                                                                                <img class="iconStatus" src="{{url('assets/images/alert.png')}}" >
                                                                            </span>
                                                                        </span>
                                                                    </h6>
                                                                    <p>{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $demanda->final)->format('d/m/Y H:i'); }} 
                                                                       
                                                                    </p>
                                                                    
                                                                </div>
                                                                <div class="contentJobSingle">
                                                                    <h6>Criado por</h6>
                                                                    <p>{{ $demanda['criador']->nome }}</p>
                                                                </div>
                                                                <div class="contentJobSingle">
                                                                    <h6>Agência</h6>
                                                                    <p>{{ $demanda['agencia']->nome }}</p>
                                                                </div>
                                                                <div class="contentJobSingle">
                                                                    <h6>Agência usuário(s)</h6>
                                                                    <div class="showUsers">
                                                                        @foreach ($demanda->agencia['agenciasUsuarios'] as $usuario )
                                                                            <span style="background-color: #222">  {{ $usuario->nome }} </span>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                                @if($demanda->drive)
                                                                <div class="contentJobSingle">
                                                                    <h6 style="margin-bottom: 15px">OneDrive</h6>
                                                                    <a class="driveBtn" target="_blank" href="{{$demanda->drive}}">Acessar</a>
                                                                </div>
                                                                @endif
                                                                <div class="contentJobSingle">
                                                                    <h6>Marcas</h6>
                                                                    <div class="showUsers">
                                                                        @foreach ($demanda['marcas'] as $marca )
                                                                            <span style="background-color: {{$marca->cor}}">  {{ $marca->nome }} </span>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xl-12">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <div class="adjustBriefing">
                                                                <h5 class="card-title comments">Briefing</h5>
                                                                <a class="arounded" data-bs-toggle="collapse" href="#collapseBriefing" role="button" aria-expanded="false" aria-controls="collapseBriefing">
                                                                    <i style="cursor: pointer" class="fas fa-angle-down"></i>
                                                                </a>
                                                            </div>
                                                            <div class="collapse" id="collapseBriefing">
                                                                <p class="card-title-desc">
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @if(count($demanda['demandasReabertas']) > 0)
                                                    @foreach ($demanda['demandasReabertas'] as $item)
                                                        <div class="col-xl-12">
                                                            <div class="card">
                                                                <div class="card-body">
                                                                    <div class="adjustBriefing">
                                                                        <h5 class="card-title comments">
                                                                            Job {{$item->status}} @if($item->finalizado != null)  <i class="mdi mdi-check-circle " style="color:#3dbb3d; font-size: 16px;"></i> @elseif (Carbon\Carbon::parse($item->finalizado)->greaterThan(Carbon\Carbon::parse($item->sugerido)))  <span class="atrasado">ATRASADO!</span> @endif
                                                                        </h5>
                                                                        <a class="arounded" data-bs-toggle="collapse" href="#collapse-{{$item->id}}" role="button" aria-expanded="false" aria-controls="collapse-{{$item->id}}">
                                                                            <i style="cursor: pointer" class="fas fa-angle-down"></i>
                                                                        </a>
                                                                    </div>
                                                                    <div class="collapse" id="collapse-{{$item->id}}">
                                                                        <div class="contenJob">
                                                                            <div class="contentJobSingle">
                                                                                <h6>Prazo inicial do job reaberto
                                                                                    
                                                                                </h6>
                                                                                <p>{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item->iniciado)->format('d/m/Y H:i'); }}</p>
                                                                            </div>
                                                                            <div class="contentJobSingle">
                                                                                <h6>Novo prazo de entrega
                                                                                    <span class="noneSpan" id="tooltip-container">
                                                                                        <span class="noneSpan" data-bs-toggle="tooltip"
                                                                                            data-bs-placement="right" data-bs-container="#tooltip-container"
                                                                                            title="Essa data poderá sofrer alteração caso seja criada uma pauta em que a nova data seja posterior ao prazo sugerido.">
                                                                                            <img class="iconStatus" src="{{url('assets/images/alert.png')}}" >
                                                                                        </span>
                                                                                    </span>
                                                                                </h6>
                                                                                <p>{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item->sugerido)->format('d/m/Y H:i'); }}</p>
                                                                            </div>
                                                                            @if($item->finalizado != null)
                                                                                <div class="contentJobSingle">
                                                                                    <h6>Finalizado em</h6>
                                                                                    <p>{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item->finalizado)->format('d/m/Y H:i'); }}</p>
                                                                                </div>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                            <div class="row">
                                                <div class="col-xl-12">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <h5 class="card-title mb-3 comments">Comentários</h5>
                                                            <div data-simplebar style="max-height: 425px;">
                                                                <div class="activity">
                                                                    @foreach ($demanda['questionamentos'] as $key => $item )
                                                                    <img alt="" class="img-activity" src="{{url('/assets/images/users/')}}/{{$item['usuario']->avatar }}">
                                                                        <div class="time-item ">
                                                                            <div class="item-info">
                                                                                <div class="text-muted float-end font-size-10 dateComentary">
                                                                                    {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item->criado)->format('d/m/Y H:i'); }}
                                                                                    
                                                                                </div>
                                                                                <div class="status statusComments">
                                                                                    <h5 class="mb-1">{{ $item['usuario']->nome }}</h5> <span style="background: {{ $item->cor }}" class="answer">{{ $item->tipo }}</span>
                                                                                </div>
                                                                                <p class="text-muted font-size-13 text-muted-tiny commentsUsers" style="margin-top: 6px">
                                                                                    {{ $item->descricao }}
                                                                                </p>
                                                                                @if ($loggedUser->id === $item->usuario_id)
                                                                                    <div class="btns">
                                                                                        <span  onclick="getComentary({{ $item->id }})" class="editBt" data-bs-toggle="modal"
                                                                                            data-bs-target="#exampleModalform">
                                                                                            <i style="cursor: pointer" class="fas fa-edit"></i>
                                                                                        </span>
                                                                                        <form action="{{route('Comentario.delete', ['id' => $item->id])}}" method="post">
                                                                                            @csrf
                                                                                            <div class="right gap-items-2 deleteBtn">
                                                                                                <button type="submit" class="submitForm"> <i class="fas fa-trash"></i></button>
                                                                                            </div>
                                                                                        </form>
                                                                                    </div>
                                                                                @endif
                                                                                @if ($loggedUser->id === $demanda->criador_id && count($item['respostas']) === 0 && $item->usuario_id != $loggedUser->id )
                                                                                        <div class="btns"> 
                                                                                            <span  data-bs-toggle="modal" class="answerBtn" data-bs-target=".exampleModalformResponseCreate{{ $key }}">
                                                                                                <i style="cursor: pointer" class="mdi mdi-send"></i>
                                                                                            </span> 
                                                                                        </div>
                                                                                        <div class="col-md-6">
                                                                                            <div class="card" style="position: relative">
                                                                                                <div class="modal fade exampleModalformResponseCreate{{ $key }}" tabindex="-1" role="dialog">
                                                                                                    <div class="modal-dialog" role="document">
                                                                                                        <div class="modal-content">
                                                                                                            <form method="POST" action="{{route('Answer.create', ['id' => $item->id])}}">
                                                                                                                @csrf
                                                                                                                <input type="hidden" name="agenciaId" value="{{$demanda->agencia_id}}"/>
                                                                                                                <div class="modal-header">
                                                                                                                    <h5 class="modal-title align-self-center exampleModalformResponseCreate{{ $key }}"
                                                                                                                        id="">Responder</h5>
                                                                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                                                                </div>
                                                                                                                <div class="modal-body">
                                                                                                                    <div class="row">
                                                                                                                        <div class="col-md-12">
                                                                                                                            <div class="mb-3 no-margin">
                                                                                                                                <input type="hidden" value="{{ $demanda->id }} " name="demandaId"/>
                                                                                                                                <textarea  id="newContent--{{ $key }}"  class="form-control field-7 elm1" id="modalEl2" name="newContent"></textarea>
                                                                                                                            </div>
                                                                                                                        </div>
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                                <div class="modal-footer">
                                                                                                                    <button type="button" class="btn btn-light"
                                                                                                                        data-bs-dismiss="modal">Fechar</button>
                                                                                                                    <button type="submit" class="btn btn-primary submitModal">Atualizar</button>
                                                                                                                </div>
                                                                                                            </form>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    @endif
                                                                            </div>
                                                                        </div>
                                                                        @foreach ($item['respostas'] as $resposta  )
                                                                            @if($resposta != null)
                                                                            <img style="margin-left: 18px" alt="" class="img-activity" src="{{url('/assets/images/users/')}}/{{$resposta->usuario->avatar }}">
                                                                            <div style="margin-left: 18px" class="time-item">
                                                                                    <div class="item-info">
                                                                                        <div class="text-muted float-end font-size-10 dateComentary">
                                                                                        {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $resposta->criado)->format('d/m/Y H:i'); }}
                                                                                       
                                                                                    </div>
                                                                                    <div class="status statusComments">
                                                                                        <h5 class="mb-1">{{ $resposta->usuario->nome }} </h5> <span class="answer">Resposta</span>
                                                                                    </div>
                                                                                        <p class="text-muted font-size-13 text-muted-tiny" style="margin-top: 6px">
                                                                                            {{ $resposta->conteudo }} 
                                                                                        </p>
                                                                                        @if ($user->id === $resposta->usuario->id)
                                                                                            <div class="btns">
                                                                                                <span onclick="getResponse({{ $resposta->id }})" class="editBt" data-bs-toggle="modal"
                                                                                                    data-bs-target="#exampleModalformResponse">
                                                                                                    <i style="cursor: pointer" class="fas fa-edit"></i>
                                                                                                </span>
                                                                                                <form action="{{route('Answer.delete', ['id' => $resposta->id])}}" method="post">
                                                                                                    @csrf
                                                                                                    <div class="right gap-items-2 deleteBtn">
                                                                                                        <button type="submit" class="submitForm" > <i class="fas fa-trash"></i></button>
                                                                                                    </div>
                                                                                                </form>
                                                                                            </div>
                                                                                        @endif
                                                                                    </div>
                                                                                </div> 
                                                                            @endif
                                                                        @endforeach    
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="card">
                                                                    <div class="modal fade" id="exampleModalform" tabindex="-1" role="dialog">
                                                                        <div class="modal-dialog" role="document">
                                                                            <div class="modal-content">
                                                                                <form method="POST" action="{{route('Comentario.edit')}}">
                                                                                    @csrf
                                                                                    <div class="modal-header">
                                                                                        <h5 class="modal-title align-self-center"
                                                                                            id="exampleModalform">Editar comentário</h5>
                                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                                    </div>
                                                                                    <div class="modal-body">
                                                                                        <div class="row">
                                                                                            <div class="col-md-12">
                                                                                                <div class="mb-3 no-margin">
                                                                                                    <input type="hidden" class="idComment" name="id" />
                                                                                                    <textarea class="form-control field-7 elm1" id="modalEl" name="newContent"></textarea>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="modal-footer">
                                                                                        <button type="button" class="btn btn-light"
                                                                                            data-bs-dismiss="modal">Fechar</button>
                                                                                        <button type="submit" class="btn btn-primary submitModal">Atualizar</button>
                                                                                    </div>
                                                                                </form>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="card">
                                                                    <div class="modal fade" id="exampleModalformResponse" tabindex="-1" role="dialog">
                                                                        <div class="modal-dialog" role="document">
                                                                            <div class="modal-content">
                                                                                <form method="POST" action="{{route('Answer.edit')}}">
                                                                                    @csrf
                                                                                    <div class="modal-header">
                                                                                        <h5 class="modal-title align-self-center"
                                                                                            id="exampleModalformResponse">Editar resposta</h5>
                                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                                    </div>
                                                                                    <div class="modal-body">
                                                                                        <div class="row">
                                                                                            <div class="col-md-12">
                                                                                                <div class="mb-3 no-margin">
                                                                                                    <input type="hidden" class="idCommentResponse" name="id" />
                                                                                                    <textarea  class="form-control field-7 elm1" id="answerModal" name="newContent"></textarea>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="modal-footer">
                                                                                        <button type="button" class="btn btn-light"
                                                                                            data-bs-dismiss="modal">Fechar</button>
                                                                                        <button type="submit" class="btn btn-primary submitModal">Atualizar</button>
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
                                                <div class="col-md-12 col-xl-12">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <form id="formCreateCommentary" class="needs-validation comentario" method="POST" action="{{route('Comentario.action', ['id' => $demanda->id])}}">
                                                                @csrf
                                                                <div class="row gx-3">
                                                                    <div class="col-md-12">
                                                                        <div class="mb-3" id="alteracao">
                                                                            <h5 class="card-title mb-3">Criar um comentário</h5>
                                                                            @if ($showAg)
                                                                                <select name="tipo" class="form-select select2">
                                                                                    @if($demanda->finalizada != 1)
                                                                                        <option value="questionamento">Questionamento</option>
                                                                                    @endif
                                                                                    <option value="observacao">Observação</option>
                                                                                    @if($demanda->entregue == 1)
                                                                                        <option value="entregue">Entregue</option>
                                                                                    @endif
                                                                                </select>
                                                                                <br />  <br />
                                                                            @endif
                                                                            @if ($loggedUser->id === $demanda->criador_id)
                                                                                <select name="tipo" class="form-select select2">
                                                                                    @if($demanda->finalizada == 0 && $demanda->pausado == 0 )
                                                                                     <option value="alteracao">Alteração</option>
                                                                                    @endif
                                                                                    <option value="observacaoadm">Observação</option> 
                                                                                    @if($demanda->finalizada == 1 )
                                                                                        <option value="finalizado">Finalizado</option>
                                                                                    @endif
                                                                                </select>
                                                                                <br />  <br /> 
                                                                                @if($demanda->finalizada == 0 && $demanda->pausado == 0)
                                                                                    <input name="sugeridoComment" id="sugeridoComment" value="" class="form-control" type="datetime-local" style="margin-bottom: 20px" />
                                                                                @endif
                                                                            @endif
                                                                            <textarea id="modalComentaryUser" class="elm1" name="conteudo">{{ old('conteudo') 
                                                                            }}</textarea>
                                                                        </div>          
                                                                    </div>
                                                                </div>
                                                                <button id="submitButtonCreateCommentary" class="btn btn-light mb-0 w-auto leftAuto" type="submit">Enviar</button>
                                                            </form>
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
                                                                            <input data-url="{{route('Imagem.upload', ":id")}}" type="file" id="file" name="file[]" multiple required/>
                                                                            <input type="hidden" id="textbox_id" value="{{ $demanda->id }}"/>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                @if ($loggedUser->id === $demanda->criador_id)
                                                    <div class="col-md-12 col-xl-12">
                                                        <a  href="{{route('Job.delete', ['id' => $demanda->id])}}" class="btn btn-outline-secondary btn-sm edit deleteBt btnDanger" style="background-color: #f73e1d" title="Deletar">
                                                            EXCLUIR JOB
                                                        </a>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="anexos" role="tabpanel">
                                            <div class="row">
                                                <div class="col-xl-12">
                                                    <div class="card">
                                                        <div class="card-body arqs">
                                                            <h5 class="card-title">Arquivos anexados</h5>
                                                            @foreach ( $demanda->imagens as $item )
                                                                <div class="dropdown">
                                                                    <div class="showUserArqs">
                                                                        <img alt="" class="img-activity" src="{{url('/assets/images/users/')}}/{{$item['usuario']->avatar }}">
                                                                        <div class="nameUserArq">
                                                                            <h5 class="mb-1">{{$item['usuario']->nome }}</h5>
                                                                            <button type="button" class="btn header-item waves-effect" data-bs-toggle="dropdown"
                                                                                aria-haspopup="true" aria-expanded="false">
                                                                                <span class="d-none d-xl-inline-block ms-1">{{ $item->imagem }}</span>
                                                                                <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                                                                            </button>
                                                                            <div class="dropdown-menu">
                                                                                <a href="{{ route('download.image', $item->id) }}" class="dropdown-item">Download</a>
                                                                                @if($loggedUser->id === $item['usuario']->id)
                                                                                    <form action="{{ route('Imagem.delete', $item->id) }}" method="post">
                                                                                        @csrf
                                                                                        <button type="submit" class="dropdown-item deleteArq" >Excluir</button>
                                                                                    </form>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                     </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="pautas" role="tabpanel">
                                            <div class="row">
                                                
                                                <div class="col-xl-12">
                                                    @if(count($demanda['prazosDaPauta']) > 0)
                                                        <div class="adjustPautas">
                                                            <p><strong>Agência: {{ $demanda['agencia']->nome }}</strong></p>
                                                            <div class="progressiveBar">
                                                                <small class="float-end ms-2 font-size-12">{{$demanda->porcentagem}}%</small>
                                                                <div class="progress" style="height: 4.5px">
                                                                    <div
                                                                    class="progress-bar bg-primary"
                                                                    role="progressbar"
                                                                    style="width: {{$demanda->porcentagem}}%"
                                                                    aria-valuenow="{{$demanda->porcentagem}}"
                                                                    aria-valuemin="0"
                                                                    aria-valuemax="100"
                                                                    ></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                    @foreach ($demanda['prazosDaPauta'] as $key => $item )
                                                        <div class="card">
                                                            <div class="card-body">
                                                                <div class="initalResume">
                                                                    <div class="nameJob">
                                                                        <h5>{{ $item->status }}
                                                                            @if($item->finalizado != null)
                                                                                <i class="mdi mdi-check-circle " style="color:#3dbb3d; font-size: 16px;"></i>
                                                                                @if($item->atrasada == 0)
                                                                                    <span class="noPrazo">(Entregue no prazo)</span>
                                                                                @else
                                                                                    <span class="atrasadoText">(Entregue com atraso)</span>
                                                                                @endif
                                                                            @endif
                                                                            
                                                                        </h5>
                                                                    </div>
                                                                    @if ($showAg && $demanda->pausado == 0 && $demanda->recebido != 0)
                                                                        @if($item->recebido == 0 && $item->code_tempo === 'alteracao')
                                                                            <form method="POST" action="{{route('Pauta.receber_alteracao', ['id' => $item->id])}}">
                                                                                @csrf
                                                                                <input type="submit" class="form-control reopenJob fin blockBtn submitQuest activePauta" value="Receber alteração">
                                                                                <input type="hidden" name="demandaId" value="{{$demanda->id}}">
                                                                            </form>
                                                                        @endif
                                                                        @if($item->recebido == 1 && $item->sugerido != null)
                                                                            @if($item->iniciado == null)
                                                                                <form method="POST" action="{{route('Pauta.iniciar_tempo', ['id' => $item->id])}}">
                                                                                    @csrf
                                                                                    <input type="submit" class="form-control reopenJob fin blockBtn submitQuest activePauta" value="Iniciar">
                                                                                    <input type="hidden" name="demandaId" value="{{$demanda->id}}">
                                                                                </form>
                                                                            @endif
                                                                            @if($item->iniciado != null && $item->finalizado == null)
                                                                                <form method="POST" action="{{route('Pauta.finalizar_tempo', ['id' => $item->id])}}">
                                                                                    @csrf
                                                                                    <input type="submit" class="form-control reopenJob fin blockBtn submitQuest activePauta" value="Concluir">
                                                                                    <input type="hidden" name="demandaId" value="{{$demanda->id}}">
                                                                                </form>
                                                                            @endif
                                                                        @endif
                                                                    @endif
                                                                </div>
                                                                <div class="">
                                                                    @foreach ($item['comentarios'] as $comentario)
                                                                        <h6>Descrição da alteração:</h6>    
                                                                        <p class="text-muted font-size-13 text-muted-tiny" style="margin-top: 6px">
                                                                            {{ $comentario['descricao'] }}
                                                                        </p>
                                                                    @endforeach
                                                                </div>
                                                                <div class="contenJob">
                                                                    <div class="contentJobSingle">
                                                                        <h6>Iniciada em</h6>
                                                                            @if($item->iniciado == null)
                                                                            <span class="borderPautas" style="background: #686667">Aguardando...</span>
                                                                            @else
                                                                                <span class="borderPautas" style="background: #3dbb3d">{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item->iniciado)->format('d/m/Y H:i'); }}</span>
                                                                            @endif
                                                                    </div>
                                                                    <div class="contentJobSingle">
                                                                        @if($item->code_tempo === 'alteracao')
                                                                            <h6>Novo prazo para entrega</h6>
                                                                        @else
                                                                            <h6>Prazo para entrega</h6>
                                                                        @endif
                                                                        @if($item->sugerido == null)
                                                                            <span class="borderPautas" style="background: #686667">Não definido...</span>
                                                                        @else
                                                                            <span class="borderPautas" style="background: #34495E">{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item->sugerido)->format('d/m/Y H:i'); }}</span>
                                                                        @endif
                                                                    </div>
                                                                    <div class="contentJobSingle">
                                                                        <h6>Finalizada em</h6>
                                                                        @if($item->finalizado == null)
                                                                        <span class="borderPautas" style="background: #686667">Aguardando...</span>
                                                                        @else
                                                                            <span class="borderPautas" style="background: {{ $item->atrasada == 0 ? '#3dbb3d' : '#f73e1d' }}">{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item->finalizado)->format('d/m/Y H:i'); }}</span>
                                                                        @endif
                                                                    </div>
                                                                    <div class="contentJobSingle">
                                                                        <h6>Tempo entre iniciada e finalizada</h6>
                                                                        @if($item->final != null)
                                                                            <p> {{ $item->final }}  </p>
                                                                            @else <span class="borderPautas" style="background: #686667">Aguardando...</span>
                                                                        @endif
                                                                    </div>
                                                                    @if($demanda->pausado == 0)
                                                                        @if($item->aceitar_colaborador == 0 || $item->aceitar_agencia == 0)
                                                                            <div class="contentJobSingle">
                                                                                <div class="acceptRefuseBtn">
                                                                                    @if($item->sugerido != null)
                                                                                        @if($showAg)
                                                                                            @if($item->aceitar_agencia == 0)
                                                                                                <form method="POST" action="{{route('Pauta.Aceitar_tempo_agencia', ['id' => $item->id])}}">
                                                                                                    @csrf
                                                                                                    <input class="accept submitQuest activePauta" type="submit" value="Aceitar novo prazo">
                                                                                                </form>
                                                                                            @endif
                                                                                        @endif
                                                                                        @if(!$showAg)
                                                                                            @if($item->aceitar_colaborador == 0 )
                                                                                                <form method="POST" action="{{route('Pauta.Aceitar_tempo_colaborador', ['id' => $item->id])}}">
                                                                                                    @csrf
                                                                                                    <input class="accept submitQuest activePauta" type="submit" value="Aceitar novo prazo">
                                                                                                </form>
                                                                                            @endif
                                                                                        @endif
                                                                                    @endif
                                                                                    <span data-bs-toggle="modal" data-bs-target=".modalPautaEditar{{ $key }}">{{$item->sugerido != null ? 'Alterar prazo sugerido' : 'Definir um prazo'}}</span>
                                                                                </div>
                                                                                <div class="col-md-12">
                                                                                    <div class="card">
                                                                                        <div class="modal fade modalPautaEditar{{ $key }}"  tabindex="-1" role="dialog">
                                                                                            <div class="modal-dialog" role="document">
                                                                                                <div class="modal-content">
                                                                                                    <form method="POST" class="sugeridoForm" action="{{route('Demanda.prazo.action', ['id' => $item->id])}}">
                                                                                                        @csrf
                                                                                                        <div class="modal-header">
                                                                                                            <h5 class="modal-title align-self-center modalPautaEditar{{ $key }}"
                                                                                                            >Novo prazo para entrega</h5>
                                                                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                                                        </div>
                                                                                                        <div class="modal-body">
                                                                                                            <div class="row">
                                                                                                                <div class="col-md-12">
                                                                                                                    <div class="mb-3 no-margin">
                                                                                                                        <input required name="sugerido" value="{{ old('sugerido') }}" class="form-control sugerido" id="sugerido--{{ $key }}" type="datetime-local" />
                                                                                                                        <br/>
                                                                                                                        <label class="form-label">Descreva o motivo da sua nova alteração!</label>
                                                                                                                        <textarea id="sugeridoText--{{ $key }}" class="form-control field-7 elm1" id="modalEl3" name="sugeridoAlt" ></textarea>
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <div class="modal-footer">
                                                                                                            <button type="button" class="btn btn-light"
                                                                                                                data-bs-dismiss="modal">Fechar</button>
                                                                                                            <button type="submit" class="btn btn-primary submitModal">Editar pauta</button>
                                                                                                        </div>
                                                                                                    </form>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        @endif
                                                                    @endif
                                                                </div>
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
        </div>
    </section>
@endsection
   
@section('plugins')
    <script src="{{ asset('assets/js/jquery.mask.min.js') }}" ></script>
    <script src="{{ asset('assets/libs/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/form-editor.init.js') }}"></script>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
   <script type="text/javascript">
        var briefing = @json($demanda->briefing);

        $('.select2').select2({
            minimumResultsForSearch: Infinity
        });
        
        $('form').submit(function() {
            $('.blockBtn').prop('disabled', true).text('Enviando...');
        });

        $('.comentario').submit(function() {
            $('.leftAuto').prop('disabled', true).text('Enviando...');
        });
        

		$(".card-title-desc").html(briefing);

        $('.text-muted-tiny').each(function(){
             var txt = $(this).text();
            $(this).html(txt);
        });
        
        $('.nav-item').bind('click', function(){
            $('.carousel').slick('refresh');
        });
       
        $(document).ready(function () {

            const form = $("#formCreateCommentary");
            const submitButton = $("#submitButtonCreateCommentary");
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
          

            // const form = $(".changePauta");
            // const submitButton = $(".sendPauta");

            // submitButton.click(function(event) {
            //     if (form[0].checkValidity()) {
            //     submitButton.prop("disabled", true).text('Enviando...');
            //     event.preventDefault();
            //     form.addClass("was-validated");

            //     // Envia o formulário para o servidor
            //     form.submit();
            //     } else {
            //     form.addClass("was-validated");
            //     }
            // });
          
            setTimeout(function() {
                $(".timeline").css("height", 'auto');
                $(".timeline").css("opacity", '1');
                $(".spinner-border").css("display", 'none');
                
            }, 800);

            let scrollDiv = $(".simplebar-content-wrapper");
            let h = $('.activity').prop('scrollHeight')
            scrollDiv.animate({scrollTop: h});

            $('select[name="tipo"]').on('change', function() {
                var selectedValue = $(this).val();
                if (selectedValue == 'alteracao') {
                $('#sugeridoComment').fadeIn().prop('required', true);
                } else {
                $('#sugeridoComment').fadeOut().prop('required', false);
                }
            });

            if(localStorage.getItem('formSubmitted')) {
                $('#pautasLink').addClass('active').attr('aria-selected', 'true'); 
                $('#projects').removeClass('active'); 
                $('#projectLink').removeClass('active').attr('aria-selected', 'false'); 
                $('#anexos').removeClass('active'); 
                $('#pautas').addClass('active'); 
                
                localStorage.removeItem('formSubmitted'); 
            }
            
            $('.activePauta').on('click', function(){
                localStorage.setItem('formSubmitted', 'true');
            });

            function validarDataInicial(input) {
                $('#jobs').text('').css('display','none');
                input.on('change', function() {
                    var value = input.val();
                    var selectedDate = new Date(value);
                    var currentDate = new Date();
                    var day = selectedDate.getDay();
                    currentDate.setHours('');
                    // Verificar se a data selecionada é um fim de semana (sábado ou domingo)
                    if (day === 0 || day === 6) {
                    input.val('');
                    Swal.fire({
                        icon: 'warning',
                        title: 'Data inválida',
                        text: 'Favor, selecione uma data em um dia útil.',
                    });
                    } else if (selectedDate < currentDate) {
                    input.val('');
                    Swal.fire({
                        icon: 'warning',
                        title: 'Data inválida',
                        text: 'Favor, selecione uma data posterior ou igual à data atual.',
                    });
                    }
                });
            }

            $('.sugerido').each(function() {
                var input = $(this);
                validarDataInicial(input);
                input.on('focus', function() {
                    validarDataInicial(input);
                });
            });    

            var inputSugeridoComment = $('#sugeridoComment');
            var inputSugeridoAg = $('#sugeridoAg')
            validarDataInicial(inputSugeridoComment);
            validarDataInicial(inputSugeridoAg);

        });
       

    </script>
@endsection

