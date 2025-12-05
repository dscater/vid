<?php

namespace App\Services;

use App\Models\DetalleOrden;
use App\Models\IngresoDetalle;
use App\Models\IngresoProducto;
use App\Models\KardexProducto;
use App\Models\Producto;
use App\Models\SalidaProducto;
use Exception;
use Illuminate\Support\Facades\Log;

class KardexProductoService
{

    public function __construct(private SucursalProductoService $sucursalProductoService) {}

    /**
     * Registrar el ingreso de un producto en kardex
     *
     * @param integer $sucursal_id
     * @param string $tipo_registro
     * @param Producto $producto
     * @param float $cantidad
     * @param float $precio
     * @param string $detalle
     * @param string $modulo
     * @param integer $registro_id
     * @return void
     */
    public function registroIngreso(int $sucursal_id, string $tipo_registro, Producto $producto, float $cantidad, float $precio, string $detalle = "", string $modulo = "", int $registro_id = 0): void
    {
        //buscar el ultimo registro y usar sus valores
        $ultimo = KardexProducto::where('producto_id', $producto->id)->where("sucursal_id", $sucursal_id)->where("status", 1);
        $ultimo = $ultimo->orderBy('created_at', 'asc')
            ->get()
            ->last();
        $monto = (float)$cantidad * (float)$precio;
        $fecha_actual = date("Y-m-d");
        if ($ultimo) {
            if (!$detalle || $detalle == "") {
                $detalle = "INGRESO DE PRODUCTO";
            }
            KardexProducto::create([
                "sucursal_id" => $sucursal_id,
                'tipo_registro' => $tipo_registro, //INGRESO, EGRESO, VENTA, COMPRA,etc...
                'registro_id' => $registro_id != 0 ? $registro_id : NULL,
                "modulo" => $modulo,
                'producto_id' => $producto->id,
                'detalle' => $detalle,
                'precio' => $precio,
                'tipo_is' => 'INGRESO',
                'cantidad_ingreso' => $cantidad,
                'cantidad_saldo' => (float)$ultimo->cantidad_saldo + (float)$cantidad,
                'cu' => $producto->precio,
                'monto_ingreso' => $monto,
                'monto_saldo' => (float)$ultimo->monto_saldo + $monto,
                'fecha' => $fecha_actual,
            ]);
        } else {
            $detalle = "VALOR INICIAL";
            KardexProducto::create([
                "sucursal_id" => $sucursal_id,
                'tipo_registro' => $tipo_registro, //INGRESO, EGRESO, VENTA,etc...
                'registro_id' => $registro_id != 0 ? $registro_id : NULL,
                "modulo" => $modulo,
                'producto_id' => $producto->id,
                'detalle' => $detalle,
                'precio' => $precio,
                'tipo_is' => 'INGRESO',
                'cantidad_ingreso' => $cantidad,
                'cantidad_saldo' => (float)$cantidad,
                'cu' => $producto->precio,
                'monto_ingreso' => $monto,
                'monto_saldo' =>  $monto,
                'fecha' => $fecha_actual,
            ]);
        }

        // INCREMENTAR STOCK
        $this->sucursalProductoService->incrementarStock($producto, $cantidad, $sucursal_id);
    }

    /**
     * Registrar egreso de un producto en kardex
     *
     * @param string $tipo_registro
     * @param Producto $producto
     * @param float $cantidad
     * @param float $precio
     * @param string $detalle
     * @param integer $sucursal_id
     * @param string $modulo
     * @param integer $registro_id
     * @return void
     */
    public function registroEgreso(string $tipo_registro, Producto $producto, float $cantidad, float $precio, string $detalle = "", int $sucursal_id, string $modulo = "", int $registro_id = 0): void
    {
        //buscar el ultimo registro y usar sus valores
        $ultimo = KardexProducto::where('producto_id', $producto->id)
            ->where("sucursal_id", $sucursal_id)
            ->where("status", 1);
        $ultimo = $ultimo->orderBy('created_at', 'asc')
            ->get()
            ->last();
        $monto = (float)$cantidad * (float)$precio;

        if (!$detalle || $detalle == "") {
            $detalle = "SALIDA DE PRODUCTO";
        }
        $fecha_actual = date("Y-m-d");

        KardexProducto::create([
            "sucursal_id" => $sucursal_id,
            'tipo_registro' => $tipo_registro,
            'registro_id' => $registro_id != 0 ? $registro_id : NULL,
            "modulo" => $modulo,
            'producto_id' => $producto->id,
            'detalle' => $detalle,
            'precio' => $precio,
            'tipo_is' => 'EGRESO',
            'cantidad_salida' => $cantidad,
            'cantidad_saldo' => (float)$ultimo->cantidad_saldo - (float)$cantidad,
            'cu' => $producto->precio,
            'monto_salida' => $monto,
            'monto_saldo' => (float)$ultimo->monto_saldo - $monto,
            'fecha' => $fecha_actual,
        ]);

        // DECREMENTAR STOCK
        $this->sucursalProductoService->decrementarStock($producto, $cantidad, $sucursal_id);
    }

