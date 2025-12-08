<?php

namespace App\Http\Controllers;

use App\Http\Requests\CuentaCobrarStoreRequest;
use App\Http\Requests\CuentaCobrarUpdateRequest;
use App\Models\HistorialAccion;
use App\Models\Modulo;
use App\Models\Permiso;
use App\Models\CuentaCobrar;
use App\Models\User;
use App\Services\CuentaCobrarService;
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

class CuentaCobrarController extends Controller
{
    public function __construct(private CuentaCobrarService $cuenta_cobrarService) {}

    /**
     * Listado de cuenta_cobrars sin ids: 1 y 2
     *
     * @return JsonResponse
     */
    public function listado(): JsonResponse
    {
        return response()->JSON([
            "cuenta_cobrars" => $this->cuenta_cobrarService->listado()
        ]);
    }

    public function listadoSelectElementUi(Request $request): JsonResponse
    {
        $search = $request->input("search", "");
        $cuenta_cobrars = CuentaCobrar::select("cuenta_cobrars.*");
        $cuenta_cobrars->where(function ($query) use ($search) {
            $query->where("razon_social", "LIKE", "%$search%");
            // ->orWhereRaw("CONCAT(nombre, ' ', paterno, ' ', materno) LIKE ?", ["%$search%"]);
        });
        $cuenta_cobrars = $cuenta_cobrars->get();
        // $cuenta_cobrars->each->append(["full_name"]);
        $cuenta_cobrars = $cuenta_cobrars->toArray();
        return response()->JSON([
            "cuenta_cobrars" => $cuenta_cobrars
        ]);
    }

    public function paginado(Request $request)
    {
        $perPage = $request->perPage;
        $page = (int)($request->input("page", 1));
        $search = (string)$request->input("search", "");
        $orderByCol = $request->orderBy;
        $desc = $request->orderAsc;

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

        $cuenta_cobrars = $this->cuenta_cobrarService->listadoPaginado($perPage, $page, $search, $columnsSerachLike, $columnsFilter, $columnsBetweenFilter, $arrayOrderBy);
        return response()->JSON([
            "data" => $cuenta_cobrars->items(),
            "total" => $cuenta_cobrars->total(),
            "lastPage" => $cuenta_cobrars->lastPage()
        ]);
    }


    /**
     * Endpoint para obtener la lista de cuenta_cobrars paginado para datatable
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function api(Request $request): JsonResponse
    {

        return response()->JSON([]);
    }

    /**
     * Registrar un nuevo cuenta_cobrar
     *
     * @param CuentaCobrarStoreRequest $request
     * @return RedirectResponse|Response
     */
    public function store(CuentaCobrarStoreRequest $request): Response|JsonResponse
    {
        DB::beginTransaction();
        try {
            // crear el CuentaCobrar
            $this->cuenta_cobrarService->crear($request->validated());
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
     * Registrar un nuevo pago de cuenta_cobrar
     *
     * @param CuentaCobrarStoreRequest $request
     * @return RedirectResponse|Response
     */
    public function pago(CuentaCobrarStoreRequest $request, CuentaCobrar $cuenta_cobrar): Response|JsonResponse
    {
        DB::beginTransaction();
        try {
            // crear el CuentaCobrar
            $this->cuenta_cobrarService->pago($request->validated(), $cuenta_cobrar);
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
     * Mostrar un cuenta_cobrar
     *
     * @param CuentaCobrar $cuenta_cobrar
     * @return JsonResponse
     */
    public function show(CuentaCobrar $cuenta_cobrar): JsonResponse
    {
        return response()->JSON($cuenta_cobrar->load(["orden_venta", "cliente", "cuenta_cobrar_detalles"]));
    }
    public function actualizaPermiso(CuentaCobrar $cuenta_cobrar, Request $request)
    {
        $sw_cambio = $request->sw_cambio;
        $modulo = $request->modulo;
        $accion = $request->accion;
        $o_modulo = Modulo::where("modulo", $modulo)->where("accion", $accion)->get()->first();
        $permiso = Permiso::where("cuenta_cobrar_id", $cuenta_cobrar->id)
            ->where("modulo_id", $o_modulo->id)
            ->get()->first();
        if ($sw_cambio == 1) {
            if (!$permiso) {
                $cuenta_cobrar->o_permisos()->create([
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
            ->where("cuenta_cobrar_id", $cuenta_cobrar->id)
            ->where("modulos.modulo", $o_modulo->modulo)->get();

        return response()->JSON([
            "array_permisos" => $array_permisos
        ]);
    }

    public function update(CuentaCobrar $cuenta_cobrar, CuentaCobrarUpdateRequest $request)
    {
        DB::beginTransaction();
        try {
            // actualizar cuenta_cobrar
            $this->cuenta_cobrarService->actualizar($request->validated(), $cuenta_cobrar);
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
     * Eliminar cuenta_cobrar
     *
     * @param CuentaCobrar $cuenta_cobrar
     * @return JsonResponse|Response
     */
    public function destroy(CuentaCobrar $cuenta_cobrar): JsonResponse|Response
    {
        DB::beginTransaction();
        try {
            $this->cuenta_cobrarService->eliminar($cuenta_cobrar);
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
