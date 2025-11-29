<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DescargaDocumento extends Model
{
    use HasFactory;

    protected $fillable = [
        "descripcion",
        "documento",
    ];

    protected $appends = ["url_documento"];

    public function getUrlDocumentoAttribute()
    {
        return asset("files/descarga_documentos/" . $this->documento);
    }
}
