<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cliente extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "razon_social",
        "tipo",
        "nit",
        "nombre_punto",
        "nombre_prop",
        "ci_prop",
        "correo",
        "cel",
        "fono",
        "dir",
        "latitud",
        "longitud",
        "ciudad",
        "contactos",
        "estado",
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
            'contactos' => 'array',
        ];
    }
}
