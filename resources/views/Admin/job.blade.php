{{-- @extends('layouts.admin')
@section('title', 'Job '. $demanda->id)

@section('css')
    
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
                                    <ul class="nav nav-tabs nav-tabs-custom" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active pb-3 pt-0" data-bs-toggle="tab" href="#projects"
                                                role="tab"><i class="fas fa-check-circle me-2"></i>Job</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link pb-3 pt-0" data-bs-toggle="tab" href="#anexos"
                                                role="tab"><i class="fas fa-suitcase me-2"></i>Anexos</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link pb-3 pt-0" data-bs-toggle="tab" href="#pautas"
                                                role="tab"><i class="fas fa-calendar-alt  me-2"></i>Pautas</a>
                                        </li>
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
                                                                <div class="showStatus" style="background-color: #3dbb3d">
                                                                    <p>STATUS: RECEBIDO</p>
                                                                </div>
                                                                @endif

                                                                @if($demanda->em_pauta == '1' && $demanda->pausado == '0')
                                                                    <div class="showStatus" style="background-color: #ff6a30">
                                                                        <p>STATUS: EM PAUTA</p>
                                                                    </div>
                                                                @endif
                                                                
                                                                @if($demanda->entregue == '1' && $demanda->pausado == '0')
                                                                    <div class="showStatus"  style="background-color: #3dbb3d">
                                                                        <p>STATUS: ENTREGUE</p>
                                                                    </div>
                                                                @endif

                                                                @if($demanda->finalizada == '1')
                                                                    <div class="showStatus" style="background-color: #3dbb3d">
                                                                        <p>STATUS: FINALIZADA</p>
                                                                    </div>
                                                                @endif

                                                                @if($demanda->pausado == '1')
                                                                    <div class="showStatus" style="background-color: #cecdcd">
                                                                        <p>STATUS: CONGELADO</p>
                                                                    </div>
                                                                @endif

                                                            </div>

                                                            <div class="initalResume">
                                                                <div class="nameJob">
                                                                    <h5>{{ $demanda->titulo }}</h5>
                                                                </div>
                                                            </div>
                                                            <div class="contenJob">
                                                                <div class="contentJobSingle">
                                                                    <h6>Prazo inicial</h6>
                                                                    <p>{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $demanda->inicio)->format('d/m/Y H:i'); }}</p>
                                                                </div>
                                                                <div class="contentJobSingle">
                                                                    <h6>Prazo sugerido  
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
                                                                    <h6 style="margin-bottom: 15px">Link anexo</h6>
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
                                                                            Job {{$item->status}} @if($item->finalizado != null)  <i class="mdi mdi-check-circle " style="color:#3dbb3d; font-size: 16px;"></i> @elseif (Carbon\Carbon::parse($item->finalizado)->greaterThan(Carbon\Carbon::parse($item->sugerido)))  <i class="mdi mdi-clock-alert alert"></i> @endif
                                                                        </h5>
                                                                        <a class="arounded" data-bs-toggle="collapse" href="#collapse-{{$item->id}}" role="button" aria-expanded="false" aria-controls="collapse-{{$item->id}}">
                                                                            <i style="cursor: pointer" class="fas fa-angle-down"></i>
                                                                        </a>
                                                                    </div>
                                                                    <div class="collapse" id="collapse-{{$item->id}}">
                                                                        <div class="contenJob">
                                                                            <div class="contentJobSingle">
                                                                                <h6>Prazo inicial do job reaberto</h6>
                                                                                <p>{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item->iniciado)->format('d/m/Y H:i'); }}</p>
                                                                            </div>
                                                                            <div class="contentJobSingle">
                                                                                <h6>Novo prazo sugerido</h6>
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
                                                                            </div>
                                                                        </div>
                                                                        @foreach ($item['respostas'] as $resposta  )
                                                                            @if($resposta != null)
                                                                            <img style="margin-left: 18px" alt="" class="img-activity" src="{{url('/assets/images/users/')}}/{{$resposta->usuario->avatar }}">
                                                                            <div style="margin-left: 18px" class="time-item">
                                                                                    <div class="item-info">
                                                                                        <div class="text-muted float-end font-size-10 dateComentary">
                                                                                        {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $resposta->criado)->format('d/m/Y H:i'); }}
                                                                                        <br/>
                                                                                    </div>
                                                                                    <div class="status">
                                                                                        <h5 class="mb-1">{{ $resposta->usuario->nome }} </h5> <span class="answer">Resposta</span>
                                                                                    </div>
                                                                                        <p class="text-muted font-size-13 text-muted-tiny">
                                                                                            {{ $resposta->conteudo }} 
                                                                                        </p>
                                                                                    </div>
                                                                                </div> 
                                                                            @endif
                                                                        @endforeach    
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
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
                                                @if(count($demanda['prazosDaPauta']) > 0)
                                                <div class="col-xl-12">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <p><strong>Agência: {{ $demanda['agencia']->nome }}</strong></p>
                                                            <br/>
                                                            <p class="mt-1">Entregues em atraso: <strong>{{$demandaAtrasadas}}</strong></p>
                                                            <p class="mt-1">Entregues dentro do prazo: <strong>{{$demandaEmPrazo}}</strong></p>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
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
                                                                                    <i class="mdi mdi-clock"  style="color:#3dbb3d; font-size: 16px;"></i>
                                                                                    @else
                                                                                    <i class="mdi mdi-clock-alert alertSingleJob"></i>
                                                                                @endif
                                                                            @endif
                                                                            
                                                                        </h5>
                                                                    </div>
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
                                                                        <h6>Prazo sugerido para término</h6>
                                                                        <span class="borderPautas" style="background: #34495E">{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item->sugerido)->format('d/m/Y H:i'); }}</span>
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
   <script type="text/javascript">
        var briefing = @json($demanda->briefing);
       
		$(".card-title-desc").html(briefing);

        $('.text-muted-tiny').each(function(){
             var txt = $(this).text();
            $(this).html(txt);
        });
        
        $('.nav-item').bind('click', function(){
            $('.carousel').slick('refresh');
        });
       
        $(document).ready(function () {
            $('li.complete:last').css('content', 'none');
          
            setTimeout(function() {
                $(".timeline").css("height", 'auto');
                $(".timeline").css("opacity", '1');
                $(".spinner-border").css("display", 'none');
                
            }, 800);

            let scrollDiv = $(".simplebar-content-wrapper");
            let h = $('.activity').prop('scrollHeight')
            scrollDiv.animate({scrollTop: h});

        });

    </script>
