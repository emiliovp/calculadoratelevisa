@extends('layouts.app')
@section('content')
<style>
    .ui-autocomplete {
        z-index: 9999;
    }
</style>

<div class="container-fluid">
<input type="hidden" id="modulo" name="modulo" value="usuarios">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    Lista de Usuario activos.
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-lg-12 text-right">
                            <a class="btn btn-warning" style="color:#FFFFFF;" href="{{ route('home') }}">Regresar</a>
                            <button class="btn btn-success" id="altaUsr">Alta de Usuario</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 table-responsive">
                            <table class="table table-bordered" id="fus-table">
                                <thead>
                                    <tr>
                                        <th>Número de empleado</th>
                                        <!-- <th>Nombre</th> -->
                                        <th>Usuario de red</th>
                                        <th>Área</th>
                                        <th>Eliminar</th>
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
@if($alat == 1)
Swal.fire({
    icon: 'succes',
    title: 'Operación exitosa',
    text: 'El usuario ha sido dado de alta'
    // footer: '<a href>Why do I have this issue?</a>'
}); 
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
        ajax: '{!! route("listusers") !!}',
        columns: [
            {data: 'cal_num_employee', name: 'cal_num_employee'}, 
            // {data: 'displayname',  name: 'displayname'},
            {data: 'cal_user_red',  name: 'cal_user_red'},
            {data: 'tipo_user',  name: 'tipo_user'},
            {
                render: function (data,type,row){
                    var html = '';
                    html = '<div class="row">'+
                            '<div class="col-md-12">'+
                                '<button class="btn btn-danger btn-block btn-baja" id="bajausr" name="bajaconf" data-idconfig="'+row.id+'">Baja</button>'+
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
    $(document).on("click", "#bajausr", function(){
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
                    url: '{{ route("bajausr") }}',
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
$('#altaUsr').click(function()
{
    var url = '{!!route('createnewusr')!!}';
    $( location).attr("href",url);
});
</script>
@endpush