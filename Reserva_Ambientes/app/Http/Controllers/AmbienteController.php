<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Aula;

class AmbienteController extends Controller
{
    public function index()                    //retorna todos los ambientes
    {
        $ambientes = Aula::all();
        return $ambientes;                     //JSON con los ambientes
    }
    public function store(Request $request)   {
        $nuevaAula = new Aula();
        $nuevaAula->id_administrador = 1;
        $nuevaAula->capacidad = $request->capacidad;
        $nuevaAula->codigo = $request->codigo;  
        $nuevaAula->tipo = $request->tipo;  
        $nuevaAula->caracteristicas = $request->caracteristicas;
        $nuevaAula->nombreAula = $request->nombreAula; 
        $nuevaAula->ubicacion = $request->ubicacion;
        $nuevaAula->save();
    }
    public function getById($id)               //retorna un Ambiente por el ID
    {
        $ambiente = Aula::findOrFail($id);     //si no encuentra ambiente devuelve falso
        return $ambiente;                      //JSON con los ambientes
    }

    public function search_and_filter(Request $request)      //busca codigo, nombreAula o ubicacion, mas los filtros 
    {
        $buscar = $request->buscar;                          //obteniendo los datos de JSON
        $caracteristicas = $request->caracteristicas;
        $tipo = $request->tipo;
        $rangoBajo = $request->rangoBajo;
        $rangoAlto = $request->rangoAlto;

        $ambientes = Aula::search($buscar,                  
        $caracteristicas, $tipo, $rangoBajo, $rangoAlto);    //devuelve coleccion de Aulas
        return $ambientes;                                   //devuelve JSON con datos de Aula
    }

    public function update(Request $request, $id)        //actualiza los datos de un ambiente
    {
        $aula = Aula::findOrFail($id);                   //si no encuentra ambiente devuelve falso
        $aula->capacidad = $request->capacidad;
        $aula->codigo = $request->codigo;  
        $aula->tipo = $request->tipo;  
        $aula->caracteristicas = $request->caracteristicas;
        $aula->nombreAula = $request->nombreAula; 
        $aula->ubicacion = $request->ubicacion;
        $aula->save();                                   //guarda cambios
        return response()->json([                        //JSON con los ambientes
            'Respuesta' => 'Actualizado correctamente'
        ], 202);                   
    }

    public function destroy($id)               //elimina un ambiente
    {
        Aula::findOrFail($id);                 //si no encuentra ambiente devuelve falso
        Aula::destroy($id);
        return response()->json([              //JSON con los ambientes
            'Respuesta' => 'Eliminado correctamente'
        ], 201);                               
    }
}
