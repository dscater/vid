<?php

use App\Http\Controllers\AjusteController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\CuentaCobrarController;
use App\Http\Controllers\DevolucionClienteController;
use App\Http\Controllers\DevolucionStockController;
use App\Http\Controllers\GastoController;
use App\Http\Controllers\InicioController;
use App\Http\Controllers\MarcaController;
use App\Http\Controllers\NotificacionController;
use App\Http\Controllers\OrdenSalidaController;
use App\Http\Controllers\OrdenVentaController;
use App\Http\Controllers\ParametroClienteController;
use App\Http\Controllers\ParametroSucursalController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProformaController;
use App\Http\Controllers\ProformaDetalleProductoController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SolicitudIngresoController;
use App\Http\Controllers\SubCategoriaController;
use App\Http\Controllers\SucursalController;
use App\Http\Controllers\SucursalProductoController;
use App\Http\Controllers\TransferenciaController;
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

// SINCRONIZAR
Route::post("orden_ventas/sincronizar", [OrdenVentaController::class, 'sincronizar'])->name("orden_ventas.sincronizar");

Route::post("cuenta_cobrars/sincronizar", [CuentaCobrarController::class, 'sincronizar'])->name("cuenta_cobrars.sincronizar");

Route::post("clientes/sincronizar", [ClienteController::class, 'sincronizar'])->name("clientes.sincronizar");

Route::post("proformas/sincronizar", [ProformaController::class, 'sincronizar'])->name("proformas.sincronizar");

