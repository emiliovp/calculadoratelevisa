@extends('layouts.app')
@section('content')
<!-- <input type="hidden" id="modulo" value="fus_wintel" /> -->
<div class="container">
    <div class="row justify.content-center"> 
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                {{ __('ALTA DE FUS')}}
                </div>
                <form method="POST" action="">
                <div class="card-body">
                <label><strong>Datos del solicitante</strong></label>
                <hr>
                      
@endsection
@push('scripts')
<script type="text/javascript">
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
$( function() {
    $( "#f_desde" ).datepicker({ maxDate: 0}); //.attr('readonly', 'readonly'); para bloquear el input
  });
  $( function() {
    $( "#f_hasta" ).datepicker({ minDate: 1 });
  });
</script>
@endpush