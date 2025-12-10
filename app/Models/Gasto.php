<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gasto extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "descripcion",
        "monto",
        "fecha",
        "hora",
    ];
    protected $appends = ["fecha_t", "hora_t", "fecha_c"];

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
}
