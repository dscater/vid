<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DevolucionStockDetalle extends Model
{
    use HasFactory;

    protected $fillable = [
        "devolucion_stock_id",
        "producto_id",
        "cantidad",
        "cantidad_fisica",
        "costo",
        "subtotal",
        "verificado",
        "sucursal_ajuste",
        "motivo",
    ];

    public function devolucion_stock()
    {
        return $this->belongsTo(DevolucionStock::class);
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
