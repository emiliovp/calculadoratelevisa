
@extends('layouts.app')
@php
    $var = session()->get('_old_input');
@endphp
@component('layouts.encabezadowintel',['foo' => $param['data'], 'foo2' => $param['data2'], 'route'=> $param['route'], 'tipo_fus' => $param['tipo_fus'], 'jer' => $param['jer'] ])
<!-- aqui va el codigo que recibira el encabezado -->
    @slot('body')
<div class="container" style="margin-top:10px;">
    <div class="row justify.content-center"> 
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <label><strong>Solicitud de usuario de red<strong></label>
                    <hr>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                    <input type="hidden" name="tipo_fus" id="tipo_fus" value="{{ $param['tipo_fus'] }}">
                        <!-- <div class="col-md-6">
                            <label for="movimiento" class="col-md-8 col-form-label text-md-rigth">Tipo de operaci&oacute;n </label>
                            <select name="movimiento" id="movimiento" class="form-control{{ $errors->has('tio') ? ' is-invalid' : '' }}" required>
                                <option value="">Selecciona una opcion valida</option>
                                <option value='Alta de Usuario'>Alta de Usuario</option>
                                <option value='Baja de Usuario'>Baja de Usuario</option>
                                <option value='Cambio de Usuario'>Cambio de Usuario</option>
                            </select>
                        </div> -->
                        <div class="col-md-12">
                            <label for="dominio" class="col-md-8 col-form-label text-md-rigth">Dominio </label>
                            <select name="dominio" id="dominio" class="form-control{{ $errors->has('dominio') ? ' is-invalid' : '' }}" required>
                                <option value="">Selecciona una opcion valida</option>
                                @foreach($param['dominio'] as  $key => $val)
                                <option value="{{ $val['dominio'] }}">{{ $val['dominio'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <!-- <div class="form-group row" id="u_red_1">
                        <div class="col-md-6">
                            <label for="n_empleado_int_int" class="col-md-8 col-form-label text-md-rigth">No. Empleado </label>
                            <input type="text" id="n_empleado_int" name="n_empleado_int" data-type="n_empleado_int" class="form-control{{ $errors->has('n_empleado_int') ? ' is-invalid' : '' }} autocomplete_int" value="">
                        </div>
                        <div class="col-md-6">
                            <label for="empresa_int" class="col-md-8 col-form-label text-md-rigth">Empresa </label>
                            <input type="text" id="empresa_int" data-type="empresa_int" name="empresa_int" class="form-control{{ $errors->has('empresa_int') ? ' is-invalid' : '' }}" value="" readonly>
                        </div>
                    </div> 
                    <div class="form-group row" id="u_red_2">
                        <div class="col-md-4">
                            <label for="nombre_int" class="col-md-8 col-form-label text-md-rigth">Nombre </label>
                            <input type="text" id="nombre_int" data-type="nombre_int" name="nombre_int" class="form-control{{ $errors->has('nombre_int') ? ' is-invalid' : '' }}" value="" readonly>
                        </div>
                        <div class="col-md-4">
                            <label for="a_pat_int" class="col-md-8 col-form-label text-md-rigth">Apellido Paterno</label>
                            <input type="text" id="a_pat_int" data-type="a_pat_int" name="a_pat_int" class="form-control{{ $errors->has('a_pat_int') ? ' is-invalid' : '' }}" value="" readonly>
                        </div>
                        <div class="col-md-4">
                            <label for="a_mat_int" class="col-md-8 col-form-label text-md-rigth">Apellido Materno</label>
                            <input type="text" id="a_mat_int" data-type="a_mat_int" name="a_mat_int" class="form-control{{ $errors->has('a_mat_int') ? ' is-invalid' : '' }}" value="" readonly>
                        </div>
                    </div> -->
                    <div class="form-group row">
                        <div class="col-md-12 text-right">
                            <a href="{{ route('fus_lista') }}" id="regresar" class="btn btn-warning">Regresar</a>
                            <button type="submit" class="btn btn-primary update" id="enviar" >Guardar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endslot
@endcomponent
@push('scripts')
<script type="text/javascript">
$(document).on('change','#d_ext', function(){
    if($(this).prop('checked')){
        $('#u_red_1').hide();
        $('#u_red_2').hide();
    }else{
        $('#u_red_1').show();
        $('#u_red_2').show();
    }
});
$(document).on('focus','.autocomplete_int', function()
    {
        type = $(this).data('type');

        if(type =='n_empleado_int')autoType='FICHA';
        if(type =='empresa_int')autoType='EMPRESA';
        if(type =='nombre_int')autoType='NOMBRE';
        if(type =='a_pat_int')autoType='APELLIDO';
        if(type =='a_mat_int')autoType='APELLIDOM';
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
                console.log(data);
                
                if (data != "fail")
                {
                    $('#n_empleado_int').val(data.FICHA);
                    $('#empresa_int').val(data.EMPRESA);
                    $('#nombre_int').val(data.NOMBRE);
                    $('#a_pat_int').val(data.APELLIDO);
                    $('#a_mat_int').val(data.APELLIDOM);
                } else if(data == "fail") {
                    event.stopImmediatePropagation();
                    event.preventDefault();
                }
            }
            });
        });
</script>
@endpush