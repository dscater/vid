<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
