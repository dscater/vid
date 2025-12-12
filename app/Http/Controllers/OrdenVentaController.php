<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrdenVentaAprobarRequest;
use App\Http\Requests\OrdenVentaStoreRequest;
use App\Http\Requests\OrdenVentaUpdateRequest;
use App\Models\OrdenVenta;
use App\Services\OrdenVentaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\ValidationException;
use App\library\numero_a_letras\src\NumeroALetras;
use App\Services\CuentaCobrarService;
use Exception;

class OrdenVentaController extends Controller
{
    public function __construct(private OrdenVentaService $orden_ventaService, private CuentaCobrarService $cuenta_cobrar_service) {}

    public function sincronizar(Request $request)
    {
        DB::beginTransaction();
        try {
            if (!isset($request->orden_venta)) {
                throw new Exception("Orden de Venta: No se encontró ningun registro");
            }
            $registro = $request->orden_venta;
            $cuenta_cobrar = $request->cuenta_cobrar;
            Cache::lock('ordenVentaStore', 10)->block(5, function () use ($registro, $cuenta_cobrar) {
                $orden_venta = $this->orden_ventaService->crear($registro);
                if ($cuenta_cobrar) {
                    // $nueva_cuenta_cobrar = $this->cuenta_cobrar_service->nuevo($orden_venta);
                    foreach ($cuenta_cobrar["cuenta_cobrar_detalles"] as $item_cc) {
                        $this->cuenta_cobrar_service->pagoByOrden($item_cc, $orden_venta);
                    }
                }
            });
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
     * Listado de orden_ventas sin ids: 1 y 2
     *
     * @return JsonResponse
     */
    public function listado(): JsonResponse
    {
        return response()->JSON([
            "orden_ventas" => $this->orden_ventaService->listado()
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
        // Log::debug($arrayOrderBy);

        $orden_ventas = $this->orden_ventaService->listadoPaginado($perPage, $page, $search, $columnsSerachLike, $columnsFilter, $columnsBetweenFilter, $arrayOrderBy);
        return response()->JSON([
            "data" => $orden_ventas->items(),
            "total" => $orden_ventas->total(),
            "lastPage" => $orden_ventas->lastPage()
        ]);
    }


    /**
     * Endpoint para obtener la lista de orden_ventas paginado para datatable
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function api(Request $request): JsonResponse
    {

        return response()->JSON([]);
    }

    /**
     * Registrar un nuevo orden_venta
     *
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function store(Request $request): Response|JsonResponse
    {
        try {

            return Cache::lock("ordenVentaStore")->block(10, function () use ($request) {
                $request = app(OrdenVentaStoreRequest::class);
                DB::beginTransaction();
                try {
                    $this->orden_ventaService->crear($request->validated());
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
     * Mostrar un orden_venta
     *
     * @param OrdenVenta $orden_venta
     * @return JsonResponse
     */
    public function show(OrdenVenta $orden_venta): JsonResponse
    {
        $convertir = new NumeroALetras();
        $array_monto = explode('.', $orden_venta->total_f);
        $literal = $convertir->convertir($array_monto[0]);
        $literal .= " " . $array_monto[1];
        $literal = strtolower($literal);
        $literal = ucfirst($literal) . "/100." . " Bolivianos";

        return response()->JSON([
            "orden_venta" => $orden_venta->load(["orden_venta_detalles.producto:id,nombre", "orden_venta_detalles.unidad_medida:id,nombre", "cliente:id,razon_social,nit", "user:id,nombre,paterno,materno"]),
            "literal" => $literal
        ]);
    }

    public function update(OrdenVenta $orden_venta, OrdenVentaUpdateRequest $request)
    {
        DB::beginTransaction();
        try {
            // actualizar orden_venta
            $this->orden_ventaService->actualizar($request->validated(), $orden_venta);
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

    public function aprobar(OrdenVenta $orden_venta, OrdenVentaAprobarRequest $request)
    {
        DB::beginTransaction();
        try {
            // aprobar orden_venta
            $orden_venta = $this->orden_ventaService->aprobar($request->validated(), $orden_venta);
            DB::commit();
            return response()->JSON([
                "sw" => true,
                "orden_venta" => $orden_venta,
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
     * Eliminar orden_venta
     *
     * @param OrdenVenta $orden_venta
     * @return JsonResponse|Response
     */
    public function destroy(OrdenVenta $orden_venta): JsonResponse|Response
    {
        DB::beginTransaction();
        try {
            $this->orden_ventaService->eliminar($orden_venta);
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
