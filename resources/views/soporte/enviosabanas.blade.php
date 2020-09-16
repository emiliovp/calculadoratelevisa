@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card" >
                <div class="card-header">
                    <strong>Envio de anexos</strong>
                </div>
                <div class="card-body">
                        @csrf
                    <div class="from-group row">
                        <div class="col-md-4">
                                <label for="folio" class="col-md-6 col-form-label text-md-left">{{ __('Folio de fus') }}</label>
                                <input type="text" class="form-control" name="folio" id="folio" onkeypress="return valideKey(event);">
                            </div>
                            <div class="col-md-8">
                                <label for="mail" class="col-md-6 col-form-label text-md-left">{{ __('Correo') }}</label>
                                <input id="mail" type="text" class="form-control" name="mail" value="" required>
                            </div>
                        </div>
                        <div class="from-group row">
                            <div class="col-md-4" style="margin-top:15px;">
                                <button class="btn btn-success" id="enviar">Enviar correo</button>
                            </div>
                        </div>
                    </div>
                </div>  
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
$(document).on('click','#enviar', function(e){
    var folio = $("#folio").val();
    var destinatarios= $("#mail").val();
    if(folio == "" || destinatarios == ""){
        Swal.fire({
            icon: 'error',
            title: 'Datos Faltantes',
            text: 'Favor de introducir datos validos',
            // footer: '<a href>Why do I have this issue?</a>'
        });  
    }
    else{
        enviar(folio, destinatarios);
    }
});

function valideKey(e){
    var key = e.charCode;
  if (key < 48 || key > 57) {
    e.preventDefault();
    }
}
// );
function enviar(folio, dest){
    $.ajax({
        url:"{{ route('notificacionFinalWtl') }}",
        dataType: "json",
            data:{term: folio, dest:dest},
        beforeSend:function(){
            mostrarLoading();
        },
        success: function(data)
        {
                        ocultarLoading();
            if (data === 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Falta información',
                    text: 'El folio solicitado puede no estar autorizado',
                });
            }else if (data == 1 ){
                Swal.fire({
                    icon: 'error',
                    title: 'Datos Erroneos',
                    text: 'Una de la cuentas de correo no cumple con el formato, favor de revisar la información',
                });
            }else if (data == 2 ){
                ocultarLoading();
                Swal.fire({
                    icon: 'succes',
                    title: 'Envio correcto',
                    text: 'El correo ha sido enviado a los destinatarios capturados',
                });
                $('#folio').val('');
                $('#mail').val('');
            }
        }
    });

}
</script>
@endpush