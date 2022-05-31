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
        return $grupo;  
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

        foreach($grupos as $grupo){
            array_push($respuesta, array("id_grupo" => $grupo->id, "grupo" => $grupo->nombreGrupo, "materia" => GrupoController::getName($grupo->id_materias))); //crea el json con los datos importantes
        }
        return $respuesta;
    }

    public function getToAssign(){            //devuelve los grupos que no tienen asignado un docente
        $grupos = Grupo::gruposWithoutUser(); 
        $respuesta = array();

        foreach($grupos as $grupo){
            array_push($respuesta, array("id_grupo" => $grupo->id, "grupo" => $grupo->nombreGrupo, "materia" => GrupoController::getName($grupo->id_materias))); //crea el json con los datos importantes
        }
        return $respuesta; 
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
