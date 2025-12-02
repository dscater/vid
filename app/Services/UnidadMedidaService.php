<?php

namespace App\Services;

use App\Services\HistorialAccionService;
use App\Models\UnidadMedida;
use App\Models\User;
use Exception;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;

class UnidadMedidaService
{

    private $modulo = "UNIDAD DE MEDIDA";

    public function __construct(private HistorialAccionService $historialAccionService) {}

    public function listado(): Collection
    {
        $unidad_medidas = UnidadMedida::select("unidad_medidas.*")->get();
        return $unidad_medidas;
    }
    /**
     * Lista de unidad_medidas paginado con filtros
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
        $unidad_medidas = UnidadMedida::select("unidad_medidas.*");

        // Filtros exactos
        foreach ($columnsFilter as $key => $value) {
            if (!is_null($value)) {
                $unidad_medidas->where("unidad_medidas.$key", $value);
            }
        }

        // Filtros por rango
        foreach ($columnsBetweenFilter as $key => $value) {
            if (isset($value[0], $value[1])) {
                $unidad_medidas->whereBetween("unidad_medidas.$key", $value);
            }
        }

        // Búsqueda en múltiples columnas con LIKE
        if (!empty($search) && !empty($columnsSerachLike)) {
            $unidad_medidas->where(function ($query) use ($search, $columnsSerachLike) {
                foreach ($columnsSerachLike as $col) {
                    $query->orWhere("$col", "LIKE", "%$search%");
                }
            });
        }

        // Ordenamiento
        foreach ($orderBy as $value) {
            if (isset($value[0], $value[1])) {
                $unidad_medidas->orderBy($value[0], $value[1]);
            }
        }


        $unidad_medidas = $unidad_medidas->paginate($length, ['*'], 'page', $page);
        return $unidad_medidas;
    }

    /**
     * Crear unidad_medida
     *
     * @param array $datos
     * @return UnidadMedida
     */
    public function crear(array $datos): UnidadMedida
    {

        $unidad_medida = UnidadMedida::create([
            "nombre" => mb_strtoupper($datos["nombre"]),
        ]);
        // registrar accion
        $this->historialAccionService->registrarAccion($this->modulo, "CREACIÓN", "REGISTRO UNA UNIDAD DE MEDIDA", $unidad_medida);

        return $unidad_medida;
    }

    /**
     * Actualizar unidad_medida
     *
     * @param array $datos
     * @param UnidadMedida $unidad_medida
     * @return UnidadMedida
     */
    public function actualizar(array $datos, UnidadMedida $unidad_medida): UnidadMedida
    {
        $old_unidad_medida = UnidadMedida::find($unidad_medida->id);
        $unidad_medida->update([
            "nombre" => mb_strtoupper($datos["nombre"]),
        ]);
        // registrar accion
        $this->historialAccionService->registrarAccion($this->modulo, "MODIFICACIÓN", "ACTUALIZÓ UNA UNIDAD DE MEDIDA", $old_unidad_medida, $unidad_medida);

        return $unidad_medida;
    }

    /**
     * Eliminar unidad_medida
     *
     * @param UnidadMedida $unidad_medida
     * @return boolean
     */
    public function eliminar(UnidadMedida $unidad_medida): bool|Exception
    {
        $old_unidad_medida = clone $unidad_medida;
        $unidad_medida->delete();
        // registrar accion
        $this->historialAccionService->registrarAccion($this->modulo, "ELIMINACIÓN", "ELIMINÓ UNA UNIDAD DE MEDIDA", $old_unidad_medida);

        return true;
    }
}
