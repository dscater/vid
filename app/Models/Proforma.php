<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use App\library\numero_a_letras\src\NumeroALetras;

class Proforma extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "nro",
        "codigo",
        "sucursal_ids",
        "fecha",
        "hora",
        "cantidad_total",
        "total",
        "observaciones",
        "user_id",
    ];


    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'sucursal_ids' => 'array',
        ];
    }



    protected $appends = ["fecha_t", "hora_t", "fecha_c", "fecha_ct", "sucursals_txt"];

    public function getSucursalsTxtAttribute()
    {
        $nombres = Sucursal::whereIn("id", $this->sucursal_ids)->pluck("nombre")->toArray();

        return implode(", ", $nombres);
    }

    public function getFechaCtAttribute()
    {
        $dt = Carbon::parse($this->fecha . ' ' . $this->hora);
        return $dt->translatedFormat('d \d\e F \d\e Y');
    }

    public function getFechaCAttribute()
    {
        return date("d/m/Y H:i", strtotime($this->fecha . ' ' . $this->hora));
    }

    public function getFechaTAttribute()
    {
        return date("d/m/Y", strtotime($this->fecha));
    }

    public function getHoraTAttribute()
    {
        return date("H:i", strtotime($this->hora));
    }

    public function user()
    {
        return $this->belongsTo(User::class, "user_id");
    }

    public function proforma_detalles()
    {
        return $this->hasMany(ProformaDetalle::class, 'proforma_id');
    }

    public function proforma_productos()
    {
        return $this->hasMany(ProformaProducto::class, 'proforma_id');
    }
}
