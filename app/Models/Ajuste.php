<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ajuste extends Model
{
    use HasFactory;

    protected $fillable = [
        "sucursal_id",
        "sucursal_origen",
        "producto_id",
        "cantidad",
        "motivo",
        "estado",
        "fecha",
    ];

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class, 'sucursal_id');
    }


    public function oSucursalOrigen()
    {
        return $this->belongsTo(Sucursal::class, 'sucursal_origen');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }
}
