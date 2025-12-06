<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrdenVenta extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        "nro",
        "codigo",
        "sucursal_id",
        "cliente_id",
        "fecha",
        "hora",
        "cantidad_total",
        "total",
        "solicitud_descuento",
        "solicitud_sw",
        "monto_solicitud",
        "descuento",
        "total_f",
        "forma_pago",
        "cancelado",
        "cambio",
        "cs_f",
        "observaciones",
        "user_id",
    ];

    protected $appends = ["fecha_t", "hora_t", "fecha_c"];

    public function getFechaCAttribute()
    {
        return date("d/m/Y H:i", strtotime($this->fecha . ' ' . $this->hora));
    }

    public function getFechaTAttribute()
    {
        return date("d/m/Y", strtotime($this->fecha));
    }

    public function getHoraTAttribute()
    {
        return date("H:i", strtotime($this->hora));
    }

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, "user_id");
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, "cliente_id");
    }

    public function orden_venta_detalles()
    {
        return $this->hasMany(OrdenVentaDetalle::class, 'orden_venta_id');
    }
}
