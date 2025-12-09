<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Proforma extends Model
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
        "total_st",
        "solicitud_descuento",
        "descuento",
        "total_f",
        "forma_pago",
        // "cancelado",
        // "cambio",
        "cs_f",
        "observaciones",
        "user_id",
    ];

    protected $appends = ["fecha_t", "hora_t", "fecha_c", "fecha_ct"];


    public function getFechaCtAttribute()
    {
        $dt = Carbon::parse($this->fecha . ' ' . $this->hora);
        return $dt->translatedFormat('d \d\e F \d\e Y');
    }

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

    public function proforma_detalles()
    {
        return $this->hasMany(ProformaDetalle::class, 'proforma_id');
    }
}
