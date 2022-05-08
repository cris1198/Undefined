<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_users',
        'id_aulas',
        'codigo',
        'materia',
        'grupo',
        'cantidadEstudiantes',
        'fechaReserva',
        'horaInicio',
        'horaFin',
        'cantidadPeriodo',
        'aceptadoRechazado',
        'razon',
        ];

    public function users(){
        return $this->belongsTo(User::class,'id_users');
    }
    public function aulas(){
        return $this->belongsTo(Aula::class,'id_aulas');
    }

    public static function searchByUserId($userId=''){
        return self::where('id_users', 'like', $userId)
                ->get();
    }

    public static function Aceptados($userId=''){
        return self::where('id_users', 'like', $userId)
                ->where('aceptadoRechazado', 'like', 1)
                ->ordeBy('fechaReserva')
                ->get();
    }

    public static function Rechazados($userId=''){
        return self::where('id_users', 'like', $userId)
                ->where('aceptadoRechazado', 'like', 0)
                ->ordeBy('fechaReserva')
                ->get();
    }
}
