@extends('layouts.app')
@component('layouts.encabezadowintel',['foo' => $param['data'], 'route'=> $param['route']])
<!-- aqui va el codigo que recibira el encabezado -->
    @slot('body')
    <style>
    @media only screen and (min-width: 768px) {
        #btn-pasar {
            margin-top:38px;
        }
    }
    #btn-quitar {
        margin-top:3px;
    }
    .btn-danger {
        margin-left: 1.5%;
        margin-top: 10px;
    }
</style>
<div class="container" style="margin-top:10px;">
    <div class="row justify.content-center"> 
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <label><strong>Solicitud de activación de cuenta de acceso por VPN (Virtual Private Network) a la red corporativa<strong></label>
                    <hr>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                        <input type="hidden" name="tipo_fus" id="tipo_fus" value="{{ $param['tipo_fus'] }}">
                            <label for="so" class="col-md-8 col-form-label text-md-rigth">Tipo de solicitud</label>
                            <select name="movimiento" id="movimiento" class="form-control{{ $errors->has('movimiento') ? ' is-invalid' : '' }}" required>
                                <option>Selecciona una opcion valida</option>
                                <option value=1>Alta VPN</option>
                                <option value=2>Baja VPN</option>
                                <option value=3>Cambio VPN</option>
                                <option value=2>Acceso de aplicaiones con VPN</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="so" class="col-md-8 col-form-label text-md-rigth">Sistema Operativo</label>
                            <select name="so" id="so" class="form-control{{ $errors->has('so') ? ' is-invalid' : '' }}" required>
                                <option>Selecciona una opcion valida</option>
                                <option value=1>Windows</option>
                                <option value=2>Mac</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                                <label for="n_app" class="col-md-8 col-form-label text-md-rigth">Nombre de aplicaci&oacute;n o servicio</label>
                                <input  type="text" id="n_app" name="n_app" data-type="n_app" class="form-control{{ $errors->has('n_app') ? ' is-invalid' : '' }}" value="">
                            </div>
                            <div class="col-md-3">
                                <label for="servidor" class="col-md-8 col-form-label text-md-rigth">Servidor</label>
                                <input  type="text" id="servidor" name="servidor" data-type="servidor" class="form-control{{ $errors->has('servidor') ? ' is-invalid' : '' }}" value="">
                            </div>
                            <div class="col-md-3">
                                <label for="ip" class="col-md-8 col-form-label text-md-rigth">IP</label>
                                <input  type="text" id="ip" name="ip" data-type="ip" class="form-control{{ $errors->has('ip') ? ' is-invalid' : '' }}" value="">
                        </div>
                    </div>
                    <div class="form-group row" >
                        <div class="col-md-12">
                            <label for="justificacion" class="col-md-12 col-form-label text-md-rigth">Justificaci&oacute;n</label>
                            <textarea id="justificacion" name="justificacion" class="form-control{{ $errors->has('justificacion') ? ' is-invalid' : '' }}">@php
                                $var = session()->get('_old_input');
                                $justificacion=$var['justificacion'];
                                @endphp
                                {{ ($justificacion !='') ? $justificacion :''}}
                            </textarea>
                        </div>
                    </div>
                    <divx class="form-group row">
                        <div class="col-md-12 text-right">
                            <a href="{{ route('fus_lista') }}" id="regresar" class="btn btn-warning">Regresar</a>
                            <button type="submit" class="btn btn-primary update" id="enviar" >Guardar</button>
                        </div>
                    </div> 
                    <!-- aqui termina -->
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endslot
@endcomponent
@push('scripts')
<script>
function soloNumeros(e){
    var key = window.Event ? e.which : e.keyCode
	return (key >= 48 && key <= 57);
}
$(document).on('focus','.autocomplete_txt', function()
{
    type = $(this).data('type');

    if(type =='nom_aut1')autoType='nombre';
    if(type =='num_aut1')autoType='numero';
    if(type =='mail_aut1')autoType='correo';
    
    $(this).autocomplete
    ({
        minLength:0,
        source: function( request, response)
        {
            $.ajax({
                url:"{{ route('fus.autocomplete') }}",
                dataType: "json",
                data:{
                    term: request.term,
                    type: type,
                    search: 1
                },
                success: function(data)
                {
                    var array = $.map(data, function(item){
                        var response = "";
                        if(item[autoType] !== undefined && item[autoType] !== undefined) {
                            response = {
                                label: item[autoType],
                                value: item[autoType],
                                data: item
                            }
                        } 
                        else if(item != "")
                        {
                            response = {
                                label: item,
                                value: item,
                                data: "fail"
                            };
                        }
                        return response;
                    });
                    response(array);
                }
            });
        },
        select: function( event, ui)
        {
            var data = ui.item.data;
            if (data != "fail")
            {
                $('#nom_aut1').val(data.nombre);
                $('#num_aut1').val(data.numero);
                $('#mail_aut1').val(data.correo);
            } else if(data == "fail") {
                
                event.stopImmediatePropagation();
                event.preventDefault();
            }
        }
    });
});
$(document).on('focus','.autocomplete', function()
{
    type = $(this).data('type');

    if(type =='nom_aut2')autoType='nombre';
    if(type =='num_aut2')autoType='numero';
    if(type =='mail_aut2')autoType='numero';
    
    $(this).autocomplete
    ({
        minLength:0,
        source: function( request, response)
        {
            $.ajax({
                url:"{{ route('fus.autocomplete') }}",
                dataType: "json",
                data:{
                    term: request.term,
                    type: type,
                    search: 1
                },
                success: function(data)
                {
                    var array = $.map(data, function(item){
                        var response = "";
                        if(item[autoType] !== undefined && item[autoType] !== undefined) {
                            response = {
                                label: item[autoType],
                                value: item[autoType],
                                data: item
                            }
                        } 
                        else if(item != "")
                        {
                            response = {
                                label: item,
                                value: item,
                                data: "fail"
                            };
                        }
                        return response;
                    });
                    response(array);
                }
            });
        },
        select: function( event, ui)
        {
            var data = ui.item.data;
            if (data != "fail")
            {
                $('#nom_aut2').val(data.nombre);
                $('#num_aut2').val(data.numero);
                $('#mail_aut2').val(data.correo);
            } else if(data == "fail") {
                
                event.stopImmediatePropagation();
                event.preventDefault();
            }
        }
    });
});
$(document).on('focus','.autocomplete_usu', function()
{
    type = $(this).data('type');

    if(type =='num_usu')autoType='numero';
    if(type =='nom_usu')autoType='cuenta';
    // if(type =='mail_aut2')autoType='numero';
    
    $(this).autocomplete
    ({
        minLength:0,
        source: function( request, response)
        {
            $.ajax({
                url:"{{ route('fus.autocomplete') }}",
                dataType: "json",
                data:{
                    term: request.term,
                    type: type,
                    search: 1
                },
                success: function(data)
                {
                    var array = $.map(data, function(item){
                        var response = "";
                        if(item[autoType] !== undefined && item[autoType] !== undefined) {
                            response = {
                                label: item[autoType],
                                value: item[autoType],
                                data: item
                            }
                        } 
                        else if(item != "")
                        {
                            response = {
                                label: item,
                                value: item,
                                data: "fail"
                            };
                        }
                        return response;
                    });
                    response(array);
                }
            });
        },
        select: function( event, ui)
        {
            var data = ui.item.data;
            if (data != "fail")
            {
                $('#num_usu').val(data.numero);
                $('#nom_usu').val(data.cuenta);
                // $('#mail_aut2').val(data.correo);
            } else if(data == "fail") {
                
                event.stopImmediatePropagation();
                event.preventDefault();
            }
        }
    });
});
var id= "";
$('#add_field').on('click',function(e) {
    
        var num_usu, lectura, escritura, valido;
        // todos los campos .form-control en #campos
        num_usu = document.querySelectorAll('#num_usu'+id);
        lectura = document.querySelectorAll('#lectura'+id);
        escritura = document.querySelectorAll('#escritura'+id);
        valido = true; // es valido hasta demostrar lo contrario
        // id=id+1;
        if(!document.getElementById("lectura"+id).checked==true && !document.getElementById("escritura"+id).checked==true){
            valido = false;
        }
        // recorremos todos los campos
        // [].slice.call(num_usu).forEach(function(num_usu) {
            [].slice.call(num_usu).forEach(function(num_usu) {
                // console.log(num_usu.value.trim());
                // console.log(num_usu.value.trim());
                // el campo esta vacio?
                // alert(nombre_inp.value);
                if (num_usu.value.trim() === '') {
                    valido = false;
                }
            // });
        });

        if (valido) {  
            id=id+1;
            e.preventDefault();     //prevenir novos clicks
                    $('#usuario').append('<div class="form-group row"><div class="col-md-4">\
                                    <label for="num_usu" class="col-md-12 col-form-label text-md-rigth">Nombre Completo del Autorizador<span style="color: red;">*</span></label>\
                                        <input type="text" class="form-control{{ $errors->has("num_usu") ? " is-invalid" : "" }} autocomplete_usu-'+id+'" data-type="num_usu-'+id+'" id="num_usu'+id+'"  name="num_usu[]" onKeyPress="return soloNumeros(event)" value="" required>\
                                </div>\
                                <div class="col-md-4">\
                                    <label for="nom_usu" class="col-md-12 col-form-label text-md-rigth">Número de Empleado del Autorizador</label>\
                                        <input type="text" class="form-control{{ $errors->has("nom_usu") ? " is-invalid" : "" }} autocomplete_txt-'+id+'" data-type="nom_usu-'+id+'" id="nom_usu'+id+'" name="nom_usu[]"  value="" readonly>\
                                    <div id="au"></div>\
                                </div>\
                                <div class="col-md-4">\
                                    <label for="perm[]" class="col-md-12 col-form-label text-md-rigth">Permisos<span style="color: red;">*</span></label>\
                                    <input type="checkbox" id="lectura'+id+'" name="lectura'+id+'" class="form-control{{ $errors->has("perm[]") ? " is-invalid" : "" }} " value="1"> Lectura\
                                    <input type="checkbox" id="escritura'+id+'" name="escritura'+id+'" class="form-control{{ $errors->has("perm[]") ? " is-invalid" : "" }} " value="2"> Escritura\
                            </div>\
                                <button type="button" class="btn btn-danger remover_aut" id="remover_campo">Remover</button>');
                                
        }
        else
        {
            alert("Faltan datos del autorizador");
            return false;
        }
        // $('#num_auto'+id).click(function() {
        //         $('#nom_auto'+id).val('');
        //     });
    $(document).on('focus','.autocomplete_usu-'+id, function()
    {
        type = $(this).data('type');

        if(type =='num_usu-'+id)autoType='numero';
        if(type =='nom_usu-'+id)autoType='cuenta';
        $(this).autocomplete
        ({
            minLength:0,
            source: function( request, response)
            {
                $.ajax({
                    url:"{{ route('fus.autocomplete') }}",
                    dataType: "json",
                    data:{
                        term: request.term,
                        type: type,
                        search: 1
                    },
                    success: function(data)
                    {
                        var array = $.map(data, function(item){
                            var response = "";
                            if(item[autoType] !== undefined && item[autoType] !== undefined) {
                                response = {
                                    label: item[autoType],
                                    value: item[autoType],
                                    data: item
                                }
                            } 
                            else if(item != "")
                            {
                                response = {
                                    label: item,
                                    value: item,
                                    data: "fail"
                                };
                            }
                            return response;
                        });
                        response(array);
                    }
                });
            },
            select: function( event, ui)
            {
                var data = ui.item.data;
                if (data != "fail")
                {
                    $('#num_usu'+id).val(data.numero);
                    $('#nom_usu'+id).val(data.cuenta);
                } else if(data == "fail") {
                    event.stopImmediatePropagation();
                    event.preventDefault();
                }
            }
            });
        });
    });
    // Remover div anterior
    $('#usuario').on("click",".remover_aut",function(e) {
            e.preventDefault();
            $(this).parent('div').remove();
    });
</script>
@endpush