@endsection
 --}}

@extends('layouts.admin')
@section('title', 'Job '. $demanda->id)

@section('css')
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
                                            <a class="nav-link active pb-3 pt-0" data-bs-toggle="tab" href="#projects"
                                                role="tab"><i class="fas fa-check-circle me-2"></i>Job</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link pb-3 pt-0" data-bs-toggle="tab" href="#anexos"
                                                role="tab"><i class="fas fa-suitcase me-2"></i>Anexos</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link pb-3 pt-0" data-bs-toggle="tab" href="#pautas"
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
                                                                    <div class="showStatus" style="background-color: #ffc7a5">
                                                                        <p>STATUS: RECEBIDO</p>
                                                                    </div>
                                                                @endif

                                                                @if($demanda->em_pauta == '1' && $demanda->pausado == '0')
                                                                    <div class="showStatus" style="background-color: #ffa76d">
                                                                        <p>STATUS: EM PAUTA</p>
                                                                    </div>
                                                                @endif
                                                                
                                                                @if($demanda->entregue == '1' && $demanda->pausado == '0')
                                                                    <div class="showStatus"  style="background-color: #ff9652">
                                                                        <p>STATUS: ENTREGUE</p>
                                                                    </div>
                                                                @endif

                                                                @if($demanda->finalizada == '1')
                                                                    <div class="showStatus" style="background-color: #ff8538">
                                                                        <p>STATUS: FINALIZADO</p>
                                                                    </div>
                                                                @endif

                                                                @if($demanda->pausado == '1')
                                                                    <div class="showStatus" style="background-color: #ffd5bf">
                                                                        <p>STATUS: CONGELADO</p>
                                                                    </div>
                                                                @endif

                                                               
                                                            </div>

                                                            <div class="initalResume">
                                                                <div class="nameJob">
                                                                    <h5>{{ $demanda->titulo }}</h5>
                                                                </div>
                                                                
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
                                                                        @foreach ($demanda['demandasUsuario'] as $usuario )
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
                                                                                <h6>Novo prazo sugerido
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
                                                                                    </div>
                                                                                </div> 
                                                                            @endif
                                                                        @endforeach    
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
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
                                                    @if(count($demanda['prazosDaPauta']) > 0)
                                                        <div class="col-xl-12">
                                                            <div class="card">
                                                                <div class="card-body">
                                                                    <p><strong>Agência: {{ $demanda['agencia']->nome }}</strong></p>
                                                                    <br/>
                                                                    <p class="mt-1">Entregues em atraso: <strong>{{$demandaAtrasadas}}</strong></p>
                                                                    <p class="mt-1">Entregues dentro do prazo: <strong>{{$demandaEmPrazo}}</strong></p>
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
                                                                        <h6>Prazo para entrega</h6>
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

