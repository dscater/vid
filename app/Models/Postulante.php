<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Postulante extends Model
{
    use HasFactory;

    protected $fillable = [
        "nombre",
        "paterno",
        "materno",
        "ci",
        "ci_exp",
        "complemento",
        "genero",
        "fecha_nac",
        "cel",
        "nro_cuenta",
        "status",
        "fecha_registro",
    ];

    protected $appends = ["full_name", "full_ci", "fecha_registro_t", "fecha_nac_t", "edad_lim", "edad"];

    public function getEdadLimAttribute()
    {
        $fechaNacimiento = Carbon::parse($this->fecha_nac);
        $fechaLimite = Carbon::create(2025, 12, 31); //FECHA LIMITE

        $edad = $fechaNacimiento->diffInYears($fechaLimite);
        return $edad;
    }

    public function getEdadAttribute()
    {
        $nacimiento = Carbon::parse($this->fecha_nac);
        $fechaComparacion = Carbon::now();
        $diff = $nacimiento->diff($fechaComparacion);
        return [
            'anios' => $diff->y,
            'meses' => $diff->m,
            'dias' => $diff->d,
        ];
    }

    public function getFechaNacTAttribute()
    {
        return date("d/m/Y", strtotime($this->fecha_nac));
    }

    public function getFechaRegistroTAttribute()
    {
        return date("d/m/Y", strtotime($this->fecha_registro));
    }

    public function getFullNameAttribute()
    {
        return $this->nombre . ' ' . $this->paterno . ($this->materno ? ' ' . $this->materno : '');
    }

    public function getFullCiAttribute()
    {
        return $this->ci . ' ' . $this->ci_exp;
    }

    public function requisito()
    {
        return $this->hasOne(Requisito::class, 'postulante_id');
    }

    public function inscripcions()
    {
        return $this->hasMany(Inscripcion::class, 'postulante_id');
    }
}
