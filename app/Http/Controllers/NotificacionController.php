<?php

namespace App\Http\Controllers;

use App\Jobs\RecalcularRankingClientes;
use App\Models\HistorialAccion;
use App\Models\Notificacion;
use App\Models\NotificacionUser;
use App\Models\User;
use App\Services\ClienteService;
use App\Services\NotificacionService;
use App\Services\ParametroClienteService;
use App\Services\ParametroNotificacionService;
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

class NotificacionController extends Controller
{
    public function __construct(private NotificacionService $notificacionService, private ClienteService $cliente_service, private ParametroClienteService $parametro_cliente_service) {}

    /**
     * Listado de notificacions sin ids: 1 y 2
     *
     * @return JsonResponse
     */
    public function listado(): JsonResponse
    {
        return response()->JSON([
            "notificacions" => $this->notificacionService->listado()
        ]);
    }

    public function listadoByUser(): JsonResponse
    {
        return response()->JSON([
            "notificacions" => $this->notificacionService->listadoByUser(Auth::user()->id)
        ]);
    }

    public function listadoByUserNoVisto(): JsonResponse
    {
        // RecalcularRankingClientes::dispatch($this->parametro_cliente_service);
        $this->cliente_service->verificarCreditoClientes();
        return response()->JSON([
            "notificacions" => $this->notificacionService->listadoByUserNoVisto(Auth::user()->id)
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

        $notificacions = $this->notificacionService->listadoPaginado($perPage, $page, $search, $columnsSerachLike, $columnsFilter, $columnsBetweenFilter, $arrayOrderBy);

        return response()->JSON([
            "data" => $notificacions->items(),
            "total" => $notificacions->total(),
            "lastPage" => $notificacions->lastPage()
        ]);
    }

    /**
     * Mostrar un notificacion
     *
     * @param Notificacion $notificacion
     * @return JsonResponse
     */
    public function show(Notificacion $notificacion): JsonResponse
    {

        $notificacion_user = NotificacionUser::where("user_id", Auth::user()->id)
            ->where("notificacion_id", $notificacion->id)
            ->get()
            ->first();
        if ($notificacion_user) {
            $notificacion_user->visto = 1;
            $notificacion_user->save();
        }


        return response()->JSON($notificacion);
    }
}
