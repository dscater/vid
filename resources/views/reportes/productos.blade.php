<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Productos</title>
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
            <h4 class="texto">LISTA DE PRODUCTOS</h4>
            <h4 class="texto">Sucursal: {{ $sucursal->nombre }}</h4>
            <h4 class="fecha">Expedido: {{ date('d-m-Y') }}</h4>
        </div>
        <table border="1">
            <thead class="bg-principal">
                <tr>
                    <th width="3%">N°</th>
                    <th width="5%">CÓDIGO</th>
                    <th>NOMBRE</th>
                    <th>CATEGORÍA</th>
                    <th>MARCA</th>
                    <th>UNIDAD DE MEDIDA</th>
                    <th>PRECIO VENTA</th>
                    <th>STOCK DISPONIBLE</th>
                    <th>CANTIDAD MÍNIMA</th>
                    <th>CANTIDAD IDEAL</th>
                    <th>ESTADO</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $cont = 1;

                    $productos = App\Models\Producto::select(
                        'productos.*',
                        DB::raw("(SELECT COALESCE(stock_actual, 0)
              FROM sucursal_productos
              WHERE sucursal_productos.producto_id = productos.id
                AND sucursal_productos.sucursal_id = {$sucursal->id}
              LIMIT 1) AS stock_actual"),
                        DB::raw("(SELECT COALESCE(cantidad_ideal, 0)
              FROM sucursal_productos
              WHERE sucursal_productos.producto_id = productos.id
                AND sucursal_productos.sucursal_id = {$sucursal->id}
              LIMIT 1) AS cantidad_ideal"),
                        DB::raw("(SELECT COALESCE(cantidad_minima, 0)
              FROM sucursal_productos
              WHERE sucursal_productos.producto_id = productos.id
                AND sucursal_productos.sucursal_id = {$sucursal->id}
              LIMIT 1) AS cantidad_minima"),
                    );

                    if ($categoria_id != 'todos') {
                        $productos->where('categoria_id', $categoria_id);
                    }
                    if ($marca_id != 'todos') {
                        $productos->where('marca_id', $marca_id);
                    }
                    if ($unidad_medida_id != 'todos') {
                        $productos->where('unidad_medida_id', $unidad_medida_id);
                    }
                    if ($estado != 'todos') {
                        $productos->where('estado', $estado);
                    }

                    $productos = $productos->get();

                @endphp
                @foreach ($productos as $item)
                    <tr>
                        <td class="centreado">{{ $cont++ }}</td>
                        <td class="">{{ $item->codigo }}</td>
                        <td class="">{{ $item->nombre }}</td>
                        <td class="">{{ $item->categoria->nombre }}</td>
                        <td class="">{{ $item->marca->nombre }}</td>
                        <td class="">{{ $item->unidad_medida->nombre }}</td>
                        <td class="">{{ $item->precio }}</td>
                        <td class="">{{ $item->stock_actual ?? 0 }}</td>
                        <td class="">{{ $item->cantidad_minima ?? 0 }}</td>
                        <td class="">{{ $item->cantidad_ideal ?? 0 }}</td>
                        <td class="centreado">{{ $item->estado == 1 ? 'ACTIVO' : 'INACTIVO' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if ($key < count($sucursals) - 1)
            <div class="break_page"></div>
        @endif
    @endforeach
</body>

</html>
