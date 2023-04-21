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
                @if ($loggedUser->tipo == 'colaborador')
                <th>Agencia</th>
                @endif
                <th>Marca(s)</th>
                @if ($loggedUser->tipo == 'colaborador')
                <th>Editar</th>
                @endif
                
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
                        <i class="mdi mdi-clock-alert alert"></i>
                        @endif
                        @if($demanda->finalizada == 1 && $demanda->atrasada == 1)
                            <i class="mdi mdi-clock-alert alert"></i>
                        @endif
                    </td>
                    @if ($loggedUser->tipo == 'colaborador')
                        <td>
                            {{ $demanda['agencia']->nome }}
                        </td>
                    @endif
                    <td>  
                    @foreach ($demanda['marcas'] as $marca )
                        <span>{{ $marca->nome }}</span>
                    @endforeach
                    </td>
                    <td>
                        @if ($loggedUser->id === $demanda->criador_id)
                            <a href="{{route('Job.editar', ['id' => $demanda->id])}}" class="btn btn-outline-secondary btn-sm edit" title="Editar" style="background-color: #a1a1a1">
                                <i class="fas fa-edit"></i>
                            </a>
                        @endif
                        {{-- <a href="{{route('Job', ['id' => $demanda->id])}}" class="btn btn-outline-secondary btn-sm edit btnJob" title="Acessar">
                            <i class="fas fa-info-circle"></i>
                        </a> --}}
                    </td>
                    <td>
                        @if($demanda->count_questionamentos > 0 )
                            <span>
                                <i class="fas fa-comment-dots msg"></i>
                            </span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    @endif
</table>
<script>
    $(document).ready(function () {
    
    $('tr').click(function() {
        var link = $(this).attr("data-href"); // get the link of the clicked row
        window.location.href = link; // redirect to the URL with the ID appended
    });

    $('.btnDanger').click(function(event) {
        event.stopPropagation(); // impede que o evento de clique na tr seja propagado para os elementos filhos
    });
});
</script>