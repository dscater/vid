<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CuentaCobrar extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "cliente_id",
        "orden_venta_id",
        "total",
        "cancelado",
        "saldo",
        "fecha",
        "hora",
    ];

    protected $appends = ["fecha_c"];

    public function getFechaCAttribute()
    {
        return date("d/m/Y H:i", strtotime($this->fecha . ' ' . $this->hora));
    }


    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function orden_venta()
    {
        return $this->belongsTo(OrdenVenta::class);
    }

    public function cuenta_cobrar_detalles()
    {
        return $this->hasMany(CuentaCobrarDetalle::class);
    }
}
