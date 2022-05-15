<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use Illuminate\Http\Request;

use App\Models\Aula;
use App\Models\User;

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
        $id_aula = $reserva->id_aulas;
        $reserva->aceptadoRechazado = 1;         //1 == Aceptado y 0 == Rechazado
        $reserva->save();

        $reservas = Reserva::getByAulaId($id_aula); //Rechazando reservas con el mismo id
        foreach ($reservas as $reservaAula) {
            if($reservaAula->id != $id){
                $reservaAula->aceptadoRechazado = 0;
                $reservaAula->save();
            }
        }

        return response()->json([   
            'Respuesta' => 'Reserva Aceptada Correctamente'
        ], 202);                                 //JSON con la respuesta
    }

    public function rejectReservation($id)       //Cambia el estado de rechazado a Aceptado
    {
        $reserva = Reserva::findOrFail($id); 
        $reserva->aceptadoRechazado = 0;         //1 == Aceptado y 0 == Rechazado
        $reserva->save();
        
        return response()->json([   
            'Respuesta' => 'Reserva Rechazada Correctamente'
        ], 202);                                 //JSON con la respuesta
    }

    public function store(Request $request){               //Crea una Reserva
        
        User::findOrFail($request->id_users); 
        Aula::findOrFail($request->id_aulas); 
        $codigoCorrecto = ReservaController::codigoCorrecto($request->codigo);
        $materiaCorrecto = ReservaController::materiaCorrecto($request->materia);
        $grupoCorrecto = ReservaController::grupoCorrecto($request->grupo);
        $cantidadCorrecto = ReservaController::cantidadCorrecto($request->cantidadEstudiantes);
        $cantidadPermitida = ReservaController::cantidadPermitidaAula($request->cantidadEstudiantes,$request->id_aulas);
        $periodoCorrecto = ReservaController::cantidadCorrecto($request->periodo);
        $cantidadPeriodoCorrecto = ReservaController::cantidadCorrecto($request->cantidadPeriodo);
        $razonCorrecto = ReservaController::razonCorrecto($request->razon);

        if(!$codigoCorrecto){
            return response()->json([   
                'codigo' => 1
             ], 500);
        }else{
            if(!$materiaCorrecto){
                return response()->json([   
                    'materia' => 0
                 ], 500);
            }else{
                if(!$grupoCorrecto){
                    return response()->json([   
                        'grupo' => 3
                     ], 500);
                }else{
                    if(!$cantidadCorrecto){
                        return response()->json([   
                            'cantidad' => 2
                         ], 500);
                    }else{
                        if(!$cantidadPermitida){
                            $aulasRecomendadas = Aula::recomendar($request->cantidadEstudiantes);
                            return $aulasRecomendadas;
                        }else{
                            if(!$periodoCorrecto){
                                return response()->json([   
                                    'periodo' => 4
                                ], 500);
                            }else{
                                if(!$cantidadPeriodoCorrecto){
                                    return response()->json([   
                                        'cantidadPeriodo' => 5
                                    ], 500);
                                }else{
                                    if(!$razonCorrecto){
                                        return response()->json([   
                                            'razon' => 6
                                        ], 500);
                                    }else{
                                        (new Reserva($request->input()))->saveOrFail();    //guarda con todos los datos, sino lo logra falla
                                        return response()->json([   
                                            'Respuesta' => 'Reserva Creada Correctamente'  //JSON con la respuesta
                                        ], 202); 
                                    }
                                }
                            }
                        }
                    }
                }
            }
        } 
         
    }

    public function getAccepted($id){           //Obtiene las reservas aceptadas
        $reservas = Reserva::Aceptados($id);
        return $reservas;
    }

    public function getRejected($id){           //Obtiene las reservas rechazados
        $reservas = Reserva::Rechazados($id);
        return $reservas;
    }

    public function getToReserve(){  //Obtiene las reservas aceptadas
        $reservas = Reserva::porReservar();
        return $reservas;
    }

    public function getAcceptAndReject($id){           //Obtiene las reservas rechazados
        $reservas = Reserva::AcceptAndReject($id);
        return $reservas;
    }
    

    public function getAvailablePeriods(Request $request, $id){
        Aula::findOrFail($id);
        $fecha = $request->input('fecha');
        $reservas = Reserva::getHorario ($id, $fecha);
        return $reservas;
    }

    private function cantidadCorrecto($capacidad){  // 1 si hay puro numeros,  0 si hay signos o letras
        if(strlen($capacidad)<4){
            $capacidadCorrecta =is_numeric($capacidad)+0;
            return $capacidadCorrecta;
        }
        return $capacidadCorrecta = 0;
    }

    private function codigoCorrecto($carac){ //0 si el caracteristicas y ubicacion contiene caracteres no alfanuericos, 1 correcto
        $caracCorrecto =true;
        $i = 0 ;
        if(strlen($carac)<16 && strlen($carac) > 2){
            while($i < strlen($carac) && $caracCorrecto && strlen($carac)<30 ){
                $car  = $carac[$i];
                if(!(((ord($car)>= 48 && ord($car) <= 57) || ord($car)>= 65 && ord($car)<=90)
                 || (ord($car)>= 97 && ord($car)<=122) || ord($car)==32)){
                    $caracCorrecto = false;
                }
                $i++;
            }
        }else{
            $caracCorrecto = false;
        }
        return $caracCorrecto;
    }

    private function materiaCorrecto($carac){ //0 si el caracteristicas y ubicacion contiene caracteres no alfanuericos, 1 correcto
        $caracCorrecto =true;
        $i = 0 ;
        if(strlen($carac)<26 && strlen($carac) > 4){
            while($i < strlen($carac) && $caracCorrecto && strlen($carac)<30 ){
                $car  = $carac[$i];
                if(!(((ord($car)>= 48 && ord($car) <= 57) || ord($car)>= 65 && ord($car)<=90)
                 || (ord($car)>= 97 && ord($car)<=122) || ord($car)==32)){
                    $caracCorrecto = false;
                }
                $i++;
            }
        }else{
            $caracCorrecto = false;
        }
        return $caracCorrecto;
    }

    private function grupoCorrecto($carac){ //0 si el caracteristicas y ubicacion contiene caracteres no alfanuericos, 1 correcto
        $caracCorrecto =true;
        $i = 0 ;
        if(strlen($carac)<16 && strlen($carac) > 0){
            while($i < strlen($carac) && $caracCorrecto && strlen($carac)<30 ){
                $car  = $carac[$i];
                if(!(((ord($car)>= 48 && ord($car) <= 57) || ord($car)>= 65 && ord($car)<=90)
                 || (ord($car)>= 97 && ord($car)<=122) || ord($car)==32)){
                    $caracCorrecto = false;
                }
                $i++;
            }
        }else{
            $caracCorrecto = false;
        }
        return $caracCorrecto;
    }

    private function razonCorrecto($carac){ //0 si el caracteristicas y ubicacion contiene caracteres no alfanuericos, 1 correcto
        $caracCorrecto =true;
        $i = 0 ;
        if(strlen($carac)<41 && strlen($carac) > 4){
            while($i < strlen($carac) && $caracCorrecto && strlen($carac)<30 ){
                $car  = $carac[$i];
                if(!((ord($car)>= 65 && ord($car)<=90) || (ord($car)>= 97 && ord($car)<=122) || ord($car)==32)){
                    $caracCorrecto = false;
                }
                $i++;
            }
        }else{
            $caracCorrecto = false;
        }
        return $caracCorrecto;
    }

    private function cantidadPermitidaAula($capacidad, $id_aula){  //Valida que la cantidad de estudiantes ingresados, sea dentro el rango
        $permitido=false;                                          //de la capacidad del aula seleccionada
        $aula = Aula::findOrFail($id_aula); 
        if(($aula->capacidad) >= $capacidad && ($aula->capacidad) >= $capacidad+20){
            return $permitido = true;
        }
        return $permitido;
    }


}
