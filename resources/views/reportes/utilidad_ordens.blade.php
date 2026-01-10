<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>UtilidadOrdenes</title>
    <style type="text/css">
        * {
            font-family: sans-serif;
        }

        @page {
            margin-top: 1.5cm;
            margin-bottom: 0.3cm;
            margin-left: 0.3cm;
            margin-right: 0.3cm;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-top: 20px;
            page-break-before: avoid;
            margin: auto;
        }

        table thead tr th,
        tbody tr td {
            padding: 3px;
            word-wrap: break-word;
        }

        table tr th {
            font-size: 8pt;
        }

        table tr td {
            font-size: 7pt;
        }


        .encabezado {
            width: 100%;
        }

        .logo img {
            position: absolute;
            height: 70px;
            top: -20px;
            left: 0px;
        }

        h2.titulo {
            width: 450px;
            margin: auto;
            margin-top: 0PX;
            margin-bottom: 15px;
            text-align: center;
            font-size: 14pt;
        }

        .texto {
            width: 250px;
            text-align: center;
            margin: auto;
            margin-top: 15px;
            font-weight: bold;
            font-size: 1.1em;
        }

        .fecha {
            width: 250px;
            text-align: center;
            margin: auto;
            margin-top: 15px;
            font-weight: normal;
            font-size: 0.85em;
        }

        .total {
            text-align: right;
            padding-right: 15px;
            font-weight: bold;
        }

        table thead {
            background: rgb(236, 236, 236)
        }

        tr {
            page-break-inside: avoid !important;
        }

        .centreado {
            padding-left: 0px;
            text-align: center;
        }

        .datos {
            margin-left: 15px;
            border-top: solid 1px;
            border-collapse: collapse;
            width: 250px;
        }

        .txt {
            font-weight: bold;
            text-align: right;
            padding-right: 5px;
        }

        .txt_center {
            font-weight: bold;
            text-align: center;
        }

        .cumplimiento {
            position: absolute;
            width: 150px;
            right: 0px;
            top: 86px;
        }

        .b_top {
            border-top: solid 1px black;
        }

        .gray {
            background: rgb(202, 202, 202);
        }

        .bg-principal {
            background: #153f59;
            color: white;
        }

        .txt_rojo {}

        .img_celda img {
            width: 45px;
        }
    </style>
</head>

<body>
    @inject('configuracion', 'App\Models\Configuracion')
    <div class="encabezado">
        <div class="logo">
            <img src="{{ $configuracion->first()->logo_b64 }}">
        </div>
        <h2 class="titulo">
            {{ $configuracion->first()->nombre_sistema }}
        </h2>
        <h4 class="texto">UTILIDAD DE ORDENDES DE VENTAS</h4>
        <h4 class="fecha">Del {{ date('d/m/Y', strtotime($fecha_ini)) }} al {{ date('d/m/Y', strtotime($fecha_fin)) }}
    </div>


    <table border="1">
        <thead>
            <tr>
                <th>PRODUCTOS</th>
                <th>UNIDAD</th>
                <th>CANTIDAD VENDIDA</th>
                <th>TOTAL</th>
                <th>CANTIDAD COMPRADA</th>
                <th>TOTAL</th>
                <th>UTILIDAD</th>
            </tr>
        </thead>
        <tbody>
            @php
                $total_final1 = 0;
                $total_final2 = 0;
                $total_final3 = 0;
                $total_final4 = 0;
                $total_final5 = 0;
            @endphp
            @foreach ($productos as $key => $value)
                @php
                    $orden_venta_detalles = App\Models\OrdenVentaDetalle::select('orden_venta_detalles.*')->where(
                        'producto_id',
                        $value->id,
                    );
                    if ($sucursal_id != 'todos') {
                        $orden_venta_detalles->whereHas('orden_venta', function ($query) use ($sucursal_id) {
                            $query->where('sucursal_id', $sucursal_id);
                            $query->where('verificado', 2);
                        });
                    }
                    $orden_venta_detalles->whereHas('orden_venta', function ($query) use ($fecha_ini, $fecha_fin) {
                        $query->whereBetween('fecha', [$fecha_ini, $fecha_fin]);
                    });
                    $total_ventas = $orden_venta_detalles
                        ->whereHas('orden_venta', function ($query) use ($sucursal_id) {
                            $query->where('estado', 'FINALIZADO');
                        })
                        ->sum('subtotal_f');
                    $total_ventas_cantidad = $orden_venta_detalles
                        ->whereHas('orden_venta', function ($query) use ($sucursal_id) {
                            $query->where('estado', 'FINALIZADO');
                        })
                        ->sum('cantidad');

                    $solicitud_ingreso_detalles = App\Models\SolicitudIngresoDetalle::select(
                        'solicitud_ingreso_detalles.*',
                    )->where('producto_id', $value->id);
                    // if ($sucursal_id != 'todos') {
                    //     $solicitud_ingreso_detalles->whereHas('solicitud_ingreso', function ($query) use (
                    //         $sucursal_id,
                    //     ) {
                    //         $query->where('sucursal_id', $sucursal_id);
                    //     });
                    // }

                    $solicitud_ingreso_detalles->whereHas('solicitud_ingreso', function ($query) use (
                        $key,
                        $fecha_ini,
                        $fecha_fin,
                    ) {
                        $query->whereIn('verificado', [1, 2, 3]);
                        $query->whereBetween('fecha_ingreso', [$fecha_ini, $fecha_fin]);
                    });

                    $total_compras = $solicitud_ingreso_detalles->sum(DB::raw('cantidad_fisica * costo'));

                    $total_compras_cantidad = $solicitud_ingreso_detalles->sum('cantidad_fisica');

                    $saldo = (float) $total_ventas - (float) $total_compras;
                    $total_final1 += (float) $total_ventas_cantidad;
                    $total_final2 += (float) $total_ventas;
                    $total_final3 += (float) $total_compras_cantidad;
                    $total_final4 += (float) $total_compras;
                    $total_final5 += (float) $saldo;
                @endphp
                <tr>
                    <td>{{ $value->nombre }}</td>
                    <td>{{ $value->unidad_medida->nombre }}</td>
                    <td class="centreado">{{ $total_ventas_cantidad }}</td>
                    <td class="centreado">Bs {{ number_format($total_ventas, 2, '.', ',') }}</td>
                    <td class="centreado">{{ $total_compras_cantidad }}</td>
                    <td class="centreado">Bs {{ number_format($total_compras, 2, '.', ',') }}</td>
                    <td class="centreado">Bs {{ number_format($saldo, 2, '.', ',') }}</td>
                </tr>
            @endforeach
            <tr>
                <th colspan="2">TOTAL</th>
                <th>{{ $total_final1 }}</th>
                <th>Bs {{ number_format($total_final2, 2, '.', ',') }}</th>
                <th>{{ $total_final3 }}</th>,
                <th>Bs {{ number_format($total_final4, 2, '.', ',') }}</th>
                <th>Bs {{ number_format($total_final5, 2, '.', ',') }}</th>
            </tr>
        </tbody>
    </table>
</body>

</html>
