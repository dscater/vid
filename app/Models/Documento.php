<?php

namespace App\Models;

use App\Services\DocumentoService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "file",
    ];

    protected $appends = ["url_file", "url_archivo", "name", "ext"];

    public function getExtAttribute()
    {
        $array = explode(".", $this->file);
        return $array[1];
    }

    public function getNameAttribute()
    {
        return $this->file;
    }

    public function getUrlArchivoAttribute()
    {
        return asset("/files/documentos/" . $this->file);
    }

    public function getUrlFileAttribute()
    {
        $array_files = ["jpg", "jpeg", "png", "webp", "gif"];
        $ext = DocumentoService::getExtNomDocumento($this->file);
        if (in_array($ext, $array_files)) {
            return asset("/files/documentos/" . $this->file);
        }
        return asset("/imgs/attach.png");
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
