<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProformaDetalle extends Model
{
    use HasFactory;

    protected $fillable  = [
        "proforma_id",
        "producto_id",
        "unidad_medida_id",
        "cantidad",
        "precio",
        "subtotal",
        "descuento",
        "subtotal_f",
    ];


    public function proforma()
    {
        return $this->belongsTo(Proforma::class);
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    public function unidad_medida()
    {
        return $this->belongsTo(UnidadMedida::class);
    }
}
