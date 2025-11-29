<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comunicado extends Model
{
    use HasFactory;

    protected $fillable = [
        "unidad",
        "descripcion",
        "imagen",
    ];

    protected $appends = ["url_imagen"];

    public function getUrlImagenAttribute()
    {
        return asset("imgs/comunicados/" . $this->imagen);
    }
}
