<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProformaAprobarRequest;
use App\Http\Requests\ProformaStoreRequest;
use App\Http\Requests\ProformaUpdateRequest;
use App\Models\Proforma;
use App\Services\ProformaService;
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
use App\Models\ProformaDetalle;
use App\Services\OrdenVentaService;

class ProformaController extends Controller
{
    public function __construct(private ProformaService $proformaService, private OrdenVentaService $orden_venta_service) {}

    public function sincronizar(Request $request)
    {
        DB::beginTransaction();
        try {
            if (!isset($request->proformas)) {
                throw new Exception("Proforma: No se encontraron registros para sincronizar");
            }
            foreach ($request->proformas as $registro) {
                Cache::lock('proformaStore', 10)->block(5, function () use ($registro) {
                    $this->proformaService->crear($registro);
                });
            }
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
     * Listado de proformas sin ids: 1 y 2
     *
     * @return JsonResponse
     */
    public function listado(): JsonResponse
    {
        return response()->JSON([
            "proformas" => $this->proformaService->listado()
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
            "codigo"
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

        $proformas = $this->proformaService->listadoPaginado($perPage, $page, $search, $columnsSerachLike, $columnsFilter, $columnsBetweenFilter, $arrayOrderBy);
        return response()->JSON([
            "data" => $proformas->items(),
            "total" => $proformas->total(),
            "lastPage" => $proformas->lastPage()
        ]);
    }


    /**
     * Endpoint para obtener la lista de proformas paginado para datatable
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function api(Request $request): JsonResponse
    {

        return response()->JSON([]);
    }

    /**
     * Registrar un nuevo proforma
     *
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function store(Request $request): Response|JsonResponse
    {
        try {

            return Cache::lock("proformaStore")->block(10, function () use ($request) {
                $request = app(ProformaStoreRequest::class);
                DB::beginTransaction();
                try {
                    $proforma = $this->proformaService->crear($request->validated());
                    DB::commit();
                    return response()->JSON([
                        "proforma" => $proforma,
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
     * Mostrar un proforma
     *
     * @param Proforma $proforma
     * @return JsonResponse
     */
    public function show(Proforma $proforma): JsonResponse
    {

        return response()->JSON([
            "proforma" => $proforma->load(["proforma_productos.producto.unidad_medida", "proforma_detalles.proforma_detalle_productos.producto.unidad_medida", "proforma_detalles.cliente",  "user:id,nombre,paterno,materno"]),
        ]);
    }

    public function update(Proforma $proforma, ProformaUpdateRequest $request)
    {
        DB::beginTransaction();
        try {
            // actualizar proforma
            $proforma = $this->proformaService->actualizar($request->validated(), $proforma);
            DB::commit();
            return response()->JSON([
                "proforma" => $proforma,
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

    public function aprobar(Proforma $proforma, ProformaAprobarRequest $request)
    {
        DB::beginTransaction();
        try {
            // aprobar proforma
            $proforma = $this->proformaService->aprobar($request->validated(), $proforma);
            DB::commit();
            return response()->JSON([
                "sw" => true,
                "proforma" => $proforma,
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
     * Eliminar proforma
     *
     * @param Proforma $proforma
     * @return JsonResponse|Response
     */
    public function destroy(Proforma $proforma): JsonResponse|Response
    {
        DB::beginTransaction();
        try {
            $this->proformaService->eliminar($proforma);
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

    public function crearOrdenVenta(Request $request)
    {
        $proforma_detalle_id = $request->proforma_detalle_id;
        $sucursal_id = $request->sucursal_id;

        DB::beginTransaction();
        try {

            $proforma_detalle = ProformaDetalle::findOrFail($proforma_detalle_id);

            // generar total de proforma_detalle->proforma_detalle_productos
            $total = 0;
            $orden_venta_detalles = [];
            foreach ($proforma_detalle->proforma_detalle_productos as $pdp) {
                if ($pdp->cantidad) {
                    $subtotal = (float)$pdp->producto->precio * (float)$pdp->cantidad;
                    $total += (float)$subtotal;
                    $pdp->cantidad_entregada = $pdp->cantidad;
                    $pdp->verificado = 1;
                    $pdp->save();
                    $orden_venta_detalles[] = [
                        "producto_id" => $pdp->producto->id,
                        "unidad_medida_id" => $pdp->producto->unidad_medida_id,
                        "cantidad" => $pdp->cantidad,
                        "precio" => $pdp->producto->precio,
                        "subtotal" => $subtotal,
                        "descuento" => 0,
                        "subtotal_f" => $subtotal
                    ];
                }
            }

            $proforma_detalle->verificado = 1;
            $proforma_detalle->estado = "ATENDIDO";
            $proforma_detalle->cantidad_entregada = $proforma_detalle->cantidad;
            $proforma_detalle->save();

            $datos = [
                "sucursal_id" => $sucursal_id,
                "cliente_id" => $proforma_detalle->cliente_id,
                "fecha" => date("Y-m-d"),
                "hora" => date("H:i:s"),
                "cantidad_total" => $proforma_detalle->cantidad,
                "cs_f" => "CON FACTURA",
                "forma_pago" => "",
                "con" => 1,
                "cancelado_c" => 0,
                "qr" => 0,
                "cancelado_qr" => 0,
                "cre" => 0,
                "credito" => 0,
                "cancelado" => 0,
                "total" => $total,
                "total_st" => $total,
                "solicitud_descuento" => 0,
                "total_f" => $total,
                "estado" => "EN ESPERA",
                "verificado" => 5,
                "user_id" => Auth::user()->id,
                "orden_venta_detalles" => $orden_venta_detalles
            ];

            $orden_venta = $this->orden_venta_service->crear($datos);
            DB::commit();
            return response()->JSON([
                "sw" => true,
                "orden_venta" => $orden_venta,
                "message" => "Proceso realizado con éxito"
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }
}
