<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Grupo;
use App\Models\User;
use App\Models\Materia;

class GrupoController extends Controller
{

    public function getById($id){            //devuelve los grupos segun su id
        $grupo = Grupo::findOrFail($id);     
        $respuesta = array("nombre" => 1,"id_grupo" => $grupo->id, "grupo" => $grupo->nombreGrupo, "materia" => GrupoController::getName($grupo->id_materias));
        return $respuesta; 
    }

    public function assignUser(Request $request){   //crea un grupo
        $grupo = Grupo::findOrFail($request->id_grupo);
        $grupo->id_users = GrupoController::getUserId($request->correo);   
        $grupo->save();  
        return $grupo;  
    }

    public function getByUser($userId){       //devuelve los grupos segun el usuario al que este asignado
        $grupos = Grupo::searchByUserId($userId); 
        $respuesta = array();
        $pos = 1;

        foreach($grupos as $grupo){
            array_push($respuesta, array("nombre" => $pos,"id_grupo" => $grupo->id, "grupo" => $grupo->nombreGrupo, "materia" => GrupoController::getName($grupo->id_materias))); //crea el json con los datos importantes
            $pos = $pos + 1;
        }
        return $respuesta;
    }

    public function getToAssign(){            //devuelve los grupos que no tienen asignado un docente
        $grupos = Grupo::gruposWithoutUser(); 
        $respuesta = array();
        $pos = 1;

        foreach($grupos as $grupo){
            array_push($respuesta, array("nombre" => $pos,"id_grupo" => $grupo->id, "grupo" => $grupo->nombreGrupo, "materia" => GrupoController::getName($grupo->id_materias))); //crea el json con los datos importantes
            $pos = $pos + 1;
        }
        return $respuesta; 
    }

    public function assignAll(Request $request){            //devuelve el nommbre de una materia segun su id
        
        $id = 1;
        $bandera = false; 
        
        $i = 1;
        $cont1 = 0;
        while($i<20){
            if($request->$i){
                $grupo = Grupo::findOrFail($request->$id);
                $grupo->id_users = GrupoController::getUserId($request->correo);   
                $grupo->save();
            }
            $i=$i+1;
        }
        
        return response()->json([   
            'Respuesta' => 'Aceptados con exito'
        ], 202);  
    }

    private function getName($id){            //devuelve el nommbre de una materia segun su id
        $materia = Materia::findOrFail($id);     
        return $materia->nombreMateria;  
    }

    private function getUserId($correo){            //devuelve el nommbre de una materia segun su id
        $user = (new User)->findByCorreo($correo);
        $id = $user[0][ "id" ];     
        return $id;  
    }
    
}
