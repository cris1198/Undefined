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
}
