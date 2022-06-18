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
        'id_grupos',
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
        'created_at',
        ];

    public function users(){
        return $this->belongsTo(User::class,'id_users');
    }
    public function grupo(){
        return $this->belongsTo(Grupo::class,'id_grupos');
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
                ->whereNull('razon')
                ->orderBy('fechaReserva', 'DESC')
                ->get();
    }

    public static function AceptadosContiguas($userId=''){
        return self::where('id_users', 'like', $userId)
                ->where('aceptadoRechazado', 'like', 1)
                ->whereNotNull('razon')
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
                ->whereNotNull('aceptadoRechazado')
                ->orderBy('created_at', 'DESC')
                ->take(5)
                ->get();
    }
    
    public static function getPrimeros(){
        return self::orderBy('created_at', 'ASC')
                ->whereNull('aceptadoRechazado')
                ->whereNull('id_aulas')
                ->get();
    }

    public static function getUltimos(){
        return self::orderBy('created_at', 'DESC')
                ->whereNull('aceptadoRechazado')
                ->whereNull('id_aulas')
                ->get();
    }

    public static function getUrgencia(){
        return self::where('tipo', 'like', 'Examen')
                ->orderBy('fechaReserva', 'ASC')
                ->whereNull('aceptadoRechazado')
                ->whereNull('id_aulas')
                ->get();
    }
    
    public static function getByFechaPeriodo1($fecha='',$periodo='', $periodo2=''){
        return self::where('fechaReserva', 'like', $fecha)
                ->where('periodo', 'like', $periodo)
                ->orWhere('periodo', 'like', $periodo2)
                ->where('aceptadoRechazado', 'like', 1)
                ->get();
    }
    public static function getByFechaPeriodo2($fecha='',$periodo=''){
        return self::where('fechaReserva', 'like', $fecha)
                ->where('periodo', 'like', $periodo)
                ->where('aceptadoRechazado', 'like', 1)
                ->get();
    }
}
