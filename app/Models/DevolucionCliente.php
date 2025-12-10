<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DevolucionCliente extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "sucursal_id",
        "cliente_id",
        "cantidad_total",
        "total",
        "fecha",
        "hora",
        "observaciones",
        "user_id",
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

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, "user_id");
    }

    public function devolucion_cliente_detalles()
    {
        return $this->hasMany(DevolucionClienteDetalle::class, 'devolucion_cliente_id');
    }
}
