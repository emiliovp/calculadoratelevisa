@extends('layouts.app')
@section('content')
<style>
    .ui-autocomplete {
        z-index: 9999;
    }
</style>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    Lista de Aplicaciones
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-lg-12 text-right">
                            <a href="{{ route('lista_dedicada') }}" class="btn btn-warning" style="color:#FFFFFF;">{{ __('Regresar') }}</a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 table-responsive">
                            <table class="table table-bordered" id="fus-table">
                                <thead>
                                    <tr>
                                        <th>Aplicación</th>
                                        <th>Estado</th>
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
</div>
@endsection
@push('scripts')
<script>
    var buttonCommon = {
        exportOptions: {
            columns: [0,1,2,3,4,5,6,7,8],
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
        ajax: '{!! route("fus.appsdata", $idfus) !!}',
        columns: [
            {data: 'nombre_app', name: 'nombre_app'}, 
            {data: 'estadoActual', name: 'estadoActual'}, 
            {
                render: function (data,type,row){
                    var btnDescargarAnexo;
                    if(row.extiste_anexo > 0) {
                        var urlAnexo;
                        if(row.extiste_anexo == 1) {
                            urlAnexo = "{{ url('/descargas/anexos') }}/"+row.path_anexos;
                            btnDescargarAnexo = '<div class="col-lg-4"><a class="btn btn-primary btn-block btn-descarga" style="color:#FFFFFF !important" href="'+urlAnexo+'">Archivo Anexo <i class="fas fa-download"></i></a></div>';
                        } else {
                            jsonPathAnexos = JSON.stringify(row.path_anexos);
                            btnDescargarAnexo = "<div class='col-lg-4'><button class='btn btn-primary btn-block btn-descarga-list' data-json='"+jsonPathAnexos+"'>Archivo Anexo <i class='fas fa-download'></i></button></div>";
                        }
                    } else {
                        btnDescargarAnexo = '<div class="col-lg-4"><button class="btn btn-primary btn-block btn-not-descarga">Archivo Anexo <i class="fas fa-download"></i></button></div>';
                    }
                    var disabledAtt = '';
                    if(row.estadoActual == "Atendido") {
                        disabledAtt = 'disabled';
                    }

                    var html = '';
                    html = '<div class="row">'+
                        '<div class="col-lg-4"><button class="btn btn-primary btn-block btn-detalles" data-app="'+row.nombre_app+'" data-detalles="'+row.detalles+'">Ver Detalles <i class="fas fa-eye"></i></button></div>'+
                            btnDescargarAnexo+
                        '<div class="col-lg-4"><button class="btn btn-primary btn-block btn-atender" data-idfus="'+row.fus_sysadmin_wtl_id+'" data-idapp="'+row.applications_id+'" data-detalles="'+row.detalles+'" data-estado="'+row.estadoActual+'" '+disabledAtt+'>Atender <i class="fas fa-concierge-bell"></i></button></div>'+
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
    $(document).ready(function(){
        $(document).on('click', '.btn-descarga-list', function() {
            var jsonPaths = $(this).data('json');
            var html = '';
            $.each( jsonPaths, function( key, value ) {
                urlAnexo = "{{ url('/descargas/anexos') }}/"+value.path;
                html += '<div class="row">'+
                            '<div class="col-lg-12">'+
                                '<a href="'+urlAnexo+'">'+value.path+'</a>'+
                            '</div>'+
                        '</div>';
            });
            
            Swal({
                title: 'Descarga de Anexos',
                // type: 'info',
                html:'<div class="container" style="margin-top: 10px;">'+
                    '<div class="form-group row">'+
                        '<div class="col-md-12">'+
                            html+
                        '</div>'+
                    '</div>'+
                '</div>',
                showCloseButton: true,
                showCancelButton: false,
                showConfirmButton: false,
                focusConfirm: false,
                confirmButtonText: '',
                confirmButtonAriaLabel: '',
                allowOutsideClick: false,
            });
        });
        $(document).on('click', '.btn-detalles', function() {
            var detalles = $(this).attr('data-detalles');
            var nombre_app = $(this).attr('data-app');        

            detalles = JSON.parse(detalles);
            var htmlContent = '<div class="container">';
            $.each( detalles, function( key, value ) {
                $.each( value, function( k, v ) {
                    var valorField;
                    switch (v.valor) {
                        case 'a':
                            valorField = 'Alta';
                            break;
                        case 'b':
                            valorField = 'Baja';
                            break;
                        case 'c':
                            valorField = 'Cambio';
                            break;

                        case 'on':
                            valorField = '<i class="fas fa-check"></i>';
                            break;
                        default:
                            valorField = v.valor;
                            break;
                    }

                    if(k != 'autorizador_unico' && k != 'path_anexos') {
                        htmlContent += '<div class="row"><div class="col-lg-6 text-left" style="font-weight:bold !important;">'+v.etiqueta+': </div><div class="col-lg-1 text-left"></div><div class="col-lg-5 text-left">'+valorField+'</div></div>';
                    }
                });
            });
            htmlContent += '</div>';

            swal.fire({
                title: 'Detalles',
                // text: "¿Esta seguro de querer realizar la operación?",
                html: htmlContent,
                type: 'info',
            })
        });
        // Atender aplicación
        $(document).on("click", "#atencionappnulo", function() {
            swal.fire(
                'Atención',
                'Para poder indicar que la aplicación ha sido atendida, se requiere de la autorización de la misma.',
                'warning'
            )
        });
        $(document).on("click", "#atencionappatendido", function() {
            swal.fire(
                'Atención',
                'La aplicación ya fue atendida.',
                'warning'
            )
        });
        $(document).on("click", "#atencionapprechazada", function() {
            swal.fire(
                'Atención',
                'La aplicación ya fue rechazada.',
                'warning'
            )
        });
        $(document).on("click", ".btn-not-descarga", function() {
            swal.fire(
                'Atención',
                'No hay archivos anexos a esta aplicación.',
                'warning'
            )
        });
        $(document).on("click", ".btn-atender", function() {
            var idapp = $(this).attr('data-idapp');
            var idfus = $(this).attr('data-idfus');
            var estadoFus = $(this).attr('data-estado');
            var btnAtender;

            switch (estadoFus) {
                case 'Pendiente':
                    btnAtender = '<button class="btn btn-primary" id="atencionappnulo">Atender</button>';
                    btnRechazo = '<button class="btn btn-danger" data-idfus="'+idfus+'" data-idapp="'+idapp+'" id="rechazoapp">Rechazar</button>';
                    break;
                case 'Rechazado':
                    btnAtender = '<button class="btn btn-primary" id="atencionapprechazada">Atender</button>';
                    btnRechazo = '<button class="btn btn-danger" id="atencionapprechazada">Rechazar</button>';
                    break;
                case 'Autorizado':
                case 'Parcialmente Autorizado':
                    btnRechazo = '<button class="btn btn-danger" data-idfus="'+idfus+'" data-idapp="'+idapp+'" id="rechazoapp">Rechazar</button>';
                    btnAtender = '<button class="btn btn-primary" data-idfus="'+idfus+'" data-idapp="'+idapp+'" id="atencionapp">Atender</button>';
                    break;
                case 'Atendido':
                    btnAtender = '<button class="btn btn-primary" id="atencionappatendido">Atender</button>';
                    btnRechazo = '<button class="btn btn-danger" id="atencionappatendido">Rechazar</button>';
                    break;
            }

            Swal({
                title: 'Atencion de FUS',
                // type: 'info',
                html:'<div class="container" style="margin-top: 10px;">'+
                    '<div class="form-group row">'+
                        '<div class="col-md-12">'+
                            '<div class="row">'+
                                '<div class="col-lg-6 text-center">'+
                                    btnRechazo+
                                '</div>'+
                                '<div class="col-lg-6 text-center">'+
                                    btnAtender+
                                '</div>'+
                            '</div>'+
                        '</div>'+
                    '</div>'+
                '</div>',
                showCloseButton: true,
                showCancelButton: false,
                showConfirmButton: false,
                focusConfirm: false,
                confirmButtonText: '',
                confirmButtonAriaLabel: '',
                // cancelButtonText: 'Cancelar Baja',
                allowOutsideClick: false,
            });
        });

        $(document).on("click", "#atencionapp", function(){
            var idapp = $(this).attr('data-idapp');
            var idfus = $(this).attr('data-idfus');

            $.ajaxSetup({
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: 'POST',
                data: { idapp: idapp, idfus: idfus, accion: 3 },
                // dataType: 'JSON',
                url: '{{ route("aplicacionapp") }}',
                async: false,
                beforeSend: function(){},
                complete: function(){}
            }).done(function(response){
                swal.fire(
                    'Validación',
                    'Se ha realizado la operación de manera satisfactoria.',
                    'success'
                ).then((result) => {
                    location.reload();
                });
            }).fail(function(response){}); 
        });
        $(document).on("click", "#rechazoapp", function(){
            var idapp = $(this).attr('data-idapp');
            var idfus = $(this).attr('data-idfus');
            swal.fire({
                title: 'Advertencia',
                input: 'text',
                inputAttributes: {
                    autocapitalize: 'off'
                },
                text: "¿Esta seguro de querer realizar la operación?, indique la causa.",
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: '#DC3545',
                confirmButtonColor: '#3085d6',
                allowOutsideClick: false,
                allowEscapeKey: false,
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'De acuerdo',
                inputValidator: (value) => {
                    if (!value) {
                        return 'Indique el motivo del rechazo!'
                    }
                },
                preConfirm:(observaciones) =>{
                    var data = { idapp: idapp, idfus: idfus, accion: 1, observaciones: observaciones };
                    return fetch('{{ route("aplicacionapp") }}', {
                        method: 'POST', // or 'PUT'
                        body: JSON.stringify(data), // data can be `string` or {object}!
                        headers:{
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                            'Content-Type': 'application/json'
                        }
                    }).then(response => {
                        if (!response.ok) {
                            throw new Error(response.statusText)
                        }
                        return response;
                    }).catch(error => {
                        Swal.showValidationMessage(
                            `Request failed: ${error}`
                        )
                    })
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.value) {
                    Swal.fire({
                    title: 'De acuerdo',
                    text: "La operación ha sido realizada con éxito.",
                    // title: `${result.value.ok}'s avatar`,
                    // imageUrl: result.value.avatar_url
                    }).then((result) => {
                        if (result.value) {
                            location.reload();   
                        }
                    });
                }
            });
        });
    });
</script>
@endpush