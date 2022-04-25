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
            // $array1 = array(
            //     "capacidad" => 0,
            // );
            // $myJSON = json_encode($array1);
            // return $myJSON; 
            return response()->json([   
                'capacidad' => 0
             ], 500);
        }else{
            if( !$codigoCorrecto){
                // $array1 = array(
                //     "codigo" => 0,
                // );
                // $myJSON = json_encode($array1);
                // return $myJSON; 
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
    private function capacidadCorrecto($capacidad){  // 1 si hay puro numeros,  0 si hay signos o letras
        $capacidadCorrecta =is_numeric($capacidad)+0; 
        return $capacidadCorrecta;
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
