@extends('layouts.app')

@section('content')
<style type="text/css">
.card-body {
    padding: 0.5rem 1.25rem;
}
label.error {
    font-size: 8pt;
    color: red;
}
.remover_campo {
    margin: auto;
    position: relative;
    border: 2px;
}
#ayuda{
    margin-top: 3%;
}
</style>
<input type="hidden" id="modulo" value="admonperfiles" />
<!-- <input type="hidden" id="formAjax" value="1" /> -->
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Alta de perfiles
                </div>
                <div class="card-body">
                    <form method="POST" id="form_editperfil" action="{{route('updperfil')}}">
                        @csrf
                        <input type="hidden" name="idperfil" id="idperfil" value="{{ $perfil[0]['id'] }}"/>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="perfil" class="txt-bold">Nombre del perfil<span style="color: red;">*</span></label>
                                <input type="text" class="form-control{{ $errors->has('perfil') ? ' is-invalid' : '' }} campo-requerido" value="{{ !empty(old('perfil')) ? old('perfil') : $perfil[0]['cal_perfil'] }}" name="perfil" id="perfil"/>   
                            </div>   
                            <div class="col-md-6">
                                <label for="area" class="txt-bold">Área<span style="color: red;">*</span></label>
                                <select class= "form-control campo-requerido" name="area" id ="area">
                                    <option value="">Seleccione...</option>
                                    @foreach($area AS $row)
                                        @php
                                            $selected = "";
                                        @endphp
                                        @if($perfil[0]['cal_areas_perfiles_id'] == $row['id'])
                                            @php
                                                $selected = "selected";
                                            @endphp
                                        @endif
                                        <option {{$selected}} value="{{$row['id']}}">{{$row["cal_area"]}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-4">
                                <label for="modulo" class="txt-bold">Módulos a asignar<span style="color: red;">*</span></label>
                            </div>
                            <div class="col-md-1">
                                <input type="hidden" class="campo-requerido" name="hiddenModulos" id="hiddenModulos" value="{{$modulos_select[0]['idmod']}}" />
                                <button id="addmod" type="button" title="Selecciona los módulos y da clic en el botón azul para agregar las módulos. Si deseas seleccionar más de un módulo, deja presionada la tecla Ctrl+Clic Izq." class="btn btn-primary btn-add text-center"><i class="fas fa-plus"></i></button>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <!-- <label for="area" class="txt-bold">Módulos<span style="color: red;">*</span></label> -->
                                <select multiple class= "form-control" name="modulos" id ="modulos">
                                    <!-- <option value="">Seleccione...</option> -->
                                    @foreach($modulo AS $row)
                                    @php
                                        $selected = "";
                                    @endphp
                                        @if($modulosmarcar != null)
                                            @foreach($modulosmarcar AS $marcar)
                                                @if($marcar == $row['id'])
                                                    @php
                                                        $selected = "selected";
                                                    @endphp
                                                @endif
                                            @endforeach
                                        @endif
                                            <option {{$selected}} value="{{$row['id']}}">{{$row["cal_alias"]}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="row">
                                <div id="content_list_modulosdds" class="col-md-12"></div>
                                <div class="col-md-12 divselectmultiple">
                                    <ul id="list_modulos">
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <a class="btn btn-warning col-md-12" style="color:#FFFFFF;" href="{{ route('listaperfiles') }}">Regresar</a>
                            </div>    
                            <div class="col-md-6">
                                <input type="submit" class="btn btn-primary col-md-12" value="Guardar"/>
                            </div>    
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/jquery_validate/jquery.validate.js') }}"></script>
<script>
    $.validator.messages.required = 'El campo es requerido.';

    jQuery.validator.addClassRules({
        'campo-requerido': {
            required: true
        }
    });
    $('#form_editperfil').validate({
        ignore: "",
        submitHandler: function(form) {
            mostrarLoading();
            setTimeout(form.submit(), 500);
        }
    });
    $(document).ready(function(){
        cargarLista();
        $("#addmod").click(function(){
                var tipo = 'Módulos';
                var hiddenIdTipo = '#hiddenModulos';
                var selectSelectedIdTipo = '#modulos option:selected';
                var divUlIdTipo = '#content_list_modulosdds';
                var ulIdTipo = '#list_modulos';
                var titleList = 'Lista de modulos agregados';

                $.each($(selectSelectedIdTipo), function() {
                    if($(this).val() != '') {
                        var valor = $(hiddenIdTipo).val();
                        var valSelect = $(this).val();
                        var html = '<li id="elementoLista_'+valSelect+'">'+$(this).text()+' <input type="checkbox" value="'+valSelect+'" class="checkremove_'+tipo+'"/></li>';
                        valSelect = valSelect.split('_');
                        
                        if(valor != '') {
                            if(compararRepetidosAutorizaciones(valor, valSelect[0]) === true) {
                                $(hiddenIdTipo).val(valor+'_'+valSelect[0]);
                                $(ulIdTipo).append(html);
                            } else {
                                swal(
                                    'Validación',
                                    'El '+tipo+' ya se ha agregado, vuelva a intentarlo con otro.',
                                    'warning'
                                )
                            }
                        } else {
                            $(divUlIdTipo).html('<strong>'+titleList+'</strong> <input type="button" class="quitarlista btn btn-danger btn-sm" data-lista="'+ulIdTipo+'" data-afectado="'+hiddenIdTipo+'" data-tipo="checkremove_'+tipo+'" value="Quitar de la lista" /> <label for="seleccionartodo"><input type="checkbox" class="seleccionartodo" id="seleccionartodo" data-tipo="'+tipo+'"/>Seleccionar todo</label>');
                            $(hiddenIdTipo).val(valSelect[0]);
                            $(ulIdTipo).append(html);
                        }
                    } else {
                        swal(
                            'Validación',
                            'Debe seleccionar un '+tipo+' antes de intentar agregarlo.',
                            'warning'
                        )
                    }
                });
                return;
            });

            $(document).on('click', '.seleccionartodo', function(){
                var clave = $(this).data('clave');
                var tipo = $(this).data('tipo');
                if($(this).is(':checked')) {
                    $('.checkremove_'+tipo).prop('checked', true);
                } else {
                    $('.checkremove_'+tipo).prop('checked', false);
                }
            });
            $(document).on('click', '.quitarlista', function(){
                var classCheckstipo = $(this).data('tipo');
                var idDelAfectado = $(this).data('afectado');
                var valorChecks = $('.'+classCheckstipo+':checked');
                var valorAfectado = $(idDelAfectado).val();
                var valorAfectadoArray = valorAfectado.split('_');
                var resultAfectado = '';
                
                if(valorChecks.length > 0) {
                    valorChecks.each(function() {
                        resultAfectado = quitarElementos(valorAfectadoArray, $(this).val());
                        $(idDelAfectado).val(resultAfectado);
                    });
                    
                } else {
                    swal(
                        'Remover de la lista',
                        'Debe seleccionar un elemento de la lista para remover.',
                        'warning'
                    )
                }
            });
        });
        function quitarElementos (arr, item) {
            var i = arr.indexOf( item );
            var result = '';
            $('#elementoLista_'+item).remove();
            arr.splice(i, 1);
            var count = 1;
            arr.forEach(function(valor, index) {
                if(count == arr.length) {
                    result += valor;
                } else {
                    result += valor+'_';
                    count = count+1;
                }
            });
            return result;
        }
        function compararRepetidosAutorizaciones(actuales, valorABuscar) {
            var valoresActuales = actuales.split('_');
            var valorABuscarAct = valorABuscar.split('_');

            if(Array.isArray(valoresActuales) == true) {
                if(valoresActuales.includes(valorABuscarAct[0]) == 1) {
                    return false;
                }
            } else {
                if(valorABuscarAct == valoresActuales) {
                    return false;
                }
            }

            return true;
        }
        function cargarLista() {
            var tipo = 'Modulos';
            var hiddenIdTipo = '#hiddenModulos';
            var selectSelectedIdTipo = '#modulos option:selected';
            var divUlIdTipo = '#content_list_modulosdds';
            var ulIdTipo = '#list_modulos';
            var titleList = 'Lista de modulos agregados';

            var valorActual = $(hiddenIdTipo).val();
            valorActual = valorActual.split('_');
            
            $.each(valorActual, function(k, v) {
                $.each($(selectSelectedIdTipo), function() {
                    if(v == $(this).val()) {
                        var valSelect = $(this).val();
                        var html = '<li id="elementoLista_'+valSelect+'">'+$(this).text()+' <input type="checkbox" value="'+valSelect+'" class="checkremove_'+tipo+'"/></li>';
                        $(divUlIdTipo).html('<strong>'+titleList+'</strong> <input type="button" class="quitarlista btn btn-danger btn-sm" data-lista="'+ulIdTipo+'" data-afectado="'+hiddenIdTipo+'" data-tipo="checkremove_'+tipo+'" value="Quitar de la lista" /> <label for="seleccionartodo"><input type="checkbox" class="seleccionartodo" id="seleccionartodo" data-tipo="'+tipo+'"/>Seleccionar todo</label>');
                        $(ulIdTipo).append(html);
                    }
                });
            });
            
            return;
        }
</script>
@endpush