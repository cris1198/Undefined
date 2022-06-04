<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_users',
        'id_materias',
        'nombreGrupo',
        'cantidad'
        ];

    public function users(){
        return $this->belongsTo(User::class,'id_users');
    }
    public function materias(){
        return $this->belongsTo(Materia::class,'id_materias');
    }

    public static function searchByUserId($userId=''){
        return self::where('id_users', 'like', $userId)
                ->get();
    }

    public static function gruposWithoutUser(){
        return self::whereNull('id_users')
                ->get();
    }
    public function reservas(){
        return $this->hasMany(Reserva::class,'id');
    }
}
