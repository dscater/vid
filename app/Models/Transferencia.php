<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transferencia extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "nro",
        "codigo",
        "sucursal_id",
        "sucursal_destino",
        "user_sol",
        "user_ap",
        "cantidad_total",
        "cantidad_total_v",
        "fecha",
        "hora",
        "observaciones",
        "estado",
        "verificado",
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

    public function sucursalDestino()
    {
        return $this->belongsTo(Sucursal::class, "sucursal_destino");
    }

    public function user_solicitante()
    {
        return $this->belongsTo(User::class, "user_sol");
    }

    public function user_aprobo()
    {
        return $this->belongsTo(User::class, "user_ap");
    }

    public function transferencia_detalles()
    {
        return $this->hasMany(TransferenciaDetalle::class, 'transferencia_id');
    }
}
