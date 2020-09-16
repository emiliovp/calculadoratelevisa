@extends('layouts.app')

@section('content')
<input type="hidden" id="modulo" value="listaoptcatalogo" />
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Lista de Opciones de Catálogos
                </div>
                <div class="card-body">
                    <div class="from-group row">
                        <div class="col-lg-12 text-right" style="margin-bottom:15px;">
                            <a class="btn btn-warning" style="color:#FFFFFF;" href="{{ route('listacatalogos') }}">Regresar</a>
                            <a class="btn btn-success" style="color:#FFFFFF;" href="{{ route('altaopciones', ['id' => $id, 'idapp' => $idapp]) }}">Alta de Opciones</a>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered" id="aplicaciones-table" name = "aplicaciones-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Opción</th>
                                    <th>Estado</th>
                                    <th>Fecha de creación</th>
                                    <th># de Jerarquía</th>
                                    <th>Dependencia con otra opción</th>
                                    <th>Dependencia con autorizaciones</th>
                                    <th>Acciones</th>
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
            columns: [0,1,2,3,4,5],
            format: {
                body: function (data, row, column, node) {
                    // if it is select
                    if (column == 9) {
                        return $(data).find("option:selected").text()
                    } else return data
                }
            },
        }
    };
    var table = $('#aplicaciones-table').DataTable({
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
        ajax: '{!! route("dataIndexOptCat", ["id" => $id]) !!}',
        columns: [
            {data: 'id', name: 'id'},
            {data: 'cat_op_descripcion', name: 'cat_op_descripcion'}, 
            {data: 'estado', name: 'estado'},
            {data: 'created_at', name: 'created_at'}, 
            {data: 'jerarquia', name: 'jerarquia'}, 
            {data: 'cat_opciones_id', name: 'cat_opciones_id'}, 
            {
                render: function (data, type, row) {
                    var html = '';
                    if(row.dependencias > 0) {
                        html = '<div class="row">'+
                                '<div class="col-md-12">'+
                                    '<button class="verdependencias" name="verdependencias" data-id="'+row.id+'">Ver Dependencias</button>'+
                                '</div>'+
                            '</div>';
                    }

                    return html;
                }
            },
            {
                render: function (data,type,row) {
                    var html = '';
                    html = '<div class="row">'+
                            '<div class="col-md-12">'+
                                '<a href="{{url("/catalogos/editaropt")}}/{{$id}}/{{$idapp}}/'+row.id+'" class="btn btn-primary btn-block" id="editarlink" name="bajaoptcatalogo" data-id="'+row.id+'">Editar</a>'+
                                '<button class="btn btn-danger btn-block btn-baja" id="bajaoptcatalogo" name="bajaoptcatalogo" data-id="'+row.id+'">Eliminar</button>'+
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
                extend: "pdfHtml5"
            })
        ]
    });
    $(document).on("click", ".verdependencias", function(){
        var idcatop = $(this).data('id');
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: 'POST',
            data: { idcatop: idcatop },
            dataType: 'JSON',
            url: '{{ route("verdependencias") }}',
            beforeSend: function(){
                console.log("Cargando");
            },
            complete: function(){
                console.log("Listo");
            }
        }).done(function(response){
            $.each(response, function(k, v){
                swal(
                    'Dependencias',
                    v.rol_mod_rep,
                    'success'
                )
            });
        }).fail(function(response) {
                                
        });
    });

    // Baja de catálogo
    @switch($msjOk)
        @case(1)
                swal({
                    title: 'Opciones de Catálogo',
                    text: 'La operación se ha realizado de manera satisfactoria',
                    type: 'success',
                    showCancelButton: false,
                    confirmButtonColor: '#3085d6',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    confirmButtonText: 'De acuerdo'
                });
            @break
    @endswitch
    $(document).on("click", "#bajaoptcatalogo", function(){
        swal({
            title: '¿Esta seguro?',
            text: "¡Una vez eliminada la opción, ya no será posible recuperarla!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Aceptar'
        }).then((result) => {
            if (result.value) {
                var id = $(this).data("id");

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: 'PUT',
                    data: { id: id },
                    dataType: 'JSON',
                    url: '{{ route("eliminarOptCatalogos") }}',
                    async: false,
                    beforeSend: function(){
                        console.log("Cargando");
                    },
                    complete: function(){
                        console.log("Listo");
                    }
                }).done(function(response){
                    if(response === true) {
                        table.ajax.reload();
                        swal(
                            'Catálogos',
                            'La operación se ha realizado con éxito',
                            'success'
                        )
                    } else if(response === false) {
                        swal(
                            'Error',
                            'La operación no pudo ser realizada',
                            'error'
                        )
                    }
                }).fail(function(response) {
                                        
                });
            }
        });
    });
</script>
@endpush