
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
            @if($tipo_fus != 3)
            <div class="card">
                <div class="card-header">
                    <strong>
                {{ __('ALTA DE FUS')}}
                    </strong>
                </div>
                <div class="card-body"> 
                    <div class="form-group row">
                        @if($tipo_fus == 1 || $tipo_fus == 2)
                        <div class="col-md-6">
                            <label for="movimiento" class="col-md-8 col-form-label text-md-rigth">Tipo de operaci&oacute;n </label>
                            <select name="movimiento" id="movimiento" class="form-control{{ $errors->has('tio') ? ' is-invalid' : '' }}" required>
                                <!-- <option value="">Selecciona una opcion valida</option> -->
                                <option value='Alta'>Alta</option>
                                <option value='Baja'>Baja</option>
                                <option value='Cambio'>Cambio</option>
                            </select>
                        </div>
                        @endif
                        <div class="col-md-3">
                            <label class="col-md-12 col-form-label text-md-rigth">El usuario que solicita es: &nbsp;&nbsp;</label>
                                <input type="radio" id="d_ext1" name="d_ext" class="form-control d_ext" value="1" checked> Interno
                                &nbsp;&nbsp;
                                <input type="radio" id="d_ext2" name="d_ext" class="form-control d_ext" value="2"> Externo
                        </div>
                        <div class="col-md-3">
                            
                        </div>
                        <!-- <hr> -->
                    </div>
                </div>
            </div>
            @elseif($tipo_fus == 3)
            <div class="card">
                <div class="card-header">
                    <strong>
                {{ __('ALTA DE FUS')}}
                    </strong>
                </div>
                <div class="card-body"> 
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="movimiento" class="col-md-8 col-form-label text-md-rigth">Tipo de operaci&oacute;n </label>
                            <select name="movimiento" id="movimiento" class="form-control{{ $errors->has('tio') ? ' is-invalid' : '' }}" required>
                                <!-- <option value="">Selecciona una opcion valida</option> -->
                                <option value='Alta'>Alta</option>
                                <option value='Baja'>Baja</option>
                                <option value='Cambio'>Cambio</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            <div class="card">
                <div class="card-header" id="usuario_int">
                @if($tipo_fus == 1 || $tipo_fus == 2)
                    {{ __('Datos del nuevo usuario')}}
                @else
                    {{ __('Datos del solicitante')}}
                @endif
                </div>
                <div class="card-header" id="usuario_ext" style="display:none">
                    {{ __('Datos del capturista')}}
                </div>
                <div class="card-body">
                   <!--  <label><strong>Datos del solicitante</strong></label>
                     <hr>-->
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
                            if($tipo_fus == 3){
                                $read = "readonly";
                            }
                            
                            @endphp
                            <input type="text" id="n_empleado" name="n_empleado" data-persona="1" data-type="n_empleado" class="form-control autocomplete_interno automat campo-requerido" value="{{ ($emp !== null) ? $emp : old('n_empleado') }}" onKeyPress="return soloNumeros(event)" required {{ $read }}>
                            @if ($errors->has('n_empleado'))
                                <p>{{ $errors->first('n_empleado') }}</p>
                            @endif
                        </div>
                        <div class="col-md-4">
                        @if($tipo_fus == 1 || $tipo_fus == 2)
                            <label for="mail_sol" class="col-md-12 col-form-label text-md-rigth">Correo electronico de seguimiento</label>
                            <input type="email" id = "mail_sol" name="mail_sol" class="form-control campo-requerido" value="{{ (!empty($foo2[0]['mail'])) ? $foo2[0]['mail'] : old('mail_sol') }}" readonly required>
                            @if ($errors->has('mail_sol'))
                                <p>{{ $errors->first('mail_sol') }}</p>
                            @endif
                        @else
                            <label for="mail_sol" class="col-md-12 col-form-label text-md-rigth">Correo electronico</label>
                            <input type="email" id = "mail_sol" name="mail_sol" class="form-control campo-requerido" value="{{ (!empty($foo[0]['mail'])) ? $foo[0]['mail'] : old('mail_sol') }}" readonly required>
                            @if ($errors->has('mail_sol'))
                                <p>{{ $errors->first('mail_sol') }}</p>
                            @endif
                        @endif
                        </div>                        
                        <div class="col-md-4">
                            @if($tipo_fus == 1 || $tipo_fus == 2)
                                <label for="u_red" class="col-md-12 col-form-label text-md-rigth">Usuario de red de seguimiento</label>
                                <input type="text" id="u_red" name="u_red" class="form-control campo-requerido" value="{{ (!empty($foo2[0]['u_red']))  ? $foo2[0]['u_red'] : old('u_red') }}" readonly>
                                @if ($errors->has('u_red'))
                                    <p>{{ $errors->first('u_red') }}</p>
                                @endif
                            @else
                                <label for="u_red" class="col-md-12 col-form-label text-md-rigth">Usuario de red </label>
                                 <input type="text" id="u_red" name="u_red" class="form-control campo-requerido" value="{{ (!empty($foo[0]['u_red'] )) ? $foo[0]['u_red'] : old('u_red') }}" readonly>
                                 @if ($errors->has('u_red'))
                                    <p>{{ $errors->first('u_red') }}</p>
                                @endif
                            @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-4">
                            <label for="nombre" class="col-md-8 col-form-label text-md-rigth">Nombre </label>
                            <input type="text" id="nombre" data-type="nombre" name="nombre" class="form-control{{ $errors->has('nombre') ? ' is-invalid' : '' }}" value="{{ (!empty($foo[0]['NOMBRE'])) ? $foo[0]['NOMBRE'] : old('nombre') }}" required readonly>
                            @if ($errors->has('nombre'))
                                <p>{{ $errors->first('nombre') }}</p>
                            @endif
                        </div>
                        <div class="col-md-4">
                            <label for="a_paterno" class="col-md-8 col-form-label text-md-rigth">Apellido Paterno</label>
                            <input type="text" id="a_paterno" data-type="a_paterno" name="a_paterno" class="form-control{{ $errors->has('a_paterno') ? ' is-invalid' : '' }}" value="{{ (!empty($foo[0]['APELLIDO'] )) ? $foo[0]['APELLIDO'] : old('a_paterno') }}" readonly>
                        </div>
                        <div class="col-md-4">
                            <label for="a_materno" class="col-md-8 col-form-label text-md-rigth">Apellido Materno</label>
                            <input type="text" id="a_materno" data-type="a_materno" name="a_materno" class="form-control{{ $errors->has('a_materno') ? ' is-invalid' : '' }}" value="{{ (!empty($foo[0]['APELLIDOM'])) ? $foo[0]['APELLIDOM'] : old('a_materno') }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-4">
                            <label for="puesto" class="col-md-8 col-form-label text-md-rigth">Puesto </label>
                            <input type="text" id="puesto" data-type="puesto" name="puesto" class="form-control{{ $errors->has('puesto') ? ' is-invalid' : '' }}" value="{{ (!empty($foo[0]['PUESTO'] )) ? $foo[0]['PUESTO'] : old('puesto') }}" readonly>
                        </div>
                        <div class="col-md-4">
                            <label for="ubi" class="col-md-8 col-form-label text-md-rigth">Ubicación </label>
                            <input type="text" id="ubi" data-type="ubi" name="ubi" class="form-control{{ $errors->has('ubi') ? ' is-invalid' : '' }}" value="{{ (!empty($foo[0]['UBICACION'])) ? $foo[0]['UBICACION'] : old('ubi') }}" readonly>
                        </div>
                        <!-- <div class="col-md-3">
                            <label for="tel" class="col-md-8 col-form-label text-md-rigth">Telefono</label>
                            <input type="text" id="tel" data-type="tel" name="tel" class="form-control{{ $errors->has('tel') ? ' is-invalid' : '' }}" value="" readonly>
                        </div> -->
                        <div class="col-md-4">
                            <label for="ext" class="col-md-8 col-form-label text-md-rigth">Ext.</label>
                            <input type="text" id="ext" data-type="ext" name="ext" class="form-control{{ $errors->has('ext') ? ' is-invalid' : '' }}" value="{{ (!empty($foo[0]['EXTENSION'])) ? $foo[0]['EXTENSION'] : old('ext') }}" readonly>
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
                        <!-- <div class="col-md-4">
                            <label for="area" class="col-md-8 col-form-label text-md-rigth">Área </label>
                            <input type="text" id="area" data-type="area" name="area" class="form-control{{ $errors->has('area') ? ' is-invalid' : '' }}" value="" readonly>
                        </div> -->
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
                            <input  id="vigencia_i" data-type="vigencia_i" name="vigencia_i" class="form-control{{ $errors->has('vigencia_i') ? ' is-invalid' : '' }}" value="{{ (!empty($foo[0]['FECHAVIGENCIA'])) ? $foo[0]['FECHAVIGENCIA'] : old('vigencia_i') }}" readonly>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                @if($tipo_fus == 1 || $tipo_fus == 2)
                    {{ __('Responsable o jefe del nuevo usuario')}}
                @elseif($tipo_fus == 3)
                    {{ __('Datos del responsable de cuenta')}}
                @else
                    {{ __('Responsable o jefe del solicitante')}}
                @endif
                </div>
                <div class="card-body">
                    <div class="form-group row" id="sin_data2" style="display:none"> 
                        <div class="col-md-12">
                            <label class="col-md-8 col-form-label text-md-rigth"><span style="color:red">No se encontró el registro</span></label>
                        </div>
                    </div>
                    <div class="form-group row" id="aviso_correo_jefe" style="display:none"> 
                        <div class="col-md-12">
                            <label class="col-md-8 col-form-label text-md-rigth"><span style="color:red">El empleado no posee correo electronico, favor de validar la informacion</span></label>
                        </div>
                    </div>
                    <div class="form-group row" id="aviso_jefe" style="display:none"> 
                        <div class="col-md-12">
                            <label class="col-md-12 col-form-label text-md-rigth"><span style="color:red">El jefe o responsable no cumple con el nivel jerárquico mínimo, favor de ingresar un autorizador con el nivel mínimo de jerarquía (Gerente)</span></label>
                        </div>
                    </div>
                    <div class="form-group row" id="aviso_jefe3" style="display:none"> 
                        <div class="col-md-12">
                            <label class="col-md-12 col-form-label text-md-rigth"><span style="color:red">El jefe o responsable no cumple con el nivel jerárquico mínimo, favor de ingresar un autorizador con el nivel mínimo de jerarquía (Director)</span></label>
                        </div>
                    </div>
                    <div class="form-group row" id="aviso_jefe2" style="display:none"> 
                        <div class="col-md-12">
                            <label class="col-md-12 col-form-label text-md-rigth"><span style="color:red">El jefe o responsable no cumple con el nivel jerárquico, favor de ingresar un autorizador con el nivel mínimo de jerarquía (Coordinador).</span></label>
                        </div>
                    </div>
                    <div class="form-group row" id="rep_sus" style="display:none"> 
                        <div class="col-md-12">
                            <label class="col-md-12 col-form-label text-md-rigth"><span style="color:red">La persona que solicita no puede ser autorizador</span></label>
                        </div>
                    </div>
                    <div class="form-group row" id="rep_sus_terceros" style="display:none"> 
                        <div class="col-md-12">
                            <label class="col-md-12 col-form-label text-md-rigth"><span style="color:red">La persona que es jefe o responsalbe no puede ser autorizador</span></label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                        @if($tipo_fus == 1 || $tipo_fus == 2)
                            <label for="n_jefe" class="col-md-12 col-form-label text-md-left">Número de empleado del responsable o jefe del nuevo usuario</label>
                        @elseif($tipo_fus == 3)
                            <label for="n_jefe" class="col-md-12 col-form-label text-md-rigth">Número de empleado del responsable de la cuenta</label>
                        @else
                            <label for="n_jefe" class="col-md-12 col-form-label text-md-rigth">Número de empleado del responsable o jefe del solicitante</label>
                        @endif
                            <input  id="n_jefe" name="n_jefe" data-persona="2" data-type="n_jefe" class="autocomplete_jefe form-control automat" placeholder="Ej: 000001" value="{{ old('n_jefe') }}" onKeyPress="return soloNumeros(event)" required>
                            @if ($errors->has('n_jefe'))
                                <p>{{ $errors->first('n_jefe') }}</p>
                            @endif
                        </div>
                        <div class="col-md-3">
                            <label for="correo_jefe" class="col-md-8 col-form-label text-md-rigth">Correo</label>
                            <input type="email" id="correo_jefe" name="correo_jefe" data-type="correo_jefe" class="form-control" value="{{ old('correo_jefe') }}" required readonly>
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
            <!-- <div class="card"> -->
                <div class="card-header">
                {{ __('Datos del autorizador')}}
                </div>
                <div class="card-body">
                    <div class="form-group row" id="aviso_correo_aut" style="display:none"> 
                        <div class="col-md-12">
                            <label class="col-md-8 col-form-label text-md-rigth"><span style="color:red">El empleado no posee correo electronico, favor de validar la informacion</span></label>
                        </div>
                    </div>
                    <div class="form-group row" id="sin_data3" style="display:none"> 
                        <div class="col-md-12">
                            <label class="col-md-8 col-form-label text-md-rigth"><span style="color:red">No se encontró el registro</span></label>
                        </div>
                    </div>    
                    <!-- <div class="form-group row"> -->
                    <div class="form-group row" id="req_aut" style="display:none"> 
                        <div class="col-md-12">
                            <label class="col-md-12 col-form-label text-md-rigth"><span style="color:red">Los datos del autorizador son requeridos</span></label>
                        </div>
                    </div>
                    <div class="form-group row" id="rep_aut" style="display:none"> 
                        <div class="col-md-12">
                            <label class="col-md-12 col-form-label text-md-rigth"><span style="color:red">El jefe o responsable no puede ser autorizador</span></label>
                        </div>
                    </div>
                    <div class="form-group row" id="rep_aut_ext" style="display:none"> 
                        <div class="col-md-12">
                            <label class="col-md-12 col-form-label text-md-rigth"><span style="color:red">El jefe o responsable no puede ser autorizador</span></label>
                        </div>
                    </div>
                    <!-- <div class="form-group row" id="rep_aut_ext" style="display:none"> 
                        <div class="col-md-12">
                            <label class="col-md-12 col-form-label text-md-rigth"><span style="color:red">El solicitante o capturista no puede ser autorizador</span></label>
                        </div>
                    </div> -->
                    <div class="form-group row" id="aviso_aut" style="display:none"> 
                        <div class="col-md-12">
                            <label class="col-md-12 col-form-label text-md-rigth"><span style="color:red">El autorizador no cumple con el nivel mínimo jerarquico, favor de seleccionar un autorizador con el nivel mínimo de jeararquía (Gerente)</span></label>
                        </div>
                    </div>
                    <div class="form-group row" id="aviso_aut2" style="display:none"> 
                        <div class="col-md-12">
                            <label class="col-md-12 col-form-label text-md-rigth"><span style="color:red">El autorizador no cumple con el nivel jerarquico</span></label>
                        </div>
                    </div>
                    <div class="form-group row" id="aviso_aut_set" style="display:none"> 
                        <div class="col-md-12">
                            <label class="col-md-12 col-form-label text-md-rigth"><span style="color:red">El autorizador no cumple con el nivel jerarquico de Director</span></label>
                        </div>
                    </div>
                    <!-- <div class="form-group row" id="div_aut_1" style="display:none"> -->
                    <div class="form-group row">
                        <div class="col-md-4">
                            <label for="n_aut" class="col-md-12 col-form-label text-md-rigth">Número de empleado del autorizador</label>
                            <input  id="n_aut" name="n_aut" onKeyPress="return soloNumeros(event)" data-persona="3" data-type="n_aut" class="form-control autocomplete_aut automat {{ $errors->has('n_aut') ? ' is-invalid' : '' }}" value="{{ old('n_aut') }}" >
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
                    <!-- <div class="form-group row" id="div_aut_2" style="display:none"> -->
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
                    @if($tipo_fus == 1 || $tipo_fus == 2)
                    <div class="form-group row">
                        <label class="col-md-12 col-form-label text-md-rigth"><span style="color:black"><strong><em>* Si el jefe o responsable cuenta con los privilegios para autorizar no será necesario capturar el autorizador en esta sección</em></strong></span></label>
                    </div>
                    @endif
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
                    @if($tipo_fus == 0)
                    <div class="form-group row">
                        <div class="col-md-12">
                            <input type="checkbox" name="sattora" id="sattora" class="form-control checksattora">
                            <label>¿Es sattora?</label>
                        </div>
                    </div>
                    @endif
                    <div class="form-group row">  
                        <div class="col-md-5">
                            <label for="ficha_ter" class="col-md-8 col-form-label text-md-rigth">Ficha</label>
                            <input type="text" data-persona="4" id="ficha_ter" data-type="ficha_t" name="ficha_t" class="form-control autocomplete_externo automat tercero" placeholder="Ej: 00001"value="{{ old('ficha_t') }}" onKeyPress="return soloNumeros(event)">
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
    $.validator.messages.required = 'El campo es requerido.';

    jQuery.validator.addClassRules({
        'campo-requerido': {
            required: true
        }
    });

    $('#enviar').click(function() {
        swal.fire({
            title: 'Advertencia',
            text: "¿Esta seguro de realizar la operación?",
            type: 'warning',
            showCancelButton: true,
            cancelButtonColor: '#DC3545',
            confirmButtonColor: '#3085d6',
            allowOutsideClick: false,
            allowEscapeKey: false,
            cancelButtonText: 'Cancelar',
            confirmButtonText: 'De acuerdo'
        }).then((result) => {
            if (result.value) {
                $('#form-fus').submit();
            }
        });
    });
        
    $('#form-fus').validate({
        ignore: "",
        submitHandler: function(form) {
            mostrarLoading();
            setTimeout(form.submit(), 500);
        }
    });
    
