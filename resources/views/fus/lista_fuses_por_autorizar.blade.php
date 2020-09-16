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
                    Lista de FUS-e Pendientes por autorizar
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-lg-12 text-right">
                            <a class="btn btn-warning" style="color:#FFFFFF;" href="{{ route('fus_lista') }}">Regresar</a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 table-responsive">
                            <table class="table table-bordered" id="fus-table">
                                <thead>
                                    <tr>
                                        <th>Folio</th>
                                        <th>Tipo de FUS</th>
                                        <th>Fecha solicitud</th>
                                        <th>Estado</th>
                                        <th>Acción</th>
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
    $(document).ready(function(){
        @switch($msjOk)
            @case(1)
                    swal({
                        title: 'Autorizaciones',
                        text: 'La operación se ha realizado de manera satisfactoria',
                        type: 'success',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        confirmButtonText: 'De acuerdo'
                    });
                @break
            @case(2)
                    swal({
                        title: 'Autorizaciones',
                        text: 'La operación ya ha sido concluida.',
                        type: 'success',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        confirmButtonText: 'De acuerdo'
                    });
                @break
        @endswitch
    });
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
        ajax: '{!! route("listafusesporautorizar.data") !!}',
        columns: [
            {data: 'folio_fus', name: 'folio_fus'}, 
            {data: 'tipo_fus',  name: 'tipo_fus'},
            {data: 'fecha_fus', name: 'fecha_fus'},
            {data: 'estadofus', name: 'estadofus'},
            {
                render: function (data,type,row){
                    var url = '{{url("/fus/fusarevisar")}}/'+row.folio_fus;
                    var html = '<div class="row">';
                        html += '<div class="col-md-12 text-center"><a class="btn btn-primary" href="'+url+'">Revisar <i class="fas fa-eye"></i></a></div>';
                        html += '</div>';
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
</script>
@endpush