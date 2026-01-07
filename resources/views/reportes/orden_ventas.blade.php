<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>MovimientoInventario</title>
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
        }

        table thead tr th,
        tbody tr td {
            padding: 3px;
            word-wrap: break-word;
        }

        table thead tr th {
            font-size: 7pt;
        }

        table tbody tr td {
            font-size: 6pt;
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
            width: 350px;
            text-align: center;
            margin: auto;
            margin-top: 15px;
            font-weight: bold;
            font-size: 1em;
        }

        .fecha {
            width: 350px;
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

        table {
            width: 100%;
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

        th.vertical {
            vertical-align: middle;
            text-align: center;
            height: 220px;
            /* controla la altura de la fila */
            padding: 0;
        }

        th.vertical div {
            transform: rotate(-90deg);
            white-space: nowrap;
            font-size: 7pt;
        }

        .img_celda img {
            width: 45px;
        }

        .break_page {
            page-break-after: always;
        }

        .obs {
            font-size: 0.9em;
        }

        .bgFinal {
            background-color: rgb(255, 255, 232);
            font-weight: bold;
            font-size: 7.6pt;
        }

        .punteado {
            display: block;
            width: 100%;
            border-bottom: dotted 1px black;
            margin-top: 25px;
        }

        .bg0 {
            background: #cff3f3;
        }

        .bg1 {
            background: #ffe9ff;
        }

        .bg2 {
            background: #f7ffe0;
        }

        .bg3 {
            background: #ecfcdd;
        }

        .bg4 {
            background: #faeee4;
        }

        .nueva_pagina {
            page-break-after: always;
        }
    </style>
</head>

<body>
    @inject('configuracion', 'App\Models\Configuracion')
    @inject('parametroSucursal', 'App\Models\ParametroSucursal')
    @php
        $horaInicial = date('H:i', strtotime($parametroSucursal->first()->valor1)) ?? '08:00';
        $horaFinal = date('H:i', strtotime($parametroSucursal->first()->valor2)) ?? '20:00';
    @endphp

    @foreach ($sucursals as $key => $sucursal)
        <div class="encabezado">
            <div class="logo">
                <img src="{{ $configuracion->first()->logo_b64 }}">
            </div>
            <h2 class="titulo">
                {{ $configuracion->first()->nombre_sistema }}
            </h2>
            <h4 class="texto">ORDENES DE VENTAS</h4>
            <h4 class="texto">SUCURSAL: {{ $sucursal->nombre }}</h4>
            <h4 class="fecha">Del {{ date('d/m/Y', strtotime($fecha_ini)) }} al
                {{ date('d/m/Y', strtotime($fecha_fin)) }}
            </h4>
        </div>
        @php

        @endphp
        <table border="1">
            <thead>
                <tr>
                    <th width="7%">PRODUCTO</th>
                    <th>UNIDAD DE MEDIDA</th>
                    @foreach ($clientes as $key => $item)
                        <th class="vertical">
                            <div>
                                {{ $item->razon_social }}
                            </div>
                        </th>
                    @endforeach
                    <th class="bg0 vertical">
                        <div>
                            STOCK DIARIO
                        </div>
                    </th>
                    <th class="bg1 vertical">
                        <div>
                            VENTAS
                        </div>
                    </th>
                    <th class="bg2 vertical">
                        <div>
                            DEVOLUCIÓN
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody>
                @php
                    $cont = 1;
                @endphp
                @foreach ($productos as $producto)
                    <tr>
                        <td>{{ $producto->nombre }}</td>
                        <td>{{ $producto->unidad_medida->nombre }}</td>
                        @foreach ($clientes as $cliente)
                            @php
                                $cantidad = App\Models\OrdenVentaDetalle::whereHas('orden_venta', function (
                                    $query,
                                ) use ($sucursal, $cliente, $fecha_ini, $fecha_fin) {
                                    $query->where('sucursal_id', $sucursal->id);
                                    $query->where('cliente_id', $cliente->id);
                                    $query->whereBetween('fecha', [$fecha_ini, $fecha_fin]);
                                    $query->where('verificado', 2);
                                })
                                    ->where('producto_id', $producto->id)
                                    ->sum('cantidad');
                            @endphp
                            <td>{{ $cantidad }}</td>
                        @endforeach
                        @php
                            $stock_diario = App\Models\SucursalProducto::where('sucursal_id', $sucursal->id)
                                ->where('producto_id', $producto->id)
                                ->value('stock_actual');
                        @endphp
                        <td>{{ $stock_diario }}</td>
                        @php
                            $ventas = App\Models\OrdenVentaDetalle::whereHas('orden_venta', function ($query) use (
                                $sucursal,
                                $cliente,
                                $fecha_ini,
                                $fecha_fin,
                            ) {
                                $query->where('sucursal_id', $sucursal->id);
                                $query->whereBetween('fecha', [$fecha_ini, $fecha_fin]);
                                $query->where('verificado', 2);
                            })
                                ->where('producto_id', $producto->id)
                                ->sum('cantidad');
                        @endphp
                        <td>{{ $ventas }}</td>
                        @php
                            $devoluciones = App\Models\DevolucionClienteDetalle::whereHas(
                                'devolucion_cliente',
                                function ($query) use ($sucursal, $cliente, $fecha_ini, $fecha_fin) {
                                    $query->where('sucursal_id', $sucursal->id);
                                    // $query->where('cliente_id', $cliente->id);
                                    $query->whereBetween('fecha', [$fecha_ini, $fecha_fin]);
                                },
                            )
                                ->where('producto_id', $producto->id)
                                ->sum('cantidad');
                        @endphp
                        <td>{{ $devoluciones }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if ($key < count($sucursals) - 1)
            <div class="nueva_pagina"></div>
        @endif
    @endforeach
</body>

</html>
