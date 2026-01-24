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
        "ubicacion",
        "ciudad",
        "ranking",
        "categoria",
        "score",
        "factor",
        "total_credito",
        "contactos",
        "estado",
        "credito",
    ];

    protected $appends = [
        "estado_t",
        "ventas_65"
    ];

    public function getVentas65Attribute()
    {
        return $this->orden_ventas()->where('created_at', '>=', now()->subDays(65))
            ->where("verificado", 2)->sum('total_f');
    }

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

    public function orden_ventas()
    {
        return $this->hasMany(OrdenVenta::class, 'cliente_id')->where("estado", "FINALIZADO");
    }
}
