<?php

namespace App\Services;

use App\Services\HistorialAccionService;
use App\Models\Gasto;
use App\Models\User;
use Exception;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;

class GastoService
{

    private $modulo = "GASTOS";

    public function __construct(private HistorialAccionService $historialAccionService) {}

    public function listado(): Collection
    {
        $gastos = Gasto::select("gastos.*")->get();
        return $gastos;
    }
    /**
     * Lista de gastos paginado con filtros
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
        $gastos = Gasto::select("gastos.*");

        // Filtros exactos
        foreach ($columnsFilter as $key => $value) {
            if (!is_null($value)) {
                $gastos->where("gastos.$key", $value);
            }
        }

        // Filtros por rango
        foreach ($columnsBetweenFilter as $key => $value) {
            if (isset($value[0], $value[1])) {
                $gastos->whereBetween("gastos.$key", $value);
            }
        }

        // Búsqueda en múltiples columnas con LIKE
        if (!empty($search) && !empty($columnsSerachLike)) {
            $gastos->where(function ($query) use ($search, $columnsSerachLike) {
                foreach ($columnsSerachLike as $col) {
                    $query->orWhere("$col", "LIKE", "%$search%");
                }
            });
        }

        // Ordenamiento
        foreach ($orderBy as $value) {
            if (isset($value[0], $value[1])) {
                $gastos->orderBy($value[0], $value[1]);
            }
        }


        $gastos = $gastos->paginate($length, ['*'], 'page', $page);
        return $gastos;
    }

    /**
     * Crear gasto
     *
     * @param array $datos
     * @return Gasto
     */
    public function crear(array $datos): Gasto
    {

        $gasto = Gasto::create([
            "descripcion" => mb_strtoupper($datos["descripcion"]),
            "monto" => mb_strtoupper($datos["monto"]),
            "fecha" => mb_strtoupper($datos["fecha"]),
            "hora" => mb_strtoupper($datos["hora"]),
        ]);
        // registrar accion
        $this->historialAccionService->registrarAccion($this->modulo, "CREACIÓN", "REGISTRO UN GASTO", $gasto);

        return $gasto;
    }

    /**
     * Actualizar gasto
     *
     * @param array $datos
     * @param Gasto $gasto
     * @return Gasto
     */
    public function actualizar(array $datos, Gasto $gasto): Gasto
    {
        $old_gasto = Gasto::find($gasto->id);
        $gasto->update([
            "descripcion" => mb_strtoupper($datos["descripcion"]),
            "monto" => mb_strtoupper($datos["monto"]),
            "fecha" => mb_strtoupper($datos["fecha"]),
            "hora" => mb_strtoupper($datos["hora"]),
        ]);
        // registrar accion
        $this->historialAccionService->registrarAccion($this->modulo, "MODIFICACIÓN", "ACTUALIZÓ UN GASTO", $old_gasto, $gasto);

        return $gasto;
    }

    /**
     * Eliminar gasto
     *
     * @param Gasto $gasto
     * @return boolean
     */
    public function eliminar(Gasto $gasto): bool|Exception
    {
        $old_gasto = clone $gasto;
        $gasto->delete();
        // registrar accion
        $this->historialAccionService->registrarAccion($this->modulo, "ELIMINACIÓN", "ELIMINÓ UN GASTO", $old_gasto);

        return true;
    }
}
