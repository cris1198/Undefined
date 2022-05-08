<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use Illuminate\Http\Request;

class ReservaController extends Controller
{
    public function index()                    //retorna todos las Reservas
    {
        $reservas = Reserva::all();

        return $reservas;                     //JSON con las reservas
    }

    public function getById($id)                 //retorna una Reserva por el ID
    {
        $reserva = Reserva::findOrFail($id);     //si no encuentra una reserva devuelve falso
        return $reserva;                         //JSON con la reserva
    }

    public function getByUserId($userId)             //retorna una Reserva por el del ID
    {
        $reserva = Reserva::searchByUserId($userId); //si no encuentra una reserva devuelve falso
        return $reserva;                             //JSON con la reserva
    }

    public function acceptReservation($id)       //Cambia el estado de rechazado a Aceptado
    {
        $reserva = Reserva::findOrFail($id); 
        $reserva->aceptadoRechazado = 1;         //1 == Aceptado y 0 == Rechazado
        return response()->json([   
            'Respuesta' => 'Reserva Aceptada Correctamente'
        ], 202);                                 //JSON con la respuesta
    }

    public function rejectReservation($id)       //Cambia el estado de rechazado a Aceptado
    {
        $reserva = Reserva::findOrFail($id); 
        $reserva->aceptadoRechazado = 0;         //1 == Aceptado y 0 == Rechazado
        return response()->json([   
            'Respuesta' => 'Reserva Rechazada Correctamente'
        ], 202);                                 //JSON con la respuesta
    }

    public function store(Request $request){               //Crea una Reserva
        (new Reserva($request->input()))->saveOrFail();    //guarda con todos los datos, sino lo logra falla
        return response()->json([   
            'Respuesta' => 'Reserva Creada Correctamente'  //JSON con la respuesta
        ], 202);  
    }

    public function getAccepted($id){           //Obtiene las reservas aceptadas
        $reservas = Reserva::Aceptados($id);
        return $reservas;
    }

    public function getRejected($id){           //Obtiene las reservas rechazados
        $reservas = Reserva::Rechazados($id);
        return $reservas;
    }
}
