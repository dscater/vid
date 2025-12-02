<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sucursal extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "nombre",
        "direccion",
        "fono",
        "correo",
        "user_id",
        "estado",
    ];

    protected $appends = [
        "estado_t"
    ];

    public function getEstadoTAttribute()
    {
        return $this->estado == 1 ? 'HABILITADO' : 'DESHABILITADO';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
