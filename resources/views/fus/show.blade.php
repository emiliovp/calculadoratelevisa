<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- Fontawesome -->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css" integrity="sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz" crossorigin="anonymous">
        <!-- Jquery -->
        <script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
        <!-- Sweet alert 2 -->
        <script src="{{ asset('js/sweetalert2/dist/sweetalert2.min.js') }}"></script>
        <link rel="stylesheet" href="{{ asset('js/sweetalert2/dist/sweetalert2.min.css') }}">
        <!-- Bootstrap css y js -->
        <link rel="stylesheet" href="{{ asset('js/fusshow/fusshow_bootstrap.min.css') }}" crossorigin="anonymous">
        <script src="{{ asset('js/fusshow/fusshow_bootstrap.min.js') }}" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="{{ asset('css/show.css') }}">
        <link rel="stylesheet" href="{{ asset('css/loading.css') }}">
        <title>FUS Electrónico</title>
    </head>
    <body>
        <div id="app">
            <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
                <div class="container">
                    <a class="navbar-brand" href="{{ url('/') }}">
                        {{ config('app.name', 'Fus Electrónico') }}
                    </a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <!-- Left Side Of Navbar -->
                        <ul class="navbar-nav mr-auto">

                        </ul>

                        <!-- Right Side Of Navbar -->
                        <ul class="navbar-nav ml-auto">
                            <!-- Authentication Links -->
                            @guest
                                <!-- <li class="nav-item">
                                    <a class="nav-link" href="https://portal_pcb.televisa.net/televisa_triada/pes2018/public/index.php">{{ __('Home') }}</a>
                                </li> -->
                                <!-- <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li> -->
                                @if (Route::has('register'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                    </li>
                                @endif
                            @else
                                <li class="nav-item dropdown">
                                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        {{ Auth::user()->name }} <span class="caret"></span>
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                            document.getElementById('logout-form').submit();">
                                            {{ __('Logout') }}
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                    </div>
                                </li>
                            @endguest
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
        <main class="py-4">
        @php
            $noEmployeeLogin = (string)Auth::user()->noEmployee;
            $pos = strpos($fus['no_empleados_auths_apps'], $noEmployeeLogin);
            $pos2 = strpos($fus['no_empleados_auths_otros'], $noEmployeeLogin);
        @endphp
        <!-- Solo podrán verlo los empleados realacionados al fus, ya sea un jefe, autorizador, autorizadores configurados -->
        @if(
            $pos !== false ||
            $pos2 !== false ||
            $noEmployeeLogin == $fus['no_empleado'] ||
            $noEmployeeLogin == $fus['no_empleado_jefe'] ||
            $noEmployeeLogin == $fus['no_empleado_aut'] ||
            Auth::user()->useradmin == 1
        )
            
        <!-- En caso de visualizar vía correo para autorización -->
        @if($tipo == 1)
            @php
                $showCuestionAuto = 1;
            @endphp
            @switch($jefeOAut)
                @case(1)
                    @if($fus['autorizo_jefe'] != 0 && $fus['autorizo_jefe'] != 0)
                        @php
                            $showCuestionAuto = 0;
                        @endphp
                    @endif
                    @break
                @case(2)
                    @if($fus['aut_autorizo'] != 0 && $fus['autorizo_jefe'] != 0)
                        @php
                            $showCuestionAuto = 0;
                        @endphp
                    @endif
                    @break
                @case(3)
                    @if($idRelConf->estado_autorizacion != 0)
                        @php
                            $showCuestionAuto = 0;
                        @endphp
                    @endif
                    @break
                @case(4)
                    @if($idRelConf->estado != 0)
                        @php
                            $showCuestionAuto = 0;
                        @endphp
                    @endif
                    @break
            @endswitch
            @if($showCuestionAuto == 1)
                @if($yaAutorizado == null || $yaAutorizado == 0)
                    @if(
                        $noEmployeeLogin == $fus['no_empleado_jefe'] ||
                        $noEmployeeLogin == $fus['no_empleado_aut']
                    )
                        <div class="container mb-3">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <strong>Autorización del FUS</strong>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-lg-12 text-justify">
                                                    <p>
                                                        A continuación se muestra la solicitud del FUS, se le pide de favor revisar de forma detalla el FUS y autorizar o rechazar la solicitud.
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-12 text-right">
                                                    <button class="btn btn-danger" id="rechazofus">Rechazar</button>
                                                    <button class="btn btn-primary" id="autoizarfus">Autorizar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @else
                <div class="container mb-3">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    ADVERTENCIA <i class="fas fa-exclamation-triangle"></i>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-12 text-justify">
                                            <p>
                                                La autorización de este FUS ya ha sido atendida por alguien más.
                                            </p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 text-right">
                                            <a class="btn btn-warning btn-block" style="color:#FFFFFF;" href="{{ route('fus_lista') }}">Regresar</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            @endif
        @endif
        <!-- Fin -->
        <!-- Encabezado -->
        <div class="container">
            @if(!isset($tipo) || $tipo == 0)
                <div class="row mt-3 mb-3"><div class="col-lg-12"><a class="btn btn-warning btn-block" style="color:#FFFFFF;" href="{{ route('fus_lista') }}">Regresar</a></div></div>
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
        <!-- Fin Encabezado -->
        <!-- Cuerpo -->
        <div class="container">
        @switch($fus['tipo_fus'])
            @case(0)
                @if($fus['fus_cuerpo'] != null)
                <div class="row">
                    @php
                        $count = 1;
                    @endphp
                    @foreach($fus['fus_cuerpo'] as $key => $value)
                        @php
                            $colLength = 6;
                        @endphp
                        @if($count == 0)
                            @php
                            $colLength = 4;
                            @endphp
                        @endif

                        <div class="col-lg-12">
                            <div class="card mb-3">
                                <div class="card-header">
                                    <strong>{{ strtoupper($key) }}</strong>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                    @foreach($value as $k => $v)
                                        @if(isset($v['etiqueta']) && $v['etiqueta'] == "Tipo de Solicitud")
                                            @switch($v['valor'])
                                                @case('a')
                                                    @php
                                                    $v['valor'] = 'Alta';
                                                    @endphp
                                                    @break
                                                @case('b')
                                                    @php
                                                    $v['valor'] = 'Baja';
                                                    @endphp
                                                    @break
                                                @case('c')
                                                    @php
                                                    $v['valor'] = 'Cambio';
                                                    @endphp
                                                    @break
                                            @endswitch
                                        @endif
                                        
                                        @if(isset($v['etiqueta']) && $v['etiqueta'] != "")
                                            <div class="col-lg-{{ $colLength }} divselectmultiple">
                                                <strong>{{ $v['etiqueta'] }}:</strong> 
                                                @if($v['valor'] == 'on')
                                                    <i class="fas fa-check"></i>
                                                @else
                                                    {{ $v['valor'] }}
                                                @endif
                                            </div>
                                        @endif
                                        @if($k == 'path_anexos')
                                            <!-- <div class="row"> -->
                                                <div class="col-lg-12 mt-3"><strong>Archivos Anexos</strong></div>
                                                @if(is_array($v) === true)
                                                    @foreach($v AS $key => $value)
                                                        @foreach($value AS $index => $val)
                                                        <div class="col-lg-12">
                                                            <a href="{{ url('/descargas/anexos') }}/{{$val}}">{{$val}}</a>
                                                        </div>
                                                        @endforeach
                                                    @endforeach
                                                @else
                                                    <div class="col-lg-12">
                                                        <a href="{{ url('/descargas/anexos') }}/{{$v}}">{{$v}}</a>
                                                    </div>
                                                @endif
                                            <!-- </div> -->
                                        @endif
                                    @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        @php
                            $count = $count+1;
                        @endphp
                    @endforeach
                </div>
                <!-- TRACK -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card mb-3">
                            <div class="card-header">
                                <label><strong>Track de Autorizaciones</strong></label>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-lg-3">
                                        @if($fus['ext_ficha'] != null)
                                            <strong>Responsable del nuevo usuario externo:</strong>
                                        @else
                                            <strong>Jefe del nuevo usuario:</strong>
                                        @endif
                                    </div>
                                    @switch($fus['autorizo_jefe'])
                                        @case(0)
                                            <div class="col-lg-3">Pendiente</div>  
                                            @break
                                        @case(1)
                                            <div class="col-lg-3">Autorizó</div>  
                                            @break
                                        @case(2)
                                            <div class="col-lg-3">Rechazó</div>  
                                            @break
                                    @endswitch
                                    @if($fus['no_empleado_aut'] != null)
                                        <div class="col-lg-3"><strong>Autorizador del FUS-e:</strong></div>
                                        @switch($fus['aut_autorizo'])
                                            @case(0)
                                                <div class="col-lg-3">Pendiente</div>  
                                                @break
                                            @case(1)
                                                <div class="col-lg-3">Autorizó</div>  
                                                @break
                                            @case(2)
                                                <div class="col-lg-3">Rechazó</div>  
                                                @break
                                        @endswitch
                                    @endif
                                </div> 
                                @if(count($trackApps) > 0)
                                <hr>
                                <div class="row mb-2">
                                    <div class="col-lg-12"><h3>Aplicaciones</h3></div>
                                </div>
                                <div class="row">
                                    @foreach($trackApps['calculocompleto'] AS $row => $value)
                                        @php
                                            $terminadoApp = array();
                                            $terminado = array();
                                        @endphp
                                        <div class="col-lg-12">
                                            <label><strong>{{$row}}</strong></label>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="row">
                                                <div class="col-lg-3"></div>
                                                <div class="col-lg-3"><b>Mesa de Control</b></div>
                                                <div class="col-lg-3"><b>Vo.Bo. Adicional</b></div>
                                                <div class="col-lg-3"><b>Ratificadores</b></div>
                                            </div>
                                            @foreach($value AS $fila)
                                                <div class="row mb-2">
                                                    <div class="col-lg-3">
                                                        {{$fila['rol_mod_rep']}}
                                                    </div>
                                                    <div class="col-lg-3">
                                                        @if(
                                                            $fila['autorizados_mesas'] > 0
                                                        )
                                                            @php
                                                                $terminado[$fila['rol_mod_rep']]['mesa'] = 1;
                                                            @endphp
                                                            Autorizado
                                                        @elseif($fila['rechazados_mesas'] > 0)
                                                            @php
                                                                $terminado[$fila['rol_mod_rep']]['mesa'] = 1;
                                                            @endphp
                                                            Rechazado
                                                        @else
                                                            @if($fila['conteo_mesas'] > 0)
                                                                @php
                                                                    $terminado[$fila['rol_mod_rep']]['mesa'] = "x";
                                                                @endphp
                                                                Pendiente
                                                            @else
                                                                @php
                                                                    $terminado[$fila['rol_mod_rep']]['mesa'] = null;
                                                                @endphp
                                                                ---
                                                            @endif
                                                        @endif
                                                    </div>
                                                    <div class="col-lg-3">
                                                        @if(
                                                            $fila['autorizados_autorizadores'] > 0
                                                        )
                                                            @php
                                                                $terminado[$fila['rol_mod_rep']]['autorizador'] = 1;
                                                            @endphp
                                                            Autorizado
                                                        @elseif($fila['rechazados_autorizadores'] > 0)
                                                            @php
                                                                $terminado[$fila['rol_mod_rep']]['autorizador'] = 1;
                                                            @endphp
                                                            Rechazado
                                                        @else
                                                            @if($fila['conteo_autorizadores'] > 0)
                                                                @php
                                                                    $terminado[$fila['rol_mod_rep']]['autorizador'] = "x";
                                                                @endphp
                                                                Pendiente
                                                            @else
                                                                @php
                                                                    $terminado[$fila['rol_mod_rep']]['autorizador'] = null;
                                                                @endphp
                                                                ---
                                                            @endif
                                                        @endif
                                                    </div>
                                                    <div class="col-lg-3">
                                                        @if(
                                                            $fila['autorizados_ratificadores'] > 0
                                                        )
                                                            @php
                                                                $terminado[$fila['rol_mod_rep']]['ratificador'] = 1;
                                                            @endphp
                                                            Autorizado
                                                        @elseif($fila['rechazados_ratificadores'] > 0)
                                                            @php
                                                                $terminado[$fila['rol_mod_rep']]['ratificador'] = 1;
                                                            @endphp
                                                            Rechazado
                                                        @else
                                                            @if($fila['conteo_ratificadores'] > 0)
                                                                @php
                                                                    $terminado[$fila['rol_mod_rep']]['ratificador'] = "x";
                                                                @endphp
                                                                Pendiente
                                                            @else
                                                                @php
                                                                    $terminado[$fila['rol_mod_rep']]['ratificador'] = null;
                                                                @endphp
                                                                ---
                                                            @endif
                                                        @endif
                                                    </div>
                                                </div>
                                                @if(
                                                    $terminado[$fila['rol_mod_rep']]['mesa'] == 1 &&
                                                    $terminado[$fila['rol_mod_rep']]['autorizador'] == 1 &&
                                                    $terminado[$fila['rol_mod_rep']]['ratificador'] == 1 ||

                                                    $terminado[$fila['rol_mod_rep']]['mesa'] == null &&
                                                    $terminado[$fila['rol_mod_rep']]['autorizador'] == 1 &&
                                                    $terminado[$fila['rol_mod_rep']]['ratificador'] == 1 ||

                                                    $terminado[$fila['rol_mod_rep']]['mesa'] == 1 &&
                                                    $terminado[$fila['rol_mod_rep']]['autorizador'] == null &&
                                                    $terminado[$fila['rol_mod_rep']]['ratificador'] == 1 ||

                                                    $terminado[$fila['rol_mod_rep']]['mesa'] == 1 &&
                                                    $terminado[$fila['rol_mod_rep']]['autorizador'] == 1 &&
                                                    $terminado[$fila['rol_mod_rep']]['ratificador'] == null ||

                                                    $terminado[$fila['rol_mod_rep']]['mesa'] == 1 &&
                                                    $terminado[$fila['rol_mod_rep']]['autorizador'] == null &&
                                                    $terminado[$fila['rol_mod_rep']]['ratificador'] == null ||

                                                    $terminado[$fila['rol_mod_rep']]['mesa'] == null &&
                                                    $terminado[$fila['rol_mod_rep']]['autorizador'] == 1 &&
                                                    $terminado[$fila['rol_mod_rep']]['ratificador'] == null ||

                                                    $terminado[$fila['rol_mod_rep']]['mesa'] == null &&
                                                    $terminado[$fila['rol_mod_rep']]['autorizador'] == null &&
                                                    $terminado[$fila['rol_mod_rep']]['ratificador'] == 1
                                                )
                                                    @php
                                                        $terminadoApp[$fila['rol_mod_rep']] = 1;
                                                    @endphp
                                                @else
                                                    @php
                                                        $terminadoApp[$fila['rol_mod_rep']] = 0;
                                                    @endphp
                                                @endif
                                            @endforeach
                                        </div>
                                        @if(
                                            isset($terminadoApp)
                                        )
                                            @php
                                                $total = count($terminadoApp);
                                                $obtenidos = 0;
                                            @endphp
                                            
                                            @foreach($terminadoApp AS $r => $e)
                                                @if($e == 1)
                                                    @php
                                                        $obtenidos = $obtenidos+1;
                                                    @endphp
                                                @endif
                                            @endforeach
                                            @if($obtenidos == $total)
                                                <div class="col-lg-12">
                                                    <b>Tu solicitud de FUS-e está siendo atendida, en cuanto se concluya serás notificado vía correo electrónico.</b>
                                                </div>
                                            @endif
                                        @endif
                                    @endforeach
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @else
                <div class="row">                   
                    <div class="col-lg-12">
                        <strong>¡¡¡Este FUS-e tuvo un problema al generarse y no se guardo la información de las aplicacones. Favor de generar un nuevo FUS-e!!!</strong>
                    </div>
                </div>
                <!-- FIN TRACK -->
                @endif
                @break
            @case(1)
                <div class="row">                   
                    <div class="col-lg-12">
                        <div class="card mb-3">
                            <div class="card-header">
                                <label><strong>Usuario de red</strong></label>
                            </div>
                            <div class = "card-body">
                                <div class="row">
                                @if($fus['fus_cuerpo']['movimiento'] != "")
                                    <div class="col-lg-3"><strong>{{ $fus['fus_cuerpo']['movimiento']['etiqueta'] }}:</strong></div>
                                    <div class="col-lg-3">{{ $fus['fus_cuerpo']['movimiento']['valor'] }}</div>
                                @endif
                                @if($fus['fus_cuerpo']['dominio'] != "")
                                    <div class="col-lg-3"><strong>{{ $fus['fus_cuerpo']['dominio']['etiqueta'] }}:</strong></div>
                                    <div class="col-lg-3">{{ $fus['fus_cuerpo']['dominio']['valor'] }}</div>  
                                @endif
                                </div> 
                            </div>
                            <div class="card-header">
                                <label><strong>Track de Autorizaciones</strong></label>
                            </div>
                            <div class = "card-body">
                                <div class="row">
                                    <div class="col-lg-3">
                                        @if($fus['ext_ficha'] != null)
                                            <strong>Responsable del nuevo usuario externo:</strong>
                                        @else
                                            <strong>Jefe del nuevo usuario:</strong>
                                        @endif
                                    </div>
                                    @switch($fus['autorizo_jefe'])
                                        @case(0)
                                            <div class="col-lg-3">Pendiente</div>  
                                            @break
                                        @case(1)
                                            <div class="col-lg-3">Autorizó</div>  
                                            @break
                                        @case(2)
                                            <div class="col-lg-3">Rechazó</div>  
                                            @break
                                    @endswitch
                                    @if($fus['no_empleado_aut'] != null)
                                        <div class="col-lg-3"><strong>Autorizador del FUS-e:</strong></div>
                                        @switch($fus['aut_autorizo'])
                                            @case(0)
                                                <div class="col-lg-3">Pendiente</div>  
                                                @break
                                            @case(1)
                                                <div class="col-lg-3">Autorizó</div>  
                                                @break
                                            @case(2)
                                                <div class="col-lg-3">Rechazó</div>  
                                                @break
                                        @endswitch
                                    @endif
                                </div> 
                            </div>
                        </div>
                    </div>
                </div>
                @break
            @case(2)
                <div class="row">                   
                    <div class="col-lg-12">
                        <div class="card mb-3">
                            <div class="card-header">
                                <label><strong>Correo</strong></label>
                            </div>
                            <div class = "card-body">
                                <div class="row">
                                @if(isset($fus['fus_cuerpo']['t_usu']) && $fus['fus_cuerpo']['t_usu'] != "")
                                    <div class="col-lg-3"><strong>{{ $fus['fus_cuerpo']['t_usu']['etiqueta'] }}:</strong></div>
                                    <div class="col-lg-3">{{ $fus['fus_cuerpo']['t_usu']['valor'] }}</div> 
                                @endif
                                @if(isset($fus['fus_cuerpo']['n_empleado_int']) && $fus['fus_cuerpo']['n_empleado_int'] != "")
                                    <div class="col-lg-3"><strong>{{ $fus['fus_cuerpo']['n_empleado_int']['etiqueta'] }}:</strong></div>
                                    <div class="col-lg-3">{{ $fus['fus_cuerpo']['n_empleado_int']['valor'] }}</div>  
                                @endif
                                </div> 
                                <div class="row">
                                @if(isset($fus['fus_cuerpo']['nombre_int']) && $fus['fus_cuerpo']['nombre_int'] != "")
                                    <div class="col-lg-3"><strong>{{ $fus['fus_cuerpo']['nombre_int']['etiqueta'] }}:</strong></div>
                                    <div class="col-lg-3">{{ $fus['fus_cuerpo']['nombre_int']['valor'] }}</div>  
                                @endif
                                @if(isset($fus['fus_cuerpo']['a_pat_int']) && $fus['fus_cuerpo']['a_pat_int'] != "")
                                    <div class="col-lg-3"><strong>{{ $fus['fus_cuerpo']['a_pat_int']['etiqueta'] }}:</strong></div>
                                    <div class="col-lg-3">{{ $fus['fus_cuerpo']['a_pat_int']['valor'] }}</div>  
                                @endif
                                </div> 
                                <div class="row">
                                @if(isset($fus['fus_cuerpo']['a_mat_int']) && $fus['fus_cuerpo']['a_mat_int'] != "")
                                    <div class="col-lg-3"><strong>{{ $fus['fus_cuerpo']['a_mat_int']['etiqueta'] }}:</strong></div>
                                    <div class="col-lg-3">{{ $fus['fus_cuerpo']['a_mat_int']['valor'] }}</div>
                                @endif
                                @if(isset($fus['fus_cuerpo']['empresa_int']) && $fus['fus_cuerpo']['empresa_int'] != "")
                                    <div class="col-lg-3"><strong>{{ $fus['fus_cuerpo']['empresa_int']['etiqueta'] }}:</strong></div>
                                    <div class="col-lg-3">{{ $fus['fus_cuerpo']['empresa_int']['valor'] }}</div>
                                @endif
                                </div> 
                                <div class="row">
                                @if(isset($fus['fus_cuerpo']['movimiento']) && $fus['fus_cuerpo']['movimiento'] != "")
                                    <div class="col-lg-3"><strong>{{ $fus['fus_cuerpo']['movimiento']['etiqueta'] }}:</strong></div>
                                    <div class="col-lg-3">{{ $fus['fus_cuerpo']['movimiento']['valor'] }}</div> 
                                @endif
                                @if(isset($fus['fus_cuerpo']['dominio']) && $fus['fus_cuerpo']['dominio'] != "")
                                    <div class="col-lg-3"><strong>{{ $fus['fus_cuerpo']['dominio']['etiqueta'] }}:</strong></div>
                                    <div class="col-lg-3">{{ $fus['fus_cuerpo']['dominio']['valor'] }}</div>  
                                @endif
                                </div> 
                                @if($fus['fus_cuerpo']['smtp'] != "")
                                <div class="row">
                                    <div class="col-lg-3"><strong>{{ $fus['fus_cuerpo']['smtp']['etiqueta'] }}:</strong></div>
                                    <div class="col-lg-3">{{ $fus['fus_cuerpo']['smtp']['valor'] }}</div>  
                                </div> 
                                @endif
                            </div>
                            <div class="card-header">
                                <label><strong>Track de Autorizaciones</strong></label>
                            </div>
                            <div class = "card-body">
                                <div class="row">
                                    <div class="col-lg-3">
                                        @if($fus['ext_ficha'] != null)
                                            <strong>Responsable del nuevo usuario externo:</strong>
                                        @else
                                            <strong>Jefe del nuevo usuario:</strong>
                                        @endif
                                    </div>
                                    @switch($fus['autorizo_jefe'])
                                        @case(0)
                                            <div class="col-lg-3">Pendiente</div>  
                                            @break
                                        @case(1)
                                            <div class="col-lg-3">Autorizó</div>  
                                            @break
                                        @case(2)
                                            <div class="col-lg-3">Rechazó</div>  
                                            @break
                                    @endswitch
                                    @if($fus['no_empleado_aut'] != null)
                                        <div class="col-lg-3"><strong>Autorizador del FUS-e:</strong></div>
                                        @switch($fus['aut_autorizo'])
                                            @case(0)
                                                <div class="col-lg-3">Pendiente</div>  
                                                @break
                                            @case(1)
                                                <div class="col-lg-3">Autorizó</div>  
                                                @break
                                            @case(2)
                                                <div class="col-lg-3">Rechazó</div>  
                                                @break
                                        @endswitch
                                    @endif
                                </div> 
                            </div>
                        </div>
                    </div>
                </div>
                @break
            @case(3)
                <div class="row">                   
                    <div class="col-lg-12">
                        <div class="card mb-3">
                            <div class="card-header">
                                <label><strong>Cuenta especial</strong></label>
                            </div>
                            <div class = "card-body">
                                <div class="row">
                                @if(isset($fus['fus_cuerpo']['movimiento']) && $fus['fus_cuerpo']['movimiento'] != "")
                                    <div class="col-lg-3"><strong>{{ $fus['fus_cuerpo']['movimiento']['etiqueta'] }}:</strong></div>
                                    <div class="col-lg-3">{{ $fus['fus_cuerpo']['movimiento']['valor'] }}</div> 
                                @endif
                                @if(isset($fus['fus_cuerpo']['n_cuenta']) && $fus['fus_cuerpo']['n_cuenta'] != "")
                                    <div class="col-lg-3"><strong>{{ $fus['fus_cuerpo']['tipo_sol']['etiqueta'] }}:</strong></div>
                                    <div class="col-lg-3">{{ $fus['fus_cuerpo']['tipo_sol']['valor'] }}</div>
                                @endif                              
                                </div> 
                                <div class="row">
                                @if(isset($fus['fus_cuerpo']['n_cuenta']) && $fus['fus_cuerpo']['n_cuenta'] != "")
                                    <div class="col-lg-3">
                                        <strong>{{ $fus['fus_cuerpo']['n_cuenta']['etiqueta'] }}:</strong> 
                                    </div>
                                    <div class="col-lg-3">
                                        {{ $fus['fus_cuerpo']['n_cuenta']['valor'] }}
                                    </div>
                                @endif
                                @if(isset($fus['fus_cuerpo']['f_vigencia']) &&  $fus['fus_cuerpo']['f_vigencia'] != "")
                                    <div class="col-lg-3"><strong>{{ $fus['fus_cuerpo']['nombre_cuenta']['etiqueta'] }}:</strong></div>
                                    <div class="col-lg-3">{{ $fus['fus_cuerpo']['nombre_cuenta']['valor'] }}</div>
                                @endif
                                </div>
                                <div class="row">
                                @if(isset($fus['fus_cuerpo']['f_vigencia']) &&  $fus['fus_cuerpo']['f_vigencia'] != "") 
                                    <div class="col-lg-3"><strong>{{ $fus['fus_cuerpo']['f_vigencia']['etiqueta'] }}:</strong></div>
                                    <div class="col-lg-3">{{ $fus['fus_cuerpo']['f_vigencia']['valor'] }}</div>
                                @endif
                                @if($fus['fus_cuerpo']['smtp'] != "")
                                    <div class="col-lg-3"><strong>{{ $fus['fus_cuerpo']['smtp']['etiqueta'] }}:</strong></div>
                                    <div class="col-lg-3">{{ $fus['fus_cuerpo']['smtp']['valor'] }}</div>   
                                @endif 
                                </div>
                                <div class="row">
                                @if(isset($fus['fus_cuerpo']['dominio']) && $fus['fus_cuerpo']['dominio'] != "")
                                    <div class="col-lg-3"><strong>{{ $fus['fus_cuerpo']['dominio']['etiqueta'] }}:</strong></div>
                                    <div class="col-lg-3">{{ $fus['fus_cuerpo']['dominio']['valor'] }}</div>  
                                @endif
                                @if(isset($fus['fus_cuerpo']['justificacion']) &&  $fus['fus_cuerpo']['justificacion'] != "")
                                    <div class="col-lg-3"><strong>{{ $fus['fus_cuerpo']['justificacion']['etiqueta'] }}:</strong> </div>
                                    <div class="col-lg-3">{{ $fus['fus_cuerpo']['justificacion']['valor'] }}</div>
                                @endif
                                </div>   
                            </div>
                            <div class="card-header">
                                <label><strong>Track de Autorizaciones</strong></label>
                            </div>
                            <div class = "card-body">
                                <div class="row">
                                    <div class="col-lg-3">
                                        @if($fus['ext_ficha'] != null)
                                            <strong>Responsable del nuevo usuario externo:</strong>
                                        @else
                                            <strong>Jefe del nuevo usuario:</strong>
                                        @endif
                                    </div>
                                    @switch($fus['autorizo_jefe'])
                                        @case(0)
                                            <div class="col-lg-3">Pendiente</div>  
                                            @break
                                        @case(1)
                                            <div class="col-lg-3">Autorizó</div>  
                                            @break
                                        @case(2)
                                            <div class="col-lg-3">Rechazó</div>  
                                            @break
                                    @endswitch
                                    @if($fus['no_empleado_aut'] != null)
                                        <div class="col-lg-3"><strong>Autorizador del FUS-e:</strong></div>
                                        @switch($fus['aut_autorizo'])
                                            @case(0)
                                                <div class="col-lg-3">Pendiente</div>  
                                                @break
                                            @case(1)
                                                <div class="col-lg-3">Autorizó</div>  
                                                @break
                                            @case(2)
                                                <div class="col-lg-3">Rechazó</div>  
                                                @break
                                        @endswitch
                                    @endif
                                </div> 
                            </div>
                        </div>
                    </div>
                </div>
                @break
        @case(4)
            <div class="row">                   
                <div class="col-lg-12">
                    <div class="card mb-3">
                        <div class="card-header">
                            <label><strong>Acceso a carpeta o directorio</strong></label>
                        </div>
                        <div class = "card-body">
                            <div class="row">
                                <div class="col-lg-3">
                                    <strong>{{ $fus['fus_cuerpo']['direc']['etiqueta'] }}:</strong>
                                </div>
                                <div class="col-lg-3">
                                    {{ $fus['fus_cuerpo']['direc']['valor'] }}
                                </div>
                                <div class="col-lg-3">
                                    <strong>{{ $fus['fus_cuerpo']['movimiento']['etiqueta'] }}:</strong>
                                </div>
                                <div class="col-lg-3">
                                    {{ $fus['fus_cuerpo']['movimiento']['valor'] }}
                                </div>
                            </div>
                        @foreach($fus['fus_cuerpo']['usuario']['valor'] as $value)
                            <div class="row">
                                <div class="col-lg-3">
                                    <strong>Usuario:</strong>
                                </div>
                                <div class="col-lg-3">
                                    @php
                                        print_r($value['nom_usu']);
                                    @endphp
                                </div>
                                <div class="col-lg-3">
                                    <strong>Permisos:</strong>
                                </div>
                                <div class="col-lg-3">
                                    @if(isset($value['lectura']))
                                        @php
                                            print_r($value['lectura']);
                                        @endphp
                                    @endif
                                     @if(isset($value['escritura']))
                                        @php
                                            print_r("y ".$value['escritura']);
                                        @endphp
                                    @endif
                                </div>
                            </div>
                        @endforeach
                        </div>
                    </div>
                </div>
            </div>
                @break
            @case(5)
                @switch($fus['fus_cuerpo']['so']['valor'])
                    @case(1)
                        @php
                        $fus['fus_cuerpo']['so']['valor'] = 'WINDOWS';
                        @endphp
                        @break
                    @case(2)
                        @php
                        $fus['fus_cuerpo']['so']['valor'] = 'MAC';
                        @endphp
                        @break
                    @case(3)
                        @php
                        $fus['fus_cuerpo']['so']['valor'] = 'LINUX';
                        @endphp
                        @break
                @endswitch
                @switch($fus['fus_cuerpo']['movimiento']['valor'])
                    @case(1)
                        @php
                        $fus['fus_cuerpo']['movimiento']['valor'] = 'ALTA';
                        @endphp
                        @break
                    @case(2)
                        @php
                        $fus['fus_cuerpo']['movimiento']['valor'] = 'CAMBIO';
                        @endphp
                        @break
                    @case(3)
                        @php
                        $fus['fus_cuerpo']['movimiento']['valor'] = 'BAJA';
                        @endphp
                        @break
                @endswitch
            <div class="row">                   
                <div class="col-lg-12">
                    <div class="card mb-3">
                        <div class="card-header">
                            <label><strong>Datos de acceso</strong></label>
                        </div>
                        <div class = "card-body">
                            <div class="row">
                                <div class="col-lg-3"><strong>{{ $fus['fus_cuerpo']['so']['etiqueta'] }}:</strong></div>
                                <div class="col-lg-3">{{ $fus['fus_cuerpo']['so']['valor'] }}</div>
                                <div class="col-lg-3"><strong>{{ $fus['fus_cuerpo']['movimiento']['etiqueta'] }}:</strong></div>
                                <div class="col-lg-3">{{ $fus['fus_cuerpo']['movimiento']['valor'] }}</div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3"><strong>{{ $fus['fus_cuerpo']['n_app']['etiqueta'] }}:</strong></div>
                                <div class="col-lg-3">{{ $fus['fus_cuerpo']['n_app']['valor'] }}</div>
                                <div class="col-lg-3"><strong>{{ $fus['fus_cuerpo']['servidor']['etiqueta'] }}:</strong></div>
                                <div class="col-lg-3">{{ $fus['fus_cuerpo']['servidor']['valor'] }}</div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3"><strong>{{ $fus['fus_cuerpo']['ip']['etiqueta'] }}:</strong></div>
                                <div class="col-lg-3">{{ $fus['fus_cuerpo']['ip']['valor'] }}</div>
                                <div class="col-lg-3"><strong>{{ $fus['fus_cuerpo']['justificacion']['etiqueta'] }}:</strong></div>
                                <div class="col-lg-3">{{ $fus['fus_cuerpo']['justificacion']['valor'] }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                @break
            @case(6)
            <div class="row">
                <div class="col-lg-12">
                    <div class="card mb-3">
                        <div class="card-header">
                            <label><strong>Datos de acceso</strong></label>
                        </div>
                        <div class = "card-body">
                            <div class = "row">
                                <div class="col-lg-3"><strong>{{ $fus['fus_cuerpo']['num_usu']['etiqueta'] }}:</strong></div>
                                <div class="col-lg-3">{{ $fus['fus_cuerpo']['num_usu']['valor'] }}</div>
                                <div class="col-lg-3"><strong>{{ $fus['fus_cuerpo']['mail']['etiqueta'] }}:</strong></div>
                                <div class="col-lg-3">{{ $fus['fus_cuerpo']['mail']['valor'] }}</div>
                            </div>
                            <div class = "row">
                                <div class="col-lg-3"><strong>{{ $fus['fus_cuerpo']['ext_usu_red']['etiqueta'] }}:</strong></div>
                                <div class="col-lg-3">{{ $fus['fus_cuerpo']['ext_usu_red']['valor'] }}</div>
                                <div class="col-lg-3"><strong>{{ $fus['fus_cuerpo']['d_pro']['etiqueta'] }}:</strong></div>
                                <div class="col-lg-3">{{ $fus['fus_cuerpo']['d_pro']['valor'] }}</div>
                            </div>
                            <div class = "row">
                                <div class="col-lg-3"><strong>{{ $fus['fus_cuerpo']['nom_proyecto']['etiqueta'] }}:</strong></div>
                                <div class="col-lg-3">{{ $fus['fus_cuerpo']['nom_proyecto']['valor'] }}</div>
                                <div class="col-lg-3"><strong>{{ $fus['fus_cuerpo']['ext_ubicacion']['etiqueta'] }}:</strong></div>
                                <div class="col-lg-3">{{ $fus['fus_cuerpo']['ext_ubicacion']['valor'] }}</div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3"><strong>{{ $fus['fus_cuerpo']['so']['etiqueta'] }}:</strong></div>
                                <div class="col-lg-3">{{ $fus['fus_cuerpo']['so']['valor'] }}</div>
                                <div class="col-lg-3"><strong>{{ $fus['fus_cuerpo']['t_equipo']['etiqueta'] }}:</strong></div>
                                <div class="col-lg-3">{{ $fus['fus_cuerpo']['t_equipo']['valor'] }}</div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3"><strong>{{ $fus['fus_cuerpo']['marca']['etiqueta'] }}:</strong></div>
                                <div class="col-lg-3">{{ $fus['fus_cuerpo']['marca']['valor'] }}</div>
                                <div class="col-lg-3"><strong>{{ $fus['fus_cuerpo']['modelo']['etiqueta'] }}:</strong></div>
                                <div class="col-lg-3">{{ $fus['fus_cuerpo']['modelo']['valor'] }}</div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3"><strong>{{ $fus['fus_cuerpo']['address']['etiqueta'] }}:</strong></div>
                                <div class="col-lg-3">{{ $fus['fus_cuerpo']['address']['valor'] }}</div>
                                <div class="col-lg-3"><strong>{{ $fus['fus_cuerpo']['n_serie']['etiqueta'] }}:</strong></div>
                                <div class="col-lg-3">{{ $fus['fus_cuerpo']['n_serie']['valor'] }}</div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3"><strong>{{ $fus['fus_cuerpo']['impresion']['etiqueta'] }}:</strong></div>
                                @if($fus['fus_cuerpo']['impresion']['valor'] == 'on')
                                    <i class="fas fa-check"></i>
                                @else
                                    {{ $fus['fus_cuerpo']['impresion']['valor'] }}
                                @endif
                                <div class="col-lg-3"><strong>{{ $fus['fus_cuerpo']['saplicacion']['etiqueta'] }}:</strong></div>
                                @if($fus['fus_cuerpo']['saplicacion']['valor'] == 'on')
                                    <i class="fas fa-check"></i>
                                @else
                                    {{ $fus['fus_cuerpo']['saplicacion']['valor'] }}
                                @endif
                            </div>
                            <div class="row">
                                <div class="col-lg-3"><strong>{{ $fus['fus_cuerpo']['sarchivo']['etiqueta'] }}:</strong></div>
                                @if($fus['fus_cuerpo']['sarchivo']['valor'] == 'on')
                                    <i class="fas fa-check"></i>
                                @else
                                    {{ $fus['fus_cuerpo']['sarchivo']['valor'] }}
                                @endif
                                <div class="col-lg-3"><strong>{{ $fus['fus_cuerpo']['justificacion']['etiqueta'] }}:</strong></div>
                                <div class="col-lg-3">{{ $fus['fus_cuerpo']['justificacion']['valor'] }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                @break
        @endswitch
        </div>
        </main>
        <!-- Fin Cuerpo -->
        <!-- Scripts JS -->
        <script src="{{ asset('js/loading.js') }}"></script>
        <script>
            $(document).ready(function(){
                @if($tipo == 1)
                    var idRelConf = '';
                    @if(isset($idRelConf->id))
                        idRelConf = {{$idRelConf->id}};
                    @endif
                    @if($showCuestionAuto == 1)
                        $('#autoizarfus').click(function(){
                            swal.fire({
                                title: 'Advertencia',
                                text: "¿Esta seguro de querer autorizar el FUS #{{ $fus['id'] }}?",
                                type: 'warning',
                                showCancelButton: true,
                                cancelButtonColor: '#DC3545',
                                confirmButtonColor: '#3085d6',
                                cancelButtonText: 'Cancelar',
                                confirmButtonText: 'De acuerdo',
                            }).then((result) => {
                                if (result.value){
                                    $.ajaxSetup({
                                        headers: {
                                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                        }
                                    });
                                    var id = '{{$fus["id"]}}';
                                    var jefeOAut = '{{$jefeOAut}}';
                                    // var idapp = '{{$idapp}}';
                                    $.ajax({
                                        type: 'POST',
                                        data: { id: id, jefeOAut: jefeOAut, tipoAccion: 1, idRelConf: idRelConf/*, idapp: idapp */},
                                        // dataType: 'JSON',
                                        url: '{{ route("autorizacionjefe") }}',
                                        beforeSend: function(){
                                            mostrarLoading();
                                        },
                                        complete: function(){
                                            var tipof = '{{$fus["tipo_fus"]}}';
                                            if(tipof == 0){
                                                ocultarLoading();
                                                swal.fire(
                                                    'Validación',
                                                    'Se a realizado la operación de manera satisfactoria.',
                                                    'success'
                                                ).then((result) => {
                                                    location.reload();   
                                                });
                                            }
                                        }
                                    }).done(function(response){
                                        var idFus = '{{$fus["id"]}}';
                                        var tipof = '{{$fus["tipo_fus"]}}';
                                        if(tipof != 0){
                                            exportarExcel(idFus);
                                        }
                                    }).fail(function(response){
                                        // location.reload();
                                    });
                                } 
                            });
                        });

                        function exportarExcel(idFus) {
                            $.ajax({
                                type: 'POST',
                                data: { id: idFus },
                                // dataType: 'JSON',
                                url: '{{ route("notificacionFinalWtl1") }}',
                                beforeSend: function(){
                                    // mostrarLoading();
                                },
                                complete: function(){
                                    ocultarLoading();
                                }
                            }).done(function(response){
                                swal.fire(
                                    'Validación',
                                    'Se a realizado la operación de manera satisfactoria.',
                                    'success'
                                ).then((result) => {
                                    location.reload();   
                                });
                            }).fail(function(response){
                                // location.reload();
                            });
                        }

                        $('#rechazofus').click(function(){
                            swal.fire({
                                title: 'Advertencia',
                                input: 'text',
                                inputAttributes: {
                                    autocapitalize: 'off'
                                },
                                text: "¿Esta seguro de querer rechazar el FUS #{{ $fus['id'] }}?, indique la causa.",
                                type: 'warning',
                                showCancelButton: true,
                                cancelButtonColor: '#DC3545',
                                confirmButtonColor: '#3085d6',
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                cancelButtonText: 'Cancelar',
                                confirmButtonText: 'De acuerdo',
                                inputValidator: (value) => {
                                    if (!value) {
                                        return 'Indique el motivo del rechazo!'
                                    }
                                },
                                preConfirm: (observaciones) => {
                                    var data = {id: {{$fus["id"]}}, jefeOAut: {{$jefeOAut}}, tipoAccion: 2, observaciones: observaciones, idRelConf: idRelConf};
                                    return fetch('{{ route("rechazofusjefe") }}', {
                                        method: 'POST', // or 'PUT'
                                        body: JSON.stringify(data), // data can be `string` or {object}!
                                        headers:{
                                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                                            'Content-Type': 'application/json'
                                        }
                                    }).then(response => {
                                            if (!response.ok) {
                                                throw new Error(response.statusText)
                                            }
                                            return response;
                                        })
                                        .catch(error => {
                                            Swal.showValidationMessage(
                                                `Request failed: ${error}`
                                            )
                                        })
                                },
                                allowOutsideClick: () => !Swal.isLoading()
                            }).then((result) => {
                                if (result.value) {
                                    Swal.fire({
                                        title: 'De acuerdo',
                                        text: "La operación ha sido realizada con éxito.",
                                        // title: `${result.value.ok}'s avatar`,
                                        // imageUrl: result.value.avatar_url
                                    }).then((result) => {
                                        if (result.value) {
                                            location.reload();   
                                        }
                                    });
                                }
                            });
                        });
                    @endif
                @endif
            });
        </script>
        <!-- Fin Scripts JS -->
        @else
            <div class="container">
                <div class="card row">
                    <div class="card-header">
                        ADVERTENCIA <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <p>
                                    Este FUS no puede ser visualizado ya que no esta vinculado a su # de empleado.
                                </p>
                            </div>
                            <div class="col-lg-12">
                                <a class="btn btn-warning btn-block" style="color:#FFFFFF;" href="{{ route('fus_lista') }}">Regresar</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <div id="content-loading" class="text-center loading_hide">
            <div id="loading-logo">
                <img src="{{ asset('images/procesando.gif') }}"/>
            </div>
        </div>
    </body>
</html>