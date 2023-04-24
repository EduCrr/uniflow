@extends('layouts.agencia')
@section('title', 'Meus jobs')

@section('css')
    <link href="{{ asset('assets/css/calendar.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')

    <section>
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="card tableHome">
                                <div class="card-body">
                                   <div class="changeDem">
                                        <h5 class="card-title">Jobs recentes</h5>
                                        {{-- <div class="formSelectDate">
                                            <select class="form-select select2" name="category_id_ag">
                                                <option value="">Selecionar status</option>
                                                <option value="em_pauta">Em pauta </option>
                                                <option value="pendentes">Pendentes</option>
                                                <option value="entregue">Entregues</option>
                                                <option value="pausados">Congelados</option>
                                            </select>
                                        </div> --}}
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="table-responsive" id="jobs" >
                                                <table class="table table-hover table-centered table-nowrap mb-0">
                                                    @if(count($demandas) === 0)
                                                        <p>Nenhum job foi encontrado!</p>
                                                        @else
                                                            <thead>
                                                                <tr>
                                                                    <th>Título</th>
                                                                    <th>Prioridade</th>
                                                                    <th>Status</th>
                                                                    <th>Prazo inicial</th>
                                                                    <th>Prazo de entrega</th>
                                                                    <th>Marca(s)</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($demandas as $demanda )
                                                                    <tr class="trLink" style="cursor: pointer;" data-href="{{route('Job', ['id' => $demanda->id])}}">
                                                                        <td class="title">{{ $demanda->titulo }}</td>
                                                                        <td>
                                                                            <span class="badge" style="background-color: {{ $demanda->cor }}">
                                                                                @if($demanda->prioridade === 10)
                                                                                    URGENTE 
                                                                                    @elseif($demanda->prioridade === 5)
                                                                                    MÉDIA 
                                                                                    @elseif($demanda->prioridade === 1)
                                                                                    BAIXA 
                                                                                    @elseif($demanda->prioridade === 7)
                                                                                    ALTA 
                                                                                @endif
                                                                            </span>
                                                                        </td>
                                                                        <td>
                                                                            @if($demanda->em_pauta == 0 && $demanda->recebido == 1 && $demanda->finalizada == 0 && $demanda->entregue_recebido == 0 && $demanda->entregue == 0 && $demanda->em_alteracao == 0 && $demanda->pausado == 0)
                                                                                <span class="statusBadge" style="margin: 0px; background-color: #ffc7a5" style="margin: 0px">RECEBIDO</span>
                                                                            @elseif($demanda->em_pauta == 1 && $demanda->pausado == 0)
                                                                                <span class="statusBadge" style="margin: 0px; background-color: #ffa76d">EM PAUTA</span>
                                                                            @elseif ($demanda->em_pauta == 0 && $demanda->finalizada == 0 && $demanda->entregue == '0' && $demanda->pausado == 0)
                                                                                <span style="background-color: #ffb887" class="statusBadge" style="margin: 0px">PENDENTE</span>
                                                                            @elseif($demanda->entregue == 1  && $demanda->pausado == 0)
                                                                                <span style="background-color: #ff9652"  class="statusBadge" style="margin: 0px">ENTREGUE</span> 
                                                                            @elseif($demanda->pausado == 1)
                                                                                <span class="statusBadge" style="margin: 0px; background-color: #ffd5bf">CONGELADO</span> 
                                                                            @elseif($demanda->finalizada == 1)
                                                                                <span style="background-color: #ff8538" class="statusBadge" style="margin: 0px">FINALIZADO</span> 
                                                                            @endif
                                                                        </td>
                                                                        <td>{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $demanda->inicio)->format('d/m/Y H:i'); }}</td>
                                                                        <td>
                                                                            {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $demanda->final)->format('d/m/Y H:i'); }}
                                                                            
                                                                            @if($demanda->finalizada == 0 && $dataAtual->greaterThan($demanda->final))
                                                                                <span class="atrasado">ATRASADO!</span>
                                                                            @endif
                                                                            @if($demanda->finalizada == 1 && $demanda->atrasada == 1)
                                                                                <span class="atrasado">ATRASADO!</span>
                                                                            @endif
                                                                        </td>
                                                                        <td>  
                                                                            @foreach ($demanda['marcas'] as $marca )
                                                                                <span>{{ $marca->nome }}</span>
                                                                            @endforeach
                                                                        </td>
                                                                        <td>
                                                                            @if($demanda->count_questionamentos > 0 || $demanda->count_respostas > 0 )
                                                                                <span>
                                                                                    <i class="fas fa-comment-dots msg"></i>
                                                                                </span>
                                                                            @endif
                                                                            {{-- @if($demanda->count_notificacoes > 0 )
                                                                                <span>
                                                                                    <i class="fas fa-bell msg"></i>
                                                                                </span>
                                                                            @endif --}}
                                                                        </td>
                                                                       {{-- <td>
                                                                            <a href="{{route('Job', ['id' => $demanda->id])}}" class="btn btn-outline-secondary btn-sm edit btnJob" title="Acessar">
                                                                                <i class="fas fa-info-circle"></i>
                                                                            </a>
                                                                        </td> --}}
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                    @endif
                                                </table>
                                            </div>
                                            <!--end table-responsive-->
                                        </div>
                                    </div>
                                </div>
                                <div class="adjustPagination">
                                    <div class="text-primary">
                                        <div>
                                            <ul class="pagination">
                                                @if ($demandas->currentPage() > 1)
                                                    <li class="page-item">
                                                        <a class="page-link" href="{{ $demandas->previousPageUrl() }}" aria-label="Anterior">
                                                            <span aria-hidden="true">&laquo;</span>
                                                        </a>
                                                    </li>
                                                @endif
                                        
                                                @if ($demandas->currentPage() > 3)
                                                    <li class="page-item">
                                                        <a class="page-link" href="{{ $demandas->url(1) }}">1</a>
                                                    </li>
                                                    @if ($demandas->currentPage() > 4)
                                                        <li class="page-item disabled"><span class="page-link">...</span></li>
                                                    @endif
                                                @endif
                                        
                                                @for ($i = max(1, $demandas->currentPage() - 2); $i <= min($demandas->currentPage() + 2, $demandas->lastPage()); $i++)
                                                    <li class="page-item {{ ($demandas->currentPage() == $i) ? 'active' : '' }}">
                                                        <a class="page-link" href="{{ $demandas->url($i) }}">{{ $i }}</a>
                                                    </li>
                                                @endfor
                                        
                                                @if ($demandas->currentPage() < $demandas->lastPage() - 2)
                                                    @if ($demandas->currentPage() < $demandas->lastPage() - 3)
                                                        <li class="page-item disabled"><span class="page-link">...</span></li>
                                                    @endif
                                                    <li class="page-item">
                                                        <a class="page-link" href="{{ $demandas->url($demandas->lastPage()) }}">{{ $demandas->lastPage() }}</a>
                                                    </li>
                                                @endif
                                        
                                                @if ($demandas->currentPage() < $demandas->lastPage())
                                                    <li class="page-item">
                                                        <a class="page-link" href="{{ $demandas->nextPageUrl() }}" aria-label="Próxima">
                                                            <span aria-hidden="true">&raquo;</span>
                                                        </a>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                    <a href="{{route('Pautas')}}" class="text-primary btnHome">Ver todos <i class="mdi mdi-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                        <!-- end col -->
                       
                        <!-- end col -->
                    </div>
                    <div class="row">
                         {{--
                        <div class="col-xl-6">
                            <div class="card card-adjust">
                                <div class="card-body">
                                    <h5 class="card-title mb-4">Últimos comentários</h5>
                                    <div data-simplebar style="max-height: 425px;">
                                        <div class="activity">
                                            @foreach ($quests as $item )
                                                <div class="commentIndex">
                                                    <a href="{{route('Job', ['id' => $item->demanda_id])}}">
                                                        <img src="{{url('/assets/images/users')}}/{{$item['usuario']->avatar }}"  alt="" class="img-activity"> 
                                                        <div class="time-item">
                                                            <div class="item-info ">
                                                                <div class="text-muted float-end font-size-12"> {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item->criado)->format('d/m/Y H:i'); }}</div>
                                                                <h5 style="color: #495057" class="mb-1">{{ $item['usuario']->nome }}</h5>  
                                                                <p class="text-muted font-size-13 text-muted-tiny ">{{ $item->descricao }}</p>
                                                                
                                                            </div>
                                                        </div>  
                                                    
                                                        @foreach ($item['respostas'] as $resposta )
                                                            <img style="margin-left: 18px" src="{{url('/assets/images/users')}}/{{$resposta['usuario']->avatar }}"  alt="" class="img-activity"> 
                                                            <div style="margin-left: 18px" class="time-item">
                                                                <div class="item-info ">
                                                                    <div class="text-muted float-end font-size-12"> {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $resposta->criado)->format('d/m/Y H:i'); }} </div>
                                                                    <h5  style="color: #495057" class="mb-1">{{ $resposta['usuario']->nome }}</h5> 
                                                                    <p class="text-muted font-size-13 text-muted-tiny ">{{ $resposta->conteudo }}</p>
                                                                   
                                                                </div>
                                                            
                                                            </div> 
                                                        @endforeach 
                                                    </a>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="text-center mt-3">
                                       
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end col -->
                        <div class="col-xl-6">
                            <div class="card card-adjust">
                                <div class="card-body cardBodyAdjust">
                                    <h5 class="card-title mb-4">Informações de jobs</h5>
                                        <div class="col-sm-12 col-sm-offset-3 text-center">
                                            <div id="pie-chart" ></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                         --}}
                        <!-- end col -->
                        <div class="col-xl12">
                            <div class="card mb-0">
                                <div class="card-body">
                                    <h5 class="card-title mb-4 ">Jobs em pauta</h5>
                                    <div id="calendar"></div>
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
    <script src="{{ asset('assets/js/jqueryui.js') }}" ></script>
    <script src="{{ asset('assets/js/moment.js') }}" ></script>
    <script src="{{ asset('assets/js/fullcalendar.js') }}" ></script>
    <script src="{{ asset('assets/js/select2.js') }}" ></script>

    <script>

        $(document).ready(function() {
            let demandas = @json($events);
           
            $('#calendar').fullCalendar({
                header:{
                    'left' : 'prev, next, today',
                    'center':  'title',
                    'right': 'month, agendaWeek, agendaDay',
                },
                events: demandas,
                monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
                monthNamesShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Aug', 'Set', 'Out', 'Nov', 'Dez'],
                dayNames: ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'],
                dayNamesShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
                buttonText: {
                    today:    'Hoje',
                    month:    'Mês',
                    week:     'Semana',
                    day:      'Dia'
                },
                displayEventTime : false
            });
            
            $('.text-muted-tiny').each(function(){
                var txt = $(this).text();
                $(this).html(txt);
            });
            
            // let  emPautaCount = @json('$emPautaCount');
            // let finalizadosCount = @json('$finalizadosCount');
            // let pendentesCount = @json('$pendentesCount');
            // let pausadosCount =  @json('$pausadosCount');
            // let entregueCount =  @json('$entregueCount');
            // Morris.Donut({
            //     element: 'pie-chart',
            //     data: [
            //     { label: "Em pauta", value: emPautaCount },
            //     { label: "Finalizados", value: finalizadosCount },
            //     { label: "Pendentes", value: pendentesCount },
            //     { label: "Pausados", value: pausadosCount },
            //     { label: "Entregues", value: entregueCount }
            // ],
            // colors: ['#ff6a30', '#0acf97', '#fb3232', '#b3e5ff', '#44a2d2'],
            // });
        });

    </script>
@endsection


