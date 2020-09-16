@extends('layouts.app')

@section('content')
<input type="hidden" id="modulo" value="confapp" />
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Lista de Configuraciones de Autorización para FUS
                </div>
                <div class="card-body">
                    <div class="from-group row">
                        <div class="col-lg-12 text-right" style="margin-bottom:15px;">
                            <a class="btn btn-warning" style="color:#FFFFFF;" href="{{ route('home') }}">Regresar</a>
                            <button class="btn btn-success" id="altaAplicaciones">Alta de configuración</button>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered" id="aplicaciones-table" name = "aplicaciones-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Descripción</th>
                                    <th>Aplicación</th>
                                    <th>Tipo de autorización</th>
                                    <th>Fecha de creación</th>
                                    <th>Correo</th>
                                    <th>Número de empleado</th>
                                    <th>Nombre</th>
                                    <th>Usuario de red</th>
                                    <th>Archivo Anexo</th>
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
        ajax: '{!! route("listconfig") !!}',
        columns: [
            {data: 'idFusResp', name: 'idFusResp'}, 
            {data: 'rol_mod_rep', name: 'rol_mod_rep'}, 
            {data: 'alias_instancia', name: 'alias_instancia'}, 
            {data: 'autorizacion', name: 'autorizacion'}, 
            {data: 'fecha_creacion', name: 'fecha_creacion'},
            {data: 'correo', name: 'correo'}, 
            {data: 'no_empleado_labora', name: 'no_empleado_labora'}, 
            {data: 'nombre_labora', name: 'nombre_labora'}, 
            {data: 'usuario_red', name: 'usuario_red'}, 
            {
                render: function (data,type,row){
                    var html = '';
                    if(row.path != null) {
                        var path = "{{ url('/descargas/anexos') }}/"+row.path;
                        html = '<div class="row">'+
                                '<div class="col-md-12">'+
                                    '<a href="'+path+'">Clic para descargar</a>'+
                                '</div>'+
                            '</div>';
                    } else {
                        html = '<div class="row">'+
                                '<div class="col-md-12">'+
                                '</div>'+
                            '</div>';
                    }

                    return html;
                }
            },
            {
                render: function (data,type,row){
                    var html = '';
                    html = '<div class="row">'+
                            '<div class="col-md-12">'+
                                '<button class="btn btn-danger btn-block btn-baja" id="bajaconf" name="bajaconf" data-total="'+row.total+'" data-idconfig="'+row.idFusResp+'">Eliminar</button>'+
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
                extend: "pdfHtml5",
                orientation: "landscape",
                pageSize: "LEGAL"
            })
        ]
    });

    $(document).on("click", "#bajaconf", function(){
        var id = $(this).attr("data-idconfig");
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: 'POST',
            data: {id: id},
            url: '{{ route("totalbyresponsabilidad") }}',
            async: false,
            beforeSend: function(){

            },
            complete: function(){
                // $("#loading_pes").addClass("loading_pes_hide");
                // ocultarLoadingMail();
            }
        }).done(function(response){
            var msj = "";
            
            if(response == 1) {
                msj = "Este es el único agente configurado. Si es eliminado, no se mostrará la opción en el FUS-e";
            } else {
                msj = "¡El registro se eliminara de su lista!";
            }

            swal({
                title: '¿Esta seguro?',
                text: msj,
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar'
            }).then((result) => {  
                if (result.value) {
                    eliminar(id);
                }
            });
        });
    });
$('#altaAplicaciones').click(function()
{
    var url = '{!!route('createconfigauto')!!}';
    $( location).attr("href",url);
});

function eliminar(id) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type: 'POST',
        data: {id: id},
        url: '{{ route("bajaconf") }}',
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
</script>
@endpush