@extends('layouts.app')

@section('content')
<input type="hidden" id="modulo" value="admonmesaslist" />
<!-- <input type="hidden" id="formAjax" value="1" /> -->
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Lista de mesas
                </div>
                <div class="card-body">
                    <div class="from-group row">
                        <div class="col-lg-12 text-right" style="margin-bottom:15px;">
                            <a class="btn btn-warning" style="color:#FFFFFF;" href="{{ route('home') }}">Regresar</a>
                            <button class="btn btn-success mov-mesas" data-movimiento="alta" id="altaMesas">Alta</button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered" id="listamesas-table" name = "listamesas-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nombre de la mesa</th>
                                    <th>Editar</th>
                                    <th>Eliminar</th>
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
    var table = $('#listamesas-table').DataTable({
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
        ajax: '{!! route("getmesas") !!}',
        columns: [
            {data: 'id', name: 'id'},  
            {data: 'cat_op_descripcion', name: 'cat_op_descripcion'}, 
            {
                render: function (data,type,row){
                    var html = '';
                    html = '<div class="row">'+
                            '<div class="col-md-12">'+
                                '<button class="btn btn-primary btn-block mov-mesas" id="editmesa" name="editmesa" data-movimiento="editar" data-namemesa="'+row.cat_op_descripcion+'" data-idmesa="'+row.id+'">Editar</button>'+
                            '</div>'+
                           '</div>';

                    return html;
                }
            },
            {
                render: function (data,type,row){
                    var html = '';
                    html = '<div class="row">'+
                            '<div class="col-md-12">'+
                                '<button class="btn btn-danger btn-block btn-baja" id="bajamesa" name="bajamesa" data-idmesa="'+row.id+'">Eliminar</button>'+
                            '</div>'+
                           '</div>';

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
    $(document).on("click", "#bajamesa", function(){
        var id = $(this).attr("data-idmesa");
            swal({
                title: '¿Esta seguro de elimar la mesa de ayuda?',
                text: 'Una vez realizada esta operación no podra revertirla',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar'
            }).then((result) => {  
                if (result.value) {
                    // eliminar(id);
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        type: 'POST',
                        data: {id: id},
                        url: '{{ route("deletemesa") }}',
                        async: false,
                        beforeSend: function(){

                        },
                        complete: function(){

                        }
                    }).done(function(response){
                        if(response == 1) {
                            table.ajax.reload();
                            swal(
                                'Eliminacion',
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
    $(document).on("click", ".mov-mesas", function(){
        var mov = $(this).attr("data-movimiento");
        if (mov=="editar") {
            var id = $(this).attr("data-idmesa");
            var mesa = $(this).attr("data-namemesa");
            var titulo="Editar mesa de ayuda";
            var cuerpo = '<div class="container" style="margin-top: 10px;">'+
                    '<form method="post" action="">'+
                    '<div class="form-group row">'+
                        '<div class="col-md-12">'+
                            '<label for="nombreMesa" class="col-lg-12 col-form-label text-left txt-bold">Nombre de la mesa de ayuda</label>'+
                            '<input type="text" class="form-control" name="nombreMesa" id="nombreMesa" value="'+mesa+'"/>'+
                            '<span id="errmsj_mesa" class="error-msj" role="alert">'+
                                '<strong>Favor de ingresar un nombre de mesa de ayuda</strong>'+
                            '</span>'+
                        '</div>'+
                    '</div>'+
                    '<div class="form-group row">'+
                        '<div class="col-md-6">'+
                            '<a href="#" onclick="swal.closeModal(); return false;" class="btn btn-danger btn-block">Cancelar</a>&nbsp;&nbsp;'+
                            '</div>'+
                        '<div class="col-md-6">'+
                            '<input class="btn btn-primary btn-block" data-movimiento="editar" data-idmesa="'+id+'" id="guardar" type="button" value="Guardar">'+
                        '</div>'+
                    '</div>'+
                    '</form>'+
                '</div>';
        }else{
            var titulo="Alta de mesa de ayuda";
            var cuerpo = '<div class="container" style="margin-top: 10px;">'+
                    '<form method="post" action="">'+
                    '<div class="form-group row">'+
                        '<div class="col-md-12">'+
                            '<label for="nombreMesa" class="col-lg-12 col-form-label text-left txt-bold">Nombre de la mesa de ayuda</label>'+
                            '<input type="text" class="form-control" name="nombreMesa" id="nombreMesa"/>'+
                            '<span id="errmsj_mesa" class="error-msj" role="alert">'+
                                '<strong>Favor de ingresar un nombre de mesa de ayuda</strong>'+
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

        var nombremesa = $("#nombreMesa").val();
        var mov = $(this).attr("data-movimiento");
        if (nombremesa == '') {
            mostrarError("errmsj_mesa");
        }else{
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            if (mov == "editar") {
                var tittle = 'Edición';
                var id = $(this).attr("data-idmesa");
                var ajax = $.ajax({
                    type: 'POST',
                    data: {nombre: nombremesa, id:id},
                    url: '{{ route("editmesa") }}',
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
                    data: {nombre: nombremesa},
                    url: '{{ route("newmesa") }}',
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