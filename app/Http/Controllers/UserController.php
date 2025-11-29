<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Inscripcion;
use App\Models\Material;
use App\Models\Postulante;
use App\Models\Producto;
use App\Models\Publicacion;
use App\Models\Tarea;
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
                    'cantidad' => User::where('id', '!=', 1)->where("tipo", "!=", "POSTULANTE")->where("status", 1)->count(),
                    'color' => 'bg-principal',
                    'icon' => "fa-users",
                    "url" => "usuarios.index"
                ];
            }

            if ($permisos == '*' || (is_array($permisos) && in_array('postulantes.index', $permisos))) {
                $total = Inscripcion::where("status", 1)->count();
                $array_infos[] = [
                    'label' => 'POSTULANTES',
                    'cantidad' => $total,
                    'color' => 'bg-principal',
                    'icon' => "fa-list",
                    "url" => "postulantes.index"
                ];
            }
        }


        return $array_infos;
    }
}
