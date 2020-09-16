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
<input type="hidden" id="modulo" value="reporteautorizadores"/>
<div class="container-fluid">
    <div class="row-fluid justify-content-center"> 
        <div class="col-md-12"> 
            <div class="card">
                <div class = "card-header">
                    Reporte de Autorizadores
                </div>
                <div class = "card-body">
                    <div class="row mb-1">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-8 text-center">
                                    <strong>Fecha de alta</strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-8 text-right">
                                    <strong>Tipo aprobador</strong>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <div class="row">
                                <div class="col-md-2 mt-2">
                                    <strong><label for="desde">Desde:</label></strong>
                                </div>
                                <div class="col-md-10">
                                    <input class="form-control" type="date" id="desde" name="desde"/>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <div class="row">
                                <div class="col-md-2 mt-2">
                                    <strong><label for="hasta">Hasta:</label></strong>
                                </div>
                                <div class="col-md-10">
                                    <input class="form-control" type="date" id="hasta" name="hasta"/>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <div class="row">
                                <div class="col-md-12">
                                    <select class = "form-control" name= "tipoaut" id="tipoaut">
                                        <option value = "">Seleccione una opción valida...</option>
                                        <option value = "1">Mesa</option>
                                        <option value = "2">Autrorizador</option>
                                        <option value = "3">Ratificador</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class ="row mb-1">
                        <div class="form-group col-md-5">
                            <div class="row">
                                <div class="col-md-2 mt-2">
                                    <label for="aplicacion">
                                    <strong>Aplicación</strong>
                                    </label>
                                </div>
                                <div class="col-md-9">
                                    <select class="form-control" name="aplicacion" id="aplicacion">
                                        <option value="">Seleccione una opción valida...</option>
                                        @foreach($listapps AS $key)
                                            <option value="{{$key['id']}}">{{$key['alias']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-7">
                            <div class="row">
                                <div class="col-md-4 mt-2">
                                    <label for="appresp">
                                    <strong>Responsabilidad/Permisos/Perfiles</strong>
                                    </label>
                                </div>
                                <div class="col-md-8">
                                    <select class = "form-control" name="appresp" id="appresp">
                                        <option value="">Seleccione una opción valida...</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class ="row mb-1">
                        <div class="form-group col-md-6">
                            <div class="row">
                                <div class="col-md-2  mt-2">
                                    <strong><label for="nombre">Nombre</label></strong>
                                </div>
                                <div class="col-md-10">
                                    <input type="text" class="form-control" name="nombreusr" id="nombreusr">
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <div class="row">
                                <div class="col-md-2  mt-2">
                                    <strong><label for="activo">Activo</label></strong>
                                </div>
                                <div class="col-md-10">
                                    <select class="form-control" name="activo" id="activo">
                                        <option value="">Seleccione una opción valida...</option>
                                        <option value="1">Si</option>
                                        <option value="2">No</option>
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
                    <div class="table-responsive">
                        <table class="table  table-bordered" id="reportaut" name="reportaut">
                            <thead>
                                <tr>
                                    <th>Aplicación</th>
                                    <th>Responsabilidad/Permisos/Perfiles</th>
                                    <th>Tipo aprobador</th>
                                    <th>Nombre</th>
                                    <th>Correo</th>
                                    <th>Usuario que dio el alta</th>
                                    <th>Fecha de alta</th>
                                    <th>Usuario que dio la baja</th>
                                    <th>Fecha de baja</th>
                                    <th>Activo</th>
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
    $(document).on('change','#aplicacion',function(){
        var app = $(this).val();
        $('#appresp').empty();
        $('#appresp').append("<option value=''>Selecciona una opcion valida</option>"); 
        $.ajax({
            url: "{{ route('opresp') }}",
            dataType:"JSON",
            data:{app: app},
            success: function(response) {
                $.each(response,function(index,value){
                    $('#appresp').append("<option value='"+value.rol_mod_rep+"'>"+value.rol_mod_rep+"</option>");
                })
            }
        });
    });
    $("#busqueda").click(function(){
        mostrarLoading();
        
        var desde = $("#desde").val();
        var hasta = $("#hasta").val();
        var tipoaut = $("#tipoaut").val();
        var app = $("#aplicacion").val();
        var responsabilidad = $("#appresp").val();
        var user = $("#nombreusr").val();
        var estatus = $("#activo").val();
        $('#reportaut').DataTable().clear();
        $('#reportaut').DataTable().destroy();
        var buttonCommon = {
            exportOptions: {
                columns: [0,1,2,3,4,5,6,7,8,9],
                format: {
                    body: function (data, row, column, node) {
                        return data;
                    }
                },
            }
        };
        var table = $('#reportaut').DataTable({
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
                url: '{!! route("datareporteautorizadores") !!}',
                type: 'POST',
                data: {
                    _token: "{{csrf_token()}}", 
                    desde: desde, 
                    hasta: hasta, 
                    tipoaut: tipoaut, 
                    app: app, 
                    responsabilidad: responsabilidad,
                    user: user,
                    estatus: estatus
                }
            },
            columns: [
                {data: 'aplicacion', name:'aplicacion'},
                {data: 'responsabilidad', name:'responsabilidad'},
                {data: 'tipo_aut', name:'tipo_aut'},
                {data: 'nombre', name:'nombre'},
                {data: 'correo', name:'correo'},
                {data: 'usr_alta', name:'usr_alta'},
                {data: 'fecha_alta', name:'fecha_alta'},
                {data: 'usr_baja', name:'usr_baja'},
                {data: 'fecha_baja', name:'fecha_baja'},
                {data: 'estatus', name:'estatus'},
            ],
            order: [1, "asc"],
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
                        orientation: "landscape"
                        // pageSize: "LEGAL"
                        // orientation: "landscape",
                        // pageSize: "TABLOID",
                        // header: false,
                        // exportOptions: {
                        //     modifier: {
                        //         page: 'current'
                        //     }
                        // },
                        // customize: function(doc) {
                        //     doc.styles.tableHeader.fontSize = 8;
                        //     doc.defaultStyle.fontSize = 8; //<-- set fontsize to 16 instead of 10 
                        //     doc.pageMargins = [10, 0, 10,0 ];
                        // } 
                    })
                ]
            });
    });
</script>
@endpush