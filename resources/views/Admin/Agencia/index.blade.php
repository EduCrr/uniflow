    @extends('layouts.admin')
    @section('title', 'Agências')

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
                                        <div class="changeDem">
                                            <div>
                                            <h5 class="card-title">Agências</h5>
                                            {{-- <a class="form-control reopenJob fin" href="{{ route('Admin.agencia_adicionar') }}">Adicionar agência</a> --}}
                                            </div>
                                            <div class="general-label">
                                                <form class="row row-cols-lg-auto g-3 align-items-center" method="GET">
                                                    <div class="col-12">
                                                        <div class="mb-0">
                                                            <input type="text" name="search" value="{{ $search }}" class="form-control" placeholder="Pesquisar">
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="mb-0">
                                                            <a href="{{route('Admin.agencias')}}" class="btn btn-danger ">Limpar</a>
                                                            <button type="submit" class="btn btn-primary ">Pesquisar</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="table-responsive" >
                                                    <table class="table table-hover table-centered table-nowrap mb-0">
                                                        @if(count($agencias) === 0)
                                                        <p>Nenhuma agência foi encontrado!</p>
                                                        @else
                                                        <thead>
                                                            <tr>
                                                                <th>Nome</th>
                                                                <th style="display: flex;justify-content: flex-end;">Ações</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($agencias as $agencia )
                                                                <tr class="trLink" style="cursor: pointer;" data-href="{{route('Admin.agencia_editar', ['id' => $agencia->id])}}">
                                                                    <td>  
                                                                        <img alt="" class="avatar-xs rounded-circle me-2" src="{{url('/assets/images/agency')}}/{{$agencia->logo }}">
                                                                        {{ $agencia->nome }}
                                                                    </td>
                                                                    <td style="display: flex;justify-content: flex-end;">
                                                                        <a style="margin-left: 5px" href="{{route('Admin.agencia_graficos', ['id' => $agencia->id])}}" class="btn btn-outline-secondary btn-sm edit btnJob" title="Gráficos">
                                                                            <i class="fas fa-chart-line"></i>
                                                                        </a>
                                                                        <a style="margin-left: 5px; background-color: #a1a1a1" href="{{route('Admin.agencia_delete_action', ['id' => $agencia->id])}}" class="btn btn-outline-secondary btn-sm deleteBt btnDanger" title="Deletar">
                                                                            <i class="fas fa-trash"></i>
                                                                        </a>
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
                                    <div class="text-primary">
                                        <div>{{$agencias->links("pagination::bootstrap-4")}}</div>
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
    @endsection


