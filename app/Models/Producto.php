<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Producto extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "codigo",
        "nombre",
        "unidades_caja",
        "descripcion",
        "categoria_id",
        "marca_id",
        "precio",
        "precio_ppp",
        "ppp",
        "unidad_medida_id",
        "estado",
        "imagen",
    ];

    protected $appends = ["url_imagen"];

    public function getUrlImagenAttribute()
    {
        if ($this->imagen) {
            return asset("imgs/productos/" . $this->imagen);
        }
        return asset("imgs/productos/default.png");
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }
    public function marca()
    {
        return $this->belongsTo(Marca::class);
    }
    public function unidad_medida()
    {
        return $this->belongsTo(UnidadMedida::class);
    }
}
