<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\library\numero_a_letras\src\NumeroALetras;

class ProformaDetalle extends Model
{
    use HasFactory;

    protected $fillable  = [
        "proforma_id",
        "cliente_id",
        "cantidad",
        "cantidad_entregada",
        "total",
        "saldo",
        "estado", // PENDIENTE, ATENDIDO , PARCIALMENTE ATENDIDO
        "verificado"
    ];

    protected $appends = ["literal"];


    public function getLiteralAttribute()
    {
        $convertir = new NumeroALetras();
        $array_monto = explode('.', $this->total);
        $literal = $convertir->convertir($array_monto[0]);
        $literal .= " " . $array_monto[1];
        $literal = strtolower($literal);
        $literal = ucfirst($literal) . "/100." . " Bolivianos";

        return $literal;
    }

    public function proforma()
    {
        return $this->belongsTo(Proforma::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
    public function proforma_detalle_productos()
    {
        return $this->hasMany(ProformaDetalleProducto::class);
    }
}
