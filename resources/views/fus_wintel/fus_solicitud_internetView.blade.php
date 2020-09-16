@extends('layouts.app')
@php
    $var = session()->get('_old_input');
@endphp
@component('layouts.encabezadowintel',['foo' => 1])
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
                    <label><strong>Solicitud de Acceso a Internet<strong></label>
                    <hr>
                    <div class="form-group row">
                        <div class="col-md-4">
                            <input type="checkbox" id="sr" name="sr" class="form-control{{ $errors->has('sr') ? ' is-invalid' : '' }} " value="1"> Solicitud para visualizar sitios restringidos                                     
                        </div>
                    </div>
                    <button class="btn btn-success" id="add_user">Agregar usuario</button>
            <div id="usuario_red" name="usuario_red">
                    <div class="form-group row">
                        <div class="col-md-4">
                            <label for="u_red" class="col-md-8 col-form-label text-md-rigth">Usuario de red</label>
                            <input  type="text" id="u_red" name="u_red[]" data-type="u_red" class="autocomplete_txt form-control{{ $errors->has('u_red') ? ' is-invalid' : '' }}" value="">
                        </div>
                        <div class="col-md-4">
                            <label for="puesto" class="col-md-8 col-form-label text-md-rigth">Puesto</label>
                            <input  type="text" id="puesto" name="puesto[]" data-type="puesto" class=" form-control{{ $errors->has('puesto') ? ' is-invalid' : '' }}" value="">
                        </div>
                        <div class="col-md-4">
                        <label for="ip" class="col-md-8 col-form-label text-md-rigth">Dirección IP</label>
                            <input  type="text" id="ip" name="ip[]" data-type="ip" class=" form-control{{ $errors->has('ip') ? ' is-invalid' : '' }}" value="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="adaptador" class="col-md-8 col-form-label text-md-rigth">Adapter address</label>
                            <input  type="text" id="adaptador" name="adaptador[]" data-type="adaptador" class=" form-control{{ $errors->has('adaptador') ? ' is-invalid' : '' }}" value="">
                        </div>
                        <div class="col-md-6">
                            <label for="clasi" class="col-md-8 col-form-label text-md-rigth">Clasificación de la pagina a visitar</label>
                            <input  type="text" id="clasi" name="clasi[]" data-type="clasi" class=" form-control{{ $errors->has('clasi') ? ' is-invalid' : '' }}" value="">
                        </div>
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
                    <div class="form-group row">
                        <div class="col-md-12">
                            <input type="checkbox" id="parchivo" name="parchivo" class="form-control{{ $errors->has('perm') ? ' is-invalid' : '' }} " value="1"> Permiso de transferencia de archivos (*.exe, *.zip, etc)
                        </div>
                        <div class="col-md-4">
                            <input type="checkbox" id="minstantanea" name="minstantanea" class="form-control{{ $errors->has('minstantanea') ? ' is-invalid' : '' }} " value="1"> Mensajeria instantanea (MSN, AOL, ICQ, etc)
                        </div>
                    </div>
                    <div class="form-group row" >
                        
                    </div>
                    <divx class="form-group row">
                        <div class="col-md-12 text-right">
                            <a href="" id="regresar" class="btn btn-warning">Regresar</a>
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
var id= "";
$('#add_user').on('click',function(e){
    
        var usu, puesto, ip, adaptador, clasi, valido;
        // todos los campos .form-control en #campos
        usu = document.querySelectorAll('#u_red'+id);
        puesto = document.querySelectorAll('#puesto'+id);
        ip = document.querySelectorAll('#ip'+id);
        adaptador = document.querySelectorAll('#adaptador'+id);
        clasi = document.querySelectorAll('#clasi'+id);
        valido = true; // es valido hasta demostrar lo contrario
        // if(!document.getElementById("lectura"+id).checked==true && !document.getElementById("escritura"+id).checked==true){
        //     valido = false;
        // }
        // recorremos todos los campos
        // [].slice.call(num_usu).forEach(function(num_usu) {
            [].slice.call(usu).forEach(function(usu) {
                console.log(usu.value.trim());
                // console.log(num_usu.value.trim());
                // el campo esta vacio?
                alert(usu.value.trim());
                if (usu.value.trim() === '') {
                    valido = false;
                    // return valido;
                }
            // });
        });

        if (valido) {  
            id=id+1;
            e.preventDefault();     //prevenir novos clicks
                $('#usuario_red').append('<div class="form-group row">\
                        <div class="col-md-4">\
                            <label for="u_red" class="col-md-8 col-form-label text-md-rigth">Usuario de red</label>\
                            <input  type="text" id="u_red" name="u_red[]" data-type="u_red" class="autocomplete_txt form-control" value="">\
                        </div>\
                        <div class="col-md-4">\
                            <label for="puesto" class="col-md-8 col-form-label text-md-rigth">Puesto</label>\
                            <input  type="text" id="puesto[]" name="puesto[]" data-type="puesto" class="autocomplete_txt form-control" value="">\
                        </div>\
                        <div class="col-md-4">\
                        <label for="ip" class="col-md-8 col-form-label text-md-rigth">Dirección IP</label>\
                            <input  type="text" id="ip[]" name="ip[]" data-type="ip" class="autocomplete_txt form-control" value="">\
                        </div>\
                    </div>\
                    <div class="form-group row">\
                        <div class="col-md-6">\
                            <label for="adaptador" class="col-md-8 col-form-label text-md-rigth">Adapter address</label>\
                            <input  type="text" id="adaptador[]" name="adaptador[]" data-type="adaptador" class="autocomplete_txt form-control" value="">\
                        </div>\
                        <div class="col-md-6">\
                            <label for="clasi" class="col-md-8 col-form-label text-md-rigth">Clasificación de la pagina a visitar</label>\
                            <input  type="text" id="clasi[]" name="clasi[]" data-type="clasi" class="autocomplete_txt form-control" value="">\
                        </div>\
                    </div>');
                                
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
    $('#usuario_red').on("click",".remover_aut",function(e) {
            e.preventDefault();
            $(this).parent('div').remove();
    });
</script>
@endpush