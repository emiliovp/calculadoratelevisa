@extends('layouts.app')

@section('content')
<input type="hidden" id="modulo" value="listacatalogo" />
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Lista de Catálogos
                </div>
                <div class="card-body">
                    <div class="from-group row">
                        <div class="col-lg-12 text-right" style="margin-bottom:15px;">
                            <a class="btn btn-warning" style="color:#FFFFFF;" href="{{ route('home') }}">Regresar</a>
                            <!-- <button class="btn btn-success" id="altaCatalogos">Alta de Catálogos</button> -->
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered" id="aplicaciones-table" name = "aplicaciones-table">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Estado</th>
                                    <th>Fecha de creación</th>
                                    <th>Dependencia con aplicación</th>
                                    <th>Opciones de Catálogo</th>
                                    <!-- <th>Acciones</th> -->
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
            columns: [0,1,2],
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
        ajax: '{!! route("dataIndexCat") !!}',
        columns: [
            {data: 'cat_nombre', name: 'cat_nombre'}, 
            {data: 'estado', name: 'estado'}, 
            {data: 'created_at', name: 'created_at'}, 
            {data: 'aplicacion', name: 'aplicacion'}, 
            {
                render: function (data,type,row){
                    var html = '';
                    html = '<div class="row">'+
                            '<div class="col-md-12">'+
                                '<a href="{{url("catalogos/listaopciones")}}/'+row.id+'/'+row.applications_id+'">Ver opciones</a>'+
                            '</div>'+
                           '</div>';

                    return html;
                }
            },
            // {
            //     render: function (data,type,row){
            //         var html = '';
            //         html = '<div class="row">'+
            //                 '<div class="col-md-12">'+
            //                     '<button class="btn btn-danger btn-block btn-baja" id="bajacatalogo" name="bajacatalogo" data-id="'+row.id+'" data-nombre="'+row.cat_nombre+'" data-aplicacion="'+row.aplicacion+'">Eliminar</button>'+
            //                 '</div>'+
            //                '</div>';

            //         return html;
            //     }
            // },
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
    $("#altaCatalogos").click(function(){
        formAltaEditar();
    });
    $(document).on("click", "#guardarCatalogo", function(){
        guardarAltaEditar();
    });
    // Baja de catálogo
    $(document).on("click", "#bajacatalogo", function(){
        swal({
            title: '¿Esta seguro?',
            text: "¡Una vez eliminado el catálogo, todas sus opciones serán eliminadas de igual forma!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Aceptar'
        }).then((result) => {
            if (result.value) {
                var id = $(this).data("id");                
                var cat = $(this).data("nombre");
                var aplicacion = $(this).data("aplicacion");

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: 'PUT',
                    data: { id: id, cat_nombre: cat, aplicacion: aplicacion },
                    dataType: 'JSON',
                    url: '{{ route("eliminarCatalogos") }}',
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
    // formulario de alta
    function formAltaEditar(tipo = "alta", id = "", name = "") {
        var idBotonGuardar = "guardarCatalogo"
        var readonly = '';
        
        if(tipo == "editar") {
            idBotonGuardar = "guardarEditarCatalogo";
            readonly = 'readonly="readonly"';
        }

        Swal({
            title: 'CATÁLOGOS',
            // type: 'info',
            html:
            '<div class="container" style="margin-top: 10px;">'+
                '<form method="post" action="">'+
                    '<input type="hidden" name="id" id="idProveedor" value="'+id+'">'+
                    '<div class="form-group row">'+
                        '<div class="col-md-12">'+
                            '<label for="name" class="col-lg-12 col-form-label text-left txt-bold">Nombre<span style="color: red;">*</span></label>'+
                            '<input id="name" type="text" class="form-control" name="name" required autofocus value="'+name+'">'+

                            '<span id="errmsj_name" class="error-msj" role="alert">'+
                                '<strong>El campo Nombre es obligatorio</strong>'+
                            '</span>'+
                        '</div>'+
                    '</div>'+
                    '<div class="form-group row">'+
                        '<div class="col-md-12">'+
                            '<label for="aplicacion" class="col-lg-12 col-form-label text-left txt-bold">Dependencia con aplicación</label>'+
                            // '<input id="description" type="text" class="form-control" name="description" required autofocus value="'+description+'">'+
                            '<select id="aplicacion" class="form-control" name="aplicacion" autofocus>'+
                                '<option value="">Seleccione</option>'+
                            @foreach($apps AS $row)
                                '<option value="{{$row["id"]}}">{{$row["name"]}}</option>'+
                            @endforeach
                            '</select>'+
                            '<span id="errmsj_descripcion" class="error-msj" role="alert">'+
                                '<strong>El campo Descripción es obligatorio</strong>'+
                            '</span>'+
                        '</div>'+
                    '</div>'+
                    '<div class="col-md-12 text-right">'+
                        '<label class="col-lg-12 col-form-label">&nbsp;</label>'+
                        '<a href="#" onclick="swal.closeModal(); return false;" class="btn btn-danger">Cancelar</a>&nbsp;&nbsp;'+
                        '<input class="btn btn-primary" id="'+idBotonGuardar+'" type="button" value="Guardar">'+
                    '</div>'+
                '</form>'+
            '</div>',
            showCloseButton: true,
            showCancelButton: false,
            showConfirmButton: false,
            focusConfirm: false,
            confirmButtonText: 'Aplicar Baja',
            confirmButtonAriaLabel: 'Aplicar Baja',
            cancelButtonText: 'Cancelar Baja',
            allowOutsideClick: false,
        });   
    }
    var validaRequired = 0;

    function guardarAltaEditar(tipo = "alta") {
        var name = $("#name").val();
        var idapp = null;
        if($("#idapp").val() != "") {
            idapp = $("#idapp").val();
        }
        var formAjax = $("#formAjax").val();

        if (name == null || name == "") {
            mostrarError("errmsj_name");
            if(validaRequired > 0) {
                validaRequired = validaRequired-1;    
            }
        } else {
            ocultarError("errmsj_name");
            validaRequired = validaRequired+1;
        }

        if (validaRequired == 1) {
            ocultarError("errmsj_name");

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // if(tipo == "editar") {
            //     var id = $("#idProveedor").val();
            //     var ajax = $.ajax({
            //         type: 'PUT',
            //         data: { modulo: modulo, formAjax: formAjax, name: name, alias: alias, description: description, id: id },
            //         dataType: 'JSON',
            //         url: 'Route para editar por ajax aquí va',
            //         async: false,
            //         beforeSend: function(){
            //             console.log("Cargando");
            //         },
            //         complete: function(){
            //             console.log("Listo");
            //         }
            //     });
            // } else {
                var ajax = $.ajax({
                    type: 'POST',
                    data: { formAjax: formAjax, name: name, app: idapp},
                    dataType: 'JSON',
                    url: '{{ route("storecat") }}',
                    beforeSend: function(){
                        console.log("Cargando");
                    },
                    complete: function(){
                        console.log("Listo");
                    }
                });
            // }
            
            ajax.done(function(response){
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
                // if (response.responseText !== undefined && response.responseText == "middleUpgrade") {
                    // En caso de redirección a home
                    // window.location.href = "{{ route('homeajax') }}";
                // }
                if (response.responseJSON !== undefined && response.responseJSON.errors !== undefined && response.responseJSON.errors.name !== undefined && response.responseJSON.errors.name[0] != "") {
                    $("#errmsj_name").html("<strong>"+response.responseJSON.errors.name[0]+"</strong>");
                    mostrarError("errmsj_name");
                }
            });
        }
        
        validaRequired = 0;
    }
</script>
@endpush