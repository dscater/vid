<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleStoreRequest;
use App\Http\Requests\RoleUpdateRequest;
use App\Models\HistorialAccion;
use App\Models\Modulo;
use App\Models\Permiso;
use App\Models\Role;
use App\Models\User;
use App\Services\RoleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response as ResponseInertia;

class RoleController extends Controller
{

    public function __construct(private RoleService $roleService) {}

    /**
     * Listado de roles sin ids: 1 y 2
     *
     * @return JsonResponse
     */
    public function listado(): JsonResponse
    {
        Log::debug("ASDSD");
        return response()->JSON([
            "roles" => $this->roleService->listado()
        ]);
    }

    public function paginado(Request $request)
    {
        $perPage = $request->perPage;
        $page = (int)($request->input("page", 1));
        $search = (string)$request->input("search", "");
        $orderByCol = $request->orderByCol;
        $desc = $request->desc;

        $columnsSerachLike = [
            "descripcion"
        ];
        $columnsFilter = [];
        $columnsBetweenFilter = [];
        $arrayOrderBy = [];
        if ($orderByCol && $desc) {
            $arrayOrderBy = [
                [$orderByCol, $desc]
            ];
        }

        $roles = $this->roleService->listadoPaginado($perPage, $page, $search, $columnsSerachLike, $columnsFilter, $columnsBetweenFilter, $arrayOrderBy);
        return response()->JSON([
            "data" => $roles->items(),
            "total" => $roles->total(),
            "lastPage" => $roles->lastPage()
        ]);
    }


    /**
     * Endpoint para obtener la lista de roles paginado para datatable
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function api(Request $request): JsonResponse
    {

        return response()->JSON([]);
    }

    /**
     * Registrar un nuevo role
     *
     * @param RoleStoreRequest $request
     * @return RedirectResponse|Response
     */
    public function store(RoleStoreRequest $request): Response|JsonResponse
    {
        DB::beginTransaction();
        try {
            // crear el Role
            $this->roleService->crear($request->validated());
            DB::commit();
            return response()->JSON([
                "sw" => true,
                "message" => "Proceso realizado con éxito"
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }

    /**
     * Mostrar un role
     *
     * @param Role $role
     * @return JsonResponse
     */
    public function show(Role $role): JsonResponse
    {

        $modulos_group = Modulo::select('modulo')->distinct()->pluck('modulo');

        $array_modulos = [];
        $array_permisos = [];
        foreach ($modulos_group as $value) {
            $array_modulos[$value] = Modulo::where("modulo", $value)->get();
            $array_permisos[$value] = Permiso::select("modulos.nombre", "modulos.accion")->join("modulos", "modulos.id", "=", "permisos.modulo_id")
                ->where("role_id", $role->id)
                ->where("modulo", $value)->get();
        }

        return response()->JSON([
            "role" => $role,
            "modulos_group" => $modulos_group,
            "array_modulos" => $array_modulos,
            "array_permisos" => $array_permisos
        ]);
    }
    public function actualizaPermiso(Role $role, Request $request)
    {
        $sw_cambio = $request->sw_cambio;
        $modulo = $request->modulo;
        $accion = $request->accion;
        $o_modulo = Modulo::where("modulo", $modulo)->where("accion", $accion)->get()->first();
        $permiso = Permiso::where("role_id", $role->id)
            ->where("modulo_id", $o_modulo->id)
            ->get()->first();
        if ($sw_cambio == 1) {
            if (!$permiso) {
                $role->o_permisos()->create([
                    "modulo_id" => $o_modulo->id
                ]);
            }
        } else {
            if ($permiso) {
                $permiso->delete();
            }
        }

        $array_permisos = Permiso::select("modulos.nombre", "modulos.accion")
            ->join("modulos", "modulos.id", "=", "permisos.modulo_id")
            ->where("role_id", $role->id)
            ->where("modulos.modulo", $o_modulo->modulo)->get();

        return response()->JSON([
            "array_permisos" => $array_permisos
        ]);
    }

    public function update(Role $role, RoleUpdateRequest $request)
    {
        DB::beginTransaction();
        try {
            // actualizar role
            $this->roleService->actualizar($request->validated(), $role);
            DB::commit();
            return response()->JSON([
                "sw" => true,
                "message" => "Proceso realizado con éxito"
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            // Log::debug($e->getMessage());
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }

    /**
     * Eliminar role
     *
     * @param Role $role
     * @return JsonResponse|Response
     */
    public function destroy(Role $role): JsonResponse|Response
    {
        DB::beginTransaction();
        try {
            $this->roleService->eliminar($role);
            DB::commit();
            return response()->JSON([
                "sw" => true,
                'message' => 'El registro se eliminó correctamente'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }
}
