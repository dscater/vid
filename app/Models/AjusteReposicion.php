<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AjusteReposicion extends Model
{
    use HasFactory;

    protected $fillable = [
        "ajuste_id",
        "sucursal_id",
        "producto_id",
        "cantidad",
        "fecha",
    ];

    public function ajuste()
    {
        return $this->belongsTo(Ajuste::class, 'ajuste_id');
    }

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class, 'sucursal_id');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }
}