Route::post("devolucion_clientes/sincronizar", [DevolucionClienteController::class, 'sincronizar'])->name("devolucion_clientes.sincronizar");

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
        ["store", "edit", "show", "update", "destroy"]
    );

    // parametro sucursal
    Route::get("parametro_sucursals", [ParametroSucursalController::class, 'index'])->name("parametro_sucursals.index");
    Route::post("parametro_sucursals", [ParametroSucursalController::class, 'store'])->name("parametro_sucursals.store");

    // SUCURSAL PRODUCTOS
    Route::get("sucursal_productos/getSucursalProducto", [SucursalProductoController::class, 'getSucursalProducto'])->name("sucursal_productos.getSucursalProducto");
    Route::get("sucursal_productos/getSucursalProductos", [SucursalProductoController::class, 'getSucursalProductos'])->name("sucursal_productos.getSucursalProductos");
    Route::get("sucursal_productos/paginado", [SucursalProductoController::class, 'paginado'])->name("sucursal_productos.paginado");
    Route::get("sucursal_productos/listado", [SucursalProductoController::class, 'listado'])->name("sucursal_productos.listado");
    Route::get("sucursal_productos/listadoSucursales", [SucursalProductoController::class, 'listadoSucursales'])->name("sucursal_productos.listadoSucursales");
    Route::put("sucursal_productos/{sucursal_producto}", [SucursalProductoController::class, 'update'])->name("sucursal_productos.update");

    // CATEGORIAS
    Route::get("categorias/api", [CategoriaController::class, 'api'])->name("categorias.api");
    Route::get("categorias/paginado", [CategoriaController::class, 'paginado'])->name("categorias.paginado");
    Route::get("categorias/listado", [CategoriaController::class, 'listado'])->name("categorias.listado");
    Route::resource("categorias", CategoriaController::class)->only(
        ["store", "edit", "show", "update", "destroy"]
    );

    // SUBCATEGORIAS
    Route::get("sub_categorias/api", [SubCategoriaController::class, 'api'])->name("sub_categorias.api");
    Route::get("sub_categorias/paginado", [SubCategoriaController::class, 'paginado'])->name("sub_categorias.paginado");
    Route::get("sub_categorias/listado", [SubCategoriaController::class, 'listado'])->name("sub_categorias.listado");
    Route::resource("sub_categorias", SubCategoriaController::class)->only(
        ["store", "edit", "show", "update", "destroy"]
    );

    // MARCAS
    Route::get("marcas/api", [MarcaController::class, 'api'])->name("marcas.api");
    Route::get("marcas/paginado", [MarcaController::class, 'paginado'])->name("marcas.paginado");
    Route::get("marcas/listado", [MarcaController::class, 'listado'])->name("marcas.listado");
    Route::resource("marcas", MarcaController::class)->only(
        ["store", "edit", "show", "update", "destroy"]
    );

    // UNIDADES DE MEDIDA
    Route::get("unidad_medidas/api", [UnidadMedidaController::class, 'api'])->name("unidad_medidas.api");
    Route::get("unidad_medidas/paginado", [UnidadMedidaController::class, 'paginado'])->name("unidad_medidas.paginado");
    Route::get("unidad_medidas/listado", [UnidadMedidaController::class, 'listado'])->name("unidad_medidas.listado");
    Route::resource("unidad_medidas", UnidadMedidaController::class)->only(
        ["store", "edit", "show", "update", "destroy"]
    );

    // PRODUCTOS
    Route::get("productos/byCodigo", [ProductoController::class, 'byCodigo'])->name("productos.byCodigo");
    Route::get("productos/byCodigoListSelectElementUi", [ProductoController::class, 'byCodigoListSelectElementUi'])->name("productos.byCodigoListSelectElementUi");
    Route::get("productos/ppp/{producto}", [ProductoController::class, 'ppp'])->name("productos.ppp");
    Route::post("productos/ppp_update/{producto}", [ProductoController::class, 'ppp_update'])->name("productos.ppp_update");
    Route::get("productos/api", [ProductoController::class, 'api'])->name("productos.api");
    Route::get("productos/paginado", [ProductoController::class, 'paginado'])->name("productos.paginado");
    Route::get("productos/listado", [ProductoController::class, 'listado'])->name("productos.listado");
    Route::resource("productos", ProductoController::class)->only(
        ["store", "edit", "show", "update", "destroy"]
    );

    // CLIENTES
    Route::get("clientes/api", [ClienteController::class, 'api'])->name("clientes.api");
    Route::get("clientes/paginado", [ClienteController::class, 'paginado'])->name("clientes.paginado");
    Route::get("clientes/listadoSelectElementUi", [ClienteController::class, 'listadoSelectElementUi'])->name("clientes.listadoSelectElementUi");
    Route::get("clientes/listado", [ClienteController::class, 'listado'])->name("clientes.listado");
    Route::resource("clientes", ClienteController::class)->only(
        ["store", "edit", "show", "update", "destroy"]
    );

    // parametro cliente
    Route::get("parametro_clientes", [ParametroClienteController::class, 'index'])->name("parametro_clientes.index");
    Route::post("parametro_clientes", [ParametroClienteController::class, 'store'])->name("parametro_clientes.store");

    // PROVEEDORES
    Route::get("proveedors/api", [ProveedorController::class, 'api'])->name("proveedors.api");
    Route::get("proveedors/paginado", [ProveedorController::class, 'paginado'])->name("proveedors.paginado");
    Route::get("proveedors/listado", [ProveedorController::class, 'listado'])->name("proveedors.listado");
    Route::resource("proveedors", ProveedorController::class)->only(
        ["store", "edit", "show", "update", "destroy"]
    );

    // GASTOS
    Route::get("gastos/api", [GastoController::class, 'api'])->name("gastos.api");
    Route::get("gastos/paginado", [GastoController::class, 'paginado'])->name("gastos.paginado");
    Route::get("gastos/listado", [GastoController::class, 'listado'])->name("gastos.listado");
    Route::resource("gastos", GastoController::class)->only(
        ["store", "edit", "show", "update", "destroy"]
    );

    // SOLICITUD DE INGRESOS
    Route::get("solicitud_ingresos/api", [SolicitudIngresoController::class, 'api'])->name("solicitud_ingresos.api");
    Route::get("solicitud_ingresos/paginado", [SolicitudIngresoController::class, 'paginado'])->name("solicitud_ingresos.paginado");
    Route::get("solicitud_ingresos/listado", [SolicitudIngresoController::class, 'listado'])->name("solicitud_ingresos.listado");
    Route::put("solicitud_ingresos/aprobar/{solicitud_ingreso}", [SolicitudIngresoController::class, 'aprobar'])->name("solicitud_ingresos.aprobar");
    Route::put("solicitud_ingresos/aprobar_costos/{solicitud_ingreso}", [SolicitudIngresoController::class, 'aprobar_costos'])->name("solicitud_ingresos.aprobar_costos");
    Route::resource("solicitud_ingresos", SolicitudIngresoController::class)->only(
        ["store", "edit", "show", "update", "destroy"]
    );

    // ORDEN DE SALIDA
    Route::get("orden_salidas/api", [OrdenSalidaController::class, 'api'])->name("orden_salidas.api");
    Route::get("orden_salidas/paginado", [OrdenSalidaController::class, 'paginado'])->name("orden_salidas.paginado");
    Route::get("orden_salidas/listado", [OrdenSalidaController::class, 'listado'])->name("orden_salidas.listado");
    Route::put("orden_salidas/aprobar/{orden_salida}", [OrdenSalidaController::class, 'aprobar'])->name("orden_salidas.aprobar");
    Route::resource("orden_salidas", OrdenSalidaController::class)->only(
        ["store", "edit", "show", "update", "destroy"]
    );

    // DEVOLUCIÓN DE STOCK
    Route::get("devolucion_stocks/api", [DevolucionStockController::class, 'api'])->name("devolucion_stocks.api");
    Route::get("devolucion_stocks/paginado", [DevolucionStockController::class, 'paginado'])->name("devolucion_stocks.paginado");
    Route::get("devolucion_stocks/listado", [DevolucionStockController::class, 'listado'])->name("devolucion_stocks.listado");
    Route::put("devolucion_stocks/aprobar/{devolucion_stock}", [DevolucionStockController::class, 'aprobar'])->name("devolucion_stocks.aprobar");
    Route::resource("devolucion_stocks", DevolucionStockController::class)->only(
        ["store", "edit", "show", "update", "destroy"]
    );

    // ORDEN DE VENTAS
    Route::get("orden_ventas/api", [OrdenVentaController::class, 'api'])->name("orden_ventas.api");
    Route::get("orden_ventas/paginado", [OrdenVentaController::class, 'paginado'])->name("orden_ventas.paginado");
    Route::get("orden_ventas/listado", [OrdenVentaController::class, 'listado'])->name("orden_ventas.listado");
    Route::get("orden_ventas/montoMaximo", [OrdenVentaController::class, 'montoMaximo'])->name("orden_ventas.montoMaximo");
    Route::put("orden_ventas/aprobar/{orden_venta}", [OrdenVentaController::class, 'aprobar'])->name("orden_ventas.aprobar");
    Route::put("orden_ventas/anular/{orden_venta}", [OrdenVentaController::class, 'anular'])->name("orden_ventas.anular");
    Route::resource("orden_ventas", OrdenVentaController::class)->only(
        ["store", "edit", "show", "update", "destroy"]
    );

    // CUENTAS POR COBRAR
    Route::get("cuenta_cobrars/api", [CuentaCobrarController::class, 'api'])->name("cuenta_cobrars.api");
    Route::get("cuenta_cobrars/paginado", [CuentaCobrarController::class, 'paginado'])->name("cuenta_cobrars.paginado");
    Route::get("cuenta_cobrars/listado", [CuentaCobrarController::class, 'listado'])->name("cuenta_cobrars.listado");
    Route::put("cuenta_cobrars/pago/{cuenta_cobrar}", [CuentaCobrarController::class, 'pago'])->name("orden_ventas.pago");
    Route::resource("cuenta_cobrars", CuentaCobrarController::class)->only(
        ["store", "edit", "show", "update", "destroy"]
    );

    // PROFORMAS
    Route::get("proformas/api", [ProformaController::class, 'api'])->name("proformas.api");
    Route::get("proformas/paginado", [ProformaController::class, 'paginado'])->name("proformas.paginado");
    Route::get("proformas/listado", [ProformaController::class, 'listado'])->name("proformas.listado");
    Route::put("proformas/aprobar/{orden_venta}", [ProformaController::class, 'aprobar'])->name("proformas.aprobar");
    Route::resource("proformas", ProformaController::class)->only(
        ["store", "edit", "show", "update", "destroy"]
    );

    // PROFORMAS-DETALLE-PRODUCTO
    Route::put("proforma_detalle_productos/verificar/{proforma_detalle_producto}", [ProformaDetalleProductoController::class, 'verificar'])->name("proforma_detalle_productos.verificar");


    // TRANSFERENCIAS
    Route::get("transferencias/api", [TransferenciaController::class, 'api'])->name("transferencias.api");
    Route::get("transferencias/paginado", [TransferenciaController::class, 'paginado'])->name("transferencias.paginado");
    Route::get("transferencias/listado", [TransferenciaController::class, 'listado'])->name("transferencias.listado");
    Route::put("transferencias/aprobar/{transferencia}", [TransferenciaController::class, 'aprobar'])->name("transferencias.aprobar");
    Route::resource("transferencias", TransferenciaController::class)->only(
        ["store", "edit", "show", "update", "destroy"]
    );

    // DEVOLUCIÓN DE CLIENTES
    Route::get("devolucion_clientes/api", [DevolucionClienteController::class, 'api'])->name("devolucion_clientes.api");
    Route::get("devolucion_clientes/paginado", [DevolucionClienteController::class, 'paginado'])->name("devolucion_clientes.paginado");
    Route::get("devolucion_clientes/listado", [DevolucionClienteController::class, 'listado'])->name("devolucion_clientes.listado");
    Route::put("devolucion_clientes/aprobar/{devolucion_stock}", [DevolucionClienteController::class, 'aprobar'])->name("devolucion_clientes.aprobar");
    Route::resource("devolucion_clientes", DevolucionClienteController::class)->only(
        ["store", "edit", "show", "update", "destroy"]
    );

    // NOTIFICACIONES
    Route::get("notificacions/paginado", [NotificacionController::class, 'paginado'])->name("notificacions.paginado");
    Route::get("notificacions/listado", [NotificacionController::class, 'listado'])->name("notificacions.listado");
    Route::get("notificacions/listadoByUser", [NotificacionController::class, 'listadoByUser'])->name("notificacions.listadoByUser");
    Route::get("notificacions/listadoByUserNoVisto", [NotificacionController::class, 'listadoByUserNoVisto'])->name("notificacions.listadoByUserNoVisto");
    Route::resource("notificacions", NotificacionController::class)->only(
        ["show"]
    );

    // AJUSTES
    Route::get("ajustes/api", [AjusteController::class, 'api'])->name("ajustes.api");
    Route::get("ajustes/paginado", [AjusteController::class, 'paginado'])->name("ajustes.paginado");
    Route::get("ajustes/listado", [AjusteController::class, 'listado'])->name("ajustes.listado");
    Route::resource("ajustes", AjusteController::class)->only(
        ["store", "edit", "show", "update", "destroy"]
    );

    // REPORTES
    Route::post('reportes/usuarios', [ReporteController::class, 'usuarios'])->name("reportes.usuarios");
    Route::post('reportes/productos', [ReporteController::class, 'productos'])->name("reportes.productos");
    Route::post('reportes/clientes', [ReporteController::class, 'clientes'])->name("reportes.clientes");
    Route::post('reportes/proveedors', [ReporteController::class, 'proveedors'])->name("reportes.proveedors");
    Route::post('reportes/movimiento_inventario', [ReporteController::class, 'movimiento_inventario'])->name("reportes.movimiento_inventario");
    Route::get('reportes/movimiento_inventario_g', [ReporteController::class, 'movimiento_inventario_g'])->name("reportes.movimiento_inventario_g");

    Route::post('reportes/solicitud_ingresos', [ReporteController::class, 'solicitud_ingresos'])->name("reportes.solicitud_ingresos");
    Route::post('reportes/orden_salidas', [ReporteController::class, 'orden_salidas'])->name("reportes.orden_salidas");
    Route::post('reportes/devolucions', [ReporteController::class, 'devolucions'])->name("reportes.devolucions");
    Route::post('reportes/orden_ventas', [ReporteController::class, 'orden_ventas'])->name("reportes.orden_ventas");
    Route::post('reportes/utilidad_ordens', [ReporteController::class, 'utilidad_ordens'])->name("reportes.utilidad_ordens");
    Route::get('reportes/utilidad_ordens_g', [ReporteController::class, 'utilidad_ordens_g'])->name("reportes.utilidad_ordens_g");
    Route::post('reportes/cuenta_cobrars', [ReporteController::class, 'cuenta_cobrars'])->name("reportes.cuenta_cobrars");
    Route::post('reportes/rotacion', [ReporteController::class, 'rotacion'])->name("reportes.rotacion");
    Route::post('reportes/gastos', [ReporteController::class, 'gastos'])->name("reportes.gastos");
    Route::post('reportes/diario_salidas', [ReporteController::class, 'diario_salidas'])->name("reportes.diario_salidas");
    Route::post('reportes/movimientos_abastecimiento', [ReporteController::class, 'movimientos_abastecimiento'])->name("reportes.movimientos_abastecimiento");
    Route::post('reportes/saldos_almacen_central', [ReporteController::class, 'saldos_almacen_central'])->name("reportes.saldos_almacen_central");
    Route::post('reportes/diario_vehiculos', [ReporteController::class, 'diario_vehiculos'])->name("reportes.diario_vehiculos");
});
