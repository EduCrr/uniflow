@extends('layouts.admin')
@section('title', 'Meus jobs')

@section('css')
@endsection

@section('content')

    <section>
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-xl-3">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">Jobs estatísticas</h5>
                                    <div class="col-sm-12 col-sm-offset-3 text-center">
                                        <div id="pie-chart" ></div>
                                    </div>
                                </div>
                            </div>
                            <!-- end card -->
                        </div>
                        <div class="col-xl-9">
                            <div class="card">
                                <div class="card-body">
                                    <span class="float-end text-muted font-size-13"> <script>document.write(new Date().getFullYear())</script></span
                                    >
                                    <h5 class="card-title mb-3">Jobs criados</h5>
                                    <div class="col-sm-12 text-center">
                                      <div id="chart"></div>
                                    </div>
                                </div>
                            </div>
                            <!-- end card -->
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="card tableHome">
                                <div class="card-body">
                                    <div class="changeDem">
                                        <h5 class="card-title">Jobs recentes</h5>
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
                                                                <th>Prioridade</th>
                                                                <th>Título</th>
                                                                <th>Status</th>
                                                                <th>Prazo inicial</th>
                                                                <th>Prazo de entrega</th>
                                                                <th>Marca(s)</th>
                                                                <th>Progresso</th>
                                                                <th>Agencia</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($demandas as $demanda )
                                                                @if ($demanda['agencia'])
                                                                    <tr class="trLink" style="cursor: pointer;" data-href="{{route('Admin.job', ['id' => $demanda->id])}}">
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
                                                                        <td class="title">{{ $demanda->titulo }} </td>
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
                                                                            <div style="max-width: 130px;">
                                                                                <small class="float-end ms-2 font-size-12 numberProgress">{{$demanda->porcentagem}}%</small>
                                                                                <div class="progress mt-2" style="height: 5px">
                                                                                    <div
                                                                                    class="progress-bar bg-primary"
                                                                                    role="progressbar"
                                                                                    style="width: {{$demanda->porcentagem}}%"
                                                                                    aria-valuenow="{{$demanda->porcentagem}}"
                                                                                    aria-valuemin="0"
                                                                                    aria-valuemax="100"
                                                                                    >
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            {{ $demanda['agencia']->nome }} 
                                                                        </td>
                                                                    </tr>
                                                                @endif
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
                                    <a href="{{route('Admin.jobs')}}" class="text-primary btnHome">Ver todos <i class="mdi mdi-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                        <!-- end col -->
                        
                    </div>
                     <div class="row">
                        <div class="col-xl-3">
                            <div class="card tableHome">
                                <div class="card-body">
                                    <div class="links">
                                        <div class="linksName"><span class="fa fa-plus me-1"></span> <p>Adidiconar Agência</p></div>
                                        <a href="{{ route('Admin.agencias') }}" class="btn btn-primary">Veja mais</a>
                                    </div>
                                    <div class="links">
                                        <div class="linksName"><span class="fa fa-plus me-1"></span> <p>Adidiconar marca</p></div>
                                        <a href="{{ route('Admin.marcas') }}" class="btn btn-primary">Veja mais</a>
                                    </div>
                                     <div class="links">
                                        <div class="linksName"><span class="fa fa-plus me-1"></span> <p>Adidiconar usuário</p></div>
                                        <a href="{{ route('Admin.usuarios') }}" class="btn btn-primary">Veja mais</a>
                                    </div>
                                     <div class="links">
                                        <div class="linksName"><span class="fas fa-user-edit me-1"></span> <p>Meu perfil</p></div>
                                        <a href="{{ route('Usuario') }}" class="btn btn-primary">Veja mais</a>
                                    </div>
                                     <div class="links">
                                        <div class="linksName"><span class="fas fa-book me-1"></span> <p>Jobs</p></div>
                                        <a href="{{ route('Admin.jobs') }}" class="btn btn-primary">Veja mais</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end col -->
                        <div class="col-xl-9">
                            <div class="card card-adjust">
                                <div class="card-body">
                                    <h5 class="card-title mb-4">Acessos na plataforma</h5>
                                     <div class="col-sm-12 col-sm-offset-3 text-center">
                                       <div id="acessos"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end col -->
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

    //jeito 1
    // let dataBar = [];

    // let teste = @json($jobsPerMonths);

    // for(let i = 0; i < teste.length; i++){
    //     dataBar.push({
    //         month: `${teste[i].month}`,
    //         jobs: `${teste[i].jobs}`,
    //     });
    // }

    //jeito 2
    
    let chart = new Morris.Bar({
        element: 'chart',
        
        data: [
            @foreach ($jobsPerMonths as $job)
                { month: '{{ $job['month'] }}', jobs: {{ $job['jobs'] }} },
            @endforeach
        ],
        xkey: 'month',
        ykeys: ['jobs'],
        labels: ['Jobs Criados'],
        hideHover: 'auto',
        barColors: ['#0acf97'],
        resize: true
    });

        
    let  emPautaCount = @json($emPautaCount);
    let finalizadosCount = @json($finalizadosCount);
    let pendentesCount = @json($pendentesCount);
        
   Morris.Donut({
        element: 'pie-chart',
        data: [
            { label: "Em pauta", value: emPautaCount },
            { label: "Finalizados", value: finalizadosCount },
            { label: "Pendentes", value: pendentesCount }
        ],
        colors: ['#34495E', '#0acf97', '#e6edf3'],
    });

    const monthNames = ["","Jan", "Fev", "Mar", "Abr", "Mai", "Jun",
        "Jul", "Ago", "Set", "Out", "Nov", "Dez"
    ];


    var data = [];
    @for ($month = 1; $month <= 12; $month++)
        data.push({ y: {{ $month }}, a: {{ $logsCountByMonth[$month] }} });
    @endfor
    

    Morris.Area({
        element: 'acessos',
        data: data,
        xkey: 'y',
        parseTime: false,
        ykeys: ['a'],
        xLabelFormat: function (x) {
            var index = parseInt(x.src.y);
            return monthNames[index];
        },
        xLabels: "month",
        labels: ['Acessos'],
        lineColors: ['#a0d0e0', '#0acf97'],
        hideHover: 'auto'

    });
   

</script>

@endsection

