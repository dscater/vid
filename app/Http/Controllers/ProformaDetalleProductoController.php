<?php

namespace App\Http\Controllers;

use App\Models\ProformaDetalleProducto;
use Illuminate\Http\Request;

class ProformaDetalleProductoController extends Controller
{
    public function verificar(ProformaDetalleProducto $proforma_detalle_producto, Request $request)
    {
        $proforma_detalle_producto->verificado = $request->verificado;
        $proforma_detalle_producto->cantidad_entregada = $request->cantidad_entregada;
        $proforma_detalle_producto->save();

        return response()->JSON(true);
    }
}
