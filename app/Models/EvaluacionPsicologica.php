<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluacionPsicologica extends Model
{
    use HasFactory;

    protected $fillable = [
        "postulante_id",
        "valoracion",
        "nro_baucher",
        "nro_folder",
    ];

    public function postulante()
    {
        return $this->belongsTo(Postulante::class, 'postulante_id');
    }
}
