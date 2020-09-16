@extends('layouts.app')

@section('content')
<input type="hidden" id="modulo" value="admonmesas" />
<input type="hidden" id="formAjax" value="1" />
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Lista usuarios admon. de mesas
                </div>
                <div class="card-body">
                    <div class="from-group row">
                        <div class="col-lg-12 text-right" style="margin-bottom:15px;">
                            <a class="btn btn-warning" style="color:#FFFFFF;" href="{{ route('home') }}">Regresar</a>
                            <button class="btn btn-success" id="altaAdmonmesas">Alta</button>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered" id="admonmesas-table" name = "admonmesas-table">
                            <thead>
                                <tr>
                                    <th># Empleado</th>
                                    <th>Usuario de red</th>
                                    <th>Nombre</th>
                                    <th>Aplicaciones</th>
                                    <th>Fecha de creación</th>
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
            columns: [0,1,2,3],
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
    var table = $('#admonmesas-table').DataTable({
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
        ajax: '{!! route("datalistacontrol") !!}',
        columns: [
            {data: 'no_empleado', name: 'no_empleado'}, 
            {data: 'usuario_red', name: 'usuario_red'}, 
            {data: 'nombre', name: 'nombre'}, 
            {data: 'apps', name: 'apps'},
            {data: 'created_at', name: 'created_at'}, 
            {
                render: function (data,type,row){
                    var html = '';
                    html = '<div class="row">'+
                            '<div class="col-md-12">'+
                                '<button class="btn btn-primary btn-block btn-editar" name="editarusuario" data-usuario_red="'+row.usuario_red+'" data-id="'+row.id+'" data-clavesapps="'+row.clavesapps+'">Editar</button>'+
                                '<button class="btn btn-danger btn-block btn-baja" id="bajausuario" name="bajausuario" data-usuario_red="'+row.usuario_red+'" data-id="'+row.id+'">Eliminar</button>'+
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
    $("#altaAdmonmesas").click(function(){
        formAltaEditar();
    });
    $(document).on("click", ".btn-editar", function(){
        var clavesapps = $(this).data("clavesapps");
        clavesapps = clavesapps.toString();
        var id = $(this).data("id");
        var usuario_red = $(this).data("usuario_red");
        
        formAltaEditar("editar", id, usuario_red, clavesapps);

        if(clavesapps == null) {
            $("#aplicacion").prop("disabled", true);
            $("#apptodas").prop("checked", true);
        }
    });
    $(document).on("click", "#guardar", function(){
        guardar();
    });
    $(document).on("click", "#guardarE", function(){
        guardar("editar");
    });
    // Baja de catálogo
    $(document).on("click", "#bajausuario", function(){
        swal({
            title: '¿Esta seguro?',
            text: "¡Una vez eliminado el usuario, no podrá recuperarlo!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Aceptar'
        }).then((result) => {
            if (result.value) {
                var id = $(this).data("id");                
                var usuario_red = $(this).data("usuario_red");

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: 'PUT',
                    data: { id: id, usuario_red: usuario_red },
                    dataType: 'JSON',
                    url: '{{ route("eliminarAdmonMesas") }}',
                    async: false,
                    beforeSend: function(){
                        mostrarLoading();
                    },
                    complete: function(){
                        ocultarLoading();
                    }
                }).done(function(response){
                    if(response === true) {
                        table.ajax.reload();
                        swal(
                            'Admon Mesas de Control',
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

    function buscarEnArray(matriz, buscar) {
        if(matriz != null) {
            if (matriz.indexOf(buscar) === -1) {
                return false;
            } else if (matriz.indexOf(buscar) > -1) {
                return true;
            }
        } else {
            return false;
        }
    }

    // formulario de alta
    function formAltaEditar(tipo = "alta", id = null, usuario_red = null, clavesapps = null) {
        var idBotonGuardar = "";
        var searchEmpleado = "";
        if(tipo == "editar") {
            idBotonGuardar = "guardarE";
            
            if(clavesapps != null) {
                clavesapps = clavesapps.split(",");
            }

            searchEmpleado = '<div class="form-group row">'+
                        '<div class="col-md-12">'+
                            '<label class="col-lg-12 col-form-label">'+usuario_red+'</label>'+
                            '<input value="'+id+'" id="idempleado" type="hidden" name="id">'+
                            '<input value="'+usuario_red+'" id="usuario_red" type="hidden" name="usuario_red">'+
                        '</div>'+
                    '</div>';
        } else {
            searchEmpleado = '<div class="form-group row">'+
                        '<div class="col-md-8">'+
                            '<label for="numepm" class="col-lg-12 col-form-label text-left txt-bold"># de Empleado</label>'+
                            '<input type="number" min="1" id="numepm" placeholder="Ej. 2017853..." class="form-control" name="numepm" required autofocus>'+
                            '<span id="errmsj_empleado" class="error-msj" role="alert">'+
                                '<strong>No se ha encontrado un empleado</strong>'+
                            '</span>'+
                        '</div>'+
                        '<div class="col-md-4">'+
                            '<label class="col-lg-12 col-form-label">&nbsp;</label>'+
                            '<input type="hidden" id="numemp" value="">'+
                            '<input type="hidden" id="samaccountname" value="">'+
                            '<button class="btn btn-primary" id="busqueda" type="button">Buscar <i class="fas fa-search"></i></button>'+
                        '</div>'+
                    '</div>'+
                    '<div class="form-group row">'+
                        '<div class="col-md-12" id="showBusqueda">'+
                        '</div>'+
                    '</div>';
            idBotonGuardar = "guardar";
        }

        var htmlSelectApss = '';
        @foreach($apps AS $key => $value)
            var idapp = {{$value['id']}};
            var app = "{{$value['alias']}}";
            if(tipo == "editar") {
                if(buscarEnArray(clavesapps, idapp.toString()) === true) {
                    htmlSelectApss += '<option selected value="'+idapp+'">'+app+'</option>';        
                } else {
                    if(clavesapps != null) {
                        htmlSelectApss += '<option value="'+idapp+'">'+app+'</option>';        
                    } else {
                        htmlSelectApss += '<option selected value="'+idapp+'">'+app+'</option>';
                    }
                }
            } else {
                htmlSelectApss += '<option selected value="'+idapp+'">'+app+'</option>';
            }
        @endforeach
        var selectApps = 
            '<label for="aplicacion" class="col-lg-12 col-form-label text-left txt-bold">Aplicaciones</label>'+
            '<select data-field="aplicacion" class="form-control multiselectvalidacion" multiple name="aplicacion" id="aplicacion">'+
                htmlSelectApss+
            '</select>'
        ;
        Swal({
            title: 'Admon. Mesas de Control',
            // type: 'info',
            html:
            '<div class="container" style="margin-top: 10px;">'+
                '<form method="post" action="">'+
                    searchEmpleado+
                    '<div class="form-group row">'+
                        '<div class="col-md-12">'+
                            '<label for="apptodas" class="col-lg-12 col-form-label text-left txt-bold">Todas las aplicaciones <input type="checkbox" name="apptodas" id="apptodas"/></label>'+
                        '</div>'+
                        '<div class="col-md-12">'+
                            selectApps+
                            '<span id="errmsj_app" class="error-msj" role="alert">'+
                                '<strong>Debe seleccionar al menos una aplicación o marcar el indicador de todas las aplicaciones.</strong>'+
                            '</span>'+
                        '</div>'+
                    '</div>'+
                    '<div class="form-group row">'+
                        '<div class="col-md-6">'+
                            '<a href="#" onclick="swal.closeModal(); return false;" class="btn btn-danger btn-block">Cancelar</a>&nbsp;&nbsp;'+
                            '</div>'+
                        '<div class="col-md-6">'+
                            '<input class="btn btn-primary btn-block" id="'+idBotonGuardar+'" type="button" value="Guardar">'+
                        '</div>'+
                    '</div>'+
                '</form>'+
            '</div>',
            showCloseButton: true,
            showCancelButton: false,
            showConfirmButton: false,
            focusConfirm: false,
            allowOutsideClick: false,
        });   
    }
    
    $(document).on("click", "#apptodas", function() {
        if($(this).is(":checked")) {
            $("#aplicacion").prop("disabled", true);
        } else {
            $("#aplicacion").prop("disabled", false);
        }
    });

    $(document).on("click", "#busqueda", function () {
        mostrarLoading();
        tipo = $('#tipoBusqueda').val();
        numemp = $('#numepm').val();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: 'POST',
            data: { tipo: tipo, valor: numemp },
            dataType: 'JSON',
            url: '{{ route("searchautorizador") }}',
            // async: false,
            beforeSend: function(){
                // 
            },
            complete: function(){
                // 
            }
        }).done(function(response){
            if(response.length > 0) {
                html = '<table class="col-md-12" style="font-size: 15px;"><caption>Resultado de la busqueda</caption><thead><th>Nombre</th><th># Empleado</th></thead><tbody><td>'+response[0].displayname+'</td><td>'+response[0].employee_number+'</td></tbody></table>';
                $('#numemp').val(response[0].employee_number);
                $('#samaccountname').val(response[0].samaccountname);
                ocultarError("errmsj_empleado");
            } else {
                html = '<table class="col-md-12" style="font-size: 15px;"><tbody><td>Sin resultados</td></tbody></table>';
            }
            $('#showBusqueda').html(html);
            ocultarLoading();
        }).fail(function(response){
        });
    });
    
    var validaRequired = 0;

    function guardar(tipo = "alta") {
        var apps = "";
        var camposAValidar = 3;

        if($("#apptodas").is(":checked")) {
            apps = 1;
        } else {
            appsArray = $("#aplicacion").val();
            apps = appsArray;
        }
        var formAjax = $("#formAjax").val();
        if(tipo == "editar") {
            var id = $("#idempleado").val();
            var usuario_red = $("#usuario_red").val();
            camposAValidar = 1;
        } else {
            var no_empleado = $("#numemp").val();
            var usuario_red = $("#samaccountname").val();

            if (no_empleado == null || no_empleado == "") {
                mostrarError("errmsj_empleado");
                if(validaRequired > 0) {
                    validaRequired = validaRequired-1;    
                }
            } else {
                ocultarError("errmsj_empleado");
                validaRequired = validaRequired+1;
            }

            if (usuario_red == null || usuario_red == "") {
                mostrarError("errmsj_empleado");
                if(validaRequired > 0) {
                    validaRequired = validaRequired-1;    
                }
            } else {
                ocultarError("errmsj_empleado");
                validaRequired = validaRequired+1;
            }
        }

        if (apps == null || apps == "") {
            mostrarError("errmsj_app");
            if(validaRequired > 0) {
                validaRequired = validaRequired-1;    
            }
        } else {
            ocultarError("errmsj_app");
            validaRequired = validaRequired+1;
        }

        if (validaRequired == camposAValidar) {
            ocultarError("errmsj_empleado");

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            if(tipo == "editar") {
                var ajax = $.ajax({
                    type: 'PUT',
                    data: { formAjax: formAjax, usuario_red: usuario_red, id: id, apps: apps},
                    dataType: 'JSON',
                    url: '{{ route("updateAdmonMesas") }}',
                    async: false,
                    beforeSend: function(){
                        mostrarLoading();
                    },
                    complete: function(){
                        ocultarLoading();
                    }
                });
            } else {
                var ajax = $.ajax({
                    type: 'POST',
                    data: { formAjax: formAjax, usuario_red: usuario_red, no_empleado: no_empleado, apps: apps},
                    dataType: 'JSON',
                    url: '{{ route("storeAdmonMesas") }}',
                    beforeSend: function(){
                        mostrarLoading();
                    },
                    complete: function(){
                        ocultarLoading();
                    }
                });
            }
            
            ajax.done(function(response){
                switch (response) {
                    case true:
                        table.ajax.reload();
                        swal(
                            'Admon. Mesas',
                            'La operación se ha realizado con éxito',
                            'success'
                        )
                        break;
                    case "error_unique":
                        $("#errmsj_empleado").html("<strong>El empleado ya existe en los registros, favor de ingresar a otro empleado.</strong>");
                        mostrarError("errmsj_empleado");
                        break;
                    case false:
                        swal(
                            'Error',
                            'La operación no pudo ser realizada',
                            'error'
                        )
                        break;
                }
            }).fail(function(response) {
                if (response.responseJSON !== undefined && response.responseJSON.errors !== undefined && response.responseJSON.errors.no_empleado !== undefined && response.responseJSON.errors.no_empleado[0] != "") {
                    $("#errmsj_empleado").html("<strong>"+response.responseJSON.errors.no_empleado[0]+"</strong>");
                    mostrarError("errmsj_empleado");
                }
                if (response.responseJSON !== undefined && response.responseJSON.errors !== undefined && response.responseJSON.errors.usuario_red !== undefined && response.responseJSON.errors.usuario_red[0] != "") {
                    $("#errmsj_empleado").html("<strong>"+response.responseJSON.errors.usuario_red[0]+"</strong>");
                    mostrarError("errmsj_empleado");
                }
            });
        }
        
        validaRequired = 0;
    }
</script>
@endpush