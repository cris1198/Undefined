
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
    @if($details22->aceptadoRechazado === 1)
        <h1>Su solicitud de reserva a sido ACEPTADA</h1>
        <p><strong>Detalles:</strong></p>
        <p><strong>Código de aula:</strong> {{ $details22->codigo }} </p>
        <p><strong>Fecha de reserva:</strong> {{ $details22->fechaReserva }} </p>
        <p>
            @if($details22->periodo === 1)
            <strong>Hora de reserva:</strong> 6:45 - 8:15
            @endif

            @if($details22->periodo === 2)
            <strong>Hora de reserva:</strong> 8:15 - 9:45
            @endif

            @if($details22->periodo === 3)
            <strong>Hora de reserva:</strong>: 9:45 - 11:15
            @endif

            @if($details22->periodo === 4)
            <strong>Hora de reserva:</strong> 11:15 - 12:45
            @endif

            @if($details22->periodo === 5)
            <strong>Hora de reserva:</strong> 12:45 - 14:15
            @endif

            @if($details22->periodo === 6)
            <strong>Hora de reserva:</strong> 14:15 - 15:45
            @endif

            @if($details22->periodo === 7)
            <strong>Hora de reserva:</strong> 15:45 - 17:15
            @endif

            @if($details22->periodo === 7)
            <strong>Hora de reserva:</strong> 17:15 - 18:45
            @endif

            @if($details22->periodo === 7)
            <strong>Hora de reserva:</strong> 18:45 - 20:15
            @endif

            @if($details22->periodo === 7)
            <strong>Hora de reserva:</strong> 20:15 - 21:45
            @endif
        </p>
        <p><strong>Motivo de reserva:</strong> {{$details22->motivo}}</p>
        <p><strong>Materia:</strong> {{$details22->materia}}</p> 
        </div> 
        </div>

        @elseif($details22->aceptadoRechazado === 0)
        <div class="container mensaje">
        <div style="margin-left: 40px;">
        <h1>
           Su solicitud de reserva a sido RECHAZADA
        </h1>
        <p><strong>Razon de rechazo:</strong> {{$details22->razon}}</p>
        <p><strong>Detalles:</strong></p>
        <p><strong>Codigo de aula:</strong> {{ $details22->codigo }} </p>
        <p>Fecha de reserva: {{ $details22->fechaReserva }} </p>
       
        <p>
            @if($details22->periodo === 1)
            <strong>Hora de reserva:</strong> 6:45 - 8:15
            @endif

            @if($details22->periodo === 2)
            <strong>Hora de reserva:</strong> 8:15 - 9:45
            @endif

            @if($details22->periodo === 3)
            <strong>Hora de reserva:</strong> 9:45 - 11:15
            @endif

            @if($details22->periodo === 4)
            <strong>Hora de reserva:</strong> 11:15 - 12:45
            @endif

            @if($details22->periodo === 5)
            <strong>Hora de reserva:</strong> 12:45 - 14:15
            @endif

            @if($details22->periodo === 6)
            <strong>Hora de reserva:</strong> 14:15 - 15:45
            @endif

            @if($details22->periodo === 7)
            <strong>Hora de reserva:</strong> 15:45 - 17:15
            @endif

            @if($details22->periodo === 7)
            <strong>Hora de reserva:</strong> 17:15 - 18:45
            @endif

            @if($details22->periodo === 7)
            <strong>Hora de reserva:</strong> 18:45 - 20:15
            @endif

            @if($details22->periodo === 7)
            <strong>Hora de reserva:</strong> 20:15 - 21:45
            @endif
        </p>
        </div> 
        </div>
    @endif
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>
</html>