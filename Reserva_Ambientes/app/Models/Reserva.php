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
        $fecha = date("y-m-d");
        return self::where('id_users', 'like', $userId)
                ->where('aceptadoRechazado', 'like', 1)
                ->where('fechaReserva', '>=', "20$fecha")
                ->whereNull('razon')
                ->orderBy('fechaReserva', 'DESC')
                ->get();
    }

    public static function AceptadosContiguas($userId=''){
        $fecha = date("y-m-d");
        return self::where('id_users', 'like', $userId)
                ->where('aceptadoRechazado', 'like', 1)
                ->where('fechaReserva', '>=', "20$fecha")
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

    public static function numAceptados(){
        return self::where('aceptadoRechazado', 'like', 1)
                ->whereNull('razon')
                ->count();
    }

    public static function numAceptadosContiguas(){
        return self::where('aceptadoRechazado', 'like', 1)
                ->whereNotNull('razon')
                ->count();
    }

    public static function numRechazados(){
        return self::where('aceptadoRechazado', 'like', 0)
                ->count();
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
        $fecha = date("y-m-d");
        return self::where('fechaReserva', '>=', "20$fecha")
                ->whereNull('aceptadoRechazado')
                ->orderBy('fechaReserva', 'DESC')
                ->get();
    }

    public static function AcceptAndReject($userId=''){
        return self::where('id_users', 'like', $userId)
                ->whereNotNull('aceptadoRechazado')
                ->orderBy('created_at', 'ASC')
                ->take(5)
                ->get();
    }

    public static function AllAcceptAndReject(){
        return self::where('razon', '!=', 'Aula Contigua')
                ->orWhereNull('razon')
                ->whereNotNull('aceptadoRechazado')
                ->orderBy('created_at', 'ASC')
                ->get();
    }
    
    public static function AllAcceptAndRejectCont(){
        return self::whereNotNull('aceptadoRechazado')
                ->orderBy('created_at', 'ASC')
                ->get();
    }
    
    public static function getPrimeros(){
        $fecha = date("y-m-d");
        return self::where('fechaReserva', '>=', "20$fecha")
                ->orderBy('created_at', 'ASC')
                ->whereNull('aceptadoRechazado')
                ->whereNull('id_aulas')
                ->get();
    }

    public static function getUltimos(){
        $fecha = date("y-m-d");
        return self::where('fechaReserva', '>=', "20$fecha")
                ->orderBy('created_at', 'DESC')
                ->whereNull('aceptadoRechazado')
                ->whereNull('id_aulas')
                ->get();
    }

    public static function getUrgencia(){
        $fecha = date("y-m-d");
        return self::where('tipo', 'like', 'Examen')
                ->where('fechaReserva', '>=', "20$fecha")
                ->orderBy('fechaReserva', 'ASC')
                ->whereNull('aceptadoRechazado')
                ->whereNull('id_aulas')
                ->get();
    }
    
    public static function getByFechaPeriodo1($fecha='',$periodo='', $periodo2=''){
        return self::where('aceptadoRechazado', 'like', 1)
                ->where('fechaReserva', '==', $fecha)
                ->where('periodo', 'like', $periodo) 
                ->where('periodo', 'like', $periodo2)
                ->get();
    }
    public static function getByFechaPeriodo2($fecha='',$periodo=''){
        return self::where('fechaReserva', 'like', $fecha)
                ->where('periodo', 'like', $periodo)
                ->where('aceptadoRechazado', 'like', 1)
                ->get();
    }
}
