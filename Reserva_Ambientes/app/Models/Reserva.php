<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
class Reserva extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_users',
        'id_aulas',
        'codigo',
        'materia',
        'grupo',
        'motivo',
        'tipo',
        'observaciones',
        'cantidadEstudiantes',
        'fechaReserva',
        'periodo',
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
                ->orderBy('fechaReserva', 'DESC')
                ->get();
    }

    public static function Rechazados($userId=''){
        return self::where('id_users', 'like', $userId)
                ->where('aceptadoRechazado', 'like', 0)
                ->orderBy('fechaReserva', 'DESC')
                ->get();
    }

    public static function getByAulaId($aulaId='',$fecha='',$periodo=''){
        return self::where('id_aulas', 'like', $aulaId)
                ->where('fechaReserva', 'like', $fecha)
                ->where('periodo', 'like', $periodo)
                ->get();
    }
    
    public static function getHorario($aulaId='', $fecha=''){
        return self::where('id_aulas', 'like', $aulaId)
                ->where('fechaReserva', 'like', $fecha)
                ->where('aceptadoRechazado', 'like', 1)
                ->get();
    }

    public static function porReservar(){
        return self::whereNull('aceptadoRechazado')
                ->orderBy('fechaReserva', 'DESC')
                ->get();
    }

    public static function AcceptAndReject($userId=''){
        return self::where('id_users', 'like', $userId)
                ->where('aceptadoRechazado', 'like', 0)
                ->orWhere('aceptadoRechazado', 'like', 1)
                ->orderBy('fechaReserva', 'DESC')
                ->take(5)
                ->get();
    }
    
    
}
