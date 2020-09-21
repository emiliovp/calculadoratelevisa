@extends('layouts.app')

@section('content')
<input type="hidden" id="modulo" value="admonareas" />
<!-- <input type="hidden" id="formAjax" value="1" /> -->
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Lista de áreas
                </div>
                <div class="card-body">
                    <div class="from-group row">
                        <div class="col-lg-12 text-right" style="margin-bottom:15px;">
                            <a class="btn btn-warning" style="color:#FFFFFF;" href="{{ route('home') }}">Regresar</a>
                            <button class="btn btn-success mov-area" data-movimiento="alta" id="altaMesas">Alta</button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered" id="listaareas-table" name = "listamesas-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Área</th>
                                    <th>Editar</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                        </table> 
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
var buttonCommon = {
        exportOptions: {
            columns: [0,1],
            format: {
                body: function (data, row, column, node) {
                    if (column == 2) {
                        return $(data).find("option:selected").text()
                    } else return data
                }
            },
        }
    };
    var table = $('#listaareas-table').DataTable({
        language: {
            url: "{{ asset('json/Spanish.json') }}",
            buttons: {
                copyTitle: 'Tabla copiada',
                copySuccess: {
                    _: '%d líneas copiadas',
                    1: '1 línea copiada'
                }
            }
        },
        processing: true,
        serverSide: true,
        ajax: '{!! route("listaareastabla") !!}',
        columns: [
            {data: 'id', name: 'id'},  
            {data: 'cal_area', name: 'cal_area'}, 
            {
                render: function (data,type,row){
                    var html = '';
                    html = '<div class="row">'+
                            '<div class="col-md-12">'+
                                '<button class="btn btn-primary btn-block mov-area" id="editmesa" name="editmesa" data-movimiento="editar" data-nomarea="'+row.cal_area+'" data-idarea="'+row.id+'">Editar</button>'+
                            '</div>'+
                           '</div>';

                    return html;
                }
            },
            {
                render: function (data,type,row){
                    var html = '';
                    if (row.cal_estado =='Activo') {
                        html = '<div class="row">'+
                                '<div class="col-md-12">'+
                                    '<button class="btn btn-danger btn-block mov-areas" id="bajaarea" name="bajaarea" data-movimiento="2" data-idarea="'+row.id+'">Desactivar</button>'+
                                '</div>'+
                            '</div>';
                    }else{
                        html = '<div class="row">'+
                                '<div class="col-md-12">'+
                                    '<button class="btn btn-primary btn-block mov-areas" id="bajaarea" name="bajaarea" data-movimiento="1" data-idarea="'+row.id+'">Activar</button>'+
                                '</div>'+
                            '</div>';
                    }
                    return html;
                }
            },
        ],
        dom: 'Blfrtip',
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Todo"]],
        buttons: [
            $.extend(true, {}, buttonCommon, {
                extend: "copyHtml5"
            }), 
            $.extend(true, {}, buttonCommon, {
                extend: "csvHtml5"
            }), 
            $.extend(true, {}, buttonCommon, {
                extend: "excelHtml5"
            }), 
            $.extend(true, {}, buttonCommon, {
                extend: "pdfHtml5",
                orientation: "landscape",
                pageSize: "LEGAL"
            })
        ]
    });
    $(document).on("click", ".mov-areas", function(){
        var mov = $(this).attr("data-movimiento");
        if (mov == 2) {
            var accion = 'bloquear';
        }else{
            var accion = 'desbloquear';
        }
        swal({
            title: '¿Esta seguro de '+accion+' el área?',
            text: 'Esta operación se podra revertir',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Aceptar'
        }).then((result) => {  
            if (result.value) {
                $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                });
                if (mov == 2) {
                    var tittle = 'Bloqueo';
                }else{
                    var tittle = 'Desbloqueo';
                }
                /*if (mov == "bloquear") {
                    var tittle = 'Edición';
                    var id = $(this).attr("data-idperfil");
                    var ajax = $.ajax({
                        type: 'POST',
                        data: {id:id},
                        url: ' route("updperfiles") ',
                        async: false,
                        beforeSend: function(){
                            mostrarLoading();
                        },
                        complete: function(){
                            ocultarLoading();
                        }
                    });
                }else{*/
                    var id = $(this).attr("data-idarea");
                    var ajax = $.ajax({
                        type: 'POST',
                        data: {id:id,tipo:mov},
                        url: '{{ route("bloqueoarea") }}',
                        async: false,
                        beforeSend: function(){
                            mostrarLoading();
                        },
                        complete: function(){
                            ocultarLoading();
                        }
                    });
                //}
                ajax.done(function(response){
                    if(response == 1) {
                        table.ajax.reload();
                        swal(
                            tittle,
                            'La operación se ha realizado con éxito',
                            'success'
                        )
                    } else if(response == false) {
                        swal(
                            'Error',
                            'La operación no pudo ser realizada',
                            'error'
                        )
                    }
                });
            }
        });  
    });
    $(document).on("click", ".mov-area", function(){
        var mov = $(this).attr("data-movimiento");
        if (mov=="editar") {
            var id = $(this).attr("data-idarea");
            var nom = $(this).attr("data-nomarea");
            var titulo="Editar área";
            var cuerpo = '<div class="container" style="margin-top: 10px;">'+
                    '<form method="post" action="">'+
                    '<div class="form-group row">'+
                        '<div class="col-md-12">'+
                            '<label for="nombreArea" class="col-lg-12 col-form-label text-left txt-bold">Nombre del área</label>'+
                            '<input type="text" class="form-control" name="nombreArea" value="'+nom+'" id="nombreArea"/>'+
                            '<span id="errmsj_area" class="error-msj" role="alert">'+
                                '<strong>Favor de ingresar un nombre de área</strong>'+
                            '</span>'+
                        '</div>'+
                    '</div>'+
                    '<div class="form-group row">'+
                        '<div class="col-md-6">'+
                            '<a href="#" onclick="swal.closeModal(); return false;" class="btn btn-danger btn-block">Cancelar</a>&nbsp;&nbsp;'+
                            '</div>'+
                        '<div class="col-md-6">'+
                            '<input class="btn btn-primary btn-block" data-movimiento="editar" data-idarea="'+id+'" id="guardar" type="button" value="Guardar">'+
                        '</div>'+
                    '</div>'+
                    '</form>'+
                '</div>';
        }else{
            var titulo="Alta de área";
            var cuerpo = '<div class="container" style="margin-top: 10px;">'+
                    '<form method="post" action="">'+
                    '<div class="form-group row">'+
                        '<div class="col-md-12">'+
                            '<label for="nombreArea" class="col-lg-12 col-form-label text-left txt-bold">Nombre del área</label>'+
                            '<input type="text" class="form-control" name="nombreArea" id="nombreArea"/>'+
                            '<span id="errmsj_area" class="error-msj" role="alert">'+
                                '<strong>Favor de ingresar un nombre de área</strong>'+
                            '</span>'+
                        '</div>'+
                    '</div>'+
                    '<div class="form-group row">'+
                        '<div class="col-md-6">'+
                            '<a href="#" onclick="swal.closeModal(); return false;" class="btn btn-danger btn-block">Cancelar</a>&nbsp;&nbsp;'+
                            '</div>'+
                        '<div class="col-md-6">'+
                            '<input class="btn btn-primary btn-block" data-movimiento="alta" id="guardar" type="button" value="Guardar">'+
                        '</div>'+
                    '</div>'+
                    '</form>'+
                '</div>';
        }
        Swal({
            title: titulo,
            // type: 'info',
            html: cuerpo,
            showCloseButton: true,
            showCancelButton: false,
            showConfirmButton: false,
            focusConfirm: false,
            allowOutsideClick: false,
        });
    });
    $(document).on("click", "#guardar", function(){

        var nombrearea = $("#nombreArea").val();
        
        var mov = $(this).attr("data-movimiento");
        if (nombrearea == '') {
            mostrarError("errmsj_area");
        }else{
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            if (mov == "editar") {
                var tittle = 'Edición';
                var id = $(this).attr("data-idarea");
                var ajax = $.ajax({
                    type: 'POST',
                    data: {nombre: nombrearea, id:id},
                    url: '{{ route("editarea") }}',
                    async: false,
                    beforeSend: function(){
                        mostrarLoading();
                    },
                    complete: function(){
                        ocultarLoading();
                    }
                });
            }else{
                var tittle = 'Alta';
                var ajax = $.ajax({
                    type: 'POST',
                    data: {nombre: nombrearea},
                    url: '{{ route("newarea") }}',
                    async: false,
                    beforeSend: function(){

                    },
                    complete: function(){

                    }
                });
            }
            ajax.done(function(response){
                if(response == 1) {
                    table.ajax.reload();
                    swal(
                        tittle,
                        'La operación se ha realizado con éxito',
                        'success'
                    )
                } else if(response == false) {
                    swal(
                        'Error',
                        'La operación no pudo ser realizada',
                        'error'
                    )
                }
            }); 
        }
    });
</script>
@endpush