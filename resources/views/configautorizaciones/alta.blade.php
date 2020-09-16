@extends('layouts.app')

@section('content')
<style>
    #showErrorRepetirCategoria{
        font-size: 12px;
        color: red;
    }
    .swal2-popup {
        width: 66em;
    }
    .asteriscorojo {
        color: red;
    }
    #msjErrorAnexo, #msjErrorAnexoSize {
        display: none;
    }
    #msjErrorAnexo p, #msjErrorAnexoSize p {
        color: red;
        font-size: 12px;
    }
</style>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Configuración de las Autorizaciones del FUS Electrónico') }}</div>

                <div class="card-body">
                    <form enctype="multipart/form-data" method="POST" action="">
                        @csrf
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="aplicaciones" class="col-md-6 col-form-label txt-bold text-md-left">{{ __('Aplicación') }}<label class="asteriscorojo">*</label></label>
                                <select id="aplicaciones" class="form-control{{ $errors->has('aplicaciones') ? ' is-invalid' : '' }}" name="aplicaciones" required>
                                    <option value="">Seleccione...</option>
                                    @foreach($aplicaciones AS $index => $value)
                                        <option value='{{$value["id"]}}'>{{$value["alias"]}}</option>
                                    @endforeach
                                </select>

                                @if ($errors->has('aplicaciones'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('aplicaciones') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <label for="tipoautorizacion" class="col-md-6 col-form-label txt-bold text-md-left">{{ __('Tipo') }}<label class="asteriscorojo">*</label></label>
                                <select id="tipoautorizacion" class="form-control{{ $errors->has('tipoautorizacion') ? ' is-invalid' : '' }}" name="tipoautorizacion" required>
                                    <option value="">Seleccione...</option>
                                    <option value="12">Administración</option>
                                    <option value="0">Aplicación</option>
                                    <option value="14">Fondo</option>
                                    <option value="1">Grupo</option>
                                    <option value="2">Reporte</option>
                                    <option value="3">Responsabilidad</option>
                                    <option value="4">Rol</option>
                                    <option value="5">Otro (En caso de un único autorizador)</option>
                                    <option value="6">Perfil</option>
                                    <option value="15">Perfil SOS</option>
                                    <option value="13">Portafolio</option>
                                    <option value="7">Función</option>
                                    <option value="8">Empresa</option>
                                    <option value="9">Instancia</option>
                                    <option value="10">Área</option>
                                    <option value="11">Permiso</option>
                                </select>

                                @if ($errors->has('tipoautorizacion'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('tipoautorizacion') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="rolmodrep" class="col-md-12 col-form-label txt-bold text-md-left">{{ __('Descripción (Rol/Módulo/Reporte/Responsabilidad/Aplicación)') }}<label class="asteriscorojo">*</label></label>
                                <input id="rolmodrep" type="text" class="form-control{{ $errors->has('rolmodrep') ? ' is-invalid' : '' }}" data-type="rolmodrep" name="rolmodrep" value="{{ old('rolmodrep') }}" required>

                                @if ($errors->has('rolmodrep'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('rolmodrep') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <!-- Selección de opciones de catálogo -->
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="catalogos" class="col-lg-12 col-form-label text-left txt-bold">Catálogos</label>
                                <select class="form-control" id="catalogos" name="catalogos">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                            <div class="col-md-12">
                                <label for="subopciones" class="col-lg-12 col-form-label text-left"><strong>Opciones</strong> <a id="selectAll" href="#">Seleccionar Todo</a></label>
                                <select class="form-control" id="subopciones" multiple name="subopciones">
                                    <option value="">Seleccione un catálogo...</option>
                                </select>
                            </div>
                            <!-- <div class="col-md-2">
                                <label>&nbsp;</label>
                                <button type="button" id="addCatalogosAConfiguracion" class="form-control btn btn-primary"><i class="fas fa-plus"></i></button>
                            </div> -->
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12" id="showErrorRepetirCategoria">
                            </div>
                            <div class="col-md-12" id="showCategoriasAgregadas">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-12 col-form-label text-md-center">{{ __('Agregue uno o más autorizadores') }}</label>
                            <div class="col-md-6">
                                <button type="button" class="btn btn-success col-md-12" id="btnMesa">Agregar Mesa Control</button>
                            </div>
                            <div class="col-md-6">
                                <button type="button" class="btn btn-success col-md-12" id="btnUser">Agregar Autorizador adicional / Ratificador</button>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                            <a href="{{ route('listappsconfig') }}" class="btn btn-warning btn-block" style="color:#FFFFFF;">{{ __('Regresar') }}</a>
                            </div>
                            <div class="col-md-6">
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
    var catalogos;

    var arregloOpcionesConfiguraciones = new Array();
    $(document).ready(function() {
        $("form").submit(function(e){
            e.preventDefault();
        });
        // Carga de anexos
        $(document).on("change", ".car-arch", function() {
            var fileName = this.files.length;
            select = $(this).attr('data-type');
            var claveArch = select.split('_');
            var archivo = document.getElementsByClassName("appselect").length;
            if (claveArch[1] == 0) {
                claveArch[1] = "";
                app = $("#id_app_fus"+claveArch[1]).val();
            }
            
            if (fileName != 0){
                var size = this.files[0].size;
                if (size < 4000000){
                    if (claveArch[1] == 0) {
                        var element = document.getElementById("id_app_fus"+claveArch[1]);
                        element.classList.add("campo-requerido");
                    }
                    $("#msjErrorAnexo").css("display", "none");
                    $("#msjErrorAnexoSize").css("display", "none");
                } else {
                    $("#msjErrorAnexoSize").css("display", "block");
                    $(this).val('');
                }
            }
        });
        // Fin de carga de anexos
        autorizadores = new Array();
        autorizador = new Array();// '';
        /**Inicia autocomplete */
        $(document).on('focus','#rolmodrep', function(){
            type = $(this).data('type');
            if(type =='rolmodrep')autoType='rol';
            var idapp = $("#aplicaciones").val();
            $(this).autocomplete({
                source: function(request, response)
                {
                    $.ajax({
                        url: "{{ route('autocomplete') }}",
                        dataType: "json",
                        data:{ term: request.term, idapp: idapp }
                        ,success:function(data){
                            var array=$.map(data, function(item){
                                if (item !== 'No se encontro el registro') {
                                    var response = {
                                        label: item.rol_mod_rep,
                                        value: item.rol_mod_rep,
                                        data: item.rol_mod_rep
                                    }
                                }else {
                                    response = {
                                        label: item,
                                        value: item,
                                        data: "fail"
                                    }
                                }
                                return response;
                            });
                            response(array);
                        }
                    });
                },
                select: function( event, ui){
                    var data = ui.item.data;
                    if(data === "fail") {
                        $('#rolmodrep').val('');
                        event.stopImmediatePropagation();
                        event.preventDefault();
                    }
                }
            });
        });
        /**Termina autocomplete */
        $('#btnUser').click(function(){
            if($('#rolmodrep').val() != '' && $('#aplicaciones').val() != '') {
                formAutorizador('usuario');
            } else {
                swal(
                    'Validación',
                    'Los campos Aplicación y Rol/Mod/Rep no deben estar vacíos',
                    'warning'
                )
            }
        });
        $('#btnMesa').click(function(){
            if($('#rolmodrep').val() != '' && $('#aplicaciones').val() != '') {
                formAutorizador('mesacontrol');
            } else {
                swal(
                    'Validación',
                    'Los campos Aplicación y Rol/Mod/Rep no deben estar vacíos',
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

        $(document).on('change', '#tipo', function() {
            if(
                $(this).val() != ''
            ) {
                $('#agregarAutorizador').prop("disabled", false);
            } else {
                $('#agregarAutorizador').prop("disabled", true);
            }
        });

        $(document).on("click", "#busqueda", function () {
            mostrarLoading();
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
                // async: false,
                beforeSend: function(){
                    // 
                },
                complete: function(){
                    // 
                }
            }).done(function(response){
                if(response.length > 0) {
                    if(
                        response[0].id.length > 0 &&
                        response[0].employee_number.length > 0 &&
                        response[0].displayname.length > 0 &&
                        response[0].samaccountname.length > 0 &&
                        response[0].email.length > 0 ||
                        
                        response[0].id != null &&
                        response[0].employee_number != null &&
                        response[0].displayname != null &&
                        response[0].samaccountname != null &&
                        response[0].email != null
                    ) {
                        var filterEmail = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                        if (filterEmail.test(response[0].email)) {
                            html = '<table class="col-md-12" style="font-size: 15px;"><caption>Resultado de la busqueda</caption><thead><th>Nombre</th><th># Empleado</th></thead><tbody><td>'+response[0].displayname+'</td><td>'+response[0].employee_number+'</td></tbody></table>';
                        
                            if($('#tipo').val() != '') {
                                $('#agregarAutorizador').prop("disabled", false);
                            }

                            $('#idemp').val(response[0].id);
                            $('#numemp').val(response[0].employee_number);
                            $('#nombreemp').val(response[0].displayname);
                            $('#samaccountname').val(response[0].samaccountname);
                            if(response[0].email) {
                                $('#emailuser').val(response[0].email);
                            }    
                        } else {
                            html = '<table class="col-md-12" style="font-size: 15px;"><tbody><td>El usuario no cuenta con una cuenta de correo.</td></tbody></table>';    
                        }
                    } else {
                        html = '<table class="col-md-12" style="font-size: 15px;"><tbody><td>El usuario no cuenta con todos los datos en el directorio activo.</td></tbody></table>';
                    }
                } else {
                    html = '<table class="col-md-12" style="font-size: 15px;"><tbody><td>Sin resultados</td></tbody></table>';
                }
                $('#showBusqueda').html(html);
                ocultarLoading();
            }).fail(function(response){
            });
        });

        function compararCatalogosRepetidos(actuales, valorABuscar) {
            actuales = actuales.split(',');
            if(Array.isArray(actuales) == true) {
                if(actuales.includes(valorABuscar) == 1) {
                    return false;
                }
            } else {
                if(valorABuscar == actuales) {
                    return false;
                }
            }

            return true;
        }
        
        $(document).on("click", "#addCatalogosAConfiguracion, #btnMesa, #btnUser", function () {
            var actualesJson = $('#relacionconconfiguracionesJSON').val();
            var samaccountname = $('#samaccountname').val();
            var numemp = $('#numemp').val();
            var msctl = $('#msctl').val();
            var msctlTxt = $('#msctl option:selected').text();
            var catalogoTxt = $('#catalogos option:selected').text();
            var catalogoId = $('#catalogos').val();
            var subOpcionTxt = $('#subopciones option:selected').text();

            if(
                $('#subopciones').val() == undefined ||
                $('#catalogos').val() == undefined ||
                $('#subopciones').val() == null ||
                $('#catalogos').val() == null ||
                $('#subopciones').val() == "" ||
                $('#catalogos').val() == ""
            ) {
                return false;
            }

            if(actualesJson == "") {
                var datosExtra = new Object();
                datosExtra.noEmp = numemp;
                datosExtra.samaccountname = samaccountname;
                datosExtra.catalogo = catalogoTxt;
                datosExtra.msctl = msctl;
                datosExtra.msctlTxt = msctlTxt;
                datosExtra.catalogoSubOpciones = $('#subopciones').val();
                datosExtra.catalogoSubOpcionesTxt = subOpcionTxt;
                arregloOpcionesConfiguraciones['"'+catalogoId+'"'] = datosExtra;
                arregloOpcionesConfiguracionesObj = Object.assign({}, arregloOpcionesConfiguraciones);
                $('#relacionconconfiguracionesJSON').val(JSON.stringify(arregloOpcionesConfiguracionesObj));

                $('#showErrorRepetirCategoria').html('');

            } else {
                actualesJson = JSON.parse(actualesJson);
                var conjuntoAValidar;
                $.each(actualesJson, function(key, value) {
                    if('"'+catalogoId+'"' == key) {
                        conjuntoAValidar = value;
                    }
                });

                if(
                    conjuntoAValidar != undefined
                ) {
                    if(compararCatalogosRepetidos(conjuntoAValidar.catalogoSubOpciones, $('#subopciones').val()) == true) {
                        var datosExtra = new Object();
                        datosExtra.noEmp = numemp;
                        datosExtra.samaccountname = samaccountname;
                        datosExtra.catalogo = catalogoTxt;
                        datosExtra.msctl = msctl;
                        datosExtra.msctlTxt = msctlTxt;
                        datosExtra.catalogoSubOpciones = conjuntoAValidar.catalogoSubOpciones+','+$('#subopciones').val();
                        datosExtra.catalogoSubOpcionesTxt = conjuntoAValidar.catalogoSubOpcionesTxt+', '+subOpcionTxt;
                        arregloOpcionesConfiguraciones['"'+catalogoId+'"'] = datosExtra;
                        arregloOpcionesConfiguracionesObj = Object.assign({}, arregloOpcionesConfiguraciones);
                        $('#relacionconconfiguracionesJSON').val(JSON.stringify(arregloOpcionesConfiguracionesObj));
                        $('#showErrorRepetirCategoria').html('');
                    } else {
                        $('#showErrorRepetirCategoria').html('La selección ya se encuentra agregada.');
                    }
                } else {
                    var datosExtra = new Object();
                    datosExtra.noEmp = numemp;
                    datosExtra.samaccountname = samaccountname;
                    datosExtra.catalogo = catalogoTxt;
                    datosExtra.msctl = msctl;
                    datosExtra.msctlTxt = msctlTxt;
                    datosExtra.catalogoSubOpciones = $('#subopciones').val();
                    datosExtra.catalogoSubOpcionesTxt = subOpcionTxt;
                    arregloOpcionesConfiguraciones['"'+catalogoId+'"'] = datosExtra;
                    arregloOpcionesConfiguracionesObj = Object.assign({}, arregloOpcionesConfiguraciones);
                    $('#relacionconconfiguracionesJSON').val(JSON.stringify(arregloOpcionesConfiguracionesObj));
                    $('#showErrorRepetirCategoria').html('');
                }
            }
        });
        
        $(document).on("change", "#catalogos", function () {
            var id = $(this).val();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: 'POST',
                data: { id: id },
                dataType: 'JSON',
                url: '{{ route("getopcionescatalogos") }}',
                // async: false,
                beforeSend: function(){},
                complete: function(){}
            }).done(function(response){
                var optSelect = '';
                $.each(response, function( index, value ) {
                    optSelect += '<option value="'+value['id']+'">'+value['cat_op_descripcion']+'</option>';
                });
                $('#subopciones').html(optSelect);
                $('#subopciones option').prop('selected', true);
            }).fail(function(response){
            });
        });
        $("#selectAll").click(function(){
            $('#subopciones option').prop('selected', true);
        });
        $(document).on("change", "#aplicaciones", function () {
            mostrarLoading();
            var id = $(this).val();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: 'POST',
                data: { id: id },
                dataType: 'JSON',
                url: '{{ route("getcatalogos") }}',
                // async: false,
                beforeSend: function(){},
                complete: function(){}
            }).done(function(response){
                catalogos = response;
                var optSelectListMultiple = '<option value="">Seleccione...</option>';
                $.each(catalogos, function( index, value ) {
                    optSelectListMultiple += '<option value="'+value['id']+'">'+value['cat_nombre']+'</option>';
                });
                $("#catalogos").html(optSelectListMultiple);
                $('#subopciones').html('<option value="">Seleccione...</option>');
                ocultarLoading();
            }).fail(function(response){
            });
        });

        $(document).on("click", "#agregarAutorizador", function () {
            var archivo = $("#archivo")[0].files[0];

            if($('#archivo').val() == "") {
                $('#archivo').focus();
                $("#msjErrorAnexo").css("display", "block");
                return false;                
            }
            
            // jsonOpcionesConfiguraciones = arregloOpcionesConfiguraciones.replace('[','{');
            // jsonOpcionesConfiguraciones = arregloOpcionesConfiguraciones.replace(']','}');
            jsonOpcionesConfiguraciones = Object.assign({}, arregloOpcionesConfiguraciones);

            var acumuladorDeCampos = new Array();
            switch ($('#tipoBusqueda').val()) {
                case 'usuario':
                    // autorizador = {archivo: archivo, idemp: $('#idemp').val(), numemp: $('#numemp').val(), nombreemp: $('#nombreemp').val(), idaplicacion: $('#aplicaciones').val(), tipoautorizacion: $('#tipoautorizacion').val(), rolmodrep: $('#rolmodrep').val(), email: $('#emailuser').val(), samaccountname: $('#samaccountname').val(), tipo: $('#tipo').val(), catalogosYConfiguraciones: jsonOpcionesConfiguraciones};
                    acumuladorDeCampos.push(archivo);
                    acumuladorDeCampos.push($('#idemp').val());
                    acumuladorDeCampos.push($('#numemp').val());
                    acumuladorDeCampos.push($('#nombreemp').val());
                    acumuladorDeCampos.push($('#aplicaciones').val());
                    acumuladorDeCampos.push($('#tipoautorizacion').val());
                    acumuladorDeCampos.push($('#rolmodrep').val());
                    acumuladorDeCampos.push($('#emailuser').val());
                    acumuladorDeCampos.push($('#samaccountname').val());
                    acumuladorDeCampos.push($('#tipo').val());
                    acumuladorDeCampos.push(jsonOpcionesConfiguraciones);
                    autorizador.push(acumuladorDeCampos);
                    break;
            
                case 'mesacontrol':
                    msctl = $('#msctl option:selected').text().split(" - ");
                    idmsctl = $('#msctl option:selected').val();
                    
                    // autorizador = {archivo: archivo, idmesa: idmsctl, idemp: $('#idemp').val(), numemp: $('#numemp').val(), nombreemp: msctl[0], idaplicacion: $('#aplicaciones').val(), tipoautorizacion: $('#tipoautorizacion').val(), rolmodrep: $('#rolmodrep').val(), email: $('#emailuser').val(), samaccountname: $('#samaccountname').val(), tipo: '1', catalogosYConfiguraciones: jsonOpcionesConfiguraciones};
                    acumuladorDeCampos.push(archivo);
                    acumuladorDeCampos.push($('#idemp').val());
                    acumuladorDeCampos.push($('#numemp').val());
                    // acumuladorDeCampos.push(msctl[0]);
                    acumuladorDeCampos.push($('#nombreemp').val());
                    acumuladorDeCampos.push($('#aplicaciones').val());
                    acumuladorDeCampos.push($('#tipoautorizacion').val());
                    acumuladorDeCampos.push($('#rolmodrep').val());
                    acumuladorDeCampos.push($('#emailuser').val());
                    acumuladorDeCampos.push($('#samaccountname').val());
                    acumuladorDeCampos.push('1');
                    acumuladorDeCampos.push(jsonOpcionesConfiguraciones);
                    acumuladorDeCampos.push(idmsctl);
                    autorizador.push(acumuladorDeCampos);
                    break;
            }
            arregloOpcionesConfiguraciones = new Array();
            autorizadores.push(autorizador[0]);
            autorizador = new Array();
            
            ttlhtml = '<tr>';
            ttlhtml += '<th>Nombre</th>';
            ttlhtml += '<th>Rol/Mod/Rep</th>';
            ttlhtml += '<th>E-mail</th>';
            ttlhtml += '<th># de Empleado</th>';
            ttlhtml += '<th>Tipo</th>';
            ttlhtml += '</tr>';

            html = '';
            var tipoAut;
            $.each(autorizadores, function(key, value) {
                switch (value[9]) {
                    case '1':
                        tipoAut = 'Mesa de Control';
                        break;
                    case '2':
                        tipoAut = 'Autorizador';
                        break;
                    case '3':
                        tipoAut = 'Ratificador';
                        break;
                }
                html += '<tr>';
                html += '<td>'+value[3]+'</td>';
                html += '<td>'+value[6]+'</td>';
                html += '<td>'+value[7]+'</td>';
                html += '<td>'+value[2]+'</td>';
                html += '<td>'+tipoAut+'</td>';
                html += '</tr>';
            });
            
            $('#ttl-lista-autorizadores').html(ttlhtml);
            $('#lista-autorizadores').html(html);
            // $('#tipoautorizacion').val('');
            // $('#rolmodrep').val('');
            // $('#aplicaciones').val('');
            
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

                var sendArray = new FormData();
                sendArray.append("total_registros", autorizadores.length);
                $.each(autorizadores, function(index, value){
                    // var countInt = 0;
                    $.each(value, function(ind, val) {
                        switch (ind) {
                            case 0:
                                sendArray.append("archivo_"+index, val);
                                break;
                            case 1:
                                sendArray.append("idemp[]", val);
                                break;
                            case 2:
                                sendArray.append("numemp[]", val);
                                break;
                            case 3:
                                sendArray.append("nombreemp[]", val);
                                break;
                            case 4:
                                sendArray.append("aplicaciones[]", val);
                                break;
                            case 5:
                                sendArray.append("tipoautorizacion[]", val);
                                break;
                            case 6:
                                sendArray.append("rolmodrep[]", val);
                                break;
                            case 7:
                                sendArray.append("emailuser[]", val);
                                break;
                            case 8:
                                sendArray.append("samaccountname[]", val);
                                break;
                            case 9:
                                sendArray.append("tipo[]", val);
                                break;
                            case 10:
                                if(val == "{}") {
                                    val = null
                                }
                                sendArray.append("jsonOpcionesConfiguraciones[]", JSON.stringify(val));
                                break;
                            case 11:
                                sendArray.append("idmsctl["+index+"]", val);
                                break;
                        }
                    });
                    // countInt = countInt+1
                });

                $.ajax({
                    type: 'POST',
                    data: sendArray,
                    dataType: 'JSON',
                    cache: false,
                    contentType: false,
                    processData: false,
                    url: '{{ route("saveregister") }}',

                    beforeSend: function(){},
                    complete: function(){}
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
        var optSelect;
        $.each(catalogos, function( index, value ) {
            optSelect += '<option value="'+value['id']+'">'+value['cat_nombre']+'</option>';
        });

        var idapp = $("#aplicaciones option:selected").val();

        switch (tipo) {
            case 'usuario':
                var titulo = 'Agregar Usuario Televisa como autorizador';
                var contentHtml = '<input type="hidden" name="emailuser" id="emailuser"/>'+
                    '<div class="col-md-10">'+
                        '<label for="numepm" class="col-lg-12 col-form-label text-left txt-bold"># de Empleado</label>'+
                        '<input type="number" min="1" id="numepm" placeholder="Ej. 2017853..." type="text" class="form-control" name="numepm" required autofocus>'+
                    '</div>'+
                    '<div class="col-md-2">'+
                        '<label class="col-lg-12 col-form-label">&nbsp;</label>'+
                        '<button class="btn btn-primary" id="busqueda" type="button">Buscar <i class="fas fa-search"></i></button>'+
                    '</div>'+
                    '<div class="col-md-12">'+
                        '<label for="tipo" class="col-lg-12 col-form-label text-left txt-bold">Tipo de Autorizador</label>'+
                        '<select id="tipo" class="form-control" name="tipo" required autofocus>'+
                            '<option value="">Seleccione...</option>'+
                            '<option value="2">Autorizador</option>'+
                            '<option value="3">Ratificador</option>'+
                        '</select>'+
                    '</div>';
                break;
            case 'mesacontrol':
                var titulo = 'Agregar Mesa de Control como autorizador';
                var contentHtml = '<div class="col-md-12 mt-3">'+
                        '<label for="msctl" class="col-lg-12 col-form-label text-left txt-bold">Mesa de Control</label>'+
                        '<select id="msctl" class="form-control" name="msctl" required>'+
                            '<option value="">Seleccione...</option>'+
                            @foreach($mesasdecontrol as $row => $value)
                                @php
                                    $id = $value['id'];
                                    $nombre = $value['cat_op_descripcion'];
                                @endphp
                                '<option value="{{ $id }}">{{ $nombre }}</option>'+
                            @endforeach
                        '</select>'+
                '</div>'+
                '<input type="hidden" name="emailuser" id="emailuser"/>'+
                '<div class="col-md-10">'+
                    '<label for="numepm" class="col-lg-12 col-form-label text-left txt-bold"># de Empleado</label>'+
                    '<input type="number" min="1" id="numepm" placeholder="Ej. 2017853..." type="text" class="form-control" name="numepm" required autofocus>'+
                '</div>'+
                '<div class="col-md-2">'+
                    '<label class="col-lg-12 col-form-label">&nbsp;</label>'+
                    '<button class="btn btn-primary" id="busqueda" type="button">Buscar <i class="fas fa-search"></i></button>'+
                '</div>';
                break;
        }

        Swal({
            title: titulo,
            // type: 'info',
            html: '<div class="container" style="margin-top: 10px;">'+
                '<form enctype="multipart/form-data" method="post" action="">'+
                    // Carga de Anexos
                    '<div class="card mb-3">'+
                        '<div class="card-header">'+
                            '<strong>Carga de Anexos</strong>'+
                        '</div>'+
                        '<div class="card-body">'+
                            '<div id="documento" name="documento">'+
                                '<div class="form-group row">'+
                                    '<div class="col-md-12" id="msjErrorAnexo">'+
                                        '<p>El campo de anexo es obligatorio.</p>'+
                                    '</div>'+
                                    '<div class="col-md-12" id="msjErrorAnexoSize">'+
                                        '<p>El documento es excede el tamaño permitido (4 megas).</p>'+
                                    '</div>'+
                                    '<div class="col-md-12">'+
                                        '<input type="hidden"class="appselect" name="archivo[0][app]" id="id_app_fus" data-type="app_0" value="'+idapp+'" />'+
                                        '<input type="file" class="form-control car-arch" id="archivo" name="archivo[]" data-type="doc_0">'+
                                    '</div>'+
                                    // '<div class="col-md-2">'+
                                    //     '<input type="button" class="btn btn-success" id="add_field" value="Agregar Anexos">'+
                                    // '</div>'+
                                '</div>'+
                            '</div>'+
                        '</div>'+
                    '</div>'+
                    // Fin de carga de anexos
                    '<div class="form-group row">'+
                        '<input type="hidden" id="tipoBusqueda" value="'+tipo+'">'+
                        contentHtml+
                    '</div>'+
                    '<div class="form-group row">'+
                        '<div class="col-md-12" id="showBusqueda">'+
                        '</div>'+
                    '</div>'+
                    '<div class="form-group row">'+
                        '<div class="col-md-12" id="showErrorRepetirCategoria">'+
                        '</div>'+
                        '<div class="col-md-12" id="showCategoriasAgregadas">'+
                        '</div>'+
                    '</div>'+
                    '<div class="col-md-12 text-right">'+
                        '<label class="col-lg-12 col-form-label">&nbsp;</label>'+
                        '<a href="#" onclick="swal.closeModal(); return false;" class="btn btn-danger">Cancelar</a>&nbsp;&nbsp;'+
                        '<input type="hidden" id="idemp" value="">'+
                        '<input type="hidden" id="numemp" value="">'+
                        '<input type="hidden" id="nombreemp" value="">'+
                        '<input type="hidden" id="samaccountname" value="">'+
                        '<input type="hidden" id="tipo" value="">'+
                        '<input type="hidden" id="idDelCatalogoSeleccionado" value="">'+
                        '<input type="hidden" id="relacionconconfiguracionesJSON" value="">'+
                        '<input disabled class="btn btn-primary" id="agregarAutorizador" type="button" value="Agregar">'+
                    '</div>'+
                '</form>'+
            '</div>',
            allowOutsideClick: false,
            allowEscapeKey: false,
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