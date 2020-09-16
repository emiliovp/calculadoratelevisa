@extends('layouts.app')
@section('content')
<style>
    .ui-autocomplete {
        z-index: 9999;
    }
</style>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card" >
                <div class="card-header">
                    <strong>Alta de nuevo usuario</strong>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('storeuser') }}" id="form-usr" accept-charset="UTF-8" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row">
                        <div class="col-md-6">
                                <label for="dominio" class="col-md-6 col-form-label text-md-left">{{ __('Dominio') }}</label>
                                <select class="form-control" name="dominio" id="dominio" required>
                                @if(old('dominio'))
                                    @php
                                        $dominioOld = old('dominio');
                                    @endphp
                                @else
                                    @php
                                        $dominioOld = '';
                                    @endphp
                                @endif
                                    <option value="" {{ empty($dominioOld) ? "selected" : "" }}>Seleccione...</option>
                                    <option value="1" {{ $dominioOld == 1 ? "selected" : "" }}>TELEVISA</option>
                                    <option value="2" {{ $dominioOld == 2 ? "selected" : "" }}>SOI</option>
                                </select>

                                @if ($errors->has('dominio'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('dominio') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <label for="u_red" class="col-md-6 col-form-label text-md-left">{{ __('Usuario de red') }}</label>
                                <input id="u_red" type="text" class="form-control{{ $errors->has('u_red') ? ' is-invalid' : '' }}" name="u_red" value="{{ old('u_red') }}" required {{ $errors->has('u_red') ? '' : 'readonly' }}>
                                @if ($errors->has('u_red'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('u_red') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="n_empleado" class="col-md-6 col-form-label text-md-left">{{ __('Número de empleado') }}</label>
                                <input id="n_empleado" type="text" class="form-control{{ $errors->has('n_empleado') ? ' is-invalid' : '' }}" name="n_empleado" value="{{ old('n_empleado') }}" readonly>
                            </div>
                                <div class="col-md-6">
                                    <label for="nom_empleado" class="col-md-6 col-form-label text-md-left">{{ __('Nombre del empleado') }}</label>
                                    <input id="nom_empleado" type="text" class="form-control{{ $errors->has('nom_empleado') ? ' is-invalid' : '' }}" name="nom_empleado" value="{{ old('nom_empleado') }}" readonly required>
                            </div>
                        </div>
                        <div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="perfil" class="col-md-6 col-form-label text-md-left">{{ __('Perfil de usuario') }}</label>
                                <select class="form-control{{ $errors->has('perfil') ? ' is-invalid' : '' }}" name="perfil" id ="perfil" required>
                                    <option value="">Seleccione...</option>
                                    @foreach($perfil AS $row)
                                        @php
                                            $selected = "";
                                        @endphp
                                        @if ($errors)
                                            @if($row['id'] == old('perfil'))
                                                @php
                                                    $selected = "selected";
                                                @endphp
                                            @endif
                                        @endif
                                        <option {{ $selected }} value="{{$row['id']}}">{{$row["perfil"]}}</option>
                                    @endforeach     
                                </select>
                                @if ($errors->has('perfil'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('perfil') }}</strong>
                                    </span>
                                @endif
                                <!-- <input id="n_empleado" type="text" class="form-control{{ $errors->has('n_empleado') ? ' is-invalid' : '' }}" name="n_empleado" value="" required> -->
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12 text-right">
                                <a class="btn btn-warning" style="color:#FFFFFF;" href="{{ route('ListaUsuarios') }}">Cancelar</a>
                                <!-- <button type="button" class="btn btn-success" id="guardar">Cancelar</button> -->
                               <!-- <button type="submit" class="btn btn-success" id="guardar">Guardar</button>-->
                               <input type="button" value="Guardar" id="enviar" class="btn btn-success" />
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
$(document).on('click','#enviar',function(e){
    Swal.fire({
        title: 'Alta de usuario',
        text: "¿Esta seguro que los datos son correctos?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Continuar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.value == true) {
            $('#form-usr').submit();  
        }
    });
});
$(document).on('change','#dominio', function(e){
    var val = $(this).val();
    var usr = $("#u_red").val();
    if (val !="") {
        $("#u_red").removeAttr('readonly');
        if (usr != "") {
            buscar(usr);
        }
    }else{
        $("#u_red").attr('readonly', true);
        $("#u_red").val('');
        $("#n_empleado").val('');
        $("#nom_empleado").val('');
        Swal.fire({
            icon: 'error',
            title: 'No se ha seleccionado un dominio',
            text: 'Para poder realizar la busqueda del usuario favor de seleccionar el dominio',
            // footer: '<a href>Why do I have this issue?</a>'
        });
    }
});
$(document).on('blur','#u_red',function(e){
    buscar($(this).val());
});
function buscar(usr){
    var dom = $("#dominio").val();
    $.ajax({
        url:"{{ route('getUsr') }}",
        dataType: "json",
            data:{term: usr, dom:dom},
        beforeSend:function(){
            mostrarLoading();
        },
        success: function(data)
        {
            ocultarLoading();
            console.log(data);
            if (data === 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Usuario no encontrado',
                    text: 'El usuario no se encuentra dentro del directorio activo',
                    // footer: '<a href>Why do I have this issue?</a>'
                });
                $('#u_red').val('');
                $('#n_empleado').val('');
                $('#nom_empleado').val('');
            }else if (data === 1) {
                Swal.fire({
                    icon: 'error',
                    title: 'No se encontró el número de empleado en el Directorio Activo.',
                    text: 'Favor de comunicarse al CAT para su actualización.',
                    // footer: '<a href>Why do I have this issue?</a>'
                });
                $('#u_red').val('');
                $('#n_empleado').val('');
                $('#nom_empleado').val('');
            }else if (data == "false" || data == false) {
                Swal.fire({
                    icon: 'error',
                    title: 'El usuario ya existente',
                    text: 'Favor de intentar con otro usuario de red.',
                    // footer: '<a href>Why do I have this issue?</a>'
                });
                $('#u_red').val('');
                $('#n_empleado').val('');
                $('#nom_empleado').val('');
            }else{
                $('#n_empleado').val(data.number_emp);
                $('#nom_empleado').val(data.nombre);
            }
        }
    });
}
</script>
@endpush