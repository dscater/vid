<?php

namespace App\Models;

use App\Services\CertificadoService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificado extends Model
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
        return asset("/files/certificados/" . $this->file);
    }

    public function getUrlFileAttribute()
    {
        $array_files = ["jpg", "jpeg", "png", "webp", "gif"];
        $ext = CertificadoService::getExtNomCertificado($this->file);
        if (in_array($ext, $array_files)) {
            return asset("/files/certificados/" . $this->file);
        }
        return asset("/imgs/attach.png");
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
