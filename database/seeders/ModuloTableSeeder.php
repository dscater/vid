<?php

namespace Database\Seeders;

use App\Models\Modulo;
use Illuminate\Database\Seeder;

class ModuloTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // GESTIÓN DE USUARIOS
        Modulo::create([
            "modulo" => "Gestión de usuarios",
            "nombre" => "usuarios.index",
            "accion" => "VER",
            "descripcion" => "VER LA LISTA DE USUARIOS"
        ]);

        Modulo::create([
            "modulo" => "Gestión de usuarios",
            "nombre" => "usuarios.create",
            "accion" => "CREAR",
            "descripcion" => "CREAR USUARIOS"
        ]);

        Modulo::create([
            "modulo" => "Gestión de usuarios",
            "nombre" => "usuarios.edit",
            "accion" => "EDITAR",
            "descripcion" => "EDITAR USUARIOS"
        ]);

        Modulo::create([
            "modulo" => "Gestión de usuarios",
            "nombre" => "usuarios.destroy",
            "accion" => "ELIMINAR",
            "descripcion" => "ELIMINAR USUARIOS"
        ]);

        // ROLES Y PERMISOS
        Modulo::create([
            "modulo" => "Roles y Permisos",
            "nombre" => "roles.index",
            "accion" => "VER",
            "descripcion" => "VER LA LISTA DE ROLES Y PERMISOS"
        ]);

        Modulo::create([
            "modulo" => "Roles y Permisos",
            "nombre" => "roles.create",
            "accion" => "CREAR",
            "descripcion" => "CREAR ROLES Y PERMISOS"
        ]);

        Modulo::create([
            "modulo" => "Roles y Permisos",
            "nombre" => "roles.edit",
            "accion" => "EDITAR",
            "descripcion" => "EDITAR ROLES Y PERMISOS"
        ]);

        Modulo::create([
            "modulo" => "Roles y Permisos",
            "nombre" => "roles.destroy",
            "accion" => "ELIMINAR",
            "descripcion" => "ELIMINAR ROLES Y PERMISOS"
        ]);

        // CONFIGURACIÓN DEL SISTEMA
        Modulo::create([
            "modulo" => "Configuración",
            "nombre" => "configuracions.index",
            "accion" => "VER",
            "descripcion" => "VER INFORMACIÓN DE LA CONFIGURACIÓN DEL SISTEMA"
        ]);

        Modulo::create([
            "modulo" => "Configuración",
            "nombre" => "configuracions.edit",
            "accion" => "EDITAR",
            "descripcion" => "EDITAR LA CONFIGURACIÓN DEL SISTEMA"
        ]);

        // SUCURSALES
        Modulo::create([
            "modulo" => "Sucursales",
            "nombre" => "sucursals.index",
            "accion" => "VER",
            "descripcion" => "VER LA LISTA DE SUCURSALES"
        ]);

        Modulo::create([
            "modulo" => "Sucursales",
            "nombre" => "sucursals.create",
            "accion" => "CREAR",
            "descripcion" => "CREAR SUCURSALES"
        ]);

        Modulo::create([
            "modulo" => "Sucursales",
            "nombre" => "sucursals.edit",
            "accion" => "EDITAR",
            "descripcion" => "EDITAR SUCURSALES"
        ]);

        Modulo::create([
            "modulo" => "Sucursales",
            "nombre" => "sucursals.destroy",
            "accion" => "ELIMINAR",
            "descripcion" => "ELIMINAR SUCURSALES"
        ]);

        // CATEGORÍAS
        Modulo::create([
            "modulo" => "Categorías",
            "nombre" => "categorias.index",
            "accion" => "VER",
            "descripcion" => "VER LA LISTA DE CATEGORÍAS"
        ]);

        Modulo::create([
            "modulo" => "Categorías",
            "nombre" => "categorias.create",
            "accion" => "CREAR",
            "descripcion" => "CREAR CATEGORÍAS"
        ]);

        Modulo::create([
            "modulo" => "Categorías",
            "nombre" => "categorias.edit",
            "accion" => "EDITAR",
            "descripcion" => "EDITAR CATEGORÍAS"
        ]);

        Modulo::create([
            "modulo" => "Categorías",
            "nombre" => "categorias.destroy",
            "accion" => "ELIMINAR",
            "descripcion" => "ELIMINAR CATEGORÍAS"
        ]);

        // SUBCATEGORÍAS
        Modulo::create([
            "modulo" => "Subcategorías",
            "nombre" => "sub_categorias.index",
            "accion" => "VER",
            "descripcion" => "VER LA LISTA DE SUBCATEGORÍAS"
        ]);

        Modulo::create([
            "modulo" => "Subcategorías",
            "nombre" => "sub_categorias.create",
            "accion" => "CREAR",
            "descripcion" => "CREAR SUBCATEGORÍAS"
        ]);

        Modulo::create([
            "modulo" => "Subcategorías",
            "nombre" => "sub_categorias.edit",
            "accion" => "EDITAR",
            "descripcion" => "EDITAR SUBCATEGORÍAS"
        ]);

        Modulo::create([
            "modulo" => "Subcategorías",
            "nombre" => "sub_categorias.destroy",
            "accion" => "ELIMINAR",
            "descripcion" => "ELIMINAR SUBCATEGORÍAS"
        ]);

        // MARCAS
        Modulo::create([
            "modulo" => "Marcas",
            "nombre" => "marcas.index",
            "accion" => "VER",
            "descripcion" => "VER LA LISTA DE MARCAS"
        ]);

        Modulo::create([
            "modulo" => "Marcas",
            "nombre" => "marcas.create",
            "accion" => "CREAR",
            "descripcion" => "CREAR MARCAS"
        ]);

        Modulo::create([
            "modulo" => "Marcas",
            "nombre" => "marcas.edit",
            "accion" => "EDITAR",
            "descripcion" => "EDITAR MARCAS"
        ]);

        Modulo::create([
            "modulo" => "Marcas",
            "nombre" => "marcas.destroy",
            "accion" => "ELIMINAR",
            "descripcion" => "ELIMINAR MARCAS"
        ]);

        // UNIDADES DE MEDIDA
        Modulo::create([
            "modulo" => "Unidades de Medida",
            "nombre" => "unidad_medidas.index",
            "accion" => "VER",
            "descripcion" => "VER LA LISTA DE UNIDADES DE MEDIDA"
        ]);

        Modulo::create([
            "modulo" => "Unidades de Medida",
            "nombre" => "unidad_medidas.create",
            "accion" => "CREAR",
            "descripcion" => "CREAR UNIDADES DE MEDIDA"
        ]);

        Modulo::create([
            "modulo" => "Unidades de Medida",
            "nombre" => "unidad_medidas.edit",
            "accion" => "EDITAR",
            "descripcion" => "EDITAR UNIDADES DE MEDIDA"
        ]);

        Modulo::create([
            "modulo" => "Unidades de Medida",
            "nombre" => "unidad_medidas.destroy",
            "accion" => "ELIMINAR",
            "descripcion" => "ELIMINAR UNIDADES DE MEDIDA"
        ]);

        // PRODUCTOS
        Modulo::create([
            "modulo" => "Productos",
            "nombre" => "productos.index",
            "accion" => "VER",
            "descripcion" => "VER LA LISTA DE PRODUCTOS"
        ]);

        Modulo::create([
            "modulo" => "Productos",
            "nombre" => "productos.create",
            "accion" => "CREAR",
            "descripcion" => "CREAR PRODUCTOS"
        ]);

        Modulo::create([
            "modulo" => "Productos",
            "nombre" => "productos.edit",
            "accion" => "EDITAR",
            "descripcion" => "EDITAR PRODUCTOS"
        ]);

        Modulo::create([
            "modulo" => "Productos",
            "nombre" => "productos.destroy",
            "accion" => "ELIMINAR",
            "descripcion" => "ELIMINAR PRODUCTOS"
        ]);

        // CLIENTES
        Modulo::create([
            "modulo" => "Clientes",
            "nombre" => "clientes.index",
            "accion" => "VER",
            "descripcion" => "VER LA LISTA DE CLIENTES"
        ]);

        Modulo::create([
            "modulo" => "Clientes",
            "nombre" => "clientes.create",
            "accion" => "CREAR",
            "descripcion" => "CREAR CLIENTES"
        ]);

        Modulo::create([
            "modulo" => "Clientes",
            "nombre" => "clientes.edit",
            "accion" => "EDITAR",
            "descripcion" => "EDITAR CLIENTES"
        ]);

        Modulo::create([
            "modulo" => "Clientes",
            "nombre" => "clientes.destroy",
            "accion" => "ELIMINAR",
            "descripcion" => "ELIMINAR CLIENTES"
        ]);

        // PROVEEDORES
        Modulo::create([
            "modulo" => "Proveedores",
            "nombre" => "proveedors.index",
            "accion" => "VER",
            "descripcion" => "VER LA LISTA DE PROVEEDORES"
        ]);

        Modulo::create([
            "modulo" => "Proveedores",
            "nombre" => "proveedors.create",
            "accion" => "CREAR",
            "descripcion" => "CREAR PROVEEDORES"
        ]);

        Modulo::create([
            "modulo" => "Proveedores",
            "nombre" => "proveedors.edit",
            "accion" => "EDITAR",
            "descripcion" => "EDITAR PROVEEDORES"
        ]);

        Modulo::create([
            "modulo" => "Proveedores",
            "nombre" => "proveedors.destroy",
            "accion" => "ELIMINAR",
            "descripcion" => "ELIMINAR PROVEEDORES"
        ]);

        // SOLICITUD DE INGRESOS
        Modulo::create([
            "modulo" => "Solicitud de Ingresos",
            "nombre" => "solicitud_ingresos.index",
            "accion" => "VER",
            "descripcion" => "VER LA LISTA DE SOLICITUD DE INGRESOS"
        ]);

        Modulo::create([
            "modulo" => "Solicitud de Ingresos",
            "nombre" => "solicitud_ingresos.create",
            "accion" => "CREAR",
            "descripcion" => "CREAR SOLICITUD DE INGRESOS"
        ]);

        Modulo::create([
            "modulo" => "Solicitud de Ingresos",
            "nombre" => "solicitud_ingresos.edit",
            "accion" => "EDITAR",
            "descripcion" => "EDITAR SOLICITUD DE INGRESOS"
        ]);

        Modulo::create([
            "modulo" => "Solicitud de Ingresos",
            "nombre" => "solicitud_ingresos.destroy",
            "accion" => "ELIMINAR",
            "descripcion" => "ELIMINAR SOLICITUD DE INGRESOS"
        ]);

        // ORDENES DE SALIDA
        Modulo::create([
            "modulo" => "Ordenes de Salida",
            "nombre" => "orden_salidas.index",
            "accion" => "VER",
            "descripcion" => "VER LA LISTA DE ORDENES DE SALIDA"
        ]);

        Modulo::create([
            "modulo" => "Ordenes de Salida",
            "nombre" => "orden_salidas.create",
            "accion" => "CREAR",
            "descripcion" => "CREAR ORDENES DE SALIDA"
        ]);

        Modulo::create([
            "modulo" => "Ordenes de Salida",
            "nombre" => "orden_salidas.edit",
            "accion" => "EDITAR",
            "descripcion" => "EDITAR ORDENES DE SALIDA"
        ]);

        Modulo::create([
            "modulo" => "Ordenes de Salida",
            "nombre" => "orden_salidas.destroy",
            "accion" => "ELIMINAR",
            "descripcion" => "ELIMINAR ORDENES DE SALIDA"
        ]);

        // DEVOLUCIÓN DE STOCK
        Modulo::create([
            "modulo" => "Devolución de Stock",
            "nombre" => "devolucion_stocks.index",
            "accion" => "VER",
            "descripcion" => "VER LA LISTA DE DEVOLUCIÓN DE STOCK"
        ]);

        Modulo::create([
            "modulo" => "Devolución de Stock",
            "nombre" => "devolucion_stocks.create",
            "accion" => "CREAR",
            "descripcion" => "CREAR DEVOLUCIÓN DE STOCK"
        ]);

        Modulo::create([
            "modulo" => "Devolución de Stock",
            "nombre" => "devolucion_stocks.edit",
            "accion" => "EDITAR",
            "descripcion" => "EDITAR DEVOLUCIÓN DE STOCK"
        ]);

        Modulo::create([
            "modulo" => "Devolución de Stock",
            "nombre" => "devolucion_stocks.destroy",
            "accion" => "ELIMINAR",
            "descripcion" => "ELIMINAR DEVOLUCIÓN DE STOCK"
        ]);

        // ORDENDES DE VENTA
        Modulo::create([
            "modulo" => "Ordenes de Venta",
            "nombre" => "orden_ventas.index",
            "accion" => "VER",
            "descripcion" => "VER LA LISTA DE ORDENDES DE VENTA"
        ]);

        Modulo::create([
            "modulo" => "Ordenes de Venta",
            "nombre" => "orden_ventas.create",
            "accion" => "CREAR",
            "descripcion" => "CREAR ORDENDES DE VENTA"
        ]);

        Modulo::create([
            "modulo" => "Ordenes de Venta",
            "nombre" => "orden_ventas.edit",
            "accion" => "EDITAR",
            "descripcion" => "EDITAR ORDENDES DE VENTA"
        ]);

        Modulo::create([
            "modulo" => "Ordenes de Venta",
            "nombre" => "orden_ventas.destroy",
            "accion" => "ELIMINAR",
            "descripcion" => "ELIMINAR ORDENDES DE VENTA"
        ]);

        // TRANSFERENCIAS DE STOCK
        Modulo::create([
            "modulo" => "Transferencias de Stock",
            "nombre" => "transferencias.index",
            "accion" => "VER",
            "descripcion" => "VER LA LISTA DE TRANSFERENCIAS DE STOCK"
        ]);

        Modulo::create([
            "modulo" => "Transferencias de Stock",
            "nombre" => "transferencias.create",
            "accion" => "CREAR",
            "descripcion" => "CREAR TRANSFERENCIAS DE STOCK"
        ]);

        Modulo::create([
            "modulo" => "Transferencias de Stock",
            "nombre" => "transferencias.edit",
            "accion" => "EDITAR",
            "descripcion" => "EDITAR TRANSFERENCIAS DE STOCK"
        ]);

        Modulo::create([
            "modulo" => "Transferencias de Stock",
            "nombre" => "transferencias.destroy",
            "accion" => "ELIMINAR",
            "descripcion" => "ELIMINAR TRANSFERENCIAS DE STOCK"
        ]);

        // DEVOLUCIÓN DE CLIENTES
        Modulo::create([
            "modulo" => "Devolución de Clientes",
            "nombre" => "devolucion_clientes.index",
            "accion" => "VER",
            "descripcion" => "VER LA LISTA DE DEVOLUCIÓN DE CLIENTES"
        ]);

        Modulo::create([
            "modulo" => "Devolución de Clientes",
            "nombre" => "devolucion_clientes.create",
            "accion" => "CREAR",
            "descripcion" => "CREAR DEVOLUCIÓN DE CLIENTES"
        ]);

        Modulo::create([
            "modulo" => "Devolución de Clientes",
            "nombre" => "devolucion_clientes.edit",
            "accion" => "EDITAR",
            "descripcion" => "EDITAR DEVOLUCIÓN DE CLIENTES"
        ]);

        Modulo::create([
            "modulo" => "Devolución de Clientes",
            "nombre" => "devolucion_clientes.destroy",
            "accion" => "ELIMINAR",
            "descripcion" => "ELIMINAR DEVOLUCIÓN DE CLIENTES"
        ]);

        // CUENTAS POR COBRAR
        Modulo::create([
            "modulo" => "Cuentas por Cobrar",
            "nombre" => "cuenta_cobrars.index",
            "accion" => "VER",
            "descripcion" => "VER LA LISTA DE CUENTAS POR COBRAR"
        ]);

        Modulo::create([
            "modulo" => "Cuentas por Cobrar",
            "nombre" => "cuenta_cobrars.create",
            "accion" => "CREAR",
            "descripcion" => "CREAR CUENTAS POR COBRAR"
        ]);

        Modulo::create([
            "modulo" => "Cuentas por Cobrar",
            "nombre" => "cuenta_cobrars.edit",
            "accion" => "EDITAR",
            "descripcion" => "EDITAR CUENTAS POR COBRAR"
        ]);

        Modulo::create([
            "modulo" => "Cuentas por Cobrar",
            "nombre" => "cuenta_cobrars.destroy",
            "accion" => "ELIMINAR",
            "descripcion" => "ELIMINAR CUENTAS POR COBRAR"
        ]);

        // REGISTRO DE GASTOS
        Modulo::create([
            "modulo" => "Registro de Gastos",
            "nombre" => "gastos.index",
            "accion" => "VER",
            "descripcion" => "VER LA LISTA DE REGISTRO DE GASTOS"
        ]);

        Modulo::create([
            "modulo" => "Registro de Gastos",
            "nombre" => "gastos.create",
            "accion" => "CREAR",
            "descripcion" => "CREAR REGISTRO DE GASTOS"
        ]);

        Modulo::create([
            "modulo" => "Registro de Gastos",
            "nombre" => "gastos.edit",
            "accion" => "EDITAR",
            "descripcion" => "EDITAR REGISTRO DE GASTOS"
        ]);

        Modulo::create([
            "modulo" => "Registro de Gastos",
            "nombre" => "gastos.destroy",
            "accion" => "ELIMINAR",
            "descripcion" => "ELIMINAR REGISTRO DE GASTOS"
        ]);

        // PROFORMAS
        Modulo::create([
            "modulo" => "Proformas",
            "nombre" => "proformas.index",
            "accion" => "VER",
            "descripcion" => "VER LA LISTA DE PROFORMAS"
        ]);

        Modulo::create([
            "modulo" => "Proformas",
            "nombre" => "proformas.create",
            "accion" => "CREAR",
            "descripcion" => "CREAR PROFORMAS"
        ]);

        Modulo::create([
            "modulo" => "Proformas",
            "nombre" => "proformas.edit",
            "accion" => "EDITAR",
            "descripcion" => "EDITAR PROFORMAS"
        ]);

        Modulo::create([
            "modulo" => "Proformas",
            "nombre" => "proformas.destroy",
            "accion" => "ELIMINAR",
            "descripcion" => "ELIMINAR PROFORMAS"
        ]);

        // REPORTES
        Modulo::create([
            "modulo" => "Reportes",
            "nombre" => "reportes.usuarios",
            "accion" => "REPORTE LISTA DE USUARIOS",
            "descripcion" => "GENERAR REPORTES DE LISTA DE USUARIOS"
        ]);

        Modulo::create([
            "modulo" => "Reportes",
            "nombre" => "reportes.productos",
            "accion" => "REPORTE LISTA DE PRODUCTOS",
            "descripcion" => "GENERAR REPORTES DE LISTA DE PRODUCTOS"
        ]);

        Modulo::create([
            "modulo" => "Reportes",
            "nombre" => "reportes.sucursals",
            "accion" => "REPORTE LISTA DE SUCURSALES",
            "descripcion" => "GENERAR REPORTES DE LISTA DE SUCURSALES"
        ]);

        Modulo::create([
            "modulo" => "Reportes",
            "nombre" => "reportes.clientes",
            "accion" => "REPORTE LISTA DE CLIENTES",
            "descripcion" => "GENERAR REPORTES DE LISTA DE CLIENTES"
        ]);

        Modulo::create([
            "modulo" => "Reportes",
            "nombre" => "reportes.proveedors",
            "accion" => "REPORTE LISTA DE PROVEEDORES",
            "descripcion" => "GENERAR REPORTES DE LISTA DE PROVEEDORES"
        ]);

        Modulo::create([
            "modulo" => "Reportes",
            "nombre" => "reportes.inventario",
            "accion" => "REPORTE DE INVENTARIO",
            "descripcion" => "GENERAR REPORTES DE INVENTARIO"
        ]);

        Modulo::create([
            "modulo" => "Reportes",
            "nombre" => "reportes.movimiento_inventario",
            "accion" => "REPORTE DE MOVIMIENTO DE INVENTARIO",
            "descripcion" => "GENERAR REPORTES DE MOVIMIENTO DE INVENTARIO"
        ]);

        Modulo::create([
            "modulo" => "Reportes",
            "nombre" => "reportes.solicitud_ingresos",
            "accion" => "REPORTE DE SOLICITUDES DE INGRESO",
            "descripcion" => "GENERAR REPORTES DE SOLICITUDES DE INGRESO"
        ]);

        Modulo::create([
            "modulo" => "Reportes",
            "nombre" => "reportes.orden_salidas",
            "accion" => "REPORTE DE ORDENES DE SALIDA",
            "descripcion" => "GENERAR REPORTES DE ORDENES DE SALIDA"
        ]);

        Modulo::create([
            "modulo" => "Reportes",
            "nombre" => "reportes.devolucions",
            "accion" => "REPORTE DE DEVOLUCIONES",
            "descripcion" => "GENERAR REPORTES DE DEVOLUCIONES"
        ]);

        Modulo::create([
            "modulo" => "Reportes",
            "nombre" => "reportes.orden_ventas",
            "accion" => "REPORTE DE ORDENES DE VENTA",
            "descripcion" => "GENERAR REPORTES DE ORDENES DE VENTA"
        ]);

        Modulo::create([
            "modulo" => "Reportes",
            "nombre" => "reportes.ejecutivos",
            "accion" => "REPORTE DE EJECUTIVOS/RESUMEN",
            "descripcion" => "GENERAR REPORTES DE EJECUTIVOS/RESUMEN"
        ]);

        Modulo::create([
            "modulo" => "Reportes",
            "nombre" => "reportes.diario_salidas",
            "accion" => "REPORTE DE DIARIO DE SALIDAS POR SUCURSAL",
            "descripcion" => "GENERAR REPORTES DE DIARIO DE SALIDAS POR SUCURSAL"
        ]);

        Modulo::create([
            "modulo" => "Reportes",
            "nombre" => "reportes.movimientos_abastecimiento",
            "accion" => "REPORTE DE SEMANAL DE MOVIMIENTOS Y ABASTECIMIENTO",
            "descripcion" => "GENERAR REPORTES DE SEMANAL DE MOVIMIENTOS Y ABASTECIMIENTO"
        ]);

        Modulo::create([
            "modulo" => "Reportes",
            "nombre" => "reportes.saldos_almacen_central",
            "accion" => "REPORTE DE SALDOS DEL ALMACÉN CENTRAL",
            "descripcion" => "GENERAR REPORTES DE SALDOS DEL ALMACÉN CENTRAL"
        ]);

        Modulo::create([
            "modulo" => "Reportes",
            "nombre" => "reportes.diario_vehiculos",
            "accion" => "REPORTE DE CONTROL DIARIO DE VEHÍCULOS",
            "descripcion" => "GENERAR REPORTES DE CONTROL DIARIO DE VEHÍCULOS"
        ]);

        Modulo::create([
            "modulo" => "Reportes",
            "nombre" => "reportes.notas_entrega",
            "accion" => "REPORTE DE NOTAS DE ENTREGA",
            "descripcion" => "GENERAR REPORTES DE NOTAS DE ENTREGA"
        ]);
    }
}
