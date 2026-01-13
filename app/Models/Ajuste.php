<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Ajuste extends Model
{
    use HasFactory;

    protected $fillable = [
        "sucursal_id",
        "sucursal_origen",
        "producto_id",
        "cantidad",
        "motivo",
        "estado",
        "tipo",
        "registro_id",
        "fecha",
    ];

    protected $appends = ["nom_sucursal", "sucursal_nom"];
    public function getSucursalNomAttribute()
    {
        if ($this->tipo == 'DEVOLUCION DE STOCK') {
            return $this->oSucursalOrigen->nombre;
        }

        if ($this->tipo == 'SOLICITUD DE INGRESO') {
            $solicitud_ingreso_detalle = SolicitudIngresoDetalle::find($this->registro_id);
            $solicitud_ingreso = $solicitud_ingreso_detalle->solicitud_ingreso;
            // Log::debug($solicitud_ingreso->proveedor);
            return $solicitud_ingreso->proveedor->razon_social;
        }

        return "";
    }

    public function getNomSucursalAttribute()
    {
        if ($this->tipo == 'DEVOLUCION DE STOCK' || $this->tipo == 'SOLICITUD DE INGRESO') {
            $almacen = Sucursal::where("almacen", 1)->get()->first();
            if (!$almacen) {
                return "-";
            }
            return $almacen->nombre;
        }
        return $this->oSucursalOrigen->nombre;
    }

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class, 'sucursal_id');
    }


    public function oSucursalOrigen()
    {
        return $this->belongsTo(Sucursal::class, 'sucursal_origen');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }
}
