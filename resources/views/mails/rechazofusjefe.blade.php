<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>FUS-e #{{$id}} - Rechazo</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <body>
        <p>
            @if($jefeOAut == 3)
                Uno de los objetos a autorizar del FUS #{{$id}}, ha sido rechazado. El motivo del rechazo es el siguiente:
            @else
                El FUS #{{$id}}, fue rechazado por el siguiente motivo:
            @endif
        </p>
        <p>
            {{$observacion}}
        </p>
    </body>
</html>