@extends('layouts.app')

@section('content')
<!-- <input type="hidden" id="modulo" value="home" /> -->
<!-- <div class="container-fluid">
    <div class="row">
        <div class="col-lg-12" style="margin-bottom:15px; color:white;">
            <p class="bg-info">Bienvenido {{ Auth::user()->name }}!</p>
        </div>
    </div>
</div> -->
<div class="container">
    <!-- <div class="row">
        <div class="col-lg-12" style="margin-bottom:15px;">
            <p class="bg-info" id="bnv-home">Bienvenido </p>
        </div>
    </div> -->
    <div class="row justify-content-center" style="margin-bottom:25px;">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Módulos de la aplicación</div>

                <div class="card-body">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-6 cuadros-mnu">
                                <div class="text-center action-mnu" data-url="{{ route('reporteseguimiento') }}">
                                    Reporte auditoria
                                </div>
                            </div>
                            <div class="col-lg-6 cuadros-mnu">
                                <div class="text-center action-mnu" data-url="{{ route('ListaUsuarios') }}">
                                    Administrador de usuarios
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 cuadros-mnu">
                                <div class="text-center action-mnu" data-url="{{ route('listaperfiles') }}">
                                    Administración de perfiles
                                </div>
                            </div>
                            <div class="col-lg-6 cuadros-mnu">
                                <div class="text-center action-mnu" data-url="{{ route('listaareas') }}">
                                    Administración de áreas
                                </div>
                            </div> 
                        </diV>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    $(document).ready(function(){
        $('.action-mnu').click(function(){
            var href = $(this).attr('data-url');
            
            window.location.href = href;
        });

        @if(session('msjError'))
            swal({
                type: 'warning',
                title: 'Advertencia',
                text: '{{ session("msjError") }}'
            });
        @endif

        @if(isset($msjError))
            swal({
                type: 'warning',
                title: 'Advertencia',
                text: '{{ $msjError }}'
            });
        @endif
    });
</script>
@endpush