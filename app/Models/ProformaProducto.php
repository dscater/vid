<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProformaProducto extends Model
{
    use HasFactory;

    protected $fillable = [
        "proforma_id",
        "producto_id",
        "precio",
        "unidad_medida_id",
        "stock_actual",
    ];

    protected $appends = ["stock_actual_aux"];

    public function getStockActualAuxAttribute()
    {
        return $this->stock_actual;
    }

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

    public function proforma_detalle_productos()
    {
        return $this->hasMany(ProformaDetalleProducto::class, 'proforma_producto_id');
    }
}
