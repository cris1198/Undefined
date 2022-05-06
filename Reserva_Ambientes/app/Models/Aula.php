<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aula extends Model
{
    use HasFactory;
    public function administrador(){
        return $this->belongsTo(Administrador::class,'id_administrador');
    }

    protected $fillable = ['id_administrador','capacidad','codigo','tipo','caracteristicas','ubicacion','imagen'];
    protected $buscable = ['id_administrador','capacidad','codigo','tipo','caracteristicas','ubicacion','imagen'];

    public static function search($buscar='', $caracteristicas='', $tipo='', $rangeDown='', $rangeUp=''){

        if($buscar){
            if($caracteristicas){
                if($tipo){
                    if($rangeDown && $rangeUp){
                        $aulas = self::where('capacidad', '<=', $rangeUp)
                        ->where('capacidad', '>=', $rangeDown) 
                        ->where('caracteristicas', 'like', "%$caracteristicas%")
                        ->Where('tipo', 'like', "%$tipo%")
                        ->where('codigo', 'like', "%$buscar%")
                        ->get();
                    }else{
                        $aulas = self::where('caracteristicas', 'like', "%$caracteristicas%")
                        ->Where('tipo', 'like', "%$tipo%")
                        ->where('codigo', 'like', "%$buscar%")
                        ->get();
                    }
                }else{
                    if($rangeDown && $rangeUp){
                        $aulas = self::where('capacidad', '<=', $rangeUp)
                        ->where('capacidad', '>=', $rangeDown) 
                        ->where('caracteristicas', 'like', "%$caracteristicas%")
                        ->where('codigo', 'like', "%$buscar%")
                        ->get();
                    }else{
                        $aulas = self::where('caracteristicas', 'like', "%$caracteristicas%")
                        ->where('codigo', 'like', "%$buscar%")
                        ->get();
                    }
                }
            }else{
                if($tipo){
                    if($rangeDown && $rangeUp){
                        $aulas = self::where('capacidad', '<=', $rangeUp)
                        ->where('capacidad', '>=', $rangeDown)
                        ->Where('tipo', 'like', "%$tipo%")
                        ->where('codigo', 'like', "%$buscar%")
                        ->get();
                    }else{
                        $aulas = self::Where('tipo', 'like', "%$tipo%")
                        ->where('codigo', 'like', "%$buscar%")
                        ->get();
                    }
                }else{
                    if($rangeDown && $rangeUp){
                        $aulas = self::where('capacidad', '<=', $rangeUp)
                        ->where('capacidad', '>=', $rangeDown)
                        ->where('codigo', 'like', "%$buscar%")
                        ->get();
                    }else{
                        $aulas = self::where('codigo', 'like', "%$buscar%")
                        ->get();
                    }
                }
            }
        }else{
            if($caracteristicas){
                if($tipo){
                    if($rangeDown && $rangeUp){
                        $aulas = self::where('capacidad', '<=', $rangeUp)
                        ->where('capacidad', '>=', $rangeDown) 
                        ->where('caracteristicas', 'like', "%$caracteristicas%")
                        ->Where('tipo', 'like', "%$tipo%")
                        ->get();
                    }else{
                        $aulas = self::where('caracteristicas', 'like', "%$caracteristicas%")
                        ->Where('tipo', 'like', "%$tipo%")
                        ->get();
                    }
                }else{
                    if($rangeDown && $rangeUp){
                        $aulas = self::where('capacidad', '<=', $rangeUp)
                        ->where('capacidad', '>=', $rangeDown) 
                        ->where('caracteristicas', 'like', "%$caracteristicas%")
                        ->get();
                    }else{
                        $aulas = self::where('caracteristicas', 'like', "%$caracteristicas%")
                        ->get();
                    }
                }
            }else{
                if($tipo){
                    if($rangeDown && $rangeUp){
                        $aulas = self::where('capacidad', '<=', $rangeUp)
                        ->where('capacidad', '>=', $rangeDown)
                        ->Where('tipo', 'like', "%$tipo%")
                        ->get();
                    }else{
                        $aulas = self::where('tipo', 'like', "%$tipo%")
                        ->get();
                    }
                }else{
                    if($rangeDown && $rangeUp){
                        $aulas = self::where('capacidad', '<=', $rangeUp)
                        ->where('capacidad', '>=', $rangeDown)
                        ->get();
                    }else{
                        return response()->json([               
                            'Respuesta' => 0
                        ], 505); ;
                    }
                }
            }
        }
        
        if($aulas == '[]'){
            return response()->json([               
                    'Respuesta' => 0
                ], 505);
        }else{
            return $aulas;
        }
    }

}
