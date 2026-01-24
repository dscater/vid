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

    protected $appends = ["url_imagen", "imagen64", "txt_imagen"];

    public function getImagen64Attribute()
    {
        $path = public_path("imgs/productos/" . $this->imagen);
        if (!$this->imagen || !file_exists($path)) {
            $path = public_path("imgs/productos/default.png");
        }
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        return $base64;
    }
    public function getTxtImagenAttribute()
    {
        if ($this->imagen) {
            return $this->imagen;
        }
        return "";
    }

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

    public function sucursal_productos()
    {
        return $this->hasMany(SucursalProducto::class, 'producto_id');
    }
}
