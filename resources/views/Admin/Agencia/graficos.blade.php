@extends('layouts.admin')
@section('title', 'Agência '. $agencia->nome )

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
                                    <h5 class="card-title">{{$agencia->nome}}</h5>
                                    <br/>
                                    <p class="mt-1">Média geral de dias para entrega das pautas: <strong>@if ($media >= 2)   {{$media }} dias  @else     {{$media}} dia    @endif</strong></p>
                                    <p class="mt-1">Jobs finalizados: <strong>{{$demandasCount}}</strong></p>
                                    <p class="mt-1">Jobs entregues em atraso: <strong>{{$demandasAtrasadasCount}}</strong></p>
                                    <p class="mt-1">Jobs entregues dentro do prazo: <strong>{{$demandasEmPrazoCount}}</strong></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="initialGraphs">
                                        <h5 class="card-title">Média de dias para a entrega das pautas</h5>
                                        <a href="{{ route('admin.export', ['id' => $agencia->id]) }}" class="btn btn-success">Exportar para Excel</a>
                                    </div>
                                    <div id="chart"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="initialGraphs">
                                        <h5 class="card-title">Jobs criados e finalizados</h5>
                                        <a href="{{ route('admin.export.jobs', ['id' => $agencia->id]) }}" class="btn btn-success">Exportar para Excel</a>
                                    </div>
                                    <div id="graph_bar"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="initialGraphs">
                                        <h5 class="card-title">Jobs entregues com atraso e entregues dentro do prazo.</h5>
                                        <a href="{{ route('admin.export.prazos', ['id' => $agencia->id]) }}" class="btn btn-success">Exportar para Excel</a>
                                    </div>
                                    <div id="graph_bar_prazos"></div>
                                </div>
                            </div>
                        </div>
                       
                        <div class="col-xl-12">
                            <div class="card tableHome">
                                <div class="card-body">
                                    <div class="changeDem">
                                        <h5 class="card-title">Jobs da {{$agencia->nome}}</h5>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="table-responsive">
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
                                                                <th>Prazo sugerido</th>
                                                                <th>Marca(s)</th>
                                                                <th>Agencia</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($demandas as $demanda )
                                                                <tr class="trLink" style="cursor: pointer;" data-href="{{route('Admin.job', ['id' => $demanda->id])}}">
                                                                    <td class="title">{{ $demanda->titulo }} </td>
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
                                                                            <span class="statusBadge" style="margin: 0px">RECEBIDO</span>
                                                                        @elseif($demanda->em_pauta == 1 && $demanda->pausado == 0)
                                                                            <span class="statusBadge" style="margin: 0px; background-color: #ff6a30">EM PAUTA</span>
                                                                        @elseif ($demanda->em_pauta == 0 && $demanda->finalizada == 0 && $demanda->entregue == '0' && $demanda->pausado == 0)
                                                                            <span style="background-color: #fb3232" class="statusBadge" style="margin: 0px">PENDENTE</span>
                                                                        @elseif($demanda->entregue == 1  && $demanda->pausado == 0)
                                                                            <span style="background-color: #44a2d2"  class="statusBadge" style="margin: 0px">ENTREGUE</span> 
                                                                        @elseif($demanda->pausado == 1)
                                                                            <span class="statusBadge" style="margin: 0px; background-color: #b3e5ff">CONGELADO</span> 
                                                                        @elseif($demanda->finalizada == 1)
                                                                            <span style="background-color: #3dbb3d" class="statusBadge" style="margin: 0px">FINALIZADO</span> 
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
                                                                        {{ $demanda['agencia']->nome }} 
                                                                    </td>
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
                                <div class="text-primary btnPage">
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

    $(function() {
        let chart = new Morris.Bar({
            element: 'chart',
            data: [
                @foreach ($mediaMeses as $job)
                    { mes: '{{ htmlspecialchars($job['mes']) }}', dias: {{ $job['dias'] }} },
                @endforeach
            ],
            xkey: 'mes',
            ykeys: ['dias'],
            labels: ['Média de dias'],
            hideHover: 'auto',
            barColors: ['#0acf97'],
            resize: true
        });
        
        Morris.Bar({
            element: 'graph_bar',
            data: <?php echo json_encode($resultadosDemanda); ?>,
            xkey: 'mes',
            ykeys: ['criadas', 'finalizadas'],
            labels: ['Criados', 'Finalizados'],
            hideHover: 'auto',
            resize: true,
            barColors: ['#34495E', '#0acf97'],
        });

        Morris.Bar({
            element: 'graph_bar_prazos',
            data: <?php echo json_encode($resultadosDemandaPrazos); ?>,
            xkey: 'mes',
            ykeys: ['atrasadas', 'prazo'],
            labels: ['Atrasados', 'No prazo'],
            hideHover: 'auto',
            resize: true,
            barColors: ['#e53d1f', '#0acf97'],
        });
    });

 </script>
    
@endsection