$( window ).on( "load", fusApp() );
function fusApp(){
    var t_fus="";
    a = $(".d_ext:checked").val();
    t_fus =  $("#tipo_fus").val(); 
    if (a == 1 && t_fus == 0){
        if (t_fus == 0){
            var apps = $('#apps').val();
            var app = JSON.parse(apps);
            for (var clave in app){
                if (clave != 50) {
                    document.getElementById("div_aut_1").style.display = "none";
                } else if (clave == 50){
                    var element = document.getElementById("n_aut");
                    element.classList.add("campo-requerido");
                    var element1 = document.getElementById("correo_aut");
                    element1.classList.add("campo-requerido");
                }
            }
        }
    }
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
$(document).on('change', '#movimiento', function(){
    var mov = $(this).val();
    var fus = $('#tipo_fus').val();
    if (fus != 3) {
        $("#n_empleado").val('');
    }
});
$(document).on('change', '.checksattora',function(){
    var a = $(".checksattora:checked").val();
    if (a == 'on') {
        Swal.fire({
          title: 'Activación de personal sattora',
          text: "Se perderá la información ingresada  del externo, ¿Esta de acuerdo?",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Continuar',
          cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.value == true) {
                $('#ficha_ter').val('');
                $('#empresa_t').val('');              
                $('#nombre_t').val('');
                $('#a_pat_t').val('');               
                $('#a_mat_t').val('');               
                $('#ubicacion_t').val('');               
                $('#proyecto').val('');               
                $('#vigencia').val('');
                $("#ficha_ter").removeClass("autocomplete_externo");
                $("#ficha_ter").removeClass("automat");
                $("#empresa_t").removeAttr("readonly");
                $("#nombre_t").removeAttr("readonly");
                $("#a_pat_t").removeAttr("readonly");
                $("#a_mat_t").removeAttr("readonly");
                $("#ubicacion_t").removeAttr("readonly");
                $("#proyecto").removeAttr("readonly");
                $("#vigencia").removeAttr("readonly");
            } else {
                $(".checksattora").prop('checked', false); 
            }
        })
    } else {
        Swal.fire({
          title: 'Desactivación de personal sattora',
          text: "Se perderá la información ingresada  del externo, ¿Esta de acuerdo?",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Continuar',
          cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.value == true) {
                $('#ficha_ter').val('');
                $('#empresa_t').val('');              
                $('#nombre_t').val('');
                $('#a_pat_t').val('');               
                $('#a_mat_t').val('');               
                $('#ubicacion_t').val('');               
                $('#proyecto').val('');               
                $('#vigencia').val('');
                $("#ficha_ter").addClass("autocomplete_externo");
                $("#ficha_ter").addClass("automat");
                $("#empresa_t").attr("readonly","readonly");
                $("#nombre_t").attr("readonly","readonly");
                $("#a_pat_t").attr("readonly","readonly");
                $("#a_mat_t").attr("readonly","readonly");
                $("#ubicacion_t").attr("readonly","readonly");
                $("#proyecto").attr("readonly","readonly");
                $("#vigencia").attr("readonly","readonly");
             }else{
                $(".checksattora").prop('checked', true); 
            }
        })
    }
});
    $(document).on('click','#enviar', function(){
        $('#sin_num_t').hide();
        var a;
        var num_t;
        var nom;
        var nom_t;
        var t_fus;
        var num_aut
        var emp_t;
        var apep_t;
        var cuenta;
        a = $(".d_ext:checked").val();
        t_fus =  $("#tipo_fus").val();
        num_t =  $("#ficha_ter").val();
        nom = $("#nombre_t").val();
        nom_t = $("#nombre_t").val();
        emp_t = $("#empresa_t").val();
        apep_t = $("#a_pat_t").val();
        num_aut = $("#n_aut").val();
        mail_aut = $("#correo_aut").val();
        switch(t_fus){
            case "1":
            case "2":
            
                if (num_aut !== '' || mail_aut == ''){
                    $('#req_aut').show();
                        event.stopImmediatePropagation();
                        event.preventDefault();  
                }
            case "3":
                let cadena = $('#puesto_jefe').val();
                // esta es la palabra a buscar
                var palabras = cadena.split(" ");
                var iguales = jerarquia(palabras);
                if(iguales == 0){
                    if (num_aut == null || num_aut == undefined || typeof num_aut =='undefined' || num_aut =='' ){
                        document.getElementById("n_aut").focus();
                        $('#req_aut').show();
                        event.stopImmediatePropagation();
                        event.preventDefault();
                    }else if (mail_aut == null || mail_aut == undefined || typeof mail_aut =='undefined' || mail_aut =='' ){
                        document.getElementById("correo_aut").focus();
                        $('#req_aut').show();
                        event.stopImmediatePropagation();
                        event.preventDefault();
                    }
                }
                if (t_fus === '3'){
                   t_sol = $("#tipo_sol").val();
                   if (t_sol == null || t_sol == undefined || typeof t_sol =='undefined' || t_sol =='' ){
                    document.getElementById("tipo_sol").focus();
                    event.stopImmediatePropagation();
                    event.preventDefault();
                   }
                }
            break;
        }
        switch(a){
            case "2":
            if (num_t == null || num_t == undefined || typeof num_t =='undefined' || num_t ==''){
                document.getElementById("ficha_ter").focus();
                $('#sin_num_t').show();
                event.stopImmediatePropagation();
                event.preventDefault();
            }
            if (emp_t == null || emp_t == undefined || typeof emp_t =='undefined' || emp_t ==''){
                document.getElementById("ficha_ter").focus();
                $('#sin_num_t').show();
                event.stopImmediatePropagation();
                event.preventDefault();
            }
            if (nom_t == null || nom_t == undefined || typeof nom_t =='undefined' || nom_t ==''){
                document.getElementById("ficha_ter").focus();
                $('#sin_num_t').show();
                event.stopImmediatePropagation();
                event.preventDefault();
            }
            if (apep_t == null || apep_t == undefined || typeof apep_t =='undefined' || apep_t ==''){
                document.getElementById("ficha_ter").focus();
                $('#sin_num_t').show();
                event.stopImmediatePropagation();
                event.preventDefault();
            }
            break;
        }
    });
