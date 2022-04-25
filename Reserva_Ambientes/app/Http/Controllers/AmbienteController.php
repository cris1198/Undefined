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
        $capacidadCorrecto = AmbienteController::capacidadCorrecto($request->capacidad);
        $codigoCorrecto = AmbienteController::codigoCorrecto($request->codigo);
        if(!$capacidadCorrecto){
            return response()->json([   
                'capacidad' => 0
             ], 500);
        }else{
            if( !$codigoCorrecto){
                return response()->json([   
                    'codigo' => 0
                 ], 500);
            }else{
                $new_classroom = new Aula($request->all());
                $path = $request->imagen->store('/public/aulas'); //aqui se saca la direccion y se guarda la imagen en la carpeta public aulas
                $new_classroom->imagen= $path; // en la base de datos se guarda la direccion referenciando a la imagen
                $new_classroom->save();

        
                return response()->json([   
                    'Respuesta' => 'Agregado Correctamente'
                 ], 202);  
            }
        }
        
    }
    private function codigoCorrecto($codigo){ //0 si el codgio contiene caracteres no alfanuericos, 1 correcto
        $codigoCorrecto =true;
        $i = 0 ;
        while($i < strlen($codigo) && $codigoCorrecto){
            $car  = $codigo[$i];
            if(!((ord($car)>= 48 && ord($car) <= 57) || (ord($car)>= 65 && ord($car)<=90) 
                || (ord($car)>= 97 && ord($car)<=122) || ord($car)==35)){
                $codigoCorrecto = 0;
            }
            $i++;
        }
        return $codigoCorrecto;
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
        $aula->imagen = $request->imagen;
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
    
    private function capacidadCorrecto($capacidad){  // 1 si hay puro numeros,  0 si hay signos o letras
        $capacidadCorrecta =is_numeric($capacidad)+0; 
        return $capacidadCorrecta;
    }
    
    public function getById($id)               //retorna un Ambiente por el ID
    {
        $ambiente = Aula::findOrFail($id);     //si no encuentra ambiente devuelve falso
        return $ambiente;                      //JSON con los ambientes
    }
}
