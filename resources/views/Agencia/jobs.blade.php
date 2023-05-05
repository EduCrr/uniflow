@php
    $layout = $isAdminAg > 0 ? 'layouts.agencia' : 'layouts.colaborador';
@endphp

@extends($layout)

@section('title', 'Meus jobs')

@section('css')
<link href="{{ asset('assets/css/daterangepicker.css') }}" rel="stylesheet" type="text/css" />
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
                                    <div class="initalResume">
                                        <h5>Filtro de pesquisa</h5>
                                    </div>
                                    <div class="general-label">
                                        <form class="row row-cols-lg-auto g-3 align-items-center" method="GET">
                                            
                                            <div class="mb-0 adjustSelects" >
                                                <input type="text" name="search" value="{{ $search }}" class="form-control" placeholder="Pesquisar">
                                            </div>
                                        
                                            <div class="mb-0 adjustSelects" >
                                                <input class="form-control filter-daterangepicker"  placeholder="Intervalo de datas" type="text" name="dateRange" value="{{ $dateRange ? $dateRange : '' }}">
                                            </div>
                                        
                                            <div class="mb-0 adjustSelects" >
                                                <select  class="form-select select2" name="category_id">
                                                    <option @if($priority === '0') selected @endif value="0">Prioridade (todas)</option>
                                                    <option @if($priority === '1') selected @endif  value="1">Baixa</option>
                                                    <option @if($priority === '5') selected @endif  value="5">Média</option>
                                                    <option @if($priority === '7') selected @endif  value="7">Alta</option>
                                                    <option @if($priority === '10') selected @endif  value="10">Urgente</option>
                                                    </select>
                                            </div>

                                            <div class="mb-0 adjustSelects" >
                                                <select class="form-select select2" name="aprovada">
                                                    <option value="">Status</option>
                                                    <option  @if($aprovada === 'pendentes') selected @endif value="pendentes">Pendentes</option>
                                                    <option  @if($aprovada === 'recebidos') selected @endif value="recebidos">Recebidos</option>
                                                    <option  @if($aprovada === 'em_pauta') selected @endif value="em_pauta">Em pauta</option>
                                                    <option  @if($aprovada === 'entregue') selected @endif value="entregue">Entregue</option>
                                                    <option  @if($aprovada === 'pausados') selected @endif value="pausados">Congelados</option>
                                                    <option  @if($aprovada === 'finalizados') selected @endif value="finalizados">Finalizados</option>
                                                </select>
                                            </div>
                                        
                                            <div class="mb-0 adjustSelects" >
                                                <select class="form-select select2" name="marca_id">
                                                    <option selected="true" value="0">Todas as marcas</option>
                                                    @foreach ($brands as $brand )
                                                            <option @if($marca == $brand->id) selected @endif value="{{ $brand->id }}">{{ $brand->nome }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        
                                            <div class="mb-0 adjustSelects" >
                                                <select class="form-select select2" name="in_tyme">
                                                    <option @if(!$inTime) selected @endif value="">Selecionar prazos</option>
                                                    <option @if($inTime === '1') selected @endif value="1">Finalizadas em atraso</option>
                                                    <option  @if($inTime === '0') selected @endif  value="0">Finalizadas no prazo</option>
                                                    <option  @if($inTime === '2') selected @endif  value="2">Atrasadas</option>
                                                </select>
                                            </div>
                                        
                                            <div class="mb-0 adjustSelects" >
                                                <a href="{{route('Agencia.Jobs')}}" class="btn btn-danger ">Limpar</a>
                                                <button type="submit" class="btn btn-primary ">Pesquisar</button>
                                            </div>
                                            
                                            <div class="btnCreate">
                                                <a href="{{route('Agencia.criar')}}" class="btn ">Criar job</a>
                                            </div>
                                        </form>
                                      
                                        <!-- end form -->
                                    </div>  
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-12">
                            <div class="card tableHome">
                                <div class="card-body">
                                    <div class="changeDem">
                                        <h5 class="card-title">Meus Jobs Criados</h5>
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
                                                                <th>Ações</th>
                                                                <th>Prioridade</th>
                                                                <th>Título</th>
                                                                <th>Status</th>
                                                                <th>Prazo inicial</th>
                                                                <th>Prazo de entrega</th>
                                                                <th>Usuario(s)</th>
                                                                <th>Marca(s)</th>
                                                                
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($demandas as $key => $demanda )
                                                                @if ($demanda['agencia'])
                                                                <tr class="trLink" style="cursor: pointer;" data-href="{{route('Job', ['id' => $demanda->id])}}">
                                                                    <td class="actions">
                                                                        <a href="{{route('Agencia.copiar', ['id' => $demanda->id])}}" class="btn btn-outline-secondary btn-sm edit" style="background-color: #a1a1a1" title="Copiar">
                                                                            <i class="fas fa-copy"></i>
                                                                        </a>
                                                                        <a href="{{route('Agencia.editar', ['id' => $demanda->id])}}" class="btn btn-outline-secondary btn-sm edit" style="background-color: #a1a1a1" title="Editar">
                                                                            <i class="fas fa-edit"></i>
                                                                        </a>
                                                                        <a href="{{route('Agencia.delete', ['id' => $demanda->id])}}" class="btn btn-outline-secondary btn-sm edit deleteBt btnDanger" style="background-color: #a1a1a1" title="Deletar">
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
                                                                    <td>
                                                                        {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $demanda->inicio)->format('d/m/Y H:i'); }}
                                                                    </td>
                                                                    <td>
                                                                        {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $demanda->final)->format('d/m/Y H:i'); }}
                                                                        @if($demanda->finalizada == 0 && $dataAtual->greaterThan($demanda->final))
                                                                            <span class="atrasado">ATRASADO!</span>
                                                                        @endif
                                                                        @if($demanda->finalizada == 1 && $demanda->atrasada == 1)
                                                                            <span class="atrasado">ATRASADO!</span>
                                                                        @endif
                                                                    </td>
                                                                    @if($isAdminAg == 0)
                                                                        <td>  
                                                                            {{ $demanda['agencia']->nome }}
                                                                        </td>
                                                                        @else
                                                                        <td>  
                                                                            @foreach ($demanda['demandasUsuario'] as $marca )
                                                                                <span>{{ $marca->nome }}</span>
                                                                            @endforeach
                                                                        </td>
                                                                    @endif
                                                                    <td>  
                                                                        @foreach ($demanda['marcas'] as $marca )
                                                                            <span>{{ $marca->nome }}</span>
                                                                        @endforeach
                                                                    </td>
                                                                   
                                                                    <td>
                                                                        @if($demanda->count_questionamentos > 0 )
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
    {{-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> --}}
    <script src="{{ asset('assets/js/select2.js') }}" ></script>
    {{-- <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment-with-locales.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script> --}}
    <script src="{{ asset('assets/js/momentlocale.js') }}" ></script>
    <script src="{{ asset('assets/js/daterangepicker.js') }}" ></script>

<script>
   
   $(document).ready(function() {
        
        let dateRange = @json($dateRange); 

        if (dateRange) {
            // Se a variável $dateRange possuir um valor válido
            $(".filter-daterangepicker").val(dateRange); // Define o valor do input usando jQuery
        }else{
            $(".filter-daterangepicker").val(''); // Define o valor do input usando jQuery

        }
       
    });
    

</script>
@endsection