$(document).on('click','.d_ext', function(){
    a = $(".d_ext:checked").val();

    $('#n_empleado').val('');
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
        /*var element7 = document.getElementById("proyecto");
        element7.classList.add("campo-requerido");
        var element8 = document.getElementById("vigencia");
        element8.classList.add("campo-requerido");*/
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
function soloNumeros(e)
{
    var key = window.Event ? e.which : e.keyCode
    return ((key >= 48 && key <= 57) || (key==8))
}
$.datepicker.regional['es'] = {
 closeText: 'Cerrar',
 prevText: '< Ant',
 nextText: 'Sig >',
 currentText: 'Hoy',
 monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
 monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
 dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
 dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
 dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
 changeMonth: true,
changeYear: true,
 weekHeader: 'Sm',
 dateFormat: 'dd-mm-yy',
 firstDay: 1,
 isRTL: false,
 showMonthAfterYear: false,
 yearSuffix: ''
 };
 $.datepicker.setDefaults($.datepicker.regional['es']);
// $( function() {
//     $( "#f_desde" ).datepicker({ maxDate: 0}); //.attr('readonly', 'readonly'); para bloquear el input
//   });
//   $( function() {
//     $( "#f_hasta" ).datepicker({ minDate: 1 });
//   });
//   fus de correo
  $( function() {
    $( "#f_inicio" ).datepicker({ maxDate: 0 });
  });
//   fus de correo especial
    $( function() {
        $( "#f_vigencia" ).datepicker({ minDate: 1 });
  });
    $( function() {
        $( "#pui" ).datepicker({ maxDate: 0 });
    });
    $( function() {
        $( "#puf" ).datepicker({ minDate: 1 });
    });
 
$(document).on('click','#btn_ayuda',function(){
    Swal.fire({
      title: '¡AYUDA!',
      text: 'En la siguiente imagen se muestra donde encontrar el número de ficha',
      imageUrl: "{{ asset('images/ficha.png') }}",
      imageWidth: 500,
      imageHeight: 315,
      imageAlt: '',
    })
});
$(document).on('click','#btn_ayuda_especial',function(){
    Swal.fire({
      title: '¡AYUDA!',
  text: 'El campo es requerido. Los caracteres especiales permitidos son: guion medio (-) , guion bajo ( _ ) , ',
      imageWidth: 500,
      imageHeight: 315,
      imageAlt: '',
    })
});
$(document).on('keypress','.automat', function(e){
   tecla = (e.keyCode ? e.keyCode : e.which);
   tpers = $(this).data('persona');
   valor = $(this).val();
   type = $(this).data('type');
    if (tecla == 13) {
        if (tpers == 4) {
        buscar(valor, 2,tpers ,type);
       }else{
        buscar(valor, 1,tpers,type);
       }
    }
});
$(document).on('blur','.automat', function(e){
   tpers = $(this).data('persona');
   valor = $(this).val();
   type = $(this).data('type');
       if (tpers == 4) {
        buscar(valor, 2,tpers,type);
       }else{
        buscar(valor, 1,tpers,type);
       }
});
function jerarquia(palabras){
    var tipo = $('#tipo_fus').val();
    var terminos = "{{ $jer }}";
    var decod = JSON.parse(atob(terminos));
    var iguales=0;
    
    if (tipo == 0) {
        var apps = $('#apps').val();
        var app = JSON.parse(apps);
        for (var clave in app){
            if (clave == 50) {
                var quit =  [ "Gte", "Gte.","Gerente", "GTE", "GTE.", "GERENTE", "Coord", "Coord.","COORD","COORD.","Coordinador","COORDINADOR","Coordinadora", "COORDINADORA","Subdir", "Subdir.","SUBDIR","SUBDIR.","SUBDIRECTOR", "SUBDIRECTORA","Sub-Director","SUB-DIRECTOR", "Sub-Directora","SUB-DIRECTORA"];
            }else{
                var quit =  [ "Gte", "Gte.","Gerente", "GTE", "GTE.", "GERENTE"];   
            }
        }
        var array=[];
        var dat = decod;
        for(var i =0; i < dat.length;i++){
            var igual=false;
            for (var j = 0; j < quit.length & !igual; j++) {
                if(dat[i] == quit[j]) 
                        igual=true;
            }
            if(!igual)array.push(dat[i]);
        }
        decod = array;
    }
    for(var j =0; j < decod.length;j++){
        if(palabras[0].toUpperCase() == decod[j]){
            iguales++;
        }
    }
    return iguales;
}
function buscar(param, buscar, persona,type='n_empleado')
{
    var tipo = $('#tipo_fus').val();
    var mov = $('#movimiento').val();
    var solici = $('#n_empleado').val();
    var jefresp = $('#n_jefe').val();
    var autroiz = $('#n_aut').val();
    var per_ext = $(".d_ext:checked").val();
    var response;
    $.ajax({
        url:"{{ route('fus.autocomplete2') }}",
        dataType: "json",
            data:{
                term: param,
                type: 1,
                search: buscar
        },
        beforeSend: function(){
            mostrarLoading();
        },
        success: function(data)
        {
            ocultarLoading();
            switch(persona){
                case 1:
                    if (data.respuesta != "No se encontro el registro"){
                        if (tipo == 1 || tipo == 2) {
                            if (data[0].mail && mov == 'Alta') {
                                if (per_ext == 1) {
                                    $('#aviso_con_mail').show();
                                    $('#n_empleado').val('');
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
                                    $('#aviso_soli').hide();
                                    $('#sin_data1').hide();
                                }else{
                                    $('#n_empleado').val(parseInt(data[0].FICHA, 10)); 
                                    $('#mail_sol').val(data[0].mail);
                                    $('#u_red').val(data[0].u_red);
                                    $('#nombre').val(data[0].NOMBRE);
                                    $('#a_paterno').val(data[0].APELLIDO);
                                    $('#a_materno').val(data[0].APELLIDOM);
                                    $('#puesto').val(data[0].PUESTO);
                                    $('#empresa').val(data[0].EMPRESA);
                                    $('#dep').val(data[0].DEPARTAMENTO+' / '+data[0].CVE_DEPTO);
                                    $('#ubi').val(data[0].UBICACION);
                                    $('#jefe').val(data[0].JEFE);
                                    $('#ext').val(data[0].EXTENSION);
                                    $('#c_costos').val(data[0].DESCRIP+' / '+data[0].CVE_CENTR);
                                    $('#vigencia_i').val(data[0].FECHAVIGENCIA);
                                    $('#aviso_soli').hide();
                                    $('#aviso_con_mail').hide();
                                    $('#sin_data1').hide();
                                }
                            }else{
                                if (mov == 'Alta') {
                                    $('#n_empleado').val(parseInt(data[0].FICHA, 10));
                                    $('#nombre').val(data[0].NOMBRE);
                                    $('#a_paterno').val(data[0].APELLIDO);
                                    $('#a_materno').val(data[0].APELLIDOM);
                                    $('#puesto').val(data[0].PUESTO);
                                    $('#empresa').val(data[0].EMPRESA);
                                    $('#dep').val(data[0].DEPARTAMENTO+' / '+data[0].CVE_DEPTO);
                                    $('#ubi').val(data[0].UBICACION);
                                    $('#jefe').val(data[0].JEFE);
                                    $('#ext').val(data[0].EXTENSION);
                                    $('#c_costos').val(data[0].DESCRIP+' / '+data[0].CVE_CENTR);
                                    $('#vigencia_i').val(data[0].FECHAVIGENCIA);
                                    $('#aviso_soli').hide();
                                    $('#aviso_con_mail').hide();
                                    $('#sin_data1').hide();    
                                }else{
                                    $('#n_empleado').val(parseInt(data[0].FICHA, 10));
                                    $('#mail_sol').val(data[0].mail);
                                    $('#u_red').val(data[0].u_red);
                                    $('#nombre').val(data[0].NOMBRE);
                                    $('#a_paterno').val(data[0].APELLIDO);
                                    $('#a_materno').val(data[0].APELLIDOM);
                                    $('#puesto').val(data[0].PUESTO);
                                    $('#empresa').val(data[0].EMPRESA);
                                    $('#dep').val(data[0].DEPARTAMENTO+' / '+data[0].CVE_DEPTO);
                                    $('#ubi').val(data[0].UBICACION);
                                    $('#jefe').val(data[0].JEFE);
                                    $('#ext').val(data[0].EXTENSION);
                                    $('#c_costos').val(data[0].DESCRIP+' / '+data[0].CVE_CENTR);
                                    $('#vigencia_i').val(data[0].FECHAVIGENCIA);
                                    $('#aviso_soli').hide();
                                    $('#aviso_con_mail').hide();
                                }
                            }
                        }else if(tipo == 3) {
                                $('#n_empleado').val(parseInt(data[0].FICHA, 10)); 
                                $('#mail_sol').val(data[0].mail);
                                $('#u_red').val(data[0].u_red);
                                $('#nombre').val(data[0].NOMBRE);
                                $('#a_paterno').val(data[0].APELLIDO);
                                $('#a_materno').val(data[0].APELLIDOM);
                                $('#puesto').val(data[0].PUESTO);
                                $('#empresa').val(data[0].EMPRESA);
                                $('#dep').val(data[0].DEPARTAMENTO+' / '+data[0].CVE_DEPTO);
                                $('#ubi').val(data[0].UBICACION);
                                $('#jefe').val(data[0].JEFE);
                                $('#ext').val(data[0].EXTENSION);
                                $('#c_costos').val(data[0].DESCRIP+' / '+data[0].CVE_CENTR);
                                $('#vigencia_i').val(data[0].FECHAVIGENCIA);
                                $('#aviso_soli').hide();
                                $('#aviso_con_mail').hide();
                                $('#sin_data1').hide();
                        }else {
                            if (data[0].mail) {
                                $('#n_empleado').val(parseInt(data[0].FICHA, 10)); 
                                $('#mail_sol').val(data[0].mail);
                                $('#u_red').val(data[0].u_red);
                                $('#nombre').val(data[0].NOMBRE);
                                $('#a_paterno').val(data[0].APELLIDO);
                                $('#a_materno').val(data[0].APELLIDOM);
                                $('#puesto').val(data[0].PUESTO);
                                $('#empresa').val(data[0].EMPRESA);
                                $('#dep').val(data[0].DEPARTAMENTO+' / '+data[0].CVE_DEPTO);
                                $('#ubi').val(data[0].UBICACION);
                                $('#jefe').val(data[0].JEFE);
                                $('#ext').val(data[0].EXTENSION);
                                $('#c_costos').val(data[0].DESCRIP+' / '+data[0].CVE_CENTR);
                                $('#vigencia_i').val(data[0].FECHAVIGENCIA);
                                $('#aviso_con_mail').hide();
                                $('#aviso_soli').hide();
                                $('#sin_data1').hide();
                            }
                             else {
                                $('#n_empleado').val('');
                                // $('#mail_sol').val('');
                                // $('#u_red').val('');
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
                                $('#aviso_soli').show();
                                $('#sin_data1').hide();
                            }
                        }
                    } else{
                        $('#sin_data1').show();
                        $('#n_empleado').val('');
                        // $('#mail_sol').val('');
                        // $('#u_red').val('');
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
                        event.stopImmediatePropagation();
                        event.preventDefault();
                    }
                break;
                case 2:
                    if (data.respuesta != "No se encontro el registro")
                    {
                        // esta es la cadena donde buscaremos
                        let cadena = data[0].PUESTO;
                        var palabras = cadena.split(" ");
                        var iguales = jerarquia(palabras);
                        if(iguales > 0){
                            if (data[0].mail) {
                                if (tipo == 1 || tipo == 2) {
                                    switch(per_ext){
                                        case '1':
                                            if (parseInt(data[0].FICHA, 10) != autroiz){
                                                $('#n_jefe').val(parseInt(data[0].FICHA, 10)); 
                                                $('#correo_jefe').val(data[0].mail);
                                                $('#nom_jefe').val(data[0].NOMBRE);
                                                $('#apat_jefe').val(data[0].APELLIDO);
                                                $('#amat_jefe').val(data[0].APELLIDOM);
                                                $('#puesto_jefe').val(data[0].PUESTO);
                                                $('#aviso_correo_jefe').hide();
                                                $('#aviso_jefe').hide();
                                                $('#rep_sus').hide();
                                                $('#sin_data2').hide();
                                                $('#rep_sus_terceros').hide();
                                            }else{
                                                $('#rep_sus').show();
                                                $('#n_jefe').val('');
                                                $('#correo_jefe').val('')
                                                $('#nom_jefe').val('');
                                                $('#apat_jefe').val('');
                                                $('#amat_jefe').val('');
                                                $('#puesto_jefe').val('');
                                                $('#sin_data2').hide();
                                            }
                                        break;
                                        case '2':
                                            if (parseInt(data[0].FICHA, 10) != autroiz){
                                                $('#n_jefe').val(parseInt(data[0].FICHA, 10)); 
                                                $('#correo_jefe').val(data[0].mail);
                                                $('#nom_jefe').val(data[0].NOMBRE);
                                                $('#apat_jefe').val(data[0].APELLIDO);
                                                $('#amat_jefe').val(data[0].APELLIDOM);
                                                $('#puesto_jefe').val(data[0].PUESTO);
                                                $('#aviso_correo_jefe').hide();
                                                $('#aviso_jefe').hide();
                                                $('#rep_sus_terceros').hide();
                                                $('#sin_data2').hide();
                                            }else{
                                                $('#rep_sus_terceros').show();
                                                $('#n_jefe').val('');
                                                $('#correo_jefe').val('')
                                                $('#nom_jefe').val('');
                                                $('#apat_jefe').val('');
                                                $('#amat_jefe').val('');
                                                $('#puesto_jefe').val('');
                                                $('#sin_data2').hide();
                                            }
                                        break;
                                    }
                                }else if (tipo == 3){
                                    if (parseInt(data[0].FICHA, 10) != autroiz){
                                        $('#aviso_correo_jefe').hide();
                                        $('#aviso_jefe').hide();
                                        $('#rep_sus').hide();
                                        $('#sin_data2').hide();
                                        $('#n_jefe').val(parseInt(data[0].FICHA, 10)); 
                                        $('#correo_jefe').val(data[0].mail);
                                        $('#nom_jefe').val(data[0].NOMBRE);
                                        $('#apat_jefe').val(data[0].APELLIDO);
                                        $('#amat_jefe').val(data[0].APELLIDOM);
                                        $('#puesto_jefe').val(data[0].PUESTO);
                                    }else{
                                        $('#rep_sus').show();
                                        $('#n_jefe').val('');
                                        $('#correo_jefe').val('');
                                        $('#aviso_correo_jefe').hide();
                                        $('#nom_jefe').val('');
                                        $('#apat_jefe').val('');
                                        $('#amat_jefe').val('');
                                        $('#puesto_jefe').val('');
                                        $('#sin_data2').hide();
                                    }
                                }else{
                                    $('#aviso_jefe').hide();
                                    $('#aviso_correo_jefe').hide();
                                    $('#aviso_jefe2').hide();
                                    $('#rep_sus').hide();
                                    $('#sin_data2').hide();
                                    $('#n_jefe').val(parseInt(data[0].FICHA, 10)); 
                                    $('#correo_jefe').val(data[0].mail);
                                    $('#nom_jefe').val(data[0].NOMBRE);
                                    $('#apat_jefe').val(data[0].APELLIDO);
                                    $('#amat_jefe').val(data[0].APELLIDOM);
                                    $('#puesto_jefe').val(data[0].PUESTO);
                                }
                            }else{
                                $('#aviso_correo_jefe').show();
                                $('#n_jefe').val('');
                                $('#correo_jefe').val('')
                                $('#nom_jefe').val('');
                                $('#apat_jefe').val('');
                                $('#amat_jefe').val('');
                                $('#puesto_jefe').val('');
                                $('#sin_data2').hide();
                            }
                        }else{
                            if (data[0].mail) {
                                if (tipo == 0){
                                    switch(per_ext){
                                        case '1':
                                            var apps = $('#apps').val();
                                            var app = JSON.parse(apps);
                                            for (var clave in app){
                                                if (clave != 50) {
                                                    $('#aviso_correo_jefe').hide();
                                                    $('#aviso_jefe').hide();
                                                    if (clave != 50) {
                                                        $('#aviso_jefe2').show();
                                                    }
                                                    //$('#aviso_jefe2').show();
                                                    $('#n_jefe').val('');
                                                    $('#correo_jefe').val('')
                                                    $('#nom_jefe').val('');
                                                    $('#apat_jefe').val('');
                                                    $('#amat_jefe').val('');
                                                    $('#puesto_jefe').val('');
                                                    $('#sin_data2').hide();
                                                }else {
                                                    if (parseInt(data[0].FICHA, 10) != autroiz){
                                                        if (clave != 50) {
                                                            $('#aviso_jefe2').show();
                                                        }
                                                        $('#aviso_correo_jefe').hide();
                                                        $('#n_jefe').val(parseInt(data[0].FICHA, 10));
                                                        $('#correo_jefe').val(data[0].mail);
                                                        $('#nom_jefe').val(data[0].NOMBRE);
                                                        $('#apat_jefe').val(data[0].APELLIDO);
                                                        $('#amat_jefe').val(data[0].APELLIDOM);
                                                        $('#puesto_jefe').val(data[0].PUESTO);
                                                        $('#aviso_correo_jefe').hide(); 
                                                        $('#rep_sus').hide();
                                                        $('#sin_data2').hide();
                                                    }else{
                                                        $('#rep_sus').show();
                                                        $('#n_jefe').val('');
                                                        $('#correo_jefe').val('')
                                                        $('#nom_jefe').val('');
                                                        $('#apat_jefe').val('');
                                                        $('#amat_jefe').val('');
                                                        $('#puesto_jefe').val('');
                                                        $('#sin_data2').hide();
                                                    }
                                                }
                                            }
                                        break;
                                        case '2':
                                            if (parseInt(data[0].FICHA, 10) != autroiz){
                                                // if (clave != 50) {
                                                //         $('#aviso_jefe2').hide();
                                                //     }
                                                $('#n_jefe').val(parseInt(data[0].FICHA, 10));
                                                $('#correo_jefe').val(data[0].mail);
                                                $('#nom_jefe').val(data[0].NOMBRE);
                                                $('#apat_jefe').val(data[0].APELLIDO);
                                                $('#amat_jefe').val(data[0].APELLIDOM);
                                                $('#puesto_jefe').val(data[0].PUESTO);
                                                $('#aviso_correo_jefe').hide(); 
                                                $('#rep_sus').hide();
                                                $('#sin_data2').hide();
                                            }else{
                                                $('#rep_sus').show();
                                                $('#n_jefe').val('');
                                                $('#correo_jefe').val('')
                                                $('#nom_jefe').val('');
                                                $('#apat_jefe').val('');
                                                $('#amat_jefe').val('');
                                                $('#puesto_jefe').val('');
                                                $('#sin_data2').hide();
                                            }
                                        break;
                                        }
                                }else if (tipo == 1 || tipo == 2) {
                                    switch(per_ext){
                                        case '1':
                                            if (parseInt(data[0].FICHA, 10) != autroiz){
                                                $('#aviso_jefe').show();
                                                $('#aviso_correo_jefe').hide();
                                                $('#n_jefe').val(parseInt(data[0].FICHA, 10));
                                                $('#correo_jefe').val(data[0].mail);
                                                $('#nom_jefe').val(data[0].NOMBRE);
                                                $('#apat_jefe').val(data[0].APELLIDO);
                                                $('#amat_jefe').val(data[0].APELLIDOM);
                                                $('#puesto_jefe').val(data[0].PUESTO);
                                                $('#aviso_correo_jefe').hide(); 
                                                $('#rep_sus').hide();
                                                $('#sin_data2').hide();
                                            }else{
                                                $('#rep_sus').show();
                                                $('#n_jefe').val('');
                                                $('#correo_jefe').val('')
                                                $('#nom_jefe').val('');
                                                $('#apat_jefe').val('');
                                                $('#amat_jefe').val('');
                                                $('#puesto_jefe').val('');
                                                $('#sin_data2').hide();
                                            }
                                        break;
                                        case '2':
                                            if (parseInt(data[0].FICHA, 10) != autroiz){
                                                $('#aviso_jefe').show();
                                                $('#aviso_correo_jefe').hide();
                                                $('#n_jefe').val(parseInt(data[0].FICHA, 10));
                                                $('#correo_jefe').val(data[0].mail);
                                                $('#nom_jefe').val(data[0].NOMBRE);
                                                $('#apat_jefe').val(data[0].APELLIDO);
                                                $('#amat_jefe').val(data[0].APELLIDOM);
                                                $('#puesto_jefe').val(data[0].PUESTO);
                                                $('#aviso_correo_jefe').hide(); 
                                                $('#rep_sus').hide();
                                                $('#sin_data2').hide();
                                            }else{
                                                $('#rep_sus').show();
                                                $('#n_jefe').val('');
                                                $('#correo_jefe').val('')
                                                $('#nom_jefe').val('');
                                                $('#apat_jefe').val('');
                                                $('#amat_jefe').val('');
                                                $('#puesto_jefe').val('');
                                                $('#sin_data2').hide();
                                            }
                                        break;
                                    }
                                }else{
                                    $('#aviso_correo_jefe').hide();
                                    $('#n_jefe').val(parseInt(data[0].FICHA, 10)); 
                                    $('#correo_jefe').val(data[0].mail);
                                    $('#nom_jefe').val(data[0].NOMBRE);
                                    $('#apat_jefe').val(data[0].APELLIDO);
                                    $('#amat_jefe').val(data[0].APELLIDOM);
                                    $('#puesto_jefe').val(data[0].PUESTO);
                                    $('#aviso_jefe').hide();
                                    $('#rep_sus').hide();
                                    $('#sin_data2').hide();
                                }
                            }else{
                                $('#aviso_correo_jefe').show();
                                $('#n_jefe').val('');
                                $('#correo_jefe').val('')
                                $('#nom_jefe').val('');
                                $('#apat_jefe').val('');
                                $('#amat_jefe').val('');
                                $('#puesto_jefe').val('');
                                $('#sin_data2').hide();
                            }
                        }
                    }else{
                        $('#sin_data2').show();
                        $('#n_jefe').val('');
                        $('#correo_jefe').val('')
                        $('#nom_jefe').val('');
                        $('#apat_jefe').val('');
                        $('#amat_jefe').val('');
                        $('#puesto_jefe').val('');
                        // event.stopImmediatePropagation();
                        // event.preventDefault();
                    }
                break;
                case 3:
                    if (data.respuesta != "No se encontro el registro")
                    {
                        // esta es la cadena donde buscaremos
                        let cadena = data[0].PUESTO;
                        cadena =cadena.trim();
                        var palabras = cadena.split(" ");
                        console.log(palabras);
                        var iguales = jerarquia(palabras);
                        if(iguales > 0){
                            if (tipo == 0) {
                                if (data[0].mail) {
                                    if (parseInt(data[0].FICHA, 10) != jefresp){
                                        $('#n_aut').val(parseInt(data[0].FICHA, 10));
                                        $('#correo_aut').val(data[0].mail);
                                        $('#nom_aut').val(data[0].NOMBRE);
                                        $('#apat_aut').val(data[0].APELLIDO);
                                        $('#amat_aut').val(data[0].APELLIDOM);
                                        $('#puesto_aut').val(data[0].PUESTO);
                                        $('#aviso_jefe').hide();
                                        $('#aviso_aut').hide();
                                        $('#rep_aut').hide();
                                        $('#req_aut').hide();
                                        $('#sin_data3').hide();
                                        var apps = $('#apps').val();
                                        var app = JSON.parse(apps);
                                        for (var clave in app){
                                            if (clave == 50) {
                                                $('#aviso_aut2').hide('');
                                                $('#aviso_aut_set').hide('');
                                            }else{
                                                $('#aviso_aut2').hide('');
                                                $('#aviso_aut_set').hide('');   
                                            }
                                        }
                                    }else{
                                        $('#rep_aut').show();
                                        $('#n_aut').val('');
                                        $('#correo_aut').val('')
                                        $('#nom_aut').val('');
                                        $('#apat_aut').val('');
                                        $('#amat_aut').val('');
                                        $('#puesto_aut').val('');
                                        $('#sin_data3').hide();
                                    }
                                }else{
                                    $('#aviso_correo_aut').show();
                                    $('#n_aut').val('');
                                    $('#correo_aut').val('')
                                    $('#nom_aut').val('');
                                    $('#apat_aut').val('');
                                    $('#amat_aut').val('');
                                    $('#puesto_aut').val('');
                                    $('#sin_data3').hide();
                                }
                            }else if (tipo == 3) {
                                var element1 = document.getElementById("n_aut");
                                element1.classList.add("campo-requerido");
                                var element2 = document.getElementById("correo_aut");
                                element2.classList.add("campo-requerido");
                                if (data[0].mail) {
                                    if (parseInt(data[0].FICHA, 10) != jefresp){
                                        $('#n_aut').val(parseInt(data[0].FICHA, 10));
                                        $('#correo_aut').val(data[0].mail);
                                        $('#nom_aut').val(data[0].NOMBRE);
                                        $('#apat_aut').val(data[0].APELLIDO);
                                        $('#amat_aut').val(data[0].APELLIDOM);
                                        $('#puesto_aut').val(data[0].PUESTO);
                                        $('#aviso_jefe').hide();
                                        $('#aviso_aut').hide();
                                        $('#rep_aut').hide();
                                        $('#req_aut').hide();
                                        $('#sin_data3').hide();
                                    }else{
                                        var element1 = document.getElementById("n_aut");
                                        element1.classList.remove("campo-requerido");
                                        var element2 = document.getElementById("correo_aut");
                                        element2.classList.remove("campo-requerido");
                                        $('#rep_aut').show();
                                        $('#n_aut').val('');
                                        $('#correo_aut').val('')
                                        $('#nom_aut').val('');
                                        $('#apat_aut').val('');
                                        $('#amat_aut').val('');
                                        $('#puesto_aut').val('');
                                        $('#sin_data3').hide();
                                    }
                                }else{
                                    var element1 = document.getElementById("n_aut");
                                    element1.classList.remove("campo-requerido");
                                    var element2 = document.getElementById("correo_aut");
                                    element2.classList.remove("campo-requerido");
                                    $('#aviso_correo_aut').show();
                                    $('#n_aut').val('');
                                    $('#correo_aut').val('')
                                    $('#nom_aut').val('');
                                    $('#apat_aut').val('');
                                    $('#amat_aut').val('');
                                    $('#puesto_aut').val('');
                                    $('#sin_data3').hide();
                                }
                            }else if (tipo == 1 || tipo == 2) {
                                if (data[0].mail){
                                    switch(per_ext){
                                        case '1':
                                            if (parseInt(data[0].FICHA, 10) != jefresp){
                                                var element1 = document.getElementById("n_aut");
                                                element1.classList.add("campo-requerido");
                                                var element2 = document.getElementById("correo_aut");
                                                element2.classList.add("campo-requerido");
                                                $('#n_aut').val(parseInt(data[0].FICHA, 10));
                                                $('#correo_aut').val(data[0].mail);
                                                $('#nom_aut').val(data[0].NOMBRE);
                                                $('#apat_aut').val(data[0].APELLIDO);
                                                $('#amat_aut').val(data[0].APELLIDOM);
                                                $('#puesto_aut').val(data[0].PUESTO);
                                                $('#aviso_jefe').hide();
                                                $('#aviso_aut').hide();
                                                $('#rep_aut').hide();
                                                $('#req_aut').hide();
                                                $('#sin_data3').hide();
                                            }else{
                                                var element1 = document.getElementById("n_aut");
                                                element1.classList.remove("campo-requerido");
                                                var element2 = document.getElementById("correo_aut");
                                                element2.classList.remove("campo-requerido");
                                                $('#rep_aut').show();
                                                $('#n_aut').val('');
                                                $('#correo_aut').val('')
                                                $('#nom_aut').val('');
                                                $('#apat_aut').val('');
                                                $('#amat_aut').val('');
                                                $('#puesto_aut').val('');
                                                $('#sin_data3').hide();
                                            }
                                        break;
                                        case '2':
                                            if (parseInt(data[0].FICHA, 10) != jefresp){
                                                var element1 = document.getElementById("n_aut");
                                                element1.classList.add("campo-requerido");
                                                var element2 = document.getElementById("correo_aut");
                                                element2.classList.add("campo-requerido");
                                                $('#n_aut').val(parseInt(data[0].FICHA, 10));
                                                $('#correo_aut').val(data[0].mail);
                                                $('#nom_aut').val(data[0].NOMBRE);
                                                $('#apat_aut').val(data[0].APELLIDO);
                                                $('#amat_aut').val(data[0].APELLIDOM);
                                                $('#puesto_aut').val(data[0].PUESTO);
                                                $('#aviso_jefe').hide();
                                                $('#aviso_aut').hide();
                                                $('#rep_aut_ext').hide();
                                                $('#req_aut').hide();
                                                $('#sin_data3').hide();
                                            }else{
                                                var element1 = document.getElementById("n_aut");
                                                element1.classList.remove("campo-requerido");
                                                var element2 = document.getElementById("correo_aut");
                                                element2.classList.remove("campo-requerido");
                                                $('#rep_aut_ext').show();
                                                $('#n_aut').val('');
                                                $('#correo_aut').val('')
                                                $('#nom_aut').val('');
                                                $('#apat_aut').val('');
                                                $('#amat_aut').val('');
                                                $('#puesto_aut').val('');
                                                $('#sin_data3').hide();
                                            }
                                        break; 
                                    } 
                                }else{
                                    var element1 = document.getElementById("n_aut");
                                    element1.classList.remove("campo-requerido");
                                    var element2 = document.getElementById("correo_aut");
                                    element2.classList.remove("campo-requerido");
                                    $('#aviso_correo_aut').show();
                                    $('#n_aut').val('');
                                    $('#correo_aut').val('')
                                    $('#nom_aut').val('');
                                    $('#apat_aut').val('');
                                    $('#amat_aut').val('');
                                    $('#puesto_aut').val('');
                                    $('#sin_data3').hide();
                                }
                            }else{
                                if (parseInt(data[0].FICHA, 10) != jefresp){
                                    if (data[0].mail) {
                                        $('#n_aut').val(parseInt(data[0].FICHA, 10));
                                        $('#correo_aut').val(data[0].mail);
                                        $('#nom_aut').val(data[0].NOMBRE);
                                        $('#apat_aut').val(data[0].APELLIDO);
                                        $('#amat_aut').val(data[0].APELLIDOM);
                                        $('#puesto_aut').val(data[0].PUESTO);
                                        $('#aviso_jefe').hide();
                                        $('#aviso_aut').hide();
                                        $('#rep_aut').hide();
                                        $('#req_aut').hide();
                                        $('#sin_data3').hide();
                                    }else{
                                        var element1 = document.getElementById("n_aut");
                                        element1.classList.remove("campo-requerido");
                                        var element2 = document.getElementById("correo_aut");
                                        element2.classList.remove("campo-requerido");
                                        $('#aviso_correo_aut').show();
                                        $('#n_aut').val('');
                                        $('#correo_aut').val('')
                                        $('#nom_aut').val('');
                                        $('#apat_aut').val('');
                                        $('#amat_aut').val('');
                                        $('#puesto_aut').val('');
                                        $('#sin_data3').hide();
                                    }
                                }else{
                                    $('#rep_aut').show();
                                    $('#n_aut').val('');
                                    $('#correo_aut').val('')
                                    $('#nom_aut').val('');
                                    $('#apat_aut').val('');
                                    $('#amat_aut').val('');
                                    $('#puesto_aut').val('');
                                    $('#sin_data3').hide();
                                }
                            }
                        } else {
                            $('#n_aut').val('');
                            $('#correo_aut').val('');
                            $('#nom_aut').val('');
                            $('#apat_aut').val('');
                            $('#amat_aut').val('');
                            $('#puesto_aut').val('');
                            $('#aviso_jefe').hide('');
                            var apps = $('#apps').val();
                            var app = JSON.parse(apps);
                            for (var clave in app){
                                    if (clave == 50) {             
                                    $('#aviso_aut2').hide('');
                                    $('#aviso_aut_set').show('');
                                }else{
                                    $('#aviso_aut2').show('');
                                    $('#aviso_aut_set').hide('');   
                                }
                            }
                            $('#sin_data3').hide();
                        }
                    } else {

                        var element1 = document.getElementById("n_aut");
                        element1.classList.remove("campo-requerido");
                        var element2 = document.getElementById("correo_aut");
                        element2.classList.remove("campo-requerido");
                        $('#n_aut').val('');
                        $('#correo_aut').val('');
                        $('#nom_aut').val('');
                        $('#apat_aut').val('');
                        $('#amat_aut').val('');
                        $('#puesto_aut').val('');
                        $('#aviso_jefe').hide('');
                        $('#aviso_correo_aut').hide('')
                        $('#aviso_aut_set').hide('');
                        $('#aviso_aut2').hide('');
                        $('#req_aut').hide('');
                        $('#rep_autrep_aut_ext').hide('');
                        $('#sin_data3').show();
                        event.stopImmediatePropagation();
                        event.preventDefault();
                    }
                break;
                case 4:
                    if (data.respuesta != "No se encontro el registro")
                    {
                        if (data[0].mail && mov == 'Alta') {
                            if (a == 2) {
                                $('#aviso_con_mail_terce').show();
                                $('#ficha_ter').val('');
                                $('#empresa_t').val('');
                                $('#nombre_t').val(''); 
                                $('#a_pat_t').val('');
                                $('#a_mat_t').val('');
                                $('#ubicacion_t').val('');
                                $('#proyecto').val('');
                                $('#vigencia').val('');
                            }
                            else{
                                $('#aviso_con_mail_terce').hide();
                                $('#sin_data4').hide();
                                $('#ficha_ter').val(parseInt(data[0].FICHA, 10));
                                $('#empresa_t').val(data[0].EMPRESA);
                                $('#nombre_t').val(data[0].NOMBRE);
                                $('#a_pat_t').val(data[0].APELLIDO);
                                $('#a_mat_t').val(data[0].APELLIDOM);
                                $('#ubicacion_t').val(data[0].UBICACION);
                                $('#proyecto').val(data[0].PROYECTO_EXT);
                                $('#vigencia').val(data[0].VIGENCIA);
                            }
                        }else{
                            $('#aviso_con_mail_terce').hide();
                            $('#sin_data4').hide();
                            $('#ficha_ter').val(parseInt(data[0].FICHA, 10));
                            $('#empresa_t').val(data[0].EMPRESA);
                            $('#nombre_t').val(data[0].NOMBRE);
                            $('#a_pat_t').val(data[0].APELLIDO);
                            $('#a_mat_t').val(data[0].APELLIDOM);
                            $('#ubicacion_t').val(data[0].UBICACION);
                            $('#proyecto').val(data[0].PROYECTO_EXT);
                            $('#vigencia').val(data[0].VIGENCIA);
                        }
                    }else  {
                        $('#sin_data4').show();
                        $('#ficha_ter').val('');
                        $('#empresa_t').val('');              
                        $('#nombre_t').val('');               
                        $('#a_mat_t').val('');               
                        $('#ubicacion_t').val('');               
                        $('#proyecto').val('');               
                        $('#vigencia').val('');         
                        event.stopImmediatePropagation();
                        event.preventDefault();
                    }
                break;
            }
                
        }

    });
}
  $(document).on('focus','.autocomplete_interno', function()
    {
        type = $(this).data('type');
        var tipo = $('#tipo_fus').val();
        var mov = $('#movimiento').val();
        var a = $(".d_ext:checked").val();
        if(type =='n_empleado')autoType='FICHA';
        if(type =='mail_sol')autoType='mail';
        if(type =='u_red')autoType='u_red';
        if(type =='nombre')autoType='NOMBRE';
        if(type =='a_paterno')autoType='APELLIDO';
        if(type =='a_materno')autoType='APELLIDOM';
        if(type =='puesto')autoType='puesto';
        if(type =='empresa')autoType='empresa';
        if(type =='dep')autoType='dep';
        if(type =='ubi')autoType='ubi';
        if(type =='tel')autoType='tel';
        if(type =='ext')autoType='ext';
        if(type =='c_costos')autoType='c_costos';
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
                    if (tipo == 1 || tipo == 2) {
                        if (data.mail && mov == 'Alta') {
                            if (a == 1) {
                                $('#aviso_con_mail').show();
                                $('#n_empleado').val('');
                                // $('#mail_sol').val('');
                                // $('#u_red').val('');
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
                                $('#aviso_soli').hide();
                            }else{
                                $('#n_empleado').val(parseInt(data.FICHA, 10)); 
                                $('#mail_sol').val(data.mail);
                                $('#u_red').val(data.u_red);
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
                            }
                        }
                        else{
                            
                            $('#n_empleado').val(parseInt(data.FICHA, 10));
                            // $('#mail_sol').val(data.mail);
                            // $('#u_red').val(data.u_red);
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
                            $('#aviso_soli').hide();
                            $('#aviso_con_mail').hide();
                        }
                    }else if(tipo == 3) {
                            $('#n_empleado').val(parseInt(data.FICHA, 10));
                            $('#mail_sol').val(data.mail);
                            $('#u_red').val(data.u_red);
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
                            $('#aviso_soli').hide();
                            $('#aviso_con_mail').hide();
                    }
                    else {
                        if (data.mail) {
                            $('#n_empleado').val(parseInt(data.FICHA, 10));
                            $('#mail_sol').val(data.mail);
                            $('#u_red').val(data.u_red);
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
                        }
                         else {
                            $('#n_empleado').val('');
                            // $('#mail_sol').val('');
                            // $('#u_red').val('');
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
                            $('#aviso_soli').show();
                        }
                    }
                } else if (data.respuesta == "No se encontro el registro") {
                    event.stopImmediatePropagation();
                    event.preventDefault();
                }
            }

            });
        });
    $(document).on('focus','.autocomplete_jefe', function()
    {
        type = $(this).data('type');
        var solici = $('#n_empleado').val();
        var tipo = $('#tipo_fus').val();
        var per_ext = $(".d_ext:checked").val();
        if(type =='n_jefe')autoType='FICHA';
        if(type =='correo_jefe')autoType='FICHA';
        if(type =='nom_jefe')autoType='NOMBRE';
        if(type =='apat_jefe')autoType='APELLIDO';
        if(type =='amat_jefe')autoType='APELLIDOM';
        if(type =='puesto_jefe')autoType='puesto';
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
                        search: 3
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
                if (data.respuesta != "No se encontro el registro")
                {
                    // esta es la cadena donde buscaremos
                    let cadena = data.PUESTO;
                    var palabras = cadena.split(" ");
                    var iguales = jerarquia(palabras);
                    if(iguales > 0){
                        if (data.mail) {
                            if (tipo == 1 || tipo == 2) {
                                if (data.FICHA != solici){
                                    $('#n_jefe').val(parseInt(data.FICHA, 10)); 
                                    $('#correo_jefe').val(data.mail);
                                    $('#nom_jefe').val(data.NOMBRE);
                                    $('#apat_jefe').val(data.APELLIDO);
                                    $('#amat_jefe').val(data.APELLIDOM);
                                    $('#puesto_jefe').val(data.PUESTO);
                                    $('#aviso_correo_jefe').hide();
                                    $('#aviso_jefe').hide();
                                    $('#rep_sus').hide();
                                }else{
                                    $('#rep_sus').show();
                                    $('#n_jefe').val('');
                                    $('#correo_jefe').val('')
                                    $('#nom_jefe').val('');
                                    $('#apat_jefe').val('');
                                    $('#amat_jefe').val('');
                                    $('#puesto_jefe').val('');
                                }
                            }else if(tipo == 0){
                                    $('#aviso_correo_jefe').hide();
                                    $('#aviso_jefe').hide();
                                    $('#aviso_jefe2').hide();
                                    $('#aviso_jefe3').hide();
                                    $('#n_jefe').val(parseInt(data.FICHA, 10)); 
                                    $('#correo_jefe').val(data.mail);
                                    $('#nom_jefe').val(data.NOMBRE);
                                    $('#apat_jefe').val(data.APELLIDO);
                                    $('#amat_jefe').val(data.APELLIDOM);
                                    $('#puesto_jefe').val(data.PUESTO);
                                    $('#aviso_correo_jefe').hide(); 
                                    $('#rep_sus').hide();
                            }else{
                                $('#aviso_correo_jefe').hide();
                                $('#aviso_jefe2').hide();
                                $('#sin_data2').hide();
                                $('#n_jefe').val(parseInt(data.FICHA, 10)); 
                                $('#correo_jefe').val(data.mail);
                                $('#nom_jefe').val(data.NOMBRE);
                                $('#apat_jefe').val(data.APELLIDO);
                                $('#amat_jefe').val(data.APELLIDOM);
                                $('#puesto_jefe').val(data.PUESTO);
                            }
                        } else {
                            $('#aviso_correo_jefe').show();
                            $('#n_jefe').val('');
                            $('#correo_jefe').val('')
                            $('#nom_jefe').val('');
                            $('#apat_jefe').val('');
                            $('#amat_jefe').val('');
                            $('#puesto_jefe').val('');
                        }

                    }
                    else
                    {
                        if (data.mail) {
                            if (tipo == 0) {
                                if( per_ext == 1){
                                    var especial = true;
                                    $('#aviso_correo_jefe').hide();
                                    var apps = $('#apps').val();
                                    var app = JSON.parse(apps);
                                    for (var clave in app){
                                        if (clave != 50) {
                                            $('#aviso_jefe2').show();  
                                            especial = false; 
                                        }
                                    }
                                    if (especial == true) {
                                        $('#aviso_jefe').hide();
                                        $('#aviso_jefe2').hide();
                                        $('#aviso_jefe3').hide();
                                        $('#n_jefe').val(parseInt(data.FICHA, 10)); 
                                        $('#correo_jefe').val(data.mail);
                                        $('#nom_jefe').val(data.NOMBRE);
                                        $('#apat_jefe').val(data.APELLIDO);
                                        $('#amat_jefe').val(data.APELLIDOM);
                                        $('#puesto_jefe').val(data.PUESTO);
                                        $('#aviso_correo_jefe').hide(); 
                                        $('#rep_sus').hide();
                                    }else{
                                        $('#n_jefe').val('');
                                        $('#correo_jefe').val('')
                                        $('#nom_jefe').val('');
                                        $('#apat_jefe').val('');
                                        $('#amat_jefe').val('');
                                        $('#puesto_jefe').val('');
                                    }
                                }else{
                                    $('#aviso_jefe').hide();
                                    $('#aviso_jefe2').hide();
                                    $('#aviso_jefe3').hide();
                                    $('#n_jefe').val(parseInt(data.FICHA, 10)); 
                                    $('#correo_jefe').val(data.mail);
                                    $('#nom_jefe').val(data.NOMBRE);
                                    $('#apat_jefe').val(data.APELLIDO);
                                    $('#amat_jefe').val(data.APELLIDOM);
                                    $('#puesto_jefe').val(data.PUESTO);
                                    $('#aviso_correo_jefe').hide(); 
                                    $('#rep_sus').hide();
                                } 
                            }else if (data.FICHA != solici){
                                $('#aviso_jefe').show();
                                $('#n_jefe').val(parseInt(data.FICHA, 10)); 
                                $('#correo_jefe').val(data.mail);
                                $('#nom_jefe').val(data.NOMBRE);
                                $('#apat_jefe').val(data.APELLIDO);
                                $('#amat_jefe').val(data.APELLIDOM);
                                $('#puesto_jefe').val(data.PUESTO);
                                $('#aviso_correo_jefe').hide(); 
                                $('#rep_sus').hide();
                            }else{
                                $('#rep_sus').show();
                                $('#n_jefe').val('');
                                $('#correo_jefe').val('')
                                $('#nom_jefe').val('');
                                $('#apat_jefe').val('');
                                $('#amat_jefe').val('');
                                $('#puesto_jefe').val('');
                            }
                            // $('#aviso_jefe').hide();
                            // $('#aviso_jefe').hide();
                        } else {
                            $('#aviso_correo_jefe').show();
                            $('#n_jefe').val('');
                            $('#correo_jefe').val('')
                            $('#nom_jefe').val('');
                            $('#apat_jefe').val('');
                            $('#amat_jefe').val('');
                            $('#puesto_jefe').val('');
                        }
                    }
                } else if (data.respuesta != "No se encontro el registro") {
                    $('#n_jefe').val('');
                    $('#correo_jefe').val('')
                    $('#nom_jefe').val('');
                    $('#apat_jefe').val('');
                    $('#amat_jefe').val('');
                    $('#puesto_jefe').val('');
                    event.stopImmediatePropagation();
                    event.preventDefault();
                }
            }
            });
        });
    $(document).on('focus','.autocomplete_aut', function()
    {
        type = $(this).data('type');
        var solici = $('#n_empleado').val();
        var tipo = $('#tipo_fus').val();
        if(type =='n_aut')autoType='FICHA';
        if(type =='correo_jefe')autoType='FICHA';
        if(type =='nom_jefe')autoType='NOMBRE';
        if(type =='apat_jefe')autoType='APELLIDO';
        if(type =='amat_jefe')autoType='APELLIDOM';
        if(type =='puesto_jefe')autoType='puesto';
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
                        search: 3
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
                
                if (data.respuesta != "No se encontro el registro")
                {
                    // esta es la cadena donde buscaremos
                    let cadena = data.PUESTO;
                    var palabras = cadena.split(" ");
                    var iguales = jerarquia(palabras);
                    if(iguales > 0){
                        if (data.mail) {
                            if (data.FICHA != solici){
                                $('#aviso_correo_aut').hide();
                                $('#n_aut').val(parseInt(data.FICHA, 10));
                                $('#correo_aut').val(data.mail);
                                $('#nom_aut').val(data.NOMBRE);
                                $('#apat_aut').val(data.APELLIDO);
                                $('#amat_aut').val(data.APELLIDOM);
                                $('#puesto_aut').val(data.PUESTO);
                                $('#sin_data3').hide();
                                $('#aviso_jefe').hide();
                                $('#aviso_aut').hide();
                                $('#rep_aut').hide();
                                $('#sin_num_t').hide();
                                $('#req_aut').hide();
                            }else{
                                $('#rep_aut').show();
                                $('#n_jefe').val('');
                                $('#correo_jefe').val('')
                                $('#nom_jefe').val('');
                                $('#apat_jefe').val('');
                                $('#amat_jefe').val('');
                                $('#puesto_jefe').val('');
                            }
                        }else{
                            $('#aviso_correo_aut').show();
                            $('#n_aut').val('');
                            $('#correo_jefe').val('')
                            $('#nom_jefe').val('');
                            $('#apat_jefe').val('');
                            $('#amat_jefe').val('');
                            $('#puesto_jefe').val('');
                        }
                    }
                    else
                    {
                        $('#n_aut').val('')
                        $('#correo_aut').val('')
                        $('#nom_aut').val('');
                        $('#apat_aut').val('');
                        $('#amat_aut').val('');
                        $('#puesto_aut').val('');
                        $('#aviso_jefe').hide('');
                        if (tipo == 0) {
                            var apps = $('#apps').val();
                            var app = JSON.parse(apps);
                            for (var clave in app){
                                if (clave == 50) {
                                    $('#aviso_aut_set').show('');
                                }else{
                                    $('#aviso_aut2').show('');   
                                }
                            }
                        }else{
                            $('#aviso_aut').show('');
                        }
                    }
                } else if(data.respuesta == "fail") {
                    event.stopImmediatePropagation();
                    event.preventDefault();
                }
            }
            });
        });
