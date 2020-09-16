@extends('layouts.app')
@if(count($objetosAutorizar) > 0)
    @section('content')
    <style>
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
    </style>
    <link href="{{ asset('css/fusarevisar.css') }}" rel="stylesheet">
    <!-- Encabezado -->
    <div class="container-fluid">
        @if(!isset($tipo) || $tipo == 0)
            <div class="row mt-3 mb-3"><div class="col-lg-12"><a class="btn btn-warning btn-block" style="color:#FFFFFF;" href="{{ route('listafusesporautorizar') }}">Regresar</a></div></div>
        @endif
        <div class="row">
            <div class="col-lg-12">
                <div class="card mb-3">
                    <div class="card-header">
                        <strong>FUS ELECTRÓNICO #{{$fus['id']}}</strong>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                        @switch($fus['tipo_fus'])
                            @case(1)
                            @case(2)
                                @if(!empty($fus['ext_ficha']))
                                    <div class="col-lg-12"><strong>Datos del capturista</strong></div>
                                @else
                                    <div class="col-lg-12"><strong>Datos del nuevo usuario</strong></div>
                                @endif
                            @break
                        @default
                            <div class="col-lg-12"><strong>Datos del Solicitante</strong></div>
                        @break
                        @endswitch
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-lg-3"><strong>Nombre Completo:</strong></div>
                            <div class="col-lg-3">{{ucwords(mb_strtolower($fus['nombre'], "UTF-8"))}} {{ucwords(mb_strtolower($fus['a_paterno'], "UTF-8"))}} {{ucwords(mb_strtolower($fus['a_materno'], "UTF-8"))}}</div>
                            <div class="col-lg-3"><strong>Puesto:</strong></div>
                            <div class="col-lg-3">{{ucwords(mb_strtolower($fus['puesto'], "UTF-8"))}}</div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3"><strong>Ubicación del Edificio:</strong></div>
                            <div class="col-lg-3">{{ucwords(mb_strtolower($fus['ubicacion_edificio'], "UTF-8"))}}</div>
                            <div class="col-lg-3"><strong>Ext.:</strong></div>
                            <div class="col-lg-3">{{$fus['tel_ext']}}</div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3"><strong>Departamento:</strong></div>
                            <div class="col-lg-3">{{ucwords(mb_strtolower($fus['departamento'], "UTF-8"))}}</div>
                            <div class="col-lg-3"><strong>Centro de Costos:</strong></div>
                            <div class="col-lg-3">{{ucwords(mb_strtolower($fus['centro_costos'], "UTF-8"))}}</div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3"><strong># de Empleado:</strong></div>
                            <div class="col-lg-3">{{$fus['no_empleado']}}</div>
                            <div class="col-lg-3"><strong>Empresa:</strong></div>
                            <div class="col-lg-3">{{ucwords(mb_strtolower($fus['empresa_nombre'], "UTF-8"))}}</div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3"><strong>Vigencia:</strong></div>
                            <div class="col-lg-6">{{$fus['vigencia']}}</div>
                        </div>
                        @switch($fus['tipo_fus'])
                        @case(1)
                        @case(2)
                            <div class="row">
                                <div class="col-lg-3"><strong>Usuario de Red de seguimiento:</strong></div>
                                <div class="col-lg-3">{{$fus['usuario_red']}}</div>
                                <div class="col-lg-3"><strong>Correo de seguimiento:</strong></div>
                                <div class="col-lg-3">{{$fus['correo_corporativo']}}</div>
                            </div>
                            @break
                        @default
                            <div class="row">
                                <div class="col-lg-3"><strong>Usuario de Red:</strong></div>
                                <div class="col-lg-3">{{$fus['usuario_red']}}</div>
                                <div class="col-lg-3"><strong>Correo Corporativo:</strong></div>
                                <div class="col-lg-3">{{$fus['correo_corporativo']}}</div>
                            </div>
                            @break
                        @endswitch
                        <hr>
                        <div class="row mb-3">
                        @switch($fus['tipo_fus'])
                        @case(1)
                        @case(2)
                            @if(!empty($fus['ext_ficha']))
                                <div class="col-lg-12"><strong>Datos del Responsable del nuevo usuario externo</strong></div>
                            @else
                                <div class="col-lg-12"><strong>Datos del jefe del nuevo usuario</strong></div>
                            @endif
                            @break
                        @case(3)
                            <div class="col-lg-12"><strong>Datos del Responsable de la cuenta</strong></div>
                            @break
                        @default
                            <div class="col-lg-12"><strong>Datos del Responsable o jefe del Solicitante</strong></div>
                        @break
                        @endswitch
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-lg-3"><strong># de Empleado del Jefe:</strong></div>
                            <div class="col-lg-3">{{$fus['no_empleado_jefe']}}</div>
                            <div class="col-lg-3"><strong>Correo:</strong></div>
                            <div class="col-lg-3">{{$fus['correo_jefe']}}</div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3"><strong>Puesto:</strong></div>
                            <div class="col-lg-3">{{ucwords(mb_strtolower($fus['puesto_jefe'], "UTF-8"))}}</div>
                            <div class="col-lg-3"><strong>Nombre:</strong></div>
                            <div class="col-lg-3">{{ucwords(mb_strtolower($fus['nombre_jefe'], "UTF-8"))}} {{ucwords(mb_strtolower($fus['apat_jefe'], "UTF-8"))}} {{ucwords(mb_strtolower($fus['amat_jefe'], "UTF-8"))}}</div>
                        </div>
                        @if(!empty($fus['no_empleado_aut']))
                        <hr>
                        <div class="row mb-3">
                            <div class="col-lg-12"><strong>Datos del Autorizador</strong></div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-lg-3"><strong># de Empleado del Autorizador:</strong></div>
                            <div class="col-lg-3">{{$fus['no_empleado_aut']}}</div>
                            <div class="col-lg-3"><strong>Correo:</strong></div>
                            <div class="col-lg-3">{{$fus['aut_correo']}}</div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3"><strong>Puesto:</strong></div>
                            <div class="col-lg-3">{{ucwords(mb_strtolower($fus['aut_puesto'], "UTF-8"))}}</div>
                            <div class="col-lg-3"><strong>Nombre:</strong></div>
                            <div class="col-lg-3">{{ucwords(mb_strtolower($fus['aut_nombre'], "UTF-8"))}} {{ucwords(mb_strtolower($fus['aut_apat'], "UTF-8"))}} {{ucwords(mb_strtolower($fus['aut_amat'], "UTF-8"))}}</div>
                        </div>
                        @endif
                        @if(!empty($fus['ext_ficha']))
                        <hr>
                        <div class="row mb-3">
                            @switch($fus['tipo_fus'])
                                @case(1)
                                @case(2)
                                    <div class="col-lg-12"><strong>Datos de la nueva cuenta del externo</strong></div>
                                @break
                                @default
                                    <div class="col-lg-12"><strong>Datos del Externo</strong></div>
                                @break
                            @endswitch
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-lg-3"><strong># de Ficha (Gafete):</strong></div>
                            <div class="col-lg-3">{{$fus['ext_ficha']}}</div>
                            <div class="col-lg-3"><strong>Empresa:</strong></div>
                            <div class="col-lg-3">{{ucwords(mb_strtolower($fus['ext_empresa'], "UTF-8"))}}</div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3"><strong>Nombre:</strong></div>
                            <div class="col-lg-3">{{ucwords(mb_strtolower($fus['ext_nombre'], "UTF-8"))}} {{ucwords(mb_strtolower($fus['ext_apat'], "UTF-8"))}} {{ucwords(mb_strtolower($fus['ext_amat'], "UTF-8"))}}</div>
                            <div class="col-lg-3"><strong>Ubicación:</strong></div>
                            <div class="col-lg-3">{{ucwords(mb_strtolower($fus['ext_ubicacion'], "UTF-8"))}}</div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3"><strong>Proyecto:</strong></div>
                            <div class="col-lg-3">{{ucwords(mb_strtolower($fus['ext_proyecto'], "UTF-8"))}}</div>
                            <div class="col-lg-3"><strong>Vigencia:</strong></div>
                            <div class="col-lg-3">{{$fus['ext_vigencia']}}</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Descargas -->
    @if(count($descargas) > 0)
    <div class="container-fluid mb-3">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <strong>Descargas</strong>
                    </div>
                    <div class="card-body">
                        @foreach($descargas AS $kdg => $vdg)
                            <div class="row">
                                <div class="col-lg-12"><strong>{{$kdg}}</strong></div>
                                <div class="col-lg-12">
                                    @foreach($vdg AS $dg)
                                        <a href="{{ url('/descargas/anexos') }}/{{$dg['path']}}">{{$dg['path']}}</a>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    <!-- Fin de descargas -->
    <!-- Fin Encabezado -->
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <form method="POST" action="{{route('guardarautorizaciones')}}" accept-charset="UTF-8" enctype="multipart/form-data" id="form-revisar">
                    @csrf
                    <!-- Carga de Anexos -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <strong>Carga de Anexos</strong>
                        </div>
                        <div class="card-body">
                            <div id="documento" name="documento">
                                <div class="form-group row">
                                    <div class="col-md-5">
                                        <input type="file" class="form-control car-arch" id="archivo" name="archivo[0][file]" data-type="doc_0">
                                    </div>
                                    <div class="col-md-5">
                                        <select class="form-control appselect" name="archivo[0][app]" id = "id_app_fus" data-type="app_0">
                                            <option value="">Selecciona una aplicación</option>
                                            @foreach($apps AS $keys)
                                                <option value="{{ $keys['applications_id'] }}">{{ $keys['nombre_app'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="button" class="btn btn-success" id="add_field" value="Agregar Anexos">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Fin de carga de anexos -->
                    <div class="card">
                        <div class="card-header">
                            <strong>Autorizaciones pendientes FUS-e #{{$idFus}}</strong>
                        </div>
                        @php
                        // dd($apps);
                        @endphp
                        <div class="card-body">
                            <div class="form-group row card">
                                @foreach($apps AS $index => $value)
                                    @php
                                        $colmd = 12;
                                    @endphp
                                    
                                    <div class="card-header">
                                        {{$value['nombre_app']}} <input type="button" class="btn btn-info btn-detalles" data-app="{{$value['nombre_app']}}" data-detalles="{{$value['detalles']}}" style="color:#FFFFFF;float:right;" value="Ver Detalles de la aplicación">
                                    </div>
                                    <div class="card-body">
                                        <input type="hidden" name="idFus" value="{{$idFus}}">
                                        <div class="form-group row">
                                        
                                            @if(
                                                isset($objetosAutorizar['mesas']) && 
                                                isset($objetosAutorizar['mesas'][$value['applications_id']])
                                            )
                                                <div class="col-md-{{$colmd}}">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <strong>Autorización como MC</strong>
                                                        </div>
                                                        <div class="col-md-12">
                                                            @switch($value['applications_id'])
                                                                @case(20)
                                                                @case(16)
                                                                @case(64)
                                                                    @if(isset($objetosAutorizar['mesas'][$value['applications_id']]['Prod']))
                                                                    <div class="form-group row">
                                                                        <div class="col-md-12"><strong>PROD</strong></div>
                                                                    </div>
                                                                    @foreach($objetosAutorizar['mesas'][$value['applications_id']]['Prod'] AS $row => $content)
                                                                        @if($content['applications_id'] == $value['applications_id'])
                                                                            <div class="form-group row">
                                                                                <div class="col-md-6">
                                                                                    {{$content['rol_mod_rep_formateado']}}
                                                                                </div>
                                                                                <div class="col-md-6">
                                                                                    @if($content['estadoActualG'] == 0 && $content['estado_autorizacion'] == 0)
                                                                                    <select class="form-control autorizacion" data-tipo="mesa" data-idapp="{{$value['applications_id']}}" data-rolmodrep="{{$content['fus_configuracion_autorizaciones_id']}}" name="autorizacion[{{$value['applications_id']}}][{{str_replace(',', '', str_replace(' ', '', $content['rol_mod_rep_formateado']))}}][{{$content['idrelAut']}}][accion]">
                                                                                        <option value="0">Seleccione...</option>
                                                                                        <option value="1">Autorizar</option>
                                                                                        <option value="2">Rechazar</option>
                                                                                    </select>
                                                                                    @else
                                                                                        @switch($content['estadoActualG'])
                                                                                            @case(1)
                                                                                                {{__('Autorizado')}}
                                                                                                @break
                                                                                            @case(2)
                                                                                                {{__('Rechazado')}}
                                                                                                @break
                                                                                        @endswitch
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group row motivorechazo" id="motivorechazo_{{$value['applications_id']}}_{{$content['fus_configuracion_autorizaciones_id']}}_mesa">
                                                                                <div class="col-md-12">
                                                                                    Motivo de rechazo
                                                                                </div>
                                                                                <div class="col-md-12">
                                                                                    @if($content['estadoActualG'] == 0 && $content['estado_autorizacion'] == 0)
                                                                                    <textarea class="form-control" name="autorizacion[{{$value['applications_id']}}][{{str_replace(',', '', str_replace(' ', '', $content['rol_mod_rep_formateado']))}}][{{$content['idrelAut']}}][motivorechazo]" id="txtMotivorechazo_{{$value['applications_id']}}_{{$value['fus_configuracion_autorizaciones_id']}}_mesa"></textarea>
                                                                                    @else
                                                                                        @switch($content['estadoActualG'])
                                                                                            @case(2)
                                                                                                {{__('Rechazado')}}
                                                                                                @break
                                                                                        @endswitch
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                        @endif
                                                                    @endforeach
                                                                    @endif
                                                                    @if(isset($objetosAutorizar['mesas'][$value['applications_id']]['Intermex']))
                                                                    <div class="form-group row">
                                                                        <div class="col-md-12"><strong>INTERMEX</strong></div>
                                                                    </div>
                                                                    @foreach($objetosAutorizar['mesas'][$value['applications_id']]['Intermex'] AS $row => $content)
                                                                        @if($content['applications_id'] == $value['applications_id'])
                                                                            <div class="form-group row">
                                                                                <div class="col-md-6">
                                                                                    {{$content['rol_mod_rep_formateado']}}
                                                                                </div>
                                                                                <div class="col-md-6">
                                                                                    @if($content['estadoActualG'] == 0 && $content['estado_autorizacion'] == 0)
                                                                                    <select class="form-control autorizacion" data-tipo="mesa" data-idapp="{{$value['applications_id']}}" data-rolmodrep="{{$content['fus_configuracion_autorizaciones_id']}}" name="autorizacion[{{$value['applications_id']}}][{{str_replace(',', '', str_replace(' ', '', $content['rol_mod_rep_formateado']))}}][{{$content['idrelAut']}}][accion]">
                                                                                        <option value="0">Seleccione...</option>
                                                                                        <option value="1">Autorizar</option>
                                                                                        <option value="2">Rechazar</option>
                                                                                    </select>
                                                                                    @else
                                                                                        @switch($content['estadoActualG'])
                                                                                            @case(1)
                                                                                                {{__('Autorizado')}}
                                                                                                @break
                                                                                            @case(2)
                                                                                                {{__('Rechazado')}}
                                                                                                @break
                                                                                        @endswitch
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group row motivorechazo" id="motivorechazo_{{$value['applications_id']}}_{{$content['fus_configuracion_autorizaciones_id']}}_mesa">
                                                                                <div class="col-md-12">
                                                                                    Motivo de rechazo
                                                                                </div>
                                                                                <div class="col-md-12">
                                                                                    @if($content['estadoActualG'] == 0 && $content['estado_autorizacion'] == 0)
                                                                                    <textarea class="form-control" name="autorizacion[{{$value['applications_id']}}][{{str_replace(',', '', str_replace(' ', '', $content['rol_mod_rep_formateado']))}}][{{$content['idrelAut']}}][motivorechazo]" id="txtMotivorechazo_{{$value['applications_id']}}_{{$value['fus_configuracion_autorizaciones_id']}}_mesa"></textarea>
                                                                                    @else
                                                                                        @switch($content['estadoActualG'])
                                                                                            @case(2)
                                                                                                {{__('Rechazado')}}
                                                                                                @break
                                                                                        @endswitch
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                        @endif
                                                                    @endforeach
                                                                    @endif
                                                                    @break
                                                                @default
                                                                    @foreach($objetosAutorizar['mesas'][$value['applications_id']] AS $row => $content)
                                                                    @php
                                                                    // dd($content);
                                                                    @endphp
                                                                        @if($content['applications_id'] == $value['applications_id'])
                                                                            <div class="form-group row">
                                                                                <div class="col-md-6">
                                                                                    {{$content['rol_mod_rep_formateado']}}
                                                                                </div>
                                                                                <div class="col-md-6">
                                                                                    @if($content['estadoActualG'] == 0 && $content['estado_autorizacion'] == 0)
                                                                                    <select class="form-control autorizacion" data-tipo="mesa" data-idapp="{{$value['applications_id']}}" data-rolmodrep="{{$content['fus_configuracion_autorizaciones_id']}}" name="autorizacion[{{$value['applications_id']}}][{{str_replace(',', '', str_replace(' ', '', $content['rol_mod_rep_formateado']))}}][{{$content['idrelAut']}}][accion]">
                                                                                        <option value="0">Seleccione...</option>
                                                                                        <option value="1">Autorizar</option>
                                                                                        <option value="2">Rechazar</option>
                                                                                    </select>
                                                                                    @else
                                                                                        @switch($content['estadoActualG'])
                                                                                            @case(1)
                                                                                                {{__('Autorizado')}}
                                                                                                @break
                                                                                            @case(2)
                                                                                                {{__('Rechazado')}}
                                                                                                @break
                                                                                        @endswitch
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group row motivorechazo" id="motivorechazo_{{$value['applications_id']}}_{{$content['fus_configuracion_autorizaciones_id']}}_mesa">
                                                                                <div class="col-md-12">
                                                                                    Motivo de rechazo
                                                                                </div>
                                                                                <div class="col-md-12">
                                                                                    @if($content['estadoActualG'] == 0 && $content['estado_autorizacion'] == 0)
                                                                                    <textarea class="form-control" name="autorizacion[{{$value['applications_id']}}][{{str_replace(',', '', str_replace(' ', '', $content['rol_mod_rep_formateado']))}}][{{$content['idrelAut']}}][motivorechazo]" id="txtMotivorechazo_{{$value['applications_id']}}_{{$value['fus_configuracion_autorizaciones_id']}}_mesa"></textarea>
                                                                                    @else
                                                                                        @switch($content['estadoActualG'])
                                                                                            @case(2)
                                                                                                {{__('Rechazado')}}
                                                                                                @break
                                                                                        @endswitch
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                        @endif
                                                                    @endforeach
                                                                    @break
                                                            @endswitch
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                            @php
                                            //dd($checks['mesas'][$value['applications_id']]);
                                            @endphp
                                            @if(
                                                $checks['mesas'][$value['applications_id']] >= 1
                                            )
                                                @if(
                                                    isset($objetosAutorizar['autorizadores']) && 
                                                    isset($objetosAutorizar['autorizadores'][$value['applications_id']])
                                                )
                                                    <div class="col-md-{{$colmd}}">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <strong>Autorización como Autorizador</strong>
                                                            </div>
                                                            <div class="col-md-12"> 
                                                                @switch($value['applications_id'])
                                                                    @case(20)
                                                                    @case(16)
                                                                    @case(64)
                                                                        @if(isset($objetosAutorizar['autorizadores'][$value['applications_id']]['Prod']))
                                                                        <div class="form-group row">
                                                                            <div class="col-md-12"><strong>PROD</strong></div>
                                                                        </div>
                                                                        @foreach($objetosAutorizar['autorizadores'][$value['applications_id']]['Prod'] AS $row => $content)
                                                                            @if(
                                                                                $checks['calculocompleto'][$content['rol_mod_rep']]['rechazados_mesas'] == 0
                                                                            )
                                                                                @if($content['applications_id'] == $value['applications_id'])
                                                                                    <div class="form-group row">
                                                                                        <div class="col-md-6">
                                                                                            {{$content['rol_mod_rep_formateado']}}
                                                                                        </div>
                                                                                        <div class="col-md-6">
                                                                                            @if($content['estadoActualG'] == 0 && $content['estado_autorizacion'] == 0)
                                                                                            <select class="form-control autorizacion" data-tipo="autorizador" data-idapp="{{$value['applications_id']}}" data-rolmodrep="{{$content['fus_configuracion_autorizaciones_id']}}" name="autorizacion[{{$value['applications_id']}}][{{str_replace(',', '', str_replace(' ', '', $content['rol_mod_rep_formateado']))}}][{{$content['idrelAut']}}][accion]">
                                                                                                <option value="0">Seleccione...</option>
                                                                                                <option value="1">Autorizar</option>
                                                                                                <option value="2">Rechazar</option>
                                                                                            </select>
                                                                                            @else
                                                                                                @switch($content['estadoActualG'])
                                                                                                    @case(1)
                                                                                                        {{__('Autorizado')}}
                                                                                                        @break
                                                                                                    @case(2)
                                                                                                        {{__('Rechazado')}}
                                                                                                        @break
                                                                                                @endswitch
                                                                                            @endif
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="form-group row motivorechazo" id="motivorechazo_{{$value['applications_id']}}_{{$content['fus_configuracion_autorizaciones_id']}}_autorizador">
                                                                                        <div class="col-md-12">
                                                                                            Motivo de rechazo
                                                                                        </div>
                                                                                        <div class="col-md-12">
                                                                                            @if($content['estadoActualG'] == 0 && $content['estado_autorizacion'] == 0)
                                                                                            <textarea class="form-control" name="autorizacion[{{$value['applications_id']}}][{{str_replace(',', '', str_replace(' ', '', $content['rol_mod_rep_formateado']))}}][{{$content['idrelAut']}}][motivorechazo]" id="txtMotivorechazo_{{$value['applications_id']}}_{{$value['fus_configuracion_autorizaciones_id']}}_autorizador"></textarea>
                                                                                            @else
                                                                                                @switch($content['estadoActualG'])
                                                                                                    @case(2)
                                                                                                        {{__('Rechazado')}}
                                                                                                        @break
                                                                                                @endswitch
                                                                                            @endif
                                                                                        </div>
                                                                                    </div>
                                                                                @endif
                                                                            @else
                                                                                <div class="form-group row">
                                                                                    <div class="col-md-6">
                                                                                        {{$content['rol_mod_rep_formateado']}}
                                                                                    </div>
                                                                                    <div class="col-md-6">
                                                                                        {{__('Rechazado')}}
                                                                                    </div>
                                                                                </div>
                                                                            @endif
                                                                        @endforeach
                                                                        @endif

                                                                        @if(isset($objetosAutorizar['autorizadores'][$value['applications_id']]['Intermex']))
                                                                        <div class="form-group row">
                                                                            <div class="col-md-12"><strong>INTERMEX</strong></div>
                                                                        </div>
                                                                        @foreach($objetosAutorizar['autorizadores'][$value['applications_id']]['Intermex'] AS $row => $content)
                                                                            @if(
                                                                                $checks['calculocompleto'][$content['rol_mod_rep']]['rechazados_mesas'] == 0
                                                                            )
                                                                                @if($content['applications_id'] == $value['applications_id'])
                                                                                    <div class="form-group row">
                                                                                        <div class="col-md-6">
                                                                                            {{$content['rol_mod_rep_formateado']}}
                                                                                        </div>
                                                                                        <div class="col-md-6">
                                                                                            @if($content['estadoActualG'] == 0 && $content['estado_autorizacion'] == 0)
                                                                                            <select class="form-control autorizacion" data-tipo="autorizador" data-idapp="{{$value['applications_id']}}" data-rolmodrep="{{$content['fus_configuracion_autorizaciones_id']}}" name="autorizacion[{{$value['applications_id']}}][{{str_replace(',', '', str_replace(' ', '', $content['rol_mod_rep_formateado']))}}][{{$content['idrelAut']}}][accion]">
                                                                                                <option value="0">Seleccione...</option>
                                                                                                <option value="1">Autorizar</option>
                                                                                                <option value="2">Rechazar</option>
                                                                                            </select>
                                                                                            @else
                                                                                                @switch($content['estadoActualG'])
                                                                                                    @case(1)
                                                                                                        {{__('Autorizado')}}
                                                                                                        @break
                                                                                                    @case(2)
                                                                                                        {{__('Rechazado')}}
                                                                                                        @break
                                                                                                @endswitch
                                                                                            @endif
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="form-group row motivorechazo" id="motivorechazo_{{$value['applications_id']}}_{{$content['fus_configuracion_autorizaciones_id']}}_autorizador">
                                                                                        <div class="col-md-12">
                                                                                            Motivo de rechazo
                                                                                        </div>
                                                                                        <div class="col-md-12">
                                                                                            @if($content['estadoActualG'] == 0 && $content['estado_autorizacion'] == 0)
                                                                                            <textarea class="form-control" name="autorizacion[{{$value['applications_id']}}][{{str_replace(',', '', str_replace(' ', '', $content['rol_mod_rep_formateado']))}}][{{$content['idrelAut']}}][motivorechazo]" id="txtMotivorechazo_{{$value['applications_id']}}_{{$value['fus_configuracion_autorizaciones_id']}}_autorizador"></textarea>
                                                                                            @else
                                                                                                @switch($content['estadoActualG'])
                                                                                                    @case(2)
                                                                                                        {{__('Rechazado')}}
                                                                                                        @break
                                                                                                @endswitch
                                                                                            @endif
                                                                                        </div>
                                                                                    </div>
                                                                                @endif
                                                                            @else
                                                                                <div class="form-group row">
                                                                                    <div class="col-md-6">
                                                                                        {{$content['rol_mod_rep_formateado']}}
                                                                                    </div>
                                                                                    <div class="col-md-6">
                                                                                        {{__('Rechazado')}}
                                                                                    </div>
                                                                                </div>
                                                                            @endif
                                                                        @endforeach
                                                                        @endif
                                                                        @break
                                                                    @default
                                                                        @foreach($objetosAutorizar['autorizadores'][$value['applications_id']] AS $row => $content)
                                                                            @if(
                                                                                $checks['calculocompleto'][$content['rol_mod_rep']]['rechazados_mesas'] == 0
                                                                            )
                                                                                @if($content['applications_id'] == $value['applications_id'])
                                                                                    <div class="form-group row">
                                                                                        <div class="col-md-6">
                                                                                            {{$content['rol_mod_rep_formateado']}}
                                                                                        </div>
                                                                                        <div class="col-md-6">
                                                                                            @if($content['estadoActualG'] == 0 && $content['estado_autorizacion'] == 0)
                                                                                            <select class="form-control autorizacion" data-tipo="autorizador" data-idapp="{{$value['applications_id']}}" data-rolmodrep="{{$content['fus_configuracion_autorizaciones_id']}}" name="autorizacion[{{$value['applications_id']}}][{{str_replace(',', '', str_replace(' ', '', $content['rol_mod_rep_formateado']))}}][{{$content['idrelAut']}}][accion]">
                                                                                                <option value="0">Seleccione...</option>
                                                                                                <option value="1">Autorizar</option>
                                                                                                <option value="2">Rechazar</option>
                                                                                            </select>
                                                                                            @else
                                                                                                @switch($content['estadoActualG'])
                                                                                                    @case(1)
                                                                                                        {{__('Autorizado')}}
                                                                                                        @break
                                                                                                    @case(2)
                                                                                                        {{__('Rechazado')}}
                                                                                                        @break
                                                                                                @endswitch
                                                                                            @endif
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="form-group row motivorechazo" id="motivorechazo_{{$value['applications_id']}}_{{$content['fus_configuracion_autorizaciones_id']}}_autorizador">
                                                                                        <div class="col-md-12">
                                                                                            Motivo de rechazo
                                                                                        </div>
                                                                                        <div class="col-md-12">
                                                                                            @if($content['estadoActualG'] == 0 && $content['estado_autorizacion'] == 0)
                                                                                            <textarea class="form-control" name="autorizacion[{{$value['applications_id']}}][{{str_replace(',', '', str_replace(' ', '', $content['rol_mod_rep_formateado']))}}][{{$content['idrelAut']}}][motivorechazo]" id="txtMotivorechazo_{{$value['applications_id']}}_{{$value['fus_configuracion_autorizaciones_id']}}_autorizador"></textarea>
                                                                                            @else
                                                                                                @switch($content['estadoActualG'])
                                                                                                    @case(2)
                                                                                                        {{__('Rechazado')}}
                                                                                                        @break
                                                                                                @endswitch
                                                                                            @endif
                                                                                        </div>
                                                                                    </div>
                                                                                @endif
                                                                            @else
                                                                                <div class="form-group row">
                                                                                    <div class="col-md-6">
                                                                                        {{$content['rol_mod_rep_formateado']}}
                                                                                    </div>
                                                                                    <div class="col-md-6">
                                                                                        {{__('Rechazado')}}
                                                                                    </div>
                                                                                </div>
                                                                            @endif
                                                                        @endforeach
                                                                        @break
                                                                @endswitch
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endif
                                            @if(
                                                $checks['mesas'][$value['applications_id']] >= 1 && 
                                                $checks['autorizadores'][$value['applications_id']] >= 1
                                            )
                                                @if(
                                                    isset($objetosAutorizar['ratificadores'][$value['applications_id']]) && 
                                                    isset($objetosAutorizar['ratificadores'][$value['applications_id']])
                                                )
                                                    <div class="col-md-{{$colmd}}">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <strong>Autorización como Ratificador</strong>
                                                            </div>
                                                            <div class="col-md-12">
                                                                @if(isset($objetosAutorizar['ratificadores']))
                                                                    @switch($value['applications_id'])
                                                                        @case(20)
                                                                        @case(16)
                                                                        @case(64)
                                                                            @if(isset($objetosAutorizar['ratificadores'][$value['applications_id']]['Prod']))
                                                                            <div class="form-group row">
                                                                                <div class="col-md-12"><strong>PROD</strong></div>
                                                                            </div>
                                                                            @foreach($objetosAutorizar['ratificadores'][$value['applications_id']]['Prod'] AS $row => $content)
                                                                                @if(
                                                                                    $checks['calculocompleto'][$content['rol_mod_rep']]['rechazados_mesas'] == 0 &&
                                                                                    $checks['calculocompleto'][$content['rol_mod_rep']]['rechazados_autorizadores'] == 0
                                                                                )
                                                                                    @if($content['applications_id'] == $value['applications_id'])
                                                                                        <div class="form-group row">
                                                                                            <div class="col-md-6">
                                                                                                {{$content['rol_mod_rep_formateado']}}
                                                                                            </div>
                                                                                            <div class="col-md-6">
                                                                                                @if($content['estadoActualG'] == 0 && $content['estado_autorizacion'] == 0)
                                                                                                <select class="form-control autorizacion" data-tipo="ratificador" data-idapp="{{$value['applications_id']}}" data-rolmodrep="{{$content['fus_configuracion_autorizaciones_id']}}" name="autorizacion[{{$value['applications_id']}}][{{str_replace(',', '', str_replace(' ', '', $content['rol_mod_rep_formateado']))}}][{{$content['idrelAut']}}][accion]">
                                                                                                    <option value="0">Seleccione...</option>
                                                                                                    <option value="1">Autorizar</option>
                                                                                                    <option value="2">Rechazar</option>
                                                                                                </select>
                                                                                                @else
                                                                                                    @switch($content['estadoActualG'])
                                                                                                        @case(1)
                                                                                                            {{__('Autorizado')}}
                                                                                                            @break
                                                                                                        @case(2)
                                                                                                            {{__('Rechazado')}}
                                                                                                            @break
                                                                                                    @endswitch
                                                                                                @endif
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="form-group row motivorechazo" id="motivorechazo_{{$value['applications_id']}}_{{$content['fus_configuracion_autorizaciones_id']}}_ratificador">
                                                                                            <div class="col-md-12">
                                                                                                Motivo de rechazo
                                                                                            </div>
                                                                                            <div class="col-md-12">
                                                                                                @if($content['estadoActualG'] == 0 && $content['estado_autorizacion'] == 0)
                                                                                                <textarea class="form-control" name="autorizacion[{{$value['applications_id']}}][{{str_replace(',', '', str_replace(' ', '', $content['rol_mod_rep_formateado']))}}][{{$content['idrelAut']}}][motivorechazo]" id="txtMotivorechazo_{{$value['applications_id']}}_{{$value['fus_configuracion_autorizaciones_id']}}_ratificador"></textarea>
                                                                                                @else
                                                                                                    @switch($content['estadoActualG'])
                                                                                                        @case(2)
                                                                                                            {{__('Rechazado')}}
                                                                                                            @break
                                                                                                    @endswitch
                                                                                                @endif
                                                                                            </div>
                                                                                        </div>
                                                                                    @endif
                                                                                @else
                                                                                    <div class="form-group row">
                                                                                        <div class="col-md-6">
                                                                                            {{$content['rol_mod_rep_formateado']}}
                                                                                        </div>
                                                                                        <div class="col-md-6">
                                                                                            {{__('Rechazado')}}
                                                                                        </div>
                                                                                    </div>
                                                                                @endif
                                                                            @endforeach
                                                                            @endif
                                                                            @if(isset($objetosAutorizar['ratificadores'][$value['applications_id']]['Intermex']))
                                                                            <div class="form-group row">
                                                                                <div class="col-md-12"><strong>INTERMEX</strong></div>
                                                                            </div>
                                                                            @foreach($objetosAutorizar['ratificadores'][$value['applications_id']]['Intermex'] AS $row => $content)
                                                                                @if(
                                                                                    $checks['calculocompleto'][$content['rol_mod_rep']]['rechazados_mesas'] == 0 &&
                                                                                    $checks['calculocompleto'][$content['rol_mod_rep']]['rechazados_autorizadores'] == 0
                                                                                )
                                                                                    @if($content['applications_id'] == $value['applications_id'])
                                                                                        <div class="form-group row">
                                                                                            <div class="col-md-6">
                                                                                                {{$content['rol_mod_rep_formateado']}}
                                                                                            </div>
                                                                                            <div class="col-md-6">
                                                                                                @if($content['estadoActualG'] == 0 && $content['estado_autorizacion'] == 0)
                                                                                                <select class="form-control autorizacion" data-tipo="ratificador" data-idapp="{{$value['applications_id']}}" data-rolmodrep="{{$content['fus_configuracion_autorizaciones_id']}}" name="autorizacion[{{$value['applications_id']}}][{{str_replace(',', '', str_replace(' ', '', $content['rol_mod_rep_formateado']))}}][{{$content['idrelAut']}}][accion]">
                                                                                                    <option value="0">Seleccione...</option>
                                                                                                    <option value="1">Autorizar</option>
                                                                                                    <option value="2">Rechazar</option>
                                                                                                </select>
                                                                                                @else
                                                                                                    @switch($content['estadoActualG'])
                                                                                                        @case(1)
                                                                                                            {{__('Autorizado')}}
                                                                                                            @break
                                                                                                        @case(2)
                                                                                                            {{__('Rechazado')}}
                                                                                                            @break
                                                                                                    @endswitch
                                                                                                @endif
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="form-group row motivorechazo" id="motivorechazo_{{$value['applications_id']}}_{{$content['fus_configuracion_autorizaciones_id']}}_ratificador">
                                                                                            <div class="col-md-12">
                                                                                                Motivo de rechazo
                                                                                            </div>
                                                                                            <div class="col-md-12">
                                                                                                @if($content['estadoActualG'] == 0 && $content['estado_autorizacion'] == 0)
                                                                                                <textarea class="form-control" name="autorizacion[{{$value['applications_id']}}][{{str_replace(',', '', str_replace(' ', '', $content['rol_mod_rep_formateado']))}}][{{$content['idrelAut']}}][motivorechazo]" id="txtMotivorechazo_{{$value['applications_id']}}_{{$value['fus_configuracion_autorizaciones_id']}}_ratificador"></textarea>
                                                                                                @else
                                                                                                    @switch($content['estadoActualG'])
                                                                                                        @case(2)
                                                                                                            {{__('Rechazado')}}
                                                                                                            @break
                                                                                                    @endswitch
                                                                                                @endif
                                                                                            </div>
                                                                                        </div>
                                                                                    @endif
                                                                                @else
                                                                                    <div class="form-group row">
                                                                                        <div class="col-md-6">
                                                                                            {{$content['rol_mod_rep_formateado']}}
                                                                                        </div>
                                                                                        <div class="col-md-6">
                                                                                            {{__('Rechazado')}}
                                                                                        </div>
                                                                                    </div>
                                                                                @endif
                                                                            @endforeach
                                                                            @endif
                                                                            @break
                                                                        @default
                                                                            @foreach($objetosAutorizar['ratificadores'][$value['applications_id']] AS $row => $content)
                                                                                @if(
                                                                                    $checks['calculocompleto'][$content['rol_mod_rep']]['rechazados_mesas'] == 0 &&
                                                                                    $checks['calculocompleto'][$content['rol_mod_rep']]['rechazados_autorizadores'] == 0
                                                                                )
                                                                                    @if($content['applications_id'] == $value['applications_id'])
                                                                                        <div class="form-group row">
                                                                                            <div class="col-md-6">
                                                                                                {{$content['rol_mod_rep_formateado']}}
                                                                                            </div>
                                                                                            <div class="col-md-6">
                                                                                                @if($content['estadoActualG'] == 0 && $content['estado_autorizacion'] == 0)
                                                                                                <select class="form-control autorizacion" data-tipo="ratificador" data-idapp="{{$value['applications_id']}}" data-rolmodrep="{{$content['fus_configuracion_autorizaciones_id']}}" name="autorizacion[{{$value['applications_id']}}][{{str_replace(',', '', str_replace(' ', '', $content['rol_mod_rep_formateado']))}}][{{$content['idrelAut']}}][accion]">
                                                                                                    <option value="0">Seleccione...</option>
                                                                                                    <option value="1">Autorizar</option>
                                                                                                    <option value="2">Rechazar</option>
                                                                                                </select>
                                                                                                @else
                                                                                                    @switch($content['estadoActualG'])
                                                                                                        @case(1)
                                                                                                            {{__('Autorizado')}}
                                                                                                            @break
                                                                                                        @case(2)
                                                                                                            {{__('Rechazado')}}
                                                                                                            @break
                                                                                                    @endswitch
                                                                                                @endif
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="form-group row motivorechazo" id="motivorechazo_{{$value['applications_id']}}_{{$content['fus_configuracion_autorizaciones_id']}}_ratificador">
                                                                                            <div class="col-md-12">
                                                                                                Motivo de rechazo
                                                                                            </div>
                                                                                            <div class="col-md-12">
                                                                                                @if($content['estadoActualG'] == 0 && $content['estado_autorizacion'] == 0)
                                                                                                <textarea class="form-control" name="autorizacion[{{$value['applications_id']}}][{{str_replace(',', '', str_replace(' ', '', $content['rol_mod_rep_formateado']))}}][{{$content['idrelAut']}}][motivorechazo]" id="txtMotivorechazo_{{$value['applications_id']}}_{{$value['fus_configuracion_autorizaciones_id']}}_ratificador"></textarea>
                                                                                                @else
                                                                                                    @switch($content['estadoActualG'])
                                                                                                        @case(2)
                                                                                                            {{__('Rechazado')}}
                                                                                                            @break
                                                                                                    @endswitch
                                                                                                @endif
                                                                                            </div>
                                                                                        </div>
                                                                                    @endif
                                                                                @else
                                                                                    <div class="form-group row">
                                                                                        <div class="col-md-6">
                                                                                            {{$content['rol_mod_rep_formateado']}}
                                                                                        </div>
                                                                                        <div class="col-md-6">
                                                                                            {{__('Rechazado')}}
                                                                                        </div>
                                                                                    </div>
                                                                                @endif
                                                                            @endforeach
                                                                            @break
                                                                    @endswitch
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-12 text-right">
                                    <a class="btn btn-warning" style="color:#FFFFFF;" href="{{ route('listafusesporautorizar') }}">Regresar</a>
                                    <input type="submit" id="btn-gdr" class="btn btn-primary" value="Guardar" />
                                </div>
                            </div>
                        </div>  
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endsection
    @push('scripts')
    <!-- Jquery Validate -->
    <script src="{{ asset('js/jquery_validate/jquery.validate.js') }}"></script>
    <script>
        $.validator.messages.required = 'El campo es requerido.';

        jQuery.validator.addClassRules({
            'campo-requerido': {
                required: true
            }
        });
        
        $('#form-revisar').validate({
            ignore: "",
            submitHandler: function(form) {
                mostrarLoading();
                form.preventDefault()
                setTimeout($(form).submit(), 500);
            }
        });

        $(document).ready(function() {
            $(document).on('click', '.btn-detalles', function() {
                var detalles = $(this).attr('data-detalles');
                var nombre_app = $(this).attr('data-app');        

                detalles = JSON.parse(detalles);
                var htmlContent = '<div class="container">';
                $.each( detalles, function( key, value ) {
                    $.each( value, function( k, v ) {
                        var valorField;
                        switch (v.valor) {
                            case 'a':
                                valorField = 'Alta';
                                break;
                            case 'b':
                                valorField = 'Baja';
                                break;
                            case 'c':
                                valorField = 'Cambio';
                                break;

                            case 'on':
                                valorField = '<i class="fas fa-check"></i>';
                                break;
                            default:
                                valorField = v.valor;
                                break;
                        }

                        if(k != 'autorizador_unico' && k != 'path_anexos' && valorField != null) {
                            htmlContent += '<div class="row"><div class="col-lg-3 text-left" style="font-weight:bold !important;">'+v.etiqueta+' </div><div class="col-lg-1 text-left"></div><div class="col-lg-8 text-left">'+valorField+'</div></div>';
                        }
                    });
                });
                htmlContent += '</div>';

                swal.fire({
                    title: 'Detalles',
                    // text: "¿Esta seguro de querer realizar la operación?",
                    html: htmlContent,
                    type: 'info',
                })
            });

            $('.autorizacion').change(function() {
                var idapp = $(this).data('idapp');
                var rolmodrep = $(this).data('rolmodrep');
                var tipoautorizador = $(this).data('tipo');
                var idDivTextarea = '#motivorechazo_'+idapp+'_'+rolmodrep+'_'+tipoautorizador;
                var idTextarea = '#txtMotivorechazo_'+idapp+'_'+rolmodrep+'_'+tipoautorizador;
                var textDeLaSeleccion = $('option:selected', this).text();
                if(textDeLaSeleccion == "Rechazar") {
                    $(idDivTextarea).css('display', 'block');
                    $(idTextarea).prop('required',true);
                } else if(textDeLaSeleccion == "Autorizar") {
                    $(idTextarea).prop('required',false);
                    $(idDivTextarea).css('display', 'none');
                } else {
                    $(idTextarea).prop('required',false);
                    $(idDivTextarea).css('display', 'none');
                }
            });
        });
        $(document).on("change",".appselect", function(){
            select = $(this).attr('data-type');
            var app = document.getElementsByClassName("appselect").length;
            var claveArch = select.split('_');
            var archivo = 0;
            if (claveArch[1] == 0) {
                claveArch[1] = "";
                archivo = $("#archivo"+claveArch[1]).val();
            }
            if (app == 1 && archivo == ""){
                var selectapp = $(this).val();
                if (selectapp == 0){
                    var element = document.getElementById("archivo"+claveArch[1]);
                    element.classList.remove("campo-requerido");
                    var element1 = document.getElementById("id_app_fus"+claveArch[1]);
                    element1.classList.remove("campo-requerido");
                }else{
                    claveArch[1] = "";
                    var element = document.getElementById("archivo"+claveArch[1]);
                    element.classList.add("campo-requerido");
                    var element1 = document.getElementById("id_app_fus"+claveArch[1]);
                    element1.classList.add("campo-requerido");
                }
            }
        });
        $(document).on("change", ".car-arch", function() {
            var fileName = this.files.length;
            select = $(this).attr('data-type');
            var claveArch = select.split('_');
            var archivo = document.getElementsByClassName("appselect").length;
            if (claveArch[1] == 0) {
                claveArch[1] = "";
                app = $("#id_app_fus"+claveArch[1]).val();
            }
            if (archivo == 1 && app == "") {
                if (fileName == 0 ) {
                    var element = document.getElementById("id_app_fus"+claveArch[1]);
                    element.classList.remove("campo-requerido");
                    var element1 = document.getElementById("archivo"+claveArch[1]);
                    element1.classList.remove("campo-requerido");
                }else{
                    var element = document.getElementById("id_app_fus"+claveArch[1]);
                    element.classList.add("campo-requerido");
                    var element1 = document.getElementById("archivo"+claveArch[1]);
                    element1.classList.add("campo-requerido");
                }
            }
            if (fileName != 0){
                var size = this.files[0].size;
                if (size < 4000000){
                    if (claveArch[1] == 0) {
                        var element = document.getElementById("id_app_fus"+claveArch[1]);
                        element.classList.add("campo-requerido");
                    }
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Advertencia',
                        text: 'El documento es excede el tamaño permitido (4 megas)'
                    })
                    $(this).val('');
                }
            }
        });
        var id ="";
        $('#add_field').on('click',function(e) {
            // todos los campos .form-control en #campos
            archivo = $('#archivo'+id).val();
            app = $('#id_app_fus'+id).val();

            valido = true;
            if(archivo == '' || app == '') {
                valido = false;
            }
            
            if (valido) {  
                if (id == "") {
                    id = 0;
                }
                id = id + 1;
                e.preventDefault();     //prevenir novos clicks
                    $('#documento').append(
                        '<div class ="form-group row">'+
                            '<div class="col-md-5">'+
                                '<input type="file" class="form-control car-arch campo-requerido" id="archivo'+id+'" name="archivo['+id+'][file]" data-type="doc_'+id+'">'+
                            '</div>'+
                            '<div class="col-md-5">'+
                                '<select class="form-control appselect campo-requerido" id="id_app_fus'+id+'" name="archivo['+id+'][app]" data-type="app_'+id+'">'+
                                    '<option value="">Selecciona una aplicación</option>'+
                                    @foreach($apps AS $keys)
                                        '<option value="{{ $keys["applications_id"] }}">{{ $keys["nombre_app"] }}</option>'+
                                    @endforeach
                                '</select>'+
                            '</div>'+
                            '<button type="button" class="btn btn-danger remover_campo" id="remover_campo">Remover</button>'+
                        '</div>'
                    );                       
            } else {
                swal(
                    'Advertencia',
                    'Los campos del anexo son obligatorios.',
                    'warning'
                )
            }
        });

        // Remover div anterior
        $('#documento').on("click",".remover_campo",function(e) {
            e.preventDefault();
            $(this).parent('div').remove();
        });
    </script>
    @endpush
@else
    @section('content')
    <div class="container">
        <div class="card row">
            <div class="card-header">
                ADVERTENCIA <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <p>
                            Este FUS no puede ser visualizado ya que no se encuentra asignado a él.
                        </p>
                    </div>
                    <div class="col-lg-12">
                        <a class="btn btn-warning btn-block" style="color:#FFFFFF;" href="{{ route('listafusesporautorizar') }}">Regresar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection
@endif