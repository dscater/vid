<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notificacion extends Model
{
    use HasFactory;

    protected $fillable = [
        "descripcion",
        "modulo",
        "modulo_id",
        "fecha",
        "hora"
    ];

    protected $appends = ["hace", "fecha_t", "fecha_c"];

    public function getFechaCAttribute()
    {
        return date("d/m/Y H:i", strtotime($this->fecha . ' ' . $this->hora));
    }


    public function getFechaTAttribute()
    {
        return date("d/m/Y", strtotime($this->fecha));
    }
    public function getHaceAttribute()
    {
        return $this->created_at->diffForHumans();
    }
}
