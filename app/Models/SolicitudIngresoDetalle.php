<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudIngresoDetalle extends Model
{
    use HasFactory;
    protected $fillable = [
        "solicitud_ingreso_id",
        "producto_id",
        "cantidad",
        "cantidad_fisica",
        "costo",
        "costo_facturado",
        "pc_facturado",
        "calculado",
        "subtotal",
        "verificado",
        "sucursal_ajuste",
        "motivo",
    ];

    public function solicitud_ingreso()
    {
        return $this->belongsTo(SolicitudIngreso::class);
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    public function oSucursalAjuste()
    {
        return $this->belongsTo(Sucursal::class, 'sucursal_ajuste');
    }
}
