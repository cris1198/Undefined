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
}
