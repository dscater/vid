<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>OrdenSalidas</title>
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

        table th {
            font-size: 8pt;
        }

        table tbody tr td {
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
            background: rgb(241, 241, 241);
        }

        .bg-principal {
            background: #153f59;
            color: white;
        }

        .bold {
            font-weight: bold;
        }

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
        <h4 class="texto">ÓRDENES DE SALIDA</h4>
        <h4 class="fecha">Fecha: {{ date('d-m-Y', strtotime($fecha)) }}</h4>
    </div>
    @foreach ($orden_salidas as $item)
        <table border="1">
            <tbody>
                <tr>
                    <td class="bold" width="6%">Código: </td>
                    <td>{{ $item->codigo }}</td>
                    <td class="bold">Sucursal/Vehículo: </td>
                    <td>{{ $item->sucursal->nombre }}</td>
                    <td class="bold">Usuario Solicitante: </td>
                    <td>{{ $item->user_solicitante->full_name }}</td>
                </tr>
                <tr>
                    <td class="bold">Fecha: </td>
                    <td>{{ $item->fecha_c }}</td>
                    <td class="bold">Usuario Aprobador: </td>
                    <td>{{ $item->user_aprobador->full_name }}</td=>
                    <td class="bold">Estado: </td>
                    <td>{{ $item->estado }}</td>
                </tr>
                <tr class="gray">
                    <th>N°</th>
                    <th>CÓD. PRODUCTO</th>
                    <th colspan="2">PRODUCTO</th>
                    <th>CANTIDAD</th>
                    <th>CANTIDAD FÍSICA</th>
                </tr>
                @foreach ($item->orden_salida_detalles as $key => $si)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $si->producto->codigo }}</td>
                        <td colspan="2">{{ $si->producto->nombre }} {{ $si->producto->unidad_medida->nombre }}</td>
                        <td class="centreado">{{ $si->cantidad }}</td>
                        <td class="centreado">{{ $si->cantidad_fisica }}</td>
                    </tr>
                @endforeach
                <tr class="gray">
                    <th colspan="4">TOTAL</th>
                    <th>{{ $item->cantidad_total }}</th>
                    <th>{{ $item->orden_salida_detalles->sum('cantidad_fisica') }}</th>
                </tr>
            </tbody>
        </table>
    @endforeach
</body>

</html>
