<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DevolucionClienteDetalle extends Model
{
    use HasFactory;

    protected $fillable = [
        "devolucion_cliente_id",
        "producto_id",
        "cantidad",
        "costo",
        "subtotal",
    ];

    public function devolucion_cliente()
    {
        return $this->belongsTo(DevolucionCliente::class);
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
