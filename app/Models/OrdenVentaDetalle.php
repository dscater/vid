<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrdenVentaDetalle extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "orden_venta_id",
        "producto_id",
        "unidad_medida_id",
        "cantidad",
        "precio",
        "subtotal",
        "descuento",
        "subtotal_f",
    ];

    public function orden_venta()
    {
        return $this->belongsTo(OrdenVenta::class);
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    public function unidad_medida()
    {
        return $this->belongsTo(UnidadMedida::class);
    }
}
