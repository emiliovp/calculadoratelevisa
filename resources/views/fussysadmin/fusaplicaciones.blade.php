<!-- @extends('layouts.app') -->
<!--  -->
@component('layouts.encabezadowintel',['foo' => $param['data'], 'route'=> $param['route'], 'tipo_fus' => $param['tipo_fus'], 'jer' => $param['jer']])
<!-- aqui va el codigo que recibira el encabezado -->
@slot('body')

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
    .divselectmultiple {
        max-height: 200px;
        overflow-y: auto;
    }
</style>

<input name="tipo_fus" id="tipo_fus" type="hidden" value="0"/>
<input name="apps" id="apps" type="hidden" value="{{ $apps }}"/>
<div class="container" style="margin-top:10px;">
<div class="card" id="card-archivos">
        <div class="card-header">
            {{ __('Carga de anexos')}}
        </div>
        <div class="card-body ">
            <div class="form-group row">
                <div class="col-md-12">
                    <div class="custom-file">
                    <div id="documento" name="documento">
                            <div class ="form-group row">
                                <div class="col-md-5">
                                    <input type="file" class="form-control car-arch" id="archivo" name="archivo[0][file]" data-type="doc_0">
                                </div>
                                <div class="col-md-5">
                                    <select class="form-control appselect" name="archivo[0][app]" id = "id_app_fus" data-type="app_0">
                                        <option value="">Selecciona una aplicación</option>
                                        @foreach($aplicaciones AS $keys => $valApp)
                                        <option value="{{ $keys }}">{{ $valApp }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button class="btn btn-success" id="add_field">Agregar Anexos</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="accordion" id="accordionSeccionesApps">
        @foreach($aplicaciones AS $keys => $valApp)
        <div class="card" id="card_{{ $keys }}">
            <div class="card-header">
                <h2 class="mb-0">
                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#multiCollapse{{$keys}}" aria-expanded="false" aria-controls="multiCollapse{{$keys}}">
                        {{ $valApp }}
                    </button>
                </h2>
            </div>
            <div class="card-body collapse multi-collapse show" id="multiCollapse{{$keys}}">
                <!-- <div class="card-body"> -->
                @switch($keys)
                    @case('2')
                        <div class="form-group row" id="subencabezado_{{ $keys }}">
                            <div class="col-md-12" id="campodeaccion_{{ $keys }}"></div>
                            <div class="col-md-6">
                                <label for="altabajacambio_{{ $keys }}">Tipo de Solicitud:</label>
                                <select name="aplicaciones[{{ $keys }}][altabajacambio_{{ $keys }}]" id="altabajacambio_{{ $keys }}" class="form-control campo-requerido tiposolicitudselect">
                                    <option value="">Seleccione...</option>
                                    <option value="a">Alta</option>
                                    <option value="b">Baja</option>
                                    <option value="c">Cambio</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="rol_{{ $keys }}">Rol: </label>
                                <select name="aplicaciones[{{ $keys }}][hiddenRol_{{ $keys }}]" class="form-control campo-requerido" id="rol_{{ $keys }}">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                           <!--  <div class="col-md-1">
                                <label>&nbsp;</label>
                                <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenRol_{{ $keys }}]" id="hiddenRol_{{ $keys }}" class="campo-requerido" />
                                <button id="addrol_{{ $keys }}" type="button" class="form-control btn btn-primary btn-add"><i class="fas fa-plus"></i></button>
                            </div> -->
                        </div>
                        <div class="form-group row">
                            <div id="content_list_rol_{{ $keys }}" class="col-md-12"></div>

                            <div class="col-md-12">
                                <ul id="list_rol_{{ $keys }}">
                                </ul>
                            </div>
                        </div>
                        @break
                    @case('3')
                        <div class="form-group row" id="subencabezado_{{ $keys }}">
                            <div class="col-md-12" id="campodeaccion_{{ $keys }}"></div>
                            <div class="col-md-6">
                                <label for="altabajacambio_{{ $keys }}">Tipo de Solicitud:</label>
                                <select name="aplicaciones[{{ $keys }}][altabajacambio_{{ $keys }}]" id="altabajacambio_{{ $keys }}" class="form-control campo-requerido tiposolicitudselect">
                                    <option value="">Seleccione...</option>
                                    <option value="a">Alta</option>
                                    <option value="b">Baja</option>
                                    <option value="c">Cambio</option>
                                </select>
                            </div>
                            <div class="col-md-5">
                                <label for="aplicacion_{{ $keys }}">Aplicación: </label>
                                <!-- <input type="text" name="rol_{{ $keys }}" id="rol_{{ $keys }}" class="form-control"/> -->
                                <select name="aplicacion_{{ $keys }}" class="form-control" id="aplicacion_{{ $keys }}">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <label>&nbsp;</label>
                                <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenAplicacion_{{ $keys }}]" id="hiddenAplicacion_{{ $keys }}" class="campo-requerido" />
                                <button id="addapp_{{ $keys }}" type="button" class="form-control btn btn-primary btn-add"><i class="fas fa-plus"></i></button>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div id="content_list_aplicacion_{{ $keys }}" class="col-md-12"></div>

                            <div class="col-md-12">
                                <ul id="list_aplicacion_{{ $keys }}">
                                </ul>
                            </div>
                        </div>
                        @break
                    @case('4')
                        <div class="form-group row" id="subencabezado_{{ $keys }}">
                            <div class="col-md-12" id="campodeaccion_{{ $keys }}"></div>
                            <div class="col-md-6">
                                <label for="altabajacambio_{{ $keys }}">Tipo de Solicitud:</label>
                                <select name="aplicaciones[{{ $keys }}][altabajacambio_{{ $keys }}]" id="altabajacambio_{{ $keys }}" class="form-control campo-requerido tiposolicitudselect">
                                    <option value="">Seleccione...</option>
                                    <option value="a">Alta</option>
                                    <option value="b">Baja</option>
                                    <option value="c">Cambio</option>
                                </select>
                            </div>
                            <div class="col-md-5">
                                <label for="aplicacion_{{ $keys }}">Aplicación: </label>
                                <!-- <input type="text" name="rol_{{ $keys }}" id="rol_{{ $keys }}" class="form-control"/> -->
                                <select name="aplicacion_{{ $keys }}" class="form-control" id="aplicacion_{{ $keys }}">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <label>&nbsp;</label>
                                <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenAplicacion_{{ $keys }}]" id="hiddenAplicacion_{{ $keys }}" class="campo-requerido" />
                                <button id="addapp_{{ $keys }}" type="button" class="form-control btn btn-primary btn-add"><i class="fas fa-plus"></i></button>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div id="content_list_aplicacion_{{ $keys }}" class="col-md-12"></div>

                            <div class="col-md-12">
                                <ul id="list_aplicacion_{{ $keys }}">
                                </ul>
                            </div>
                        </div>
                        @break
                    @case('5')
                        <div class="form-group row" id="subencabezado_{{ $keys }}">
                            <div class="col-md-12" id="campodeaccion_{{ $keys }}"></div>
                            <div class="col-md-6">
                                <!-- <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenOtros_{{ $keys }}]" id="hiddenOtros_{{ $keys }}"/> -->
                                <label for="altabajacambio_{{ $keys }}">Tipo de Solicitud:</label>
                                <select name="aplicaciones[{{ $keys }}][altabajacambio_{{ $keys }}]" id="altabajacambio_{{ $keys }}" class="form-control campo-requerido tiposolicitudselect">
                                    <option value="">Seleccione...</option>
                                    <option value="a">Alta</option>
                                    <option value="b">Baja</option>
                                    <option value="c">Cambio</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="tipousuario_{{ $keys }}">Tipo de Usuario:</label>
                                <select name="aplicaciones[{{ $keys }}][tipousuario_{{ $keys }}]" id="tipousuario_{{ $keys }}" class="form-control">
                                    <option value="">Seleccione...</option>
                                    <!-- <option value="Vendedor">Vendedor</option>
                                    <option value="Sistema">Sistema</option> -->
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="areas_{{ $keys }}">Áreas:</label>
                                <select name="aplicaciones[{{ $keys }}][hiddenAreas_{{ $keys }}]" id="areas_{{ $keys }}" class="form-control campo-requerido areaselect">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                            <div class="col-md-5">
                                <label for="areas_{{ $keys }}">Perfiles:</label>
                                <select name="perfil_{{ $keys }}" id="perfil_{{ $keys }}" class="form-control">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                 <label>&nbsp;</label>
                                <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenPerfil_{{ $keys }}]" id="hiddenPerfil_{{ $keys }}" class="campo-requerido" />
                                <button id="addperfil_{{ $keys }}" type="button" class="form-control btn btn-primary btn-add"><i class="fas fa-plus"></i></button>
                            </div>
                        </div>
                         <div class="form-group row">
                            <div id="content_list_perfil_{{ $keys }}" class="col-md-12"></div>

                            <div class="col-md-12">
                                <ul id="list_perfil_{{ $keys }}">
                                </ul>
                            </div>
                        </div>
                        @break
                    @case('6')
                        <div class="form-group row" id="subencabezado_{{ $keys }}">
                            <div class="col-md-12" id="campodeaccion_{{ $keys }}"></div>
                            <div class="col-md-6">
                                <!-- <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenOtros_{{ $keys }}]" id="hiddenOtros_{{ $keys }}"/> -->
                                <label for="altabajacambio_{{ $keys }}">Tipo de Solicitud:</label>
                                <select name="aplicaciones[{{ $keys }}][altabajacambio_{{ $keys }}]" id="altabajacambio_{{ $keys }}" class="form-control campo-requerido tiposolicitudselect">
                                    <option value="">Seleccione...</option>
                                    <option value="a">Alta</option>
                                    <option value="b">Baja</option>
                                    <option value="c">Cambio</option>
                                </select>
                            </div>    
                           <div class="col-md-5">
                            <label for="perfil_{{ $keys }}">Perfiles: </label>
                                <select name="perfil_{{ $keys }}" class="form-control perfilselect" id="perfil_{{ $keys }}">
                                    <option value="">Seleccione...</option>
                                </select>
                           </div>
                            <div class="col-md-1">
                                <label>&nbsp;</label>
                                <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenPerfil_{{ $keys }}]" id="hiddenPerfil_{{ $keys }}" class="campo-requerido" />
                                <button id="addperfil_{{ $keys }}" type="button" class="form-control btn btn-primary btn-add"><i class="fas fa-plus"></i></button>
                            </div>
                        </div>
                        <div class="form-group row">
                           <!--  <div class="col-md-6">
                                <label for="nodfp_{{ $keys }}"># DFP: </label>
                                <input type="text" name="aplicaciones[{{ $keys }}][nodfp_{{ $keys }}]" id="nodfp_{{ $keys }}" class="form-control"/>
                            </div>-->
                             <div class="col-md-6">
                                <label for="tipousuario_{{ $keys }}">Tipo de Usuario:</label>
                                <select name="aplicaciones[{{ $keys }}][tipousuario_{{ $keys }}]" id="tipousuario_{{ $keys }}" class="form-control campo-requerido">
                                    <option value="">Seleccione...</option>
                                    <!-- <option value="Ventas">Ventas</option>
                                    <option value="Trafico">Trafico</option> -->
                                    <!-- <option value="Trafico Service">Trafico Service</option>
                                    <option value="Reporting">Reporting</option>
                                    <option value="Admon de Operaciones">Admon de Operaciones</option>
                                    <option value="Admon Ventas">Admon Ventas</option>
                                    <option value="Mesa de Control">Mesa de Control</option> -->
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="usuariodfp_{{ $keys }}">Usuario en DFP: </label>
                                <input type="text" name="aplicaciones[{{ $keys }}][usuariodfp_{{ $keys }}]" id="usuariodfp_{{ $keys }}" class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div id="content_list_perfil_{{ $keys }}" class="col-md-12"></div>

                            <div class="col-md-12">
                                <ul id="list_perfil_{{ $keys }}">
                                </ul>
                            </div>
                        </div>
                        @break
                    @case('7')
                        <div class="form-group row" id="subencabezado_{{ $keys }}">
                            <div class="col-md-12" id="campodeaccion_{{ $keys }}"></div>
                            <div class="col-md-6">
                                <label for="altabajacambio_{{ $keys }}">Tipo de Solicitud:</label>
                                <select name="aplicaciones[{{ $keys }}][altabajacambio_{{ $keys }}]" id="altabajacambio_{{ $keys }}" class="form-control campo-requerido tiposolicitudselect">
                                    <option value="">Seleccione...</option>
                                    <option value="a">Alta</option>
                                    <option value="b">Baja</option>
                                    <option value="c">Cambio</option>
                                </select>
                            </div>
                            <div class="col-md-5">
                                <label for="rol_{{ $keys }}">Rol: </label>
                                <!-- <input type="text" name="rol_{{ $keys }}" id="rol_{{ $keys }}" class="form-control"/> -->
                                <select name="rol_{{ $keys }}" class="form-control" id="rol_{{ $keys }}">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <label>&nbsp;</label>
                                <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenRol_{{ $keys }}]" id="hiddenRol_{{ $keys }}" class="campo-requerido" />
                                <button id="addrol_{{ $keys }}" type="button" class="form-control btn btn-primary btn-add"><i class="fas fa-plus"></i></button>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="tipoacceso_{{ $keys }}">Tipo de Acceso:</label>
                                <select name="aplicaciones[{{ $keys }}][tipoacceso_{{ $keys }}]" id="tipoacceso_{{ $keys }}" class="form-control">
                                    <option value="">Seleccione...</option>
                                    <option value="Lectura">Lectura</option>
                                    <option value="Escritura">Escritura</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div id="content_list_rol_{{ $keys }}" class="col-md-12"></div>

                            <div class="col-md-12">
                                <ul id="list_rol_{{ $keys }}">
                                </ul>
                            </div>
                        </div>
                        @break
                    @case('9')
                        <div class="form-group row" id="subencabezado_{{ $keys }}">
                            <div class="col-md-12" id="campodeaccion_{{ $keys }}"></div> 
                            <div class="col-md-6">
                                <label for="altabajacambio_{{ $keys }}">Tipo de Solicitud:</label>
                                <select name="aplicaciones[{{ $keys }}][altabajacambio_{{ $keys }}]" id="altabajacambio_{{ $keys }}" class="form-control campo-requerido tiposolicitudselect">
                                    <option value="">Seleccione...</option>
                                    <option value="a">Alta</option>
                                    <option value="b">Baja</option>
                                    <option value="c">Cambio</option>
                                </select>
                            </div>    
                            <div class="col-md-6">
                                <label for="modulo_{{ $keys }}">Módulos:</label>
                                <select name="aplicaciones[{{ $keys }}][modulo_{{ $keys }}]" id="modulo_{{ $keys }}" class="form-control moduloselect campo-requerido ">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-5"> 
                                <label for="empresa_{{ $keys }}">Empresas:</label>
                                <select name="empresa_{{ $keys }}" class="form-control empresaselect" id="empresa_{{ $keys }}">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <label>&nbsp;</label>
                                <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenEmpresa_{{ $keys }}]" id="hiddenEmpresa_{{ $keys }}" class="campo-requerido" />
                                <button id="addempresa_{{ $keys }}" type="button" class="form-control btn btn-primary btn-add"><i class="fas fa-plus"></i></button>
                            </div>
                            <div class="col-md-6">
                                <label for="rol_{{ $keys }}">Rol: </label>
                                <!-- <input type="text" name="rol_{{ $keys }}" id="rol_{{ $keys }}" class="form-control"/> -->
                                <select name="aplicaciones[{{ $keys }}][hiddenRol_{{ $keys }}]" class="form-control campo-requerido " id="rol_{{ $keys }}">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                            <!-- <div class="col-md-1">
                                <label>&nbsp;</label>
                                <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenRol_{{ $keys }}]" id="hiddenRol_{{ $keys }}" class="campo-requerido" />
                                <button id="addrol_{{ $keys }}" type="button" class="form-control btn btn-primary btn-add"><i class="fas fa-plus"></i></button>
                            </div> -->
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div id="content_list_empresa_{{ $keys }}" class="col-md-12"></div>

                                    <div class="col-md-12">
                                        <ul id="list_empresa_{{ $keys }}">
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="col-md-6">
                                <div class="row">
                                    <div id="content_list_rol_{{ $keys }}" class="col-md-12"></div>
    
                                    <div class="col-md-12">
                                        <ul id="list_rol_{{ $keys }}">
                                        </ul>
                                    </div>
                                </div>
                            </div> -->
                        </div>
                        @break
                    @case('10')
                        <div class="form-group row" id="subencabezado_{{ $keys }}">
                            <div class="col-md-12" id="campodeaccion_{{ $keys }}"></div>
                            <div class="col-md-6">
                                <label for="altabajacambio_{{ $keys }}">Tipo de Solicitud:</label>
                                <select name="aplicaciones[{{ $keys }}][altabajacambio_{{ $keys }}]" id="altabajacambio_{{ $keys }}" class="form-control campo-requerido tiposolicitudselect">
                                    <option value="">Seleccione...</option>
                                    <option value="a">Alta</option>
                                    <option value="b">Baja</option>
                                    <option value="c">Cambio</option>
                                </select>
                            </div>
                            <div class="col-md-5">
                                <label for="rol_{{ $keys }}">Rol: </label>
                                <!-- <input type="text" name="rol_{{ $keys }}" id="rol_{{ $keys }}" class="form-control"/> -->
                                <select name="rol_{{ $keys }}" class="form-control" id="rol_{{ $keys }}">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <label>&nbsp;</label>
                                <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenRol_{{ $keys }}]" id="hiddenRol_{{ $keys }}" class="campo-requerido" />
                                <button id="addrol_{{ $keys }}" type="button" class="form-control btn btn-primary btn-add"><i class="fas fa-plus"></i></button>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div id="content_list_rol_{{ $keys }}" class="col-md-12"></div>

                            <div class="col-md-12">
                                <ul id="list_rol_{{ $keys }}">
                                </ul>
                            </div>
                        </div>
                        @break
                    @case('12')
                        <div class="form-group row" id="subencabezado_{{ $keys }}">
                            <div class="col-md-12" id="campodeaccion_{{ $keys }}"></div>
                            <div class="col-md-12">
                                <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenOtros_{{ $keys }}]" id="hiddenOtros_{{ $keys }}"/>
                                <label for="altabajacambio_{{ $keys }}">Tipo de Solicitud:</label>
                                <select name="aplicaciones[{{ $keys }}][altabajacambio_{{ $keys }}]" id="altabajacambio_{{ $keys }}" class="form-control campo-requerido tiposolicitudselect">
                                    <option value="">Seleccione...</option>
                                    <option value="a">Alta</option>
                                    <option value="b">Baja</option>
                                    <option value="c">Cambio</option>
                                </select>
                            </div>
                            <!-- <div class="col-md-5">
                                <label for="perfil_{{ $keys }}">Perfiles: </label>
                                <select name="perfil_{{ $keys }}" class="form-control" id="perfil_{{ $keys }}">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <label>&nbsp;</label>
                                <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenPerfil_{{ $keys }}]" id="hiddenPerfil_{{ $keys }}" class="campo-requerido" />
                                <button id="addperfil_{{ $keys }}" type="button" class="form-control btn btn-primary btn-add"><i class="fas fa-plus"></i></button>
                            </div> -->
                        </div>
                        <!-- <div class="form-group row">
                            <div class="col-md-6">
                                <label for="autor_{{ $keys }}">Autor: </label>
                                <input type="text" name="aplicaciones[{{ $keys }}][autor_{{ $keys }}]" id="autor_{{ $keys }}" class="form-control"/>
                            </div>
                            <div class="col-md-6">
                                <label for="consumidor_{{ $keys }}">Consumidor: </label>
                                <input type="text" name="aplicaciones[{{ $keys }}][consumidor_{{ $keys }}]" id="consumidor_{{ $keys }}" class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div id="content_list_perfil_{{ $keys }}" class="col-md-12"></div>

                            <div class="col-md-12">
                                <ul id="list_perfil_{{ $keys }}">
                                </ul>
                            </div>
                        </div> -->
                        @break
                    @case('13')
                        <div class="form-group row" id="subencabezado_{{ $keys }}">
                            <div class="col-md-12" id="campodeaccion_{{ $keys }}"></div>
                            <div class="col-md-12">
                                <label for="altabajacambio_{{ $keys }}">Tipo de Solicitud:</label>
                                <select name="aplicaciones[{{ $keys }}][altabajacambio_{{ $keys }}]" id="altabajacambio_{{ $keys }}" class="form-control campo-requerido tiposolicitudselect">
                                    <option value="">Seleccione...</option>
                                    <option value="a">Alta</option>
                                    <option value="b">Baja</option>
                                    <option value="c">Cambio</option>
                                </select>
                            </div>
                            <!-- <div class="col-md-6 accioncambio" id="accioncambio_{{ $keys }}">
                                <label for="accionrol_{{ $keys }}">Acción</label>
                                <select id="accionrol_{{ $keys }}" name="aplicaciones[{{ $keys }}][accionrol_{{ $keys }}]" class="form-control campo-requerido">
                                    <option value="">Seleccione...</option>
                                    <option value="Agregar">Agregar</option>
                                    <option value="Quitar">Quitar</option>
                                </select>
                            </div> -->
                        </div>
                        <div class="form-group row">
                            <!-- <div class="col-md-6">
                                <label for="tipousuario_{{ $keys }}">Tipo de Usuario: </label>
                                <select name="aplicaciones[{{ $keys }}][tipousuario_{{ $keys }}]" id="tipousuario_{{ $keys }}" class="form-control tipousuarioselect campo-requerido">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div> -->
                            <div class="col-md-12">
                                <label for="unidadnegocio_{{ $keys }}">Unidad de Negocio: </label>
                                <select id="unidadnegocio_{{ $keys }}" name="aplicaciones[{{ $keys }}][unidadnegocio_{{ $keys }}]" class="form-control campo-requerido">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                            <!-- <div class="col-md-1">
                                <label>&nbsp;</label>
                                <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenRol_{{ $keys }}]" id="hiddenRol_{{ $keys }}" class="campo-requerido" />
                                <button id="addrol_{{ $keys }}" type="button" class="form-control btn btn-primary btn-add"><i class="fas fa-plus"></i></button>
                            </div> -->
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="rol_{{ $keys }}">Rol: </label>
                                <!-- <input type="text" name="rol_{{ $keys }}" id="rol_{{ $keys }}" class="form-control"/> -->
                                <select name="aplicaciones[{{ $keys }}][hiddenRol_{{ $keys }}]" class="form-control campo-requerido" id="rol_{{ $keys }}">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                        </div>
                        <!-- <div class="form-group row">
                            <div id="content_list_rol_{{ $keys }}" class="col-md-12"></div>

                            <div class="col-md-12">
                                <ul id="list_rol_{{ $keys }}">
                                </ul>
                            </div>
                        </div> -->
                        @break
                    @case('14')
                        <div class="form-group row" id="subencabezado_{{ $keys }}">
                            <div class="col-md-12" id="campodeaccion_{{ $keys }}"></div>
                            <div class="col-md-12">
                                <label for="altabajacambio_{{ $keys }}">Tipo de Solicitud:</label>
                                <select name="aplicaciones[{{ $keys }}][altabajacambio_{{ $keys }}]" id="altabajacambio_{{ $keys }}" class="form-control campo-requerido tiposolicitudselect">
                                    <option value="">Seleccione...</option>
                                    <option value="a">Alta</option>
                                    <option value="b">Baja</option>
                                    <option value="c">Cambio</option>
                                </select>
                            </div>
                            <!-- <div class="col-md-6">
                                <label for="accionrol_{{ $keys }}">Acción para Rol</label>
                                <select name="aplicaciones[{{ $keys }}][accionrol_{{ $keys }}]" id="accionrol_{{ $keys }}" class="form-control">
                                    <option value="">Seleccione...</option>
                                    <option value="Agregar">Agregar</option>
                                    <option value="Quitar">Quitar</option>
                                </select>
                            </div> -->
                        </div>
                        <div class="form-group row">
                            <div class="col-md-11">
                                <label for="rol_{{ $keys }}">Especificar Rol: </label>
                                <!-- <input type="text" name="rol_{{ $keys }}" id="rol_{{ $keys }}" class="form-control"/> -->
                                <select name="rol_{{ $keys }}" class="form-control" id="rol_{{ $keys }}">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <label>&nbsp;</label>
                                <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenRol_{{ $keys }}]" id="hiddenRol_{{ $keys }}" class="campo-requerido" />
                                <button id="addrol_{{ $keys }}" type="button" class="form-control btn btn-primary btn-add"><i class="fas fa-plus"></i></button>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div id="content_list_rol_{{ $keys }}" class="col-md-12"></div>

                            <div class="col-md-12">
                                <ul id="list_rol_{{ $keys }}">
                                </ul>
                            </div>
                        </div>
                        @break
                    @case('15')
                        <div class="form-group row" id="subencabezado_{{ $keys }}">
                            <div class="col-md-12" id="campodeaccion_{{ $keys }}"></div>
                            <div class="col-md-6">
                                <label for="altabajacambio_{{ $keys }}">Tipo de Solicitud:</label>
                                <select name="aplicaciones[{{ $keys }}][altabajacambio_{{ $keys }}]" id="altabajacambio_{{ $keys }}" class="form-control campo-requerido tiposolicitudselect">
                                    <option value="">Seleccione...</option>
                                    <option value="a">Alta</option>
                                    <option value="b">Baja</option>
                                    <option value="c">Cambio</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="rol_{{ $keys }}">Rol: </label>
                                <select name="aplicaciones[{{ $keys }}][hiddenRol_{{ $keys }}]" class="form-control campo-requerido" id="rol_{{ $keys }}">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                            <!-- <div class="col-md-1">
                                <label>&nbsp;</label>
                                <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenRol_{{ $keys }}]" id="hiddenRol_{{ $keys }}" class="campo-requerido" />
                                <button id="addrol_{{ $keys }}" type="button" class="form-control btn btn-primary btn-add"><i class="fas fa-plus"></i></button>
                            </div> -->
                        </div>
                        <!--<div class="form-group row">
                            <div id="content_list_rol_{{ $keys }}" class="col-md-12"></div>

                            <div class="col-md-12">
                                <ul id="list_rol_{{ $keys }}">
                                </ul>
                            </div>
                        </div> -->
                        @break
                    @case('16')
                        <div class="form-group row" id="subencabezado_{{ $keys }}">
                            <div class="col-md-12" id="campodeaccion_{{ $keys }}"></div>
                            <div class="col-md-12">
                                <label for="altabajacambio_{{ $keys }}">Tipo de Solicitud:</label>
                                <select name="aplicaciones[{{ $keys }}][altabajacambio_{{ $keys }}]" id="altabajacambio_{{ $keys }}" class="form-control campo-requerido tiposolicitudselect">
                                    <option value="">Seleccione...</option>
                                    <option value="a">Alta</option>
                                    <option value="b">Baja</option>
                                    <option value="c">Cambio</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12"><input type="hidden" name="validacion_al_menos_uno_{{ $keys }}" id="validacion_al_menos_uno_{{ $keys }}" class="campo-requerido" /></div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3">
                                <div class="row">
                                    <div class="col-md-12">    
                                        <input type="checkbox" name="aplicaciones[{{ $keys }}][prod_{{ $keys }}]" data-datos="reporteprod_{{ $keys }}" data-idreal="prodreporte_{{ $keys }}"  id="prod_{{ $keys }}" class="form-control check-not-disabled" data-aquien="hiddenReporteProd_{{ $keys }}" />
                                        <label for="prod_{{ $keys }}">Prod:</label>
                                    </div>
                                    <div class="col-md-12">
                                        <input type="checkbox" name="aplicaciones[{{ $keys }}][intermex_{{ $keys }}]" data-datos="reporteintermex_{{ $keys }}" data-idreal="intermexreporte_{{ $keys }}"  id="intermex_{{ $keys }}" class="form-control check-not-disabled" data-aquien="hiddenReporteIntermex_{{ $keys }}" />
                                        <label for="intermex_{{ $keys }}">Intermex:</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-md-11"> 
                                        <label for="prodreportes_{{ $keys }}">Reportes Prod:</label>
                                        <select disabled name="prodreporte_{{ $keys }}" class="form-control" id="prodreporte_{{ $keys }}">
                                            <option value="">Seleccione...</option>
                                        </select>
                                    </div>
                                    <div class="col-md-1">
                                        <label>&nbsp;</label>
                                        <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenReporteProd_{{ $keys }}]" id="hiddenReporteProd_{{ $keys }}" />
                                        <button disabled id="addreporteprod_{{ $keys }}" type="button" class="form-control btn btn-primary btn-add"><i class="fas fa-plus"></i></button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-11"> 
                                        <label for="intermex_{{ $keys }}">Reportes Intermex:</label>
                                        <select disabled name="intermexreporte_{{ $keys }}" class="form-control" id="intermexreporte_{{ $keys }}">
                                            <option value="">Seleccione...</option>
                                        </select>
                                    </div>
                                    <div class="col-md-1">
                                        <label>&nbsp;</label>
                                        <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenReporteIntermex_{{ $keys }}]" id="hiddenReporteIntermex_{{ $keys }}" />
                                        <button disabled id="addreporteintermex_{{ $keys }}" type="button" class="form-control btn btn-primary btn-add"><i class="fas fa-plus"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div id="content_list_reporte_prod_{{ $keys }}" class="col-md-12"></div>
    
                                    <div class="col-md-12">
                                        <ul id="list_reporte_prod_{{ $keys }}">
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div id="content_list_reporte_intermex_{{ $keys }}" class="col-md-12"></div>

                                    <div class="col-md-12">
                                        <ul id="list_reporte_intermex_{{ $keys }}">
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @break
                    @case('17')
                        <div class="form-group row" id="subencabezado_{{ $keys }}">
                            <div class="col-md-12" id="campodeaccion_{{ $keys }}"></div>
                            <div class="col-md-6">
                                <label for="altabajacambio_{{ $keys }}">Tipo de Solicitud:</label>
                                <select name="aplicaciones[{{ $keys }}][altabajacambio_{{ $keys }}]" id="altabajacambio_{{ $keys }}" class="form-control campo-requerido tiposolicitudselect">
                                    <option value="">Seleccione...</option>
                                    <option value="a">Alta</option>
                                    <option value="b">Baja</option>
                                    <option value="c">Cambio</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="repositorio_{{ $keys }}">Repositorio:</label>
                                <select name="aplicaciones[{{ $keys }}][hiddenRepositorio_{{ $keys }}]" id="hiddenRepositorio_{{ $keys }}"
                                class="form-control campo-requerido tiporepositorioselect">
                                    <option value="">Seleccione...</option>
                                </select>
                                <!-- <input name="aplicaciones[{{ $keys }}][repositorio_{{ $keys }}]" id="repositorio_{{ $keys }}" class="form-control" /> -->
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="rol_{{ $keys }}">Rol: </label>
                                <select name="aplicaciones[{{ $keys }}][hiddenRol_{{ $keys }}]" class="form-control campo-requerido" id="rol_{{ $keys }}">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                           <!--  <div class="col-md-1">
                                <label>&nbsp;</label>
                                <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenRol_{{ $keys }}]" id="hiddenRol_{{ $keys }}" class="campo-requerido" />
                                <button id="addrol_{{ $keys }}" type="button" class="form-control btn btn-primary btn-add"><i class="fas fa-plus"></i></button>
                            </div> -->
                            <div class="col-md-6">
                                <label for="grupo_{{ $keys }}">Grupo: </label>
                                <select name="aplicaciones[{{ $keys }}][hiddenGrupo_{{ $keys }}]" class="form-control" id="grupo_{{ $keys }}">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                            <!-- <div class="col-md-1">
                                <label>&nbsp;</label>
                                <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenGrupo_{{ $keys }}]" id="hiddenGrupo_{{ $keys }}" class="campo-requerido" />
                                <button id="addgrupo_{{ $keys }}" type="button" class="form-control btn btn-primary btn-add"><i class="fas fa-plus"></i></button>
                            </div> -->
                        </div>
                        <!-- <div class="form-group row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div id="content_list_rol_{{ $keys }}" class="col-md-12"></div>
    
                                    <div class="col-md-12">
                                        <ul id="list_rol_{{ $keys }}">
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div id="content_list_grupo_{{ $keys }}" class="col-md-12"></div>

                                    <div class="col-md-12">
                                        <ul id="list_grupo_{{ $keys }}">
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div> -->
                        @break
                    @case('19')
                        <div class="form-group row" id="subencabezado_{{ $keys }}">
                            <div class="col-md-12" id="campodeaccion_{{ $keys }}"></div>
                            <div class="col-md-6">
                                <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenOtros_{{ $keys }}]" id="hiddenOtros_{{ $keys }}"/>
                                <label for="altabajacambio_{{ $keys }}">Tipo de Solicitud:</label>
                                <select name="aplicaciones[{{ $keys }}][altabajacambio_{{ $keys }}]" id="altabajacambio_{{ $keys }}" class="form-control campo-requerido tiposolicitudselect">
                                    <option value="">Seleccione...</option>
                                    <option value="a">Alta</option>
                                    <option value="b">Baja</option>
                                    <option value="c">Cambio</option>
                                </select>
                            </div>
                            <div class="col-md-5">
                                <label for="empresa_{{ $keys }}">Empresa y RFC: </label>
                                <select name="aplicaciones[{{ $keys }}][empresa_{{ $keys }}]" id="empresa_{{ $keys }}" class="form-control campo-requerido">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <label>&nbsp;</label>
                                <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenEmpresa_{{ $keys }}]" id="hiddenEmpresa_{{ $keys }}" class="campo-requerido" />
                                <button id="addempresa_{{ $keys }}" type="button" class="form-control btn btn-primary btn-add"><i class="fas fa-plus"></i></button>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="tipousuario_{{ $keys }}">Tipo de Usuario:</label>
                                <select name="aplicaciones[{{ $keys }}][tipousuario_{{ $keys }}]" id="tipousuario_{{ $keys }}" class="form-control">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="serie_{{ $keys }}">Serie: </label>
                                <input type="text" name="aplicaciones[{{ $keys }}][serie_{{ $keys }}]" id="serie_{{ $keys }}" class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <!-- <div class="col-md-6">
                                <label for="tipoplantahonorarios_{{ $keys }}">Administración (Planta u Honorarios):</label>
                                <select name="aplicaciones[{{ $keys }}][tipoplantahonorarios_{{ $keys }}]" id="tipoplantahonorarios_{{ $keys }}" class="form-control">
                                    <option value="">Seleccione...</option>
                                    <option value="Planta">Planta</option>
                                    <option value="Honorarios">Honorarios</option>
                                </select>
                            </div> -->
                            <div class="col-md-6">
                                <label for="tipoplantahonorarios_{{ $keys }}">Administración :</label>
                                <select name="aplicaciones[{{ $keys }}][administracion_{{ $keys }}]" id="administracion_{{ $keys }}" class="form-control campo-requerido">
                                    <option value="">Seleccione...</option>
                                    <!-- <option value="Planta">Planta</option>
                                    <option value="Honorarios">Honorarios</option> -->
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="norequisicion_{{ $keys }}">No. Requisición: </label>
                                <input type="text" name="aplicaciones[{{ $keys }}][norequisicion_{{ $keys }}]" id="norequisicion_{{ $keys }}" class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div id="content_list_empresa_{{ $keys }}" class="col-md-12"></div>
    
                                    <div class="col-md-12">
                                        <ul id="list_empresa_{{ $keys }}">
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @break
                    @case('18')
                        <div class="form-group row" id="subencabezado_{{ $keys }}">
                            <div class="col-md-12" id="campodeaccion_{{ $keys }}"></div>
                            <div class="col-md-6">
                                <!-- <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenOtros_{{ $keys }}]" id="hiddenOtros_{{ $keys }}"/> -->
                                <label for="altabajacambio_{{ $keys }}">Tipo de Solicitud:</label>
                                <select name="aplicaciones[{{ $keys }}][altabajacambio_{{ $keys }}]" id="altabajacambio_{{ $keys }}" class="form-control campo-requerido tiposolicitudselect">
                                    <option value="">Seleccione...</option>
                                    <option value="a">Alta</option>
                                    <option value="b">Baja</option>
                                    <option value="c">Cambio</option>
                                </select>
                            </div>
                            <div class="col-md-5">
                                <label for="empresa_{{ $keys }}">Empresa: </label>
                                <select name="aplicaciones[{{ $keys }}][empresa_{{ $keys }}]" id="empresa_{{ $keys }}" class="form-control campo-requerido">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <label>&nbsp;</label>
                                <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenEmpresa_{{ $keys }}]" id="hiddenEmpresa_{{ $keys }}" class="campo-requerido" />
                                <button id="addempresa_{{ $keys }}" type="button" class="form-control btn btn-primary btn-add"><i class="fas fa-plus"></i></button>
                            </div>
                            <!-- <div class="col-md-6">
                                <label for="empresatxt_{{ $keys }}">Empresa: </label>
                                <input type="text" name="aplicaciones[{{ $keys }}][empresatxt_{{ $keys }}]" id="empresatxt_{{ $keys }}" class="form-control"/>
                            </div>
                            <div class="col-md-6">
                                <label for="rfctxt_{{ $keys }}">RFC: </label>
                                <input type="text" name="aplicaciones[{{ $keys }}][rfctxt_{{ $keys }}]" id="rfctxt_{{ $keys }}" class="form-control"/>
                            </div> -->
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div id="content_list_empresa_{{ $keys }}" class="col-md-12"></div>
    
                                    <div class="col-md-12">
                                        <ul id="list_empresa_{{ $keys }}">
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @break
                    @case('20')
                        <div class="form-group row" id="subencabezado_{{ $keys }}">
                            <div class="col-md-12" id="campodeaccion_{{ $keys }}"></div>
                            <div class="col-md-12">
                                <label for="altabajacambio_{{ $keys }}">Tipo de Solicitud:</label>
                                <select name="aplicaciones[{{ $keys }}][altabajacambio_{{ $keys }}]" id="altabajacambio_{{ $keys }}" class="form-control campo-requerido tiposolicitudselect">
                                    <option value="">Seleccione...</option>
                                    <option value="a">Alta</option>
                                    <option value="b">Baja</option>
                                    <option value="c">Cambio</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label><strong>IMPORTANTE:</strong> En caso de requerir una responsabilidad con juego de libros, no es necesario seleccionar una empresa.</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12"><input type="hidden" name="validacion_al_menos_uno_{{ $keys }}" id="validacion_al_menos_uno_{{ $keys }}" class="campo-requerido" /></div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-2">
                                        <input type="checkbox" name="aplicaciones[{{ $keys }}][prod_{{ $keys }}]" data-datos="responsabilidad_{{ $keys }}" id="prod_{{ $keys }}" class="form-control check-not-disabled" data-aquien="hiddenResponsabilidad_prod_{{ $keys }}" />
                                        <label for="prod_{{ $keys }}">Prod:</label>
                                    </div>
                                    <div class="col-md-4"> 
                                        <label for="empresa_{{ $keys }}">Empresas PROD:</label>
                                        <select disabled name="empresa_{{ $keys }}" class="form-control empresaselect" id="empresa_prod_{{ $keys }}" data-tipo="prod">
                                            <option value="">Seleccione...</option>
                                        </select>
                                    </div>
                                    <div class="col-md-1">
                                        <label>&nbsp;</label>
                                        <!-- <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenEmpresa_{{ $keys }}]" id="hiddenEmpresa_prod_{{ $keys }}" class="campo-requerido" /> -->
                                        <!-- <button id="addempresa_{{ $keys }}" type="button" class="form-control btn btn-primary btn-add"><i class="fas fa-plus"></i></button> -->
                                    </div>
                                    <div class="col-md-4"> 
                                        <label for="responsabilidad_prod_{{ $keys }}">Reponsabilidades PROD:</label>
                                        <select disabled name="responsabilidad_prod_{{ $keys }}" class="form-control" data-tipo="PROD" id="responsabilidad_prod_{{ $keys }}">
                                            <option value="">Seleccione...</option>
                                        </select>
                                    </div>
                                    <div class="col-md-1">
                                        <label>&nbsp;</label>
                                        <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenResponsabilidad_prod_{{ $keys }}]" id="hiddenResponsabilidad_prod_{{ $keys }}" />
                                        <button disabled id="addresponsabilidad_{{ $keys }}" data-id-input="prod" type="button" class="form-control btn btn-primary btn-add" data-cod="addresponsabilidad_{{ $keys }}"><i class="fas fa-plus"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-2">
                                        <input type="checkbox" name="aplicaciones[{{ $keys }}][intermex_{{ $keys }}]" data-datos="responsabilidadintermex_{{ $keys }}" id="intermex_{{ $keys }}" class="form-control check-not-disabled" data-aquien="hiddenResponsabilidad_intermex_{{ $keys }}" />
                                        <label for="intermex_{{ $keys }}">Intermex:</label>
                                    </div>
                                    <div class="col-md-4"> 
                                    <label for="empresa_intermex_{{ $keys }}">Empresas INTERMEX:</label>
                                        <select disabled name="empresa_intermex_{{ $keys }}" class="form-control empresaselect" id="empresa_intermex_{{ $keys }}" data-tipo="intermex">
                                            <option value="">Seleccione...</option>
                                        </select>
                                    </div>
                                    <div class="col-md-1">
                                        <label>&nbsp;</label>
                                        <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenEmpresaIntermex_{{ $keys }}]" id="hiddenEmpresa_intermex_{{ $keys }}" />
                                        <!-- <button id="addempresa_{{ $keys }}" type="button" class="form-control btn btn-primary btn-add"><i class="fas fa-plus"></i></button> -->
                                    </div>
                                    <div class="col-md-4"> 
                                    <label for="responsabilidad_intermex_{{ $keys }}">Reponsabilidades INTERMEX:</label>
                                        <select disabled name="responsabilidad_intermex_{{ $keys }}" data-tipo="INTERMEX" class="form-control" id="responsabilidad_intermex_{{ $keys }}">
                                            <option value="">Seleccione...</option>
                                        </select>
                                    </div>
                                    <div class="col-md-1">
                                        <label>&nbsp;</label>
                                        <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenResponsabilidad_intermex_{{ $keys }}]" id="hiddenResponsabilidad_intermex_{{ $keys }}" />
                                        <button disabled id="addresponsabilidadintermex_{{ $keys }}" data-id-input="intermex" type="button" class="form-control btn btn-primary btn-add" data-cod="addresponsabilidad_{{ $keys }}"><i class="fas fa-plus"></i></button>
                                    </div>
                                </div>
                            </div>                            
                        </div>
                        <div class="form-group row">
                            <!-- <div class="col-md-6">
                                <div class="row">
                                    <div id="content_list_empresa_{{ $keys }}" class="col-md-12"></div>
    
                                    <div class="col-md-12">
                                        <ul id="list_empresa_{{ $keys }}">
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div id="content_list_responsabilidad_{{ $keys }}" class="col-md-12"></div>

                                    <div class="col-md-12">
                                        <ul id="list_responsabilidad_{{ $keys }}">
                                        </ul>
                                    </div>
                                </div>
                            </div> -->
                            <div class="col-md-12">
                                <div class="row">
                                    <div id="content_list_responsabilidad_{{ $keys }}" class="col-md-12"></div>

                                    <div class="col-md-12">
                                        <ul id="list_responsabilidad_{{ $keys }}">
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @break
                    <!-- ERP CLOUD -->
                    @case('1032')
                        <div class="form-group row" id="subencabezado_{{ $keys }}">
                            <div class="col-md-12" id="campodeaccion_{{ $keys }}"></div>
                            <div class="col-md-12">
                                <label for="altabajacambio_{{ $keys }}">Tipo de Solicitud:</label>
                                <select name="aplicaciones[{{ $keys }}][altabajacambio_{{ $keys }}]" id="altabajacambio_{{ $keys }}" class="form-control campo-requerido tiposolicitudselect">
                                    <option value="">Seleccione...</option>
                                    <option value="a">Alta</option>
                                    <option value="b">Baja</option>
                                    <option value="c">Cambio</option>
                                </select>
                            </div>
                        </div>
                        <!-- <div class="row">
                            <div class="col-md-12"><input type="hidden" name="validacion_al_menos_uno_{{ $keys }}" id="validacion_al_menos_uno_{{ $keys }}" class="campo-requerido" /></div>
                        </div> -->
                        <div class="form-group row">
                            <div class="col-md-12">
                                <div class="row">
                                    <!-- <div class="col-md-2">
                                        <input type="checkbox" name="aplicaciones[{{ $keys }}][prod_{{ $keys }}]" data-datos="responsabilidad_{{ $keys }}" id="prod_{{ $keys }}" class="form-control check-not-disabled" data-aquien="hiddenResponsabilidad_prod_{{ $keys }}" />
                                        <label for="prod_{{ $keys }}">Prod:</label>
                                    </div> -->
                                    <div class="col-md-6"> 
                                        <label for="empresa_{{ $keys }}">Empresas PROD:</label>
                                        <select disabled name="empresa_{{ $keys }}" class="form-control empresaselect" id="empresa_prod_{{ $keys }}" data-tipo="prod">
                                            <option value="">Seleccione...</option>
                                        </select>
                                    </div>
                                    <div class="col-md-5"> 
                                        <label for="responsabilidad_prod_{{ $keys }}">Reponsabilidades PROD:</label>
                                        <select multiple disabled name="responsabilidad_prod_{{ $keys }}" class="form-control" data-tipo="PROD" id="responsabilidad_prod_{{ $keys }}">
                                            <option value="">Seleccione...</option>
                                        </select>
                                    </div>
                                    <div class="col-md-1">
                                        <label>&nbsp;</label>
                                        <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenResponsabilidad_prod_{{ $keys }}]" id="hiddenResponsabilidad_prod_{{ $keys }}" class="campo-requerido" />
                                        <button disabled id="addresponsabilidadmultiple_{{ $keys }}" data-id-input="prod" type="button" class="form-control btn btn-primary btn-add" data-cod="addresponsabilidad_{{ $keys }}"><i class="fas fa-plus"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div id="content_list_responsabilidad_{{ $keys }}" class="col-md-12"></div>

                                    <div class="col-md-12">
                                        <ul id="list_responsabilidad_{{ $keys }}">
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @break
                    <!-- FIN ERP CLOUD -->
                    @case('65')
                        <div class="form-group row" id="subencabezado_{{ $keys }}">
                            <div class="col-md-12" id="campodeaccion_{{ $keys }}"></div>
                            <div class="col-md-6">
                                <label for="altabajacambio_{{ $keys }}">Tipo de Solicitud:</label>
                                <select name="aplicaciones[{{ $keys }}][altabajacambio_{{ $keys }}]" id="altabajacambio_{{ $keys }}" class="form-control campo-requerido tiposolicitudselect">
                                    <option value="">Seleccione...</option>
                                    <option value="a">Alta</option>
                                    <option value="b">Baja</option>
                                    <option value="c">Cambio</option>
                                </select>
                            </div>
                            <div class="col-md-5">
                                <label for="perfil_{{ $keys }}">Perfiles: </label>
                                <select name="perfil_{{ $keys }}" class="form-control" id="perfil_{{ $keys }}">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <label>&nbsp;</label>
                                <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenPerfil_{{ $keys }}]" id="hiddenPerfil_{{ $keys }}" class="campo-requerido" />
                                <button id="addperfil_{{ $keys }}" type="button" class="form-control btn btn-primary btn-add"><i class="fas fa-plus"></i></button>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div id="content_list_perfil_{{ $keys }}" class="col-md-12"></div>

                            <div class="col-md-12">
                                <ul id="list_perfil_{{ $keys }}">
                                </ul>
                            </div>
                        </div>
                        @break

                    @case('1027')
                        <div class="form-group row" id="subencabezado_{{ $keys }}">
                            <div class="col-md-12" id="campodeaccion_{{ $keys }}"></div>
                            <div class="col-md-6">
                                <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenOtros_{{ $keys }}]" id="hiddenOtros_{{ $keys }}"/>
                                <label for="altabajacambio_{{ $keys }}">Tipo de Solicitud:</label>
                                <select name="aplicaciones[{{ $keys }}][altabajacambio_{{ $keys }}]" id="altabajacambio_{{ $keys }}" class="form-control campo-requerido tiposolicitudselect">
                                    <option value="">Seleccione...</option>
                                    <option value="a">Alta</option>
                                    <option value="b">Baja</option>
                                    <option value="c">Cambio</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="observaciones_{{ $keys }}">Observaciones: </label>
                                <textarea name="aplicaciones[{{ $keys }}][observaciones_{{ $keys }}]" id="observaciones_{{ $keys }}" class="form-control"></textarea>
                            </div>
                        </div>
                        @break
                    @case('1028')
                        <div class="form-group row" id="subencabezado_{{ $keys }}">
                            <div class="col-md-12" id="campodeaccion_{{ $keys }}"></div>
                            <div class="col-md-6">
                                <label for="altabajacambio_{{ $keys }}">Tipo de Solicitud:</label>
                                <select name="aplicaciones[{{ $keys }}][altabajacambio_{{ $keys }}]" id="altabajacambio_{{ $keys }}" class="form-control campo-requerido tiposolicitudselect">
                                    <option value="">Seleccione...</option>
                                    <option value="a">Alta</option>
                                    <option value="b">Baja</option>
                                    <option value="c">Cambio</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="portales_{{ $keys }}">Portal:</label>
                                <select name="aplicaciones[{{ $keys }}][portales_{{ $keys }}]" id="portales_{{ $keys }}" class="form-control campo-requerido portaleselect">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="perfil_{{ $keys }}">Perfil:</label>
                                <select name="aplicaciones[{{ $keys }}][perfil_{{ $keys }}]" id="perfil_{{ $keys }}" class="form-control campo-requerido">
                                    <option value="">Selecciones...</option>
                                </select>
                            </div>
                        </div>
                        @break
                    @case('22')
                        <div class="form-group row" id="subencabezado_{{ $keys }}">
                            <div class="col-md-12" id="campodeaccion_{{ $keys }}"></div>
                            <div class="col-md-6">
                                <label for="altabajacambio_{{ $keys }}">Tipo de Solicitud:</label>
                                <select name="aplicaciones[{{ $keys }}][altabajacambio_{{ $keys }}]" id="altabajacambio_{{ $keys }}" class="form-control campo-requerido tiposolicitudselect">
                                    <option value="">Seleccione...</option>
                                    <option value="a">Alta</option>
                                    <option value="b">Baja</option>
                                    <option value="c">Cambio</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="rol_{{ $keys }}">Rol: </label>
                                <select name="aplicaciones[{{ $keys }}][hiddenRol_{{ $keys }}]" class="form-control campo-requerido" id="rol_{{ $keys }}">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                            <!-- <div class="col-md-6">
                                <label for="tipogestionadaono_{{ $keys }}">Tipo (Gestionada o no gestionada):</label>
                                <select name="aplicaciones[{{ $keys }}][tipogestionadaono_{{ $keys }}]" id="tipogestionadaono_{{ $keys }}" class="form-control">
                                    <option value="">Seleccione...</option>
                                    <option value="Gestionada">Gestionada</option>
                                    <option value="No Gestionada">No Gestionada</option>
                                </select>
                            </div> -->
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="tipo_{{ $keys }}">Tipo: </label>
                                <select name="aplicaciones[{{ $keys }}][hiddenTipo_{{ $keys }}]" class="form-control" id="tipo_{{ $keys }}">
                                    <option value="">Seleccione...</option>
                                    <option value="Gestionado">Gestionado</option>
                                    <option value="No Gestionado">No gestionado</option>
                                </select>
                            </div>
                        </div>
                        <!-- <div class="form-group row">
                            <div class="col-md-11">
                                <label for="rol_{{ $keys }}">Rol: </label>
                                <select name="rol_{{ $keys }}" class="form-control" id="rol_{{ $keys }}">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <label>&nbsp;</label>
                                <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenRol_{{ $keys }}]" id="hiddenRol_{{ $keys }}" class="campo-requerido" />
                                <button id="addrol_{{ $keys }}" type="button" class="form-control btn btn-primary btn-add"><i class="fas fa-plus"></i></button>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div id="content_list_rol_{{ $keys }}" class="col-md-12"></div>

                            <div class="col-md-12">
                                <ul id="list_rol_{{ $keys }}">
                                </ul>
                            </div>
                        </div> -->
                        @break
                    @case('23')
                        <div class="form-group row" id="subencabezado_{{ $keys }}">
                            <div class="col-md-12" id="campodeaccion_{{ $keys }}"></div>
                            <div class="col-md-12">
                                <label for="altabajacambio_{{ $keys }}">Tipo de Solicitud:</label>
                                <select name="aplicaciones[{{ $keys }}][altabajacambio_{{ $keys }}]" id="altabajacambio_{{ $keys }}" class="form-control campo-requerido tiposolicitudselect">
                                    <option value="">Seleccione...</option>
                                    <option value="a">Alta</option>
                                    <option value="b">Baja</option>
                                    <option value="c">Cambio</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-5">
                                <label for="grupo_{{ $keys }}">Grupo: </label>
                                <select name="grupo_{{ $keys }}" class="form-control" id="grupo_{{ $keys }}">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <label>&nbsp;</label>
                                <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenGrupo_{{ $keys }}]" id="hiddenGrupo_{{ $keys }}" class="campo-requerido" />
                                <button id="addgrupo_{{ $keys }}" type="button" class="form-control btn btn-primary btn-add"><i class="fas fa-plus"></i></button>
                            </div>
                            <div class="col-md-5">
                                <label for="aplicacion_{{ $keys }}">Aplicación: </label>
                                <select name="aplicacion_{{ $keys }}" class="form-control" id="aplicacion_{{ $keys }}">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <label>&nbsp;</label>
                                <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenAplicacion_{{ $keys }}]" id="hiddenAplicacion_{{ $keys }}" class="campo-requerido" />
                                <button id="addapp_{{ $keys }}" type="button" class="form-control btn btn-primary btn-add"><i class="fas fa-plus"></i></button>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div id="content_list_grupo_{{ $keys }}" class="col-md-12"></div>

                                    <div class="col-md-12">
                                        <ul id="list_grupo_{{ $keys }}">
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div id="content_list_aplicacion_{{ $keys }}" class="col-md-12"></div>
    
                                    <div class="col-md-12">
                                        <ul id="list_aplicacion_{{ $keys }}">
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @break
                    @case('24')
                        <div class="form-group row" id="subencabezado_{{ $keys }}">
                            <div class="col-md-12" id="campodeaccion_{{ $keys }}"></div>
                            <div class="col-md-12">
                                <label for="altabajacambio_{{ $keys }}">Tipo de Solicitud:</label>
                                <select name="aplicaciones[{{ $keys }}][altabajacambio_{{ $keys }}]" id="altabajacambio_{{ $keys }}" class="form-control campo-requerido tiposolicitudselect">
                                    <option value="">Seleccione...</option>
                                    <option value="a">Alta</option>
                                    <option value="b">Baja</option>
                                    <option value="c">Cambio</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-5">
                                <label for="aplicacion_{{ $keys }}">Aplicación: </label>
                                <select name="aplicacion_{{ $keys }}" class="form-control" id="aplicacion_{{ $keys }}">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <label>&nbsp;</label>
                                <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenAplicacion_{{ $keys }}]" id="hiddenAplicacion_{{ $keys }}" class="campo-requerido" />
                                <button id="addapp_{{ $keys }}" type="button" class="form-control btn btn-primary btn-add"><i class="fas fa-plus"></i></button>
                            </div>
                            <div class="col-md-5">
                                <label for="grupo_{{ $keys }}">Grupo: </label>
                                <select name="grupo_{{ $keys }}" class="form-control" id="grupo_{{ $keys }}">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <label>&nbsp;</label>
                                <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenGrupo_{{ $keys }}]" id="hiddenGrupo_{{ $keys }}" class="campo-requerido" />
                                <button id="addgrupo_{{ $keys }}" type="button" class="form-control btn btn-primary btn-add"><i class="fas fa-plus"></i></button>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div id="content_list_aplicacion_{{ $keys }}" class="col-md-12"></div>
    
                                    <div class="col-md-12">
                                        <ul id="list_aplicacion_{{ $keys }}">
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div id="content_list_grupo_{{ $keys }}" class="col-md-12"></div>

                                    <div class="col-md-12">
                                        <ul id="list_grupo_{{ $keys }}">
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @break
                    @case('1024')
                        <div class="form-group row" id="subencabezado_{{ $keys }}">
                            <div class="col-md-12" id="campodeaccion_{{ $keys }}"></div>
                            <div class="col-md-6">
                                <label for="altabajacambio_{{ $keys }}">Tipo de Solicitud:</label>
                                <select name="aplicaciones[{{ $keys }}][altabajacambio_{{ $keys }}]" id="altabajacambio_{{ $keys }}" class="form-control campo-requerido tiposolicitudselect">
                                    <option value="">Seleccione...</option>
                                    <option value="a">Alta</option>
                                    <option value="b">Baja</option>
                                    <option value="c">Cambio</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="laboraselect_{{ $keys }}">Aplicativo:</label>
                                <select name="aplicaciones[{{ $keys }}][aplicacion_{{ $keys }}]" id="aplicacion_{{ $keys }}" class="form-control campo-requerido">
                                    <option value="">Seleccione...</option>
                                    <!-- <option value="Televisa">Televisa</option>
                                    <option value="Telecom">Telecom</option>
                                    <option value="Siho">Siho</option> -->
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="perfil_{{ $keys }}">Perfiles: </label>
                                <input type="text" name="aplicaciones[{{ $keys }}][hiddenPerfil_{{ $keys }}]" class="form-control" id="perfil_{{ $keys }}">
                                <!-- <select name="perfil_{{ $keys }}" class="form-control" id="perfil_{{ $keys }}">
                                    <option value="">Seleccione...</option>
                                </select> -->
                            </div>
                           <!--  <div class="col-md-1">
                                <label>&nbsp;</label>
                                <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenPerfil_{{ $keys }}]" id="hiddenPerfil_{{ $keys }}" class="campo-requerido" />
                                <button id="addperfil_{{ $keys }}" type="button" class="form-control btn btn-primary btn-add"><i class="fas fa-plus"></i></button>
                            </div> -->
                        </div>
                        <!-- <div class="form-group row">
                            <div id="content_list_perfil_{{ $keys }}" class="col-md-12"></div>

                            <div class="col-md-12">
                                <ul id="list_perfil_{{ $keys }}">
                                </ul>
                            </div>
                        </div> -->
                        @break
                    @case('27')
                        <div class="form-group row" id="subencabezado_{{ $keys }}">
                            <div class="col-md-12" id="campodeaccion_{{ $keys }}"></div>
                            <div class="col-md-6">
                                <!-- <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenOtros_{{ $keys }}]" id="hiddenOtros_{{ $keys }}"/> -->
                                <label for="altabajacambio_{{ $keys }}">Tipo de Solicitud:</label>
                                <select name="aplicaciones[{{ $keys }}][altabajacambio_{{ $keys }}]" id="altabajacambio_{{ $keys }}" class="form-control campo-requerido tiposolicitudselect">
                                    <option value="">Seleccione...</option>
                                    <option value="a">Alta</option>
                                    <option value="b">Baja</option>
                                    <option value="c">Cambio</option>
                                </select>
                            </div>
                            <div class="col-md-5">
                                <label for="instancia_{{ $keys }}">Instancia: </label>
                                <select name="instancia_{{ $keys }}" class="form-control" id="instancia_{{ $keys }}">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <label>&nbsp;</label>
                                <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenInstancia_{{ $keys }}]" id="hiddenInstancia_{{ $keys }}" class="campo-requerido" />
                                <button id="addinstancia_{{ $keys }}" type="button" class="form-control btn btn-primary btn-add"><i class="fas fa-plus"></i></button>
                            </div>
                            <!-- <div class="col-md-6">
                                <label for="nivelesdeseguridad_{{ $keys }}">Niveles de Seguridad:</label>
                                <input type="text" name="aplicaciones[{{ $keys }}][nivelesdeseguridad_{{ $keys }}]" id="nivelesdeseguridad_{{ $keys }}" class="form-control"/>
                            </div> -->
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="nivelesdeseguridad_{{ $keys }}">Nivel de seguridad: </label>
                                <select name="aplicaciones[{{ $keys }}][nivelesdeseguridad_{{ $keys }}]" class="form-control" id="nivelesdeseguridad_{{ $keys }}">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="departamento_{{ $keys }}">Departamento:</label>
                                <input type="text" name="aplicaciones[{{ $keys }}][departamento_{{ $keys }}]" id="departamento_{{ $keys }}" class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="localidadesdepago_{{ $keys }}">Localidades de pago:</label>
                                <input type="text" name="aplicaciones[{{ $keys }}][localidadesdepago_{{ $keys }}]" id="localidadesdepago_{{ $keys }}" class="form-control"/>
                            </div>
                            <div class="col-md-6">
                                <label for="menu_{{ $keys }}">Menú:</label>
                                <input type="text" name="aplicaciones[{{ $keys }}][menu_{{ $keys }}]" id="menu_{{ $keys }}" class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="conceptos_{{ $keys }}">Conceptos:</label>
                                <input type="text" name="aplicaciones[{{ $keys }}][conceptos_{{ $keys }}]" id="conceptos_{{ $keys }}" class="form-control"/>
                            </div>
                            <div class="col-md-6">
                                <label for="procesos_{{ $keys }}">Procesos:</label>
                                <input type="text" name="aplicaciones[{{ $keys }}][procesos_{{ $keys }}]" id="procesos_{{ $keys }}" class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="otrostxt_{{ $keys }}">Otros:</label>
                                <textarea name="aplicaciones[{{ $keys }}][otrostxt_{{ $keys }}]" id="otrostxt_{{ $keys }}" class="form-control"></textarea>
                                <!-- <input type="text" name="aplicaciones[{{ $keys }}][otrostxt_{{ $keys }}]" id="otrostxt_{{ $keys }}" class="form-control"/> -->
                            </div>
                        </div>
                        <div class="form-group row">
                            <div id="content_list_instancia_{{ $keys }}" class="col-md-12"></div>

                            <div class="col-md-12">
                                <ul id="list_instancia_{{ $keys }}">
                                </ul>
                            </div>
                        </div>
                        @break
                    @case('1009')
                        <div class="form-group row" id="subencabezado_{{ $keys }}">
                            <div class="col-md-12" id="campodeaccion_{{ $keys }}"></div>
                            <div class="col-md-12">
                                <label for="altabajacambio_{{ $keys }}">Tipo de Solicitud:</label>
                                <select name="aplicaciones[{{ $keys }}][altabajacambio_{{ $keys }}]" id="altabajacambio_{{ $keys }}" class="form-control campo-requerido tiposolicitudselect">
                                    <option value="">Seleccione...</option>
                                    <option value="a">Alta</option>
                                    <option value="b">Baja</option>
                                    <option value="c">Cambio</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="perfi_sos">Activar perfil SOS</label>
                               <input type="checkbox" name="aplicaciones[{{ $keys }}][perfi_sos_{{ $keys }}]" id="perfi_sos" class="form-control checkboxPerfil"> 
                            </div>
                            <div class="col-md-12"> 
                                <label for="perfiles_{{ $keys }}">Perfiles:</label>
                                <select name="aplicaciones[{{ $keys }}][perfiles_{{ $keys }}]" class="form-control campo-requerido" id="perfiles_{{ $keys }}">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="idred_{{ $keys }}">ID de red (Name):</label>
                                <input type="text" name="aplicaciones[{{ $keys }}][idred_{{ $keys }}]" id="idred_{{ $keys }}" class="form-control"/>
                            </div>
                            <div class="col-md-6">
                                <label for="nombrejefeinmediato_{{ $keys }}">Nombre completo del jefe inmediato:</label>
                                <input type="text" name="aplicaciones[{{ $keys }}][nombrejefeinmediato_{{ $keys }}]" id="nombrejefeinmediato_{{ $keys }}" class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="ubicacionlaboral_{{ $keys }}">Ubicación laboral (Location):</label>
                                <select name="aplicaciones[{{ $keys }}][ubicacionlaboral_{{ $keys }}]" id="ubicacionlaboral_{{ $keys }}" class="form-control">
                                    <option value="">Seleccione...</option>
                                    <!-- <option value="Chapultepec">Chapultepec</option>
                                    <option value="San Ángel">San Ángel</option>
                                    <option value="Santa Fé">Santa Fé</option> -->
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12"><p class="text-center"><strong>Llenar el siguiente apartado solo en caso de requerir un perfil SOS:</strong></p></div>
                            <div class="col-md-12">
                                <label for="perfillandmarksales_{{ $keys }}">Seleccionar perfil por asignar:</label>
                                <select name="aplicaciones[{{ $keys }}][perfillandmarksales_{{ $keys }}]" id="perfillandmarksales_{{ $keys }}" class="form-control" disabled>
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="motivoasignacion_{{ $keys }}">Motivo de asignación:</label>
                                <input type="text" name="aplicaciones[{{ $keys }}][motivoasignacion_{{ $keys }}]" id="motivoasignacion_{{ $keys }}" class="form-control" disabled/>
                            </div>
                            <div class="col-md-6">
                                <label for="fechaasignacion_{{ $keys }}">Fecha de asignación:</label>
                                <input type="date" name="aplicaciones[{{ $keys }}][fechaasignacion_{{ $keys }}]" id="fechaasignacion_{{ $keys }}" class="form-control" disabled/>
                            </div>
                            <div class="col-md-12"><small><em>(Nota: La vigencia del perfil emergente es de 24hrs. como máximo posterior a la fecha de asignación)</em></small></div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="otrostxt_{{ $keys }}">Otros:</label>
                                <textarea name="aplicaciones[{{ $keys }}][otrostxt_{{ $keys }}]" id="otrostxt_{{ $keys }}" class="form-control"></textarea>
                                <!-- <input type="text" name="aplicaciones[{{ $keys }}][otrostxt_{{ $keys }}]" id="otrostxt_{{ $keys }}" class="form-control"/> -->
                            </div>
                        </div>
                        @break
                    @case('31')
                        <div class="form-group row" id="subencabezado_{{ $keys }}">
                            <div class="col-md-12" id="campodeaccion_{{ $keys }}"></div>
                            <div class="col-md-6">
                                <label for="altabajacambio_{{ $keys }}">Tipo de Solicitud:</label>
                                <select name="aplicaciones[{{ $keys }}][altabajacambio_{{ $keys }}]" id="altabajacambio_{{ $keys }}" class="form-control campo-requerido tiposolicitudselect">
                                    <option value="">Seleccione...</option>
                                    <option value="a">Alta</option>
                                    <option value="b">Baja</option>
                                    <option value="c">Cambio</option>
                                </select>
                            </div>
                            <div class="col-md-5">
                                <label for="grupoeditorial_{{ $keys }}">Grupo Editorial: </label>
                                <select name="grupoeditorial_{{ $keys }}" class="form-control" id="grupoeditorial_{{ $keys }}">
                                    <option value="">Seleccione...</option>
                                </select>
                                <!--<input type="text" name="aplicaciones[{{ $keys }}][equipoeditorial_{{ $keys }}]" id="equipoeditorial_{{ $keys }}" class="form-control"/>-->
                            </div>
                            <div class="col-md-1">
                                <label>&nbsp;</label>
                                <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenGrupoeditorial_{{ $keys }}]" id ="hiddenGrupoeditorial_{{ $keys }}" class="campo-requerido" />
                                <button id="addgrupoeditorial_{{ $keys }}" type="button" class="form-control btn btn-primary btn-add"><i class="fas fa-plus"></i></button>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-5">
                                <label for="perfil_{{ $keys }}">Perfiles: </label>
                                <select name="perfil_{{ $keys }}" class="form-control" id="perfil_{{ $keys }}">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <label>&nbsp;</label>
                                <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenPerfil_{{ $keys }}]" id="hiddenPerfil_{{ $keys }}" class="campo-requerido" />
                                <button id="addperfil_{{ $keys }}" type="button" class="form-control btn btn-primary btn-add"><i class="fas fa-plus"></i></button>
                            </div>
                            <div class="col-md-5">
                                <label for="rol_{{ $keys }}">Roles</label>
                                <select name="rol_{{ $keys }}" class="form-control" id="rol_{{ $keys }}">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <label>&nbsp;</label>
                                <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenRol_{{ $keys }}]" id="hiddenRol_{{ $keys }}" class="campo-requerido">
                                <button id="addrol_{{ $keys }}" type="button" class="form-control btn btn-primary btn-add"><i class="fas fa-plus"></i></button>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div id="content_list_grupo_editorial_{{ $keys }}" class="col-md-12"></div>
                                <div class="col-md-12">
                                    <ul id="list_grupo_editorial_{{ $keys }}">
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div id="content_list_perfil_{{ $keys }}" class="col-md-12"></div>
                                <div class="col-md-12">
                                    <ul id="list_perfil_{{ $keys }}">
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div id="content_list_rol_{{ $keys }}" class="col-md-12"></div>
                                <div class="col-md-12">
                                    <ul id="list_rol_{{ $keys }}">
                                    </ul>
                                </div>
                            </div>
                        </div>
                        @break
                    @case('32')
                        <div class="form-group row" id="subencabezado_{{ $keys }}">
                            <div class="col-md-12" id="campodeaccion_{{ $keys }}"></div>
                            <div class="col-md-6">
                                <label for="altabajacambio_{{ $keys }}">Tipo de Solicitud:</label>
                                <select name="aplicaciones[{{ $keys }}][altabajacambio_{{ $keys }}]" id="altabajacambio_{{ $keys }}" class="form-control campo-requerido tiposolicitudselect">
                                    <option value="">Seleccione...</option>
                                    <option value="a">Alta</option>
                                    <option value="b">Baja</option>
                                    <option value="c">Cambio</option>
                                </select>
                            </div>
                            <div class="col-md-5">
                                <label for="perfil_{{ $keys }}">Perfiles: </label>
                                <select name="perfil_{{ $keys }}" class="form-control" id="perfil_{{ $keys }}">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <label>&nbsp;</label>
                                <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenPerfil_{{ $keys }}]" id="hiddenPerfil_{{ $keys }}" class="campo-requerido" />
                                <button id="addperfil_{{ $keys }}" type="button" class="form-control btn btn-primary btn-add"><i class="fas fa-plus"></i></button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-11"> 
                                <label for="empresa_{{ $keys }}">Empresas:</label>
                                <select name="empresa_{{ $keys }}" class="form-control" id="empresa_{{ $keys }}">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <label>&nbsp;</label>
                                <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenEmpresa_{{ $keys }}]" id="hiddenEmpresa_{{ $keys }}" />
                                <button id="addempresa_{{ $keys }}" type="button" class="form-control btn btn-primary btn-add"><i class="fas fa-plus"></i></button>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div id="content_list_perfil_{{ $keys }}" class="col-md-12"></div>
    
                                    <div class="col-md-12">
                                        <ul id="list_perfil_{{ $keys }}">
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div id="content_list_empresa_{{ $keys }}" class="col-md-12"></div>

                                    <div class="col-md-12">
                                        <ul id="list_empresa_{{ $keys }}">
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @break
                    @case('33')
                        <div class="form-group row" id="subencabezado_{{ $keys }}">
                            <div class="col-md-12" id="campodeaccion_{{ $keys }}"></div>
                            <div class="col-md-6">
                                <label for="altabajacambio_{{ $keys }}">Tipo de Solicitud:</label>
                                <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenOtros_{{ $keys }}]" id="hiddenOtros_{{ $keys }}"/>
                                <select name="aplicaciones[{{ $keys }}][altabajacambio_{{ $keys }}]" id="altabajacambio_{{ $keys }}" class="form-control campo-requerido tiposolicitudselect">
                                    <option value="">Seleccione...</option>
                                    <option value="a">Alta</option>
                                    <option value="b">Baja</option>
                                    <option value="c">Cambio</option>
                                </select>
                            </div>
                            <div class="col-md-5">
                                <label for="grupo_{{ $keys }}">Grupo: </label>
                                <select name="grupo_{{ $keys }}" class="form-control" id="grupo_{{ $keys }}">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <label>&nbsp;</label>
                                <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenGrupo_{{ $keys }}]" id="hiddenGrupo_{{ $keys }}" class="campo-requerido" />
                                <button id="addgrupo_{{ $keys }}" type="button" class="form-control btn btn-primary btn-add"><i class="fas fa-plus"></i></button>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="areadeinsercion{{ $keys }}">Área de inserción: </label>
                                <!-- <input type="text" name="aplicaciones[{{ $keys }}][areadeinsercion{{ $keys }}]" id="areadeinsercion{{ $keys }}" class="form-control"/> -->
                                <select name="aplicaciones[{{ $keys }}][areasInsercion_{{ $keys }}]" id="areasInsercion_{{ $keys }}" class="form-control">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="estado_{{ $keys }}">Estado: </label>
                                <!-- <input type="text" name="aplicaciones[{{ $keys }}][estado_{{ $keys }}]" id="estado_{{ $keys }}" class="form-control"/> -->
                                <select name="aplicaciones[{{ $keys }}][estado_{{ $keys }}]" id="estado_{{ $keys }}" class="form-control">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="tipoempleado_{{ $keys }}">Tipo de empleado: </label>
                                <!-- <input type="text" name="aplicaciones[{{ $keys }}][tipoempleado_{{ $keys }}]" id="tipoempleado_{{ $keys }}" class="form-control"/> -->
                                <select name="aplicaciones[{{ $keys }}][tipoEmpleado_{{ $keys }}]" id="tipoEmpleado_{{ $keys }}" class="form-control">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="otrotxt_{{ $keys }}">Otro: </label>
                                <input type="text" name="aplicaciones[{{ $keys }}][otrotxt_{{ $keys }}]" id="otrotxt_{{ $keys }}" class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div id="content_list_grupo_{{ $keys }}" class="col-md-12"></div>

                            <div class="col-md-12">
                                <ul id="list_grupo_{{ $keys }}">
                                </ul>
                            </div>
                        </div>
                        @break
                    @case('maximovideocine')
                        <!-- <div class="form-group row">
                            <div class="col-md-6">
                                <label for="altabajacambio_{{ $keys }}">Tipo de Solicitud:</label>
                                <select name="aplicaciones[{{ $keys }}][altabajacambio_{{ $keys }}]" id="altabajacambio_{{ $keys }}" class="form-control campo-requerido tiposolicitudselect">
                                    <option value="">Seleccione...</option>
                                    <option value="a">Alta</option>
                                    <option value="b">Baja</option>
                                    <option value="c">Cambio</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="altabajacambio_{{ $keys }}">Tipo de Usuario:</label>
                                <select name="aplicaciones[{{ $keys }}][altabajacambio_{{ $keys }}]" id="altabajacambio_{{ $keys }}" class="form-control campo-requerido tiposolicitudselect">
                                    <option value="">Seleccione...</option>
                                    <option value="a">Alta</option>
                                    <option value="b">Baja</option>
                                    <option value="c">Cambio</option>
                                </select>
                            </div>
                        </div> -->
                        @break
                    @case('1001')
                        <div class="form-group row" id="subencabezado_{{ $keys }}">
                            <div class="col-md-12" id="campodeaccion_{{ $keys }}"></div>
                            <div class="col-md-6">
                                <label for="altabajacambio_{{ $keys }}">Tipo de Solicitud:</label>
                                <select name="aplicaciones[{{ $keys }}][altabajacambio_{{ $keys }}]" id="altabajacambio_{{ $keys }}" class="form-control campo-requerido tiposolicitudselect">
                                    <option value="">Seleccione...</option>
                                    <option value="a">Alta</option>
                                    <option value="b">Baja</option>
                                    <option value="c">Cambio</option>
                                </select>
                            </div>
                            <div class="col-md-5">
                                <label for="rol_{{ $keys }}">Rol: </label>
                                <select name="rol_{{ $keys }}" class="form-control" id="rol_{{ $keys }}">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <label>&nbsp;</label>
                                <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenRol_{{ $keys }}]" id="hiddenRol_{{ $keys }}" class="campo-requerido" />
                                <button id="addrol_{{ $keys }}" type="button" class="form-control btn btn-primary btn-add"><i class="fas fa-plus"></i></button>
                            </div>
                        </div>
                        <!-- <div class="form-group row">
                            <div class="col-md-6">
                                <label for="modulos_{{ $keys }}">Módulos: </label>
                                <select name="aplicaciones[{{ $keys }}][modulos_{{ $keys }}]" class="form-control" id="modulos_{{ $keys }}">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="instancias_{{ $keys }}">Instancias: </label>
                                <select name="aplicaciones[{{ $keys }}][instancias_{{ $keys }}]" class="form-control" id="instancias_{{ $keys }}">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                        </div> -->
                        <div class="form-group row">
                            <div id="content_list_rol_{{ $keys }}" class="col-md-12"></div>

                            <div class="col-md-12">
                                <ul id="list_rol_{{ $keys }}">
                                </ul>
                            </div>
                        </div>
                        @break
                    @case('35')
                        <div class="form-group row" id="subencabezado_{{ $keys }}">
                            <div class="col-md-12" id="campodeaccion_{{ $keys }}"></div>
                            <div class="col-md-6">
                                <label for="altabajacambio_{{ $keys }}">Tipo de Solicitud:</label>
                                <select name="aplicaciones[{{ $keys }}][altabajacambio_{{ $keys }}]" id="altabajacambio_{{ $keys }}" class="form-control campo-requerido tiposolicitudselect">
                                    <option value="">Seleccione...</option>
                                    <option value="a">Alta</option>
                                    <option value="b">Baja</option>
                                    <option value="c">Cambio</option>
                                </select>
                            </div>
                            <div class="col-md-5">
                                <label for="perfil_{{ $keys }}">Perfiles: </label>
                                <select name="perfil_{{ $keys }}" class="form-control" id="perfil_{{ $keys }}">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <label>&nbsp;</label>
                                <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenPerfil_{{ $keys }}]" id="hiddenPerfil_{{ $keys }}" class="campo-requerido" />
                                <button id="addperfil_{{ $keys }}" type="button" class="form-control btn btn-primary btn-add"><i class="fas fa-plus"></i></button>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div id="content_list_perfil_{{ $keys }}" class="col-md-12"></div>

                            <div class="col-md-12">
                                <ul id="list_perfil_{{ $keys }}">
                                </ul>
                            </div>
                        </div>
                        @break
                    @case('1003')
                        <div class="form-group row" id="subencabezado_{{ $keys }}">
                            <div class="col-md-12" id="campodeaccion_{{ $keys }}"></div>
                            <div class="col-md-6">
                                <label for="altabajacambio_{{ $keys }}">Tipo de Solicitud:</label>
                                <select name="aplicaciones[{{ $keys }}][altabajacambio_{{ $keys }}]" id="altabajacambio_{{ $keys }}" class="form-control campo-requerido tiposolicitudselect">
                                    <option value="">Seleccione...</option>
                                    <option value="a">Alta</option>
                                    <option value="b">Baja</option>
                                    <option value="c">Cambio</option>
                                </select>
                            </div>
                            <div class="col-md-5">
                                <label for="grupo_{{ $keys }}">Grupo: </label>
                                <select name="grupo_{{ $keys }}" class="form-control" id="grupo_{{ $keys }}">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <label>&nbsp;</label>
                                <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenGrupo_{{ $keys }}]" id="hiddenGrupo_{{ $keys }}" class="campo-requerido" />
                                <button id="addgrupo_{{ $keys }}" type="button" class="form-control btn btn-primary btn-add"><i class="fas fa-plus"></i></button>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div id="content_list_grupo_{{ $keys }}" class="col-md-12"></div>

                            <div class="col-md-12">
                                <ul id="list_grupo_{{ $keys }}">
                                </ul>
                            </div>
                        </div>
                        @break
                    @case('8')
                        <div class="form-group row" id="subencabezado_{{ $keys }}">
                            <div class="col-md-12" id="campodeaccion_{{ $keys }}"></div>
                            <div class="col-md-6">
                                <label for="altabajacambio_{{ $keys }}">Tipo de Solicitud:</label>
                                <select name="aplicaciones[{{ $keys }}][altabajacambio_{{ $keys }}]" id="altabajacambio_{{ $keys }}" class="form-control campo-requerido tiposolicitudselect">
                                    <option value="">Seleccione...</option>
                                    <option value="a">Alta</option>
                                    <option value="b">Baja</option>
                                    <option value="c">Cambio</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="rol_{{ $keys }}">Rol: </label>
                                <select name="aplicaciones[{{ $keys }}][hiddenRol_{{ $keys }}]" class="form-control campo-requerido" id="rol_{{ $keys }}">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                            <!-- <div class="col-md-1">
                                <label>&nbsp;</label>
                                <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenRol_{{ $keys }}]" id="hiddenRol_{{ $keys }}" class="campo-requerido" />
                                <button id="addrol_{{ $keys }}" type="button" class="form-control btn btn-primary btn-add"><i class="fas fa-plus"></i></button>
                            </div> -->
                        </div>
                        <div class="form-group row">
                            <div id="content_list_rol_{{ $keys }}" class="col-md-12"></div>

                            <div class="col-md-12">
                                <ul id="list_rol_{{ $keys }}">
                                </ul>
                            </div>
                        </div>
                        @break
                    @case('37')
                    <div class="form-group row" id="subencabezado_{{ $keys }}">
                        <div class="col-md-12" id="campodeaccion_{{ $keys }}"></div>
                        <div class="col-md-6">
                            <label for="altabajacambio_{{ $keys }}">Tipo de Solicitud:</label>
                            <select name="aplicaciones[{{ $keys }}][altabajacambio_{{ $keys }}]" id="altabajacambio_{{ $keys }}" class="form-control campo-requerido tiposolicitudselect">
                                <option value="">Seleccione...</option>
                                <option value="a">Alta</option>
                                <option value="b">Baja</option>
                                <option value="c">Cambio</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="usuarioreferenciaparadigm_{{ $keys }}">Usuario de referencia:</label>  
                            <input type="text" name="aplicaciones[{{ $keys }}][usuarioreferenciaparadigm_{{ $keys }}]" id="usuarioreferenciaparadigm_{{ $keys }}" class="form-control">
                            <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenAreasForParadigm_{{ $keys }}]" class="form-control" id="hiddenAreasForParadigm_{{ $keys }}">                              
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-5">
                            <label for="canalesparadigm_{{ $keys }}">Canales:</label>
                            <input type="button" value="Todos" class="btn btn-primary btn-selected-all" data-id="canalesparadigm_{{ $keys }}">
                            <select multiple name="canalesparadigm_{{ $keys }}" class="form-control" id="canalesparadigm_{{ $keys }}"></select>
                        </div>
                        <div class=" col-md-1">
                            <label>&nbsp;</label>
                            <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenCanalesparadigm_{{ $keys }}]" id="hiddenCanalesparadigm_{{ $keys }}">
                            <button id="addcanalesparadigm_{{ $keys }}" type="button" class="form-control btn btn-primary btn-add"><i class="fas fa-plus" ></i></button>
                        </div>
                        <div class="col-md-6">
                            <label for="areasforparadigm_{{ $keys }}">Áreas:</label>
                            <select name="aplicaciones[{{ $keys }}][areasforparadigm_{{ $keys }}]" class="form-control campo-requerido select-add" id="areasforparadigm_{{ $keys }}">
                                <option value="">Seleccione...</option>
                            </select>                          
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12" >
                            <label for="otrostxt_{{ $keys }}">Otros:</label>
                            <textarea class="form-control" name="aplicaciones[{{ $keys }}][otrostxt_{{ $keys }}]" id="otrostxt_{{ $keys }}"></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-12" id="content_list_canalesparadigm_{{ $keys }}"></div>
                                <div class="col-md-12 divselectmultiple">
                                    <ul id="list_canalesparadigm_{{ $keys }}"></ul>
                                </div>
                            </div>
                        </div>
                    </div>
                        @break
                    @case('39')
                        <div class="form-group row" id="subencabezado_{{ $keys }}">
                            <div class="col-md-12" id="campodeaccion_{{ $keys }}"></div>
                            <div class="col-md-6">
                                <label for="altabajacambio_{{ $keys }}">Tipo de Solicitud:</label>
                                <select name="aplicaciones[{{ $keys }}][altabajacambio_{{ $keys }}]" id="altabajacambio_{{ $keys }}" class="form-control campo-requerido tiposolicitudselect">
                                    <option value="">Seleccione...</option>
                                    <option value="a">Alta</option>
                                    <option value="b">Baja</option>
                                    <option value="c">Cambio</option>
                                </select>
                            </div>
                            <div class="col-md-5">
                                <label for="rol_{{ $keys }}">Rol: </label>
                                <select name="rol_{{ $keys }}" class="form-control" id="rol_{{ $keys }}">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <label>&nbsp;</label>
                                <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenRol_{{ $keys }}]" id="hiddenRol_{{ $keys }}" class="campo-requerido" />
                                <button id="addrol_{{ $keys }}" type="button" class="form-control btn btn-primary btn-add"><i class="fas fa-plus"></i></button>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-5">    
                                <label for="tipocaso_{{ $keys }}">Tipo de caso: </label>
                                <select name="tipocaso_{{ $keys }}" class="form-control" id="tipocaso_{{ $keys }}">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <label>&nbsp;</label>
                                <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenTipocaso_{{ $keys }}]" id="hiddenTipocaso_{{ $keys }}" class="campo-requerido" />
                                <button id="addtipocaso_{{ $keys }}" type="button" class="form-control btn btn-primary btn-add"><i class="fas fa-plus"></i></button>
                            </div>

                            <div class="col-md-5">    
                                <label for="equipo_{{ $keys }}">Equipo: </label>
                                <select name="equipo_{{ $keys }}" class="form-control" id="equipo_{{ $keys }}">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <label>&nbsp;</label>
                                <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenEquipo_{{ $keys }}]" id="hiddenEquipo_{{ $keys }}" class="campo-requerido" />
                                <button id="addequipo_{{ $keys }}" type="button" class="form-control btn btn-primary btn-add"><i class="fas fa-plus"></i></button>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="grupo_{{ $keys }}">Grupo: </label>
                                <select name="aplicaciones[{{ $keys }}][grupo_{{ $keys }}]" id="grupo_{{ $keys }}" class="form-control campo-requerido">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div id="content_list_rol_{{ $keys }}" class="col-md-12"></div>

                                    <div class="col-md-12">
                                        <ul id="list_rol_{{ $keys }}">
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div id="content_list_tipocaso_{{ $keys }}" class="col-md-12"></div>

                                    <div class="col-md-12">
                                        <ul id="list_tipocaso_{{ $keys }}">
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div id="content_list_equipo_{{ $keys }}" class="col-md-12"></div>

                                    <div class="col-md-12">
                                        <ul id="list_equipo_{{ $keys }}">
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @break
                    @case('38')
                        <div class="form-group row" id="subencabezado_{{ $keys }}">
                            <div class="col-md-12" id="campodeaccion_{{ $keys }}"></div>
                            <div class="col-md-6">
                                <label for="altabajacambio_{{ $keys }}">Tipo de Solicitud:</label>
                                <select name="aplicaciones[{{ $keys }}][altabajacambio_{{ $keys }}]" id="altabajacambio_{{ $keys }}" class="form-control campo-requerido tiposolicitudselect">
                                    <option value="">Seleccione...</option>
                                    <option value="a">Alta</option>
                                    <option value="b">Baja</option>
                                    <option value="c">Cambio</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="rol_{{ $keys }}">Rol: </label>
                                <select name="aplicaciones[{{ $keys }}][hiddenRol_{{ $keys }}]" class="form-control campo-requerido" id="rol_{{ $keys }}">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                           <!--  <div class="col-md-1">
                                <label>&nbsp;</label>
                                <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenRol_{{ $keys }}]" id="hiddenRol_{{ $keys }}" class="campo-requerido" />
                                <button id="addrol_{{ $keys }}" type="button" class="form-control btn btn-primary btn-add"><i class="fas fa-plus"></i></button>
                             </div>-->
                        </div>
                       <!--  <div class="form-group row">
                            <div id="content_list_rol_{{ $keys }}" class="col-md-12"></div>

                            <div class="col-md-12">
                                <ul id="list_rol_{{ $keys }}">
                                </ul>
                            </div>
                        </div> -->
                        @break
                    @case('40')
                        <div class="form-group row" id="subencabezado_{{ $keys }}">
                            <div class="col-md-12" id="campodeaccion_{{ $keys }}"></div>
                            <div class="col-md-6">
                                <label for="altabajacambio_{{ $keys }}">Tipo de Solicitud:</label>
                                <select name="aplicaciones[{{ $keys }}][altabajacambio_{{ $keys }}]" id="altabajacambio_{{ $keys }}" class="form-control campo-requerido tiposolicitudselect">
                                    <option value="">Seleccione...</option>
                                    <option value="a">Alta</option>
                                    <option value="b">Baja</option>
                                    <option value="c">Cambio</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="rol_{{ $keys }}">Rol: </label>
                                <select name="aplicaciones[{{ $keys }}][hiddenRol_{{ $keys }}]" class="form-control campo-requerido" id="rol_{{ $keys }}">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                            <!-- <div class="col-md-1">
                                <label>&nbsp;</label>
                                <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenRol_{{ $keys }}]" id="hiddenRol_{{ $keys }}" class="campo-requerido" />
                                <button id="addrol_{{ $keys }}" type="button" class="form-control btn btn-primary btn-add"><i class="fas fa-plus"></i></button>
                            </div> -->
                        </div>
                        <!-- <div class="form-group row">
                            <div id="content_list_rol_{{ $keys }}" class="col-md-12"></div>

                            <div class="col-md-12">
                                <ul id="list_rol_{{ $keys }}">
                                </ul>
                            </div>
                        </div> -->
                        @break
                    @case('1004')
                        <div class="form-group row" id="subencabezado_{{ $keys }}">
                            <div class="col-md-12" id="campodeaccion_{{ $keys }}"></div>
                            <div class="col-md-6">
                                <label for="altabajacambio_{{ $keys }}">Tipo de Solicitud:</label>
                                <select name="aplicaciones[{{ $keys }}][altabajacambio_{{ $keys }}]" id="altabajacambio_{{ $keys }}" class="form-control campo-requerido tiposolicitudselect">
                                    <option value="">Seleccione...</option>
                                    <option value="a">Alta</option>
                                    <option value="b">Baja</option>
                                    <option value="c">Cambio</option>
                                </select>
                            </div>
                            <div class="col-md-5">
                                <label for="perfil_{{ $keys }}">Perfiles: </label>
                                <select name="aplicaciones[{{ $keys }}][hiddenPerfil_{{ $keys }}]" class="form-control campo-requerido" id="perfil_{{ $keys }}">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                            <!-- <div class="col-md-1">
                                <label>&nbsp;</label>
                                <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenPerfil_{{ $keys }}]" id="hiddenPerfil_{{ $keys }}" class="campo-requerido" />
                                <button id="addperfil_{{ $keys }}" type="button" class="form-control btn btn-primary btn-add"><i class="fas fa-plus"></i></button>
                            </div> -->
                        </div>
                        <div class="form-group row">
                            <div class="col-md-11">
                                <label for="notificaciones_{{ $keys }}">Notificaciones:</label>
                                <select name="notificacion_{{ $keys }}}" id="notificacion_{{ $keys }}" class="form-control">
                                    <option>Seleccione...</option>
                                </select>
                            </div> 
                            <div class="col-md-1">
                                <label>&nbsp;</label>
                                <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenNotificacion_{{ $keys }}]" id="hiddenNotificacion_{{ $keys }}" class="campo-requerido" />
                                <button id="addnotificacion_{{ $keys }}" type="button" class="form-control btn btn-primary btn-add"><i class="fas fa-plus"></i></button>
                        </div>
                        <div class="form-group row">
                            <div id="content_list_notificacion_{{ $keys }}" class="col-md-12"></div>

                            <div class="col-md-12">
                                <ul id="list_notificacion_{{ $keys }}">
                                </ul>
                            </div>
                        </div>
                        @break
                    @case('41')
                        <div class="form-group row" id="subencabezado_{{ $keys }}">
                            <div class="col-md-12" id="campodeaccion_{{ $keys }}"></div>
                            <div class="col-md-6">
                                <!-- <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenOtros_{{ $keys }}]" id="hiddenOtros_{{ $keys }}"/> -->
                                <label for="altabajacambio_{{ $keys }}">Tipo de Solicitud:</label>
                                <select name="aplicaciones[{{ $keys }}][altabajacambio_{{ $keys }}]" id="altabajacambio_{{ $keys }}" class="form-control campo-requerido tiposolicitudselect">
                                    <option value="">Seleccione...</option>
                                    <option value="a">Alta</option>
                                    <option value="b">Baja</option>
                                    <option value="c">Cambio</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="tipo_{{ $keys }}">Salas:</label>
                                <select name="aplicaciones[{{ $keys }}][sala_{{ $keys }}]" id="sala_{{ $keys }}" class="form-control campo-requerido salaselect" data-app="{{ $keys }}">
                                    <option value="">Seleccione...</option>                                    
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="tipo_{{ $keys }}">Perfil:</label>
                                <select name="aplicaciones[{{ $keys }}][perfil_{{ $keys }}]" id="perfil_{{ $keys }}" class="form-control campo-requerido" data-app="{{ $keys }}">
                                    <option value="">Seleccione...</option>
                                    <!-- <option value="Cajero">Cajero</option>
                                    <option value="Capitán">Capitán</option>
                                    <option value="Mesero">Mesero</option>
                                    <option value="Gerente">Gerente</option>
                                    <option value="RH">RH</option>
                                    <option value="Otro">Otro</option> -->
                                </select>
                            </div>
                        </div>
                        <!-- <div class="form-group row">
                            <div class="col-md-12">
                                <label for="descripcionotro_{{ $keys }}">En caso de seleccionar otro: </label>
                                <input disabled type="text" name="aplicaciones[{{ $keys }}][descripcionotro_{{ $keys }}]" id="descripcionotro_{{ $keys }}" class="form-control"/>
                            </div>
                        </div> -->
                        @break
                    @case('1007')
                        <div class="form-group row" id="subencabezado_{{ $keys }}">
                            <div class="col-md-12" id="campodeaccion_{{ $keys }}"></div>
                            <div class="col-md-6">
                                <label for="altabajacambio_{{ $keys }}">Tipo de Solicitud:</label>
                                <select name="aplicaciones[{{ $keys }}][altabajacambio_{{ $keys }}]" id="altabajacambio_{{ $keys }}" class="form-control campo-requerido tiposolicitudselect">
                                    <option value="">Seleccione...</option>
                                    <option value="a">Alta</option>
                                    <option value="b">Baja</option>
                                    <option value="c">Cambio</option>
                                </select>
                            </div>
                            <div class="col-md-5">
                                <label for="grupo_{{ $keys }}">Grupo: </label>
                                <select name="grupo_{{ $keys }}" class="form-control" id="grupo_{{ $keys }}">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <label>&nbsp;</label>
                                <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenGrupo_{{ $keys }}]" id="hiddenGrupo_{{ $keys }}" class="campo-requerido" />
                                <button id="addgrupo_{{ $keys }}" type="button" class="form-control btn btn-primary btn-add"><i class="fas fa-plus"></i></button>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div id="content_list_grupo_{{ $keys }}" class="col-md-12"></div>

                            <div class="col-md-12">
                                <ul id="list_grupo_{{ $keys }}">
                                </ul>
                            </div>
                        </div>
                        @break
                    @case('44')
                        <div class="form-group row" id="subencabezado_{{ $keys }}">
                            <div class="col-md-12" id="campodeaccion_{{ $keys }}"></div>
                            <div class="col-md-6">
                                <label for="altabajacambio_{{ $keys }}">Tipo de Solicitud:</label>
                                <select name="aplicaciones[{{ $keys }}][altabajacambio_{{ $keys }}]" id="altabajacambio_{{ $keys }}" class="form-control campo-requerido tiposolicitudselect">
                                    <option value="">Seleccione...</option>
                                    <option value="a">Alta</option>
                                    <option value="b">Baja</option>
                                    <option value="c">Cambio</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="rol_{{ $keys }}">Rol: </label>
                                <select name="aplicaciones[{{ $keys }}][hiddenRol_{{ $keys }}]" class="form-control campo-requerido" id="rol_{{ $keys }}">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                            <!-- <div class="col-md-1">
                                <label>&nbsp;</label>
                                <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenRol_{{ $keys }}]" id="hiddenRol_{{ $keys }}" class="campo-requerido" />
                                <button id="addrol_{{ $keys }}" type="button" class="form-control btn btn-primary btn-add"><i class="fas fa-plus"></i></button>
                            </div> -->
                        </div>
                       <!--  <div class="form-group row">
                            <div id="content_list_rol_{{ $keys }}" class="col-md-12"></div>

                            <div class="col-md-12">
                                <ul id="list_rol_{{ $keys }}">
                                </ul>
                            </div>
                        </div> -->
                        @break
                    @case('43')
                        <div class="form-group row" id="subencabezado_{{ $keys }}">
                            <div class="col-md-12" id="campodeaccion_{{ $keys }}"></div>
                            <div class="col-md-12">
                                <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenOtros_{{ $keys }}]" id="hiddenOtros_{{ $keys }}"/>
                                <label for="altabajacambio_{{ $keys }}">Tipo de Solicitud:</label>
                                <select name="aplicaciones[{{ $keys }}][altabajacambio_{{ $keys }}]" id="altabajacambio_{{ $keys }}" class="form-control campo-requerido tiposolicitudselect">
                                    <option value="">Seleccione...</option>
                                    <option value="a">Alta</option>
                                    <option value="b">Baja</option>
                                    <option value="c">Cambio</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-5"> 
                                <label for="permisos_{{ $keys }}">Tipo de permisos:</label>
                                <select name="permisos_{{ $keys }}" class="form-control" id="permisos_{{ $keys }}">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <label>&nbsp;</label>
                                <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenPermisos_{{ $keys }}]" id="hiddenPermisos_{{ $keys }}" class="campo-requerido" />
                                <button id="addpermisos_{{ $keys }}" type="button" class="form-control btn btn-primary btn-add"><i class="fas fa-plus"></i></button>
                            </div>
                            <div class="col-md-5"> 
                                <label for="portafolios_{{ $keys }}">Portafolios:</label>
                                <select name="portafolios_{{ $keys }}" class="form-control" id="portafolios_{{ $keys }}">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <label>&nbsp;</label>
                                <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenPortafolios_{{ $keys }}]" id="hiddenPortafolios_{{ $keys }}" class="campo-requerido" />
                                <button id="addportafolios_{{ $keys }}" type="button" class="form-control btn btn-primary btn-add"><i class="fas fa-plus"></i></button>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div id="content_list_permisos_{{ $keys }}" class="col-md-12"></div>

                                    <div class="col-md-12">
                                        <ul id="list_permisos_{{ $keys }}">
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div id="content_list_portafolios_{{ $keys }}" class="col-md-12"></div>

                                    <div class="col-md-12">
                                        <ul id="list_portafolios_{{ $keys }}">
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @break
                    @case('45')
                        <div class="form-group row" id="subencabezado_{{ $keys }}">
                            <div class="col-md-12" id="campodeaccion_{{ $keys }}"></div>
                            <div class="col-md-6">
                                <label for="altabajacambio_{{ $keys }}">Tipo de Solicitud:</label>
                                <select name="aplicaciones[{{ $keys }}][altabajacambio_{{ $keys }}]" id="altabajacambio_{{ $keys }}" class="form-control campo-requerido tiposolicitudselect">
                                    <option value="">Seleccione...</option>
                                    <option value="a">Alta</option>
                                    <option value="b">Baja</option>
                                    <option value="c">Cambio</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="rol_{{ $keys }}">Rol: </label>
                                <select name="aplicaciones[{{ $keys }}][hiddenRol_{{ $keys }}]" class="form-control campo-requerido" id="rol_{{ $keys }}">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                            <!-- <div class="col-md-1">
                                <label>&nbsp;</label>
                                <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenRol_{{ $keys }}]" id="hiddenRol_{{ $keys }}" class="campo-requerido" />
                                <button id="addrol_{{ $keys }}" type="button" class="form-control btn btn-primary btn-add"><i class="fas fa-plus"></i></button>
                            </div> -->
                        </div>
                        <!-- <div class="form-group row">
                            <div id="content_list_rol_{{ $keys }}" class="col-md-12"></div>

                            <div class="col-md-12">
                                <ul id="list_rol_{{ $keys }}">
                                </ul>
                            </div>
                        </div> -->
                        @break
                    @case('46')
                        <div class="form-group row" id="subencabezado_{{ $keys }}">
                            <div class="col-md-12" id="campodeaccion_{{ $keys }}"></div>
                            <div class="col-md-6">
                                <!-- <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenOtros_{{ $keys }}]" id="hiddenOtros_{{ $keys }}"/> -->
                                <label for="altabajacambio_{{ $keys }}">Tipo de Solicitud:</label>
                                <select name="aplicaciones[{{ $keys }}][altabajacambio_{{ $keys }}]" id="altabajacambio_{{ $keys }}" class="form-control campo-requerido tiposolicitudselect">
                                    <option value="">Seleccione...</option>
                                    <option value="a">Alta</option>
                                    <option value="b">Baja</option>
                                    <option value="c">Cambio</option>
                                </select>
                            </div>
                            <div class="col-md-5">
                                <label for="permisos_{{ $keys }}">Permisos: </label>
                                <select name="permisos_{{ $keys }}" class="form-control campo-requerido" id="permisos_{{ $keys }}">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <label>&nbsp;</label>
                                <input type="hidden" class="campo-requerido" name="aplicaciones[{{ $keys }}][hiddenPermisos_{{ $keys }}]" id="hiddenPermisos_{{ $keys }}" />
                                <button id="addpermisos_{{ $keys }}" type="button" class="form-control btn btn-primary btn-add"><i class="fas fa-plus"></i></button>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="dominiousuario_{{ $keys }}">DOMINIO: </label>
                                <input type="text" name="aplicaciones[{{ $keys }}][dominio_{{ $keys }}]" id="dominio_{{ $keys }}" class="form-control campo-requerido"/>
                            </div>
                            <div class="col-md-6">
                                <label for="usuario_{{ $keys }}">USUARIO: </label>
                                <input type="text" name="aplicaciones[{{ $keys }}][usuario_{{ $keys }}]" id="usuario_{{ $keys }}" class="form-control campo-requerido"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div id="content_list_permisos_{{ $keys }}" class="col-md-12"></div>

                            <div class="col-md-12">
                                <ul id="list_permisos_{{ $keys }}">
                                </ul>
                            </div>
                        </div>
                        @break
                    @case('1005')
                        <div class="form-group row" id="subencabezado_{{ $keys }}">
                            <div class="col-md-12" id="campodeaccion_{{ $keys }}"></div>
                            <div class="col-md-6">
                                <label for="altabajacambio_{{ $keys }}">Tipo de Solicitud:</label>
                                <select name="aplicaciones[{{ $keys }}][altabajacambio_{{ $keys }}]" id="altabajacambio_{{ $keys }}" class="form-control campo-requerido tiposolicitudselect">
                                    <option value="">Seleccione...</option>
                                    <option value="a">Alta</option>
                                    <option value="b">Baja</option>
                                    <option value="c">Cambio</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="rol_{{ $keys }}">Rol: </label>
                                <select name="aplicaciones[{{ $keys }}][hiddenRol_{{ $keys }}]" class="form-control campo-requerido" id="rol_{{ $keys }}">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                            <!-- <div class="col-md-1">
                                <label>&nbsp;</label>
                                <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenRol_{{ $keys }}]" id="hiddenRol_{{ $keys }}" class="campo-requerido" />
                                <button id="addrol_{{ $keys }}" type="button" class="form-control btn btn-primary btn-add"><i class="fas fa-plus"></i></button>
                            </div> -->
                        </div>
                        <div class="form-group row">
                            <div class="col-md-11"> 
                                <label for="empresa_{{ $keys }}">Empresa(s):</label>
                                <select name="empresa_{{ $keys }}" class="form-control campo-requerido" id="empresa_{{ $keys }}">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <label>&nbsp;</label>
                                <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenEmpresa_{{ $keys }}]" id="hiddenEmpresa_{{ $keys }}" />
                                <button id="addempresa_{{ $keys }}" type="button" class="form-control btn btn-primary btn-add"><i class="fas fa-plus"></i></button>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div id="content_list_empresa_{{ $keys }}" class="col-md-12"></div>
                                    <div class="col-md-12">
                                        <ul id="list_empresa_{{ $keys }}">
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @break
                    @case('64')
                        <div class="form-group row" id="subencabezado_{{ $keys }}">
                            <div class="col-md-12" id="campodeaccion_{{ $keys }}"></div>
                            <div class="col-md-12">
                                <label for="altabajacambio_{{ $keys }}">Tipo de Solicitud:</label>
                                <select name="aplicaciones[{{ $keys }}][altabajacambio_{{ $keys }}]" id="altabajacambio_{{ $keys }}" class="form-control campo-requerido tiposolicitudselect">
                                    <option value="">Seleccione...</option>
                                    <option value="a">Alta</option>
                                    <option value="b">Baja</option>
                                    <option value="c">Cambio</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12"><input type="hidden" name="validacion_al_menos_uno_{{ $keys }}" id="validacion_al_menos_uno_{{ $keys }}" class="campo-requerido" /></div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3">
                                <div class="row">
                                    <div class="col-md-12">    
                                        <input type="checkbox" name="aplicaciones[{{ $keys }}][prod_{{ $keys }}]" id="prod_{{ $keys }}" data-datos="reporteprod_{{ $keys }}" data-idreal="prodreporte_{{ $keys }}" class="form-control check-not-disabled" data-aquien="hiddenReporteProd_{{ $keys }}"/>
                                        <label for="prod_{{ $keys }}">Prod:</label>
                                    </div>
                                    <div class="col-md-12">
                                        <input type="checkbox" name="aplicaciones[{{ $keys }}][intermex_{{ $keys }}]" id="intermex_{{ $keys }}"  data-datos="reporteintermex_{{ $keys }}" data-idreal="intermexreporte_{{ $keys }}" class="form-control check-not-disabled" data-aquien="hiddenReporteIntermex_{{ $keys }}" />
                                        <label for="intermex_{{ $keys }}">Intermex:</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-md-11"> 
                                        <label for="prodreportes_{{ $keys }}">Reportes Prod:</label>
                                        <select disabled name="prodreporte_{{ $keys }}" class="form-control" id="prodreporte_{{ $keys }}">
                                            <option value="">Seleccione...</option>
                                        </select>
                                    </div>
                                    <div class="col-md-1">
                                        <label>&nbsp;</label>
                                        <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenReporteProd_{{ $keys }}]" id="hiddenReporteProd_{{ $keys }}" />
                                        <button disabled id="addreporteprod_{{ $keys }}" type="button" class="form-control btn btn-primary btn-add"><i class="fas fa-plus"></i></button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-11"> 
                                        <label for="intermex_{{ $keys }}">Reportes Intermex:</label>
                                        <select disabled name="intermexreporte_{{ $keys }}" class="form-control" id="intermexreporte_{{ $keys }}">
                                            <option value="">Seleccione...</option>
                                        </select>
                                    </div>
                                    <div class="col-md-1">
                                        <label>&nbsp;</label>
                                        <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenReporteIntermex_{{ $keys }}]" id="hiddenReporteIntermex_{{ $keys }}" />
                                        <button disabled id="addreporteintermex_{{ $keys }}" type="button" class="form-control btn btn-primary btn-add"><i class="fas fa-plus"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div id="content_list_reporte_prod_{{ $keys }}" class="col-md-12"></div>
                                    <div class="col-md-12">
                                        <ul id="list_reporte_prod_{{ $keys }}">
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div id="content_list_reporte_intermex_{{ $keys }}" class="col-md-12"></div>
                                    <div class="col-md-12">
                                        <ul id="list_reporte_intermex_{{ $keys }}">
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @break
                    @case('69')
                        <div class="form-group row" id="subencabezado_{{ $keys }}">
                            <div class="col-md-12" id="campodeaccion_{{ $keys }}"></div>
                            <div class="col-md-6">
                                <!-- <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenOtros_{{ $keys }}]" id="hiddenOtros_{{ $keys }}"/> -->
                                <label for="altabajacambio_{{ $keys }}">Tipo de Solicitud:</label>
                                <select name="aplicaciones[{{ $keys }}][altabajacambio_{{ $keys }}]" id="altabajacambio_{{ $keys }}" class="form-control campo-requerido tiposolicitudselect">
                                    <option value="">Seleccione...</option>
                                    <option value="a">Alta</option>
                                    <option value="b">Baja</option>
                                    <option value="c">Cambio</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="fondo_{{ $keys }}">Fondo:</label>
                                <select name="aplicaciones[{{ $keys }}][fondo_{{ $keys }}]" id="fondo_{{ $keys }}" class="form-control campo-requerido fondoselect">
                                    <option value="">Seleccione...</option>
                                    <!-- <option value="Fondo de ahorro">Fondo de ahorro</option>
                                    <option value="Nomina con causa">Nomina con causa</option>
                                    <option value="Intercambios">Intercambios</option> -->
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="cajaahorros_{{ $keys }}">Caja de ahorro:</label>
                                <select name="aplicaciones[{{ $keys }}][cajaahorros_{{ $keys }}]" id="cajaahorros_{{ $keys }}" class="form-control">
                                    <option value="">Seleccione...</option>
                                    <!-- <option value="cn">CN</option>
                                    <option value="ce">CE</option>
                                    <option value="ca">CA</option>
                                    <option value="cne">CNE</option> -->
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="tipousuario_{{ $keys }}">Tipo de usuario:</label>
                                <select name="aplicaciones[{{ $keys }}][tipousuario_{{ $keys }}]" id="tipousuario_{{ $keys }}" class="form-control tipousuarioselect">
                                    <option value="">Seleccione...</option>
                                    <!-- <option value="cn">CN</option>
                                    <option value="ce">CE</option>
                                    <option value="ca">CA</option>
                                    <option value="cne">CNE</option> -->
                                </select>
                            </div>
                        </div>
                        <!--  -->
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="especificarperfilcentral_{{ $keys }}">Especificar perfil Central:</label>
                                <select name="aplicaciones[{{ $keys }}][perfilCentral_{{ $keys }}]" id="perfilCentral_{{ $keys }}" class="form-control">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="especificarperfilinternet_{{ $keys }}">Especificar perfil Internet:</label>
                                <!-- <input disabled type="text" name="aplicaciones[{{ $keys }}][especificarperfilinternet_{{ $keys }}]" id="especificarperfilinternet_{{ $keys }}" class="form-control"/> -->
                                <select name="aplicaciones[{{ $keys }}][perfilInternet_{{ $keys }}]" id="perfilInternet_{{ $keys }}" class="form-control">
                                    <option>Seleccione...</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="usuarioWeb_{{ $keys }}">Tipos de usuarios web:</label>
                                <!-- <input disabled type="text" name="aplicaciones[{{ $keys }}][especificarperfilinternet_{{ $keys }}]" id="especificarperfilinternet_{{ $keys }}" class="form-control"/> -->
                                <select name="aplicaciones[{{ $keys }}][usuarioWeb_{{ $keys }}]" id="usuarioWeb_{{ $keys }}" class="form-control">
                                    <option>Seleccione...</option>
                                </select>
                            </div>
                        </div>
                       <!--  <div class="form-group row">
                            <div class="col-md-12">
                                <label for="tipoadministrador_{{ $keys }}">Tipo de administrador:</label>
                                <select name="aplicaciones[{{ $keys }}][tipoadministrador_{{ $keys }}]" id="tipoadministrador_{{ $keys }}" class="form-control">
                                    <option value="">Seleccione...</option>
                                    <option value="Administrador cliente">Administrador cliente</option>
                                    <option value="Adminitrador empresa">Adminitrador empresa</option>
                                    <option value="Administrador empresa y ubicación">Administrador empresa y ubicación</option>
                                    <option value="Otro">Otro</option>
                                </select>
                            </div>
                        </div> -->
                        @break
                    @case('47')
                        <div class="form-group row" id="subencabezado_{{ $keys }}">
                            <div class="col-md-12" id="campodeaccion_{{ $keys }}"></div>
                            <div class="col-md-6">
                                <label for="altabajacambio_{{ $keys }}">Tipo de Solicitud:</label>
                                <select name="aplicaciones[{{ $keys }}][altabajacambio_{{ $keys }}]" id="altabajacambio_{{ $keys }}" class="form-control campo-requerido tiposolicitudselect">
                                    <option value="">Seleccione...</option>
                                    <option value="a">Alta</option>
                                    <option value="b">Baja</option>
                                    <option value="c">Cambio</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="perfil_{{ $keys }}">Perfiles: </label>
                                <!-- <select name="perfil_{{ $keys }}" class="form-control" id="perfil_{{ $keys }}"> -->
                                <select name="aplicaciones[{{ $keys }}][hiddenPerfil_{{ $keys }}]" class="form-control campo-requerido" id="perfil_{{ $keys }}">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                            <!-- <div class="col-md-1">
                                <label>&nbsp;</label>
                                <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenPerfil_{{ $keys }}]" id="hiddenPerfil_{{ $keys }}" class="campo-requerido" />
                                <button id="addperfil_{{ $keys }}" type="button" class="form-control btn btn-primary btn-add"><i class="fas fa-plus"></i></button>
                            </div> -->
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="funcion_{{ $keys }}">Función:</label>
                                <input type="text" name="aplicaciones[{{ $keys }}][hiddenFuncion_{{ $keys }}]" class="form-control" id="funcion_{{ $keys }}">
                            </div>
                        </div>

                        <!-- <div class="row">
                            <div class="col-md-11"> 
                                <label for="funcion_{{ $keys }}">Función:</label>
                                <select name="funcion_{{ $keys }}" class="form-control" id="funcion_{{ $keys }}">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <label>&nbsp;</label>
                                <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenFuncion_{{ $keys }}]" id="hiddenFuncion_{{ $keys }}" class="campo-requerido" />
                                <button id="addfuncion_{{ $keys }}" type="button" class="form-control btn btn-primary btn-add"><i class="fas fa-plus"></i></button>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div id="content_list_perfil_{{ $keys }}" class="col-md-12"></div>
    
                                    <div class="col-md-12">
                                        <ul id="list_perfil_{{ $keys }}">
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div id="content_list_funcion_{{ $keys }}" class="col-md-12"></div>

                                    <div class="col-md-12">
                                        <ul id="list_funcion_{{ $keys }}">
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div> -->
                        @break
                    @case('62')
                        <div class="form-group row" id="subencabezado_{{ $keys }}">
                            <div class="col-md-12" id="campodeaccion_{{ $keys }}"></div>
                            <div class="col-md-6">
                                <label for="altabajacambio_{{ $keys }}">Tipo de Solicitud:</label>
                                <select name="aplicaciones[{{ $keys }}][altabajacambio_{{ $keys }}]" id="altabajacambio_{{ $keys }}" class="form-control campo-requerido tiposolicitudselect">
                                    <option value="">Seleccione...</option>
                                    <option value="a">Alta</option>
                                    <option value="b">Baja</option>
                                    <option value="c">Cambio</option>
                                </select>
                            </div>
                            <div class="col-md-5">
                                <label for="perfil_{{ $keys }}">Perfiles: </label>
                                <select name="perfil_{{ $keys }}" class="form-control" id="perfil_{{ $keys }}">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <label>&nbsp;</label>
                                <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenPerfil_{{ $keys }}]" id="hiddenPerfil_{{ $keys }}" class="campo-requerido" />
                                <button id="addperfil_{{ $keys }}" type="button" class="form-control btn btn-primary btn-add"><i class="fas fa-plus"></i></button>
                            </div>
                        </div>
                       <!--  <div class="row">
                            <div class="col-md-11"> 
                                <label for="funcion_{{ $keys }}">Función:</label>
                                <select name="funcion_{{ $keys }}" class="form-control" id="funcion_{{ $keys }}">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <label>&nbsp;</label>
                                <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenFuncion_{{ $keys }}]" id="hiddenFuncion_{{ $keys }}" class="campo-requerido" />
                                <button id="addfuncion_{{ $keys }}" type="button" class="form-control btn btn-primary btn-add"><i class="fas fa-plus"></i></button>
                            </div>
                        </div> -->
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="infoadicional_{{ $keys }}">Información Adicional: </label>
                                <textarea name="aplicaciones[{{ $keys }}][infoadicional_{{ $keys }}]" id="infoadicional_{{ $keys }}" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div id="content_list_perfil_{{ $keys }}" class="col-md-12"></div>
    
                                    <div class="col-md-12">
                                        <ul id="list_perfil_{{ $keys }}">
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div id="content_list_funcion_{{ $keys }}" class="col-md-12"></div>

                                    <div class="col-md-12">
                                        <ul id="list_funcion_{{ $keys }}">
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @break
                    @case('48')
                        <div class="form-group row" id="subencabezado_{{ $keys }}">
                            <div class="col-md-12" id="campodeaccion_{{ $keys }}"></div>
                            <div class="col-md-6">
                                <label for="altabajacambio_{{ $keys }}">Tipo de Solicitud:</label>
                                <select name="aplicaciones[{{ $keys }}][altabajacambio_{{ $keys }}]" id="altabajacambio_{{ $keys }}" class="form-control campo-requerido tiposolicitudselect">
                                    <option value="">Seleccione...</option>
                                    <option value="a">Alta</option>
                                    <option value="b">Baja</option>
                                    <option value="c">Cambio</option>
                                </select>
                            </div>
                            <div class="col-md-5">
                                <label for="rol_{{ $keys }}">Rol: </label>
                                <select name="rol_{{ $keys }}" class="form-control" id="rol_{{ $keys }}">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <label>&nbsp;</label>
                                <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenRol_{{ $keys }}]" id="hiddenRol_{{ $keys }}" class="campo-requerido" />
                                <button id="addrol_{{ $keys }}" type="button" class="form-control btn btn-primary btn-add"><i class="fas fa-plus"></i></button>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div id="content_list_rol_{{ $keys }}" class="col-md-12"></div>

                            <div class="col-md-12">
                                <ul id="list_rol_{{ $keys }}">
                                </ul>
                            </div>
                        </div>
                        @break
                    @case('49')
                        <div class="form-group row" id="subencabezado_{{ $keys }}">
                            <div class="col-md-12" id="campodeaccion_{{ $keys }}"></div>
                            <div class="col-md-6">
                                <label for="altabajacambio_{{ $keys }}">Tipo de Solicitud:</label>
                                <select name="aplicaciones[{{ $keys }}][altabajacambio_{{ $keys }}]" id="altabajacambio_{{ $keys }}" class="form-control campo-requerido tiposolicitudselect">
                                    <option value="">Seleccione...</option>
                                    <option value="a">Alta</option>
                                    <option value="b">Baja</option>
                                    <option value="c">Cambio</option>
                                </select>
                            </div>
                            <div class="col-md-5">
                                <label for="rol_{{ $keys }}">Rol: </label>
                                <select name="rol_{{ $keys }}" class="form-control" id="rol_{{ $keys }}">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <label>&nbsp;</label>
                                <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenRol_{{ $keys }}]" id="hiddenRol_{{ $keys }}" class="campo-requerido" />
                                <button id="addrol_{{ $keys }}" type="button" class="form-control btn btn-primary btn-add"><i class="fas fa-plus"></i></button>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div id="content_list_rol_{{ $keys }}" class="col-md-12"></div>

                            <div class="col-md-12">
                                <ul id="list_rol_{{ $keys }}">
                                </ul>
                            </div>
                        </div>
                        @break
                    @case('50')
                        <div class="form-group row" id="subencabezado_{{ $keys }}">
                            <div class="col-md-12" id="campodeaccion_{{ $keys }}"></div>
                            <div class="col-md-6">
                                <label for="altabajacambio_{{ $keys }}">Tipo de Solicitud:</label>
                                <select name="aplicaciones[{{ $keys }}][altabajacambio_{{ $keys }}]" id="altabajacambio_{{ $keys }}" class="form-control campo-requerido tiposolicitudselect">
                                    <option value="">Seleccione...</option>
                                    <option value="a">Alta</option>
                                    <option value="b">Baja</option>
                                    <option value="c">Cambio</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="perfil_{{ $keys }}">Perfiles: </label>
                                <select name="aplicaciones[{{ $keys }}][perfil_{{ $keys }}]" class="form-control campo-requerido" id="perfil_{{ $keys }}">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                            <!-- <div class="col-md-1">
                                <label>&nbsp;</label>
                                <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenPerfil_{{ $keys }}]" id="hiddenPerfil_{{ $keys }}" class="campo-requerido" />
                                <button id="addperfil_{{ $keys }}" type="button" class="form-control btn btn-primary btn-add"><i class="fas fa-plus"></i></button>
                            </div> -->
                        </div>
                        <div class="form-group row">
                            <div class="col-md-11"> 
                                <label for="empresa_{{ $keys }}">Empresas:</label>
                                <select name="empresa_{{ $keys }}" class="form-control" id="empresa_{{ $keys }}">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <label>&nbsp;</label>
                                <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenEmpresa_{{ $keys }}]" id="hiddenEmpresa_{{ $keys }}" />
                                <button id="addempresa_{{ $keys }}" type="button" class="form-control btn btn-primary btn-add"><i class="fas fa-plus"></i></button>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="externlogin_{{ $keys }}">Información Adicional: </label>
                                <textarea name="aplicaciones[{{ $keys }}][externlogin_{{ $keys }}]" id="externlogin_{{ $keys }}" class="form-control"></textarea>
                            </div>
                            <div class="col-md-5">
                                <label for="facultades_{{ $keys }}">Facultades: </label>
                                <select name="facultades_{{ $keys }}" class="form-control" id="facultades_{{ $keys }}">
                                    <option value="">Seleccione...</option>
                                </select>
                                <!-- <input type="text" name="aplicaciones[{{ $keys }}][facultades_{{ $keys }}]" id="facultades_{{ $keys }}" class="form-control"/> -->
                            </div>
                            <div class="col-md-1">
                                <label>&nbsp;</label>
                                <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenFacultades_{{ $keys }}]" id="hiddenFacultades_{{ $keys }}" />
                                <button id="addfacultades_{{ $keys }}" type="button" class="form-control btn btn-primary btn-add"><i class="fas fa-plus"></i></button>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div id="content_list_empresa_{{ $keys }}" class="col-md-12"></div>

                                    <div class="col-md-12">
                                        <ul id="list_empresa_{{ $keys }}">
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div id="content_list_facultades_{{ $keys }}" class="col-md-12"></div>
        
                                    <div class="col-md-12">
                                        <ul id="list_facultades_{{ $keys }}">
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @break
                    @case('51')
                        <div class="form-group row" id="subencabezado_{{ $keys }}">
                            <div class="col-md-12" id="campodeaccion_{{ $keys }}"></div>
                            <div class="col-md-12">
                                <label for="altabajacambio_{{ $keys }}">Tipo de Solicitud:</label>
                                <select name="aplicaciones[{{ $keys }}][altabajacambio_{{ $keys }}]" id="altabajacambio_{{ $keys }}" class="form-control campo-requerido tiposolicitudselect">
                                    <option value="">Seleccione...</option>
                                    <option value="a">Alta</option>
                                    <option value="b">Baja</option>
                                    <option value="c">Cambio</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="grupo_{{ $keys }}">Grupo: </label>
                                <select name="aplicaciones[{{ $keys }}][hiddenGrupo_{{ $keys }}]" class="form-control campo-requerido" id="grupo_{{ $keys }}">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                            <!-- <div class="col-md-1">
                                <label>&nbsp;</label>
                                <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenGrupo_{{ $keys }}]" id="hiddenGrupo_{{ $keys }}" class="campo-requerido" />
                                <button id="addgrupo_{{ $keys }}" type="button" class="form-control btn btn-primary btn-add"><i class="fas fa-plus"></i></button>
                            </div> -->
                            <!-- <div class="col-md-5">
                                <label for="aplicacion_{{ $keys }}">Aplicación: </label>
                                <select name="aplicacion_{{ $keys }}" class="form-control" id="aplicacion_{{ $keys }}">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div> -->
                            <div class="col-md-6">
                                <label for="aplicacion_{{ $keys }}">Aplicación: </label>
                                <input type="text" class="form-control campo-requerido" id="aplicacion_{{ $keys }}" name="aplicaciones[{{ $keys }}][hiddenAplicacion_{{ $keys }}]">
                                <!-- <select name="aplicacion_{{ $keys }}" class="form-control" id="aplicacion_{{ $keys }}" name="aplicaciones[{{ $keys }}][hiddenAplicacion_{{ $keys }}]">
                                    <option value="">Seleccione...</option>
                                </select> -->
                            </div>
                            <!-- <div class="col-md-1">
                                <label>&nbsp;</label>
                                <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenAplicacion_{{ $keys }}]" id="hiddenAplicacion_{{ $keys }}" class="campo-requerido" />
                                <button id="addapp_{{ $keys }}" type="button" class="form-control btn btn-primary btn-add"><i class="fas fa-plus"></i></button>
                            </div> -->
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div id="content_list_grupo_{{ $keys }}" class="col-md-12"></div>

                                    <div class="col-md-12">
                                        <ul id="list_grupo_{{ $keys }}">
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div id="content_list_aplicacion_{{ $keys }}" class="col-md-12"></div>
    
                                    <div class="col-md-12">
                                        <ul id="list_aplicacion_{{ $keys }}">
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @break
                    @case('76')
                        <div class="form-group row" id="subencabezado_{{ $keys }}">
                            <div class="col-md-12" id="campodeaccion_{{ $keys }}"></div>
                            <div class="col-md-12">
                                <label for="altabajacambio_{{ $keys }}">Tipo de Solicitud:</label>
                                <select name="aplicaciones[{{ $keys }}][altabajacambio_{{ $keys }}]" id="altabajacambio_{{ $keys }}" class="form-control campo-requerido tiposolicitudselect">
                                    <option value="">Seleccione...</option>
                                    <option value="a">Alta</option>
                                    <option value="b">Baja</option>
                                    <option value="c">Cambio</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="grupo_{{ $keys }}">Rol: </label>
                                <select name="aplicaciones[{{ $keys }}][hiddenRol_{{ $keys }}]" class="form-control campo-requerido" id="rol_{{ $keys }}">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="aplicacion_{{ $keys }}">Aplicación: </label>
                                <input type="text" name="aplicaciones[{{ $keys }}][hiddenAplicacion_{{ $keys }}]" class="form-control campo-requerido" id="aplicacion_{{ $keys }}">
                            </div>
                            <!-- <div class="col-md-1">
                                <label>&nbsp;</label>
                                <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenGrupo_{{ $keys }}]" id="hiddenGrupo_{{ $keys }}" class="campo-requerido" />
                                <button id="addgrupo_{{ $keys }}" type="button" class="form-control btn btn-primary btn-add"><i class="fas fa-plus"></i></button>
                            </div>
                            <div class="col-md-5">
                                <label for="aplicacion_{{ $keys }}">Aplicación: </label>
                                <select name="aplicacion_{{ $keys }}" class="form-control" id="aplicacion_{{ $keys }}">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <label>&nbsp;</label>
                                <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenAplicacion_{{ $keys }}]" id="hiddenAplicacion_{{ $keys }}" class="campo-requerido" />
                                <button id="addapp_{{ $keys }}" type="button" class="form-control btn btn-primary btn-add"><i class="fas fa-plus"></i></button>
                             </div>-->
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div id="content_list_grupo_{{ $keys }}" class="col-md-12"></div>

                                    <div class="col-md-12">
                                        <ul id="list_grupo_{{ $keys }}">
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div id="content_list_aplicacion_{{ $keys }}" class="col-md-12"></div>
    
                                    <div class="col-md-12">
                                        <ul id="list_aplicacion_{{ $keys }}">
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @break
                    @case('52')
                    <div class="form-group row" id="subencabezado_{{ $keys }}">
                        <div class="col-md-12" id="campodeaccion_{{ $keys }}"></div>
                        <div class="col-md-6">
                        <!--  <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenOtros_{{ $keys }}]" id="hiddenOtros_{{ $keys }}"/> -->
                            <label for="altabajacambio_{{ $keys }}">Tipo de Solicitud:</label>
                            <select name="aplicaciones[{{ $keys }}][altabajacambio_{{ $keys }}]" id="altabajacambio_{{ $keys }}" class="form-control campo-requerido tiposolicitudselect">
                                <option value="">Seleccione...</option>
                                <option value="a">Alta</option>
                                <option value="b">Baja</option>
                                <option value="c">Cambio</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="permisos_{{ $keys }}">Permisos: </label>
                            <select name="aplicaciones[{{ $keys }}][hiddenPermisos_{{ $keys }}]" class="form-control campo-requerido" id="permisos_{{ $keys }}">
                                <option value="">Seleccione...</option>
                            </select>
                        </div>
                        <!--<div class="col-md-1">
                            <label>&nbsp;</label>
                            <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenPermisos_{{ $keys }}]" id="hiddenPermisos_{{ $keys }}" class="campo-requerido" />
                            <button id="addpermisos_{{ $keys }}" type="button" class="form-control btn btn-primary btn-add"><i class="fas fa-plus"></i></button>
                        </div>-->
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="dominiousuario_{{ $keys }}">Datos adicionales: </label>
                            <input type="text" name="aplicaciones[{{ $keys }}][dominiousuario_{{ $keys }}]" id="dominiousuario_{{ $keys }}" class="form-control"/>
                        </div>
                    </div>
                    <!--<div class="form-group row">
                        <div id="content_list_permisos_{{ $keys }}" class="col-md-12"></div>

                        <div class="col-md-12">
                            <ul id="list_permisos_{{ $keys }}">
                            </ul>
                        </div>
                    </div>-->
                    @break
                    @case('53')
                        <div class="form-group row" id="subencabezado_{{ $keys }}">
                            <div class="col-md-12" id="campodeaccion_{{ $keys }}"></div>
                            <div class="col-md-6">
                                <label for="altabajacambio_{{ $keys }}">Tipo de Solicitud:</label>
                                <select name="aplicaciones[{{ $keys }}][altabajacambio_{{ $keys }}]" id="altabajacambio_{{ $keys }}" class="form-control campo-requerido tiposolicitudselect">
                                    <option value="">Seleccione...</option>
                                    <option value="a">Alta</option>
                                    <option value="b">Baja</option>
                                    <option value="c">Cambio</option>
                                </select>
                            </div>
                            <div class="col-md-5">
                                <label for="grupo_{{ $keys }}">Grupo: </label>
                                <select name="grupo_{{ $keys }}" class="form-control" id="grupo_{{ $keys }}">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <label>&nbsp;</label>
                                <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenGrupo_{{ $keys }}]" id="hiddenGrupo_{{ $keys }}" class="campo-requerido" />
                                <button id="addgrupo_{{ $keys }}" type="button" class="form-control btn btn-primary btn-add"><i class="fas fa-plus"></i></button>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div id="content_list_grupo_{{ $keys }}" class="col-md-12"></div>

                            <div class="col-md-12">
                                <ul id="list_grupo_{{ $keys }}">
                                </ul>
                            </div>
                        </div>
                        @break
                    @case('54')
                        <div class="form-group row" id="subencabezado_{{ $keys }}">
                            <div class="col-md-12" id="campodeaccion_{{ $keys }}"></div>
                            <div class="col-md-6">
                                <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenOtros_{{ $keys }}]" id="hiddenOtros_{{ $keys }}"/>
                                <label for="altabajacambio_{{ $keys }}">Tipo de Solicitud:</label>
                                <select name="aplicaciones[{{ $keys }}][altabajacambio_{{ $keys }}]" id="altabajacambio_{{ $keys }}" class="form-control campo-requerido tiposolicitudselect">
                                    <option value="">Seleccione...</option>
                                    <option value="a">Alta</option>
                                    <option value="b">Baja</option>
                                    <option value="c">Cambio</option>
                                </select>
                            </div>
                            <div class="col-md-5"> 
                                <label for="empresa_{{ $keys }}">Empresas:</label>
                                <select name="empresa_{{ $keys }}" class="form-control campo-requerido" id="empresa_{{ $keys }}">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <label>&nbsp;</label>
                                <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenEmpresa_{{ $keys }}]" id="hiddenEmpresa_{{ $keys }}" />
                                <button id="addempresa_{{ $keys }}" type="button" class="form-control btn btn-primary btn-add"><i class="fas fa-plus"></i></button>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="sistema_{{ $keys }}">Sistema (Módulo): </label>
                                <select name="aplicaciones[{{ $keys }}][sistema_{{ $keys }}]" class="form-control moduloselect campo-requerido" id="sistema_{{ $keys }}">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                            <!-- <div class="col-md-1">
                                <label>&nbsp;</label>
                                <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenSistema_{{ $keys }}]" id="hiddenSistema_{{ $keys }}" />
                                <button id="addsistema_{{ $keys }}" type="button" class="form-control btn btn-primary btn-add"><i class="fas fa-plus"></i></button>
                            </div> -->
                            <!-- <div class="col-md-6">
                                <label for="subsistema_{{ $keys }}">Sub-Sistema: </label>
                                <input type="text" name="aplicaciones[{{ $keys }}][subsistema_{{ $keys }}]" id="subsistema_{{ $keys }}" class="form-control"/>
                            </div> -->
                            <div class="col-md-5">
                                <label for="subsistemas_{{ $keys }}">Sub-Sistemas: </label>
                                <select name="subsistemas_{{ $keys }}" class="form-control" id="subsistemas_{{ $keys }}">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <label>&nbsp;</label>
                                <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenSubsistemas_{{ $keys }}]" id="hiddenSubsistemas_{{ $keys }}" />
                                <button id="addsubsistemas_{{ $keys }}" type="button" class="form-control btn btn-primary btn-add"><i class="fas fa-plus"></i></button>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-11">
                                <label for="perfil_{{ $keys }}">Perfiles: </label>
                                <select name="perfil_{{ $keys }}" class="form-control" id="perfil_{{ $keys }}">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <label>&nbsp;</label>
                                <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenPerfil_{{ $keys }}]" id="hiddenPerfil_{{ $keys }}" class="campo-requerido" />
                                <button id="addperfil_{{ $keys }}" type="button" class="form-control btn btn-primary btn-add"><i class="fas fa-plus"></i></button>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div id="content_list_empresa_{{ $keys }}" class="col-md-12"></div>
            
                                    <div class="col-md-12">
                                        <ul id="list_empresa_{{ $keys }}">
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div id="content_list_subsistemas_{{ $keys }}" class="col-md-12"></div>
        
                                    <div class="col-md-12">
                                        <ul id="list_subsistemas_{{ $keys }}">
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div id="content_list_perfil_{{ $keys }}" class="col-md-12"></div>
                                    
                                    <div class="col-md-12">
                                        <ul id="list_perfil_{{ $keys }}">
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @break
                        @case('55')
                        <div class="form-group row" id="subencabezado_{{ $keys }}">
                            <div class="col-md-12" id="campodeaccion_{{ $keys }}"></div>
                            <div class="col-md-6">
                                <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenOtros_{{ $keys }}]" id="hiddenOtros_{{ $keys }}"/>
                                <label for="altabajacambio_{{ $keys }}">Tipo de Solicitud:</label>
                                <select name="aplicaciones[{{ $keys }}][altabajacambio_{{ $keys }}]" id="altabajacambio_{{ $keys }}" class="form-control campo-requerido tiposolicitudselect">
                                    <option value="">Seleccione...</option>
                                    <option value="a">Alta</option>
                                    <option value="b">Baja</option>
                                    <option value="c">Cambio</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="nivelesseguridad_{{ $keys }}">Niveles de seguridad: </label>
                                <input type="text" name="aplicaciones[{{ $keys }}][nivelesseguridad_{{ $keys }}]" id="nivelesseguridad_{{ $keys }}" class="form-control"/>
                                <!-- <select name="aplicaciones[{{ $keys }}][nivelesseguridad_{{ $keys }}]" id="nivelesseguridad_{{ $keys }}" class="form-control">
                                    <option value="">Seleccione...</option>
                                </select> -->
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="nomina_{{ $keys }}">Nómina: </label>
                                <input type="text" name="aplicaciones[{{ $keys }}][nomina_{{ $keys }}]" id="nomina_{{ $keys }}" class="form-control"/>
                            </div>
                            <div class="col-md-6">
                                <label for="tiposcontrato_{{ $keys }}">Tipos de contrato: </label>
                                <input type="text" name="aplicaciones[{{ $keys }}][tiposcontrato_{{ $keys }}]" id="tiposcontrato_{{ $keys }}" class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="menu_{{ $keys }}">Menú: </label>
                                <input type="text" name="aplicaciones[{{ $keys }}][menu_{{ $keys }}]" id="menu_{{ $keys }}" class="form-control"/>
                            </div>
                            <div class="col-md-6">
                                <label for="centrodecostos_{{ $keys }}">Centro de costos: </label>
                                <input type="text" name="aplicaciones[{{ $keys }}][centrodecostos_{{ $keys }}]" id="centrodecostos_{{ $keys }}" class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="areas_{{ $keys }}">Áreas: </label>
                                <input type="text" name="aplicaciones[{{ $keys }}][areas_{{ $keys }}]" id="areas_{{ $keys }}" class="form-control"/>
                            </div>
                            <div class="col-md-6">
                                <label for="procesos_{{ $keys }}">Procesos: </label>
                                <input type="text" name="aplicaciones[{{ $keys }}][procesos_{{ $keys }}]" id="procesos_{{ $keys }}" class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="actividades_{{ $keys }}">Actividades: </label>
                                <input type="text" name="aplicaciones[{{ $keys }}][actividades_{{ $keys }}]" id="actividades_{{ $keys }}" class="form-control"/>
                            </div>
                            <div class="col-md-6">
                                <label for="optotros_{{ $keys }}">Otros: </label>
                                <textarea name="aplicaciones[{{ $keys }}][optotros_{{ $keys }}]" id="optotros_{{ $keys }}" class="form-control"></textarea>
                               <!-- <input type="text" name="aplicaciones[{{ $keys }}][optotros_{{ $keys }}]" id="optotros_{{ $keys }}" class="form-control"/> -->
                            </div>
                        </div>

                        @break
                    @case('56')
                        <div class="form-group row" id="subencabezado_{{ $keys }}">
                            <div class="col-md-12" id="campodeaccion_{{ $keys }}"></div>
                            <div class="col-md-6">
                               <!--  <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenOtros_{{ $keys }}]" id="hiddenOtros_{{ $keys }}"/> -->
                                <label for="altabajacambio_{{ $keys }}">Tipo de Solicitud:</label>
                                <select name="aplicaciones[{{ $keys }}][altabajacambio_{{ $keys }}]" id="altabajacambio_{{ $keys }}" class="form-control campo-requerido tiposolicitudselect">
                                    <option value="">Seleccione...</option>
                                    <option value="a">Alta</option>
                                    <option value="b">Baja</option>
                                    <option value="c">Cambio</option>
                                </select>
                            </div>
                            <div class="col-md-5">
                                <label for="permisos_{{ $keys }}">Permisos: </label>
                                <select name="permisos_{{ $keys }}" class="form-control" id="permisos_{{ $keys }}">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <label>&nbsp;</label>
                                <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenPermisos_{{ $keys }}]" id="hiddenPermisos_{{ $keys }}" class="campo-requerido"/>
                                <button id="addpermisos_{{ $keys }}" type="button" class="form-control btn btn-primary btn-add"><i class="fas fa-plus"></i></button>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="otropermisos_{{ $keys }}">Datos adicionales: </label>
                                <input type="text" name="aplicaciones[{{ $keys }}][OtroPermiso_{{ $keys }}]" id="OtroPermisos_{{ $keys }}" class="form-control">
                            </div>
                        </div>    
                        <div class="form-group row">
                            <div id="content_list_permisos_{{ $keys }}" class="col-md-12"></div>

                            <div class="col-md-12">
                                <ul id="list_permisos_{{ $keys }}">
                                </ul>
                            </div>
                        </div>
                        @break
                    @case('11')
                        <div class="form-group row" id="subencabezado_{{ $keys }}">
                            <div class="col-md-12" id="campodeaccion_{{ $keys }}"></div>
                            <div class="col-md-6">
                                <label for="altabajacambio_{{ $keys }}">Tipo de Solicitud:</label>
                                <select name="aplicaciones[{{ $keys }}][altabajacambio_{{ $keys }}]" id="altabajacambio_{{ $keys }}" class="form-control campo-requerido tiposolicitudselect">
                                    <option value="">Seleccione...</option>
                                    <option value="a">Alta</option>
                                    <option value="b">Baja</option>
                                    <option value="c">Cambio</option>
                                </select>
                            </div>
                            <div class="col-md-5">
                                <label for="perfil_{{ $keys }}">Perfiles: </label>
                                <select name="perfil_{{ $keys }}" class="form-control" id="perfil_{{ $keys }}">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <label>&nbsp;</label>
                                <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenPerfil_{{ $keys }}]" id="hiddenPerfil_{{ $keys }}" class="campo-requerido" />
                                <button id="addperfil_{{ $keys }}" type="button" class="form-control btn btn-primary btn-add"><i class="fas fa-plus"></i></button>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div id="content_list_perfil_{{ $keys }}" class="col-md-12"></div>

                            <div class="col-md-12">
                                <ul id="list_perfil_{{ $keys }}">
                                </ul>
                            </div>
                        </div>
                        @break
                    @case('57')
                        <div class="form-group row" id="subencabezado_{{ $keys }}">
                            <div class="col-md-12" id="campodeaccion_{{ $keys }}"></div>
                            <div class="col-md-6">
                                <label for="altabajacambio_{{ $keys }}">Tipo de Solicitud:</label>
                                <select name="aplicaciones[{{ $keys }}][altabajacambio_{{ $keys }}]" id="altabajacambio_{{ $keys }}" class="form-control campo-requerido tiposolicitudselect">
                                    <option value="">Seleccione...</option>
                                    <option value="a">Alta</option>
                                    <option value="b">Baja</option>
                                    <option value="c">Cambio</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="rol_{{ $keys }}">Rol: </label>
                                <select name="aplicaciones[{{ $keys }}][hiddenRol_{{ $keys }}]" class="form-control campo-requerido" id="rol_{{ $keys }}">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                            <!-- <div class="col-md-1">
                                <label>&nbsp;</label>
                                <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenRol_{{ $keys }}]" id="hiddenRol_{{ $keys }}" class="campo-requerido" />
                                <button id="addrol_{{ $keys }}" type="button" class="form-control btn btn-primary btn-add"><i class="fas fa-plus"></i></button>
                            </div> -->
                        </div>
                        <!-- <div class="form-group row">
                            <div id="content_list_rol_{{ $keys }}" class="col-md-12"></div>

                            <div class="col-md-12">
                                <ul id="list_rol_{{ $keys }}">
                                </ul>
                            </div>
                        </div> -->
                        @break
                    @case('1011')
                        <div class="form-group row" id="subencabezado_{{ $keys }}">
                            <div class="col-md-12" id="campodeaccion_{{ $keys }}"></div>
                            <div class="col-md-6">
                                <label for="altabajacambio_{{ $keys }}">Tipo de Solicitud:</label>
                                <select name="aplicaciones[{{ $keys }}][altabajacambio_{{ $keys }}]" id="altabajacambio_{{ $keys }}" class="form-control campo-requerido tiposolicitudselect">
                                    <option value="">Seleccione...</option>
                                    <option value="a">Alta</option>
                                    <option value="b">Baja</option>
                                    <option value="c">Cambio</option>
                                </select>
                            </div>
                            <div class="col-md-5">
                                <label for="perfil_{{ $keys }}">Perfiles: </label>
                                <select name="perfil_{{ $keys }}" class="form-control" id="perfil_{{ $keys }}">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <label>&nbsp;</label>
                                <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenPerfil_{{ $keys }}]" id="hiddenPerfil_{{ $keys }}" class="campo-requerido" />
                                <button id="addperfil_{{ $keys }}" type="button" class="form-control btn btn-primary btn-add"><i class="fas fa-plus"></i></button>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div id="content_list_perfil_{{ $keys }}" class="col-md-12"></div>

                            <div class="col-md-12">
                                <ul id="list_perfil_{{ $keys }}">
                                </ul>
                            </div>
                        </div>
                        @break
                    @case('59')
                        <div class="form-group row" id="subencabezado_{{ $keys }}">
                            <div class="col-md-12" id="campodeaccion_{{ $keys }}"></div>
                            <div class="col-md-12">
                                <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenOtros_{{ $keys }}]" id="hiddenOtros_{{ $keys }}"/>
                                <label for="altabajacambio_{{ $keys }}">Tipo de Solicitud:</label>
                                <select name="aplicaciones[{{ $keys }}][altabajacambio_{{ $keys }}]" id="altabajacambio_{{ $keys }}" class="form-control campo-requerido tiposolicitudselect">
                                    <option value="">Seleccione...</option>
                                    <option value="a">Alta</option>
                                    <option value="b">Baja</option>
                                    <option value="c">Cambio</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="salas_{{ $keys }}">Salas:</label>
                                <select name="aplicaciones[{{ $keys }}][salas_{{ $keys }}]" id="salas_{{ $keys }}" class="form-control salasselect campo-requerido">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="perfil_{{ $keys }}">Perfil:</label>
                                <select name="aplicaciones[{{ $keys }}][perfil_{{ $keys }}]" id="perfil_{{ $keys }}" class="form-control campo-requerido">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="otro_{{ $keys }}">Otro: </label>
                                <input type="text" name="aplicaciones[{{ $keys }}][otro_{{ $keys }}]" id="otro_{{ $keys }}" class="form-control">
                            </div>
                        </div> 
                        @break
                    @case('63')
                        <div class="form-group row" id="subencabezado_{{ $keys }}">
                            <div class="col-md-12" id="campodeaccion_{{ $keys }}"></div>
                            <div class="col-md-6">
                                <label for="altabajacambio_{{ $keys }}">Tipo de Solicitud:</label>
                                <select name="aplicaciones[{{ $keys }}][altabajacambio_{{ $keys }}]" id="altabajacambio_{{ $keys }}" class="form-control campo-requerido tiposolicitudselect">
                                    <option value="">Seleccione...</option>
                                    <option value="a">Alta</option>
                                    <option value="b">Baja</option>
                                    <option value="c">Cambio</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="rol_{{ $keys }}">Rol: </label>
                                <select name="aplicaciones[{{ $keys }}][hiddenRol_{{ $keys }}]" class="form-control campo-requerido" id="rol_{{ $keys }}">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                        </div>
                        @break
                    @case('1006')
                        <div class="form-group row" id="subencabezado_{{ $keys }}">
                            <div class="col-md-12" id="campodeaccion_{{ $keys }}"></div>
                            <div class="col-md-6">
                                <label for="altabajacambio_{{ $keys }}">Tipo de Solicitud:</label>
                                <select name="aplicaciones[{{ $keys }}][altabajacambio_{{ $keys }}]" id="altabajacambio_{{ $keys }}" class="form-control campo-requerido tiposolicitudselect">
                                    <option value="">Seleccione...</option>
                                    <option value="a">Alta</option>
                                    <option value="b">Baja</option>
                                    <option value="c">Cambio</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="rol_{{ $keys }}">Rol: </label>
                                <select name="aplicaciones[{{ $keys }}][hiddenRol_{{ $keys }}]" class="form-control campo-requerido" id="rol_{{ $keys }}">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                            <!-- <div class="col-md-1">
                                <label>&nbsp;</label>
                                <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenRol_{{ $keys }}]" id="hiddenRol_{{ $keys }}" class="campo-requerido" />
                                <button id="addrol_{{ $keys }}" type="button" class="form-control btn btn-primary btn-add"><i class="fas fa-plus"></i></button>
                            </div> -->
                        </div>
                        <!-- <div class="form-group row">
                            <div id="content_list_rol_{{ $keys }}" class="col-md-12"></div>

                            <div class="col-md-12">
                                <ul id="list_rol_{{ $keys }}">
                                </ul>
                            </div>
                        </div> -->
                        @break
                    @case('58')
                        <div class="form-group row" id="subencabezado_{{ $keys }}">
                            <div class="col-md-12" id="campodeaccion_{{ $keys }}"></div>
                            <div class="col-md-6">
                                <label for="altabajacambio_{{ $keys }}">Tipo de Solicitud:</label>
                                <select name="aplicaciones[{{ $keys }}][altabajacambio_{{ $keys }}]" id="altabajacambio_{{ $keys }}" class="form-control campo-requerido tiposolicitudselect">
                                    <option value="">Seleccione...</option>
                                    <option value="a">Alta</option>
                                    <option value="b">Baja</option>
                                    <option value="c">Cambio</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="tipousuario_{{ $keys }}">Tipo de Usuario: </label>
                                <select name="aplicaciones[{{ $keys }}][tipousuario_{{ $keys }}]" class="form-control" id="tipousuario_{{ $keys }}">
                                    <option value="">Seleccione...</option>
                                    <!-- <option value="soporte">Soporte</option>
                                    <option value="vendedor">Vendedor</option>
                                    <option value="otros">Otros</option> -->
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5">
                                <label for="perfil_{{ $keys }}">Perfiles: </label>
                                <select name="perfil_{{ $keys }}" class="form-control" id="perfil_{{ $keys }}">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <label>&nbsp;</label>
                                <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenPerfil_{{ $keys }}]" id="hiddenPerfil_{{ $keys }}" class="campo-requerido" />
                                <button id="addperfil_{{ $keys }}" type="button" class="form-control btn btn-primary btn-add"><i class="fas fa-plus"></i></button>
                            </div>
                            <div class="col-md-6"> 
                                <label for="empresa_{{ $keys }}">Empresas:</label>
                                <select name="aplicaciones[{{ $keys }}][empresa_{{ $keys }}]" class="form-control campo-requerido" id="empresa_{{ $keys }}">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                            <!-- <div class="col-md-1">
                                <label>&nbsp;</label>
                                <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenEmpresa_{{ $keys }}]" id="hiddenEmpresa_{{ $keys }}" />
                                <button id="addempresa_{{ $keys }}" type="button" class="form-control btn btn-primary btn-add"><i class="fas fa-plus"></i></button>
                            </div> -->
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div id="content_list_perfil_{{ $keys }}" class="col-md-12"></div>
    
                                    <div class="col-md-12">
                                        <ul id="list_perfil_{{ $keys }}">
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div id="content_list_empresa_{{ $keys }}" class="col-md-12"></div>

                                    <div class="col-md-12">
                                        <ul id="list_empresa_{{ $keys }}">
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @break
                    @case('66')
                        <div class="form-group row" id="subencabezado_{{ $keys }}">
                            <div class="col-md-12" id="campodeaccion_{{ $keys }}"></div>
                            <div class="col-md-6">
                                <label for="altabajacambio_{{ $keys }}">Tipo de Solicitud:</label>
                                <select name="aplicaciones[{{ $keys }}][altabajacambio_{{ $keys }}]" id="altabajacambio_{{ $keys }}" class="form-control campo-requerido tiposolicitudselect">
                                    <option value="">Seleccione...</option>
                                    <option value="a">Alta</option>
                                    <option value="b">Baja</option>
                                    <option value="c">Cambio</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="grupo_{{ $keys }}">Grupo: </label>
                                <select name="aplicaciones[{{ $keys }}][hiddenGrupo_{{ $keys }}]" class="form-control campo-requerido" id="grupo_{{ $keys }}">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                            <!-- <div class="col-md-1">
                                <label>&nbsp;</label>
                                <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenGrupo_{{ $keys }}]" id="hiddenGrupo_{{ $keys }}" class="campo-requerido" />
                                <button id="addgrupo_{{ $keys }}" type="button" class="form-control btn btn-primary btn-add"><i class="fas fa-plus"></i></button>
                            </div> -->
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="dominio_{{ $keys }}">Dominio: </label>
                                 <input type="text" name="aplicaciones[{{ $keys }}][dominio_{{ $keys }}]" id="dominio_{{ $keys }}" class="form-control"/>
                                <!-- <select name="aplicaciones[{{ $keys }}][dominio_{{ $keys }}]" id="dominio_{{ $keys }}" class="form-control campo-requerido">
                                    <option value="">Seleccione...</option>
                                    <option value="CORP">CORP</option>
                                </select> -->
                            </div>
                            <div class="col-md-6">
                                <label for="usuario_{{ $keys }}">Usuario: </label>
                                <input type="text" name="aplicaciones[{{ $keys }}][usuario_{{ $keys }}]" id="usuario_{{ $keys }}" class="form-control"/>
                            </div>
                        </div>
                        <!-- <div class="form-group row">
                            <div id="content_list_grupo_{{ $keys }}" class="col-md-12"></div>

                            <div class="col-md-12">
                                <ul id="list_grupo_{{ $keys }}">
                                </ul>
                            </div>
                        </div> -->
                        @break
                    @case('60')
                        <div class="form-group row" id="subencabezado_{{ $keys }}">
                            <div class="col-md-12" id="campodeaccion_{{ $keys }}"></div>
                            <div class="col-md-6">
                                <label for="altabajacambio_{{ $keys }}">Tipo de Solicitud:</label>
                                <select name="aplicaciones[{{ $keys }}][altabajacambio_{{ $keys }}]" id="altabajacambio_{{ $keys }}" class="form-control campo-requerido tiposolicitudselect">
                                    <option value="">Seleccione...</option>
                                    <option value="a">Alta</option>
                                    <option value="b">Baja</option>
                                    <option value="c">Cambio</option>
                                </select>
                            </div>
                            <div class="col-md-5">
                                <label for="emisora_{{ $keys }}">Emisora:</label>
                                <select name="emisora_{{ $keys }}" id="emisora_{{ $keys }}" class="form-control emisoraselect">
                                    <option value="">Seleccione...</option>
                                    <!-- <option value="Televisa">Televisa</option>
                                    <option value="Cable">Cable</option>-->
                                </select>
                            </div>
                            <div class="col-md-1">
                                <label>&nbsp;</label>
                                <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenEmisora_{{ $keys }}]" id="hiddenEmisora_{{ $keys }}" class="campo-requerido">
                                <button id="addemisora_{{ $keys }}" type="button" class="form-control btn btn-primary btn-add"><i class="fas fa-plus"></i></button>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-11">
                                <label for="rol_{{ $keys }}">Roles: </label>
                                <select name="rol_{{ $keys }}" class="form-control" id="rol_{{ $keys }}">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <label>&nbsp;</label>
                                <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenRol_{{ $keys }}]" id="hiddenRol_{{ $keys }}" class="campo-requerido" />
                                <button id="addrol_{{ $keys }}" type="button" class="form-control btn btn-primary btn-add"><i class="fas fa-plus"></i></button>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div id="content_list_emisora_{{ $keys }}" class="col-md-6"></div>

                                <div class="col-md-6">
                                    <ul id="list_emisora_{{ $keys }}">
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div id="content_list_rol_{{ $keys }}" class="col-md-6"></div>

                                <div class="col-md-6">
                                    <ul id="list_rol_{{ $keys }}">
                                    </ul>
                                </div>
                            </div>
                        </div>
                        @break
                    @case('61')
                        <div class="form-group row" id="subencabezado_{{ $keys }}">
                            <div class="col-md-12" id="campodeaccion_{{ $keys }}"></div>
                            <div class="col-md-6">
                                <input type="hidden" name="aplicaciones[{{ $keys }}][hiddenOtros_{{ $keys }}]" id="hiddenOtros_{{ $keys }}"/>
                                <label for="altabajacambio_{{ $keys }}">Tipo de Solicitud:</label>
                                <select name="aplicaciones[{{ $keys }}][altabajacambio_{{ $keys }}]" id="altabajacambio_{{ $keys }}" class="form-control campo-requerido tiposolicitudselect">
                                    <option value="">Seleccione...</option>
                                    <option value="a">Alta</option>
                                    <option value="b">Baja</option>
                                    <option value="c">Cambio</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="lugar_{{ $keys }}">INSTANCIA:</label>
                                <select name="aplicaciones[{{ $keys }}][lugar_{{ $keys }}]" id="lugar_{{ $keys }}" class="form-control instanciaselect campo-requerido">
                                    <option value="">Seleccione...</option>
                                    <!-- <option value="Chapultepec">Chapultepec</option>
                                    <option value="San Ángel">San Ángel</option>
                                    <option value="Networks">Networks</option>
                                    <option value="Sng-Cha">Sng-Cha</option> -->
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="perfiltxt_{{ $keys }}">Perfil: </label>
                                <!-- <input type="text" name="perfiltxt_{{ $keys }}" id="perfiltxt_{{ $keys }}" class="form-control"/> -->
                                <select class="form-control campo-requerido" name="aplicaciones[{{ $keys }}][perfil_{{ $keys }}] " id="perfil_{{ $keys }}">
                                    <option value="">Seleccione...</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="otrostxt_{{ $keys }}">Otros:</label>
                                <textarea name="aplicaciones[{{ $keys }}][otrostxt_{{ $keys }}]" id="otrostxt_{{ $keys }}" class="form-control"></textarea>
                                <!-- <input type="text" name="aplicaciones[{{ $keys }}][otrostxt_{{ $keys }}]" id="otrostxt_{{ $keys }}" class="form-control"/> -->
                            </div>
                        </div>
                        @break
                @endswitch
            </div>
        </div>
        @endforeach
    </div>
    <div class="form-group row" style="margin-top:10px;">
        <div class="col-md-6">
            <a href="{{ route('seleccionfusapps') }}" class="btn btn-warning btn-block" style="color:#FFFFFF;">{{ __('Regresar') }}</a>
        </div>
        <div class="col-md-6">
            <input type="button" value="Guardar FUS" id="enviar" class="form-control btn btn-primary" />
        </div>
    </div>
