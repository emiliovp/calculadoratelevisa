@extends('layouts.app')
@section('content')
<style>
    .ui-autocomplete {
        z-index: 9999;
    }
</style>

<div class="container-fluid">
<input type="hidden" id="modulo" name="modulo" value="listafuses">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    Lista de FUSES.
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        @if(Auth::user()->useradmin == 1)
                            @if($actLinkFusesXAutorizar == 1)
                                @if($tipo_usuario == "CAT" || !empty($tipo_usuario) && $tipo_usuario == "SYSADMIN")
                                    <div class="col-lg-6 text-left">
                                        <a href="{{ route('lista_dedicada') }}">{{ __('Ver FUS por Atender') }}</a>
                                    </div>
                                    <div class="col-lg-6 text-right">
                                        <a class="btn btn-warning" style="color:#FFFFFF;" href="{{ route('home') }}">Regresar</a>
                                        <button class="btn btn-success" id="altaFUS">Generación de FUS-e</button>
                                    </div>
                                    <div class="col-lg-6 text-left">
                                        <a href="{{ route('listafusesporautorizar') }}">{{ __('Ver FUS por Autorizar') }}</a>
                                    </div>
                                @else
                                    <div class="col-lg-12 text-right">
                                        <a class="btn btn-warning" style="color:#FFFFFF;" href="{{ route('home') }}">Regresar</a>
                                        <button class="btn btn-success" id="altaFUS">Generación de FUS-e</button>
                                    </div>
                                    <div class="col-lg-12 text-left">
                                        <a href="{{ route('listafusesporautorizar') }}">{{ __('Ver FUS por Autorizar') }}</a>
                                    </div>
                                @endif
                            @else
                                @if($tipo_usuario == "CAT")
                                    <div class="col-lg-3 text-left">
                                        <a href="{{ route('lista_dedicada') }}">{{ __('Ver FUS por Atender') }}</a>
                                    </div>
                                    <div class="col-lg-3 text-center">
                                        <a href="{{ route('reporteservicios') }}">{{ __('Ver reporte de FUS') }}</a>
                                    </div>
                                    <div class="col-lg-12 text-right">
                                        <a class="btn btn-warning" style="color:#FFFFFF;" href="{{ route('home') }}">Regresar</a>
                                        <button class="btn btn-success" id="altaFUS">Alta de FUS</button>
                                    </div>
                                @elseif($tipo_usuario == "SYSADMIN")
                                    <div class="col-lg-6 text-left">
                                        <a href="{{ route('lista_dedicada') }}">{{ __('Ver FUS por Atender') }}</a>
                                    </div>
                                    <div class="col-lg-6 text-right">
                                        <a class="btn btn-warning" style="color:#FFFFFF;" href="{{ route('home') }}">Regresar</a>
                                        <button class="btn btn-success" id="altaFUS">Alta de FUS</button>
                                    </div>
                                @else
                                    <div class="col-lg-12 text-right">
                                        <a class="btn btn-warning" style="color:#FFFFFF;" href="{{ route('home') }}">Regresar</a>
                                        <button class="btn btn-success" id="altaFUS">Alta de FUS</button>
                                    </div>
                                @endif
                            @endif
                        @else
                            @if($actLinkFusesXAutorizar == 1)
                                <div class="col-lg-6 text-left">
                                    <a href="{{ route('listafusesporautorizar') }}">{{ __('Ver FUS por Autorizar') }}</a>
                                </div>
                                <div class="col-lg-6 text-right">
                                    <a class="btn btn-warning" style="color:#FFFFFF;" href="{{ route('home') }}">Regresar</a>
                                    <button class="btn btn-success" id="altaFUS">Alta de FUS</button>
                                </div>
                            @else
                                <div class="col-lg-12 text-right">
                                    <a class="btn btn-warning" style="color:#FFFFFF;" href="{{ route('home') }}">Regresar</a>
                                    <button class="btn btn-success" id="altaFUS">Alta de FUS</button>
                                </div>
                            @endif
                        @endif
                    </div>
                    <div class="row">
                        <div class="col-lg-12 table-responsive">
                            <table class="table table-bordered" id="fus-table">
                                <thead>
                                    <tr>
                                        <th>FOLIO</th>
                                        <th>Tipo de FUS</th>
                                        <th>Estado</th>
                                        <th>Fecha solicitud</th>
                                        <th>Ver FUS</th>
                                        <th>Reenviar FUS</th>
                                    </tr>
                                </thead>
                            </table>
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
@if($confirm == 1)
Swal.fire(
  'Operación exitosa!',
  'Se ha dado de alta un nuevo FUS con folio #{{$folio}}',
  'success'
)
@endif

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
    var table = $('#fus-table').DataTable({
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
        ajax: '{!! route("fus.data") !!}',
        columns: [
            {data: 'folio_fus', name: 'folio_fus'}, 
            {data: 'tipo_fus',  name: 'tipo_fus'},
            {data: 'estado',    name: 'estado'},
            {data: 'fecha_fus',    name: 'fecha_fus'},
            {
                render: function (data,type,row){
                    var url = "{{ url('/fus/showfus/') }}";
                    url= url +'/'+ row.folio_fus;
                    var html = '<div class="row">';
                        html += '<div class="col-md-12 text-center"><a class="btn btn-primary" href="'+ url+'">Ver FUS <i class="fas fa-edit"></i></a></div>';
                        html += '</div>';
                    return html;
                }
            },
            {
                render: function (data, type, row) {
                    var html = '';
                    if (row.estado=='Atendido' || row.estado=='Rechazado' ) {
                        html = '<div class="row"><div class="col-lg-12 text-center"><label>Cerrado</label></div></div>'; 
                    }
                    else if (row.estado=='Autorizado') {
                        html = '<div class="row"><div class="col-lg-12 text-center"><label>Autorizado</label></div></div>'; 
                    }
                    else{
                        html = '<div class="row"><div class="col-lg-12 text-center"><button type="button" class="btn btn-primary btn-reenvio" data-id="' + row.folio_fus + '">Reenviar Notificaciones</button></div></div>';
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
                extend: "pdfHtml5"
            })
        ]
    });
    
    $(document).on("click", ".btn-reenvio", function(){
        swal({
            title: '¿Desea reenviar la notificación?',
            text: '',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Aceptar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {  
            if (result.value) {
                var id = $(this).attr('data-id');
                var url = '{{url("/notificaciones/reenvios")}}/'+id;
                /******************************* */
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: 'GET',
                    // data: { id: id },
                    // dataType: 'JSON',
                    url: url,
                    async: false,
                    beforeSend: function(){
                        // console.log("Cargando");
                    },
                    complete: function(){
                        // console.log("Listo");
                    }
                }).done(function(response){
                    swal.fire(
                        'Notificaciones',
                        'Se a realizado la operación de manera satisfactoria.',
                        'success'
                    )
                }).fail(function(response){
                    // location.reload();
                });
                /****************************** */        
            }
        });
        
    });

    $(document).on("click", "#altaFUS", function(){
        var url = "{{ url('/fusaplicaciones/seleccionapps/') }}";
                    // url= url +'/'+ row.folio_fus;
        var url2 = "{{ url('/fus_wintel/fus_cuenta/') }}";
        Swal({
                    title: 'Genración de FUS-e',
                    // type: 'info',
                    html:
                    '<div class="container" style="margin-top: 10px;">'+
                        '<form method="post" action="">'+
                            '<div class="form-group row">'+
                                '<div class="col-md-12">'+
                                    '<label for="name" class="col-lg-12 col-form-label text-left txt-bold">Selecciona tu FUS<span style="color: red;">*</span></label>'+
                                        '<select id="tipo" name="tipo"class="form-control" required>'+
                                            '<option value="'+url2+'/1">FUS de usuario de red </option>'+    
                                            '<option value="'+url2+'/2">FUS de Solicitud de correo </option>'+    
                                            '<option value="'+url2+'/3">FUS de cuenta de correo especial  </option>'+    
                                            '<option value="'+url+'">FUS de aplicación </option>'+    
                                            // '<option value="'+url2+'/4">FUS de solicitud de acceso a carpeta o directorio </option>'+    
                                            // '<option value="'+url2+'/5">FUS de acceso a la red por VPN </option>'+    
                                            // '<option value="'+url2+'/6">FUS de acceso a la red corporativa </option>'+   
                                        '</select>'+
                                '</div>'+
                                '<div class="col-md-12 text-right">'+
                                    '<label class="col-lg-12 col-form-label">&nbsp;</label>'+
                                    '<input class="btn btn-primary" id="nuevo_fus" type="button" value="Generar">'+
                                '</div>'+
                            '</div>'+
                        '</form>'+
                    '</div>',
                    showCloseButton: true,
                    showCancelButton: false,
                    showConfirmButton: false,
                    focusConfirm: false,
                    confirmButtonText: 'Generar FUS',
                    confirmButtonAriaLabel: 'Generar FUS',
                    // cancelButtonText: 'Cancelar Baja',
                    allowOutsideClick: false,
                });   
            });
    $(document).on("click", "#nuevo_fus", function(){
        var liga = $('#tipo').val();
        location.href = liga;
    });
</script>
@endpush