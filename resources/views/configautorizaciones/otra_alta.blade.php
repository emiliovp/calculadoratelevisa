@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Configuración de las Autorizaciones del FUS Electrónico') }}</div>

                <div class="card-body">
                    <form method="POST" action="">
                        @csrf
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="t_fus" class="col-md-6 col-form-label text-md-left">{{ __('Tipo de FUS') }}</label>
                                <select id="t_fus" class="form-control{{ $errors->has('t_fus') ? ' is-invalid' : '' }}" name="t_fus" required>
                                    <option value="">Seleccione...</option>
                                   @foreach($fuses as $row => $value)
                                   <option value="{{ $value['cve_fus'] }}">{{ $value['nombre'] }}</option>
                                   @endforeach
                                </select>

                                @if ($errors->has('t_fus'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('t_fus') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-12 col-form-label text-md-center">{{ __('Agregue uno o más autorizadores') }}</label>
                            <div class="col-md-6">
                                <button type="button" class="btn btn-success col-md-12" id="btnUser">Usuario Televisa</button>
                            </div>
                            <div class="col-md-6">
                                <button type="button" class="btn btn-success col-md-12" id="btnMesa">Mesa Control</button>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-primary col-md-12" id="guardar">Guardar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div style="margin-top:10px;" class="card">
                <div class="card-header">{{ __('Lista de autorizadores seleccionados') }}</div>
                <div class="card-body">
                    <table class="col-md-12 text-md-center">
                        <thead id="ttl-lista-autorizadores"></thead>
                        <tbody id="lista-autorizadores"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    $(document).ready(function() {
        $("form").submit(function(e){
            e.preventDefault();
        });
        autorizadores = new Array();
        $('#btnUser').click(function(){
            if($('#t_fus').val() != '') {
                formAutorizador('usuario');
            } else {
                swal(
                    'Validación',
                    'Debe selecionar un Tipo de FUS',
                    'warning'
                )
            }
        });
        $('#btnMesa').click(function(){
            if($('#t_fus').val() != '') {
                formAutorizador('mesacontrol');
            } else {
                swal(
                    'Validación',
                    'Debe selecionar un Tipo de FUS',
                    'warning'
                )
            }
        });

        $(document).on('change', '#msctl', function() {
            if($(this).val() != '') {
                $('#agregarAutorizador').prop("disabled", false);
            } else {
                $('#agregarAutorizador').prop("disabled", true);
            }
        });
        $(document).on("click", "#busqueda", function () {
            tipo = $('#tipoBusqueda').val();
            numemp = $('#numepm').val();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: 'POST',
                data: { tipo: tipo, valor: numemp },
                dataType: 'JSON',
                url: '{{ route("searchautorizador") }}',
                async: false,
                beforeSend: function(){
                    console.log("Cargando");
                },
                complete: function(){
                    console.log("Listo");
                }
            }).done(function(response){
                if(response.length > 0) {
                    html = '<table class="col-md-12" style="font-size: 15px;"><caption>Resultado de la busqueda</caption><thead><th>Nombre</th><th># Empleado</th></thead><tbody><td>'+response[0].displayname+'</td><td>'+response[0].employee_number+'</td></tbody></table>';
                    $('#agregarAutorizador').prop("disabled", false);
                    $('#idemp').val(response[0].id);
                    $('#numemp').val(response[0].employee_number);
                    $('#nombreemp').val(response[0].displayname);
                    $('#samaccountname').val(response[0].samaccountname);
                    if(response[0].email) {
                        $('#emailuser').val(response[0].email);
                    }
                } else {
                    html = '<table class="col-md-12" style="font-size: 15px;"><tbody><td>Sin resultados</td></tbody></table>';
                }
                $('#showBusqueda').html(html);
            }).fail(function(response){
            });
        });

        $(document).on("click", "#agregarAutorizador", function () {
            switch ($('#tipoBusqueda').val()) {
                case 'usuario':
                    autorizador = {idemp: $('#idemp').val(), numemp: $('#numemp').val(), nombreemp: $('#nombreemp').val(), t_fus: $('#t_fus').val(), email: $('#emailuser').val(), samaccountname: $('#samaccountname').val()};
                    
                    break;
            
                case 'mesacontrol':
                    msctl = $('#msctl option:selected').text().split(" - ");
                    idmsctl = $('#msctl option:selected').val();
                    
                    autorizador = {idmesa: idmsctl, idemp: $('#idemp').val(), numemp: msctl[2], nombreemp: msctl[0], t_fus: $('#t_fus').val(), email: msctl[1]};
                    break;
            }
            autorizadores.push(autorizador);
            
            ttlhtml = '<tr>';
            ttlhtml += '<th>Nombre</th>';
            ttlhtml += '<th>Tipo de FUS</th>';
            ttlhtml += '<th>E-mail</th>';
            ttlhtml += '<th># de Empleado</th>';
            ttlhtml += '</tr>';

            html = '';
            $.each(autorizadores, function(key, value) {
                html += '<tr>';
                html += '<td>'+value.nombreemp+'</td>';
                html += '<td>'+value.t_fus+'</td>';
                html += '<td>'+value.email+'</td>';
                html += '<td>'+value.numemp+'</td>';
                html += '</tr>';
            });
            
            $('#ttl-lista-autorizadores').html(ttlhtml);
            $('#lista-autorizadores').html(html);
            $('#tipoautorizacion').val('');
            $('#rolmodrep').val('');
            $('#aplicaciones').val('');
            
            swal.closeModal(); 
        });

        $('#guardar').click(function() {
            if(autorizadores.length == 0) {
                swal(
                    'Aviso',
                    'No se han agregado autorizadores',
                    'warning'
                )
            } else {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: 'POST',
                    data: { autorizadores: autorizadores },
                    dataType: 'JSON',
                    url: '{{ route("saveconfig") }}',
                    async: false,
                    beforeSend: function(){
                        console.log("Cargando");
                    },
                    complete: function(){
                        console.log("Listo");
                    }
                }).done(function(response){
                    if(response == true) {
                        swal(
                            'De acuerdo',
                            'La operación se ha realizado con éxito.',
                            'success'
                        );
                        $('#ttl-lista-autorizadores').html('');
                        $('#lista-autorizadores').html('');
                        autorizadores = new Array();
                    } else {
                        swal(
                            'Error',
                            'Se ha detectado un error al intentar guardar la información. Si el problema persiste, favor de contactar al admon. de la aplicación.',
                            'error'
                        );
                    }
                }).fail(function(response){
                });    
            }
        });
    });
    
    function formAutorizador(tipo) {
        switch (tipo) {
            case 'usuario':
                var titulo = 'Agregar Usuario Televisa como autorizador';
                var contentHtml = '<input type="hidden" name="emailuser" id="emailuser"/>'+
                    '<div class="col-md-8">'+
                        '<label for="numepm" class="col-lg-12 col-form-label text-left txt-bold"># de Empleado</label>'+
                        '<input type="number" min="1" id="numepm" placeholder="Ej. 2017853..." type="text" class="form-control" name="numepm" required autofocus>'+
                    '</div>'+
                    '<div class="col-md-4">'+
                        '<label class="col-lg-12 col-form-label">&nbsp;</label>'+
                        '<button class="btn btn-primary" id="busqueda" type="button">Buscar <i class="fas fa-search"></i></button>'+
                    '</div>';
                break;
            case 'mesacontrol':
                var titulo = 'Agregar Mesa de Control como autorizador';
                var contentHtml = '<label for="msctl" class="col-lg-12 col-form-label text-left txt-bold">Mesa de Control</label>'+
                '<select id="msctl" class="form-control" name="msctl" required>'+
                    '<option value="">Seleccione...</option>'+
                    @foreach($mesasdecontrol as $row => $value)
                        @php
                            $id = $value['id'];
                            $nombre = $value['name'].' - '.$value['email'].' - '.$value['no_empleado_labora'];
                        @endphp
                        '<option value="{{ $id }}">{{ $nombre }}</option>'+
                    @endforeach
                '</select>';
                break;
        }

        Swal({
            title: titulo,
            // type: 'info',
            html: '<div class="container" style="margin-top: 10px;">'+
                '<form method="post" action="">'+
                    '<div class="form-group row">'+
                        '<input type="hidden" id="tipoBusqueda" value="'+tipo+'">'+
                        contentHtml+
                    '</div>'+
                    '<div class="form-group row">'+
                        '<div class="col-md-12" id="showBusqueda">'+
                        '</div>'+
                    '</div>'+
                    '<div class="col-md-12 text-right">'+
                        '<label class="col-lg-12 col-form-label">&nbsp;</label>'+
                        '<a href="#" onclick="swal.closeModal(); return false;" class="btn btn-danger">Cancelar</a>&nbsp;&nbsp;'+
                        '<input type="hidden" id="idemp" value="">'+
                        '<input type="hidden" id="numemp" value="">'+
                        '<input type="hidden" id="nombreemp" value="">'+
                        '<input type="hidden" id="samaccountname" value="">'+
                        '<input disabled class="btn btn-primary" id="agregarAutorizador" type="button" value="Agregar">'+
                    '</div>'+
                '</form>'+
            '</div>',
            showCloseButton: true,
            showCancelButton: false,
            showConfirmButton: false,
            focusConfirm: false,
            confirmButtonText: 'Aplicar Baja',
            confirmButtonAriaLabel: 'Aplicar Baja',
            cancelButtonText: 'Cancelar Baja',
            allowOutsideClick: false,
        });
    }
</script>
@endpush