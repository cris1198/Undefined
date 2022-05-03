<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AulaVecina extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_aulas',
        'vecino1',
        'vecino2',
        'vecino3'
        ];
    public function users(){
        return $this->belongsTo(Aula::class,'id_aulas');
    }
}