$(document).on('focus','.autocomplete_externo', function(){
        type = $(this).data('type');
        var a = $(".d_ext:checked").val();
        var mov = $('#movimiento').val();
        if(type =='ficha_t')autoType='FICHA';
        if(type =='empresa_t')autoType='EMPRESA';
        if(type =='nombre_t')autoType='NOMBRE';
        if(type =='a_pat_t')autoType='APELLIDO';
        if(type =='a_mat_t')autoType='APELLIDOM';
        if(type =='ubicacion_t')autoType='puesto';
        if(type =='proyecto')autoType='proyecto';
        if(type =='vigencia')autoType='vigencia';
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
                
                if (data.respuesta != "No se encontro el registro")
                {
                    if (data.mail && mov == 'Alta' && a == 2) {
                        $('#aviso_con_mail_terce').show();
                        $('#ficha_ter').val('');
                        $('#empresa_t').val('');
                        $('#nombre_t').val(''); 
                        $('#a_pat_t').val('');
                        $('#a_mat_t').val('');
                        $('#ubicacion_t').val('');
                        $('#proyecto').val('');
                        $('#vigencia').val('');
                    }
                    else{
                        $('#aviso_con_mail_terce').hide();
                        $('#sin_data4').hide();
                        $('#ficha_ter').val(parseInt(data.FICHA, 10));
                        $('#empresa_t').val(data.EMPRESA);
                        $('#nombre_t').val(data.NOMBRE);
                        $('#a_pat_t').val(data.APELLIDO);
                        $('#a_mat_t').val(data.APELLIDOM);
                        $('#ubicacion_t').val(data.UBICACION);
                        $('#proyecto').val(data.PROYECTO_EXT);
                        $('#vigencia').val(data.VIGENCIA);
                    }
                } else if(data.respuesta == "No se encontro el registro") {
                    event.stopImmediatePropagation();
                    event.preventDefault();
                }
            }
        });
    });
</script>
@endpush