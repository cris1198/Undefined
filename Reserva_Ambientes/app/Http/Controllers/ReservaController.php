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
use App\Mail\Contigua;
class ReservaController extends Controller
{
    
    public function reporteTodos(){
        $reservas = Reserva::all();
        return $reservas;
    }

    public function getAllAcceptAndReject(){           //Obtiene las reservas rechazados
        $reservas = Reserva::AllAcceptAndReject();
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

    public function getAllAcceptAndRejecCont(){           //Obtiene las reservas rechazados
        $reservas = Reserva::AllAcceptAndRejectCont();
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

        $reservasResp = array();

        foreach($reservas as $reserva){
            foreach($reservas as $res){
                $arrayAux = array();
                $arrayAux2 = array();
                if(!($reserva->id ==  $res->id)){
                    if($reserva->id_users ==  $res->id_users && $reserva->id_grupos ==  $res->id_grupos && $reserva->cantidadEstudiantes ==  $res->cantidadEstudiantes &&
                        $reserva->tipo ==  $res->tipo && $reserva->fechaReserva ==  $res->fechaReserva && $reserva->periodo ==  $res->periodo && $reserva->cantidadPeriodo ==  $res->cantidadPeriodo){
                        array_push($arrayAux, $reserva);
                        array_push($arrayAux, $res);
                        array_push($arrayAux2, $res);
                        array_push($arrayAux2, $reserva);
                        if(!(in_array($arrayAux2, $reservasResp))){
                            array_push($reservasResp, $arrayAux);
                        }
                    }
                    
                }     
            }    
        }

        if(empty($reservasResp)){
            return response()->json([   
                'noRecomendacion' => 1,  //JSON con la respuesta
            ], 501); 
        }

        return $reservasResp;
    }

    public function reporteAceptados(){
        $id =1;
        $reservas = Reserva::where("aceptadoRechazado","=",$id)
                ->where('razon', '!=', 'Aula Contigua')
                ->orWhereNull('razon')
                ->get();

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
    public function reporteRechazados(){
        $id =0;
        $reservas = Reserva::where("aceptadoRechazado","=",$id)->get();
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

    public function index()                    //retorna todos las Reservas
    {
        return Reserva::all();                 //JSON con las reservas
    }

    public function getStats(){
        $numAccept = Reserva::numAceptados();
        $numAcceptCount = Reserva::numAceptadosContiguas();
        $numReject = Reserva::numRechazados();

        return array("numAccept" => $numAccept,"numAcceptCount" => (int)($numAcceptCount/2),"numReject" => $numReject, "total" => ($numAccept + (int)($numAcceptCount/2) + $numReject));
    }

    public function eliminarReserva($id){
        Reserva::findOrFail($id);         //si no encuentra ambiente devuelve falso
        Reserva::destroy($id);
        return response()->json([              //JSON con los ambientes
            'status' => 'eliminado'
        ], 201);  
    }
    public function eliminarReservaContigua($id1, $id2){
        Reserva::findOrFail($id1);         //si no encuentra ambiente devuelve falso
        Reserva::findOrFail($id2); 
        Reserva::destroy($id1);
        Reserva::destroy($id2);
        return response()->json([              //JSON con los ambientes
            'status' => 'Contiguos Eliminados'
        ], 201);  
    }
    public function getById($id)                 //retorna una Reserva por el ID
    {
        $reserva = Reserva::findOrFail($id);     //si no encuentra una reserva devuelve falso
        return $reserva;                         //JSON con la reserva
    }

    public function getByUserId($userId)             //retorna una Reserva por el del ID
    {
        $reservas = Reserva::searchByUserId($userId); //si no encuentra una reserva devuelve falso
        return $reservas;                            //JSON con la reserva
    }

    /* public function getByUserIdContguas($userId){
        $reservas = Reserva::searchByUserId($userId); //si no encuentra una reserva devuelve falso
        $reservasResp = array();

        foreach($reservas as $reserva){
            foreach($reservas as $res){
                $arrayAux = array();
                $arrayAux2 = array();
                if(!($reserva->id ==  $res->id)){
                    if($reserva->razon == "Aula Contigua" && $res->razon == "Aula Contigua"){
                        if($reserva->id_users ==  $res->id_users && $reserva->id_grupos ==  $res->id_grupos && $reserva->cantidadEstudiantes ==  $res->cantidadEstudiantes &&
                         $reserva->tipo ==  $res->tipo && $reserva->fechaReserva ==  $res->fechaReserva && $reserva->periodo ==  $res->periodo && $reserva->cantidadPeriodo ==  $res->cantidadPeriodo){
                            array_push($arrayAux, $reserva);
                            array_push($arrayAux, $res);
                            array_push($arrayAux2, $res);
                            array_push($arrayAux2, $reserva);
                            if(!in_array($arrayAux, $reservasResp) || !in_array($arrayAux2, $reservasResp)){
                                array_push($reservasResp, $arrayAux);
                            }
                        }
                    }
                }     
            }    
        }

        if(empty($aulasContiguas)){
            return response()->json([   
                'noRecomendacion' => 1,  //JSON con la respuesta
            ], 501); 
        }

        return $reservasResp;
    } */

    public function filterPrimeros()
    {
        $reservas = Reserva::getPrimeros();
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
        
        $json= json_encode($reservas);
        $arrayReservas = json_decode($json, true);  
        
        $i=0;
        while($i<(sizeof($arrayReservas))){
            
            $user = User::findOrFail($arrayReservas[$i]["id_users"]);
            $nom_completo = sprintf("%s %s", $user->name, $user->apellido);
            array_push($arrayReservas[$i], array("nombre" => $nom_completo));
            $i = $i +1;
        }


        return json_encode($arrayReservas);
    }

    public function filterUltimos()
    {
        $reservas = Reserva::getUltimos();
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
        
        

        $json= json_encode($reservas);
        $arrayReservas = json_decode($json, true);  
        
        $i=0;
        while($i<(sizeof($arrayReservas))){
            
            $user = User::findOrFail($arrayReservas[$i]["id_users"]);
            $nom_completo = sprintf("%s %s", $user->name, $user->apellido);
            array_push($arrayReservas[$i], array("nombre" => $nom_completo));
            $i = $i +1;
        }


        return json_encode($arrayReservas);
    }

    public function filterUrgencia()
    {
        $reservas = Reserva::getUrgencia();
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
        
        

        $json= json_encode($reservas);
        $arrayReservas = json_decode($json, true);  
        
        $i=0;
        while($i<(sizeof($arrayReservas))){
            
            $user = User::findOrFail($arrayReservas[$i]["id_users"]);
            $nom_completo = sprintf("%s %s", $user->name, $user->apellido);
            array_push($arrayReservas[$i], array("nombre" => $nom_completo));
            $i = $i +1;
        }


        return json_encode($arrayReservas);
    }

    public function acceptReservation($id, $id_aula)       //Cambia el estado de rechazado a Aceptado
    {
        $aula = Aula::findOrFail($id_aula);
        $reserva = Reserva::findOrFail($id); 
        $reserva->id_aulas = $id_aula;
        $reserva->codigo = $aula->codigo;
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
        $materia  = "intro a la progra";       
        $usuario = User::where("id","=",$reserva->id_users)->first();

        $grupo = Grupo::where("id","=",$reserva->id_grupos)->first();
        $materia = Materia::where("id","=",$grupo->id_materias)->first();
        Mail::to($usuario->email)->send(new TestMail($reserva,$materia->nombreMateria,$grupo->nombreGrupo)); // a donde enviaremos el correo de prueba
        return response()->json([   
            'Respuesta' => 'Reserva Aceptada Correctamente'
        ], 202);                                 //JSON con la respuesta
    }

    public function acceptReservationContigua($id, $id_aula1, $id_aula2)       //Cambia el estado de rechazado a Aceptado
    {
        $aula1 = Aula::findOrFail($id_aula1);
        $aula2 = Aula::findOrFail($id_aula2);
        $reserva = Reserva::findOrFail($id);
        if($reserva->aceptadoRechazado == 1){
            return response()->json([   
                'Respuesta' => 'Reserva ya creada'
            ], 508); 
        } 
        $reserva->id_aulas = $id_aula1;
        $reserva->codigo = $aula1->codigo;
        $reserva->razon = "Aula Contigua";
        $reserva->aceptadoRechazado = 1;         //1 == Aceptado y 0 == Rechazado
        $reserva->save();

        $nuevaReserva = new Request(array(
            "id_users" => $reserva->id_users,
            "id_grupos" => $reserva->id_grupos,
            "id_aulas" => $id_aula2,
            "codigo" => $aula2->codigo,
            "cantidadEstudiantes" => $reserva->cantidadEstudiantes,
            "tipo" => $reserva->tipo,
            "fechaReserva" => $reserva->fechaReserva,
            "periodo" => $reserva->periodo,
            "cantidadPeriodo" => $reserva->cantidadPeriodo,
            "aceptadoRechazado" => $reserva->aceptadoRechazado,
            "motivo" => $reserva->motivo,
            "razon" => $reserva->razon
        ));

        if(ReservaController::store($nuevaReserva)){
            //email
            $usuario = User::where("id","=",$reserva->id_users)->first();
            $grupo = Grupo::where("id","=",$reserva->id_grupos)->first();
            $materia = Materia::where("id","=",$grupo->id_materias)->first();
            $segundaAula= $aula2->codigo;
            Mail::to($usuario->email)->send(new Contigua($reserva,$materia->nombreMateria,$grupo->nombreGrupo,$segundaAula));
            return response()->json([   
                'Respuesta' => 'Reserva Aceptada Correctamente'
            ], 202); 

        }else{
            return response()->json([   
                'Respuesta' => 'No se pudo crear la reserva contigua'
            ], 508); 
        }
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
        $grupo = Grupo::where("id","=",$reserva->id_grupos)->first();
        $materia = Materia::where("id","=",$grupo->id_materias)->first();
        Mail::to($usuario->email)->send(new TestMail($reserva,$materia->nombreMateria,$grupo->nombreGrupo)); 
        return response()->json([   
            'Respuesta' => 'Reserva Rechazada Correctamente'
        ], 202);                                 //JSON con la respuesta
    }
   
    public function store(Request $request){               //Crea una Reserva
        
        User::findOrFail($request->id_users); 

        //-----------Inicio Validaciones----------------
        
        $cantidadCorrecto = ReservaController::cantidadCorrecto($request->cantidadEstudiantes);
        $periodoCorrecto = ReservaController::cantidadCorrecto($request->periodo);
        $cantidadPeriodoCorrecto = ReservaController::cantidadCorrecto($request->cantidadPeriodo);
        if($request->motivo){
            $razonCorrecto = ReservaController::razonCorrecto($request->motivo);
        }else{
            $razonCorrecto = true;
        }
        //-----------Fin Validaciones-------------------------

                    if(!$cantidadCorrecto){
                        return response()->json([   
                            'cantidad' => 2
                         ], 500);
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
                                        if($request->cantidadPeriodo == 2 && $request->periodo == 10){
                                            return response()->json([   
                                                'Respuesta' => "Periodo no puede tener 2",
                                            ], 500);
                                        }else{  
                                            $reserva1 = new Reserva($request->all());
                                            $reserva1->observaciones = ReservaController::observaciones($request->id_users, $request->id_grupos, $request->cantidadEstudiantes);

                                            $reserva1->save();                                  //Guardar una reserva
                                            return response()->json([   
                                                'Respuesta' => 'Solicitud creada Correctamente',  //JSON con la respuesta
                                            ], 202); 
                                        }

                                    }
                                }
                            }
                            
                    }
                    
            
    }

