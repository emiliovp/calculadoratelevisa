@extends('layouts.app')
@component('layouts.encabezadowintel',['foo' => $param['data'], 'route'=> $param['route'], 'tipo_fus' => $param['tipo_fus'], 'jer' => $param['jer'] ])
<!-- aqui va el codigo que recibira el encabezado -->
    @slot('body')
<div class="container" style="margin-top:10px;">
    <input type="hidden" name="tipo_fus" id="tipo_fus" value="{{ $param['tipo_fus'] }}">
    <div class="row justify.content-center"> 
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <label><strong>Datos para la generaci&oacute;n de correo especial<strong></label>
                    <hr>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                         <div class="col-md-3">
                            <label for="f_vigencia" class="col-md-8 col-form-label text-md-rigth">Tipo de solicitud</label>
                            <select class="form-control" id="tipo_sol" name="tipo_sol" required>
                                <option value="">Selecciona una opcion</option>
                                <option value="Cuenta">Cuenta</option>
                                <option value="Cuenta y correo">Cuenta y correo</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="n_cuenta" class="col-md-8 col-form-label text-md-rigth">Cuenta</label>
                            <input type="text" id="n_cuenta" name="n_cuenta" class="form-control" value="" onKeyPress="return v_especial(event)" required>
                        </div>
                        <div class="col-md-4">
                            <label for="nombre_cuenta" class="col-md-8 col-form-label text-md-rigth">Nombre de la cuenta</label>
                            <input type="text" id="nombre_cuenta" name="nombre_cuenta" placeholder="" class="form-control" value="" required>
                        </div>
                        <div class="col-md-2">
                            <label for="f_vigencia" class="col-md-12 col-form-label text-md-rigth">Fecha de vigencia</label>
                            <input type="text" id="f_vigencia" name="f_vigencia" placeholder="dd-mm-yyyy" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="form-group row" style="display:none" id="dom" >
                        <div class="col-md-12">
                            <label for="dominio" class="col-md-8 col-form-label text-md-rigth">Dominio</label>
                            <select name="dominio" id="dominio" class="form-control">
                                <option value="">Selecciona una opcion valida</option>
                                @foreach($param['dominio'] as  $key => $val)
                                <option value="{{ $val['dominio'] }}">{{ $val['dominio'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row" style="display:none" id="smtp_dom" >
                    <div class="col-md-6">
                            <label for="smtp" class="col-md-8 col-form-label text-md-rigth">SMTP</label>
                            <select name="smtp" id="smtp" class="form-control">
                                <option value="">Selecciona una opcion valida</option>
                                @foreach($param['smtp'] as  $key => $val)
                                <option value="{{ $val['dominio'] }}">{{ $val['dominio'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="dominio2" class="col-md-8 col-form-label text-md-rigth">Dominio</label>
                            <select name="dominio2" id="dominio2" class="form-control">
                                <option>Selecciona una opcion valida</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row" >
                        <div class="col-md-12">
                            <label for="justificacion" class="col-md-12 col-form-label text-md-rigth">Justificaci&oacute;n</label>
                            <textarea id="justificacion" name="justificacion" class="form-control " minlength="15" required></textarea>
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
function v_especial(e)
{
    tecla = (document.all) ? e.keyCode : e.which;

    //Tecla de retroceso para borrar, siempre la permite
    if (tecla == 8) {
        return true;
    }

    // Patron de entrada, en este caso solo acepta numeros y letras
    patron = /[A-Za-z0-9_\d-\\]/;
    tecla_final = String.fromCharCode(tecla);
    return patron.test(tecla_final);
}   
$(document).on('focus','.autocomplete_txt', function()
{
    var type = $(this).data('type');
    var mov = $('#movimiento').val();
    if(type =='nom_aut1')autoType='nombre';
    if(type =='num_aut1')autoType='numero';
    
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
                    type: type
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
                //$('#nom_res').blur();
            } else if(data == "fail") {
                
                event.stopImmediatePropagation();
                event.preventDefault();
            }
        }
    });
});
$(document).on('blur','#n_cuenta', function (){
    var term= $(this).val();
    var mov = $('#movimiento').val();
    if (mov == 'Alta'){
        if (term != "") {
            $.ajax({
                url:"{{ route('fus.validuser') }}",
                dataType: "json",
                data:{
                    term: term
                },
                success: function(data)
                {
                    if (data === 1) {
                        Swal.fire({
                        icon: 'error',
                        title: 'Advertencia',
                        text: 'La cuenta ya existe, favor de seleccionar otra'
                        })
                    }
                }
           });
        }
    }
});
$(document).on('focus','.autocomplete', function()
{
    type = $(this).data('type');

    if(type =='nom_aut2')autoType='nombre';
    if(type =='num_aut2')autoType='numero';
    
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
                    type: type
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
                //$('#nom_res').blur();
            } else if(data == "fail") {
                
                event.stopImmediatePropagation();
                event.preventDefault();
            }
        }
    });
});
$(document).on('change','#tipo_sol', function(){
    var t_sol = $(this).val();
    if (t_sol === 'Cuenta') {
        $('#dom').show();
        $('#smtp_dom').hide(); 
        $('#smtp').val('');
        $('#dominio2').val('');
        var element1 = document.getElementById("dominio");
        element1.classList.add("campo-requerido");
        var element1 = document.getElementById("smtp");
        element1.classList.remove("campo-requerido"); 
        var element2 = document.getElementById("dominio2");
        element2.classList.remove("campo-requerido");
    }
    else{
        $('#smtp_dom').show();
        $('#dom').hide();
        $('#dominio').val('');
        var element = document.getElementById("dominio");
        element.classList.remove("campo-requerido");
        var element1 = document.getElementById("smtp");
        element1.classList.add("campo-requerido"); 
        var element2 = document.getElementById("dominio2");
        element2.classList.add("campo-requerido"); 
    }
});
$(document).on('change','#smtp', function(){
    var smtp = $(this).val();
    $('#dominio2').empty();
    $('#dominio2').append("<option value=''>Selecciona una opcion valida</option>");
    $.ajax({
        url: "{{ route('opDom') }}",
        dataType: "JSON",
        data:{smtp: smtp},
        success: function(response) {
            $.each(response,function(index,value){
                $('#dominio2').append("<option value='"+value.cat_op_descripcion+"'>"+value.cat_op_descripcion+"</option>");
             console.log(value);
            })
        }
    });
});
</script>
@endpush