@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Alta de opciones de catálogos') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{route('storeoptcat')}}">
                        @csrf
                        <input type="hidden" name="catalogos_id" id="catalogos_id" value="{{$catalogos_id}}"/>
                        <input type="hidden" name="idapp" id="idapp" value="{{$idapp}}"/>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="cat_op_descripcion" class="txt-bold">Nombre de la opción<span style="color: red;">*</span></label>
                                <input type="text" placeholder="Descripción de la opción..." class="form-control{{ $errors->has('cat_op_descripcion') ? ' is-invalid' : '' }}" value="{{ old('cat_op_descripcion') }}" name="cat_op_descripcion" required id="cat_op_descripcion"/>
                                        
                                @if ($errors->has('cat_op_descripcion'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('cat_op_descripcion') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <!-- <div class="col-md-6">
                                <label for="jerarquia" class="txt-bold">Jerarquía<span style="color: red;">*</span></label>
                                <input type="number" min="1" value="1" class="form-control" name="jerarquia" id="jerarquia" required />
                            </div> -->
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="cat_id" class="txt-bold">Dependencia con catálogo</label>
                                    <select class="form-control" name="cat_id" id="cat_id">
                                        <option value="">Seleccione...</option>
                                    @foreach($catalogos AS $row)
                                        <option value="{{$row['id']}}">{{$row["cat_nombre"]}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="cat_opciones_id" class="txt-bold">Dependencia con otra opción</label>
                                <select class="form-control" name="cat_opciones_id" id="cat_opciones_id">
                                    <option value="">Seleccione...</option>
                                
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-4">
                                <label for="autorizaciones" class="txt-bold">Dependencia con autorizaciones</label>
                            </div>
                            <div class="col-md-1">
                                <input type="hidden" name="hiddenAutorizaciones" id="hiddenAutorizaciones" />
                                <button id="addaut" type="button" title="Selecciona las autorizaciones y da clic en el botón azul para agregar las dependencias. Si deseas seleccionar más de una autorización, deja presionada la tecla Ctrl+Clic Izq." class="btn btn-primary btn-add text-center"><i class="fas fa-plus"></i></button>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <select multiple name="autorizaciones" id="autorizaciones" class="form-control">
                                    @foreach($autorizaciones AS $row)
                                        <option value="{{$row['id']}}">{{$row["rol_mod_rep"]}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="row">
                                <div id="content_list_autorizacionesadds" class="col-md-12"></div>

                                <div class="col-md-12 divselectmultiple">
                                    <ul id="list_autorizaciones">
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <a class="btn btn-warning col-md-12" style="color:#FFFFFF;" href="{{url('catalogos/listaopciones')}}/{{$catalogos_id}}/{{$idapp}}">Regresar</a>
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
    <script>
        $(document).ready(function(){
            $("#addaut").click(function(){
                var tipo = 'autorizaciones';
                var hiddenIdTipo = '#hiddenAutorizaciones';
                var selectSelectedIdTipo = '#autorizaciones option:selected';
                var divUlIdTipo = '#content_list_autorizacionesadds';
                var ulIdTipo = '#list_autorizaciones';
                var titleList = 'Lista de autorizaciones agregadas';

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
                            $(divUlIdTipo).html('<strong>'+titleList+'</strong> <input type="button" class="quitarlista btn btn-danger btn-sm" data-lista="'+ulIdTipo+'" data-afectado="'+hiddenIdTipo+'" data-tipo="checkremove_'+tipo+'" value="Quitar de la lista" /> <label for="seleccionartodo"><input type="checkbox" class="seleccionartodo" id="seleccionartodo" data-tipo="'+tipo+'" data-clave="{{$idapp}}"/>Seleccionar todo</label>');
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
    $(document).on('change','#cat_id', function(){
        var cat = $(this).val();
        $('#cat_opciones_id').empty();
        $('#cat_opciones_id').append("<option value=''>Selecciona una opcion valida</option>");
        $.ajax({
        url: "{{ route('opByCat') }}",
        dataType: "JSON",
        data:{cat: cat},
        success: function(response) {
            $.each(response,function(index,value){
                $('#cat_opciones_id').append("<option value='"+value.id+"'>"+value.cat_op_descripcion+"</option>");
             
            })
        }
    });
    });
    </script>
@endpush