    public function getAccepted($id){           //Obtiene las reservas aceptadas
        $reservas = Reserva::Aceptados($id);

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


    public function getAcceptedContigua($id){           //Obtiene las reservas aceptadas
        $reservas = Reserva::AceptadosContiguas($id);

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


        $reservasResp = array();

        foreach($reservas as $reserva){
            foreach($reservas as $res){
                $arrayAux = array();
                $arrayAux2 = array();
                if(!($reserva->id ==  $res->id)){
                    if($reserva->id_users ==  $res->id_users && $reserva->id_grupos ==  $res->id_grupos && $reserva->cantidadEstudiantes ==  $res->cantidadEstudiantes &&
                        $reserva->tipo ==  $res->tipo && $reserva->fechaReserva ==  $res->fechaReserva && $reserva->periodo ==  $res->periodo && $reserva->cantidadPeriodo ==  $res->cantidadPeriodo){
                        array_push($arrayAux, $reserva);
                        array_push($arrayAux, $res);
                        array_push($arrayAux2, $res);
                        array_push($arrayAux2, $reserva);
                        if(!(in_array($arrayAux2, $reservasResp))){
                            array_push($reservasResp, $arrayAux);
                        }
                    }
                    
                }     
            }    
        }

        if(empty($reservasResp)){
            return response()->json([   
                'noRecomendacion' => 1,  //JSON con la respuesta
            ], 501); 
        }

        return $reservasResp;
    }

    
    
    
    public function getRejected($id){           //Obtiene las reservas rechazados
        $reservas = Reserva::Rechazados($id);
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
              

        $json= json_encode($reservas);
        $arrayReservas = json_decode($json, true);  
        
        $i=0;
        while($i<(sizeof($arrayReservas))){
            
            $user = User::findOrFail($arrayReservas[$i]["id_users"]);
            $nom_completo = sprintf("%s %s", $user->name, $user->apellido);
            array_push($arrayReservas[$i], array("nombre" => $nom_completo));
            $i = $i +1;
        }


        return json_encode($arrayReservas);
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
            ], 502); 
        }

        return $aulasRecomendadas;
    }
    
    public function getRecommendationContiguas(Request $request){  //Recomienda aulas segun las caracteristicas, tipo y capacidad ingresado
        
        $tipos = array("Aula", "Laboratorio", "Auditorio");
        $ubicaciones = array("Edificio Academico 2 planta baja", "Edificio Academico 2 piso 1", "Edificio Academico 2 piso 2", "Edificio Academico 2 piso 3", "Biblioteca", "Campus Central", "Trencito", "Laboratorios");
        $aulasContiguas = array();

        foreach($tipos as $tipo){
            foreach($ubicaciones as $ubicacion){
                $aulas = Aula::recomendarContiguas($request->capacidad/2, $tipo, $ubicacion);
                $json= json_encode($aulas);
                $arrayAulas = json_decode($json, true);
                if(!empty($arrayAulas)) {
                    if(sizeof($arrayAulas)%2 == 1){
                        if(sizeof($arrayAulas) == 1){
                            
                        }else{
                            $cont = 1;
                            foreach($aulas as $aula){
                                if($cont <= sizeof($arrayAulas)-1) {
                                    array_push($aulasContiguas, $aula);
                                }
                                $cont = $cont + 1;
                            }
                        }
                    }else{
                        foreach($aulas as $aula){
                            array_push($aulasContiguas, $aula);   
                        }
                    }
                }
            }
        }

        if(empty($aulasContiguas)){
            return response()->json([   
                'noRecomendacion' => 1,  //JSON con la respuesta
            ], 501); 
        }
        
        if($request->cantidadPeriodo == 2){
            $periodo2 = $request->periodo + 1;
            $reservas = Reserva::getByFechaPeriodo1($request->fecha, $request->periodo, $periodo2);
        }else{
            $reservas = Reserva::getByFechaPeriodo2($request->fecha, $request->periodo);
        }
        $aulasRecomendadas = array();
        $aulasReservadas = array();
        $json= json_encode($reservas);
        $arrayReservas = json_decode($json, true);

        
        if(!empty($arrayReservas)){
            foreach($reservas as $reserva){
                $aulaRes = Aula::findOrFail($reserva->id_aulas);
                array_push($aulasReservadas, $aulaRes);
            }
        }else{
            $pos = 0; 

            while($pos < sizeof($aulasContiguas)-1){
                $aulaAux = array();
                
                array_push($aulaAux, $aulasContiguas[$pos]);
                array_push($aulaAux, $aulasContiguas[$pos+1]);

                array_push($aulasRecomendadas, $aulaAux);

                $pos = $pos + 2;
            }
            return $aulasRecomendadas; 
        }

        $pos = 0; 

        while($pos < sizeof($aulasContiguas)-1){
            $aulaAux = array();
            if(!(in_array($aulasContiguas[$pos], $aulasReservadas))) {
                if(!(in_array($aulasContiguas[$pos+1], $aulasReservadas))){
                    array_push($aulaAux, $aulasContiguas[$pos]);
                    array_push($aulaAux, $aulasContiguas[$pos+1]);
                  
                    array_push($aulasRecomendadas, $aulaAux);
                }
            }
            $pos = $pos + 2;
        }

        if(empty($aulasRecomendadas)){
            return response()->json([   
                'Ocupadas' => 2,  //JSON con la respuesta
            ], 502); 
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

    public function observaciones($ids,$idg, $cant){       //id_user   materia     grupo  cantidadEstudaintes
        $obs = "";
        $grupo = Grupo::where("id","=",$idg)->first();
        $materia = Materia::where("id","=",$grupo->id_materias)->first();
       
        if(isset($materia->id)){
            $grupo = Grupo::where("id_materias","=",$materia->id)->first();
            if(isset($grupo->id)){
                //if($grupo->id_users ==$ids){
                    /* $aulaa = Aula::where("id","=",$idau)->first();
                    if($cant <= $aulaa->capacidad){ */
                        $obs = "No hay observaciones";
                    /* }else{
                        $obs = "La cantidad de alumnos sobrepasa a la cantidad del total del aula";
                    }*/
                //}else{
                  //  $obs = "El grupo no le corresponde";
                //}
            }else{
                $obs="El grupo no existe";
            }
        }else{
            $obs = "La materia que solicita no existe";
        }
        return $obs;
    }

}
