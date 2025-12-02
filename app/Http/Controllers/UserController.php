<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
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
            if ($permisos == '*' || (is_array($permisos) && in_array('usuarios.index', $permisos))) {
                $array_infos[] = [
                    'label' => 'USUARIOS',
                    'cantidad' => User::where('id', '!=', 1)->count(),
                    'color' => 'bg-secundario',
                    'icon' => "fa-users",
                    "url" => "usuarios.index"
                ];
            }

            if ($permisos == '*' || (is_array($permisos) && in_array('clientes.index', $permisos))) {
                $total = Cliente::count();
                $array_infos[] = [
                    'label' => 'CLIENTES',
                    'cantidad' => $total,
                    'color' => 'bg-secundario',
                    'icon' => "fa-list",
                    "url" => "clientes.index"
                ];
            }

            if ($permisos == '*' || (is_array($permisos) && in_array('proveedors.index', $permisos))) {
                $total = Cliente::count();
                $array_infos[] = [
                    'label' => 'PROVEEDORES',
                    'cantidad' => $total,
                    'color' => 'bg-secundario',
                    'icon' => "fa-list",
                    "url" => "proveedors.index"
                ];
            }

            if ($permisos == '*' || (is_array($permisos) && in_array('productos.index', $permisos))) {
                $total = Cliente::count();
                $array_infos[] = [
                    'label' => 'PRODUCTOS',
                    'cantidad' => $total,
                    'color' => 'bg-secundario',
                    'icon' => "fa-list",
                    "url" => "productos.index"
                ];
            }
        }


        return $array_infos;
    }
}
