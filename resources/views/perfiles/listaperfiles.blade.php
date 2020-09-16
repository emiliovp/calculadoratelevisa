@extends('layouts.app')

@section('content')
<input type="hidden" id="modulo" value="admonperfiles" />
<!-- <input type="hidden" id="formAjax" value="1" /> -->
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Lista de perfiles
                </div>
                <div class="card-body">
                    <div class="from-group row">
                        <div class="col-lg-12 text-right" style="margin-bottom:15px;">
                            <a class="btn btn-warning" style="color:#FFFFFF;" href="{{ route('home') }}">Regresar</a>
                            <button class="btn btn-success" id="altaperfil">Alta</button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered" id="listaperfiles-table" name = "listaperfiles-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nombre del perfil</th>
                                    <th>Área</th>
                                    <th>Módulos asignados</th>
                                    <th>Acción</th>
                                    <th>Estatus</th>
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
@switch($msjOk)
    @case(1)
        swal({
            title: 'Administración de perfiles',
            text: 'La operación se ha realizado de manera satisfactoria',
            type: 'success',
            showCancelButton: false,
            confirmButtonColor: '#3085d6',
            allowOutsideClick: false,
            allowEscapeKey: false,
            confirmButtonText: 'De acuerdo'
        });
    @break
       
@endswitch
var buttonCommon = {
        exportOptions: {
            columns: [0,1,2],
            format: {
                body: function (data, row, column, node) {
                    if (column == 3) {
                        return $(data).find("option:selected").text()
                    } else return data
                }
            },
        }
    };
    var table = $('#listaperfiles-table').DataTable({
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
        ajax: '{!! route("anydataper") !!}',
        columns: [
            {data: 'id', name: 'id'},  
            {data: 'perfil', name: 'perfil'},
            {data: 'area', name: 'area'},
            {data: 'modulos_acceso', name: 'modulos_acceso'}, 
            {
                render: function (data,type,row){
                    var html = '';
                    html = '<div class="row">'+
                            '<div class="col-md-12">'+
                            '<a href="{{url("/perfiles/editarperfil")}}/'+row.id+'" class="btn btn-primary btn-block" id="editarperfil" name="editarperfil" data-id="'+row.id+'">Editar</a>'+
                            '</div>'+
                           '</div>';

                    return html;
                }
            },
            {
                render: function (data,type,row){
                    var html = '';
                    if (row.estado =='Activo') {
                        html = '<div class="row">'+
                                '<div class="col-md-12">'+
                                    '<button class="btn btn-danger btn-block mov-perfil" id="bajaperfil" name="bajaperfil" data-movimiento="2" data-idperfil="'+row.id+'">Desactivar</button>'+
                                '</div>'+
                            '</div>';
                    }else{
                        html = '<div class="row">'+
                                '<div class="col-md-12">'+
                                    '<button class="btn btn-primary btn-block mov-perfil" id="bajaperfil" name="bajaperfil" data-movimiento="1" data-idperfil="'+row.id+'">Activar</button>'+
                                '</div>'+
                            '</div>';
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
                extend: "pdfHtml5",
                orientation: "landscape",
                pageSize: "LEGAL"
            })
        ]
    });
    $('#altaperfil').click(function()
    {
        var url = '{!!route('mewperfil')!!}';
        $( location).attr("href",url);
    });
    $(document).on("click", ".mov-perfil", function(){
        var mov = $(this).attr("data-movimiento");
        $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
        });
        if (mov == 2) {
            var tittle = 'Bloqueo';
        }else{
            var tittle = 'Desbloqueo';
        }
        /*if (mov == "bloquear") {
            var tittle = 'Edición';
            var id = $(this).attr("data-idperfil");
            var ajax = $.ajax({
                type: 'POST',
                data: {id:id},
                url: ' route("updperfiles") ',
                async: false,
                beforeSend: function(){
                    mostrarLoading();
                },
                complete: function(){
                    ocultarLoading();
                }
            });
        }else{*/
            var id = $(this).attr("data-idperfil");
            var ajax = $.ajax({
                type: 'POST',
                data: {id:id,tipo:mov},
                url: '{{ route("bloqueoperfiles") }}',
                async: false,
                beforeSend: function(){
                    mostrarLoading();
                },
                complete: function(){
                    ocultarLoading();
                }
            });
        //}
        ajax.done(function(response){
            if(response == 1) {
                table.ajax.reload();
                swal(
                    tittle,
                    'La operación se ha realizado con éxito',
                    'success'
                )
            } else if(response == false) {
                swal(
                    'Error',
                    'La operación no pudo ser realizada',
                    'error'
                )
            }
        });
    });
</script>
@endpush