<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>MovimientoAbastecimiento</title>
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

        .txt_rojo {}

        .img_celda img {
            width: 45px;
        }

        .break_page {
            page-break-after: always;
        }

        .obs {
            font-size: 0.9em;
        }

        .punteado {
            display: block;
            width: 100%;
            border-bottom: dotted 1px black;
            margin-top: 25px;
        }
    </style>
</head>

<body>
    @inject('configuracion', 'App\Models\Configuracion')
    @foreach ($sucursals as $key => $sucursal)
        <div class="encabezado">
            <div class="logo">
                <img src="{{ $configuracion->first()->logo_b64 }}">
            </div>
            <h2 class="titulo">
                {{ $configuracion->first()->nombre_sistema }}
            </h2>
            <h4 class="texto">REPORTE SEMANAL DE MOVIMIENTOS Y ABASTECIMIENTO</h4>
            <h4 class="texto">Sucursal: {{ $sucursal->nombre }}</h4>
            @if ($sucursal->almacen == 0)
                <h4 class="texto">Encargado: {{ $sucursal->user->full_name }}</h4>
            @endif
            <h4 class="fecha">Del {{ date('d/m/Y', strtotime($fecha_ini)) }} al
                {{ date('d/m/Y', strtotime($fecha_fin)) }}
                {{-- <h4 class="fecha">Expedido: {{ date('d-m-Y') }}</h4> --}}
        </div>
        @php
            $fecha_aux = date('Y-m-d', strtotime($fecha_ini));
        @endphp
        <table border="1">
            <thead>
                <tr>
                    <th width="4%">N°</th>
                    <th width="4%">CÓD. PROD</th>
                    <th>PRODUCTO</th>
                    @while ($fecha_aux <= $fecha_fin)
                        <th width="8%">{{ date('d/m/Y', strtotime($fecha_aux)) }}
                            <br />
                            @if ($fecha_aux < $fecha_fin)
                                (SALIDA)
                            @else
                                (SALDO)
                            @endif

                        </th>
                        @php
                            $fecha_aux = date('Y-m-d', strtotime($fecha_aux . ' +1days'));
                        @endphp
                    @endwhile
                </tr>
            </thead>
            <tbody>
                @php
                    $cont = 1;
                    $sucursal_id = $sucursal->id;
                @endphp
                @foreach ($productos as $producto)
                    @php
                        $fecha_aux = date('Y-m-d', strtotime($fecha_ini));
                    @endphp
                    <tr>
                        <td>{{ $cont++ }}</td>
                        <td>{{ $producto->codigo }}</td>
                        <td>{{ $producto->nombre }} {{ $producto->unidad_medida->nombre }}</td>
                        @while ($fecha_aux <= $fecha_fin)
                            @php
                                // OBTENER REGISTROS
                                if ($fecha_aux < $fecha_fin) {
                                    // ventas realizadas
                                    $ventas_realizadas = App\Models\OrdenVentaDetalle::where(
                                        'producto_id',
                                        $producto->id,
                                    );
                                    $ventas_realizadas->whereHas('orden_venta', function ($query) use (
                                        $fecha_aux,
                                        $sucursal_id,
                                    ) {
                                        $query->where('fecha', $fecha_aux)->where('sucursal_id', $sucursal_id);
                                        $query->where('verificado', 2);
                                    });
                                    $total = $ventas_realizadas->sum('cantidad');
                                } else {
                                    // SALDO FINAL
                                    $total = App\Models\KardexProducto::where('producto_id', $producto->id)
                                        ->where('fecha', $fecha_aux)
                                        ->where('sucursal_id', $sucursal_id)
                                        ->where('tipo_registro', '!=', 'DEVOLUCIÓN DE STOCK')
                                        ->get()
                                        ->last();
                                    $total = $total ? $total->cantidad_saldo : 0;
                                }

                                $fecha_aux = date('Y-m-d', strtotime($fecha_aux . ' +1days'));
                            @endphp
                            <td>{{ $total }}</td>
                        @endwhile
                    </tr>
                @endforeach
            </tbody>
        </table>

        <p class='obs'>Observaciones:</p>
        <p class='punteado'></p>
        <p class='punteado'></p>
        <p class='punteado'></p>

        @if ($key < count($sucursals) - 1)
            <div class="break_page"></div>
        @endif
    @endforeach
</body>

</html>
