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
        $variablequenotieneimportancia=0;
    }
    public function getById($id)               //retorna un Ambiente por el ID
    {
        $ambiente = Aula::findOrFail($id);     //si no encuentra ambiente devuelve falso
        return $ambiente;                      //JSON con los ambientes
    }

    public function search(Request $request)   //busca codigo, nombreAula o ubicacion
    {
        $buscar = $request->input('buscar');   //busca variables de el url buscar=palabra_buscada
        $ambientes = Aula::search($buscar);    //devuelve coleccion de Aulas
        return $ambientes;                     //devuelve JSON con datos de Aula
    }

    public function filter(Request $request)    //busca tipo o caracteristicas
    {
        $filtrar = $request->input('filtro');   //busca variables de el url filtro=palabra_filtrada
        $ambientes = Aula::filter($filtrar);    //devuelve coleccion de Aulas
        return $ambientes;                      //devuelve JSON con datos de Aula
    }

    public function rangeFilter(Request $request)              //busca un rango de capacidad 
    {
        $rangeDown = $request->input('rango_Bajo');            //busca variables de el url rango_Alto=Numero_Bajo
        $rangeUp = $request->input('rango_Alto');              //busca variables de el url rango_Alto=Numero_Alto   
        $ambientes = Aula::rangeFilter($rangeUp, $rangeDown);  //devuelve coleccion de Aulas en el rango de capacidad
        return $ambientes;                                     //devuelve JSON con datos de Aula
    }

    public function update($id)               //actualiza los datos de un ambiente
    {
        Aula::findOrFail($id);                //si no encuentra ambiente devuelve falso
        $ambientes = Aula::all();
        return response()->json([              //JSON con los ambientes
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
