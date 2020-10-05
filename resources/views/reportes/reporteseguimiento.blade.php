@extends('layouts.app')
@section('content')
<style>
.dataTables_wrapper .dataTables_processing {
    top: 0%;
    width: 100%;
    height: 100%;
    background-color: #fff;
}
</style>
<input type="hidden" id="modulo" value="reportaud" />
<div class="container-fluid">
    <div class="row-fluid justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    Reporte de auditoria
                </div>
                <div class="card-body">
                    <!-- <div class="container"> -->
                        <div class="row mb-1">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-8 text-center">
                                        <strong>Fecha de creación</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <div class="row">
                                    <div class="col-md-2 mt-2">
                                        <label for="desde">Desde:</label>
                                    </div>
                                    <div class="col-md-10">
                                        <input class="form-control" type="date" id="desde" name="desde"/>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <div class="row">
                                    <div class="col-md-2 mt-2">
                                        <label for="hasta">Hasta:</label>
                                    </div>
                                    <div class="col-md-10">
                                        <input class="form-control" type="date" id="hasta" name="hasta"/>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <div class="row">
                                    <div class="col-md-2 mt-2">
                                        <label for="folio">Folio:</label>
                                    </div>
                                    <div class="col-md-10">
                                        <input class="form-control" type="number" min="1" id="folio" name="folio"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-1">
                            <div class="form-group col-md-6">
                                <div class="row">
                                    <div class="col-md-2">
                                        <label for="tipo_fus">Tipo de Fus:</label>
                                    </div>
                                    <div class="col-md-10">
                                        <select data-field="tipofus" class="form-control multiselectvalidacion" multiple name="tipo_fus" id="tipo_fus">
                                            <option id="opt-tipofus" value="" selected>Ninguna</option>
                                            <!-- @ foreach($dataForm["tipofus"] AS $key => $value)
                                            <option value="{ {$value['tipo_fus']}}">{ {$value['tipo_fus']}}</option>
                                            @ endforeach -->
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <div class="row">
                                    <div class="col-md-2">
                                        <label for="tipo_movimiento">Tipo movimiento:</label>
                                    </div>
                                    <div class="col-md-10">
                                        <select data-field="tipomov" class="form-control multiselectvalidacion" multiple name="tipo_movimiento" id="tipo_movimiento">
                                            <option id="opt-tipomov" value="" selected>Ninguna</option>
                                            <!-- @ foreach($dataForm["tipomovimiento"] AS $key => $value)
                                            <option value="{ {$value['tipo_movimiento']}}">{ {$value['tipo_movimiento']}}</option>
                                            @ endforeach -->
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="row mb-1">
                            
                        </div> -->
                        <div class="row mb-1" id="fieldAplicaciones" style="display:none;">
                            <div class="form-group col-md-6">
                                <div class="row">
                                    <div class="col-md-2">
                                        <label for="aplicacion">Aplicación:</label>
                                    </div>
                                    <div class="col-md-10">
                                        <select data-field="aplicacion" class="form-control multiselectvalidacion" multiple name="aplicacion" id="aplicacion">
                                            <option id="opt-aplicacion" value="" selected>Ninguna</option>
                                            <!-- @ foreach($dataForm["apps"] AS $key => $value)
                                            <option value="{  {$value['app']} }">{  {$value['app']} }</option>
                                            @ endforeach -->
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <div class="row">
                                    <div class="col-md-2">
                                        <label for="sox">Tipo de aplicación:</label>
                                    </div>
                                    <div class="col-md-10">
                                        <select class="form-control" name="sox" id="sox">
                                            <option value="">Seleccione...</option>
                                            <option value="1">SOX</option>
                                            <option value="0">NO SOX</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-end mb-3">
                            <div class="form-group col-md-2 text-right">
                                <a class="form-control btn btn-warning" style="color:#FFFFFF;" href="{{ route('home') }}">Regresar</a>
                                </div>
                            <div class="form-group col-md-2 text-right">
                                <input class="form-control btn btn-primary" id="busqueda" type="button" value="Buscar" />
                            </div>
                        </div>
                    <!-- </div> -->
                    <div class="row mb-3">
                        <div class="col-md-12" id="total-fuse">
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered" id="reporteseguimiento">
                            <thead>
                                <tr>
                                    <th>Folio</th>
                                    <th>Fecha de creación</th>
                                    <th>Tipo de FUS</th>
                                    <th>Tipo de movimiento</th>
                                    <th>Tipo empleado</th>
                                    <th>Solicitante</th>
                                    <th>Jefe</th>
                                    <th>Estado jefe</th>
                                    <th>Fecha autorización</th>
                                    <th>Autorizador</th>
                                    <th>Estado autorizador</th>
                                    <th>Fecha autorización</th>
                                    <th>Estado FUS-e</th>
                                    <th>Aplicación</th>
                                    <th>Estado de la aplicación</th>
                                    <th>Fecha de aut. de la aplicación</th>
                                    <th>Objeto de autorización</th>
                                    <th>Mesa de control</th>
                                    <th>Estado mesa de control</th>
                                    <th>MC fecha de autorización</th>
                                    <th>Autorizador adicional</th>
                                    <th>Estado autorizador adicional</th>
                                    <th>Auto. adicional fecha de autorización</th>
                                    <th>Ratificador</th>
                                    <th>Estado ratificador</th>
                                    <th>Ratificador fecha de autorización</th>
                                </tr>
                            </thead>
                        </table> 
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection('content')
@push('scripts')
<script>
    function actualizarFiltros() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: '{!! route("filtersForm") !!}',
            type: 'GET',
            // async: false,
            beforeSend: function(){

            },
        }).done(function(response){
            var htmltipofus = '<option id="opt-tipofus" value="" selected>Ninguna</option>';
            var htmltipomovimiento = '<option id="opt-tipomov" value="" selected>Ninguna</option>';
            var htmlaplicacion = '<option id="opt-aplicacion" value="" selected>Ninguna</option>';

            $.each(response["tipomovimiento"], function(key, value) {
                htmltipomovimiento = htmltipomovimiento+'<option value="'+value.tipo_movimiento+'">'+value.tipo_movimiento+'</option>';
            });
            $.each(response["tipofus"], function(key, value) {
                htmltipofus = htmltipofus+'<option value="'+value.tipo_fus+'">'+value.tipo_fus+'</option>';
            });
            $.each(response["apps"], function(key, value) {
                htmlaplicacion = htmlaplicacion+'<option value="'+value.app+'">'+value.app+'</option>';
            });
            
            $("#tipo_fus").html(htmltipofus);
            $("#tipo_movimiento").html(htmltipomovimiento);
            $("#aplicacion").html(htmlaplicacion);
        });
    }

    function getTotal(desde, hasta, folio, tipofus, tipomovimiento, aplicacion, sox) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: '{!! route("datareporteseguimientopost") !!}',
            type: 'POST',
            data: {
                _token: "{{csrf_token()}}", 
                desde: desde, 
                hasta: hasta, 
                folio: folio, 
                tipofus: tipofus, 
                tipomovimiento: tipomovimiento,
                aplicacion: aplicacion,
                sox: sox,
                total: 1 // Para indicar si solo quieres el total de fus-e
            },
            beforeSend: function(){

            },
            complete: function(){
                // ocultarLoading();
            }
        }).done(function(response){
            $("#total-fuse").html('<strong>Total de FUS-e encontrados: '+response+'</strong>');
            // ocultarLoading();
        });
    }
    
    $(document).ready(function(){
        $("#tipo_fus").change(function(){
            var app = 0;
            $.each($(this).val(), function(key, value) {
                if(value == "Aplicaciones") {
                    app = 1;
                }
            });

            if(app == 1) {
                $("#fieldAplicaciones").show();
            } else {
                $("#fieldAplicaciones").hide();
            }
        });
        
        $(".multiselectvalidacion").change(function(){
            var elegidos = 0;
            var tipo = $(this).data("field");
            $.each($(this).val(), function(key, value) {
                if(value != "") {
                    elegidos = elegidos+1;
                }
                if(elegidos > 0) {
                    $("#opt-"+tipo).prop("selected", false);
                }
            });
            
        });

        $("#busqueda").click(function(){
            mostrarLoading();

            var desde = $("#desde").val();
            var hasta = $("#hasta").val();
            var folio = $("#folio").val();
            var tipofus = $("#tipo_fus").val();
            var tipomovimiento = $("#tipo_movimiento").val();
            var aplicacion = $("#aplicacion").val();
            var sox = $("#sox").val();
            
            if(
                desde != "" && hasta == "" ||
                desde == "" && hasta != ""
            ) {
                swal(
                    'Validación',
                    'Los campos de fecha de creación deben ser llenados.',
                    'warning'
                )
                return false;
            }
            // 
            $('#reporteseguimiento').DataTable().clear();
            $('#reporteseguimiento').DataTable().destroy();
            var buttonCommon = {
                exportOptions: {
                    columns: [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25],
                    format: {
                        body: function (data, row, column, node) {
                            return data;
                        }
                    },
                }
            };
            var table = $('#reporteseguimiento').DataTable({
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
                drawCallback: function(settings) {
                    ocultarLoading();
                },
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{!! route("datareporteseguimientopost") !!}',
                    type: 'POST',
                    data: {
                        _token: "{{csrf_token()}}", 
                        desde: desde, 
                        hasta: hasta, 
                        folio: folio, 
                        tipofus: tipofus, 
                        tipomovimiento: tipomovimiento,
                        aplicacion: aplicacion,
                        sox: sox,
                        total: 0 // Para indicar si solo quieres el resultado de la consulta
                    }
                },
                columns: [
                    {data: 'folio', name: 'folio'},
                    {data: 'fecha_creacion', name: 'fecha_creacion'}, 
                    {data: 'tipo_fus', name: 'tipo_fus'}, 
                    {data: 'tipo_movimiento', name: 'tipo_movimiento'}, 
                    {data: 'tipo_empleado', name: 'tipo_empleado'}, 
                    {data: 'solicitante', name: 'solicitante'},
                    {data: 'jefe', name: 'jefe'},
                    {data: 'estado_jefe', name: 'estado_jefe'},
                    {data: 'fecha_autorizacion_jefe', name: 'fecha_autorizacion_jefe'},
                    {data: 'autorizador', name: 'autorizador'},
                    {data: 'estado_aut', name: 'estado_aut'},
                    {data: 'fecha_autorizacion_aut', name: 'fecha_autorizacion_aut'},
                    {data: 'estado_fus', name: 'estado_fus'},
                    {data: 'app', name: 'app'},
                    {data: 'estado_app', name: 'estado_app'},
                    {data: 'fecha_aut_app', name: 'fecha_aut_app'},
                    {data: 'rol_mod_rep', name: 'rol_mod_rep'},
                    {data: 'mesa_control', name: 'mesa_control'},
                    {data: 'mesa_control_estado', name: 'mesa_control_estado'},
                    {data: 'mesa_control_fecha_autorizacion', name: 'mesa_control_fecha_autorizacion'},
                    {data: 'aut_adicional', name: 'aut_adicional'},
                    {data: 'aut_adicional_estado', name: 'aut_adicional_estado'},
                    {data: 'aut_adicional_fecha_autorizacion', name: 'aut_adicional_fecha_autorizacion'},
                    {data: 'ratificador', name: 'ratificador'},
                    {data: 'ratificador_estado', name: 'ratificador_estado'},
                    {data: 'ratificador_fecha_autorizacion', name: 'ratificador_fecha_autorizacion'}
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
                        pageSize: "TABLOID",
                        // header: false,
                        exportOptions: {
                            modifier: {
                                page: 'current'
                            }
                        },
                        customize: function(doc) {
                            doc.styles.tableHeader.fontSize = 8;
                            doc.defaultStyle.fontSize = 8; //<-- set fontsize to 16 instead of 10 
                            doc.pageMargins = [10, 0, 10,0 ];
                        } 
                    })
                ]
            });
            // 
            var total = getTotal(desde, hasta, folio, tipofus, tipomovimiento, aplicacion, sox);
            actualizarFiltros();
            // ocultarLoading();
        });
    });
</script>
@endpush