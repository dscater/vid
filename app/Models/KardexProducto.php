<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KardexProducto extends Model
{
    use HasFactory;

    protected $fillable  = [
        "sucursal_id",
        "tipo_registro",
        "registro_id",
        "modulo",
        "producto_id",
        "detalle",
        "precio",
        "tipo_is",
        "cantidad_ingreso",
        "cantidad_salida",
        "cantidad_saldo",
        "cu",
        "monto_ingreso",
        "monto_salida",
        "monto_saldo",
        "fecha",
        "status",
        "user_id",
    ];

    protected $appends = ["fecha_hora"];

    public function getFechaHoraAttribute()
    {
        return date("d/m/Y H:i", strtotime($this->created_at));
    }

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class, 'sucursal_id');
    }

    public function producto()
    {
        return $this->belongsTo(Sucursal::class, 'producto_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
