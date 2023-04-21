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
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="init">
                                        <h5 class="card-title">Editar</h5>
                                        <a href="{{ route('Admin.agencias') }}" class="btnBack btn btn-primary">Voltar</a>
                                    </div>
                                    <div class="text-center">
                                        <img
                                            src="{{url('/assets/images/agency')}}/{{$agencia->logo }}"
                                            alt=""
                                            class="rounded-circle img-thumbnail avatar-xl"
                                        />
                                    </div>
                                    <form style="margin-top: 15px" method="POST" action="{{route('Admin.agencia_editar_action', ['id' => $agencia->id])}}" enctype="multipart/form-data" class="needs-validation" novalidate>
                                        @csrf
                                        <div class="mb-3 row">
                                            <div class="col-lg-12  mo-b-15">
                                               <label for="inputT" class="form-label pt-0">Nome da Agência</label>
                                               <input required name="nome" value="{{ $agencia->nome }}" class="form-control" type="text" id="inputT">
                                                <div class="invalid-feedback">
                                                    Preencha o campo nome
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <div class="col-lg-12  mo-b-15">
                                               <label for="inputF" class="form-label pt-0">Logo</label>
                                                <input
                                                    id="inputF"
                                                    type="file"
                                                    class="form-control"
                                                    name="logo"
                                                    accept="image/png, image/jpeg, image/jpg" 
                                                />
                                                <p style="margin-top: 15px"><span style="background:#d9ba14; color:white; padding: 6px 3px; border-radius: 4px">TAMANHO!</span> A imagem deve ter no mínimo 128px de largura e 128px de altura.</p>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary w-lg leftAuto">Editar agência</button>
                                    </form>
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

 <script>

        $(document).ready(function() {
            let imgSrc = $('.img-thumbnail').attr('src');
              
            $("#inputF").change(function () {
                const file = this.files[0];
                if (file) {
                    let reader = new FileReader();
                    reader.onload = function (event) {
                        $(".img-thumbnail")
                            .attr("src", event.target.result);
                    };
                    reader.readAsDataURL(file);
                }else if(file === null || file === undefined){
                     $(".img-thumbnail").attr("src", imgSrc);
                }
            });
        });
        (function () {
        'use strict'

        var forms = document.querySelectorAll('.needs-validation')
    
        Array.prototype.slice.call(forms)
            .forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
                }

                form.classList.add('was-validated')
            }, false)
            })
        })()
    </script>
    
@endsection
