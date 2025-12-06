<?php

namespace App\Http\Controllers;

use App\Http\Requests\DevolucionStockAprobarRequest;
use App\Http\Requests\DevolucionStockStoreRequest;
use App\Http\Requests\DevolucionStockUpdateRequest;
use App\Models\DevolucionStock;
use App\Services\DevolucionStockService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\ValidationException;

class DevolucionStockController extends Controller
{
    public function __construct(private DevolucionStockService $devolucion_stockService) {}

    /**
     * Listado de devolucion_stocks sin ids: 1 y 2
     *
     * @return JsonResponse
     */
    public function listado(): JsonResponse
    {
        return response()->JSON([
            "devolucion_stocks" => $this->devolucion_stockService->listado()
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

        $devolucion_stocks = $this->devolucion_stockService->listadoPaginado($perPage, $page, $search, $columnsSerachLike, $columnsFilter, $columnsBetweenFilter, $arrayOrderBy);
        return response()->JSON([
            "data" => $devolucion_stocks->items(),
            "total" => $devolucion_stocks->total(),
            "lastPage" => $devolucion_stocks->lastPage()
        ]);
    }


    /**
     * Endpoint para obtener la lista de devolucion_stocks paginado para datatable
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function api(Request $request): JsonResponse
    {

        return response()->JSON([]);
    }

    /**
     * Registrar un nuevo devolucion_stock
     *
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function store(Request $request): Response|JsonResponse
    {
        try {

            return Cache::lock("devolucionStore")->block(10, function () use ($request) {
                $request = app(DevolucionStockStoreRequest::class);
                DB::beginTransaction();
                try {
                    $this->devolucion_stockService->crear($request->validated());
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
     * Mostrar un devolucion_stock
     *
     * @param DevolucionStock $devolucion_stock
     * @return JsonResponse
     */
    public function show(DevolucionStock $devolucion_stock): JsonResponse
    {
        return response()->JSON([
            "devolucion_stock" => $devolucion_stock->load(["devolucion_stock_detalles.producto"]),
        ]);
    }

    public function update(DevolucionStock $devolucion_stock, DevolucionStockUpdateRequest $request)
    {
        DB::beginTransaction();
        try {
            // actualizar devolucion_stock
            $this->devolucion_stockService->actualizar($request->validated(), $devolucion_stock);
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

    public function aprobar(DevolucionStock $devolucion_stock, DevolucionStockAprobarRequest $request)
    {
        DB::beginTransaction();
        try {
            // aprobar devolucion_stock
            $this->devolucion_stockService->aprobar($request->validated(), $devolucion_stock);
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
     * Eliminar devolucion_stock
     *
     * @param DevolucionStock $devolucion_stock
     * @return JsonResponse|Response
     */
    public function destroy(DevolucionStock $devolucion_stock): JsonResponse|Response
    {
        DB::beginTransaction();
        try {
            $this->devolucion_stockService->eliminar($devolucion_stock);
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
