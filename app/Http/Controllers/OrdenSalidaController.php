<?php

namespace App\Http\Controllers;


use App\Http\Requests\OrdenSalidaAprobarRequest;
use App\Http\Requests\OrdenSalidaStoreRequest;
use App\Http\Requests\OrdenSalidaUpdateRequest;
use App\Models\OrdenSalida;
use App\Services\OrdenSalidaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\ValidationException;

class OrdenSalidaController extends Controller
{
    public function __construct(private OrdenSalidaService $orden_salidaService) {}

    /**
     * Listado de orden_salidas sin ids: 1 y 2
     *
     * @return JsonResponse
     */
    public function listado(): JsonResponse
    {
        return response()->JSON([
            "orden_salidas" => $this->orden_salidaService->listado()
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

        $orden_salidas = $this->orden_salidaService->listadoPaginado($perPage, $page, $search, $columnsSerachLike, $columnsFilter, $columnsBetweenFilter, $arrayOrderBy);
        return response()->JSON([
            "data" => $orden_salidas->items(),
            "total" => $orden_salidas->total(),
            "lastPage" => $orden_salidas->lastPage()
        ]);
    }


    /**
     * Endpoint para obtener la lista de orden_salidas paginado para datatable
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function api(Request $request): JsonResponse
    {

        return response()->JSON([]);
    }

    /**
     * Registrar un nuevo orden_salida
     *
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function store(Request $request): Response|JsonResponse
    {
        try {

            return Cache::lock("ordenSalidaStore")->block(10, function () use ($request) {
                $request = app(OrdenSalidaStoreRequest::class);
                DB::beginTransaction();
                try {
                    $this->orden_salidaService->crear($request->validated());
                    DB::commit();
                    return response()->JSON([
                        "sw" => true,
                        "message" => "Proceso realizado con éxito"
                    ]);
                } catch (\Exception $e) {
                    DB::rollBack();
                    throw ValidationException::withMessages([
                        'error' =>  $e->getMessage()
                    ]);
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
     * Mostrar un orden_salida
     *
     * @param OrdenSalida $orden_salida
     * @return JsonResponse
     */
    public function show(OrdenSalida $orden_salida): JsonResponse
    {
        return response()->JSON([
            "orden_salida" => $orden_salida->load(["orden_salida_detalles.producto"]),
        ]);
    }

    public function update(OrdenSalida $orden_salida, OrdenSalidaUpdateRequest $request)
    {
        DB::beginTransaction();
        try {
            // actualizar orden_salida
            $this->orden_salidaService->actualizar($request->validated(), $orden_salida);
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

    public function aprobar(OrdenSalida $orden_salida, OrdenSalidaAprobarRequest $request)
    {
        DB::beginTransaction();
        try {
            // aprobar orden_salida
            $this->orden_salidaService->aprobar($request->validated(), $orden_salida);
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
     * Eliminar orden_salida
     *
     * @param OrdenSalida $orden_salida
     * @return JsonResponse|Response
     */
    public function destroy(OrdenSalida $orden_salida): JsonResponse|Response
    {
        DB::beginTransaction();
        try {
            $this->orden_salidaService->eliminar($orden_salida);
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
