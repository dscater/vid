<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProformaDetalleProducto extends Model
{
    use HasFactory;

    protected $fillable = [
        "proforma_id",
        "proforma_detalle_id",
        "proforma_producto_id",
        "producto_id",
        "unidad_medida_id",
        "cantidad",
        "cantidad_entregada",
        "resta",
        "precio",
        "subtotal",
        "verificado",
    ];

    public function proforma()
    {
        return $this->belongsTo(Proforma::class);
    }

    public function proforma_detalle()
    {
        return $this->belongsTo(ProformaDetalle::class);
    }

    public function proforma_producto()
    {
        return $this->belongsTo(ProformaProducto::class);
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
