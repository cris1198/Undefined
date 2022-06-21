<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="./cssmail.css" rel="stylesheet">
    <title>Prueba de correo</title>
</head>
<body>
<div class="container mensaje">
        <div style="margin-left: 40px;">
        <h1>Su solicitud de reserva a sido ACEPTADA</h1>
       <h2>Detalles</h2>
        <p><strong>Materia: </strong> {{$materia}}</p>
        <p><strong>Grupo: </strong>{{$grupo}}</p>
        <p><strong>Su reserva son dos aulas contiguas</strong></p>
        <p><strong>Primer Aula:</strong> {{ $datos->codigo }} </p>
        <p><strong>Segunda Aula</strong>{{$aula2}}</p>
        <p><strong>Fecha de reserva:</strong> {{ $datos->fechaReserva }} </p>
        <p>
            @if($datos->periodo === 1)
            <strong>Hora de reserva:</strong> 6:45 - 8:15
            @endif

            @if($datos->periodo === 2)
            <strong>Hora de reserva:</strong> 8:15 - 9:45
            @endif

            @if($datos->periodo === 3)
            <strong>Hora de reserva:</strong>: 9:45 - 11:15
            @endif

            @if($datos->periodo === 4)
            <strong>Hora de reserva:</strong> 11:15 - 12:45
            @endif

            @if($datos->periodo === 5)
            <strong>Hora de reserva:</strong> 12:45 - 14:15
            @endif

            @if($datos->periodo === 6)
            <strong>Hora de reserva:</strong> 14:15 - 15:45
            @endif

            @if($datos->periodo === 7)
            <strong>Hora de reserva:</strong> 15:45 - 17:15
            @endif

            @if($datos->periodo === 7)
            <strong>Hora de reserva:</strong> 17:15 - 18:45
            @endif

            @if($datos->periodo === 7)
            <strong>Hora de reserva:</strong> 18:45 - 20:15
            @endif

            @if($datos->periodo === 7)
            <strong>Hora de reserva:</strong> 20:15 - 21:45
            @endif
        </p>
        <p><strong>Motivo de reserva:</strong> {{$datos->motivo}}</p>
        </div> 
        </div>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>
</html>