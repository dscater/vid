<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClienteStoreRequest;
use App\Http\Requests\ClienteUpdateRequest;
use App\Models\HistorialAccion;
use App\Models\Modulo;
use App\Models\Permiso;
use App\Models\Cliente;
use App\Models\User;
use App\Services\ClienteService;
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

class ClienteController extends Controller
{
    public function __construct(private ClienteService $clienteService) {}

    /**
     * Listado de clientes sin ids: 1 y 2
     *
     * @return JsonResponse
     */
    public function listado(): JsonResponse
    {
        return response()->JSON([
            "clientes" => $this->clienteService->listado()
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

        $clientes = $this->clienteService->listadoPaginado($perPage, $page, $search, $columnsSerachLike, $columnsFilter, $columnsBetweenFilter, $arrayOrderBy);
        return response()->JSON([
            "data" => $clientes->items(),
            "total" => $clientes->total(),
            "lastPage" => $clientes->lastPage()
        ]);
    }


    /**
     * Endpoint para obtener la lista de clientes paginado para datatable
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function api(Request $request): JsonResponse
    {

        return response()->JSON([]);
    }

    /**
     * Registrar un nuevo cliente
     *
     * @param ClienteStoreRequest $request
     * @return RedirectResponse|Response
     */
    public function store(ClienteStoreRequest $request): Response|JsonResponse
    {
        DB::beginTransaction();
        try {
            // crear el Cliente
            $this->clienteService->crear($request->validated());
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
     * Mostrar un cliente
     *
     * @param Cliente $cliente
     * @return JsonResponse
     */
    public function show(Cliente $cliente): JsonResponse
    {
        return response()->JSON($cliente);
    }
    public function actualizaPermiso(Cliente $cliente, Request $request)
    {
        $sw_cambio = $request->sw_cambio;
        $modulo = $request->modulo;
        $accion = $request->accion;
        $o_modulo = Modulo::where("modulo", $modulo)->where("accion", $accion)->get()->first();
        $permiso = Permiso::where("cliente_id", $cliente->id)
            ->where("modulo_id", $o_modulo->id)
            ->get()->first();
        if ($sw_cambio == 1) {
            if (!$permiso) {
                $cliente->o_permisos()->create([
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
            ->where("cliente_id", $cliente->id)
            ->where("modulos.modulo", $o_modulo->modulo)->get();

        return response()->JSON([
            "array_permisos" => $array_permisos
        ]);
    }

    public function update(Cliente $cliente, ClienteUpdateRequest $request)
    {
        DB::beginTransaction();
        try {
            // actualizar cliente
            $this->clienteService->actualizar($request->validated(), $cliente);
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
     * Eliminar cliente
     *
     * @param Cliente $cliente
     * @return JsonResponse|Response
     */
    public function destroy(Cliente $cliente): JsonResponse|Response
    {
        DB::beginTransaction();
        try {
            $this->clienteService->eliminar($cliente);
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
