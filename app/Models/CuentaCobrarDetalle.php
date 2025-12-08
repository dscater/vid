<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CuentaCobrarDetalle extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "cuenta_cobrar_id",
        "cancelado",
        "fecha",
        "hora",
    ];

    protected $appends = ["fecha_c"];

    public function getFechaCAttribute()
    {
        return date("d/m/Y H:i", strtotime($this->fecha . ' ' . $this->hora));
    }


    public function cuenta_cobrar()
    {
        return $this->belongsTo(CuentaCobrar::class);
    }
}
