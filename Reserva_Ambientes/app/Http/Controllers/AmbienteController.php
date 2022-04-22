<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Aula;

class AmbienteController extends Controller
{
    public function index(Request $request)
    {
        //
    }

    public function getById(Request $request)
    {
        $id = $request->input('id_aula');
        $ambiente = Aula::findOrFail($id);
        return $ambiente; 
    }

    public function search(Request $request)   //busca codigo, nombreAula o ubicacion
    {
        $buscar = $request->input('buscar');   //busca variables de el url buscar=palabra_buscada
        $ambientes = Aula::search($buscar);    //devuelve coleccion de Aulas
        return $ambientes;                     //devuelve JSON con datos de Aula
    }

    public function filter(Request $request)   //busca tipo o caracteristicas
    {
        $buscar = $request->input('filtro');   //busca variables de el url filtro=palabra_filtrada
        $ambientes = Aula::filter($buscar);    //devuelve coleccion de Aulas
        return $ambientes;                     //devuelve JSON con datos de Aula
    }

    public function rangeFilter(Request $request)              //busca un rango de capacidad 
    {
        $rangeDown = $request->input('rango_Bajo');            //busca variables de el url rango_Alto=Numero_Bajo
        $rangeUp = $request->input('rango_Alto');              //busca variables de el url rango_Alto=Numero_Alto   
        $ambientes = Aula::rangeFilter($rangeUp, $rangeDown);  //devuelve coleccion de Aulas en el rango de capacidad
        return $ambientes;                                     //devuelve JSON con datos de Aula
    }
}
