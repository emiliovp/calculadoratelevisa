@extends('layouts.app')
@section('content')
<input type="hidden" id="modulo" value="reporteservicios"/>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Reporte de fuses 
                </div>
                <div class="card-body">
                    <div class="from-group row">
                        <div class="col-lg-12 text-right" style="margin-bottom:15px;">
                            <a class="btn btn-warning" style="color:#FFFFFF;" href="{{ route('fus_lista') }}">Regresar</a>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered" id="reporteFus-table" name = "reporteFus-table">
                            <thead>
                                <tr>
                                <th>Folio</th>
                                <th>Fecha de creación</th>
                                <th>Tipo de FUS</th>
                                <th>Correo del jefe</th>
                                <th>Autorización</th>
                                <th>Fecha de autorización</th>
                                <th>Coreo del autorizador</th>
                                <th>Autorización</th>
                                <th>Fecha de autorización</th>
                                <th>Estado del FUS</th>
                                <th>Fecha de atención</th>
                                <th>Ver FUS</th>
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
var buttonCommon = {
        exportOptions: {
            columns: [0,1,2,3,4,5,6,7,8,9,10],
            format: {
                body: function (data, row, column, node) {
                    // if it is select
                    if (column == 10) {
                        return $(data).find("option:selected").text()
                    } else return data
                }
            },
        }
    };
    var table = $('#reporteFus-table').DataTable({
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
        ajax: '{!! route("listservicios") !!}',
        columns: [
            {data: 'Folio', name: 'Folio'},
            {data: 'fecha_creacion', name: 'fecha_creacion'}, 
            {data: 'tipo_fus', name: 'tipo_fus'}, 
            {data: 'correo_jefe', name: 'correo_jefe'}, 
            {data: 'EstadoJefe', name: 'EstadoJefe'}, 
            {data: 'fecha_auto_jefe', name: 'fecha_auto_jefe'},
            {data: 'aut_correo', name: 'aut_correo'},
            {data: 'EstadoAutorizador', name: 'EstadoAutorizador'},
            {data: 'fecha_auto_autorizador', name: 'fecha_auto_autorizador'},
            {data: 'EstadoFUS', name: 'EstadoFUS'},
            {data: 'fecha_atencion', name: 'fecha_atencion'},
            {
                render: function (data,type,row){
                    var url = "{{ url('/fus/showfus/') }}";
                    url= url +'/'+ row.Folio;
                    var html = '<div class="row">';
                        html += '<div class="col-md-12 text-center"><a class="btn btn-primary" href="'+ url+'">Ver FUS <i class="fas fa-edit"></i></a></div>';
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
                extend: "pdfHtml5",
                orientation: "landscape",
                pageSize: "LEGAL"
            })
        ]
    });
</script>
@endpush