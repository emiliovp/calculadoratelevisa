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
                    <label><strong>Autorizaci&oacute;n de acceso a la red corporativa<strong></label>
                    <hr>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-4">
                            <label for="num_usu" class="col-md-12 col-form-label text-md-rigth">Numero de empleado<span style="color: red;">*</span></label>
                            <input type="text" class="form-control autocomplete_usu" data-type="num_usu" id="num_usu"  name="num_usu" value="" required>
                        </div>
                        <div class="col-md-4">
                            <label for="ext_usu_red" class="col-md-12 col-form-label text-md-rigth">Usuario de red</label>
                            <input type="text" class="form-control " data-type="ext_usu_red" id="ext_usu_red"  name="ext_usu_red" value=""  readonly>
                        </div>
                        <div class="col-md-4">
                            <label for="mail" class="col-md-12 col-form-label text-md-rigth">Correo</label>
                            <input type="text" class="form-control" data-type="mail" id="mail"  name="mail" value=""  readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-4">
                            <label for="nom_proyecto" class="col-md-12 col-form-label text-md-rigth">Proyecto</label>
                            <input type="text" class="form-control" data-type="nom_proyecto" id="nom_proyecto"  name="nom_proyecto" value="" readonly>
                        </div>
                        <div class="col-md-4">
                            <label for="d_pro" class="col-md-12 col-form-label text-md-rigth">Duracion del proyecto</label>
                            <input type="text" class="form-control" data-type="d_pro" id="d_pro"  name="d_pro" value=""  readonly>
                        </div>
                        <div class="col-md-4">
                            <label for="ext_ubicacion" class="col-md-12 col-form-label text-md-rigth">Ubicacion</label>
                            <input type="text" class="form-control" data-type="ext_ubicacion" id="ext_ubicacion"  name="ext_ubicacion" value="" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                    <input type="hidden" name="tipo_fus" id="tipo_fus" value="{{ $param['tipo_fus'] }}">
                        <div class="col-md-12">
                            <label for="t_servicio" class="col-md-8 col-form-label text-md-rigth">Servicio que requiere de la infraestructura</label>
                            <br>
                            <input type="checkbox" id="impresion" name="impresion" class="form-control"> Impresion
                            <br>
                            <input type="checkbox" id="sarchivo" name="sarchivo" class="form-control" > Servidor de archivos
                            <br>
                            <input type="checkbox" id="saplicacion" name="saplicacion" class="form-control"> Servidor de aplicaciones
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
                    <label><strong>Datos del equipo propio<strong></label>
                    <hr>
                    <div class="form-group row" >
                        <div class="col-md-4">
                            <label for="marca" class="col-md-8 col-form-label text-md-rigth">Marca</label>
                            <input type="text" class="form-control" data-type="marca" id="marca"  name="marca" value="">
                        </div>
                        <div class="col-md-4">
                            <label for="modelo" class="col-md-8 col-form-label text-md-rigth">Modelo</label>
                            <input type="text" class="form-control" data-type="modelo" id="modelo"  name="modelo" value="">
                        </div>
                        <div class="col-md-4">
                            <label for="so" class="col-md-8 col-form-label text-md-rigth">Sistema operativo</label>
                            <select name="so" id="so" class="form-control{{ $errors->has('so') ? ' is-invalid' : '' }}" required>
                                <option>Selecciona una opcion valida</option>
                                <option value='Windows'>Windows</option>
                                <option value='Mac'>Mac</option>
                                <option value='Linux'>Linux</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row" >
                        <div class="col-md-6">
                            <label for="n_serie" class="col-md-8 col-form-label text-md-rigth">No. serie</label>
                            <input type="text" class="form-control" data-type="n_serie" id="n_serie"  name="n_serie" value="">
                        </div>
                        <div class="col-md-6">
                            <label for="t_equipo" class="col-md-8 col-form-label text-md-rigth">Tipo de equipo</label>
                            <select name="t_equipo" id="t_equipo" class="form-control{{ $errors->has('t_equipo') ? ' is-invalid' : '' }}" required>
                                <option>Selecciona una opcion valida</option>
                                <option value='Mac'>Mac</option>
                                <option value='Laptop'>Laptop</option>
                                <option value='Escritorio'>Escritorio</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row" >
                        <div class="col-md-12">
                            <label for="address" class="col-md-8 col-form-label text-md-rigth">Mac Address</label>
                            <input type="text" class="form-control" data-type="address" id="address"  name="address" value="">
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
$(document).on('focus','.autocomplete_usu', function()
{
    type = $(this).data('type');

    if(type =='num_usu')autoType='FICHA';
    if(type =='ext_usu_red')autoType='ext_usu_red';
    if(type =='mail')autoType='mail';
    if(type =='nom_proyecto')autoType='nom_proyecto';
    if(type =='d_pro')autoType='d_pro';
    if(type =='ext_ubicacion')autoType='ext_ubicacion';
    
    $(this).autocomplete
    ({
        minLength:0,
        source: function( request, response)
        {
            $.ajax({
                url:"{{ route('fus.autocomplete2') }}",
                dataType: "json",
                data:{
                    term: request.term,
                    type: type,
                    search: 2
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
                $('#num_usu').val(data.FICHA);
                $('#ext_usu_red').val(data.u_red);
                $('#mail').val(data.mail);

                // $('#num_usu').val(data.NOMBRE);
                // $('#u_red').val(data.APELLIDO);
                // $('#mail').val(data.APELLIDOM);

                $('#nom_proyecto').val(data.PROYECTO_EXT);
                $('#d_pro').val(data.VIGENCIA);
                $('#ext_ubicacion').val(data.UBICACION);
            } else if(data == "fail") {
                
                event.stopImmediatePropagation();
                event.preventDefault();
            }
        }
    });
});

</script>
@endpush