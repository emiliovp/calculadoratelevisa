
@extends('layouts.app')
@php
    $var = session()->get('_old_input');
@endphp
@component('layouts.encabezadosolicitud',['foo' => $param['data'], 'route'=> $param['route'], 'tipo_fus' => $param['tipo_fus'], 'jer' => $param['jer'] ])
<!-- aqui va el codigo que recibira el encabezado -->
    @slot('body')
<div class="container" style="margin-top:10px;">
    <div class="row justify.content-center"> 
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <label><strong>Solicitud de usuario de red<strong></label>
                    <hr>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-12 text-right">
                            <a href="{{ route('fus_lista') }}" id="regresar" class="btn btn-warning">Regresar</a>
                            <button type="submit" class="btn btn-primary update" id="enviar" >Guardar</button>
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
</script>
@endpush