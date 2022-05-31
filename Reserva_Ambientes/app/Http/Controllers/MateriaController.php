<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Materia;

class MateriaController extends Controller
{
    public function getById($id){
        $materia = Materia::findOrFail($id);     //si no encuentra ambiente devuelve falso
        return $materia;  
    }
}
