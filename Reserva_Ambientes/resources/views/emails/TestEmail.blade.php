
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prueba de correo</title>
</head>
<body>
    @if($details22->aceptadoRechazado === 1)
        <h1>
           Su solicitud de reserva a sido ACEPTADO
        </h1>
        <h2>
            Detalles
        </h2>
        <h2>
            Codigo de aula: {{ $details22->codigo }} 
        </h2>
        <h2>
            Fecha de reserva: {{ $details22->fechaReserva }} 
        </h2>
        <h2>
            @if($details22->periodo === 1)
            Hora de reserva: 6:45 - 8:15
            @endif

            @if($details22->periodo === 2)
            Hora de reserva: 8:15 - 9:45
            @endif

            @if($details22->periodo === 3)
            Hora de reserva: 9:45 - 11:15
            @endif

            @if($details22->periodo === 4)
            Hora de reserva: 11:15 - 12:45
            @endif

            @if($details22->periodo === 5)
            Hora de reserva: 12:45 - 14:15
            @endif

            @if($details22->periodo === 6)
            Hora de reserva: 14:15 - 15:45
            @endif

            @if($details22->periodo === 7)
            Hora de reserva: 15:45 - 17:15
            @endif

            @if($details22->periodo === 7)
            Hora de reserva: 17:15 - 18:45
            @endif

            @if($details22->periodo === 7)
            Hora de reserva: 18:45 - 20:15
            @endif

            @if($details22->periodo === 7)
            Hora de reserva: 20:15 - 21:45
            @endif

        </h2>
        <h2>
            Motivo de reserva: {{$details22->motivo}}
        </h2>
        <h2>
            Materia: {{$details22->materia}}
        </h2> 
        @elseif($details22->aceptadoRechazado === 0)
        <h1>
           Su solicitud de reserva a sido RECHAZADO
        </h1>
        <h2>

            Razon de rechazo: {{$details22->razon}}
        </h2>
        <h2>
            Detalles:
        </h2>
        <h2>
            Codigo de aula: {{ $details22->codigo }} 
        </h2>
        <h2>
            Fecha de reserva: {{ $details22->fechaReserva }} 
        </h2>
        <h2>
            @if($details22->periodo === 1)
            Hora de reserva: 6:45 - 8:15
            @endif

            @if($details22->periodo === 2)
            Hora de reserva: 8:15 - 9:45
            @endif

            @if($details22->periodo === 3)
            Hora de reserva: 9:45 - 11:15
            @endif

            @if($details22->periodo === 4)
            Hora de reserva: 11:15 - 12:45
            @endif

            @if($details22->periodo === 5)
            Hora de reserva: 12:45 - 14:15
            @endif

            @if($details22->periodo === 6)
            Hora de reserva: 14:15 - 15:45
            @endif

            @if($details22->periodo === 7)
            Hora de reserva: 15:45 - 17:15
            @endif

            @if($details22->periodo === 7)
            Hora de reserva: 17:15 - 18:45
            @endif

            @if($details22->periodo === 7)
            Hora de reserva: 18:45 - 20:15
            @endif

            @if($details22->periodo === 7)
            Hora de reserva: 20:15 - 21:45
            @endif
    @endif
    
   
</body>
</html>