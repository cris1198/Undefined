<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_aulas',
        'periodo',
        'fecha'
        ];
    public function aulas(){
        return $this->belongsTo(Aula::class,'id_aulas');
    }
}
