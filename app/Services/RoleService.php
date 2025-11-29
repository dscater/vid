<?php

namespace App\Services;

use App\Services\HistorialAccionService;
use App\Models\Role;
use App\Models\User;
use Exception;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;

class RoleService
{

    private $modulo = "ROLES";
    private $no_delete_role = [1]; // super administrador|cliente

    public function __construct(private HistorialAccionService $historialAccionService) {}

    public function listado(): Collection
    {
        $roles = Role::select("roles.*")->where("usuarios", 1)->get();
        return $roles;
    }
    /**
     * Lista de roles paginado con filtros
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
        $roles = Role::select("roles.*")->where("id", "!=", 1);

        // Filtros exactos
        foreach ($columnsFilter as $key => $value) {
            if (!is_null($value)) {
                $roles->where("roles.$key", $value);
            }
        }

        // Filtros por rango
        foreach ($columnsBetweenFilter as $key => $value) {
            if (isset($value[0], $value[1])) {
                $roles->whereBetween("roles.$key", $value);
            }
        }

        // Búsqueda en múltiples columnas con LIKE
        if (!empty($search) && !empty($columnsSerachLike)) {
            $roles->where(function ($query) use ($search, $columnsSerachLike) {
                foreach ($columnsSerachLike as $col) {
                    $query->orWhere("$col", "LIKE", "%$search%");
                }
            });
        }

        // Ordenamiento
        foreach ($orderBy as $value) {
            if (isset($value[0], $value[1])) {
                $roles->orderBy($value[0], $value[1]);
            }
        }


        $roles = $roles->paginate($length, ['*'], 'page', $page);
        return $roles;
    }

    /**
     * Crear role
     *
     * @param array $datos
     * @return Role
     */
    public function crear(array $datos): Role
    {

        $role = Role::create([
            "nombre" => mb_strtoupper($datos["nombre"])
        ]);
        // registrar accion
        $this->historialAccionService->registrarAccion($this->modulo, "CREACIÓN", "REGISTRO UN ROLE", $role);

        return $role;
    }

    /**
     * Actualizar role
     *
     * @param array $datos
     * @param Role $role
     * @return Role
     */
    public function actualizar(array $datos, Role $role): Role
    {
        $old_role = Role::find($role->id);
        $role->update([
            "nombre" => mb_strtoupper($datos["nombre"])
        ]);
        // registrar accion
        $this->historialAccionService->registrarAccion($this->modulo, "MODIFICACIÓN", "ACTUALIZÓ UN ROLE", $old_role, $role);

        return $role;
    }

    /**
     * Eliminar role
     *
     * @param Role $role
     * @return boolean
     */
    public function eliminar(Role $role): bool|Exception
    {
        // verificar usos
        $usos = User::where("role_id", $role->id)->get();
        if (count($usos) > 0) {
            throw ValidationException::withMessages([
                'error' =>  "No es posible eliminar este registro porque esta siendo utilizado por otros registros",
            ]);
        }

        // no eliminar roles predeterminados para el funcionamiento del sistema
        if (!in_array($role->id, $this->no_delete_role)) {
            $old_role = Role::find($role->id);
            $role->o_permisos()->delete();
            $role->delete();

            // registrar accion
            $this->historialAccionService->registrarAccion($this->modulo, "ELIMINACIÓN", "ELIMINÓ UN ROLE", $old_role);

            return true;
        }

        throw new Exception("No es posible eliminar este role");
    }
}
