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

    public function show(Request $request)
    {
        $id = $request->input('id_aula');
        $ambiente = Aula::findOrFail($id);
        return $ambiente->nombreAula; 
    }

    public function search(Request $request)
    {
        $buscar = $request->input('buscar');
        $ambiente = Aula::search($buscar);
        return $ambiente->nombreAula; 
    }
}
