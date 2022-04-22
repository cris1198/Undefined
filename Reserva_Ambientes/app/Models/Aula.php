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

    protected $buscable = ['capacidad','codigo','tipo','caracteristicas','nombreAula','ubicacion'];

    public static function search($query=''){
        if(!$query){
            return self::all();
        }
        return self::where('codigo', 'like', "%$query%")
        ->orWhere('nombreAula', 'like', "%$query%")
        ->orWhere('ubicacion', 'like', "%$query%")
        ->get();
    }

    public static function filter($query=''){
        if(!$query){
            return self::all();
        }
        return self::where('caracteristicas', 'like', "%$query%")
        ->orWhere('tipo', 'like', "%$query%")
        ->get();
    }

    public static function rangeFilter($rangeUp='', $rangeDown=''){

        if(!($rangeUp && $rangeDown) ){
            return self::all();
        }
        
        return self::where('capacidad', '<=', $rangeUp)
        ->where('capacidad', '>=', $rangeDown)
        ->get();
    }
}
