<?php

namespace App\Http\Controllers;

use App\Http\Requests\SolicitudIngresoStoreRequest;
use App\Http\Requests\SolicitudIngresoUpdateRequest;
use App\Models\HistorialAccion;
use App\Models\Modulo;
use App\Models\Permiso;
use App\Models\SolicitudIngreso;
use App\Services\SolicitudIngresoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\ValidationException;

class SolicitudIngresoController extends Controller
{
    public function __construct(private SolicitudIngresoService $solicitud_ingresoService) {}

    /**
     * Listado de solicitud_ingresos sin ids: 1 y 2
     *
     * @return JsonResponse
     */
    public function listado(): JsonResponse
    {
        return response()->JSON([
            "solicitud_ingresos" => $this->solicitud_ingresoService->listado()
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

        $solicitud_ingresos = $this->solicitud_ingresoService->listadoPaginado($perPage, $page, $search, $columnsSerachLike, $columnsFilter, $columnsBetweenFilter, $arrayOrderBy);
        return response()->JSON([
            "data" => $solicitud_ingresos->items(),
            "total" => $solicitud_ingresos->total(),
            "lastPage" => $solicitud_ingresos->lastPage()
        ]);
    }


    /**
     * Endpoint para obtener la lista de solicitud_ingresos paginado para datatable
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function api(Request $request): JsonResponse
    {

        return response()->JSON([]);
    }

    /**
     * Registrar un nuevo solicitud_ingreso
     *
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function store(Request $request): Response|JsonResponse
    {
        try {

            return Cache::lock("solicitudStore")->block(10, function () use ($request) {
                $request = app(SolicitudIngresoStoreRequest::class);
                DB::beginTransaction();
                try {
                    $this->solicitud_ingresoService->crear($request->validated());
                    DB::commit();
                    return response()->JSON([
                        "sw" => true,
                        "message" => "Proceso realizado con éxito"
                    ]);
                } catch (\Exception $e) {
                    DB::rollBack();
                    return back()->withErrors(["error" => $e->getMessage()]);
                }
            });
        } catch (ValidationException $ve) {
            // Si falla la validación fuera del lock
            throw ValidationException::withMessages($ve->errors());
        } catch (\Exception $e) {
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage()
            ]);
        }
    }

    /**
     * Mostrar un solicitud_ingreso
     *
     * @param SolicitudIngreso $solicitud_ingreso
     * @return JsonResponse
     */
    public function show(SolicitudIngreso $solicitud_ingreso): JsonResponse
    {
        return response()->JSON([
            "solicitud_ingreso" => $solicitud_ingreso->load(["solicitud_ingreso_detalles.producto"]),
        ]);
    }

    public function update(SolicitudIngreso $solicitud_ingreso, SolicitudIngresoUpdateRequest $request)
    {
        DB::beginTransaction();
        try {
            // actualizar solicitud_ingreso
            $this->solicitud_ingresoService->actualizar($request->validated(), $solicitud_ingreso);
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
     * Eliminar solicitud_ingreso
     *
     * @param SolicitudIngreso $solicitud_ingreso
     * @return JsonResponse|Response
     */
    public function destroy(SolicitudIngreso $solicitud_ingreso): JsonResponse|Response
    {
        DB::beginTransaction();
        try {
            $this->solicitud_ingresoService->eliminar($solicitud_ingreso);
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
