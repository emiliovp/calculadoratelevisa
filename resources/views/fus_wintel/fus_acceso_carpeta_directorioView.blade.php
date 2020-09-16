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
                    <label><strong>Acceso a un Directorio o Carpeta<strong></label>
                    <hr>
                </div>
                <div class="card-body">
                    <input type="hidden" name="tipo_fus" id="tipo_fus" value="{{ $param['tipo_fus'] }}">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="movimiento" class="col-md-8 col-form-label text-md-rigth">Tipo de movimiento</label>
                            <select name="movimiento" id="movimiento" class="form-control{{ $errors->has('movimiento') ? ' is-invalid' : '' }}" required>
                                <option>Selecciona una opcion valida</option>
                                <option value='Alta'>Alta</option>
                                <option value='Baja'>Baja</option>
                                <option value='Cambio'>Cambio</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="direc" class="col-md-8 col-form-label text-md-rigth">Directorio o carpeta</label>
                            <input type="text" id="direc" name="direc" class="form-control{{ $errors->has('direc') ? ' is-invalid' : '' }}" value="">
                        </div>
                    </div>
                    <button class="btn btn-success" id="add_field">Agregar usuario</button>
                    <div id="usuario">
                        <div class="form-group row">
                            <div class="col-md-4">
                                <label for="num_usu" class="col-md-12 col-form-label text-md-rigth">Numero de empleado<span style="color: red;">*</span></label>
                                <input type="text" class="form-control{{ $errors->has('num_usu[]') ? ' is-invalid' : '' }} autocomplete_usu" data-type="num_usu" id="num_usu"  name="usuario[0][num_usu]" value="" required>
                            </div>
                            <div class="col-md-4">
                                <label for="nom_usu" class="col-md-12 col-form-label text-md-rigth">Usuario<span style="color: red;">*</span></label>
                                <input type="text" class="form-control{{ $errors->has('nom_usu[]') ? ' is-invalid' : '' }} " data-type="nom_usu" id="nom_usu"  name="usuario[0][nom_usu]" value="" required readonly>
                            </div>
                            <div class="col-md-4">
                                <label for="perm[]" class="col-md-12 col-form-label text-md-rigth">Permisos<span style="color: red;">*</span></label>
                                <input type="checkbox" id="lectura" name="usuario[0][lectura]" class="form-control{{ $errors->has('perm[]') ? ' is-invalid' : '' }} " value="Lectura"> Lectura                                     
                                <input type="checkbox" id="escritura" name="usuario[0][escritura]" class="form-control{{ $errors->has('perm[]') ? ' is-invalid' : '' }} "  value="Escritura"> Escritura
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
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
            if (id == "") {
                id = 0;
            }
            id= id + 1;
            e.preventDefault();     //prevenir novos clicks
                    $('#usuario').append('<div class="form-group row"><div class="col-md-4">\
                                    <label for="num_usu" class="col-md-12 col-form-label text-md-rigth">Número Completo del Autorizador<span style="color: red;">*</span></label>\
                                        <input type="text" class="form-control{{ $errors->has("num_usu") ? " is-invalid" : "" }} autocomplete_usu-'+id+'" data-type="num_usu-'+id+'" id="num_usu'+id+'"  name="usuario['+id+'][num_usu]" onKeyPress="return soloNumeros(event)" value="" required>\
                                </div>\
                                <div class="col-md-4">\
                                    <label for="nom_usu" class="col-md-12 col-form-label text-md-rigth">Nombre de Empleado del Autorizador</label>\
                                        <input type="text" class="form-control{{ $errors->has("nom_usu") ? " is-invalid" : "" }} autocomplete_txt-'+id+'" data-type="nom_usu-'+id+'" id="nom_usu'+id+'" name="usuario['+id+'][nom_usu]"  value="" readonly>\
                                    <div id="au"></div>\
                                </div>\
                                <div class="col-md-4">\
                                    <label for="perm[]" class="col-md-12 col-form-label text-md-rigth">Permisos<span style="color: red;">*</span></label>\
                                    <input type="checkbox" id="lectura'+id+'" name="usuario['+id+'][lectura]" class="form-control{{ $errors->has("perm[]") ? " is-invalid" : "" }} " value="Lectura"> Lectura\
                                    <input type="checkbox" id="escritura'+id+'" name="usuario['+id+'][escritura]" class="form-control{{ $errors->has("perm[]") ? " is-invalid" : "" }} " value="Escritura"> Escritura\
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