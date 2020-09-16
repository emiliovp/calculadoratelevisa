<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Notificación - Asignación de objeto de autorización</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <body>
        <p>
            A sido asignado a los siguientes objetos de autorización para los FUS-e de aplicaciones:
        </p>
        <table>
            <thead>
                <tr>
                    <th>Aplicación</th>
                    <th>Objeto de autorización</th>
                    <th>Tipo de Objeto</th>
                    <th>Asignación</th>
                </tr>
            </thead>
            <tbody>
                @foreach($info AS $key => $value)
                <tr>
                    <td>{{$value["app"]}}</td>
                    <td>{{$value["rol_mod_rep"]}}</td>
                    <td>{{$value["tipo_autorizacion"]}}</td>
                    <td>{{$value["tipo_autorizador"]}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </body>
</html>