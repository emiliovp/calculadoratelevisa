@extends('layouts.app')

@section('content')
<input type="hidden" id="modulo" value="tcsaplicacion" />
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Lista de Configuraciones de Autorización para FUS
                </div>
                <div class="card-body">
                    <div class="from-group row">
                        <div class="col-lg-12 text-right" style="margin-bottom:15px;">
                            <button type="button" class="btn btn-warning" id="regresar">{{ __('Regresar') }}</button>
                            <button class="btn btn-success" id="altaConfig">Alta de autorizadores</button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered" id="configuraciones-table" name = "configuraciones-table">
                            <thead>
                                <tr>
                                    <th>Número de empleado</th>
                                    <th>Nombre</th>
                                    <th>Correo</th>
                                    <th>FUS asiganado</th>
                                    <th>Baja</th>
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
    var table = $('#configuraciones-table').DataTable({
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
        ajax: '{!! route("dataconfig") !!}',
        columns: [
            {data: 'numero_empleado', name: 'numero_empleado'}, 
            {data: 'nombre_emp', name: 'nombre_emp'}, 
        {   data: 'correo', name: 'correo'}, 
            {data: 'fus', name: 'fus'}, 
            {
                render: function (data,type,row){
                    var html = '';
                    html = '<div class="row">'+
                            '<div class="col-md-12">'+
                                '<button class="btn btn-danger btn-block btn-baja" id="bajaconf" name="bajaconf" data-idconfig="'+row.id_config+'">Baja</button>'+
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
$('#altaConfig').click(function()
{
    var url = '{!!route('createconfigautooper')!!}';
    $( location).attr("href",url);
});
$(document).on("click", "#bajaconf", function(){
        var id = $(this).attr("data-idconfig");
        swal({
            title: '¿Esta seguro?',
            text: "¡El registro se eliminara de su lista!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Aceptar'
        }).then((result) => {  
            if (result.value) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: 'POST',
                    data: {id: id},
                    url: '{{ route("bajaconfig") }}',
                    async: false,
                    beforeSend: function(){
                
                    },
                    complete: function(){
                        // $("#loading_pes").addClass("loading_pes_hide");
                        // ocultarLoadingMail();
                    }
                }).done(function(response){
                    if(response == 1) {
                        table.ajax.reload();
                        swal(
                            'Eliminacion',
                            'La operación se ha realizado con éxito',
                            'success'
                        )
                    } else if(response == "false") {
                        swal(
                            'Error',
                            'La operación no pudo ser realizada',
                            'error'
                        )
                    }
                });
            }
        });
    });
</script>
@endpush