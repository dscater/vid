<?php

namespace App\Services;

use App\Services\HistorialAccionService;
use App\Models\AjusteReposicion;
use App\Models\User;
use Exception;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;

class AjusteReposicionService
{

    private $modulo = "AJUSTES REPOSICIÓN";

    public function __construct(private HistorialAccionService $historialAccionService) {}

    public function listado(): Collection
    {
        $ajuste_reposicions = AjusteReposicion::select("ajuste_reposicions.*")->get();
        return $ajuste_reposicions;
    }
    /**
     * Lista de ajuste_reposicions paginado con filtros
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
        $ajuste_reposicions = AjusteReposicion::select("ajuste_reposicions.*");

        // Filtros exactos
        foreach ($columnsFilter as $key => $value) {
            if (!is_null($value)) {
                $ajuste_reposicions->where("ajuste_reposicions.$key", $value);
            }
        }

        // Filtros por rango
        foreach ($columnsBetweenFilter as $key => $value) {
            if (isset($value[0], $value[1])) {
                $ajuste_reposicions->whereBetween("ajuste_reposicions.$key", $value);
            }
        }

        // Búsqueda en múltiples columnas con LIKE
        if (!empty($search) && !empty($columnsSerachLike)) {
            $ajuste_reposicions->where(function ($query) use ($search, $columnsSerachLike) {
                foreach ($columnsSerachLike as $col) {
                    $query->orWhere("$col", "LIKE", "%$search%");
                }
            });
        }

        // Ordenamiento
        foreach ($orderBy as $value) {
            if (isset($value[0], $value[1])) {
                $ajuste_reposicions->orderBy($value[0], $value[1]);
            }
        }


        $ajuste_reposicions = $ajuste_reposicions->paginate($length, ['*'], 'page', $page);
        return $ajuste_reposicions;
    }

    /**
     * Crear ajuste_reposicion
     *
     * @param array $datos
     * @return AjusteReposicion
     */
    public function crear(array $datos): AjusteReposicion
    {

        $ajuste_reposicion = AjusteReposicion::create([
            "ajuste_id" => $datos["ajuste_id"],
            "sucursal_id" => $datos["sucursal_id"],
            "producto_id" => $datos["producto_id"],
            "cantidad" => $datos["cantidad"],
            "fecha" => date("Y-m-d")
        ]);
        // registrar accion
        $this->historialAccionService->registrarAccion($this->modulo, "CREACIÓN", "REGISTRO UNA REPOSICIÓN", $ajuste_reposicion);

        return $ajuste_reposicion;
    }
}
