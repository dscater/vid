<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\OrdenVenta;
use App\Models\Proforma;
use App\Models\SolicitudIngreso;
use App\Models\Sucursal;
use App\Models\User;
use App\Services\PermisoService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{

    public function permisosUsuario(Request $request)
    {
        $permisoService = new PermisoService();
        return response()->JSON([
            "permisos" => $permisoService->getPermisosUser()
        ]);
    }

    public function getUser()
    {
        return response()->JSON([
            "user" => Auth::user()
        ]);
    }

    public static function getInfoBoxUser()
    {
        $permisos = [];
        $array_infos = [];
        if (Auth::check()) {
            $oUser = new User();
            $permisos = $oUser->permisos;
            if ($permisos == '*' || (is_array($permisos) && in_array('sucursals.index', $permisos))) {
                $total = Sucursal::count();
                $array_infos[] = [
                    'label' => 'SUCURSALES',
                    'cantidad' => $total,
                    'color' => 'bg-secundario',
                    'icon' => "fa-users",
                    "url" => "sucursals.index"
                ];
            }

            if ($permisos == '*' || (is_array($permisos) && in_array('proformas.index', $permisos))) {
                $total = Proforma::count();
                $array_infos[] = [
                    'label' => 'PROFORMAS',
                    'cantidad' => $total,
                    'color' => 'bg-secundario',
                    'icon' => "fa-list",
                    "url" => "proformas.index"
                ];
            }

            if ($permisos == '*' || (is_array($permisos) && in_array('orden_ventas.index', $permisos))) {
                $total = OrdenVenta::count();
                $array_infos[] = [
                    'label' => 'VENTAS',
                    'cantidad' => $total,
                    'color' => 'bg-secundario',
                    'icon' => "fa-list",
                    "url" => "orden_ventas.index"
                ];
            }

            if ($permisos == '*' || (is_array($permisos) && in_array('solicitud_ingresos.index', $permisos))) {
                $total = SolicitudIngreso::count();
                $array_infos[] = [
                    'label' => 'COMPRAS',
                    'cantidad' => $total,
                    'color' => 'bg-secundario',
                    'icon' => "fa-list",
                    "url" => "solicitud_ingresos.index"
                ];
            }
        }


        return $array_infos;
    }
}
