    @extends('layouts.admin')
    @section('title', 'Usuários')

    @section('css')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.css" />
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
                                                <h5 class="card-title">Usuários</h5>
                                                {{-- <a class="form-control reopenJob fin" href="{{ route('Admin.usuario_adicionar') }}">Adicionar usuario</a> --}}
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
                                                            <a href="{{route('Admin.usuarios')}}" class="btn btn-danger ">Limpar</a>
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
                                                        @if(count($usuarios) === 0)
                                                        <p>Nenhum usuário foi encontrado!</p>
                                                        @else
                                                        <thead>
                                                            <tr>
                                                                <th>Nome</th>
                                                                <th style="display: flex;justify-content: flex-end;">Ações</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($usuarios as $usuario )
                                                                <tr class="trLink" style="cursor: pointer;" data-href="{{route('Admin.usuario_editar', ['id' => $usuario->id])}}">
                                                                    <td> 
                                                                        <div style="display: flex; align-items: center;"> 
                                                                            <img alt="" class="avatar-xs rounded-circle me-2" src="{{url('/assets/images/users')}}/{{$usuario->avatar }}">
                                                                            <div>
                                                                                {{ $usuario->nome }}<br/>
                                                                                <span style="font-size: 13px; text-transform: capitalize">@if($usuario->tipo === 'agencia') Agência @else {{$usuario->tipo}} @endif</span>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td style="display: flex;justify-content: flex-end;">
                                                                        @if ($loggedUser->id != $usuario->id)
                                                                            {{-- <a style="margin-left: 5px; background-color: #a1a1a1" href="{{route('Admin.usuario_delete_action', ['id' => $usuario->id])}}" class="btn btn-outline-secondary btn-sm deleteBt btnDanger" title="Deletar">
                                                                                <i class="fas fa-trash"></i>
                                                                            </a> --}}
                                                                            <a style="background-color: #a1a1a1" href="{{route('Admin.usuario_delete_action', ['id' => $usuario->id])}}" class="btn btn-outline-secondary btn-sm edit deleteBt btnDanger" style="background-color: #a1a1a1" title="Deletar">
                                                                                <i class="fas fa-trash"></i>
                                                                            </a>
                                                                        @endif
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
                                        <div>{{$usuarios->links("pagination::bootstrap-4")}}</div>
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


