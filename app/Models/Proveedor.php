<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Proveedor extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "razon_social",
        "nombre_com",
        "nit",
        "moneda",
        "fono_emp",
        "correo",
        "dir",
        "ciudad",
        "tipo",
        "estado",
        "observaciones",
        "categorias",
        "marcas",
        "contactos"
    ];

    protected $appends = [
        "estado_t"
    ];

    public function getEstadoTAttribute()
    {
        return $this->estado == 1 ? 'HABILITADO' : 'DESHABILITADO';
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'marcas' => 'array',
            'categorias' => 'array',
            "contactos" => "array",
        ];
    }
}
