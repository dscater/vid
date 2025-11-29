<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Requisito extends Model
{
    use HasFactory;

    protected $fillable = [
        "postulante_id",
        "inscripcion_id",
        "user_id",
        "file1",
        "file2",
        "file3",
        "file4",
        "file5",
        "file6",
        "file7",
        "file8",
        "file9",
        "file10",
        "file11",
        "file12",
        "file13",
        "file14",
        "edad",
    ];

    protected $appends = ["url_file1", "url_file2", "url_file3", "url_file4", "url_file5", "url_file6", "url_file7", "url_file8", "url_file9", "url_file10", "url_file11", "url_file12", "url_file13", "url_file14"];

    public function getUrlFile1Attribute()
    {
        return asset("files/requisitos/" . $this->file1);
    }

    public function getUrlFile2Attribute()
    {
        return asset("files/requisitos/" . $this->file2);
    }
    public function getUrlFile3Attribute()
    {
        return asset("files/requisitos/" . $this->file3);
    }
    public function getUrlFile4Attribute()
    {
        return asset("files/requisitos/" . $this->file4);
    }
    public function getUrlFile5Attribute()
    {
        return asset("files/requisitos/" . $this->file5);
    }
    public function getUrlFile6Attribute()
    {
        return asset("files/requisitos/" . $this->file6);
    }
    public function getUrlFile7Attribute()
    {
        return asset("files/requisitos/" . $this->file7);
    }
    public function getUrlFile8Attribute()
    {
        return asset("files/requisitos/" . $this->file8);
    }
    public function getUrlFile9Attribute()
    {
        return asset("files/requisitos/" . $this->file9);
    }
    public function getUrlFile10Attribute()
    {
        return asset("files/requisitos/" . $this->file10);
    }
    public function getUrlFile11Attribute()
    {
        return asset("files/requisitos/" . $this->file11);
    }
    public function getUrlFile12Attribute()
    {
        return asset("files/requisitos/" . $this->file12);
    }
    public function getUrlFile13Attribute()
    {
        return asset("files/requisitos/" . $this->file13);
    }
    public function getUrlFile14Attribute()
    {
        return asset("files/requisitos/" . $this->file14);
    }

    public function postulante()
    {
        return $this->belongsTo(Postulante::class, 'postulante_id');
    }
    public function inscripcion()
    {
        return $this->belongsTo(Inscripcion::class, 'inscripcion_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