</div>
</form>
@endslot
@endcomponent
@push('scripts')
<script>
    $(document).ready(function() {
        $(".btn-selected-all").click(function(){
            var idElement = $(this).data("id");
            // console.log(idElement);
            $("#"+idElement+" option").prop("selected", true);
            $("#opt_"+idElement).prop("selected", false);
        });
        $(document).on("change", ".tiposolicitudselect", function() {
            var idApp = $(this).attr('id');
            idApp = idApp.split('_');
            idApp = idApp[1];
            
            switch ($(this).val()) {
                case 'b':
                    $("[id$='_"+idApp+"']").prop('disabled', true);
                    $("[id$='_"+idApp+"']").prop('checked', false);
                    $(this).prop('disabled', false);
                    $('#accioncambio_'+idApp).remove();
                    break;
                case 'c':
                    if (idApp != "1009"){
                        $("[id$='_"+idApp+"']").prop('disabled', false);
                        switch (idApp) {
                            case "20":
                                $('#addresponsabilidad_'+idApp).prop('disabled', true);
                                $('#addresponsabilidadintermex_'+idApp).prop('disabled', true);
                                $('#empresa_prod_'+idApp).prop('disabled', true);
                                $('#responsabilidad_prod_'+idApp).prop('disabled', true);
                                $('#empresa_intermex_'+idApp).prop('disabled', true);
                                $('#responsabilidad_intermex_'+idApp).prop('disabled', true);
                                break;
                            case "16":
                            case "64":
                                $('#addreporteprod_'+idApp).prop('disabled', true);
                                $('#addreporteintermex_'+idApp).prop('disabled', true);
                                break;
                        }
                    }else{
                        a = $(".checkboxPerfil:checked").val();
                        if (a == 'on') {
                            $("#perfiles_"+idApp).prop('disabled', true);
                            $("#idred_"+idApp).prop('disabled', true);
                            $("#nombrejefeinmediato_"+idApp).prop('disabled', true);
                            $("#ubicacionlaboral_"+idApp).prop('disabled', true);
                            $("#perfillandmarksales_"+idApp).prop('disabled', false);
                            $("#motivoasignacion_"+idApp).prop('disabled', false);
                            $("#fechaasignacion_"+idApp).prop('disabled', false);
                        }else{
                            $("#perfiles_"+idApp).prop('disabled', false);
                            $("#idred_"+idApp).prop('disabled', false);
                            $("#nombrejefeinmediato_"+idApp).prop('disabled', false);
                            $("#ubicacionlaboral_"+idApp).prop('disabled', false);
                            $("#perfillandmarksales_"+idApp).prop('disabled', true);
                            $("#motivoasignacion_"+idApp).prop('disabled', true);
                            $("#fechaasignacion_"+idApp).prop('disabled', true);
                        }
                    }
                    var quitarAgregar = '<div class="mb-3" id="accioncambio_'+idApp+'">'+
                            '<label for="accion_'+idApp+'">Acción</label>'+
                            '<select id="accion_'+idApp+'" name="aplicaciones['+idApp+'][accion_'+idApp+']" class="form-control campo-requerido">'+
                                '<option value="">Seleccione...</option>'+
                                '<option value="Agregar">Agregar</option>'+
                                '<option value="Quitar">Quitar</option>'+
                            '</select>'+
                        '</div>';
                        $('#campodeaccion_'+idApp).html(quitarAgregar);
                    break;
                default:
                    if (idApp != "1009"){
                        // $('#campodeaccion_'+idApp).children('#accioncambio_'+idApp).remove();
                        $("[id$='_"+idApp+"']").prop('disabled', false);
                        switch (idApp) {
                            case "20":
                                console.log("ERP");
                                $('#addresponsabilidad_'+idApp).attr('disabled', true);
                                $('#addresponsabilidadintermex_'+idApp).attr('disabled', true);
                                $('#empresa_prod_'+idApp).prop('disabled', true);
                                $('#responsabilidad_prod_'+idApp).prop('disabled', true);
                                $('#empresa_intermex_'+idApp).prop('disabled', true);
                                $('#responsabilidad_intermex_'+idApp).prop('disabled', true);
                                break;
                            case "16":
                            case "64":
                                $('#addreporteprod_'+idApp).prop('disabled', true);
                                $('#addreporteintermex_'+idApp).prop('disabled', true);
                                break;
                        }
                    }else{
                        a = $(".checkboxPerfil:checked").val();
                        if (a == 'on') {
                            $("#perfiles_"+idApp).prop('disabled', true);
                            $("#idred_"+idApp).prop('disabled', true);
                            $("#nombrejefeinmediato_"+idApp).prop('disabled', true);
                            $("#ubicacionlaboral_"+idApp).prop('disabled', true);
                            $("#perfillandmarksales_"+idApp).prop('disabled', false);
                            $("#motivoasignacion_"+idApp).prop('disabled', false);
                            $("#fechaasignacion_"+idApp).prop('disabled', false);
                        }else{
                            $("#perfiles_"+idApp).prop('disabled', false);
                            $("#idred_"+idApp).prop('disabled', false);
                            $("#nombrejefeinmediato_"+idApp).prop('disabled', false);
                            $("#ubicacionlaboral_"+idApp).prop('disabled', false);
                            $("#perfillandmarksales_"+idApp).prop('disabled', true);
                            $("#motivoasignacion_"+idApp).prop('disabled', true);
                            $("#fechaasignacion_"+idApp).prop('disabled', true);
                        }
                    }
                    $('#accioncambio_'+idApp).remove();
                    break;
            }
        });
             
        $('.check-not-disabled').change(function() {
            var complementoId = $(this).data('datos');
            var complementoId2 = $(this).attr('id');
            var complementoId3 = $(this).data('idreal');
            var aquien = $(this).data('aquien');
            
            if(this.checked) {
                $("#empresa_"+complementoId2).prop('disabled', false);
                $("#responsabilidad_"+complementoId2).prop('disabled', false);
                $("#"+complementoId3).prop('disabled', false);
                $('#add'+complementoId).prop('disabled', false);
                $("#"+aquien).addClass("campo-requerido")
            } else {
                $("#empresa_"+complementoId2).prop('disabled', true);
                $("#responsabilidad_"+complementoId2).prop('disabled', true);
                $("#"+complementoId3).prop('disabled', true);
                $('#add'+complementoId).prop('disabled', true);
                $("#"+aquien).removeClass("campo-requerido")
            }
        });
        $('.select-otro-input').change(function(){
            var app = $(this).attr('data-app');
            
            if($('#tipo_'+app+' option:selected').val() == 'Otro') {
                $('#descripcionotro_'+app).prop('disabled', false);
            } else {
                $('#descripcionotro_'+app).prop('disabled', true);
            }
        });

        $(document).on("change",".appselect", function(){
            select = $(this).attr('data-type');
            var claveArch = select.split('_');
            if (claveArch[1] == 0) {
                claveArch[1] = "";
                var element = document.getElementById("archivo"+claveArch[1]);
                element.classList.add("campo-requerido");
            }
        });
        $(document).on("change", ".car-arch", function() {
            var fileName = this.files.length;
            select = $(this).attr('data-type');
            var claveArch = select.split('_');
            var app = document.querySelectorAll('#id_app_fus'+id);
            
            if (claveArch[1] == 0) {
                    claveArch[1] = "";
                }
            if (fileName == 0 ) {
                $("#id_app_fus"+id).val('');
                Swal.fire({
                    icon: 'error',
                    title: 'Advertencia',
                    text: 'El documento es obligatorio'
                })
            }else{
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
        archivo = document.querySelectorAll('#archivo'+id);
        app = document.querySelectorAll('#id_app_fus'+id);
        valido = true; // es valido hasta demostrar lo contrario
        // recorremos todos los campos
        [].slice.call(archivo).forEach(function(archivo) {
            [].slice.call(app).forEach(function(app) {
                // console.log(app.selectedIndex);
                if (archivo.value === '' || app.selectedIndex === 0) {
                    valido = false;
                }
            });
        });
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
                        '<select class="form-control campo-requerido" id="id_app_fus'+id+'" name="archivo['+id+'][app]" data-type="app_'+id+'">'+
                                '<option value="">Selecciona una aplicación</option>'+
                                    '@foreach($aplicaciones AS $keys => $valApp)'+
                                        '<option value="{{ $keys }}">{{ $valApp }}</option>'+
                                    '@endforeach'+
                        '</select>'+
                        '</div>'+
                            '<button type="button" class="btn btn-danger remover_campo" id="remover_campo">Remover</button>'+
                    '</div>');                          
        }
        else
        {
            Swal.fire({
                icon: 'error',
                title: 'Advertencia',
                text: 'Los campos del anexo son obligatorios'
                // footer: '<a href>Why do I have this issue?</a>'
            })
        }
    });

        // Remover div anterior
        $('#documento').on("click",".remover_campo",function(e) {
            e.preventDefault();
            $(this).parent('div').remove();
        });
        $(document).on("change",".select-add",function(e){
            var idselectcontrol = $(this).attr('id');
            var clave = idselectcontrol.split('_');
            var valSelect = $(this).val();
            switch (clave[0]){
                case 'areasforparadigm':
                    var hiddenIdValor = '#hiddenAreasForParadigm_'+clave[1];
                    $(hiddenIdValor).val(valSelect);
                    return;
                    break;
            }
        });

        $('.btn-add').click(function() {
            var idBtnControl;
            if($(this).attr('id') != undefined && $(this).attr('id') != null) {
                idBtnControl = $(this).attr('id');
            } else {
                idBtnControl = $(this).data('cod');
            }
            var claveApp = idBtnControl.split('_');
            // '#addrol_'+claveApp
            switch (claveApp[0]) {
                case 'addrol':
                    var tipo = 'rol';
                    var hiddenIdTipo = '#hiddenRol_'+claveApp[1];
                    var selectSelectedIdTipo = '#rol_'+claveApp[1]+' option:selected';
                    var divUlIdTipo = '#content_list_rol_'+claveApp[1];
                    var ulIdTipo = '#list_rol_'+claveApp[1];
                    var titleList = 'Lista de Roles agregados';
                    break;
                case 'addcanalesparadigm':
                    var tipo = 'canales';
                    var hiddenIdTipo = '#hiddenCanalesparadigm_'+claveApp[1];
                    var selectSelectedIdTipo = '#canalesparadigm_'+claveApp[1]+' option:selected';
                    var divUlIdTipo = '#content_list_canalesparadigm_'+claveApp[1];
                    var ulIdTipo = '#list_canalesparadigm_'+claveApp[1];
                    var titleList = 'Lista de canales agregados';

                    $.each($(selectSelectedIdTipo), function() {
                        if($(this).val() != '') {
                            var valor = $(hiddenIdTipo).val();
                            var valSelect = $(this).val();
                            var html = '<li id="elementoLista_'+valSelect+'">'+$(this).text()+' <input type="checkbox" value="'+valSelect+'" class="checkremove_'+tipo+'_'+claveApp[1]+'"/></li>';
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
                                $(divUlIdTipo).html('<strong>'+titleList+'</strong> <input type="button" class="quitarlista btn btn-danger btn-sm" data-lista="'+ulIdTipo+'" data-afectado="'+hiddenIdTipo+'" data-tipo="checkremove_'+tipo+'_'+claveApp[1]+'" value="Quitar de la lista" /> <label for="seleccionartodo_'+claveApp[1]+'"><input type="checkbox" class="seleccionartodo" id="seleccionartodo_'+claveApp[1]+'" data-tipo="'+tipo+'" data-clave="'+claveApp[1]+'"/>Seleccionar todo</label>');
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
                    break;
                case 'addapp':
                    var tipo = 'aplicacion';
                    var hiddenIdTipo = '#hiddenAplicacion_'+claveApp[1];
                    var selectSelectedIdTipo = '#aplicacion_'+claveApp[1]+' option:selected';
                    var divUlIdTipo = '#content_list_aplicacion_'+claveApp[1];
                    var ulIdTipo = '#list_aplicacion_'+claveApp[1];
                    var titleList = 'Lista de Aplicaciones Agregadas';
                    break;
                case 'addemisora':
                    var tipo = 'emisora';
                    var hiddenIdTipo = '#hiddenEmisora_'+claveApp[1];
                    var selectSelectedIdTipo = '#emisora_'+claveApp[1]+' option:selected';
                    var divUlIdTipo = '#content_list_emisora_'+claveApp[1];
                    var ulIdTipo = '#list_emisora_'+claveApp[1];
                    var titleList = 'Lista de Emisoras Agregadas';
                    break;
                case 'addnotificacion':
                    var tipo = 'notificacion';
                    var hiddenIdTipo = '#hiddenNotificacion_'+claveApp[1];
                    var selectSelectedIdTipo = '#notificacion_'+claveApp[1]+' option:selected';
                    var divUlIdTipo = '#content_list_notificacion_'+claveApp[1];
                    var ulIdTipo = '#list_notificacion_'+claveApp[1];
                    var titleList = 'Lista de Notificaciones Agregadas';
                    break;
                case 'addperfil':
                    var tipo = 'perfil';
                    var hiddenIdTipo = '#hiddenPerfil_'+claveApp[1];
                    var selectSelectedIdTipo = '#perfil_'+claveApp[1]+' option:selected';
                    var divUlIdTipo = '#content_list_perfil_'+claveApp[1];
                    var ulIdTipo = '#list_perfil_'+claveApp[1];
                    var titleList = 'Lista de Perfiles agregados';
                    break;
                case 'addsubsistemas':
                    var tipo = 'subsistemas';
                    var hiddenIdTipo = '#hiddenSubsistemas_'+claveApp[1];
                    var selectSelectedIdTipo = '#subsistemas_'+claveApp[1]+' option:selected';
                    var divUlIdTipo = '#content_list_subsistemas_'+claveApp[1];
                    var ulIdTipo = '#list_subsistemas_'+claveApp[1];
                    var titleList = 'Lista de Sub-Sistemas agregados';
                    break;
                case 'addreporte':
                    var tipo = 'reporte';
                    var hiddenIdTipo = '#hiddenReporte_'+claveApp[1];
                    var selectSelectedIdTipo = '#reporte_'+claveApp[1]+' option:selected';
                    var divUlIdTipo = '#content_list_reporte_'+claveApp[1];
                    var ulIdTipo = '#list_reporte_'+claveApp[1];
                    var titleList = 'Lista de reportes agregados';
                    break;
                case 'addreporteprod':
                    var tipo = 'reporte';
                    var hiddenIdTipo = '#hiddenReporteProd_'+claveApp[1];
                    var selectSelectedIdTipo = '#prodreporte_'+claveApp[1]+' option:selected';
                    var divUlIdTipo = '#content_list_reporte_prod_'+claveApp[1];
                    var ulIdTipo = '#list_reporte_prod_'+claveApp[1];
                    var titleList = 'Lista de reportes agregados para Prod';
                    break;
                case 'addinstancia':
                    var tipo = 'instancia';
                    var hiddenIdTipo = '#hiddenInstancia_'+claveApp[1];
                    var selectSelectedIdTipo = '#instancia_'+claveApp[1]+' option:selected';
                    var divUlIdTipo = '#content_list_instancia_'+claveApp[1];
                    var ulIdTipo = '#list_instancia_'+claveApp[1];
                    var titleList = 'Lista de instancias agregadas';
                    break;
                case 'addreporteintermex':
                    var tipo = 'reporte';
                    var hiddenIdTipo = '#hiddenReporteIntermex_'+claveApp[1];
                    var selectSelectedIdTipo = '#intermexreporte_'+claveApp[1]+' option:selected';
                    var divUlIdTipo = '#content_list_reporte_intermex_'+claveApp[1];
                    var ulIdTipo = '#list_reporte_intermex_'+claveApp[1];
                    var titleList = 'Lista de reportes agregados para Intermex';
                    break;
                case 'addgrupo':
                    var tipo = 'grupo';
                    var hiddenIdTipo = '#hiddenGrupo_'+claveApp[1];
                    var selectSelectedIdTipo = '#grupo_'+claveApp[1]+' option:selected';
                    var divUlIdTipo = '#content_list_grupo_'+claveApp[1];
                    var ulIdTipo = '#list_grupo_'+claveApp[1];
                    var titleList = 'Lista de Grupos agregados';
                    break;
                case 'addgrupoeditorial':
                    var tipo = 'grupoeditorial';
                    var hiddenIdTipo = '#hiddenGrupoeditorial_'+claveApp[1];
                    var selectSelectedIdTipo = '#grupoeditorial_'+claveApp[1]+' option:selected';
                    var divUlIdTipo = '#content_list_grupo_editorial_'+claveApp[1];
                    var ulIdTipo = '#list_grupo_editorial_'+claveApp[1];
                    var titleList = 'Lista de Grupos Editoriales agregados';
                    break;
                case 'addequipo':
                    var tipo = 'equipo';
                    var hiddenIdTipo = '#hiddenEquipo_'+claveApp[1];
                    var selectSelectedIdTipo = '#equipo_'+claveApp[1]+' option:selected';
                    var divUlIdTipo = '#content_list_equipo_'+claveApp[1];
                    var ulIdTipo = '#list_equipo_'+claveApp[1];
                    var titleList = 'Lista de Equipos agregados';
                    break;
                case 'addtipocaso':
                    var tipo = 'tipocaso';
                    var hiddenIdTipo = '#hiddenTipocaso_'+claveApp[1];
                    var selectSelectedIdTipo = '#tipocaso_'+claveApp[1]+' option:selected';
                    var divUlIdTipo = '#content_list_tipocaso_'+claveApp[1];
                    var ulIdTipo = '#list_tipocaso_'+claveApp[1];
                    var titleList = 'Lista de Tipo de Casos agregados';
                    break;
                case 'addempresa':
                    var tipo = 'empresa';
                    var hiddenIdTipo = '#hiddenEmpresa_'+claveApp[1];
                    var selectSelectedIdTipo = '#empresa_'+claveApp[1]+' option:selected';
                    var divUlIdTipo = '#content_list_empresa_'+claveApp[1];
                    var ulIdTipo = '#list_empresa_'+claveApp[1];
                    var titleList = 'Lista de empresas agregadas';
                    break;
                case 'addfacultades':
                    var tipo = 'facultades';
                    var hiddenIdTipo = '#hiddenFacultades_'+claveApp[1];
                    var selectSelectedIdTipo = '#facultades_'+claveApp[1]+' option:selected';
                    var divUlIdTipo = '#content_list_facultades_'+claveApp[1];
                    var ulIdTipo = '#list_facultades_'+claveApp[1];
                    var titleList = 'Lista de facultades agregadas';
                    break;
                case 'addresponsabilidadmultiple':
                case 'addresponsabilidadmultipleintermex':
                    var identificador = $(this).data('id-input');
                    
                    var tipo = 'responsabilidad';
                    var hiddenIdTipo = '#hiddenResponsabilidad_'+identificador+'_'+claveApp[1];
                    var selectSelectedIdTipo = '#responsabilidad_'+identificador+'_'+claveApp[1];
                    var valResponsabilidades = $(selectSelectedIdTipo).val();

                    var divUlIdTipo = '#content_list_responsabilidad_'+claveApp[1];
                    var ulIdTipo = '#list_responsabilidad_'+claveApp[1];
                    var titleList = 'Lista de Empresas y Responsabilidades agregadas';

                    var tipo2 = 'empresa';
                    var selectSelectedIdTipo2 = '#empresa_'+identificador+'_'+claveApp[1]+' option:selected';

                    $.each(valResponsabilidades, function( index, idResponsabilidad ) {
                        if(
                            idResponsabilidad != ''
                        ) {
                            var valSelectEmpresa = $(selectSelectedIdTipo2).val();
                            valSelectEmpresa = valSelectEmpresa.split('_');

                            var valor = $(hiddenIdTipo).val();
                            var valSelect = idResponsabilidad;
                            valSelect = valSelect.split('_');
                            
                            var valSelectUnificado = '';
                            if(valSelectEmpresa[0] != '') {
                                valSelectUnificado = valSelectEmpresa[0]+'-'+valSelect[0];
                            } else {
                                valSelectUnificado = '0-'+valSelect[0];
                            }
                            
                            var textoEmpresa = $(selectSelectedIdTipo2).text()+' / ';
                            var textoResponsabilidad = $(selectSelectedIdTipo+' option[value="'+idResponsabilidad+'"]').text();
                            
                            if(claveApp[1] != 1032) {
                                if(textoResponsabilidad.includes('%%%') === true) {
                                    var textoSustitucionEmpresa = textoEmpresa.split(' - ');
                                    textoResponsabilidad = textoResponsabilidad.replace('%%%', textoSustitucionEmpresa[0]);
                                } else {
                                    textoEmpresa = $('#responsabilidad_'+identificador+'_'+claveApp[1]).data('tipo')+' ';
                                }
                            }
                            
                            var html = '<li id="elementoLista_'+valSelectUnificado+'">'+textoEmpresa+textoResponsabilidad+' <input type="checkbox" value="'+valSelectUnificado+'" class="checkremove_'+tipo+'_'+claveApp[1]+'"/></li>';
                            
                            if(
                                valor != ''
                            ) {
                                if(
                                    compararRepetidosAutorizaciones(valor, valSelectUnificado) === true
                                ) {
                                    $(hiddenIdTipo).val(valor+'_'+valSelectUnificado);
                                    $(ulIdTipo).append(html);
                                } else {
                                    swal(
                                        'Validación',
                                        'El '+tipo+'/'+tipo2+' ya se ha agregado, vuelva a intentarlo con otro.',
                                        'warning'
                                    )
                                }
                            } else {
                                $(divUlIdTipo).html('<strong>'+titleList+'</strong> <input type="button" class="quitarlista btn btn-danger btn-sm" data-lista="'+ulIdTipo+'" data-afectado="'+hiddenIdTipo+'" data-tipo="checkremove_'+tipo+'_'+claveApp[1]+'" value="Quitar de la lista" /> <label for="seleccionartodo_'+claveApp[1]+'"><input type="checkbox" class="seleccionartodo" id="seleccionartodo_'+claveApp[1]+'" data-tipo="'+tipo+'" data-clave="'+claveApp[1]+'"/>Seleccionar todo</label>');
                                $(hiddenIdTipo).val(valSelectUnificado);
                                $(ulIdTipo).append(html);
                            }
                            
                        } else {
                            swal(
                                'Validación',
                                'Debe seleccionar un '+tipo+'/'+tipo2+' antes de intentar agregarlo.',
                                'warning'
                            )
                        }
                    });
                                        
                    return false;

                    break;                    
                case 'addresponsabilidad':
                case 'addresponsabilidadintermex':
                    var identificador = $(this).data('id-input');
                    // var idEmpresa = '#empresa_'+identificador+'_'+claveApp[1];
                    
                    var tipo = 'responsabilidad';
                    var hiddenIdTipo = '#hiddenResponsabilidad_'+identificador+'_'+claveApp[1];
                    var selectSelectedIdTipo = '#responsabilidad_'+identificador+'_'+claveApp[1]+' option:selected';

                    var divUlIdTipo = '#content_list_responsabilidad_'+claveApp[1];
                    var ulIdTipo = '#list_responsabilidad_'+claveApp[1];
                    var titleList = 'Lista de Empresas y Responsabilidades agregadas';

                    var tipo2 = 'empresa';
                    // var hiddenIdTipo2 = '#hiddenEmpresa_'+identificador+'_'+claveApp[1];
                    var selectSelectedIdTipo2 = '#empresa_'+identificador+'_'+claveApp[1]+' option:selected';

                    // var divUlIdTipo2 = '#content_list_empresa_'+claveApp[1];
                    // var ulIdTipo2 = '#list_empresa_'+claveApp[1];
                    // var titleList2 = 'Lista de empr esas agregadas';
                    
                    if(
                        $(selectSelectedIdTipo).val() != ''
                    ) {
                        var valSelectEmpresa = $(selectSelectedIdTipo2).val();
                        valSelectEmpresa = valSelectEmpresa.split('_');

                        var valor = $(hiddenIdTipo).val();
                        var valSelect = $(selectSelectedIdTipo).val();
                        valSelect = valSelect.split('_');
                        
                        var valSelectUnificado = '';
                        if(valSelectEmpresa[0] != '') {
                            valSelectUnificado = valSelectEmpresa[0]+'-'+valSelect[0];
                        } else {
                            valSelectUnificado = '0-'+valSelect[0];
                        }
                        
                        var textoEmpresa = $(selectSelectedIdTipo2).text()+' / ';
                        var textoResponsabilidad = $(selectSelectedIdTipo).text();
                        
                        if(claveApp[1] != 1032) {
                            if(textoResponsabilidad.includes('%%%') === true) {
                                var textoSustitucionEmpresa = textoEmpresa.split(' - ');
                                textoResponsabilidad = textoResponsabilidad.replace('%%%', textoSustitucionEmpresa[0]);
                            } else {
                                textoEmpresa = $('#responsabilidad_'+identificador+'_'+claveApp[1]).data('tipo')+' ';
                            }
                        }
                        
                        var html = '<li id="elementoLista_'+valSelectUnificado+'">'+textoEmpresa+textoResponsabilidad+' <input type="checkbox" value="'+valSelectUnificado+'" class="checkremove_'+tipo+'_'+claveApp[1]+'"/></li>';
                        
                        if(
                            valor != ''
                        ) {
                            if(
                                compararRepetidosAutorizaciones(valor, valSelectUnificado) === true
                            ) {
                                $(hiddenIdTipo).val(valor+'_'+valSelectUnificado);
                                $(ulIdTipo).append(html);
                            } else {
                                swal(
                                    'Validación',
                                    'El '+tipo+'/'+tipo2+' ya se ha agregado, vuelva a intentarlo con otro.',
                                    'warning'
                                )
                            }
                        } else {
                            $(divUlIdTipo).html('<strong>'+titleList+'</strong> <input type="button" class="quitarlista btn btn-danger btn-sm" data-lista="'+ulIdTipo+'" data-afectado="'+hiddenIdTipo+'" data-tipo="checkremove_'+tipo+'_'+claveApp[1]+'" value="Quitar de la lista" /> <label for="seleccionartodo_'+claveApp[1]+'"><input type="checkbox" class="seleccionartodo" id="seleccionartodo_'+claveApp[1]+'" data-tipo="'+tipo+'" data-clave="'+claveApp[1]+'"/>Seleccionar todo</label>');
                            $(hiddenIdTipo).val(valSelectUnificado);
                            $(ulIdTipo).append(html);
                        }
                        
                    } else {
                        swal(
                            'Validación',
                            'Debe seleccionar un '+tipo+'/'+tipo2+' antes de intentar agregarlo.',
                            'warning'
                        )
                    }
                                        
                    return false;

                    break;
                case 'addpermisos':
                    var tipo = 'tipopermisos';
                    var hiddenIdTipo = '#hiddenPermisos_'+claveApp[1];
                    var selectSelectedIdTipo = '#permisos_'+claveApp[1]+' option:selected';
                    var divUlIdTipo = '#content_list_permisos_'+claveApp[1];
                    var ulIdTipo = '#list_permisos_'+claveApp[1];
                    var titleList = 'Lista de tipo de permisos agregados';
                    break;
                case 'addportafolios':
                    var tipo = 'portafolios';
                    var hiddenIdTipo = '#hiddenPortafolios_'+claveApp[1];
                    var selectSelectedIdTipo = '#portafolios_'+claveApp[1]+' option:selected';
                    var divUlIdTipo = '#content_list_portafolios_'+claveApp[1];
                    var ulIdTipo = '#list_portafolios_'+claveApp[1];
                    var titleList = 'Lista de portafolios agregados';
                    break;
                case 'addpermisos':
                    var tipo = 'permisos';
                    var hiddenIdTipo = '#hiddenPermisos_'+claveApp[1];
                    var selectSelectedIdTipo = '#permisos_'+claveApp[1]+' option:selected';
                    var divUlIdTipo = '#content_list_permisos_'+claveApp[1];
                    var ulIdTipo = '#list_permisos_'+claveApp[1];
                    var titleList = 'Lista de permisos agregados';
                    break;    
                case 'addsistema':
                    var tipo = 'sistema';
                    var hiddenIdTipo = '#hiddenSistema_'+claveApp[1];
                    var selectSelectedIdTipo = '#sistema_'+claveApp[1]+' option:selected';
                    var divUlIdTipo = '#content_list_sistema_'+claveApp[1];
                    var ulIdTipo = '#list_sistema_'+claveApp[1];
                    var titleList = 'Lista de sistemas agregados';
                    break;    
                case 'addfuncion':
                    var tipo = 'funcion';
                    var hiddenIdTipo = '#hiddenFuncion_'+claveApp[1];
                    var selectSelectedIdTipo = '#funcion_'+claveApp[1]+' option:selected';
                    var divUlIdTipo = '#content_list_funcion_'+claveApp[1];
                    var ulIdTipo = '#list_funcion_'+claveApp[1];
                    var titleList = 'Lista de funciones agregados';
                    break;
            }
            if($(selectSelectedIdTipo).val() != '') {
                var valor = $(hiddenIdTipo).val();
                var valSelect = $(selectSelectedIdTipo).val();
                var html = '<li id="elementoLista_'+valSelect+'">'+$(selectSelectedIdTipo).text()+' <input type="checkbox" value="'+valSelect+'" class="checkremove_'+tipo+'_'+claveApp[1]+'"/></li>';
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
                    $(divUlIdTipo).html('<strong>'+titleList+'</strong> <input type="button" class="quitarlista btn btn-danger btn-sm" data-lista="'+ulIdTipo+'" data-afectado="'+hiddenIdTipo+'" data-tipo="checkremove_'+tipo+'_'+claveApp[1]+'" value="Quitar de la lista" /> <label for="seleccionartodo_'+claveApp[1]+'"><input type="checkbox" class="seleccionartodo" id="seleccionartodo_'+claveApp[1]+'" data-tipo="'+tipo+'" data-clave="'+claveApp[1]+'"/>Seleccionar todo</label>');
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
        $(document).on('click', '.seleccionartodo', function(){
            var clave = $(this).data('clave');
            var tipo = $(this).data('tipo');
            if($(this).is(':checked')) {
                $('.checkremove_'+tipo+'_'+clave).prop('checked', true);
            } else {
                $('.checkremove_'+tipo+'_'+clave).prop('checked', false);
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

        function getCatalogo(clave, idControl, catalogo, tipocatalogo = null, idCatPrin = null, idAut = null, catalogo2 = null) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: 'POST',
                data: { clave: clave, catalogo: catalogo, tipocatalogo: tipocatalogo, idCatPrin: idCatPrin, idAut: idAut, catalogo2: catalogo2 },
                dataType: 'JSON',
                url: '{{ route("getcatalogo") }}',
                async: false,
                beforeSend: function(){
                    // console.log("Cargando");
                },
                complete: function(){
                    // console.log("Listo");
                }
            }).done(function(response){
                var html = '<option value="" id="opt_'+idControl+'">Seleccione...</option>';
                $.each(response, function(key, value) {
                    html += '<option value="'+value.id+'">'+value.cat_op_descripcion+'</option>';
                });
                
                $('#'+idControl).html(html);            
            }).fail(function(response){
                // location.reload();
            });
        }
        // Ejemplo de tipocatalogo prod e intermex
        function getAutorizadores(clave, tipo, idControl, idext = null, tipocatalogo = null, selectMultiple = false) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: 'POST',
                data: { clave: clave, tipo: tipo, idext: idext, tipocatalogo: tipocatalogo },
                dataType: 'JSON',
                url: '{{ route("getconfigsauto") }}',
                async: false,
                beforeSend: function(){
                    // console.log("Cargando");
                },
                complete: function(){
                    // console.log("Listo");
                }
            }).done(function(response){
                if(tipo != 5) {
                    if(selectMultiple === false) {
                        var html = '<option value="">Seleccione...</option>';
                    } else if(selectMultiple === true) {
                        var html = '';
                    }
                    $.each(response, function(key, value) {
                        html += '<option value="'+value.id+'">'+value.rol_mod_rep+'</option>';
                    });
                    $('#'+idControl).html(html);
                } else {
                    var val = '';
                    var count = 1;
                    $.each(response, function(key, value) {
                        if(count == 1) {
                            val += value.id;
                        } else {
                            val += ','+value.id;
                        }
                        count = count+1;
                    });
                    $('#'+idControl).val(val);
                }
            }).fail(function(response){
                // location.reload();
            });
        }
        @foreach($aplicaciones AS $keys => $valApp)
            @switch($keys)
                @case('2')
                    getAutorizadores('{{ $keys }}', 4, 'rol_{{ $keys }}');
                    @break
                @case('3')
                    getAutorizadores('{{ $keys }}', 0, 'aplicacion_{{ $keys }}');
                    @break
                @case('4')
                    getAutorizadores('{{ $keys }}', 0, 'aplicacion_{{ $keys }}');
                    @break
                @case('5')
                    getCatalogo('{{ $keys }}', 'areas_{{ $keys }}', 'AREAS');
                    getCatalogo('{{ $keys }}', 'tipousuario_{{ $keys }}', 'TIPO DE USUARIO');
                    $(document).on('change', '.areaselect', function(){
                        var valorSelect = $(this).val();
                        valorSelect = valorSelect.split('_');
                        getAutorizadores('{{ $keys }}', 6, 'perfil_{{ $keys }}',valorSelect[0]);
                    });
                    @break
                @case('6')
                    getCatalogo('{{ $keys }}', 'tipousuario_{{ $keys }}', 'TIPO DE USUARIO');
                    getAutorizadores('{{ $keys }}', 6, 'perfil_{{ $keys }}');
                    // $(document).on('change', '.perfilselect', function(){
                    //     var textoSelect = $('select[id="perfil_{{ $keys }}"] option:selected').text();
                    //     console.log(textoSelect);
                    //     if (textoSelect.toUpperCase() == 'VENTAS' || textoSelect.toUpperCase() == 'TRÁFICO' || textoSelect.toUpperCase() == 'TRAFICO' || textoSelect.toUpperCase() == 'ACCOUNT SERVICE') {
                    //         var element = document.getElementById("usuariodfp_{{ $keys }}");
                    //         element.classList.add("campo-requerido");
                    //     }else{
                    //         var element = document.getElementById("usuariodfp_{{ $keys }}");
                    //         element.classList.remove("campo-requerido");
                    //     }
                    // });
                    @break
                @case('7')
                    getAutorizadores('{{ $keys }}', 4, 'rol_{{ $keys }}');
                    @break
                @case('9')
                    getCatalogo('{{ $keys }}', 'modulo_{{ $keys }}', 'MODULOS');
                    $(document).on('change', '.moduloselect', function() {
                        var valorSelect = $(this).val();
                        getAutorizadores('{{ $keys }}', 4, 'rol_{{ $keys }}', valorSelect);
                    });
                    getCatalogo('{{ $keys }}', 'empresa_{{ $keys }}', 'EMPRESAS');
                    @break
                @case('10')
                    getAutorizadores('{{ $keys }}', 4, 'rol_{{ $keys }}');
                    @break
                @case('12')
                    getAutorizadores('{{ $keys }}', 5, 'hiddenOtros_{{ $keys }}');
                    // getAutorizadores('{{ $keys }}', 6, 'perfil_{{ $keys }}');
                    @break
                @case('13')
                    // getCatalogo('{{ $keys }}', 'tipousuario_{{ $keys }}', 'TIPO USUARIOS');
                    // $(document).on('change', '.tipousuarioselect', function() {
                        var valorSelect = $(this).val();
                        getAutorizadores('{{ $keys }}', 4, 'rol_{{ $keys }}', valorSelect);
                    // });
                    getCatalogo('{{ $keys }}', 'unidadnegocio_{{ $keys }}', 'UNIDAD DE NEGOCIO');
                    @break
                @case('14')
                    getAutorizadores('{{ $keys }}', 4, 'rol_{{ $keys }}');
                    @break
                @case('15')
                    getAutorizadores('{{ $keys }}', 4, 'rol_{{ $keys }}');
                    @break
                @case('16')
                    $('#prod_{{$keys}}, #intermex_{{$keys}}').click(function(){
                        var validacion_al_menos_uno = $("#validacion_al_menos_uno_{{$keys}}").val();
                    
                        if(validacion_al_menos_uno == "") {
                            validacion_al_menos_uno = 0;
                        }

                        if($(this).is(':checked')) {
                            validacion_al_menos_uno = parseInt(validacion_al_menos_uno)+1;
                        } else {
                            validacion_al_menos_uno = parseInt(validacion_al_menos_uno)-1;
                        }

                        if(validacion_al_menos_uno == 0) {
                            validacion_al_menos_uno = "";
                        }

                        $("#validacion_al_menos_uno_{{$keys}}").val(validacion_al_menos_uno);
                    });
                    getAutorizadores('{{ $keys }}', 2, 'prodreporte_{{ $keys }}', null, 'prod');
                    getAutorizadores('{{ $keys }}', 2, 'intermexreporte_{{ $keys }}', null, 'intermex');
                    @break
                @case('17')
                    getCatalogo('{{ $keys }}', 'hiddenRepositorio_{{ $keys }}', 'REPOSITORIO');
                    $(document).on('change', '.tiporepositorioselect', function() {
                        var valorSelect = $(this).val();
                        // valorSelect = valorSelect.split('_');
                        getCatalogo('{{ $keys }}', 'grupo_{{ $keys }}', 'GRUPOS', null, valorSelect);
                        getAutorizadores('{{ $keys }}', 4, 'rol_{{ $keys }}', valorSelect);
                    });
                    @break
                @case('19')
                    getCatalogo('{{ $keys }}', 'tipousuario_{{ $keys }}', 'TIPO DE USUARIO');
                    getAutorizadores('{{ $keys }}', 12, 'administracion_{{ $keys }}');
                    getCatalogo('{{ $keys }}', 'empresa_{{ $keys }}', 'EMPRESAS');
                    @break
                @case('18')
                    // getAutorizadores('{{ $keys }}', 5, 'hiddenOtros_{{ $keys }}');
                    getAutorizadores('{{ $keys }}', 8, 'empresa_{{ $keys }}');
                    @break
                @case('20')                  
                    $('#prod_{{$keys}}, #intermex_{{$keys}}').click(function(){
                        var validacion_al_menos_uno = $("#validacion_al_menos_uno_{{$keys}}").val();
                    
                        if(validacion_al_menos_uno == "") {
                            validacion_al_menos_uno = 0;
                        }

                        if($(this).is(':checked')) {
                            validacion_al_menos_uno = parseInt(validacion_al_menos_uno)+1;
                        } else {
                            validacion_al_menos_uno = parseInt(validacion_al_menos_uno)-1;
                        }

                        if(validacion_al_menos_uno == 0) {
                            validacion_al_menos_uno = "";
                        }

                        $("#validacion_al_menos_uno_{{$keys}}").val(validacion_al_menos_uno);
                    });
                    
                    $(document).on('change', '.empresaselect', function() {
                        var idSelect = $(this).attr('id');
                        idSelect = idSelect.split('_');
                        var valorSelect = $(this).val();
                        var tipo = $(this).data('tipo');
                        valorSelect = valorSelect.split('_');
                        if(idSelect[2] == '{{ $keys }}') {
                            getAutorizadores('{{ $keys }}', 3, 'responsabilidad_'+tipo+'_{{ $keys }}', valorSelect[0], tipo);
                        }
                    });
                    getAutorizadores('{{ $keys }}', 3, 'responsabilidad_intermex_{{ $keys }}', null, 'intermex');
                    getAutorizadores('{{ $keys }}', 3, 'responsabilidad_prod_{{ $keys }}', null, 'prod');
                    getCatalogo('{{ $keys }}', 'empresa_prod_{{ $keys }}', 'EMPRESAS', 'prod');
                    getCatalogo('{{ $keys }}', 'empresa_intermex_{{ $keys }}', 'EMPRESAS', 'intermex');
                    @break
                // ERP CLOUD
                @case('1032')                  
                    $('#prod_{{$keys}}, #intermex_{{$keys}}').click(function(){
                        var validacion_al_menos_uno = $("#validacion_al_menos_uno_{{$keys}}").val();
                    
                        if(validacion_al_menos_uno == "") {
                            validacion_al_menos_uno = 0;
                        }

                        if($(this).is(':checked')) {
                            validacion_al_menos_uno = parseInt(validacion_al_menos_uno)+1;
                        } else {
                            validacion_al_menos_uno = parseInt(validacion_al_menos_uno)-1;
                        }

                        if(validacion_al_menos_uno == 0) {
                            validacion_al_menos_uno = "";
                        }

                        $("#validacion_al_menos_uno_{{$keys}}").val(validacion_al_menos_uno);
                    });
                    
                    $(document).on('change', '.empresaselect', function() {
                        var idSelect = $(this).attr('id');
                        idSelect = idSelect.split('_');
                        var valorSelect = $(this).val();
                        var tipo = $(this).data('tipo');
                        valorSelect = valorSelect.split('_');
                        if(idSelect[2] == '{{ $keys }}') {
                            getAutorizadores('{{ $keys }}', 3, 'responsabilidad_'+tipo+'_{{ $keys }}', valorSelect[0], tipo, true);
                        }
                    });
                    // getAutorizadores('{{ $keys }}', 3, 'responsabilidad_intermex_{{ $keys }}', null, 'intermex');
                    getAutorizadores('{{ $keys }}', 3, 'responsabilidad_prod_{{ $keys }}', null, 'prod');
                    getCatalogo('{{ $keys }}', 'empresa_prod_{{ $keys }}', 'EMPRESAS', 'prod', null, null, 'TIPO');
                    // getCatalogo('{{ $keys }}', 'empresa_intermex_{{ $keys }}', 'EMPRESAS', 'intermex');
                    @break
                // FIN ERP CLOUD
                @case('65')
                    getAutorizadores('{{ $keys }}', 6, 'perfil_{{ $keys }}');
                    @break
                @case('1027')
                    getAutorizadores('{{ $keys }}', 5, 'hiddenOtros_{{ $keys }}');
                    @break
                @case('1028')
                    getCatalogo('{{ $keys }}', 'portales_{{ $keys }}', 'PORTALES');
                    $(document).on('change','.portaleselect', function(){
                        var valorSelect = $(this).val();
                        valorSelect = valorSelect.split('_');
                        getAutorizadores('{{ $keys }}', 6, 'perfil_{{ $keys }}', valorSelect[0]);
                    });
                    @break
                @case('22')
                    getAutorizadores('{{ $keys }}', 4, 'rol_{{ $keys }}');
                    @break
                @case('23')
                    getAutorizadores('{{ $keys }}', 0, 'aplicacion_{{ $keys }}');
                    getCatalogo('{{ $keys }}', 'grupo_{{ $keys }}', 'GRUPOS');
                    // getAutorizadores('{{ $keys }}', 1, 'grupo_{{ $keys }}');
                    @break
                @case('24')
                    getAutorizadores('{{ $keys }}', 0, 'aplicacion_{{ $keys }}');
                    // getAutorizadores('{{ $keys }}', 1, 'grupo_{{ $keys }}');
                    getCatalogo('{{ $keys }}', 'grupo_{{ $keys }}', 'GRUPOS');
                    @break
                @case('1024')
                    // getAutorizadores('{{ $keys }}', 6, 'perfil_{{ $keys }}');
                    getAutorizadores('{{ $keys }}', 0, 'aplicacion_{{ $keys }}');
                    @break
                @case('27')
                    getAutorizadores('{{ $keys }}', 9, 'instancia_{{ $keys }}');
                    getCatalogo('{{ $keys }}', 'nivelesdeseguridad_{{ $keys }}', 'NIVEL DE SEGURIDAD');
                    @break
                @case('1009')
                    // getAutorizadores('{{ $keys }}', 6, 'hiddenOtros_{{ $keys }}');
                    getAutorizadores('{{ $keys }}', 6, 'perfiles_{{ $keys }}');
                    
                    getCatalogo('{{ $keys }}', 'ubicacionlaboral_{{ $keys }}', 'UBICACIÓN LABORAL (LOCATION)');
                    $(document).on('change', '.checkboxPerfil', function() {
                        a = $(".checkboxPerfil:checked").val();
                        if (a == 'on') {
                            Swal.fire({
                              title: 'Activación de perfil SOS',
                              text: "Se perderá la información ingresada en la aplicación, ¿Esta de acuerdo?",
                              icon: 'warning',
                              showCancelButton: true,
                              confirmButtonColor: '#3085d6',
                              cancelButtonColor: '#d33',
                              confirmButtonText: 'Continuar',
                              cancelButtonText: 'Cancelar'
                            }).then((result) => {
                                if (result.value == true) {
                                    getAutorizadores('{{ $keys }}', 15, 'perfillandmarksales_{{ $keys }}');
                                    $("#perfiles_{{ $keys }}").val('');
                                    $("#idred_{{ $keys }}").val('');
                                    $("#nombrejefeinmediato_{{ $keys }}").val('');
                                    $("#ubicacionlaboral_{{ $keys }}").val('');
                                    $("#perfiles_{{ $keys }}").attr('disabled', true);
                                    $("#idred_{{ $keys }}").attr('disabled', true);
                                    $("#nombrejefeinmediato_{{ $keys }}").attr('disabled', true);
                                    $("#ubicacionlaboral_{{ $keys }}").attr('disabled', true);
                                    $("#perfillandmarksales_{{ $keys }}").attr('disabled', false);
                                    $("#motivoasignacion_{{ $keys }}").attr('disabled', false);
                                    $("#fechaasignacion_{{ $keys }}").attr('disabled', false);
                                    $("#perfiles_{{ $keys }}").removeClass('campo-requerido');
                                    $("#perfillandmarksales_{{ $keys }}").addClass('campo-requerido');  
                                }else{
                                    $("#perfi_sos").prop('checked', false); 
                                }
                            })
                        }else{
                            Swal.fire({
                              title: 'Desactivación de perfil SOS',
                              text: "Se perderá la información ingresada en la aplicación, ¿Esta de acuerdo?",
                              icon: 'warning',
                              showCancelButton: true,
                              confirmButtonColor: '#3085d6',
                              cancelButtonColor: '#d33',
                              confirmButtonText: 'Continuar',
                              cancelButtonText: 'Cancelar'
                            }).then((result) => {
                                console.log(result);
                                if (result.value == true) {
                                    $("#perfillandmarksales_{{ $keys }}").val('');
                                    $("#motivoasignacion_{{ $keys }}").val('');
                                    $("#fechaasignacion_{{ $keys }}").val('');
                                    $("#perfiles_{{ $keys }}").attr('disabled', false);
                                    $("#idred_{{ $keys }}").attr('disabled', false);
                                    $("#nombrejefeinmediato_{{ $keys }}").attr('disabled', false);
                                    $("#ubicacionlaboral_{{ $keys }}").attr('disabled', false);
                                    $("#perfillandmarksales_{{ $keys }}").attr('disabled', true);
                                    $("#motivoasignacion_{{ $keys }}").attr('disabled', true);
                                    $("#fechaasignacion_{{ $keys }}").attr('disabled', true);
                                    $("#perfiles_{{ $keys }}").addClass('campo-requerido');
                                    $("#perfillandmarksales_{{ $keys }}").removeClass('campo-requerido');
                                }else{
                                    $("#perfi_sos").prop('checked', true); 
                                }
                            })
                        }

                    });
                    @break
                @case('31')
                    getAutorizadores('{{ $keys }}', 6, 'perfil_{{ $keys }}');
                    getCatalogo('{{ $keys }}', 'rol_{{ $keys }}', 'ROLES');
                    getCatalogo('{{ $keys }}', 'grupoeditorial_{{ $keys }}', 'GRUPOS EDITORIALES');
                    @break
                @case('32')
                    getAutorizadores('{{ $keys }}', 6, 'perfil_{{ $keys }}');
                    getCatalogo('{{ $keys }}', 'empresa_{{ $keys }}', 'EMPRESAS');
                    @break
                @case('33')
                    getAutorizadores('{{ $keys }}', 5, 'hiddenOtros_{{ $keys }}');
                    getCatalogo('{{ $keys }}', 'grupo_{{ $keys }}', 'GRUPO');
                    getCatalogo('{{ $keys }}', 'areasInsercion_{{ $keys }}', 'AREAS DE INSERCION');
                    getCatalogo('{{ $keys }}', 'tipoEmpleado_{{ $keys }}', 'TIPO DE EMPLEADO');
                    getCatalogo('{{ $keys }}', 'estado_{{ $keys }}', 'ESTADO');
                    @break
                @case('maximovideocine')
                    @break
                @case('1001')
                    getAutorizadores('{{ $keys }}', 4, 'rol_{{ $keys }}');
                    //  getCatalogo('{{ $keys }}', 'modulos_{{ $keys }}', 'MODULOS ORACLE AACG');
                    //  getCatalogo('{{ $keys }}', 'instancias_{{ $keys }}', 'INSTANCIAS ORACLE AACG');
                    @break
                @case('35')
                    getAutorizadores('{{ $keys }}', 6, 'perfil_{{ $keys }}');
                    @break
                @case('1003')
                    getAutorizadores('{{ $keys }}', 1, 'grupo_{{ $keys }}');
                    @break
                @case('8')
                    getAutorizadores('{{ $keys }}', 4, 'rol_{{ $keys }}');
                    @break
                @case('37')
                    getCatalogo('{{ $keys }}', 'canalesparadigm_{{ $keys }}', 'CANALES');
                    getAutorizadores('{{ $keys }}',10, 'areasforparadigm_{{ $keys }}');
                    @break
                @case('39')
                    getCatalogo('{{ $keys }}', 'grupo_{{ $keys }}', 'GRUPOS');
                    getCatalogo('{{ $keys }}', 'equipo_{{ $keys }}', 'EQUIPOS');
                    getCatalogo('{{ $keys }}', 'tipocaso_{{ $keys }}', 'TIPO DE CASO');
                    getAutorizadores('{{ $keys }}', 4, 'rol_{{ $keys }}');
                    @break
                @case('38')
                    getAutorizadores('{{ $keys }}', 4, 'rol_{{ $keys }}');
                    @break
                @case('40')
                    getAutorizadores('{{ $keys }}', 4, 'rol_{{ $keys }}');
                    @break
                @case('1004')
                    getCatalogo('{{ $keys }}', 'notificacion_{{ $keys }}', 'NOTIFICACIONES POR CORREO');
                    getAutorizadores('{{ $keys }}', 6, 'perfil_{{ $keys }}');
                    @break
                @case('41')
                    getCatalogo('{{ $keys }}', 'sala_{{ $keys }}', 'SALAS');
                    $(document).on('change', '.salaselect', function() {
                        var valorSelect = $(this).val();
                        valorSelect = valorSelect.split('_');
                        getAutorizadores('{{ $keys }}', 6, 'perfil_{{ $keys }}',valorSelect[0]);
                    });
                    @break
                @case('1007')
                    getAutorizadores('{{ $keys }}', 1, 'grupo_{{ $keys }}');
                    @break
                @case('44')
                    getAutorizadores('{{ $keys }}', 4, 'rol_{{ $keys }}');
                    @break
                @case('43')
                    getCatalogo('{{ $keys }}', 'portafolios_{{ $keys }}', 'PORTAFOLIOS');
                    getAutorizadores('{{ $keys }}', 11, 'permisos_{{ $keys }}');
                    // getCatalogo('{{ $keys }}', 'permisosportafolios_{{ $keys }}', 'PERMISOS PORTAFOLIOS');
                    @break
                @case('45')
                    getAutorizadores('{{ $keys }}', 4, 'rol_{{ $keys }}');
                    @break
                @case('46')
                    getAutorizadores('{{ $keys }}', 11, 'permisos_{{ $keys }}');
                    @break
                @case('1005')
                    getAutorizadores('{{ $keys }}', 4, 'rol_{{ $keys }}');
                    getCatalogo('{{ $keys }}', 'empresa_{{ $keys }}', 'EMPRESAS');
                    @break
                @case('64')
                    $('#prod_{{$keys}}, #intermex_{{$keys}}').click(function(){
                        var validacion_al_menos_uno = $("#validacion_al_menos_uno_{{$keys}}").val();
                    
                        if(validacion_al_menos_uno == "") {
                            validacion_al_menos_uno = 0;
                        }

                        if($(this).is(':checked')) {
                            validacion_al_menos_uno = parseInt(validacion_al_menos_uno)+1;
                        } else {
                            validacion_al_menos_uno = parseInt(validacion_al_menos_uno)-1;
                        }

                        if(validacion_al_menos_uno == 0) {
                            validacion_al_menos_uno = "";
                        }

                        $("#validacion_al_menos_uno_{{$keys}}").val(validacion_al_menos_uno);
                    });
                    getAutorizadores('{{ $keys }}', 2, 'prodreporte_{{ $keys }}', null, 'prod');
                    getAutorizadores('{{ $keys }}', 2, 'intermexreporte_{{ $keys }}', null, 'intermex');
                    @break
                @case('69')
                    getAutorizadores('{{ $keys }}', 14, 'fondo_{{ $keys }}');
                    getCatalogo('{{ $keys }}', 'cajaahorros_{{ $keys }}', 'TIPO DE CAJA DE AHORRO');
                    getCatalogo('{{ $keys }}', 'tipousuario_{{ $keys }}', 'TIPOS DE USUARIO');

                    $(document).on('change', '.fondoselect', function() {
                        var textoSelect = $('select[id="fondo_{{ $keys }}"] option:selected').text();
                        if (textoSelect === 'CAJA DE AHORRO'){
                            $("#cajaahorros_{{ $keys }}").attr('disabled', false);
                            var element = document.getElementById("cajaahorros_{{ $keys }}");
                            element.classList.add("campo-requerido");

                        }else{ 
                            $("#cajaahorros_{{ $keys }}").attr('disabled', true);
                            var element3 = document.getElementById("perfilInternet_{{ $keys }}");
                            element3.classList.remove("campo-requerido");
                        }
                        });

                    $(document).on('change', '.tipousuarioselect', function() {
                        var valorSelect = $(this).val();
                        var textoSelect = $('select[id="tipousuario_{{ $keys }}"] option:selected').text();
                        valorSelect = valorSelect.split('_');
                        getCatalogo('{{ $keys }}', 'perfilCentral_{{ $keys }}', 'ESPECIFICAR PERFIL CENTRAL', null, valorSelect[0]);
                        getCatalogo('{{ $keys }}', 'perfilInternet_{{ $keys }}', 'ESPECIFICAR PERFIL INTERNET', null, valorSelect[0]);
                        getCatalogo('{{ $keys }}', 'usuarioWeb_{{ $keys }}', 'TIPOS DE USUARIO WEB', null, valorSelect[0]);
                        var textoSelect = $('select[id="tipousuario_{{ $keys }}"] option:selected').text();
                        switch(textoSelect){
                            case 'Administrador Total':
                                var element = document.getElementById("perfilCentral_{{ $keys }}");
                                element.classList.add("campo-requerido");
                                $("#perfilCentral_{{ $keys }}").attr('disabled', false);
                                var element2 = document.getElementById("usuarioWeb_{{ $keys }}");
                                element2.classList.add("campo-requerido");
                                $("#usuarioWeb_{{ $keys }}").attr('disabled', false);
                                var element3 = document.getElementById("perfilInternet_{{ $keys }}");
                                element3.classList.remove("campo-requerido");
                                $("#perfilInternet_{{ $keys }}").attr('disabled', true);
                            break;
                            case 'Central':
                                var element = document.getElementById("perfilCentral_{{ $keys }}");
                                element.classList.add("campo-requerido");
                                $("#perfilCentral_{{ $keys }}").attr('disabled', false);
                                var element2 = document.getElementById("perfilInternet_{{ $keys }}");
                                element2.classList.remove("campo-requerido");
                                $("#perfilInternet_{{ $keys }}").attr('disabled', true);
                                var element3 = document.getElementById("usuarioWeb_{{ $keys }}");
                                element3.classList.remove("campo-requerido");
                                $("#usuarioWeb_{{ $keys }}").attr('disabled', true);

                            break;
                            case 'Central e Internet':
                                var element = document.getElementById("perfilCentral_{{ $keys }}");
                                element.classList.add("campo-requerido");
                                $("#perfilCentral_{{ $keys }}").attr('disabled', false);
                                var element2 = document.getElementById("perfilInternet_{{ $keys }}");
                                element2.classList.add("campo-requerido");
                                $("#perfilInternet_{{ $keys }}").attr('disabled', false);
                                var element3 = document.getElementById("usuarioWeb_{{ $keys }}");
                                element3.classList.add("campo-requerido");
                                $("#usuarioWeb_{{ $keys }}").attr('disabled', false);
                            break;
                            case 'Internet':
                                var element = document.getElementById("perfilInternet_{{ $keys }}");
                                element.classList.add("campo-requerido");
                                $("#perfilInternet_{{ $keys }}").attr('disabled', false);
                                var element2 = document.getElementById("usuarioWeb_{{ $keys }}");
                                element2.classList.add("campo-requerido");
                                $("#usuarioWeb_{{ $keys }}").attr('disabled', false);
                                var element3 = document.getElementById("perfilCentral_{{ $keys }}");
                                element3.classList.remove("campo-requerido");
                                $("#perfilCentral_{{ $keys }}").attr('disabled', true);
                            break;
                        }
                    });
                    @break
                @case('47')
                    getAutorizadores('{{ $keys }}', 6, 'perfil_{{ $keys }}');
                    // getAutorizadores('{{ $keys }}', 7, 'funcion_{{ $keys }}');
                    @break
                @case('62')
                    getAutorizadores('{{ $keys }}', 6, 'perfil_{{ $keys }}');
                    // getAutorizadores('{{ $keys }}', 7, 'funcion_{{ $keys }}');
                    @break
                @case('48')
                    getAutorizadores('{{ $keys }}', 4, 'rol_{{ $keys }}');
                    @break
                @case('49')
                    getAutorizadores('{{ $keys }}', 4, 'rol_{{ $keys }}');
                    @break
                @case('50')
                    getAutorizadores('{{ $keys }}', 6, 'perfil_{{ $keys }}');
                    getCatalogo('{{ $keys }}', 'empresa_{{ $keys }}', 'EMPRESAS');
                    getCatalogo('{{ $keys }}', 'facultades_{{ $keys }}', 'FACULTADES');
                    @break
                @case('51')
                    // getAutorizadores('{{ $keys }}', 0, 'aplicacion_{{ $keys }}');
                    getAutorizadores('{{ $keys }}', 1, 'grupo_{{ $keys }}');
                    @break
                @case('76')
                    // getAutorizadores('{{ $keys }}', 0, 'aplicacion_{{ $keys }}');
                    getAutorizadores('{{ $keys }}', 4, 'rol_{{ $keys }}');
                    @break
                @case('52')
                    // getAutorizadores('{{ $keys }}', 5, 'hiddenOtros_{{ $keys }}');
                    // getCatalogo('{{ $keys }}', 'permisos_{{ $keys }}', 'PERMISOS');
                    getAutorizadores('{{ $keys }}', 11, 'permisos_{{ $keys }}');
                    @break
                @case('53')
                    getAutorizadores('{{ $keys }}', 1, 'grupo_{{ $keys }}');
                    @break
                @case('54')
                    $(document).on('change', '.moduloselect', function() {
                        var valorSelect = $(this).val();
                        valorSelect = valorSelect.split('_');
                        getCatalogo('{{ $keys }}', 'subsistemas_{{ $keys }}', 'SUBSISTEMAS', null, valorSelect);
                        getAutorizadores('{{ $keys }}', 6, 'perfil_{{ $keys }}', valorSelect[0]);
                    });
                    getCatalogo('{{ $keys }}', 'sistema_{{ $keys }}', 'MODULOS');
                    getCatalogo('{{ $keys }}', 'empresa_{{ $keys }}', 'EMPRESAS');
                    @break
                @case('55')
                    getAutorizadores('{{ $keys }}', 5, 'hiddenOtros_{{ $keys }}');
                    getCatalogo('{{ $keys }}', 'nivelesseguridad_{{ $keys }}', 'NIVELES DE SEGURIDAD');
                    @break
                @case('56')
                    getAutorizadores('{{ $keys }}', 11, 'permisos_{{ $keys }}');
                    // getCatalogo('{{ $keys }}', 'permisos_{{ $keys }}', 'PERMISOS');
                    @break
                @case('11')
                    getAutorizadores('{{ $keys }}', 6, 'perfil_{{ $keys }}');
                    @break
                @case('57')
                    getAutorizadores('{{ $keys }}', 4, 'rol_{{ $keys }}');
                    @break
                @case('1011')
                    getAutorizadores('{{ $keys }}', 6, 'perfil_{{ $keys }}');
                    @break
                @case('59')
                    getCatalogo('{{ $keys }}', 'salas_{{ $keys }}', 'SALAS');

                    $(document).on('change', '.salasselect', function() {
                        var valorSelect = $(this).val();
                        valorSelect = valorSelect.split('_');
                        getAutorizadores('{{ $keys }}', 6, 'perfil_{{ $keys }}', valorSelect[0]);
                    });
                    @break
                @case('63')
                    getAutorizadores('{{ $keys }}', 4, 'rol_{{ $keys }}');
                    @break
                @case('1006')
                    getAutorizadores('{{ $keys }}', 4, 'rol_{{ $keys }}');
                    @break
                @case('58')
                    getAutorizadores('{{ $keys }}', 8, 'empresa_{{ $keys }}');
                    getCatalogo('{{ $keys }}', 'tipousuario_{{ $keys }}', 'TIPO DE USUARIO');
                    getCatalogo('{{ $keys }}', 'perfil_{{ $keys }}', 'PERFILES');
                    @break
                @case('66')
                    getAutorizadores('{{ $keys }}', 1, 'grupo_{{ $keys }}');
                    @break
                @case('60')
                    getCatalogo('{{ $keys }}', 'emisora_{{ $keys }}', 'EMISORA');
                    $(document).on('change', '.emisoraselect', function() {
                        var valorSelect = $(this).val();
                        valorSelect = valorSelect.split('_');
                        getAutorizadores('{{ $keys }}', 4, 'rol_{{ $keys }}',valorSelect[0]);
                    });
                    @break
                @case('61')
                    getAutorizadores('{{ $keys }}', 9, 'lugar_{{ $keys }}');
                    // getCatalogo('{{ $keys }}', 'perfil_{{ $keys }}', 'PERFIL POR INSTANCIA');
                    // getCatalogo('{{ $keys }}', 'lugar_{{ $keys }}', 'INSTANCIA');
                    $(document).on('change', '.instanciaselect', function() {
                        var valorSelect = $(this).val();
                        valorSelect = valorSelect.split('_');
                        // getAutorizadores('{{ $keys }}', 6, 'perfil_{{ $keys }}', valorSelect[0]);
                        getCatalogo('{{ $keys }}', 'perfil_{{ $keys }}', 'PERFIL POR INSTANCIA',  null, null, valorSelect[0]);
                    });
                    @break
            @endswitch
        @endforeach
    });
</script>
@endpush