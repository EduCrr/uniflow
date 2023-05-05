@extends('layouts.colaborador')
@section('title', 'Etapa 2')

@section('css')
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
                                        <h5 class="card-title">Etapa 2 que ainda não foram concluídas: ({{count($demandas)}})</h5>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="table-responsive">
                                                {{-- <table class="table table-hover table-centered table-nowrap mb-0">
                                                    @if(count($demandas) === 0)
                                                        <p>Todas as etapas foram concluídas!</p>
                                                        @else
                                                        <thead>
                                                            <tr>
                                                                <th>Título</th>
                                                                <th>Agência</th>
                                                                <th>Marca(s)</th>
                                                                <th>Status</th>
                                                                <th>Prazo inicial</th>
                                                                <th>Prazo sugerido</th>
                                                                <th>Prioridade</th>
                                                                <th>Ações</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($demandas as $key => $demanda )
                                                                @if ($demanda['agencia'])
                                                                <tr>
                                                                    <td class="title">
                                                                        {{ $demanda->titulo }}
                                                                    </td>
                                                                     <td>  
                                                                        {{ $demanda['agencia']->nome }}
                                                                    </td>
                                                                    <td>  
                                                                        @foreach ($demanda['marcas'] as $marca )
                                                                            <span style="padding: 5px; margin-right:5px; border-radius:4px; color:white; background: {{ $marca->cor }}">{{ $marca->nome }}</span>
                                                                        @endforeach
                                                                    </td>
                                                                    <td>
                                                                        @if($demanda->em_pauta == '0' && $demanda->recebido == 1 && $demanda->finalizada == 0 && $demanda->entregue_recebido == 0 && $demanda->entregue == 0 && $demanda->em_alteracao == 0)
                                                                        <span class="statusBadge" style="margin: 0px">RECEBIDO</span>
                                                                        @elseif($demanda->em_pauta == '1')
                                                                        <span class="statusBadge" style="margin: 0px">EM PAUTA</span>
                                                                        @elseif ($demanda->em_pauta == '0' && $demanda->finalizada == '0' && $demanda->entregue == '0')
                                                                        <span class="statusBadge" style="margin: 0px">PENDENTE</span>
                                                                        @elseif($demanda->entregue == '1')
                                                                            <span class="statusBadge" style="margin: 0px">ENTREGUE</span> 
                                                                        @elseif($demanda->finalizada == '1')
                                                                            <span class="statusBadge" style="margin: 0px">FINALIZADO</span> 
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $demanda->inicio)->format('d/m/Y H:i'); }}
                                                                    </td>
                                                                    <td>
                                                                        {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $demanda->final)->format('d/m/Y H:i'); }}
                                                                        @if($demanda->finalizada == 0 && $dataAtual->greaterThan($demanda->final))
                                                                        <i class="mdi mdi-clock-alert alert"></i>
                                                                        @endif
                                                                        @if($demanda->finalizada == 1 && $demanda->atrasada == 1)
                                                                            <i class="mdi mdi-clock-alert alert"></i>
                                                                        @endif
                                                                    </td>
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
                                                                    <td class="actions">
                                                                        <a  href="{{route('Job.deletar_etapa_1', ['id' => $demanda->id])}}" class="btn btn-outline-secondary btn-sm edit deleteBt btnDanger" title="Edit">
                                                                            <i class="fas fa-trash"></i>
                                                                        </a>
                                                                        <a href="{{route('Job.criar_etapa_2', ['id' => $demanda->id])}}" class="btn btn-outline-secondary btn-sm edit btnEdit" title="Editar">
                                                                            <i class="fas fa-edit"></i>
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                                @endif
                                                            @endforeach
                                                        </tbody>
                                                    @endif
                                                </table> --}}
                                                <table class="table table-hover table-centered table-nowrap mb-0">
                                                    @if(count($demandas) === 0)
                                                        <p>Nenhum job foi encontrado!</p>
                                                        @else
                                                        <thead>
                                                            <tr>
                                                                <th>Ações</th>
                                                                <th>Prioridade</th>
                                                                <th>Título</th>
                                                                <th>Status</th>
                                                                <th>Prazo inicial</th>
                                                                <th>Prazo de entrega</th>
                                                                <th>Marca</th>
                                                                <th>Agência</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($demandas as $key => $demanda )
                                                                @if ($demanda['agencia'])
                                                                <tr class="trLink" style="cursor: pointer;" data-href="{{route('Job.criar_etapa_2', ['id' => $demanda->id])}}">
                                                                    <td class="actions">
                                                                        <a  href="{{route('Job.deletar_etapa_1', ['id' => $demanda->id])}}" class="btn btn-outline-secondary btn-sm edit deleteBt btnDanger" style="background-color: #5e5e5e" title="Deletar">
                                                                            <i class="fas fa-trash"></i>
                                                                        </a>
                                                                    </td>
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
                                                                    <td class="title">
                                                                        {{ $demanda->titulo }}
                                                                    </td>
                                                                    <td>
                                                                        <span style="background-color: #ffb887" class="statusBadge" style="margin: 0px">PENDENTE</span>
                                                                    </td>
                                                                    <td>
                                                                        {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $demanda->inicio)->format('d/m/Y H:i'); }}
                                                                    </td>
                                                                    <td>
                                                                        {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $demanda->final)->format('d/m/Y H:i'); }}
                                                                        @if($demanda->finalizada == 0 && $dataAtual->greaterThan($demanda->final))
                                                                        <i class="mdi mdi-clock-alert alert"></i>
                                                                        @endif
                                                                        @if($demanda->finalizada == 1 && $demanda->atrasada == 1)
                                                                            <i class="mdi mdi-clock-alert alert"></i>
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
                                                                    <td>
                                                                        @if($demanda->count_questionamentos > 0 )
                                                                            <span>
                                                                                <i class="fas fa-comment-dots msg"></i>
                                                                            </span>
                                                                        @endif
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
                            </div>
                        </div>
                        <!-- end col -->
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
   
</script>
@endsection

