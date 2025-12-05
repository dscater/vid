<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SucursalProducto extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "sucursal_id",
        "producto_id",
        "cantidad_ideal",
        "cantidad_minima",
        "stock_actual",
    ];

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class);
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
