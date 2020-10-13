<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Alta de FUS-e con folio #{{$id}}</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <body>
        <p>
           Se ha dado de alta una solicitud de equipo en cual usted ha sido asignado como solicitante, en la siguiente URL podra visualizarlo
        </p>
        <a href="{{url('/fus/showfus/'.$id)}}">{{url('/fus/showfus/'.$id)}}</a>
    </body>
</html>