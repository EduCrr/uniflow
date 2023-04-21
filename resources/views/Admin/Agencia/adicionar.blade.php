@extends('layouts.admin')
@section('title', 'Adicionar agência')

@section('css')
    {{-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /> --}}
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
                                        <h5 class="card-title">Adicione uma agência</h5>
                                        <a href="{{ route('Admin.agencias') }}" class="btnBack btn btn-primary">Voltar</a>
                                    </div>
                                    <form style="margin-top: 15px" method="POST" action="{{route('Admin.agencia_adicionar')}}" enctype="multipart/form-data" class="needs-validation" novalidate>
                                        @csrf
                                        <div class="mb-3 row">
                                            <div class="col-lg-12  mo-b-15">
                                               <label for="inputT" class="form-label pt-0">Nome da Agência</label>
                                               <input name="nome" required value="{{ old('nome') }}" class="form-control" type="text"  id="inputT">
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
                                        <button type="submit" class="btn btn-primary w-lg leftAuto">Criar agência</button>
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

            const form = $(".needs-validation");
            const submitButton = $(".leftAuto");

            submitButton.click(function(event) {
                if (form[0].checkValidity()) {
                submitButton.prop("disabled", true).text('Enviando...');
                event.preventDefault();
                form.addClass("was-validated");

                // Envia o formulário para o servidor
                form.submit();
                } else {
                form.addClass("was-validated");
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
