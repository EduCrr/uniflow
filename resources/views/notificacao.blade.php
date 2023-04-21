@php
    $layout = $loggedUser->tipo == 'agencia' ? 'layouts.agencia' : 'layouts.colaborador';
@endphp

@extends($layout)
@section('title', 'Notificações')

@section('css')
@endsection

@section('content')

    <section>
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="card card-adjust">
                                <div class="card-body">
                                    <h5 class="card-title mb-4">Notificações</h5>
                                    @if(count($notifications) > 0)
                                        <div>
                                            <form action="{{route('Notification.action')}}" method="POST">
                                                @csrf
                                                @if($notificationsCount > 0)
                                                    <div class="selectAll">
                                                        <input type="checkbox" id="selecionar-todos">
                                                        <label for="selecionar-todos">Selecionar todos</label>
                                                    </div>
                                                @endif
                                                @foreach ($notifications as $item )
                                                    <div class="notifyArea">
                                                        @if($item->visualizada == 0)
                                                            <input type="checkbox" name="notificacoes[]" value="{{ $item->id }}">
                                                        @elseif($item->visualizada != 0 && $notificationsCount > 0 )
                                                            <div style="margin-left: 15px"></div>
                                                        @endif
                                                        <a href="{{route('Job', ['id' => $item->demanda_id])}}" class="text-reset notification-item">
                                                            <div class="d-flex align-items-start {{ $item->visualizada == 1 ? 'notifyContent' : '' }}">
                                                                <div class="avatar-xs me-3">
                                                                    <span class="avatar-title bg-primary rounded-circle font-size-16 avatar-notify">
                                                                        @if($item->tipo === 'criada')
                                                                            <i class="mdi mdi-check text-primary"></i>
                                                                        @elseif($item->tipo === 'pauta')
                                                                            <i class="mdi mdi-check text-primary"></i>
                                                                        @elseif($item->tipo === 'entregue')
                                                                            <i class="mdi mdi-check text-primary"></i>
                                                                        @elseif($item->tipo === 'finalizado')
                                                                            <i class="mdi mdi-check text-primary"></i>
                                                                        @elseif($item->tipo === 'reaberto')
                                                                            <i class="mdi mdi-check text-primary"></i>
                                                                        @elseif($item->tipo === 'questionamento')
                                                                            <i class="mdi mdi-alert-outline text-danger"></i>
                                                                        @elseif($item->tipo === 'observacao')
                                                                            <i class="mdi mdi-comment-outline text-info"></i>
                                                                        @elseif($item->tipo === 'alterado')
                                                                            <i class="mdi mdi-comment-outline text-info"></i>
                                                                        @endif
                                                                    </span>
                                                                </div>
                                                                <div class="flex-1">
                                                                    <h6 class="mb-1 font-size-15">Job: {{$item->demanda->titulo}}</h6>
                                                                    <div class="text-muted">
                                                                        <p class="mb-1 font-size-12">{{ $item->conteudo }}</p>
                                                                    </div>
                                                                </div>
                                                                <p class="notifyDate text-center">{{ Carbon\Carbon::parse($item->criado)->diffForHumans()}}</p>
                                                            </div>
                                                        </a>
                                                    </div>
                                                @endforeach
                                               
                                                @if($notificationsCount > 0)
                                                    <button disabled type="submit" class="btn btn-primary w-lg leftAuto" id="submitButtonCreate">Marcar como lido</button>
                                                @endif
                                                <br/>
                                                <div style="margin-right: 0px; display:flex; justify-content: flex-end" class="text-primary btnPage">
                                                    <div>{{$notifications->links("pagination::bootstrap-4")}}</div>
                                                </div>
                                               
                                            </form>
                                        </div>
                                    @else
                                    <h4>Nenhuma notificação foi encontrada!</h4>
                                    @endif
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
 
    <script>

    $(function() {
        $('#selecionar-todos').click(function() {
            $('input[type="checkbox"]').prop('checked', $(this).is(':checked'));
            if ($(this).is(':checked')) {
                $('#submitButtonCreate').prop('disabled', false); //remove
            } else {
                $('#submitButtonCreate').prop('disabled', true); //add
            }
        });

        $('input[type="checkbox"]').not('#selecionar-todos').click(function() {
            if (!$(this).is(':checked')) {
                $('#selecionar-todos').prop('checked', false);
            }
            if ($('input[type="checkbox"]').not('#selecionar-todos').is(':checked')) {
                $('#submitButtonCreate').prop('disabled', false); //remove
            } else { 
                $('#submitButtonCreate').prop('disabled', true); //add
            }
        });
    });

    </script>
@endsection


