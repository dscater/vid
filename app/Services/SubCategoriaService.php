<?php

namespace App\Services;

use App\Services\HistorialAccionService;
use App\Models\SubCategoria;
use App\Models\User;
use Exception;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;

class SubCategoriaService
{

    private $modulo = "SUBCATEGORÍAS";

    public function __construct(private HistorialAccionService $historialAccionService) {}

    public function listado(): Collection
    {
        $sub_categorias = SubCategoria::select("sub_categorias.*")->get();
        return $sub_categorias;
    }
    /**
     * Lista de sub_categorias paginado con filtros
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
        $sub_categorias = SubCategoria::select("sub_categorias.*")
            ->with(["categoria:id,nombre"]);

        // Filtros exactos
        foreach ($columnsFilter as $key => $value) {
            if (!is_null($value)) {
                $sub_categorias->where("sub_categorias.$key", $value);
            }
        }

        // Filtros por rango
        foreach ($columnsBetweenFilter as $key => $value) {
            if (isset($value[0], $value[1])) {
                $sub_categorias->whereBetween("sub_categorias.$key", $value);
            }
        }

        // Búsqueda en múltiples columnas con LIKE
        if (!empty($search) && !empty($columnsSerachLike)) {
            $sub_categorias->where(function ($query) use ($search, $columnsSerachLike) {
                foreach ($columnsSerachLike as $col) {
                    $query->orWhere("$col", "LIKE", "%$search%");
                }
            });
        }

        // Ordenamiento
        foreach ($orderBy as $value) {
            if (isset($value[0], $value[1])) {
                $sub_categorias->orderBy($value[0], $value[1]);
            }
        }


        $sub_categorias = $sub_categorias->paginate($length, ['*'], 'page', $page);
        return $sub_categorias;
    }

    /**
     * Crear sub_categoria
     *
     * @param array $datos
     * @return SubCategoria
     */
    public function crear(array $datos): SubCategoria
    {

        $sub_categoria = SubCategoria::create([
            "nombre" => mb_strtoupper($datos["nombre"]),
            "categoria_id" => $datos["categoria_id"],
        ]);
        // registrar accion
        $this->historialAccionService->registrarAccion($this->modulo, "CREACIÓN", "REGISTRO UNA SUBCATEGORÍA", $sub_categoria);

        return $sub_categoria;
    }

    /**
     * Actualizar sub_categoria
     *
     * @param array $datos
     * @param SubCategoria $sub_categoria
     * @return SubCategoria
     */
    public function actualizar(array $datos, SubCategoria $sub_categoria): SubCategoria
    {
        $old_sub_categoria = SubCategoria::find($sub_categoria->id);
        $sub_categoria->update([
            "nombre" => mb_strtoupper($datos["nombre"]),
            "categoria_id" => $datos["categoria_id"],
        ]);
        // registrar accion
        $this->historialAccionService->registrarAccion($this->modulo, "MODIFICACIÓN", "ACTUALIZÓ UNA SUBCATEGORÍA", $old_sub_categoria, $sub_categoria);

        return $sub_categoria;
    }

    /**
     * Eliminar sub_categoria
     *
     * @param SubCategoria $sub_categoria
     * @return boolean
     */
    public function eliminar(SubCategoria $sub_categoria): bool|Exception
    {
        $old_sub_categoria = clone $sub_categoria;
        $sub_categoria->delete();
        // registrar accion
        $this->historialAccionService->registrarAccion($this->modulo, "ELIMINACIÓN", "ELIMINÓ UNA SUBCATEGORÍA", $old_sub_categoria);

        return true;
    }
}
