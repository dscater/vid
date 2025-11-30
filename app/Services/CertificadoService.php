<?php

namespace App\Services;

use App\Models\User;
use App\Models\Certificado;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

class CertificadoService
{
    private $pathFiles = "";
    public function __construct(private  CargarArchivoService $cargarArchivoService)
    {
        $this->pathFiles = public_path("files/certificados");
    }

    /**
     * Cargar file
     *
     * @param User $user
     * @param UploadedFile $file
     * @return Certificado
     */
    public function guardarCertificado(User $user, UploadedFile $archivo, int $index = -1): Certificado
    {
        $nombre = ($index != -1 ? $index : 0) . $user->id . time();
        return $user->certificados()->create([
            "file" => $this->cargarArchivoService->cargarArchivo($archivo, $this->pathFiles, $nombre)
        ]);
    }

    /**
     * Eliminacion fisica de archivo Documento
     *
     * @param Certificado $certificado
     * @return void
     */
    public function eliminarCertificado(Certificado $certificado): void
    {
        $archivo = $certificado->file;
        if ($archivo) {
            \File::delete($this->pathFiles . "/" . $archivo);
        }
        $archivo->delete();
    }

    /**
     * Obtener extension del nombre de la archivo
     * ejem: image.png -> png
     * 
     * @param string $archivo
     * @return string
     */
    public static function getExtNomCertificado(string $archivo): string
    {
        $array = explode(".", $archivo);
        $array[count($array) - 1];
        return $array[count($array) - 1];
    }
}
