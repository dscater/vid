<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\InicioController;
use App\Http\Controllers\MarcaController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SolicitudIngresoController;
use App\Http\Controllers\SubCategoriaController;
use App\Http\Controllers\SucursalController;
use App\Http\Controllers\SucursalProductoController;
use App\Http\Controllers\UnidadMedidaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::post('/me', [AuthController::class, 'me']);

Route::get("configuracions/getConfiguracion", [ConfiguracionController::class, 'getConfiguracion'])->name("configuracions.getConfiguracion");

Route::middleware(['auth:api'])->get('/perfil', function () {
    return auth()->user();
});

Route::get("/authCheck", [AuthController::class, 'authCheck']);

Route::middleware(['auth:api'])->prefix("admin")->group(function () {
    Route::get('inicio', [InicioController::class, 'inicio'])->name('inicio');
    // CONFIGURACION
    Route::resource("configuracions", ConfiguracionController::class)->only(
        ["show", "update"]
    );

    // USUARIO
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('profile/update_foto', [ProfileController::class, 'update_foto'])->name('profile.update_foto');
    Route::delete('profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get("getUser", [UserController::class, 'getUser'])->name('users.getUser');
    Route::get("permisosUsuario", [UserController::class, 'permisosUsuario']);

    // USUARIOS
    Route::put("usuarios/password/{user}", [UsuarioController::class, 'actualizaPassword'])->name("usuarios.password");
    Route::get("usuarios/paginado", [UsuarioController::class, 'paginado'])->name("usuarios.paginado");
    Route::get("usuarios/listado", [UsuarioController::class, 'listado'])->name("usuarios.listado");
    Route::get("usuarios/listado/byTipo", [UsuarioController::class, 'byTipo'])->name("usuarios.byTipo");
    Route::get("usuarios/show/{user}", [UsuarioController::class, 'show'])->name("usuarios.show");
    Route::put("usuarios/update/{user}", [UsuarioController::class, 'update'])->name("usuarios.update");
    Route::delete("usuarios/{user}", [UsuarioController::class, 'destroy'])->name("usuarios.destroy");
    Route::resource("usuarios", UsuarioController::class)->only(
        ["store"]
    );

    // ROLES
    Route::get("roles/api", [RoleController::class, 'api'])->name("roles.api");
    Route::get("roles/paginado", [RoleController::class, 'paginado'])->name("roles.paginado");
    Route::get("roles/listado", [RoleController::class, 'listado'])->name("roles.listado");
    Route::post("roles/actualizaPermiso/{role}", [RoleController::class, 'actualizaPermiso'])->name("roles.actualizaPermiso");
    Route::resource("roles", RoleController::class)->only(
        ["store", "show", "update", "destroy"]
    );

    // SUCURSALES
    Route::get("sucursals/api", [SucursalController::class, 'api'])->name("sucursals.api");
    Route::get("sucursals/paginado", [SucursalController::class, 'paginado'])->name("sucursals.paginado");
    Route::get("sucursals/listado", [SucursalController::class, 'listado'])->name("sucursals.listado");
    Route::get("sucursals/listadoSP", [SucursalController::class, 'listadoSP'])->name("sucursals.listadoSP");
    Route::resource("sucursals", SucursalController::class)->only(
        ["index", "store", "edit", "show", "update", "destroy"]
    );

    // SUCURSAL PRODUCTOS
    Route::get("sucursal_productos/getSucursalProducto", [SucursalProductoController::class, 'getSucursalProducto'])->name("sucursal_productos.getSucursalProducto");
    Route::get("sucursal_productos/getSucursalProductos", [SucursalProductoController::class, 'getSucursalProductos'])->name("sucursal_productos.getSucursalProductos");
    Route::get("sucursal_productos/paginado", [SucursalProductoController::class, 'paginado'])->name("sucursal_productos.paginado");
    Route::get("sucursal_productos/listado", [SucursalProductoController::class, 'listado'])->name("sucursal_productos.listado");
    Route::put("sucursal_productos/{sucursal_producto}", [SucursalProductoController::class, 'update'])->name("sucursal_productos.update");

    // CATEGORIAS
    Route::get("categorias/api", [CategoriaController::class, 'api'])->name("categorias.api");
    Route::get("categorias/paginado", [CategoriaController::class, 'paginado'])->name("categorias.paginado");
    Route::get("categorias/listado", [CategoriaController::class, 'listado'])->name("categorias.listado");
    Route::resource("categorias", CategoriaController::class)->only(
        ["index", "store", "edit", "show", "update", "destroy"]
    );

    // SUBCATEGORIAS
    Route::get("sub_categorias/api", [SubCategoriaController::class, 'api'])->name("sub_categorias.api");
    Route::get("sub_categorias/paginado", [SubCategoriaController::class, 'paginado'])->name("sub_categorias.paginado");
    Route::get("sub_categorias/listado", [SubCategoriaController::class, 'listado'])->name("sub_categorias.listado");
    Route::resource("sub_categorias", SubCategoriaController::class)->only(
        ["index", "store", "edit", "show", "update", "destroy"]
    );

    // MARCAS
    Route::get("marcas/api", [MarcaController::class, 'api'])->name("marcas.api");
    Route::get("marcas/paginado", [MarcaController::class, 'paginado'])->name("marcas.paginado");
    Route::get("marcas/listado", [MarcaController::class, 'listado'])->name("marcas.listado");
    Route::resource("marcas", MarcaController::class)->only(
        ["index", "store", "edit", "show", "update", "destroy"]
    );

    // UNIDADES DE MEDIDA
    Route::get("unidad_medidas/api", [UnidadMedidaController::class, 'api'])->name("unidad_medidas.api");
    Route::get("unidad_medidas/paginado", [UnidadMedidaController::class, 'paginado'])->name("unidad_medidas.paginado");
    Route::get("unidad_medidas/listado", [UnidadMedidaController::class, 'listado'])->name("unidad_medidas.listado");
    Route::resource("unidad_medidas", UnidadMedidaController::class)->only(
        ["index", "store", "edit", "show", "update", "destroy"]
    );

    // PRODUCTOS
    Route::get("productos/byCodigo", [ProductoController::class, 'byCodigo'])->name("productos.byCodigo");
    Route::get("productos/api", [ProductoController::class, 'api'])->name("productos.api");
    Route::get("productos/paginado", [ProductoController::class, 'paginado'])->name("productos.paginado");
    Route::get("productos/listado", [ProductoController::class, 'listado'])->name("productos.listado");
    Route::resource("productos", ProductoController::class)->only(
        ["index", "store", "edit", "show", "update", "destroy"]
    );

    // CLIENTES
    Route::get("clientes/api", [ClienteController::class, 'api'])->name("clientes.api");
    Route::get("clientes/paginado", [ClienteController::class, 'paginado'])->name("clientes.paginado");
    Route::get("clientes/listado", [ClienteController::class, 'listado'])->name("clientes.listado");
    Route::resource("clientes", ClienteController::class)->only(
        ["index", "store", "edit", "show", "update", "destroy"]
    );

    // PROVEEDORES
    Route::get("proveedors/api", [ProveedorController::class, 'api'])->name("proveedors.api");
    Route::get("proveedors/paginado", [ProveedorController::class, 'paginado'])->name("proveedors.paginado");
    Route::get("proveedors/listado", [ProveedorController::class, 'listado'])->name("proveedors.listado");
    Route::resource("proveedors", ProveedorController::class)->only(
        ["index", "store", "edit", "show", "update", "destroy"]
    );

    // SOLICITUD DE INGRESOS
    Route::get("solicitud_ingresos/api", [SolicitudIngresoController::class, 'api'])->name("solicitud_ingresos.api");
    Route::get("solicitud_ingresos/paginado", [SolicitudIngresoController::class, 'paginado'])->name("solicitud_ingresos.paginado");
    Route::get("solicitud_ingresos/listado", [SolicitudIngresoController::class, 'listado'])->name("solicitud_ingresos.listado");
    Route::put("solicitud_ingresos/aprobar/{solicitud_ingreso}", [SolicitudIngresoController::class, 'aprobar'])->name("solicitud_ingresos.aprobar");
    Route::resource("solicitud_ingresos", SolicitudIngresoController::class)->only(
        ["index", "store", "edit", "show", "update", "destroy"]
    );

    // REPORTES
    Route::get('reportes/r_usuarios', [ReporteController::class, 'r_usuarios'])->name("reportes.r_usuarios");
});
