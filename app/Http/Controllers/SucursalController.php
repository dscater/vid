<?php

namespace App\Http\Controllers;

use App\Http\Requests\SucursalStoreRequest;
use App\Http\Requests\SucursalUpdateRequest;
use App\Models\HistorialAccion;
use App\Models\Modulo;
use App\Models\Permiso;
use App\Models\Sucursal;
use App\Models\User;
use App\Services\SucursalService;
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

class SucursalController extends Controller
{
    public function __construct(private SucursalService $sucursalService) {}

    /**
     * Listado de sucursals sin ids: 1 y 2
     *
     * @return JsonResponse
     */
    public function listado(): JsonResponse
    {
        return response()->JSON([
            "sucursals" => $this->sucursalService->listado()
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

        $sucursals = $this->sucursalService->listadoPaginado($perPage, $page, $search, $columnsSerachLike, $columnsFilter, $columnsBetweenFilter, $arrayOrderBy);
        return response()->JSON([
            "data" => $sucursals->items(),
            "total" => $sucursals->total(),
            "lastPage" => $sucursals->lastPage()
        ]);
    }


    /**
     * Endpoint para obtener la lista de sucursals paginado para datatable
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function api(Request $request): JsonResponse
    {

        return response()->JSON([]);
    }

    /**
     * Registrar un nuevo sucursal
     *
     * @param SucursalStoreRequest $request
     * @return RedirectResponse|Response
     */
    public function store(SucursalStoreRequest $request): Response|JsonResponse
    {
        DB::beginTransaction();
        try {
            // crear el Sucursal
            $this->sucursalService->crear($request->validated());
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
     * Mostrar un sucursal
     *
     * @param Sucursal $sucursal
     * @return JsonResponse
     */
    public function show(Sucursal $sucursal): JsonResponse
    {
        return response()->JSON($sucursal);
    }
    public function actualizaPermiso(Sucursal $sucursal, Request $request)
    {
        $sw_cambio = $request->sw_cambio;
        $modulo = $request->modulo;
        $accion = $request->accion;
        $o_modulo = Modulo::where("modulo", $modulo)->where("accion", $accion)->get()->first();
        $permiso = Permiso::where("sucursal_id", $sucursal->id)
            ->where("modulo_id", $o_modulo->id)
            ->get()->first();
        if ($sw_cambio == 1) {
            if (!$permiso) {
                $sucursal->o_permisos()->create([
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
            ->where("sucursal_id", $sucursal->id)
            ->where("modulos.modulo", $o_modulo->modulo)->get();

        return response()->JSON([
            "array_permisos" => $array_permisos
        ]);
    }

    public function update(Sucursal $sucursal, SucursalUpdateRequest $request)
    {
        DB::beginTransaction();
        try {
            // actualizar sucursal
            $this->sucursalService->actualizar($request->validated(), $sucursal);
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
     * Eliminar sucursal
     *
     * @param Sucursal $sucursal
     * @return JsonResponse|Response
     */
    public function destroy(Sucursal $sucursal): JsonResponse|Response
    {
        DB::beginTransaction();
        try {
            $this->sucursalService->eliminar($sucursal);
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
