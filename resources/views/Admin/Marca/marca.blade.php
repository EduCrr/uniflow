@extends('layouts.admin')
@section('title', 'Marca '. $marca->nome )

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
                                        <a href="{{ route('Admin.marcas') }}" class="btnBack btn btn-primary">Voltar</a>
                                    </div>
                                    <div class="text-center">
                                        <img
                                            src="{{url('/assets/images/brands')}}/{{$marca->logo }}"
                                            alt=""
                                            class="imgBrand"
                                        />
                                    </div>
                                    <form style="margin-top: 15px" method="POST" action="{{route('Admin.marca_editar_action', ['id' => $marca->id])}}" enctype="multipart/form-data" class="needs-validation" novalidate>
                                        @csrf
                                        <div class="mb-3 row">
                                            <div class="col-lg-12  mo-b-15">
                                               <label for="inputT" class="form-label pt-0">Nome da marca</label>
                                               <input required name="nome" value="{{ $marca->nome }}" class="form-control" type="text" id="inputT">
                                               <div class="invalid-feedback">
                                                    Preencha o campo nome
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <div class="col-lg-12  mo-b-15">
                                              <label for="Color" class="form-label">Cor da marca</label>
                                                <input required type="color" class="form-control form-control-color" id="Color" name="cor" value="{{ $marca->cor }}">
                                                <div class="invalid-feedback">
                                                    Preencha o campo cor
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
                                            </div>
                                        </div>
                                        <br/>
                                        <button type="submit" class="btn btn-primary w-lg leftAuto">Editar marca</button>
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

        $(document).ready(function() {
            let imgSrc = $('.imgBrand').attr('src');
              
            $("#inputF").change(function () {
                const file = this.files[0];
                if (file) {
                    let reader = new FileReader();
                    reader.onload = function (event) {
                        $(".imgBrand")
                            .attr("src", event.target.result);
                    };
                    reader.readAsDataURL(file);
                }else if(file === null || file === undefined){
                     $(".imgBrand").attr("src", imgSrc);
                }
            });
        });
    </script>
    
@endsection
