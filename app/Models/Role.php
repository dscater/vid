<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "nombre",
        "permisos",
        "usuarios"
    ];

    public function o_permisos()
    {
        return $this->hasMany(Permiso::class, 'role_id');
    }
}
