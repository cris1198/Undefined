<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aula extends Model
{
    use HasFactory;
    public function users(){
        return $this->belongsTo(User::class,'id_users');
    }

    public function aulaVecinas(){
        return $this->hasMany(AulaVecina::class,'id');
    }
    public function horarios(){
        return $this->hasMany(Horario::class,'id');
    }
    protected $fillable = ['id','id_users','capacidad','codigo','tipo','caracteristicas','ubicacion','imagen'];
    protected $buscable = ['id','id_users','capacidad','codigo','tipo','caracteristicas','ubicacion','imagen'];

    public static function search($buscar='', $caracteristicas='', $tipo='', $rangeDown='', $rangeUp=''){

        if($buscar){
            if($caracteristicas){
                if($tipo){
                    if($rangeDown && $rangeUp){
                        $aulas = self::where('capacidad', '<=', $rangeUp)
                        ->where('capacidad', '>=', $rangeDown) 
                        ->where('caracteristicas', 'like', "%$caracteristicas%")
                        ->where('tipo', 'like', "%$tipo%")
                        ->where('codigo', 'like', "%$buscar%")
                        ->get();
                    }else{
                        $aulas = self::where('caracteristicas', 'like', "%$caracteristicas%")
                        ->where('tipo', 'like', "%$tipo%")
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
                        ->where('tipo', 'like', "%$tipo%")
                        ->where('codigo', 'like', "%$buscar%")
                        ->get();
                    }else{
                        $aulas = self::where('tipo', 'like', "%$tipo%")
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
                        ->where('tipo', 'like', "%$tipo%")
                        ->get();
                    }else{
                        $aulas = self::where('caracteristicas', 'like', "%$caracteristicas%")
                        ->where('tipo', 'like', "%$tipo%")
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
                        ->where('tipo', 'like', "%$tipo%")
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
                        $aulas = self::all();
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

    public static function recomendar($capacidad=''){
        
        $aulas = self::where('capacidad', '<=', $capacidad+50)
            ->where('capacidad', '>=', $capacidad-1) 
            ->take(5)
            ->get();
        return $aulas;
    }

    public static function recomendarContiguas($capacidad='', $tipo='', $ubicacion=''){
        
        $aulas = self::where('capacidad', '<=', $capacidad + 30)
            ->where('capacidad', '>=', $capacidad-1)
            ->where('tipo', 'like', $tipo)
            ->where('ubicacion', 'like', $ubicacion)
            ->take(10)
            ->get();
        return $aulas;
    }

}
