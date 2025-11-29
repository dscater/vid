<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inscripcion extends Model
{
    use HasFactory;

    protected $fillable = [
        "postulante_id",
        "user_id",
        "correo",
        "unidad",
        "lugar_preins",
        "observacion",
        "estado",
        "foto",
        "fecha_registro",
        "nroPre",
        "codigoPre",
        "nroInsc",
        "codigoInsc",
        "codigo",
        "ecodigo",
        "epass",
        "validDocs",
        "status",
        "registrado_por"
    ];
    protected $appends = ["url_foto", "sede", "fecha_registro_t"];

    public function getFechaRegistroTAttribute()
    {
        return date("d/m/Y", strtotime($this->fecha_registro));
    }


    public function getUrlFotoAttribute()
    {
        if ($this->foto) {
            return asset("imgs/users/" . $this->foto);
        }
        return asset("imgs/users/default.png");
    }

    public function getFotoB64Attribute()
    {
        $path = public_path("imgs/users/" . $this->foto);
        if (!$this->foto || !file_exists($path)) {
            $path = public_path("imgs/users/default.png");
        }
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        return $base64;
    }
    public function getSedeAttribute()
    {
        $sedes = ["LA PAZ", "ORURO", "POTOSÍ", "CHUQUISCA", "TARIJA", "PANDO", "BENI", "SANTA CRUZ", "COCHABAMBA"];
        $lugar_preins = [
            "ANAPOL" => 0,
            'FATESCIPOL "EL ALTO"' => 0,
            'FATESCIPOL "COLQUIRI"' => 0,
            'FATESCIPOL "HUANUNI"' => 1,
            'FATESCIPOL "CARACOLLO"' => 1,
            "COMANDO DPTAL. DE ORURO" => 1,
            'FATESCIPOL "POTOSÍ”' => 2,
            'FATESCIPOL "LLALLAGUA”' => 2,
            'FATESCIPOL "SUCRE”' => 3,
            'FATESCIPOL "TARIJA”' => 4,
            'FATESCIPOL "GRAN CHACO”' => 4,
            'FATESCIPOL "PANDO”' => 5,
            "COMANDO DPTAL. DE BEN" => 6,
            'FATESCIPOL "RIBERALTA"' => 6,
            'FATESCIPOL "SANTA CRUZ"' => 7,
            'FATESCIPOL "COCHABAMBA"' => 8,
            "ESBAPOLMUS" => 8,
        ];

        return $sedes[$lugar_preins[$this->lugar_preins]];
    }

    public function postulante()
    {
        return $this->belongsTo(Postulante::class, 'postulante_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function requisito()
    {
        return $this->hasOne(Requisito::class, 'inscripcion_id');
    }


    public function registrado_por()
    {
        return $this->belongsTo(User::class, 'registrado_por');
    }

    // EVALUACIONES
    public function evaluacion_medica()
    {
        return $this->hasOne(EvaluacionMedica::class, 'inscripcion_id');
    }
    public function evaluacion_fisica()
    {
        return $this->hasOne(EvaluacionFisica::class, 'inscripcion_id');
    }

    public function evaluacion_instruccion()
    {
        return $this->hasOne(EvaluacionInstruccion::class, 'inscripcion_id');
    }

    public function evaluacion_conocimiento()
    {
        return $this->hasOne(EvaluacionConocimiento::class, 'inscripcion_id');
    }
    public function lengua_castellana()
    {
        return $this->hasOne(LenguaCastellana::class, 'inscripcion_id');
    }
    public function matematica_fisica()
    {
        return $this->hasOne(MatematicaFisica::class, 'inscripcion_id');
    }
    public function ciencia_social()
    {
        return $this->hasOne(CienciaSocial::class, 'inscripcion_id');
    }
    public function historial_policial()
    {
        return $this->hasOne(HistorialPolicial::class, 'inscripcion_id');
    }
    public function instruccion_policial()
    {
        return $this->hasOne(InstruccionPolicial::class, 'inscripcion_id');
    }
    public function acondicionamiento_fisico()
    {
        return $this->hasOne(AcondicionamientoFisico::class, 'inscripcion_id');
    }
}
