<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Solicitud de autorización para FUS-e #{{$id}}</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <body>
        @if($tipo_fus == 'Aplicaciones' && $jefeOAut == 3)
            <p>
                Usted tiene una solicitud por revisar:
            </p>
            <p>
                <a href="{{url('/fus/fusesporautorizar')}}">{{url('/fus/fusesporautorizar')}}</a>
            </p>
        @else
            <p>
                Dar clic en el siguiente enlace para poder autorizar o rechazar el fus #{{$id}} .
            </p>
            <p>
                @if(isset($idRelConf))
                    <a href="{{url('/fus/showfus/'.$id.'/'.$tipo.'/'.$jefeOAut.'/'.$idRelConf.'/'.$act_Apps_Otros.'/'.$idapp)}}">{{url('/fus/showfus/'.$id.'/'.$tipo.'/'.$jefeOAut.'/'.$idRelConf.'/'.$act_Apps_Otros.'/'.$idapp)}}</a>
                @else
                    <a href="{{url('/fus/showfus/'.$id.'/'.$tipo.'/'.$jefeOAut)}}">{{url('/fus/showfus/'.$id.'/'.$tipo.'/'.$jefeOAut)}}</a>
                @endif
            </p>
        @endif
    </body>
</html>