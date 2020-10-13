
@section('content')
<!-- <input type="hidden" id="modulo" value="fus_wintel" /> -->

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
<form method="POST" action="{{ action($route) }}" id="form-fus" accept-charset="UTF-8" enctype="multipart/form-data">
@csrf
<div class="container">
    <div class="row justify.content-center"> 
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <strong>
                        {{ __('ALTA DE SOLICITUD DE EQUIPO DE COMPUTO')}}
                    </strong>
                </div>
                <div class="card-body"> 
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="movimiento" class="col-md-8 col-form-label text-md-rigth">Tipo de operaci&oacute;n </label>
                            <select name="movimiento" id="movimiento" class="form-control{{ $errors->has('tio') ? ' is-invalid' : '' }}" required>
                                <option value='Alta'>Alta</option>
                                <!--<option value='Baja'>Baja</option>
                                <option value='Cambio'>Cambio</option>-->
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="col-md-12 col-form-label text-md-rigth">El usuario que solicita es: &nbsp;&nbsp;</label>
                            <input type="radio" id="d_ext1" name="d_ext" class="form-control d_ext" value="1" checked> Interno
                            &nbsp;&nbsp;
                            <input type="radio" id="d_ext2" name="d_ext" class="form-control d_ext" value="2"> Externo
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header" id="usuario_int">           
                    {{ __('Datos del solicitante')}}
                </div>
                <div class="card-header" id="usuario_ext" style="display:none">
                    {{ __('Datos del capturista')}}
                </div>
                <div class="card-body">
                     <div class="form-group row" id="sin_data1" style="display:none"> 
                        <div class="col-md-12">
                            <label class="col-md-8 col-form-label text-md-rigth"><span style="color:red">No se encontró el registro</span></label>
                        </div>
                    </div>
                    <div class="form-group row" id="aviso_soli" style="display:none"> 
                        <div class="col-md-12">
                            <label class="col-md-8 col-form-label text-md-rigth"><span style="color:red">El empleado no posee correo electronico, favor de validar la informacion</span></label>
                        </div>
                    </div>
                    <div class="form-group row" id="aviso_con_mail" style="display:none"> 
                        <div class="col-md-12">
                            <label class="col-md-8 col-form-label text-md-rigth"><span style="color:red">El empleado ya posee una cuenta, favor de validar la informacion</span></label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-4">
                            <label for="n_empleado" class="col-md-8 col-form-label text-md-rigth">No. Empleado </label>
                            @php
                                $emp = (isset($foo[0]['FICHA'])) ? ltrim($foo[0]['FICHA'], 0) : null;
                            $read = "";
                            @endphp
                            <input type="text" id="n_empleado" name="n_empleado" data-persona="1" data-type="n_empleado" class="form-control{{ $errors->has('n_empleado') ? ' is-invalid' : '' }} automat autocomplete campo-requerido" value="{{ old('n_empleado') ? old('n_empleado') : $emp }}" onKeyPress="return soloNumeros(event)" required >
                            @if ($errors->has('n_empleado'))
                                <p>{{ $errors->first('n_empleado') }}</p>
                            @endif
                        </div>
                        <div class="col-md-4">
                            <label for="mail_sol" class="col-md-12 col-form-label text-md-rigth">Correo electronico</label>
                            @php
                                $correo = (isset($foo[0]['mail'][0])) ? $foo[0]['mail'][0] : null;
                                if($errors->has('mail_sol')){
                                    $correo = null;
                                }
                            @endphp
                            <input type="email" id = "mail_sol" name="mail_sol" class="form-control{{ $errors->has('mail_sol') ? ' is-invalid' : '' }} campo-requerido" value="{{ (old('mail_sol') ) ? old('mail_sol') : $correo }}" readonly required>
                            @if ($errors->has('mail_sol'))
                                <p>{{ $errors->first('mail_sol') }}</p>
                            @endif
                        </div>                        
                        <div class="col-md-4">
                            <label for="u_red" class="col-md-12 col-form-label text-md-rigth">Usuario de red </label>
                            @php
                                $ured = (isset($foo[0]['u_red'][0])) ? $foo[0]['u_red'][0] : null;
                            @endphp
                            <input type="text" id="u_red" name="u_red" class="form-control{{ $errors->has('u_red') ? ' is-invalid' : '' }} campo-requerido" value="{{ (old('u_red')) ? old('u_red') : $ured }}" readonly>
                            @if ($errors->has('u_red'))
                                <p>{{ $errors->first('u_red') }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-4">
                            <label for="nombre" class="col-md-8 col-form-label text-md-rigth">Nombre </label>
                            @php
                                $nombre = (isset($foo[0]['NOMBRE'])) ? $foo[0]['NOMBRE'] : null;
                            @endphp
                            <input type="text" id="nombre" data-type="nombre" name="nombre" class="form-control{{ $errors->has('nombre') ? ' is-invalid' : '' }}" value="{{ (old('nombre')) ? old('nombre') : $nombre }}" required readonly>
                            @if ($errors->has('nombre'))
                                <p>{{ $errors->first('nombre') }}</p>
                            @endif
                        </div>
                        <div class="col-md-4">
                            <label for="a_paterno" class="col-md-8 col-form-label text-md-rigth">Apellido Paterno</label>
                            @php
                                $apat = (isset($foo[0]['APELLIDO'])) ? $foo[0]['APELLIDO'] : null;
                            @endphp
                            <input type="text" id="a_paterno" data-type="a_paterno" name="a_paterno" class="form-control{{ $errors->has('a_paterno') ? ' is-invalid' : '' }}" value="{{ (old('a_paterno')) ? old('a_paterno') : $apat }}" readonly>
                        </div>
                        <div class="col-md-4">
                            <label for="a_materno" class="col-md-8 col-form-label text-md-rigth">Apellido Materno</label>
                            @php
                                $amat = (isset($foo[0]['APELLIDOM'])) ? $foo[0]['APELLIDOM'] : null;
                            @endphp
                            <input type="text" id="a_materno" data-type="a_materno" name="a_materno" class="form-control{{ $errors->has('a_materno') ? ' is-invalid' : '' }}" value="{{ (old('a_materno')) ? old('a_materno') : $amat }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-4">
                            <label for="puesto" class="col-md-8 col-form-label text-md-rigth">Puesto </label>
                            @php
                                $puesto = (isset($foo[0]['PUESTO'])) ? $foo[0]['PUESTO'] : null;
                            @endphp
                            <input type="text" id="puesto" data-type="puesto" name="puesto" class="form-control{{ $errors->has('puesto') ? ' is-invalid' : '' }}" value="{{ ( old('puesto') ) ? old('puesto') : $puesto }}" readonly>
                        </div>
                        <div class="col-md-4">
                            <label for="ubi" class="col-md-8 col-form-label text-md-rigth">Ubicación </label>
                            @php
                                $ubi = (isset($foo[0]['UBICACION'])) ? $foo[0]['UBICACION'] : null;
                            @endphp
                            <input type="text" id="ubi" data-type="ubi" name="ubi" class="form-control{{ $errors->has('ubi') ? ' is-invalid' : '' }}" value="{{ (old('ubi') ) ? old('ubi') : $ubi }}" readonly>
                        </div>
                        <div class="col-md-4">
                            <label for="ext" class="col-md-8 col-form-label text-md-rigth">Ext.</label>
                            @php
                                $ext = (isset($foo[0]['EXTENSION'])) ? $foo[0]['EXTENSION'] : null;
                            @endphp
                            <input type="text" id="ext" data-type="ext" name="ext" class="form-control{{ $errors->has('ext') ? ' is-invalid' : '' }}" value="{{ ( old('ext') ) ? old('ext') : $ext }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="empresa" class="col-md-8 col-form-label text-md-rigth">Empresa </label>
                            <input type="text" id="empresa" data-type="empresa" name="empresa" class="form-control{{ $errors->has('empresa') ? ' is-invalid' : '' }}" value="{{ (!empty($foo[0]['EMPRESA'])) ? $foo[0]['EMPRESA'] : old('empresa') }}" readonly>
                        </div>
                        <div class="col-md-6">
                            @php
                            $dep = (isset($foo[0]['DEPARTAMENTO'])) ? $foo[0]['DEPARTAMENTO'] : '';
                            $cve_dep = (isset($foo[0]['CVE_DEPTO'])) ? $foo[0]['CVE_DEPTO']: '';
                            $dep_cve = $dep.' / '.$cve_dep;
                            @endphp
                            <label for="dep" class="col-md-8 col-form-label text-md-rigth">Departamento</label>
                            <input type="text" id="dep" data-type="dep" name="dep" class="form-control{{ $errors->has('dep') ? ' is-invalid' : '' }}" value="{{ ($dep_cve !== ' / ') ? $dep_cve : old('dep') }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                        @php
                        $c_c = (isset($foo[0]['DESCRIP'])) ? $foo[0]['DESCRIP'] : '';
                        $cve_c = (isset($foo[0]['CVE_CENTR'])) ? $foo[0]['CVE_CENTR']: '';
                        $c_cve = $c_c.' / '.$cve_c;
                        @endphp
                            <label for="c_costos" class="col-md-8 col-form-label text-md-rigth"> Centro de costos</label>
                            <input type="text" id = "c_costos" data-type="c_costos" name="c_costos" class="form-control{{ $errors->has('c_costos') ? ' is-invalid' : '' }}" value="{{ ($c_cve !== ' / ') ? $c_cve : old('c_costos') }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label for="vigencia_i" class="col-md-8 col-form-label text-md-rigth">Vigencia</label>
                            @php
                                $fvigencia = (isset($foo[0]['FECHAVIGENCIA'])) ? $foo[0]['FECHAVIGENCIA'] : null;
                            @endphp
                            <input  id="vigencia_i" data-type="vigencia_i" name="vigencia_i" class="form-control{{ $errors->has('vigencia_i') ? ' is-invalid' : '' }}" value="{{ ( old('vigencia_i') ) ? old('vigencia_i') : $fvigencia }}" readonly>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    {{ __('Responsable o jefe del solicitante')}}
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-5">
                            <div class="form-group row" id="sin_mail_jefe" style="display:none"> 
                                <div class="col-md-12">
                                    <label class="col-md-8 col-form-label text-md-rigth"><span style="color:red">El empleado no posee correo electronico, favor de validar la informacion</span></label>
                                </div>
                            </div>
                            <label for="n_jefe" class="col-md-12 col-form-label text-md-rigth">Número de empleado del responsable o jefe del solicitante</label>
                            <input  id="n_jefe" name="n_jefe" data-persona="2" data-type="n_jefe" class="autocomplete form-control{{ $errors->has('n_jefe') ? ' is-invalid' : '' }} automat" placeholder="Ej: 000001" value="{{ old('n_jefe') }}" onKeyPress="return soloNumeros(event)" required>
                            @if ($errors->has('n_jefe'))
                                <p>{{ $errors->first('n_jefe') }}</p>
                            @endif
                        </div>
                        <div class="col-md-4">
                            <label for="correo_jefe" class="col-md-8 col-form-label text-md-rigth">Correo</label>
                            <input type="email" id="correo_jefe" name="correo_jefe" data-type="correo_jefe" class="form-control{{ $errors->has('correo_jefe') ? ' is-invalid' : '' }}" value="{{ old('correo_jefe') }}" required readonly>
                             @if ($errors->has('correo_jefe'))
                                <p>{{ $errors->first('correo_jefe') }}</p>
                            @endif
                        </div>
                        <div class="col-md-3">
                            <label for="puesto_jefe" class="col-md-8 col-form-label text-md-rigth">Puesto</label>
                            <input  id="puesto_jefe" name="puesto_jefe" data-type="puesto_jefe" class="form-control campo-requerido{{ $errors->has('puesto_jefe') ? ' is-invalid' : '' }}" value="{{ old('puesto_jefe') }}" readonly>
                            @if ($errors->has('puesto_jefe'))
                                <p>{{ $errors->first('puesto_jefe') }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-4">
                            <label for="nom_jefe" class="col-md-8 col-form-label text-md-rigth">Nombre</label>
                            <input  id="nom_jefe" name="nom_jefe" data-type="nom_jefe" class="form-control{{ $errors->has('nom_jefe') ? ' is-invalid' : '' }}" value="{{ old('nom_jefe') }}" readonly>
                        </div>
                        <div class="col-md-4">
                            <label for="apat_jefe" class="col-md-8 col-form-label text-md-rigth">Apellido paterno</label>
                            <input  id="apat_jefe" name="apat_jefe" data-type="apat_jefe" class="form-control{{ $errors->has('apat_jefe') ? ' is-invalid' : '' }}" value="{{ old('apat_jefe') }}" readonly>
                        </div>
                        <div class="col-md-4">
                            <label for="amat_jefe" class="col-md-8 col-form-label text-md-rigth">Apellido materno</label>
                            <input  id="amat_jefe" name="amat_jefe" data-type="amat_jefe" class="form-control{{ $errors->has('amat_jefe') ? ' is-invalid' : '' }}" value="{{ old('amat_jefe') }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12 col-form-label text-md-rigth"><span style="color:black"><strong><em>* Es responsabilidad del capturista verificar que el responsable o jefe del solicitante sea el correcto</strong></em></span></label>
                    </div>
                </div>
            </div>
            <div id="div_aut_1" class="card">
                <div class="card-header">
                {{ __('Datos del autorizador')}}
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-4">
                            <label for="n_aut" class="col-md-12 col-form-label text-md-rigth">Número de empleado del autorizador</label>
                            <input  id="n_aut" name="n_aut" onKeyPress="return soloNumeros(event)" data-persona="3" data-type="n_aut" class="form-control autocomplete automat {{ $errors->has('n_aut') ? ' is-invalid' : '' }}" value="{{ old('n_aut') }}" >
                            @if ($errors->has('n_aut'))
                                <p>{{ $errors->first('n_aut') }}</p>
                            @endif
                        </div>
                        <div class="col-md-3">
                            <label for="correo_aut" class="col-md-8 col-form-label text-md-rigth">Correo</label>
                            <input type="email" id="correo_aut" name="correo_aut" data-type="correo_aut" class="form-control{{ $errors->has('correo_aut') ? ' is-invalid' : '' }}" value="{{ old('correo_aut') }}" readonly>
                            @if ($errors->has('correo_aut'))
                                <p>{{ $errors->first('correo_aut') }}</p>
                            @endif
                        </div>
                        <div class="col-md-5">
                            <label for="puesto_aut" class="col-md-8 col-form-label text-md-rigth">Puesto</label>
                            <input  id="puesto_aut" name="puesto_aut" data-type="puesto_aut" class="form-control{{ $errors->has('puesto_aut') ? ' is-invalid' : '' }}" value="{{ old('puesto_aut') }}" readonly>
                            @if ($errors->has('puesto_aut'))
                                <p>{{ $errors->first('puesto_aut') }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-4">
                            <label for="nom_aut" class="col-md-8 col-form-label text-md-rigth">Nombre</label>
                            <input  id="nom_aut" name="nom_aut" data-type="nom_aut" class="form-control{{ $errors->has('nom_aut') ? ' is-invalid' : '' }}" value="{{ old('nom_aut') }}" readonly>
                        </div>
                        <div class="col-md-4">
                            <label for="apat_aut" class="col-md-8 col-form-label text-md-rigth">Apellido paterno</label>
                            <input  id="apat_aut" name="apat_aut" data-type="apat_aut" class="form-control{{ $errors->has('apat_aut') ? ' is-invalid' : '' }}" value="{{ old('apat_aut') }}" readonly>
                        </div>
                        <div class="col-md-4">
                            <label for="amat_aut" class="col-md-8 col-form-label text-md-rigth">Apellido materno</label>
                            <input  id="amat_aut" name="amat_aut" data-type="amat_aut" class="form-control{{ $errors->has('amat_aut') ? ' is-invalid' : '' }}" value="{{ old('amat_aut') }}" readonly>
                        </div>
                    </div>
                </div>
            </div>
            <div style="display:none" id="ficha_t" class="card">
                <div class="card-header">
                {{ __('Datos del externo')}}
                </div>
                <div class="card-body">
                    <div class="form-group row" id="aviso_con_mail_terce" style="display:none"> 
                        <div class="col-md-12">
                            <label class="col-md-8 col-form-label text-md-rigth"><span style="color:red">El empleado ya posee una cuenta, favor de validar la informacion</span></label>
                        </div>
                    </div>
                    <div class="form-group row" id="sin_data4" style="display:none"> 
                        <div class="col-md-12">
                            <label class="col-md-8 col-form-label text-md-rigth"><span style="color:red">No se encontró el registro</span></label>
                        </div>
                    </div>
                     <div class="form-group row" id="sin_num_t" style="display:none"> 
                        <div class="col-md-12">
                            <label class="col-md-12 col-form-label text-md-rigth"><span style="color:red">Faltan capturar datos del externo</span></label>
                        </div>
                    </div>
                    <div class="form-group row">  
                        <div class="col-md-5">
                            <label for="ficha_ter" class="col-md-8 col-form-label text-md-rigth">Ficha</label>
                            <input type="text" data-persona="4" id="ficha_ter" data-type="ficha_ter" name="ficha_ter" class="form-control{{ $errors->has('ficha_ter') ? ' is-invalid' : '' }} autocomplete automat tercero" placeholder="Ej: 00001"value="{{ old('ficha_t') }}" onKeyPress="return soloNumeros(event)">
                            @if ($errors->has('ficha_t'))
                                <p>{{ $errors->first('ficha_t') }}</p>
                            @endif
                        </div>
                        <div class="col-md-1" id="ayuda">
                            <button type="button" class="form-control btn btn-default" id="btn_ayuda">
                              <span class="fas fa-info"></span>
                            </button>
                        </div>
                        <div class="col-md-6">
                            <label for="empresa_t" class="col-md-8 col-form-label text-md-rigth">Empresa</label>
                            <input type="text" id="empresa_t" data-type="empresa_t" name="empresa_t" class="form-control{{ $errors->has('empresa_t') ? ' is-invalid' : '' }}" value="{{ old('empresa_t') }}" readonly>
                            @if ($errors->has('empresa_t'))
                                <p>{{ $errors->first('empresa_t') }}</p>
                            @endif
                        </div>
                    </div> 
                    <div style="display:none" id="datos_t" class="form-group row terceros">   
                        <div class="col-md-4">
                            <label for="nombre_t" class="col-md-8 col-form-label text-md-rigth">Nombre </label>
                            <input type="text" id="nombre_t" data-type="nombre_t" name="nombre_t" class="form-control{{ $errors->has('nombre_t') ? ' is-invalid' : '' }}" value="{{ old('nombre_t') }}" readonly>
                            @if ($errors->has('nombre_t'))
                                <p>{{ $errors->first('nombre_t') }}</p>
                            @endif
                        </div>
                        <div class="col-md-4">
                            <label for="a_pat_t" class="col-md-8 col-form-label text-md-rigth">Apellido Paterno </label>
                            <input type="text" id="a_pat_t" data-type="a_pat_t" name="a_pat_t" class="form-control{{ $errors->has('a_pat_t') ? ' is-invalid' : '' }}" value="{{ old('a_pat_t') }}" readonly>
                        </div>
                        <div class="col-md-4">
                            <label for="a_mat_t" class="col-md-8 col-form-label text-md-rigth">Apellido Materno </label>
                            <input type="text" id="a_mat_t" data-type="a_mat_t" name="a_mat_t" class="form-control{{ $errors->has('a_mat_t') ? ' is-invalid' : '' }}" value="{{ old('a_mat_t') }}" readonly>
                        </div>
                    </div>
                    <div style="display:none" id="comp_t" class="form-group row terceros">
                        <div class="col-md-4">
                            <label for="ubicacion_t" class="col-md-8 col-form-label text-md-rigth">Ubicacion </label>
                            <input type="text" id="ubicacion_t" data-type="ubicacion_t" name="ubicacion_t" class="form-control{{ $errors->has('ubicacion_t') ? ' is-invalid' : '' }}" value="{{ old('ubicacion_t') }}" readonly>
                        </div>
                        <div class="col-md-4">
                            <label for="proyecto" class="col-md-8 col-form-label text-md-rigth">Proyecto </label>
                            <input type="text" id="proyecto" data-type="proyecto" name="proyecto" class="form-control{{ $errors->has('proyecto') ? ' is-invalid' : '' }}" value="{{ old('proyecto') }}" readonly>
                        </div>
                        <div class="col-md-4">
                            <label for="vigencia" class="col-md-8 col-form-label text-md-rigth">Vigencia </label>
                            <input type="text" id="vigencia" data-type="vigencia" name="vigencia" class="form-control{{ $errors->has('vigencia') ? ' is-invalid' : '' }}" value="{{ old('vigencia') }}" readonly>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{ $body }}            
@endsection
@push('scripts')
<script src="{{ asset('js/jquery_validate/jquery.validate.js') }}"></script>
<script type="text/javascript">
$( window ).on( "load", fusApp() );
function fusApp(){
    var t_fus="";
    a = $(".d_ext:checked").val();
    @if(old('d_ext') == 2)
    $("#d_ext1").prop('checked', false);
    $("#d_ext2").prop('checked', true);
    $('#usuario_int').hide();
    $('#usuario_ext').show();
    $('#div_aut_1').show();
    $('#ficha_t').show();
    $('#datos_t').show();
    $('#comp_t').show();
    @endif
}
function soloNumeros(e)
{
    var key = window.Event ? e.which : e.keyCode;
    return ((key >= 48 && key <= 57) || (key==8));
}
$(document).on('focus','.autocomplete', function()
{
    type = $(this).data('type');
    var a = $(".d_ext:checked").val();
    tpers = $(this).data('persona');
    valor = $(this).val();
    var t_buscar = 1;
    if(tpers == 1){
        if(type =='n_empleado')autoType='FICHA';
    }else if (tpers == 2){
        var autroiz = $('#n_aut').val();
        if(type =='n_jefe')autoType='FICHA';
    }else if(tpers == 3){
        if(type =='n_aut')autoType='FICHA';  
    }else{
        if(type =='ficha_ter')autoType='FICHA';
        t_buscar = 2;
    }
    return buscar(type,autoType,t_buscar);
});
function buscar(type, autoType, t_buscar){
    tpers = $('#'+type).data('persona');
    valor = $('#'+type).val();
    if(tpers == 1){
        if(type =='n_empleado')autoType='FICHA';
    }else if (tpers == 2){
        var autroiz = $('#n_aut').val();
        if(type =='n_jefe')autoType='FICHA';
    }else if(tpers == 3){
        var jefe = $('#n_jefe').val();
        if(type =='n_empleado')autoType='FICHA';  
    }else{
        if(type =='n_empleado')autoType='FICHA';
    }
$('#'+type).autocomplete({
        minLength:0,
        source: function( request, response)
        {
            $.ajax({
                url:"{{ route('sol.autocomplete') }}",
                dataType: "json",
                data:{
                    term: request.term,
                    type: type,
                    search: t_buscar
               },
                success: function(data)
                {
                    var array = $.map(data, function(item){
                        var response = "";
                        if(item[autoType] !== undefined && item[autoType] !== undefined) {
                            response = {
                                label: parseInt(item[autoType], 10),
                                value: parseInt(item[autoType], 10),
                                data: item
                            }
                        } 
                        else if(item != "")
                        {
                            response = {
                                label: item,
                                value: item,
                                data: "fail"
                            }
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
            if (data.respuesta != "No se encontro el registro")
            {
                if(tpers == 1){
                    var resp = b_ad(parseInt(data.FICHA, 10));
                    if (resp.responseJSON.mail == null) {
                        var mail = resp.responseJSON.mail;
                        var ured = resp.responseJSON.u_red;
                    }else{
                        var mail = resp.responseJSON.mail[0];
                        var ured = resp.responseJSON.u_red[0];
                    }
                    if(mail){
                        $('#n_empleado').val(parseInt(data.FICHA, 10));
                        $('#mail_sol').val(mail);
                        $('#u_red').val(ured);
                        $('#nombre').val(data.NOMBRE);
                        $('#a_paterno').val(data.APELLIDO);
                        $('#a_materno').val(data.APELLIDOM);
                        $('#puesto').val(data.PUESTO);
                        $('#empresa').val(data.EMPRESA);
                        $('#dep').val(data.DEPARTAMENTO+' / '+data.CVE_DEPTO);
                        $('#ubi').val(data.UBICACION);
                        $('#jefe').val(data.JEFE);
                        $('#ext').val(data.EXTENSION);
                        $('#c_costos').val(data.DESCRIP+' / '+data.CVE_CENTR);
                        $('#vigencia_i').val(data.FECHAVIGENCIA);
                        $('#aviso_con_mail').hide();
                        $('#aviso_soli').hide();
                    }else{
                        $('#n_empleado').val('');
                        $('#mail_sol').val('');
                        $('#u_red').val('');
                        $('#nombre').val('');
                        $('#a_paterno').val('');
                        $('#a_materno').val('');
                        $('#puesto').val('');
                        $('#empresa').val('');
                        $('#dep').val('');
                        $('#ubi').val('');
                        $('#jefe').val('');
                        $('#ext').val('');
                        $('#c_costos').val('');
                        $('#vigencia_i').val('');
                        Swal.fire({
                            icon: 'error',
                            title: 'Alerta',
                            text: 'El usuario no cuenta con correo electronico'    
                          // footer: '<a href>Why do I have this issue?</a>'
                        });
                    }
                }else if(tpers == 2){
                    var resp = b_ad(parseInt(data.FICHA, 10));
                    if (resp.responseJSON.mail == null) {
                        var mail = resp.responseJSON.mail;
                    }else{
                        var mail = resp.responseJSON.mail[0];
                    }
                    if(mail){
                        if (parseInt(data.FICHA, 10) != autroiz){
                            $('#n_jefe').val(parseInt(data.FICHA, 10)); 
                            $('#correo_jefe').val(mail);
                            $('#nom_jefe').val(data.NOMBRE);
                            $('#apat_jefe').val(data.APELLIDO);
                            $('#amat_jefe').val(data.APELLIDOM);
                            $('#puesto_jefe').val(data.PUESTO);
                            $('#sin_mail_jefe').hide();
                            $('#aviso_jefe').hide();
                            $('#rep_sus').hide();
                        }else{
                            $('#n_jefe').val('');
                            $('#correo_jefe').val('')
                            $('#nom_jefe').val('');
                            $('#apat_jefe').val('');
                            $('#amat_jefe').val('');
                            $('#puesto_jefe').val('');
                            Swal.fire({
                                icon: 'error',
                                title: 'Alerta',
                                text: 'El jefe no puede ser autorizador'    
                              // footer: '<a href>Why do I have this issue?</a>'
                            });
                        }
                    }else{
                        $('#n_jefe').val('');
                        $('#correo_jefe').val('')
                        $('#nom_jefe').val('');
                        $('#apat_jefe').val('');
                        $('#amat_jefe').val('');
                        $('#puesto_jefe').val('');
                        // $('#sin_mail_jefe').show();
                        Swal.fire({
                            icon: 'error',
                            title: 'Alerta',
                            text: 'El usuario no cuenta con correo electronico'    
                          // footer: '<a href>Why do I have this issue?</a>'
                        });
                    }
                }else if(tpers == 3){
                    var resp = b_ad(parseInt(data.FICHA, 10));
                    if (resp.responseJSON.mail == null) {
                        var mail = resp.responseJSON.mail;
                    }else{
                        var mail = resp.responseJSON.mail[0];
                    }
                    if(mail){
                        if (parseInt(data.FICHA, 10) != jefe){
                            $('#n_aut').val(parseInt(data.FICHA, 10));
                            $('#correo_aut').val(mail);
                            $('#nom_aut').val(data.NOMBRE);
                            $('#apat_aut').val(data.APELLIDO);
                            $('#amat_aut').val(data.APELLIDOM);
                            $('#puesto_aut').val(data.PUESTO);
                            $('#aviso_jefe').hide();
                            $('#aviso_aut').hide();
                            $('#rep_aut').hide();
                            $('#req_aut').hide();
                            $('#sin_data3').hide();
                        }else{
                            // $('#rep_sus').show();
                            $('#n_aut').val('');
                            $('#correo_aut').val('')
                            $('#nom_aut').val('');
                            $('#apat_aut').val('');
                            $('#amat_aut').val('');
                            $('#puesto_aut').val('');
                            $('#sin_mail_jefe').hide();
                            Swal.fire({
                                icon: 'error',
                                title: 'Alerta',
                                text: 'El autorizador no puede ser jefe'
                              // footer: '<a href>Why do I have this issue?</a>'
                            });
                        }
                    }else{
                        $('#n_jefe').val('');
                        $('#correo_jefe').val('')
                        $('#nom_jefe').val('');
                        $('#apat_jefe').val('');
                        $('#amat_jefe').val('');
                        $('#puesto_jefe').val('');
                        // $('#sin_mail_jefe').show();
                        Swal.fire({
                            icon: 'error',
                            title: 'Alerta',
                            text: 'El usuario no cuenta con correo electronico'    
                          // footer: '<a href>Why do I have this issue?</a>'
                        });
                    }
                }else if(tpers == 4){
                    $('#ficha_ter').val(parseInt(data.FICHA, 10));
                    $('#empresa_t').val(data.EMPRESA);
                    $('#nombre_t').val(data.NOMBRE);
                    $('#a_pat_t').val(data.APELLIDO);
                    $('#a_mat_t').val(data.APELLIDOM);
                    $('#ubicacion_t').val(data.UBICACION);
                    $('#proyecto').val(data.PROYECTO_EXT);
                    $('#vigencia').val(data.VIGENCIA);
                }
            } else if (data.respuesta == "No se encontro el registro") {
                event.stopImmediatePropagation();
                event.preventDefault();
            }
        }
        });
    }
function b_ad(employeeid){
    return $.ajax({
        url:"{{ route('sol.ad') }}",
        dataType: "json",
        async:false,
        data:{n_emp : employeeid
        }
    })
}
$(document).on('click','.d_ext', function(){
    a = $(".d_ext:checked").val();
    $('#n_empleado').val('');
    $('#mail_sol').val('');
    $('#u_red').val('');
    $('#nombre').val('');
    $('#a_paterno').val('');
    $('#a_materno').val('');
    $('#puesto').val('');
    $('#empresa').val('');
    $('#dep').val('');
    $('#ubi').val('');
    $('#jefe').val('');
    $('#ext').val('');
    $('#c_costos').val('');
    $('#vigencia_i').val('');
    $('#n_jefe').val('');
    $('#correo_jefe').val('')
    $('#nom_jefe').val('');
    $('#apat_jefe').val('');
    $('#amat_jefe').val('');
    $('#puesto_jefe').val('');
    $('#n_aut').val('')
    $('#correo_aut').val('')
    $('#nom_aut').val('');
    $('#apat_aut').val('');
    $('#amat_aut').val('');
    $('#puesto_aut').val('');
    $('#ficha_ter').val('');
    $('#empresa_t').val('');              
    $('#nombre_t').val('');               
    $('#a_mat_t').val('');               
    $('#ubicacion_t').val('');               
    $('#proyecto').val('');               
    $('#vigencia').val('');
    t_fus =  $("#tipo_fus").val();
    if(a == '2'){
        $('#usuario_ext').show();
        $('#usuario_int').hide();
        $('#ficha_t').show();
        $('#datos_t').show();
        $('#comp_t').show();
        $('#div_aut_1').show();
        var element1 = document.getElementById("ficha_ter");
        element1.classList.add("campo-requerido");
        var element2 = document.getElementById("nombre_t");
        element2.classList.add("campo-requerido");
        var element3 = document.getElementById("a_pat_t");
        element3.classList.add("campo-requerido");
        var element4 = document.getElementById("a_mat_t");
        element4.classList.add("campo-requerido");
        var element5 = document.getElementById("empresa_t");
        element5.classList.add("campo-requerido");
        var element6 = document.getElementById("ubicacion_t");
        element6.classList.add("campo-requerido");
        if (t_fus == 0){
            var element9 = document.getElementById("n_aut");
            element9.classList.add("campo-requerido");
            var element10 = document.getElementById("correo_aut");
            element10.classList.add("campo-requerido");
        }
    }else{ 
        var t_fus="";
        t_fus =  $("#tipo_fus").val(); 
        // document.getElementById("div_aut_1").style.display = "none";
        var element1 = document.getElementById("ficha_ter");
        element1.classList.remove("campo-requerido");
        var element2 = document.getElementById("nombre_t");
        element2.classList.remove("campo-requerido");
        var element3 = document.getElementById("a_pat_t");
        element3.classList.remove("campo-requerido");
        var element4 = document.getElementById("a_mat_t");
        element4.classList.remove("campo-requerido");
        var element5 = document.getElementById("empresa_t");
        element5.classList.remove("campo-requerido");
        var element6 = document.getElementById("ubicacion_t");
        element6.classList.remove("campo-requerido");
        /*var element7 = document.getElementById("proyecto");
        element7.classList.remove("campo-requerido");
        var element8 = document.getElementById("vigencia");
        element8.classList.remove("campo-requerido");*/
        if (t_fus == 0){
            var apps = $('#apps').val();
            var app = JSON.parse(apps);
            for (var clave in app){
                if (clave != 50) {
                    document.getElementById("div_aut_1").style.display = "none";
                    var element9 = document.getElementById("n_aut");
                    element9.classList.remove("campo-requerido");
                    var element10 = document.getElementById("correo_aut");
                    element10.classList.remove("campo-requerido");
                }
            }
        }
        $('#usuario_int').show();
        $('#usuario_ext').hide();
        $('#ficha_t').hide();
        $('#datos_t').hide();
        $('#comp_t').hide();
    }
});
</script>
@endpush