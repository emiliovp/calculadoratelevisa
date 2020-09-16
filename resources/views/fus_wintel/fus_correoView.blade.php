@extends('layouts.app')
@component('layouts.encabezadowintel',['foo' => $param['data'], 'foo2' => $param['data2'], 'route'=> $param['route'], 'tipo_fus' => $param['tipo_fus'], 'jer' => $param['jer']  ])
<!-- aqui va el codigo que recibira el encabezado -->
@slot('body')
<div class="container" style="margin-top:10px;">
    <div class="row justify.content-center"> 
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <label><strong>Datos para la generaci&oacute;n del correo<strong></label>
                    <hr>
                </div>
                <div class="card-body">
                    <div class="form-group row"> 
                        <div class="col-md-12">
                            <label class="col-md-12 col-form-label text-md-rigth"><span style="color:black">* Si no cuenta con usuario de red este será creado</span></label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <input type="hidden" name="tipo_fus" id="tipo_fus" value="{{ $param['tipo_fus'] }}">
                            <!-- <div class="col-md-4">
                                <label for="movimiento" class="col-md-8 col-form-label text-md-rigth">Tipo de movimiento</label>
                                <select name="movimiento" id="movimiento" class="form-control{{ $errors->has('tio') ? ' is-invalid' : '' }}" required>
                                    <option>Selecciona una opcion valida</option>
                                    <option value="Alta">Alta</option>
                                    <option value="Baja">Baja</option>
                                    <option value="Cambio">Cambio</option>
                                </select>
                            </div> -->
                    <!-- </div>
                    <div class="form-group row" id="u_red_2"> -->
                        <div class="col-md-6">
                            <label for="smtp" class="col-md-8 col-form-label text-md-rigth">SMTP </label>
                            <select name="smtp" id="smtp" class="form-control" required>
                                <option value="">Selecciona una opcion valida</option>
                                @foreach($param['smtp'] as  $key => $val)
                                <option value="{{ $val['dominio'] }}">{{ $val['dominio'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="dominio" class="col-md-8 col-form-label text-md-rigth">Dominio </label>
                            <select name="dominio" id="dominio" class="form-control" required>
                                <option value="">Selecciona una opcion valida</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                    <div class="col-md-12 text-right">
                        <a href="{{ route('fus_lista') }}" id="regresar" class="btn btn-warning">Regresar</a>
                        <button type="submit" class="btn btn-primary update" id="enviar" >Guardar</button>
                    </div>
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
        $('#u_red_correo').hide();
        $('#u_red_2').hide();
    }else{
        $('#u_red_correo').show();
        $('#u_red_2').show();
    }
}); 
$(document).on('change','#smtp', function(){
    var smtp = $(this).val();
    $('#dominio').empty();
    $('#dominio').append("<option value=''>Selecciona una opcion valida</option>");
    $.ajax({
        url: "{{ route('opDom') }}",
        dataType: "JSON",
        data:{smtp: smtp},
        success: function(response) {
            $.each(response,function(index,value){
                $('#dominio').append("<option value='"+value.cat_op_descripcion+"'>"+value.cat_op_descripcion+"</option>");
             console.log(value);
            })
        }
    });
}); 
$(document).on('focus','.autocomplete_int', function()
    {
        type = $(this).data('type');

        if(type =='n_empleado_int')autoType='FICHA';
        if(type =='empresa_int')autoType='EMPRESA';
        if(type =='nombre_int')autoType='NOMBRE';
        if(type =='a_pat_int')autoType='APELLIDO';
        if(type =='a_mat_int')autoType='APELLIDOM';
        if(type =='puesto_correo')autoType='PUESTO';
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
                    $('#puesto_correo').val(data.PUESTO);
                } else if(data == "fail") {
                    event.stopImmediatePropagation();
                    event.preventDefault();
                }
            }
            });
        });
</script>
@endpush