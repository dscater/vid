<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransferenciaDetalle extends Model
{
    use HasFactory;

    protected $fillable = [
        "transferencia_id",
        "producto_id",
        "cantidad",
        "cantidad_fisica",
        "costo",
        "subtotal",
        "verificado",
        "sucursal_ajuste",
        "motivo",
    ];


    public function transferencia()
    {
        return $this->belongsTo(Transferencia::class);
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
