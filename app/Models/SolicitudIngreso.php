<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SolicitudIngreso extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "nro",
        "codigo",
        "proveedor_id",
        "fecha_ingreso",
        "hora_ingreso",
        "fecha_sis",
        "hora_sis",
        "cs_f",
        "tipo_cambio",
        "gastos",
        "observaciones",
        "descripcion",
        "cantidad_total",
        "total",
        "estado",
        "user_id",
    ];

    protected $appends = ["fecha_ingreso_t", "hora_ingreso_t", "fecha_c"];


    public function getFechaCAttribute()
    {
        return date("d/m/Y H:i", strtotime($this->fecha_ingreso . ' ' . $this->hora_ingreso));
    }

    public function getFechaIngresoTAttribute()
    {
        return date("d/m/Y", strtotime($this->fecha_ingreso));
    }

    public function getHoraIngresoTAttribute()
    {
        return date("H:i", strtotime($this->hora_ingreso));
    }

    public function solicitud_ingreso_detalles()
    {
        return $this->hasMany(SolicitudIngresoDetalle::class, '');
    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
