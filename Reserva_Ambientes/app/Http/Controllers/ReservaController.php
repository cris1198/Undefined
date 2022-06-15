<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use Illuminate\Http\Request;

use App\Models\Aula;
use App\Models\Materia;
use App\Models\Grupo;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Mail;
use App\Mail\TestMail;
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

    public function filterPrimeros()
    {
        $reservas = Reserva::getPrimeros();
        return $reservas;
    }

    public function filterUltimos()
    {
        $reservas = Reserva::getUltimos();
        return $reservas;
    }

    public function filterUrgencia()
    {
        $reservas = Reserva::getUrgencia();
        return $reservas;
    }

    public function acceptReservation($id, $id_aula)       //Cambia el estado de rechazado a Aceptado
    {
        $reserva = Reserva::findOrFail($id); 
        $reserva->id_aulas = $id_aula;
        /* $fecha = $reserva->fechaReserva;
        $periodo = $reserva->periodo; */
        $reserva->aceptadoRechazado = 1;         //1 == Aceptado y 0 == Rechazado
        $reserva->save();

       /*  $reservas = Reserva::getByAulaId($id_aula, $fecha, $periodo); //Rechazando reservas con el mismo id
        foreach ($reservas as $reservaAula) {
            if($reservaAula->id != $id){
                $reservaAula->aceptadoRechazado = 0;
                $reservaAula->razon = "El horario y fecha que solicita para la reserva ya esta ocupado";
                $reservaAula->save(); 
                $usuario = User::where("id","=",$reserva->id_users)->first();  
                Mail::to($usuario->email)->send(new TestMail($reserva));
            }
        } */
        $usuario = User::where("id","=",$reserva->id_users)->first();
        //Mail::to($usuario->email)->send(new TestMail($reserva)); // a donde enviaremos el correo de prueba
        return response()->json([   
            'Respuesta' => 'Reserva Aceptada Correctamente'
        ], 202);                                 //JSON con la respuesta
    }
    
    // public function rejectReservation($id)  {

    // }     //Cambia el estado de rechazado a Aceptado
    public function rejectReservation(Request $request, $id)       //Cambia el estado de rechazado a Aceptado
    {
        $reserva = Reserva::findOrFail($id); 
        $reserva->aceptadoRechazado = 0;
        $reserva->razon = $request->razon;         //1 == Aceptado y 0 == Rechazado
        $reserva->save();
        $usuario = User::where("id","=",$reserva->id_users)->first();
        Mail::to($usuario->email)->send(new TestMail($reserva)); 
        return response()->json([   
            'Respuesta' => 'Reserva Rechazada Correctamente'
        ], 202);                                 //JSON con la respuesta
    }
   
    public function store(Request $request){               //Crea una Reserva
        
        User::findOrFail($request->id_users); 
        //Aula::findOrFail($request->id_aulas); 

        //-----------Inicio Validaciones----------------
        //$codigoCorrecto = ReservaController::codigoCorrecto($request->codigo);
        
        $cantidadCorrecto = ReservaController::cantidadCorrecto($request->cantidadEstudiantes);
        //$cantidadPermitida = ReservaController::cantidadPermitidaAula($request->cantidadEstudiantes,$request->id_aulas);
        $periodoCorrecto = ReservaController::cantidadCorrecto($request->periodo);
        $cantidadPeriodoCorrecto = ReservaController::cantidadCorrecto($request->cantidadPeriodo);
        $razonCorrecto = ReservaController::razonCorrecto($request->motivo);
        //-----------Fin Validaciones-------------------------

        /* if(!$codigoCorrecto){
            return response()->json([   
                'codigo' => 1
             ], 500);
        }else{ */
                    if(!$cantidadCorrecto){
                        return response()->json([   
                            'cantidad' => 2
                         ], 500);
                    }else{
                        /* if(!$cantidadPermitida){ */
                           /*  $aulasRecomendadas = Aula::recomendar($request->cantidadEstudiantes);
                            return $aulasRecomendadas; */
                        /* }else{ */
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
                                        if($request->cantidadPeriodo == 2 && $request->periodo == 10){
                                            return response()->json([   
                                                'Respuesta' => "Periodo no puede tener 2",
                                            ], 500);
                                        }else{    
                                            //(new Reserva($request->input()))->saveOrFail();    //guarda con todos los datos, sino lo logra falla
                                            $reserva1 = new Reserva($request->all());
                                            $reserva1->observaciones = ReservaController::observaciones($request->id_users, $request->id_grupo, $request->cantidadEstudiantes, $request->id_materia);
                                            //$is_user = Auth::user()->id;
                                            //$is_user = auth()->user()->id;
                                            //$reserva1->id_users = $is_user;
                                            $reserva1->save();                                  //Guardar una reserva
                                            return response()->json([   
                                                'Respuesta' => 'Solicitud creada Correctamente',  //JSON con la respuesta
                                                //"data" => auth()->user()
                                            ], 202); 
                                        }

                                    }
                                }
                            }
                       /*  } */
                    }
              /*   } */
            
    }

    public function getAccepted($id){           //Obtiene las reservas aceptadas
        $reservas = Reserva::Aceptados($id);
        return $reservas;
    }
    
    public function observaciones($ids,$idg, $cant, $idmat){       //id_user   materia     grupo  cantidadEstudaintes
        $obs = "";
        $materia = Materia::where("id","=",$idmat)->first();
       
        if(isset($materia->id)){
            $grupo = Grupo::where("id_materias","=",$materia->id)->first();
            if(isset($grupo->id)){
                if($grupo->id_users ==$ids){
                    /* $aulaa = Aula::where("id","=",$idau)->first();
                    if($cant <= $aulaa->capacidad){ */
                        $obs = "No hay observaciones";
                    /* }else{
                        $obs = "La cantidad de alumnos sobrepasa a la cantidad del total del aula";
                    } */
                }else{
                    $obs = "El grupo no le corresponde";
                }
            }else{
                $obs="El grupo no existe";
            }
        }else{
            $obs = "La materia que solicita no existe";
        }
        return $obs;
    }
    public function getRejected($id){           //Obtiene las reservas rechazados
        $reservas = Reserva::Rechazados($id);
        return $reservas;
    }

    public function getToReserve(){  //Obtiene las reservas aceptadas
        $reservas = Reserva::porReservar();
        $periodosDisponibles = array("nada","6:45 - 8:15", "8:15 - 9:45", "9:45 - 11:15", "11:15 - 12:45", "12:45 - 14:15", "14:15 - 15:45", "15:45 - 17:15", "17:15 - 18:45", "18:45 - 20:15", "20:15 - 21:45");

            $i=0;
                foreach ($reservas as $reserva){  //convierte numero de periodo en texto
                    $j=1;
                    $bandera = true;
                    while($j < 11 && $bandera){
                        if ($reserva["periodo"] == $j) {
                            $reservas[$i]["periodo"] = $periodosDisponibles[$j];
                            $bandera = false;
                        }
                        $j=$j+1;
                    }
                    $i=$i+1;
                }
              

        return $reservas;
    }

    public function getAcceptAndReject($id){           //Obtiene las reservas rechazados
        $reservas = Reserva::AcceptAndReject($id);
        $periodosDisponibles = array("nada","6:45 - 8:15", "8:15 - 9:45", "9:45 - 11:15", "11:15 - 12:45", "12:45 - 14:15", "14:15 - 15:45", "15:45 - 17:15", "17:15 - 18:45", "18:45 - 20:15", "20:15 - 21:45");

            $i=0;
                foreach ($reservas as $reserva){       //convierte numero de periodo en texto
                    $j=1;
                    $bandera = true;
                    while($j < 11 && $bandera){
                        if ($reserva["periodo"] == $j) {
                            $reservas[$i]["periodo"] = $periodosDisponibles[$j];
                            $bandera = false;
                        }
                        $j=$j+1;
                    }
                    $i=$i+1;
                }

        return $reservas;
    }

    public function getRecommendation(Request $request){  //Recomienda aulas segun las caracteristicas, tipo y capacidad ingresado
        $aulas = Aula::recomendar($request->capacidad);
        if($request->cantidadPeriodo == 2){
            $periodo2 = $request->periodo + 1;

            $reservas = Reserva::getByFechaPeriodo1($request->fecha, $request->periodo, $periodo2);
        }else{
            $reservas = Reserva::getByFechaPeriodo2($request->fecha, $request->periodo);
        }
        $aulasRecomendadas = array();
        $aulasReservadas = array();
        $json= json_encode($aulas);
        $arrayAulas = json_decode($json, true);
        $json= json_encode($reservas);
        $arrayReservas = json_decode($json, true);

        if(!empty($arrayReservas)){
            foreach($reservas as $reserva){
                $aulaRes = Aula::findOrFail($reserva->id_aulas);
                array_push($aulasReservadas, $aulaRes);
            }
        }else{
            return $aulas; 
        }
        if(!empty($arrayAulas)){
            foreach($aulas as $aula){
                if(!(in_array($aula, $aulasReservadas))) {
                    array_push($aulasRecomendadas, $aula);
                }
            }
        }else{
            return response()->json([   
                'aulaVacio' => 0,  //JSON con la respuesta
            ], 202); 
        }

        return $aulasRecomendadas;
    }
    
    public function getRecommendationContiguas(Request $request){  //Recomienda aulas segun las caracteristicas, tipo y capacidad ingresado
        $aulas = Aula::recomendarContiguas($request->capacidad);
        if($request->cantidadPeriodo == 2){
            $periodo2 = $request->periodo + 1;

            $reservas = Reserva::getByFechaPeriodo1($request->fecha, $request->periodo, $periodo2);
        }else{
            $reservas = Reserva::getByFechaPeriodo2($request->fecha, $request->periodo);
        }
        $aulasRecomendadas = array();
        $aulasReservadas = array();
        $json= json_encode($aulas);
        $arrayAulas = json_decode($json, true);
        $json= json_encode($reservas);
        $arrayReservas = json_decode($json, true);


        
        if(!empty($arrayReservas)){
            foreach($reservas as $reserva){
                $aulaRes = Aula::findOrFail($reserva->id_aulas);
                array_push($aulasReservadas, $aulaRes);
            }
        }else{
            return $aulas; 
        }
        if(!empty($arrayAulas)){
            foreach($aulas as $aula){
                if(!(in_array($aula, $aulasReservadas))) {
                    array_push($aulasRecomendadas, $aula);
                }
            }
        }else{
            return response()->json([   
                'aulaVacio' => 0,  //JSON con la respuesta
            ], 202); 
        }

        return $aulasRecomendadas;
    }

    public function getAvailablePeriods(Request $request, $id){ //devuelve los periodos disponibles, con el status 1=No disponible y 0=Disponible
        Aula::findOrFail($id);
        $fecha = $request->fecha;
        $reservas = Reserva::getHorario ($id, $fecha);
        $periodosModificados= array();
        $periodosDisponibles = array("6:45 - 8:15", "8:15 - 9:45", "9:45 - 11:15", "11:15 - 12:45", "12:45 - 14:15", "14:15 - 15:45", "15:45 - 17:15", "17:15 - 18:45", "18:45 - 20:15", "20:15 - 21:45");
        $periodosUtilizados = array();
        $numPeriodo = 1;

        foreach($periodosDisponibles as $periodo){
            array_push($periodosModificados, array("periodo" => $periodo, "status" => "0", "numPeriodo" => $numPeriodo));
            $numPeriodo=$numPeriodo+1;
        }
        
        foreach ($reservas as $reserva) {  
            
            if($reserva->periodo == 1){
                array_push($periodosUtilizados, array("periodo" => "6:45 - 8:15", "status" => "1", "numPeriodo" => 1));
            }
            if($reserva->periodo == 2){
                array_push($periodosUtilizados, array("periodo" => "8:15 - 9:45", "status" => "1", "numPeriodo" => 2));
            }
            if($reserva->periodo == 3){
                array_push($periodosUtilizados, array("periodo" => "9:45 - 11:15", "status" => "1", "numPeriodo" => 3));
            }
            if($reserva->periodo == 4){
                array_push($periodosUtilizados, array("periodo" => "11:15 - 12:45", "status" => "1", "numPeriodo" => 4));
            }
            if($reserva->periodo == 5){
                array_push($periodosUtilizados, array("periodo" => "12:45 - 14:15", "status" => "1", "numPeriodo" => 5));
            }
            if($reserva->periodo == 6){
                array_push($periodosUtilizados, array("periodo" => "14:15 - 15:45", "status" => "1", "numPeriodo" => 6));
            }
            if($reserva->periodo == 7){
                array_push($periodosUtilizados, array("periodo" => "15:45 - 17:15", "status" => "1", "numPeriodo" => 7));
            }
            if($reserva->periodo == 8){
                array_push($periodosUtilizados, array("periodo" => "17:15 - 18:45", "status" => "1", "numPeriodo" => 8));
            }
            if($reserva->periodo == 9){
                array_push($periodosUtilizados, array("periodo" => "18:45 - 20:15", "status" => "1", "numPeriodo" => 9));
            }
            if($reserva->periodo == 10){
                array_push($periodosUtilizados, array("periodo" => "20:15 - 21:45", "status" => "1", "numPeriodo" => 10));
            }    
        }

        foreach ($periodosUtilizados as $periodoU) {
            $i=0;
            foreach ($periodosModificados as $periodoM) {
                if ($periodoU["periodo"] == $periodoM["periodo"]) {
                    $periodosModificados[$i][ "status" ] = "1";
                }
                $i=$i+1;
            }
        }  

        return json_encode($periodosModificados);
    }


//----------------------------------------VALIDACIONES---------------------------------------------------------

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
                if(!((ord($car)>= 48 && ord($car) <= 57) || (ord($car)>= 65 && ord($car)<=90) || (ord($car)>= 97 && ord($car)<=122) || ord($car)==32)){
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
