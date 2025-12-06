<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DevolucionStock extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "nro",
        "codigo",
        "sucursal_id",
        "cantidad_total",
        "total",
        "cantidad_total_v",
        "total_v",
        "fecha",
        "hora",
        "observaciones",
        "estado",
        "user_id",
        "verificado",
    ];

    protected $appends = ["fecha_t", "hora_t", "fecha_c"];

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

    public function devolucion_stock_detalles()
    {
        return $this->hasMany(DevolucionStockDetalle::class, 'devolucion_stock_id');
    }
}
