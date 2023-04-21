@extends('layouts.admin')
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
                                                                <th>Título</th>
                                                                <th>Prioridade</th>
                                                                <th>Status</th>
                                                                <th>Prazo inicial</th>
                                                                <th>Prazo de entrega</th>
                                                                <th>Marca</th>
                                                                <th>Agência</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($demandas as $key => $demanda )
                                                                <tr>
                                                                    <td class="title">
                                                                        {{ $demanda->titulo }}
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
                                                                            <span class="statusBadge" style="margin: 0px; background-color: #b3e5ff">PAUSADO</span> 
                                                                        @elseif($demanda->finalizada == 1)
                                                                            <span style="background-color: #3dbb3d" class="statusBadge" style="margin: 0px">FINALIZADO</span> 
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
<script>
</script>
@endsection

