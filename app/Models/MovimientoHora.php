<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovimientoHora extends Model
{
    use HasFactory;

    protected $fillable = [
        "sucursal_id",
        "producto_id",
        "fecha",
        "cantidad_inicial",
        "hora_inicial",
        "cantidad_final",
        "hora_final",
    ];

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class);
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
