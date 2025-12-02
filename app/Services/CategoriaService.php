<?php

namespace App\Services;

use App\Services\HistorialAccionService;
use App\Models\Categoria;
use App\Models\User;
use Exception;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;

class CategoriaService
{

    private $modulo = "CATEGORÍAS";

    public function __construct(private HistorialAccionService $historialAccionService) {}

    public function listado(): Collection
    {
        $categorias = Categoria::select("categorias.*")->get();
        return $categorias;
    }
    /**
     * Lista de categorias paginado con filtros
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
        $categorias = Categoria::select("categorias.*");

        // Filtros exactos
        foreach ($columnsFilter as $key => $value) {
            if (!is_null($value)) {
                $categorias->where("categorias.$key", $value);
            }
        }

        // Filtros por rango
        foreach ($columnsBetweenFilter as $key => $value) {
            if (isset($value[0], $value[1])) {
                $categorias->whereBetween("categorias.$key", $value);
            }
        }

        // Búsqueda en múltiples columnas con LIKE
        if (!empty($search) && !empty($columnsSerachLike)) {
            $categorias->where(function ($query) use ($search, $columnsSerachLike) {
                foreach ($columnsSerachLike as $col) {
                    $query->orWhere("$col", "LIKE", "%$search%");
                }
            });
        }

        // Ordenamiento
        foreach ($orderBy as $value) {
            if (isset($value[0], $value[1])) {
                $categorias->orderBy($value[0], $value[1]);
            }
        }


        $categorias = $categorias->paginate($length, ['*'], 'page', $page);
        return $categorias;
    }

    /**
     * Crear categoria
     *
     * @param array $datos
     * @return Categoria
     */
    public function crear(array $datos): Categoria
    {

        $categoria = Categoria::create([
            "nombre" => mb_strtoupper($datos["nombre"]),
        ]);
        // registrar accion
        $this->historialAccionService->registrarAccion($this->modulo, "CREACIÓN", "REGISTRO UNA CATEGORÍA", $categoria);

        return $categoria;
    }

    /**
     * Actualizar categoria
     *
     * @param array $datos
     * @param Categoria $categoria
     * @return Categoria
     */
    public function actualizar(array $datos, Categoria $categoria): Categoria
    {
        $old_categoria = Categoria::find($categoria->id);
        $categoria->update([
            "nombre" => mb_strtoupper($datos["nombre"]),
        ]);
        // registrar accion
        $this->historialAccionService->registrarAccion($this->modulo, "MODIFICACIÓN", "ACTUALIZÓ UNA CATEGORÍA", $old_categoria, $categoria);

        return $categoria;
    }

    /**
     * Eliminar categoria
     *
     * @param Categoria $categoria
     * @return boolean
     */
    public function eliminar(Categoria $categoria): bool|Exception
    {
        $old_categoria = clone $categoria;
        $categoria->delete();
        // registrar accion
        $this->historialAccionService->registrarAccion($this->modulo, "ELIMINACIÓN", "ELIMINÓ UNA CATEGORÍA", $old_categoria);

        return true;
    }
}
