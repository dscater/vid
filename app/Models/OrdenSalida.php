<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrdenSalida extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "nro",
        "codigo",
        "sucursal_id",
        "user_sol",
        "user_ap",
        "fecha",
        "hora",
        "observaciones",
        "cantidad_total",
        "total",
        "estado",
        "verificado",
        "user_id"
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

    public function user_solicitante()
    {
        return $this->belongsTo(User::class, "user_sol");
    }

    public function user_aprobador()
    {
        return $this->belongsTo(User::class, "user_ap");
    }

    public function user()
    {
        return $this->belongsTo(User::class, "user_id");
    }

    public function orden_salida_detalles()
    {
        return $this->hasMany(OrdenSalidaDetalle::class, 'orden_salida_id');
    }
}
