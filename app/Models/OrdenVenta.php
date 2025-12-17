<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use App\library\numero_a_letras\src\NumeroALetras;

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
        "total_st",
        "solicitud_descuento",
        "solicitud_sw",
        "user_ap",
        "monto_solicitud",
        "descuento",
        "total_f",
        "con",
        "cancelado_c",
        "qr",
        "cancelado_qr",
        "cre",
        "credito",
        "cancelado",
        "forma_pago",
        "cambio",
        "cs_f",
        "observaciones",
        "estado", // para controlar:PENDIENTE, APROBADO, FINALIZADO, RECHAZADO
        "verificado", // 0:PENDIENTE, 1: APROBADO, 2: FINALIZADO, 3: RECHAZADO
        "user_id",
    ];

    protected $appends = ["fecha_t", "hora_t", "fecha_c", "fecha_ct"];

    public function getLiteralTxtAttribute()
    {
        $convertir = new NumeroALetras();
        $array_monto = explode('.', $this->total_f);
        $literal = $convertir->convertir($array_monto[0]);
        $literal .= " " . $array_monto[1];
        $literal = strtolower($literal);
        $literal = ucfirst($literal) . "/100." . " Bolivianos";

        return $literal;
    }

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

    public function user_aprobo()
    {
        return $this->belongsTo(User::class, "user_ap");
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

    public function cuenta_cobrar()
    {
        return $this->hasOne(CuentaCobrar::class);
    }
}
