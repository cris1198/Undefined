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
    protected $buscable = ['capacidad','codigo','tipo','caracteristicas','nombreAula','ubicacion'];

    public static function search($buscar='', $caracteristicas='', $tipo='', $rangeDown='', $rangeUp=''){

        return self::where('capacidad', '<=', $rangeUp)
        ->where('capacidad', '>=', $rangeDown) 
        ->where('caracteristicas', 'like', "%$caracteristicas%")
        ->Where('tipo', 'like', "%$tipo%")
        ->where('codigo', 'like', "%$buscar%")
        ->orWhere('nombreAula', 'like', "%$buscar%")
        ->orWhere('ubicacion', 'like', "%$buscar%")
        ->get();
    }

}
