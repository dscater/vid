<?php

namespace App\Services;

use App\Models\Comunicado;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class ComunicadoService
{
    private $modulo = "DOCUMENTOS DE DESCARGA";

    public function __construct(private  CargarArchivoService $cargarArchivoService, private HistorialAccionService $historialAccionService) {}

    /**
     * Lista de comunicados paginado con filtros
     *
     * @param integer $length
     * @param integer $page
     * @param string $search
     * @param array $columnsSerachLike
     * @param array $columnsFilter
     * @return LengthAwarePaginator
     */
    public function listadoPaginado(int $length, int $page, string $search, array $columnsSerachLike = [], array $columnsFilter = [], array $columnsBetweenFilter = [], array $orderBy = []): LengthAwarePaginator
    {
        $comunicados = Comunicado::select("comunicados.*");

        // Filtros exactos
        foreach ($columnsFilter as $key => $value) {
            if (!is_null($value)) {
                $comunicados->where("comunicados.$key", $value);
            }
        }

        // Filtros por rango
        foreach ($columnsBetweenFilter as $key => $value) {
            if (isset($value[0], $value[1])) {
                $comunicados->whereBetween("comunicados.$key", $value);
            }
        }

        // Búsqueda en múltiples columnas con LIKE
        if (!empty($search) && !empty($columnsSerachLike)) {
            $comunicados->where(function ($query) use ($search, $columnsSerachLike) {
                foreach ($columnsSerachLike as $col) {
                    $query->orWhere("$col", "LIKE", "%$search%");
                }
            });
        }

        // Ordenamiento
        foreach ($orderBy as $value) {
            if (isset($value[0], $value[1])) {
                $comunicados->orderBy($value[0], $value[1]);
            }
        }


        $comunicados = $comunicados->paginate($length, ['*'], 'page', $page);
        return $comunicados;
    }


    /**
     * Crear comunicado
     *
     * @param array $datos
     * @return Comunicado
     */
    public function crear(array $datos): Comunicado
    {
        $comunicado = Comunicado::create([
            "descripcion" => $datos["descripcion"],
            "unidad" => $datos["unidad"],
        ]);

        // cargar documento
        $this->cargarImagen($comunicado, $datos["imagen"]);

        // registrar accion
        $this->historialAccionService->registrarAccion($this->modulo, "CREACIÓN", "REGISTRO UN DOCUMENTO DE DESCARGA", $comunicado);

        return $comunicado;
    }

    /**
     * Actualizar comunicado
     *
     * @param array $datos
     * @param Comunicado $comunicado
     * @return Comunicado
     */
    public function actualizar(array $datos, Comunicado $comunicado): Comunicado
    {
        $old_comunicado = clone $comunicado;
        $comunicado->update([
            "descripcion" => $datos["descripcion"],
        ]);

        // cargar documento
        if ($datos["imagen"] && !is_string($datos["imagen"])) {
            $this->cargarImagen($comunicado, $datos["imagen"]);
        }

        // registrar accion
        $this->historialAccionService->registrarAccion($this->modulo, "MODIFICACIÓN", "ACTUALIZÓ UN DOCUMENTO DE DESCARGA", $old_comunicado, $comunicado->withoutRelations());

        return $comunicado;
    }

    /**
     * Cargar documento
     *
     * @param Comunicado $comunicado
     * @param UploadedFile $documento
     * @return void
     */
    public function cargarImagen(Comunicado $comunicado, UploadedFile $documento): void
    {
        if ($comunicado->imagen) {
            \File::delete(public_path("imgs/comunicados/" . $comunicado->imagen));
        }

        $nombre = $comunicado->id . time();
        $comunicado->imagen = $this->cargarArchivoService->cargarArchivo($documento, public_path("imgs/comunicados"), $nombre);
        $comunicado->save();
    }

    /**
     * Eliminar comunicado
     *
     * @param Comunicado $comunicado
     * @return boolean
     */
    public function eliminar(Comunicado $comunicado): bool
    {
        $old_comunicado = clone $comunicado;
        // no eliminar comunicados predeterminados para el funcionamiento del sistema
        $comunicado->delete();

        // registrar accion
        $this->historialAccionService->registrarAccion($this->modulo, "ELIMINACIÓN", "ELIMINÓ AL USUARIO " . $old_comunicado->comunicado, $old_comunicado, $comunicado);
        return true;
    }
}
