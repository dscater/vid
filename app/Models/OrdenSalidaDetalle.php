<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrdenSalidaDetalle extends Model
{
    use HasFactory;

    protected $fillable = [
        "orden_salida_id",
        "producto_id",
        "cantidad",
        "cantidad_fisica",
        "costo",
        "subtotal",
        "verificado",
        "sucursal_ajuste",
        "motivo",
    ];

    public function orden_salida()
    {
        return $this->belongsTo(OrdenSalida::class);
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