    // 
    /**
     * ACTUALIZA REGISTROS KARDEX
     * FUNCIÓN QUE ACTUALIZA LOS REGISTROS DEL KARDEX DE UN LUGAR
     * SOLO ACTUALIZARA LOS REGISTROS POSTERIORES AL REGISTRO ACTUALIZADO
     *
     * @param integer $kardex_id
     * @param integer $producto_id
     * @param integer $sucursal_id
     * @return void
     */
    public static function actualizaRegistrosKardex(int $kardex_id, int $producto_id, int $sucursal_id): void
    {
        $siguientes = KardexProducto::where("producto_id", $producto_id)
            ->where("id", ">=", $kardex_id)
            ->where("sucursal_id", $sucursal_id);
        $siguientes = $siguientes->get();

        foreach ($siguientes as $item) {
            $anterior = KardexProducto::where("producto_id", $producto_id)
                ->where("id", "<", $item->id);
            $anterior->where("sucursal_id", $sucursal_id);
            $anterior = $anterior->get()->last();

            $datos_actualizacion = [
                "precio" => 0,
                "cantidad_ingreso" => NULL,
                "cantidad_salida" => NULL,
                "cantidad_saldo" => 0,
                "cu" => 0,
                "monto_ingreso" => NULL,
                "monto_salida" => NULL,
                "monto_saldo" => 0,
            ];
            switch ($item->tipo_registro) {
                case 'INGRESO DE PRODUCTO':
                    $ingreso_producto = IngresoDetalle::find($item->registro_id);
                    $monto = (float)$ingreso_producto->cantidad * (float)$ingreso_producto->producto->precio;
                    if ($anterior) {
                        $datos_actualizacion["precio"] = $ingreso_producto->producto->precio;
                        $datos_actualizacion["cantidad_ingreso"] =  $ingreso_producto->cantidad;
                        $datos_actualizacion["cantidad_saldo"] = (float)$anterior->cantidad_saldo + (float)$ingreso_producto->cantidad;
                        $datos_actualizacion["cu"] = $ingreso_producto->producto->precio;
                        $datos_actualizacion["monto_ingreso"] = $monto;
                        $datos_actualizacion["monto_saldo"] = (float)$anterior->monto_saldo + $monto;
                    } else {
                        $datos_actualizacion["precio"] = $ingreso_producto->producto->precio;
                        $datos_actualizacion["cantidad_ingreso"] =  $ingreso_producto->cantidad;
                        $datos_actualizacion["cantidad_saldo"] = (float)$ingreso_producto->cantidad;
                        $datos_actualizacion["cu"] = $ingreso_producto->producto->precio;
                        $datos_actualizacion["monto_ingreso"] = $monto;
                        $datos_actualizacion["monto_saldo"] = $monto;
                    }
                    break;
                case 'SALIDA DE PRODUCTO':
                    $salida_producto = SalidaProducto::find($item->registro_id);
                    $monto = (float)$salida_producto->cantidad * (float)$salida_producto->producto->precio;

                    if ($anterior) {
                        $datos_actualizacion["precio"] = $salida_producto->producto->precio;
                        $datos_actualizacion["cantidad_salida"] =  $salida_producto->cantidad;
                        $datos_actualizacion["cantidad_saldo"] = (float)$anterior->cantidad_saldo - (float)$salida_producto->cantidad;
                        $datos_actualizacion["cu"] = $salida_producto->producto->precio;
                        $datos_actualizacion["monto_salida"] = $monto;
                        $datos_actualizacion["monto_saldo"] =  (float)$anterior->monto_saldo - $monto;
                    } else {
                        $datos_actualizacion["precio"] = $salida_producto->producto->precio;
                        $datos_actualizacion["cantidad_salida"] =  $salida_producto->cantidad;
                        $datos_actualizacion["cantidad_saldo"] = (float)$salida_producto->cantidad * (-1);
                        $datos_actualizacion["cu"] = $salida_producto->producto->precio;
                        $datos_actualizacion["monto_salida"] = $monto;
                        $datos_actualizacion["monto_saldo"] = $monto * (-1);
                    }

                    break;
                case 'ORDEN DE VENTA':
                    $venta_detalle = DetalleOrden::find($item->registro_id);
                    $monto = (float)$venta_detalle->cantidad * (float)$venta_detalle->precio;
                    if ($anterior) {
                        $datos_actualizacion["precio"] = $venta_detalle->precio;
                        $datos_actualizacion["cantidad_salida"] =  $venta_detalle->cantidad;
                        $datos_actualizacion["cantidad_saldo"] = (float)$anterior->cantidad_saldo - (float)$venta_detalle->cantidad;
                        $datos_actualizacion["cu"] = $venta_detalle->precio;
                        $datos_actualizacion["monto_salida"] = $monto;
                        $datos_actualizacion["monto_saldo"] =  (float)$anterior->monto_saldo - $monto;
                    } else {
                        $datos_actualizacion["precio"] = $venta_detalle->precio;
                        $datos_actualizacion["cantidad_salida"] =  $venta_detalle->cantidad;
                        $datos_actualizacion["cantidad_saldo"] = (float)$venta_detalle->cantidad * (-1);
                        $datos_actualizacion["cu"] = $venta_detalle->precio;
                        $datos_actualizacion["monto_salida"] = $monto;
                        $datos_actualizacion["monto_saldo"] = $monto * (-1);
                    }
                    break;
            }

            $item->update($datos_actualizacion);
        }
    }
}
