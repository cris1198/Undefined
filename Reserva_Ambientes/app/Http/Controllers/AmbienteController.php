<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ambiente;

class AmbienteController extends Controller
{
    public function index(Request $request)
    {
        return Ambiente::search($request->buscar)
    }
}
