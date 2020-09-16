@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('FUS de Aplicaciones') }}</div>
                <div class="card-body">
                    <label>Seleccione las aplicaciones para generar el FUS</label>
                    <form method="POST" id="selectordeapps" action="{{ route('solicitudfusapps') }}">
                        @csrf
                        <div class="form-group row">
                            <div class="col-md-4"><input type='checkbox' name='1028'> ACERVO Y OBRAS LITERARIAS</div>
                            <!-- <div class="col-md-4"><input type='checkbox' name='analyticsweb'> ANALYTICS WEB</div> -->
                            <div class="col-md-4"><input type='checkbox' name='1024'> IMPROMPTU</div>
                            <div class="col-md-4"><input type='checkbox' name='47'> SALESFORCE EDITORIAL</div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-4"><input type='checkbox' name='2'> ANALYTICS WEB</div>
                            <!-- <div class="col-md-4"><input type='checkbox' name='apexformulaciones'> APEX FORMULACIONES</div> -->
                            <div class="col-md-4"><input type='checkbox' name='27'> LABORA</div>
                            <div class="col-md-4"><input type='checkbox' name='62'> SALESFORCE VENTAS</div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-4"><input type='checkbox' name='3'> APEX FORMULACIONES</div>
                            <!-- <div class="col-md-4"><input type='checkbox' name='apextv-networks'> APEX TV-NETWORKS</div> -->
                            <div class="col-md-4"><input type='checkbox' name='1009'> LANDMARK SALES</div>
                            <div class="col-md-4"><input type='checkbox' name='48'> SECMAN</div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-4"><input type='checkbox' name='4'> APEX TV-NETWORKS</div>
                            <!-- <div class="col-md-4"><input type='checkbox' name='bookup'> BOOKUP</div> -->
                            <div class="col-md-4"><input type='checkbox' name='31'> LIVE UP</div>
                            <div class="col-md-4"><input type='checkbox' name='49'> SECMAN 12</div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-4"><input type='checkbox' name='5'> BOOKUP</div>
                            <!-- <div class="col-md-4"><input type='checkbox' name='bookuptim'> BOOKUP! TIM</div> -->
                            <div class="col-md-4"><input type='checkbox' name='32'> MAF</div>
                            <div class="col-md-4"><input type='checkbox' name='50'> SET</div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-4"><input type='checkbox' name='6'> BOOKUP! TIM</div>
                            <!-- <div class="col-md-4"><input type='checkbox' name='catalogoscorporativos'> CATALOGOS CORPORATIVOS</div> -->
                            <div class="col-md-4"><input type='checkbox' name='33'> MAXIMO VESTUARIO</div>
                            <div class="col-md-4"><input type='checkbox' name='51'> SIASA</div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-4"><input type='checkbox' name='7'> CATALOGOS CORPORATIVOS</div>
                            <div class="col-md-4"><input type='checkbox' name='1001'> ORACLE AACG</div>
                            <div class="col-md-4"><input type='checkbox' name='76'> SIASA 2</div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-4"><input type='checkbox' name='9'> COFIDI</div>
                            <div class="col-md-4"><input type='checkbox' name='35'> ORDUNI-SECMAN</div>
                            <div class="col-md-4"><input type='checkbox' name='52'> SICEA</div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-4"><input type='checkbox' name='10'> CONCOM-SECMAN</div>
                            <div class="col-md-4"><input type='checkbox' name='1003'> ORDWEB</div>
                            <div class="col-md-4"><input type='checkbox' name='53'> SIFIC</div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-4"><input type='checkbox' name='12'> CREDITO CORP.</div>
                            <div class="col-md-4"><input type='checkbox' name='8'> OPERACIONES CON VALOR</div>
                            <div class="col-md-4"><input type='checkbox' name='54'> SIFIT</div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-4"><input type='checkbox' name='13'> CRM DYNAMICS PROTELE</div>
                            <div class="col-md-4"><input type='checkbox' name='37'> PARADIGM</div>
                            <div class="col-md-4"><input type='checkbox' name='55'> SIHO</div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-4"><input type='checkbox' name='14'> CRM TELEFONIA</div>
                            <div class="col-md-4"><input type='checkbox' name='39'> PATRICIA</div>
                            <div class="col-md-4"><input type='checkbox' name='56'> SIM</div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-4"><input type='checkbox' name='15'> DAM</div>
                            <div class="col-md-4"><input type='checkbox' name='38'> PARRILLAS</div>
                            <div class="col-md-4"><input type='checkbox' name='11'> SIMM</div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-4"><input type='checkbox' name='16'> DISCOVERER</div>
                            <div class="col-md-4"><input type='checkbox' name='40'> PENDIUM</div>
                            <!-- <div class="col-md-4"><input type='checkbox' name='57'> SISTEMAS DE GESTION RRHH</div> -->
                            <div class="col-md-4"><input type='checkbox' name='1011'> SMARTCONCIL</div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-4"><input type='checkbox' name='17'> DOCUMENTUM</div>
                            <div class="col-md-4"><input type='checkbox' name='1004'> PCB</div>
                            <div class="col-md-4"><input type='checkbox' name='59'> SUC (WIGOS)</div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-4"><input type='checkbox' name='19'> EDIWIN-PLANTA Y HONORARIOS</div>
                            <div class="col-md-4"><input type='checkbox' name='41'> PIXEL POINT</div>
                            <div class="col-md-4"><input type='checkbox' name='63'> TAXIS</div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-4"><input type='checkbox' name='18'> EDIWIN-EMISION</div>
                            <div class="col-md-4"><input type='checkbox' name='1007'> PLAN COMERCIAL</div>
                            <div class="col-md-4"><input type='checkbox' name='1006'> TIEMPOS EFECTIVOS</div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-4"><input type='checkbox' name='20'> ERP</div>
                            <div class="col-md-4"><input type='checkbox' name='44'> PORTAL DE PRODUCCION</div>
                            <div class="col-md-4"><input type='checkbox' name='58'> TVSPOT</div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-4"><input type='checkbox' name='1032'> ERP CLOUD</div>
                            <div class="col-md-4"><input type='checkbox' name='43'> PORTAFOLIOS NET</div>
                            <div class="col-md-4"><input type='checkbox' name='66'> VIMBIZ</div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-4"><input type='checkbox' name='65'> EVE TV</div>
                            <div class="col-md-4"><input type='checkbox' name='45'> PRODUCTIVIDAD EN LÍNEA</div>
                            <div class="col-md-4"><input type='checkbox' name='60'> XBRL</div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-4"><input type='checkbox' name='1027'> FLUJO DE EFECTIVO CORP</div>
                            <div class="col-md-4"><input type='checkbox' name='46'> QLIKVIEW</div>
                            <div class="col-md-4"><input type='checkbox' name='61'> XYTECH</div>
                        </div>
                        
                        <div class="form-group row">
                            <div class="col-md-4"><input type='checkbox' name='22'> FLUJO DE EFECTIVO CxC "SOIN"</div>
                            <div class="col-md-4"><input type='checkbox' name='1005'> RECIBOS DE NOMINA</div>

                        </div> 
                        <div class="form-group row">
                            <div class="col-md-4"><input type='checkbox' name='23'> HYPERION PLANNING</div>
                            <div class="col-md-4"><input type='checkbox' name='64'> RENTABILIDAD DISCOVERER</div>
                            
                        </div>
                        <div class="form-group row">
                            <div class="col-md-4"><input type='checkbox' name='24'> HYPERION TV-NETWORKS</div>
                            <div class="col-md-4"><input type='checkbox' name='69'> SAF</div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 ">
                                <a href="{{ route('fus_lista') }}" class="btn btn-warning btn-block" style="color:#FFFFFF;">{{ __('Regresar') }}</a>
                            </div>
                            <div class="col-md-6 ">
                                <input type="submit" class="btn btn-primary btn-block" value="Generar FUS">
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
    $(document).ready(function() {
        $("input[type=checkbox]").click(function() {
            var idDelSeleccionado = $(this).attr("name");
            if($(this).is(":checked")) {
                if(idDelSeleccionado != 50 && $("input[type=checkbox]:checked").length > 1) {
                    $("input[type=checkbox]:checked").each(function(){
                        if($(this).attr("name") == 50) {
                            swal(
                                'Validación',
                                'Generar un FUS-e para la aplicación SET, requiere que sea la única aplicación seleccionada.',
                                'warning'
                            )
                            $("input[name="+idDelSeleccionado+"]").prop("checked",false);                
                        }
                    });
                } else if(idDelSeleccionado == 50 && $("input[type=checkbox]:checked").length > 1) {
                    swal(
                        'Validación',
                        'Generar un FUS-e para la aplicación SET, requiere que sea la única aplicación seleccionada.',
                        'warning'
                    )
                    $(this).prop("checked",false);
                }                    
            }
        });
        $("form").submit(function(e){
            var contador=0;
 
            $("input[type=checkbox]").each(function(){
                if($(this).is(":checked"))
                contador++;
            });
            
            if(contador > 0) {
                $("form").submit();
            } else {
                e.preventDefault();
                swal(
                    'Validación',
                    'Debe seleccionar al menos una aplicación',
                    'warning'
                )
            }
        });
    });
</script>
@endpush