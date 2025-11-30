<?php

namespace App\Services;

use App\Models\User;
use App\Models\Documento;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

class DocumentoService
{
    private $pathFiles = "";
    public function __construct(private  CargarArchivoService $cargarArchivoService)
    {
        $this->pathFiles = public_path("files/documentos");
    }

    /**
     * Cargar file
     *
     * @param User $habitacion
     * @param UploadedFile $file
     * @return Documento
     */
    public function guardarDocumento(User $habitacion, UploadedFile $archivo, int $index = -1): Documento
    {
        $nombre = ($index != -1 ? $index : 0) . $habitacion->id . time();
        return $habitacion->documentos()->create([
            "file" => $this->cargarArchivoService->cargarArchivo($archivo, $this->pathFiles, $nombre)
        ]);
    }

    /**
     * Eliminacion fisica de archivo Documento
     *
     * @param Documento $documento
     * @return void
     */
    public function eliminarDocumento(Documento $documento): void
    {
        $archivo = $documento->file;
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
    public static function getExtNomDocumento(string $archivo): string
    {
        $array = explode(".", $archivo);
        $array[count($array) - 1];
        return $array[count($array) - 1];
    }
}
