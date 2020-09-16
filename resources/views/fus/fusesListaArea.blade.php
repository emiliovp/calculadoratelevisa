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
                    Lista de FUSES.
                </div>
                <div class="card-body">
                    <input type="hidden" value="{{ $confirm }}" id="conf">
                    <div class="form-group row">
                        <div class="col-lg-6 text-left">
                            <a href="{{ route('fus_lista') }}">{{ __('Ver Lista de FUS') }}</a>
                        </div>
                        <div class="col-lg-6 text-right">
                            <a class="btn btn-warning" style="color:#FFFFFF;" href="{{ route('fus_lista') }}">Regresar</a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 table-responsive">
                            <table class="table table-bordered" id="fus-table">
                                <thead>
                                    <tr>
                                        <th>FOLIO</th>
                                        <th>Tipo de FUS</th>
                                        <th>Aplicaciones</th>
                                        <th>Estado</th>
                                        <th>Fecha solicitud</th>
                                        <th>Ver FUS</th>
                                        <!-- @ if($empleado[0]['tipo_user'] != 0) -->
                                        <th>Atender FUS</th>
                                        <!-- @ endif -->
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
  'Se ha dado de alta un nuevo FUS',
  'success'
)
@endif
    var buttonCommon = {
        exportOptions: {
            columns: [0,1,2,3,4],
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
        ajax: '{!! route("fus.data2") !!}',
        columns: [
            {data: 'folio_fus', name: 'folio_fus'}, 
            {data: 'tipo_fus',  name: 'tipo_fus'},
            {data: 'apps',    name: 'apps'},
            {data: 'estado',    name: 'estado'},
            {data: 'fecha_fus',    name: 'fecha_fus'},
            {
                render: function (data,type,row){
                    var url = "{{ url('/fus/showfus/') }}";
                    url= url +'/'+ row.folio_fus;
                    var html = '<div class="row">';
                        html += '<div class="col-md-12 text-center"><a class="btn btn-primary" href="'+ url+'">Ver FUS <i class="fas fa-eye"></i></a></div>';
                        html += '</div>';
                    return html;
                }
            },
            {
                render: function (data, type, row) {
                    var html = '<div class="row"></div>';
                    if ( row.estado == 'Atendido') {
                        html = '<div class="row"><div class="col-lg-12 text-center"><label>Atendido</label></div></div>'; 
                    } else{
                        @if($empleado == 'CAT')
                            html = '<div class="row"><div class="col-lg-12 text-center"><button type="button" class="btn btn-primary" data-cve="' + row.clave_atencion + '" data-id="' + row.folio_fus + '" id="atFUS">Atender FUS <i class="fas fa-concierge-bell"></i></button></div></div>';
                        @else
                            var urlAppsFus = "{{ url('/FusGeneral/appsfuses/') }}/"+row.folio_fus;
                            html = '<div class="row"><div class="col-lg-12 text-center"><a href="'+urlAppsFus+'">Atender Aplicaciones</a></div></div>';
                        @endif
                    }

                    return html;
                }
            },
        ],
        dom: 'Blfrtip',
        columnDefs: [ {
            targets: [5, 6],
            orderable: false
        }],
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
    
$(document).on("click", "#atFUS", function(){
    var id = $(this).data("id");
    var cve = $(this).data("cve");
    switch (cve) {
        case 0:
            cve = 5;
            break;
        case 1:
            cve = 6;
            break;
        case 2:
            cve = 7;
            break;
        case 3:
            cve = 8;
            break;
        default:
            break;
    }
        Swal({
            title: 'Atención de FUS',
            // type: 'info',
            html:'<div class="container" style="margin-top: 10px;">'+
                    '<div class="form-group row">'+
                        '<div class="col-md-12">'+
                            '<div class="row">'+
                                '<div class="col-lg-6 text-center">'+
                                    '<button data-cve="' +cve+ '" data-id="' +id + '" class="btn btn-danger" id="rechazofus">Rechazar</button>'+
                                '</div>'+
                                '<div class="col-lg-6 text-center">'+
                                    '<button data-cve="'+cve+'" data-id="' +id + '" class="btn btn-primary" id="autoizarfus">Atender</button>'+
                                '</div>'+
                            '</div>'+
                            '</br>'+
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
$(document).on("click", "#autoizarfus", function(){
    var id = $(this).data("id");
    var jefeOAut = $(this).data("cve");
    var idRelConf = null;
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type: 'POST',
        data: { id: id, jefeOAut: jefeOAut, tipoAccion: 1, idRelConf: idRelConf },
        // dataType: 'JSON',
        url: '{{ route("autorizacionjefe") }}',
        async: false,
        beforeSend: function(){
            // console.log("Cargando");
        },
        complete: function(){
            // console.log("Listo");
        }
    }).done(function(response){
        swal.fire(
            'Validación',
            'Se a realizado la operación de manera satisfactoria.',
            'success'
        ).then((result) => {
            location.reload();   
        });
    }).fail(function(response){
        // location.reload();
    }); 
});
$(document).on("click", "#rechazofus", function(){
    var id = $(this).data("id");
    var jefeOAut = $(this).data("cve");
    var idRelConf = null;
        swal.fire({
            title: 'Advertencia',
            input: 'text',
            inputAttributes: {
                autocapitalize: 'off'
            },
            text: "¿Esta seguro de querer rechazar el FUS# "+id+" ?, indique la causa.",
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
                    var data = {id: id, jefeOAut: jefeOAut, tipoAccion: 2, observaciones: observaciones, idRelConf: idRelConf};  
                    
                    return fetch('{{ route("rechazofusjefe") }}', {
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
</script>
@endpush