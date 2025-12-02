<?php

namespace App\Services;

use App\Services\HistorialAccionService;
use App\Models\Marca;
use App\Models\User;
use Exception;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;

class MarcaService
{

    private $modulo = "MARCAS";

    public function __construct(private HistorialAccionService $historialAccionService) {}

    public function listado(): Collection
    {
        $marcas = Marca::select("marcas.*")->get();
        return $marcas;
    }
    /**
     * Lista de marcas paginado con filtros
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
        $marcas = Marca::select("marcas.*");

        // Filtros exactos
        foreach ($columnsFilter as $key => $value) {
            if (!is_null($value)) {
                $marcas->where("marcas.$key", $value);
            }
        }

        // Filtros por rango
        foreach ($columnsBetweenFilter as $key => $value) {
            if (isset($value[0], $value[1])) {
                $marcas->whereBetween("marcas.$key", $value);
            }
        }

        // Búsqueda en múltiples columnas con LIKE
        if (!empty($search) && !empty($columnsSerachLike)) {
            $marcas->where(function ($query) use ($search, $columnsSerachLike) {
                foreach ($columnsSerachLike as $col) {
                    $query->orWhere("$col", "LIKE", "%$search%");
                }
            });
        }

        // Ordenamiento
        foreach ($orderBy as $value) {
            if (isset($value[0], $value[1])) {
                $marcas->orderBy($value[0], $value[1]);
            }
        }


        $marcas = $marcas->paginate($length, ['*'], 'page', $page);
        return $marcas;
    }

    /**
     * Crear marca
     *
     * @param array $datos
     * @return Marca
     */
    public function crear(array $datos): Marca
    {

        $marca = Marca::create([
            "nombre" => mb_strtoupper($datos["nombre"]),
        ]);
        // registrar accion
        $this->historialAccionService->registrarAccion($this->modulo, "CREACIÓN", "REGISTRO UNA MARCA", $marca);

        return $marca;
    }

    /**
     * Actualizar marca
     *
     * @param array $datos
     * @param Marca $marca
     * @return Marca
     */
    public function actualizar(array $datos, Marca $marca): Marca
    {
        $old_marca = Marca::find($marca->id);
        $marca->update([
            "nombre" => mb_strtoupper($datos["nombre"]),
        ]);
        // registrar accion
        $this->historialAccionService->registrarAccion($this->modulo, "MODIFICACIÓN", "ACTUALIZÓ UNA MARCA", $old_marca, $marca);

        return $marca;
    }

    /**
     * Eliminar marca
     *
     * @param Marca $marca
     * @return boolean
     */
    public function eliminar(Marca $marca): bool|Exception
    {
        $old_marca = clone $marca;
        $marca->delete();
        // registrar accion
        $this->historialAccionService->registrarAccion($this->modulo, "ELIMINACIÓN", "ELIMINÓ UNA MARCA", $old_marca);

        return true;
    }
}
