<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Cliente;
use App\Models\Configuracion;
use App\Models\CuentaCobrar;
use App\Models\DevolucionStock;
use App\Models\HistorialAccion;
use App\Models\Inscripcion;
use App\Models\KardexProducto;
use App\Models\Marca;
use App\Models\OrdenSalida;
use App\Models\OrdenVenta;
use App\Models\Producto;
use App\Models\Proveedor;
use App\Models\SolicitudIngreso;
use App\Models\SolicitudIngresoDetalle;
use App\Models\Sucursal;
use App\Models\User;
use App\Services\ReporteService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use PDF;
use Carbon\Carbon;
use FPDF;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class ReporteController extends Controller
{
    public $titulo = [
        'font' => [
            'bold' => true,
            'size' => 12,
            'family' => 'Times New Roman'
        ],
        'borders' => [
            'allBorders' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_NONE,
            ],
        ],
    ];

    public $textoBold = [
        'font' => [
            'bold' => true,
            'size' => 10,
        ],
    ];

    public $headerTabla = [
        'font' => [
            'bold' => true,
            'size' => 10,
            'color' => ['argb' => 'ffffff'],
        ],
        'alignment' => [
            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
        ],
        'borders' => [
            'allBorders' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            ],
        ],
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'color' => ['rgb' => '203764']
        ],
    ];

    public $bodyTabla = [
        'font' => [
            'size' => 10,
        ],
        'alignment' => [
            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            // 'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
        ],
        'borders' => [
            'allBorders' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            ],
        ],
    ];

    public $textLeft = [
        'alignment' => [
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
        ],
    ];

    public $textRight = [
        'alignment' => [
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
        ],
    ];

    public $textCenter = [
        'alignment' => [
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
        ],
    ];

    private $configuracion = null;
    public function __construct()
    {
        $this->configuracion = Configuracion::first();
        if (!$this->configuracion) {
            $this->configuracion = new Configuracion([
                "nombre_sistema" => "SISPRENDASOL S.A.",
                "alias" => "SP",
                "logo" => "logo.png",
                "fono" => "2222222",
                "dir" => "LOS OLIVOS",
            ]);
        }
    }

    public function usuarios(Request $request)
    {
        ini_set('memory_limit', '1024M');
        set_time_limit(-1);
        $role_id =  $request->role_id;
        $usuarios = User::select("users.*")
            ->where('id', '!=', 1);

        if ($role_id != 'todos') {
            $request->validate([
                'role_id' => 'required',
            ]);
            $usuarios->where('role_id', $role_id);
        }

        $usuarios = $usuarios->orderBy("paterno", "ASC")->get();

        if ($request->tipo == 'pdf') {
            $pdf = PDF::loadView('reportes.usuarios', compact('usuarios'))->setPaper('legal', 'landscape');

            // ENUMERAR LAS PÁGINAS USANDO CANVAS
            $pdf->output();
            $dom_pdf = $pdf->getDomPDF();
            $canvas = $dom_pdf->get_canvas();
            $alto = $canvas->get_height();
            $ancho = $canvas->get_width();
            $canvas->page_text($ancho - 90, $alto - 25, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 9, array(0, 0, 0));

            return $pdf->download('Usuarios.pdf');
        } else {
            $spreadsheet = new Spreadsheet();
            $spreadsheet->getProperties()
                ->setCreator("ADMIN")
                ->setLastModifiedBy('Administración')
                ->setTitle('Registros')
                ->setSubject('Registros')
                ->setDescription('Registros')
                ->setKeywords('PHPSpreadsheet')
                ->setCategory('Listado');

            $sheet = $spreadsheet->getActiveSheet();

            $spreadsheet->getDefaultStyle()->getFont()->setName('Arial');

            $fila = 1;
            if (file_exists(public_path() . '/imgs/' . $this->configuracion->logo)) {
                $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                $drawing->setName('logo');
                $drawing->setDescription('logo');
                $drawing->setPath(public_path() . '/imgs/' . $this->configuracion->logo); // put your path and image here
                $drawing->setCoordinates('A' . $fila);
                $drawing->setOffsetX(5);
                $drawing->setOffsetY(0);
                $drawing->setHeight(60);
                $drawing->setWorksheet($sheet);
            }

            $fila = 2;
            $sheet->setCellValue('A' . $fila, $this->configuracion->nombre_sistema);
            $sheet->mergeCells("A" . $fila . ":M" . $fila);  //COMBINAR CELDAS
            $sheet->getStyle('A' . $fila . ':M' . $fila)->getAlignment()->setHorizontal('center');
            $sheet->getStyle('A' . $fila . ':M' . $fila)->applyFromArray($this->titulo);
            $fila++;
            $sheet->setCellValue('A' . $fila, "LISTA DE USUARIOS");
            $sheet->mergeCells("A" . $fila . ":M" . $fila);  //COMBINAR CELDAS
            $sheet->getStyle('A' . $fila . ':M' . $fila)->getAlignment()->setHorizontal('center');
            $sheet->getStyle('A' . $fila . ':M' . $fila)->applyFromArray($this->titulo);
            $fila++;
            $fila++;
            $sheet->setCellValue('A' . $fila, 'N°');
            $sheet->setCellValue('B' . $fila, 'USUARIO');
            $sheet->setCellValue('C' . $fila, 'PATERNO');
            $sheet->setCellValue('D' . $fila, 'MATERNO');
            $sheet->setCellValue('E' . $fila, 'NOMBRE(S)');
            $sheet->setCellValue('F' . $fila, 'C.I.');
            $sheet->setCellValue('G' . $fila, 'DIRECCIÓN');
            $sheet->setCellValue('H' . $fila, 'CORREO');
            $sheet->setCellValue('I' . $fila, 'TELÉFONO/CELULAR');
            $sheet->setCellValue('J' . $fila, 'TIPO');
            $sheet->setCellValue('K' . $fila, 'ROLE');
            $sheet->setCellValue('L' . $fila, 'ACCESO');
            $sheet->setCellValue('M' . $fila, 'FECHA DE REGISTRO');
            $sheet->getStyle('A' . $fila . ':M' . $fila)->applyFromArray($this->headerTabla);
            $fila++;

            foreach ($usuarios as $key => $item) {
                $sheet->setCellValue('A' . $fila, $key + 1);
                $sheet->setCellValue('B' . $fila, $item->usuario);
                $sheet->setCellValue('C' . $fila, $item->paterno);
                $sheet->setCellValue('D' . $fila, $item->materno);
                $sheet->setCellValue('E' . $fila, $item->nombre);
                $sheet->setCellValue('F' . $fila, $item->full_ci);
                $sheet->setCellValue('G' . $fila, $item->dir);
                $sheet->setCellValue('H' . $fila, $item->correo);
                $sheet->setCellValue('I' . $fila, $item->fono);
                $sheet->setCellValue('J' . $fila, $item->tipo);
                $sheet->setCellValue('K' . $fila, $item->role->nombre);
                $sheet->setCellValue('L' . $fila, $item->acceso == 1 ? 'HABILITADO' : 'DENEGADO');
                $sheet->setCellValue('M' . $fila, $item->fecha_registro_t);
                $sheet->getStyle('A' . $fila . ':M' . $fila)->applyFromArray($this->bodyTabla);
                $fila++;
            }

            $sheet->getColumnDimension('A')->setWidth(6);
            $sheet->getColumnDimension('B')->setWidth(15);
            $sheet->getColumnDimension('C')->setWidth(15);
            $sheet->getColumnDimension('D')->setWidth(10);
            $sheet->getColumnDimension('E')->setWidth(20);
            $sheet->getColumnDimension('F')->setWidth(12);
            $sheet->getColumnDimension('G')->setWidth(15);
            $sheet->getColumnDimension('H')->setWidth(15);
            $sheet->getColumnDimension('I')->setWidth(13);
            $sheet->getColumnDimension('I')->setWidth(13);
            $sheet->getColumnDimension('J')->setWidth(12);
            $sheet->getColumnDimension('K')->setWidth(12);
            $sheet->getColumnDimension('L')->setWidth(12);
            $sheet->getColumnDimension('M')->setWidth(12);

            foreach (range('A', 'M') as $columnID) {
                $sheet->getStyle($columnID)->getAlignment()->setWrapText(true);
            }

            $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
            $sheet->getPageMargins()->setTop(0.5);
            $sheet->getPageMargins()->setRight(0.1);
            $sheet->getPageMargins()->setLeft(0.1);
            $sheet->getPageMargins()->setBottom(0.1);
            $sheet->getPageSetup()->setPrintArea('A:M');
            $sheet->getPageSetup()->setFitToWidth(1);
            $sheet->getPageSetup()->setFitToHeight(0);

            return response()->streamDownload(
                function () use ($spreadsheet) {
                    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
                    $writer->save('php://output');
                },
                'usuarios_' . time() . '.xlsx',
                [
                    'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                ]
            );
        }
    }

    public function productos(Request $request)
    {
        ini_set('memory_limit', '1024M');
        set_time_limit(-1);
        $categoria_id =  $request->categoria_id;
        $marca_id =  $request->marca_id;
        $unidad_medida_id =  $request->unidad_medida_id;
        $sucursal_id =  $request->sucursal_id;
        $estado =  $request->estado;

        $sucursals = Sucursal::select("sucursals.*");
        if ($sucursal_id != 'todos') {
            $sucursals->where("id", $sucursal_id);
        }

        $sucursals = $sucursals->get();

        if ($request->tipo == 'pdf') {
            $pdf = PDF::loadView('reportes.productos', compact(
                'sucursals',
                "categoria_id",
                "marca_id",
                "unidad_medida_id",
                "estado",

            ))->setPaper('letter', 'portrait');

            // ENUMERAR LAS PÁGINAS USANDO CANVAS
            $pdf->output();
            $dom_pdf = $pdf->getDomPDF();
            $canvas = $dom_pdf->get_canvas();
            $alto = $canvas->get_height();
            $ancho = $canvas->get_width();
            $canvas->page_text($ancho - 90, $alto - 25, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 9, array(0, 0, 0));

            return $pdf->download('Usuarios.pdf');
        } else {
            $spreadsheet = new Spreadsheet();
            $spreadsheet->getProperties()
                ->setCreator("ADMIN")
                ->setLastModifiedBy('Administración')
                ->setTitle('Registros')
                ->setSubject('Registros')
                ->setDescription('Registros')
                ->setKeywords('PHPSpreadsheet')
                ->setCategory('Listado');

            $sheet = $spreadsheet->getActiveSheet();

            $spreadsheet->getDefaultStyle()->getFont()->setName('Arial');

            $fila = 1;
            if (file_exists(public_path() . '/imgs/' . $this->configuracion->logo)) {
                $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                $drawing->setName('logo');
                $drawing->setDescription('logo');
                $drawing->setPath(public_path() . '/imgs/' . $this->configuracion->logo); // put your path and image here
                $drawing->setCoordinates('A' . $fila);
                $drawing->setOffsetX(5);
                $drawing->setOffsetY(0);
                $drawing->setHeight(60);
                $drawing->setWorksheet($sheet);
            }

            $fila = 2;

            foreach ($sucursals as $sucursal) {
                $sheet->setCellValue('A' . $fila, $this->configuracion->nombre_sistema);
                $sheet->mergeCells("A" . $fila . ":K" . $fila);  //COMBINAR CELDAS
                $sheet->getStyle('A' . $fila . ':K' . $fila)->getAlignment()->setHorizontal('center');
                $sheet->getStyle('A' . $fila . ':K' . $fila)->applyFromArray($this->titulo);
                $fila++;
                $sheet->setCellValue('A' . $fila, "LISTA DE PRODUCTOS");
                $sheet->mergeCells("A" . $fila . ":K" . $fila);  //COMBINAR CELDAS
                $sheet->getStyle('A' . $fila . ':K' . $fila)->getAlignment()->setHorizontal('center');
                $sheet->getStyle('A' . $fila . ':K' . $fila)->applyFromArray($this->titulo);
                $fila++;
                $sheet->setCellValue('A' . $fila, "Sucursal: " . $sucursal->nombre);
                $sheet->mergeCells("A" . $fila . ":K" . $fila);  //COMBINAR CELDAS
                $sheet->getStyle('A' . $fila . ':K' . $fila)->getAlignment()->setHorizontal('center');
                $sheet->getStyle('A' . $fila . ':K' . $fila)->applyFromArray($this->titulo);
                $fila++;
                $fila++;
                $sheet->setCellValue('A' . $fila, 'N°');
                $sheet->setCellValue('B' . $fila, 'CÓDIGO');
                $sheet->setCellValue('C' . $fila, 'NOMBRE');
                $sheet->setCellValue('D' . $fila, 'CATEGORÍA');
                $sheet->setCellValue('E' . $fila, 'MARCA');
                $sheet->setCellValue('F' . $fila, 'UNIDAD DE MEDIDA');
                $sheet->setCellValue('G' . $fila, 'PRECIO VENTA');
                $sheet->setCellValue('H' . $fila, 'STOCK DISPONIBLE');
                $sheet->setCellValue('I' . $fila, 'CANTIDAD MÍNIMA');
                $sheet->setCellValue('J' . $fila, 'CANTIDAD IDEAL');
                $sheet->setCellValue('K' . $fila, 'ESTADO');
                $sheet->getStyle('A' . $fila . ':K' . $fila)->applyFromArray($this->headerTabla);
                $fila++;

                $productos = Producto::select(
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
              LIMIT 1) AS cantidad_minima")
                );

                if ($categoria_id != 'todos') {
                    $productos->where("categoria_id", $categoria_id);
                }
                if ($marca_id != 'todos') {
                    $productos->where("marca_id", $marca_id);
                }
                if ($unidad_medida_id != 'todos') {
                    $productos->where("unidad_medida_id", $unidad_medida_id);
                }
                if ($estado != 'todos') {
                    $productos->where("estado", $estado);
                }

                $productos = $productos->get();

                foreach ($productos as $key => $item) {
                    $sheet->setCellValue('A' . $fila, $key + 1);
                    $sheet->setCellValue('B' . $fila, $item->codigo);
                    $sheet->setCellValue('C' . $fila, $item->nombre);
                    $sheet->setCellValue('D' . $fila, $item->categoria->nombre);
                    $sheet->setCellValue('E' . $fila, $item->marca->nombre);
                    $sheet->setCellValue('F' . $fila, $item->unidad_medida->nombre);
                    $sheet->setCellValue('G' . $fila, $item->precio);
                    $sheet->setCellValue('H' . $fila, $item->stock_actual ?? 0);
                    $sheet->setCellValue('I' . $fila, $item->cantidad_minima ?? 0);
                    $sheet->setCellValue('J' . $fila, $item->cantidad_ideal ?? 0);
                    $sheet->setCellValue('K' . $fila, $item->estado == 1 ? 'ACTIVO' : 'INACTIVO');
                    $sheet->setCellValue('M' . $fila, $item->fecha_registro_t);
                    $sheet->getStyle('A' . $fila . ':K' . $fila)->applyFromArray($this->bodyTabla);
                    $fila++;
                }
                $fila += 4;
            }

            $sheet->getColumnDimension('A')->setWidth(6);
            $sheet->getColumnDimension('B')->setWidth(15);
            $sheet->getColumnDimension('C')->setWidth(15);
            $sheet->getColumnDimension('D')->setWidth(10);
            $sheet->getColumnDimension('E')->setWidth(20);
            $sheet->getColumnDimension('F')->setWidth(12);
            $sheet->getColumnDimension('G')->setWidth(15);
            $sheet->getColumnDimension('H')->setWidth(15);
            $sheet->getColumnDimension('I')->setWidth(13);
            $sheet->getColumnDimension('I')->setWidth(13);
            $sheet->getColumnDimension('J')->setWidth(12);
            $sheet->getColumnDimension('K')->setWidth(12);
            $sheet->getColumnDimension('L')->setWidth(12);

            foreach (range('A', 'M') as $columnID) {
                $sheet->getStyle($columnID)->getAlignment()->setWrapText(true);
            }

            $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
            $sheet->getPageMargins()->setTop(0.5);
            $sheet->getPageMargins()->setRight(0.1);
            $sheet->getPageMargins()->setLeft(0.1);
            $sheet->getPageMargins()->setBottom(0.1);
            $sheet->getPageSetup()->setPrintArea('A:K');
            $sheet->getPageSetup()->setFitToWidth(1);
            $sheet->getPageSetup()->setFitToHeight(0);

            return response()->streamDownload(
                function () use ($spreadsheet) {
                    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
                    $writer->save('php://output');
                },
                'productos_' . time() . '.xlsx',
                [
                    'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                ]
            );
        }
    }

    public function clientes(Request $request)
    {
        ini_set('memory_limit', '1024M');
        set_time_limit(-1);
        $tipo_cliente =  $request->tipo_cliente;
        $estado =  $request->estado;
        $clientes = Cliente::select("clientes.*");

        if ($tipo_cliente != 'todos') {
            $clientes->where('tipo', $tipo_cliente);
        }

        if ($estado != 'todos') {
            $clientes->where('estado', $estado);
        }

        $clientes = $clientes->get();

        if ($request->tipo == 'pdf') {
            $pdf = PDF::loadView('reportes.clientes', compact('clientes'))->setPaper('legal', 'landscape');

            // ENUMERAR LAS PÁGINAS USANDO CANVAS
            $pdf->output();
            $dom_pdf = $pdf->getDomPDF();
            $canvas = $dom_pdf->get_canvas();
            $alto = $canvas->get_height();
            $ancho = $canvas->get_width();
            $canvas->page_text($ancho - 90, $alto - 25, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 9, array(0, 0, 0));

            return $pdf->download('Usuarios.pdf');
        } else {
            $spreadsheet = new Spreadsheet();
            $spreadsheet->getProperties()
                ->setCreator("ADMIN")
                ->setLastModifiedBy('Administración')
                ->setTitle('Registros')
                ->setSubject('Registros')
                ->setDescription('Registros')
                ->setKeywords('PHPSpreadsheet')
                ->setCategory('Listado');

            $sheet = $spreadsheet->getActiveSheet();

            $spreadsheet->getDefaultStyle()->getFont()->setName('Arial');

            $fila = 1;
            if (file_exists(public_path() . '/imgs/' . $this->configuracion->logo)) {
                $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                $drawing->setName('logo');
                $drawing->setDescription('logo');
                $drawing->setPath(public_path() . '/imgs/' . $this->configuracion->logo); // put your path and image here
                $drawing->setCoordinates('A' . $fila);
                $drawing->setOffsetX(5);
                $drawing->setOffsetY(0);
                $drawing->setHeight(60);
                $drawing->setWorksheet($sheet);
            }

            $fila = 2;
            $sheet->setCellValue('A' . $fila, $this->configuracion->nombre_sistema);
            $sheet->mergeCells("A" . $fila . ":M" . $fila);  //COMBINAR CELDAS
            $sheet->getStyle('A' . $fila . ':M' . $fila)->getAlignment()->setHorizontal('center');
            $sheet->getStyle('A' . $fila . ':M' . $fila)->applyFromArray($this->titulo);
            $fila++;
            $sheet->setCellValue('A' . $fila, "LISTA DE CLIENTES");
            $sheet->mergeCells("A" . $fila . ":M" . $fila);  //COMBINAR CELDAS
            $sheet->getStyle('A' . $fila . ':M' . $fila)->getAlignment()->setHorizontal('center');
            $sheet->getStyle('A' . $fila . ':M' . $fila)->applyFromArray($this->titulo);
            $fila++;
            $fila++;
            $sheet->setCellValue('A' . $fila, 'N°');
            $sheet->setCellValue('B' . $fila, 'RAZÓN SOCIAL');
            $sheet->setCellValue('C' . $fila, 'TIPO');
            $sheet->setCellValue('D' . $fila, 'NIT');
            $sheet->setCellValue('E' . $fila, 'NOMBRE PUNTO DE VENTA');
            $sheet->setCellValue('F' . $fila, 'NOMBRE DEL PROPIETARIO');
            $sheet->setCellValue('G' . $fila, 'C.I. DEL PROPIETARIO');
            $sheet->setCellValue('H' . $fila, 'CORREO');
            $sheet->setCellValue('I' . $fila, 'CELULAR');
            $sheet->setCellValue('J' . $fila, 'TELÉFONO');
            $sheet->setCellValue('K' . $fila, 'DIRECCIÓN');
            $sheet->setCellValue('L' . $fila, 'CIUDAD');
            $sheet->setCellValue('M' . $fila, 'ESTADO');
            $sheet->getStyle('A' . $fila . ':M' . $fila)->applyFromArray($this->headerTabla);
            $fila++;

            foreach ($clientes as $key => $item) {
                $sheet->setCellValue('A' . $fila, $key + 1);
                $sheet->setCellValue('B' . $fila, $item->razon_social);
                $sheet->setCellValue('C' . $fila, $item->tipo);
                $sheet->setCellValue('D' . $fila, $item->nit);
                $sheet->setCellValue('E' . $fila, $item->nombre_punto);
                $sheet->setCellValue('F' . $fila, $item->nombre_prop);
                $sheet->setCellValue('G' . $fila, $item->ci_prop);
                $sheet->setCellValue('H' . $fila, $item->correo);
                $sheet->setCellValue('I' . $fila, $item->cel);
                $sheet->setCellValue('J' . $fila, $item->fono);
                $sheet->setCellValue('K' . $fila, $item->dir);
                $sheet->setCellValue('L' . $fila, $item->ciudad);
                $sheet->setCellValue('M' . $fila,  $item->estado == 1 ? 'ACTIVO' : 'INACTIVO');
                $sheet->getStyle('A' . $fila . ':M' . $fila)->applyFromArray($this->bodyTabla);
                $fila++;
            }

            $sheet->getColumnDimension('A')->setWidth(6);
            $sheet->getColumnDimension('B')->setWidth(15);
            $sheet->getColumnDimension('C')->setWidth(15);
            $sheet->getColumnDimension('D')->setWidth(10);
            $sheet->getColumnDimension('E')->setWidth(20);
            $sheet->getColumnDimension('F')->setWidth(12);
            $sheet->getColumnDimension('G')->setWidth(15);
            $sheet->getColumnDimension('H')->setWidth(15);
            $sheet->getColumnDimension('I')->setWidth(13);
            $sheet->getColumnDimension('I')->setWidth(13);
            $sheet->getColumnDimension('J')->setWidth(12);
            $sheet->getColumnDimension('K')->setWidth(12);
            $sheet->getColumnDimension('L')->setWidth(12);
            $sheet->getColumnDimension('M')->setWidth(12);

            foreach (range('A', 'M') as $columnID) {
                $sheet->getStyle($columnID)->getAlignment()->setWrapText(true);
            }

            $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
            $sheet->getPageMargins()->setTop(0.5);
            $sheet->getPageMargins()->setRight(0.1);
            $sheet->getPageMargins()->setLeft(0.1);
            $sheet->getPageMargins()->setBottom(0.1);
            $sheet->getPageSetup()->setPrintArea('A:M');
            $sheet->getPageSetup()->setFitToWidth(1);
            $sheet->getPageSetup()->setFitToHeight(0);

            return response()->streamDownload(
                function () use ($spreadsheet) {
                    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
                    $writer->save('php://output');
                },
                'clientes_' . time() . '.xlsx',
                [
                    'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                ]
            );
        }
    }

    public function proveedors(Request $request)
    {
        ini_set('memory_limit', '1024M');
        set_time_limit(-1);
        $tipo_proveedor =  $request->tipo_proveedor;
        $estado =  $request->estado;
        $proveedors = Proveedor::select("proveedors.*");

        if ($tipo_proveedor != 'todos') {
            $proveedors->where('tipo', $tipo_proveedor);
        }

        if ($estado != 'todos') {
            $proveedors->where('estado', $estado);
        }

        $proveedors = $proveedors->get();

        if ($request->tipo == 'pdf') {
            $pdf = PDF::loadView('reportes.proveedors', compact('proveedors'))->setPaper('legal', 'landscape');

            // ENUMERAR LAS PÁGINAS USANDO CANVAS
            $pdf->output();
            $dom_pdf = $pdf->getDomPDF();
            $canvas = $dom_pdf->get_canvas();
            $alto = $canvas->get_height();
            $ancho = $canvas->get_width();
            $canvas->page_text($ancho - 90, $alto - 25, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 9, array(0, 0, 0));

            return $pdf->download('Usuarios.pdf');
        } else {
            $spreadsheet = new Spreadsheet();
            $spreadsheet->getProperties()
                ->setCreator("ADMIN")
                ->setLastModifiedBy('Administración')
                ->setTitle('Registros')
                ->setSubject('Registros')
                ->setDescription('Registros')
                ->setKeywords('PHPSpreadsheet')
                ->setCategory('Listado');

            $sheet = $spreadsheet->getActiveSheet();

            $spreadsheet->getDefaultStyle()->getFont()->setName('Arial');

            $fila = 1;
            if (file_exists(public_path() . '/imgs/' . $this->configuracion->logo)) {
                $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                $drawing->setName('logo');
                $drawing->setDescription('logo');
                $drawing->setPath(public_path() . '/imgs/' . $this->configuracion->logo); // put your path and image here
                $drawing->setCoordinates('A' . $fila);
                $drawing->setOffsetX(5);
                $drawing->setOffsetY(0);
                $drawing->setHeight(60);
                $drawing->setWorksheet($sheet);
            }

            $fila = 2;
            $sheet->setCellValue('A' . $fila, $this->configuracion->nombre_sistema);
            $sheet->mergeCells("A" . $fila . ":M" . $fila);  //COMBINAR CELDAS
            $sheet->getStyle('A' . $fila . ':M' . $fila)->getAlignment()->setHorizontal('center');
            $sheet->getStyle('A' . $fila . ':M' . $fila)->applyFromArray($this->titulo);
            $fila++;
            $sheet->setCellValue('A' . $fila, "LISTA DE PROVEEDORES");
            $sheet->mergeCells("A" . $fila . ":M" . $fila);  //COMBINAR CELDAS
            $sheet->getStyle('A' . $fila . ':M' . $fila)->getAlignment()->setHorizontal('center');
            $sheet->getStyle('A' . $fila . ':M' . $fila)->applyFromArray($this->titulo);
            $fila++;
            $fila++;
            $sheet->setCellValue('A' . $fila, 'N°');
            $sheet->setCellValue('B' . $fila, 'RAZÓN SOCIAL');
            $sheet->setCellValue('C' . $fila, 'NOMBRE COMERCIAL');
            $sheet->setCellValue('D' . $fila, 'NIT');
            $sheet->setCellValue('E' . $fila, 'MONEDA');
            $sheet->setCellValue('F' . $fila, 'TELÉFONO EMPRESA');
            $sheet->setCellValue('G' . $fila, 'CORREO');
            $sheet->setCellValue('H' . $fila, 'DIRECCIÓN');
            $sheet->setCellValue('I' . $fila, 'CIUDAD');
            $sheet->setCellValue('J' . $fila, 'TIPO');
            $sheet->setCellValue('K' . $fila, 'CATEGORÍAS');
            $sheet->setCellValue('L' . $fila, 'MARCAS');
            $sheet->setCellValue('M' . $fila, 'ESTADO');
            $sheet->getStyle('A' . $fila . ':M' . $fila)->applyFromArray($this->headerTabla);
            $fila++;

            foreach ($proveedors as $key => $item) {
                $categorias = Categoria::whereIn('id', $item->categorias)->pluck('nombre')->toArray();
                $categorias = implode(', ', $categorias);
                $marcas = Marca::whereIn('id', $item->marcas)->pluck('nombre')->toArray();
                $marcas = implode(', ', $marcas);

                $sheet->setCellValue('A' . $fila, $key + 1);
                $sheet->setCellValue('B' . $fila, $item->razon_social);
                $sheet->setCellValue('C' . $fila, $item->nombre_com);
                $sheet->setCellValue('D' . $fila, $item->nit);
                $sheet->setCellValue('E' . $fila, $item->moneda);
                $sheet->setCellValue('F' . $fila, $item->fono_emp);
                $sheet->setCellValue('G' . $fila, $item->correo);
                $sheet->setCellValue('H' . $fila, $item->dir);
                $sheet->setCellValue('I' . $fila, $item->ciudad);
                $sheet->setCellValue('J' . $fila, $item->tipo);
                $sheet->setCellValue('K' . $fila, $categorias);
                $sheet->setCellValue('L' . $fila, $marcas);
                $sheet->setCellValue('M' . $fila,  $item->estado == 1 ? 'ACTIVO' : 'INACTIVO');
                $sheet->getStyle('A' . $fila . ':M' . $fila)->applyFromArray($this->bodyTabla);
                $fila++;
            }

            $sheet->getColumnDimension('A')->setWidth(6);
            $sheet->getColumnDimension('B')->setWidth(15);
            $sheet->getColumnDimension('C')->setWidth(15);
            $sheet->getColumnDimension('D')->setWidth(10);
            $sheet->getColumnDimension('E')->setWidth(20);
            $sheet->getColumnDimension('F')->setWidth(12);
            $sheet->getColumnDimension('G')->setWidth(15);
            $sheet->getColumnDimension('H')->setWidth(15);
            $sheet->getColumnDimension('I')->setWidth(13);
            $sheet->getColumnDimension('I')->setWidth(13);
            $sheet->getColumnDimension('J')->setWidth(12);
            $sheet->getColumnDimension('K')->setWidth(12);
            $sheet->getColumnDimension('L')->setWidth(12);

            foreach (range('A', 'M') as $columnID) {
                $sheet->getStyle($columnID)->getAlignment()->setWrapText(true);
            }

            $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
            $sheet->getPageMargins()->setTop(0.5);
            $sheet->getPageMargins()->setRight(0.1);
            $sheet->getPageMargins()->setLeft(0.1);
            $sheet->getPageMargins()->setBottom(0.1);
            $sheet->getPageSetup()->setPrintArea('A:M');
            $sheet->getPageSetup()->setFitToWidth(1);
            $sheet->getPageSetup()->setFitToHeight(0);

            return response()->streamDownload(
                function () use ($spreadsheet) {
                    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
                    $writer->save('php://output');
                },
                'proveedors_' . time() . '.xlsx',
                [
                    'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                ]
            );
        }
    }

    public function movimiento_inventario(Request $request)
    {
        ini_set('memory_limit', '1024M');
        set_time_limit(-1);
        $producto_id =  $request->producto_id;
        $sucursal_id =  $request->sucursal_id;
        $user_id =  $request->user_id;
        $tipo_movimiento =  $request->tipo_movimiento;
        $fecha =  $request->fecha;
        $sucursals = Sucursal::select("sucursals.*");

        if ($sucursal_id != 'todos') {
            $sucursals->where('id', $sucursal_id);
        }
        $sucursals = $sucursals->where("estado", 1)->get();
        $productos = Producto::select("productos.*");
        if ($producto_id != 'todos') {
            $productos->where("id", $producto_id);
        }

        $productos = $productos->where("estado", 1)->get();

        $array_dias = [
            '0' => 'Domingo',
            '1' => 'Lunes',
            '2' => 'Martes',
            '3' => 'Miércoles',
            '4' => 'Jueves',
            '5' => 'Viernes',
            '6' => 'Sábado',
        ];
        $array_meses = [
            '01' => 'enero',
            '02' => 'febrero',
            '03' => 'marzo',
            '04' => 'abril',
            '05' => 'mayo',
            '06' => 'junio',
            '07' => 'julio',
            '08' => 'agosto',
            '09' => 'septiembre',
            '10' => 'octubre',
            '11' => 'noviembre',
            '12' => 'diciembre',
        ];

        if ($request->tipo == 'pdf') {
            $pdf = PDF::loadView('reportes.movimiento_inventario', compact('sucursals', 'productos', 'array_dias', 'array_meses', 'fecha', 'user_id', 'tipo_movimiento'))->setPaper('legal', 'landscape');

            // ENUMERAR LAS PÁGINAS USANDO CANVAS
            $pdf->output();
            $dom_pdf = $pdf->getDomPDF();
            $canvas = $dom_pdf->get_canvas();
            $alto = $canvas->get_height();
            $ancho = $canvas->get_width();
            $canvas->page_text($ancho - 90, $alto - 25, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 9, array(0, 0, 0));

            return $pdf->download('Usuarios.pdf');
        } else {
            $spreadsheet = new Spreadsheet();
            $spreadsheet->getProperties()
                ->setCreator("ADMIN")
                ->setLastModifiedBy('Administración')
                ->setTitle('Registros')
                ->setSubject('Registros')
                ->setDescription('Registros')
                ->setKeywords('PHPSpreadsheet')
                ->setCategory('Listado');

            $sheet = $spreadsheet->getActiveSheet();

            $spreadsheet->getDefaultStyle()->getFont()->setName('Arial');

            $fila = 1;
            if (file_exists(public_path() . '/imgs/' . $this->configuracion->logo)) {
                $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                $drawing->setName('logo');
                $drawing->setDescription('logo');
                $drawing->setPath(public_path() . '/imgs/' . $this->configuracion->logo); // put your path and image here
                $drawing->setCoordinates('A' . $fila);
                $drawing->setOffsetX(5);
                $drawing->setOffsetY(0);
                $drawing->setHeight(60);
                $drawing->setWorksheet($sheet);
            }

            $fila = 2;

            foreach ($sucursals as $sucursal) {
                $sheet->setCellValue('A' . $fila, $this->configuracion->nombre_sistema);
                $sheet->mergeCells("A" . $fila . ":J" . $fila);  //COMBINAR CELDAS
                $sheet->getStyle('A' . $fila . ':J' . $fila)->getAlignment()->setHorizontal('center');
                $sheet->getStyle('A' . $fila . ':J' . $fila)->applyFromArray($this->titulo);
                $fila++;
                $sheet->setCellValue('A' . $fila, "KARDEX DE INVENTARIO");
                $sheet->mergeCells("A" . $fila . ":J" . $fila);  //COMBINAR CELDAS
                $sheet->getStyle('A' . $fila . ':J' . $fila)->getAlignment()->setHorizontal('center');
                $sheet->getStyle('A' . $fila . ':J' . $fila)->applyFromArray($this->titulo);
                $fila++;
                $sheet->setCellValue('A' . $fila, "Sucursal: " . $sucursal->nombre);
                $sheet->mergeCells("A" . $fila . ":J" . $fila);  //COMBINAR CELDAS
                $sheet->getStyle('A' . $fila . ':J' . $fila)->getAlignment()->setHorizontal('center');
                $sheet->getStyle('A' . $fila . ':J' . $fila)->applyFromArray($this->titulo);
                $fila++;
                $sheet->setCellValue('A' . $fila, $array_dias[date('w')] . ', ' . date('d') . ' de ' . $array_meses[date('m')] . ' de ' . date('Y'));
                $sheet->mergeCells("A" . $fila . ":J" . $fila);  //COMBINAR CELDAS
                $sheet->getStyle('A' . $fila . ':J' . $fila)->getAlignment()->setHorizontal('center');
                $sheet->getStyle('A' . $fila . ':J' . $fila)->applyFromArray($this->titulo);
                $fila++;
                $sheet->setCellValue('A' . $fila, '(Expresado en bolivianos)');
                $sheet->mergeCells("A" . $fila . ":J" . $fila);  //COMBINAR CELDAS
                $sheet->getStyle('A' . $fila . ':J' . $fila)->getAlignment()->setHorizontal('center');
                $sheet->getStyle('A' . $fila . ':J' . $fila)->applyFromArray($this->titulo);
                $fila++;
                $fila++;

                foreach ($productos as $registro) {
                    $sheet->setCellValue('A' . $fila, $registro->nombre . ' ' . $registro->unidad_medida->nombre);
                    $sheet->mergeCells("A" . $fila . ":J" . $fila);  //COMBINAR CELDAS
                    $sheet->getStyle('A' . $fila . ':J' . $fila)->applyFromArray($this->headerTabla);
                    $sheet->getStyle('A' . $fila . ':J' . $fila)->applyFromArray($this->textCenter);
                    $fila++;
                    $sheet->getStyle('A' . $fila . ':J' . $fila)->applyFromArray($this->textCenter);
                    $sheet->setCellValue('A' . $fila, 'FECHA');
                    $sheet->mergeCells("A" . $fila . ":A" . ($fila + 1));  //COMBINAR CELDAS
                    $sheet->setCellValue('B' . $fila, 'USUARIO RESPONSABLE');
                    $sheet->mergeCells("B" . $fila . ":B" . ($fila + 1));  //COMBINAR CELDAS
                    $sheet->setCellValue('C' . $fila, 'DETALLE');
                    $sheet->mergeCells("C" . $fila . ":C" . ($fila + 1));  //COMBINAR CELDAS
                    $sheet->setCellValue('D' . $fila, 'CANTIDADES');
                    $sheet->mergeCells("D" . $fila . ":F" . $fila);  //COMBINAR CELDAS
                    $sheet->setCellValue('G' . $fila, 'P/U');
                    $sheet->setCellValue('H' . $fila, 'BOLIVIANOS');
                    $sheet->mergeCells("H" . $fila . ":J" . $fila);  //COMBINAR CELDAS
                    $sheet->getStyle('A' . $fila . ':J' . $fila)->applyFromArray($this->headerTabla);
                    $sheet->getStyle('A' . $fila . ':J' . $fila)->applyFromArray($this->textCenter);
                    $fila++;
                    $sheet->setCellValue('D' . $fila, 'ENTRADA');
                    $sheet->setCellValue('E' . $fila, 'SALIDA');
                    $sheet->setCellValue('F' . $fila, 'SALDO');

                    $sheet->setCellValue('H' . $fila, 'ENTRADA');
                    $sheet->setCellValue('I' . $fila, 'SALIDA');
                    $sheet->setCellValue('J' . $fila, 'SALDO');

                    $sheet->getStyle('A' . $fila . ':J' . $fila)->applyFromArray($this->headerTabla);
                    $sheet->getStyle('A' . $fila . ':J' . $fila)->applyFromArray($this->textCenter);
                    $fila++;
                    $kardex_productos = KardexProducto::where("producto_id", $registro->id)
                        ->where("sucursal_id", $sucursal->id);

                    if ($tipo_movimiento != 'todos') {
                        if ($tipo_movimiento == 'ajuste') {
                            $kardex_productos->where("detalle", 'INGRESO POR AJUSTE');
                        } else {
                            $kardex_productos->where("tipo_is", $tipo_movimiento);
                        }
                    }

                    if ($fecha) {
                        $kardex_productos->where("fecha", $fecha);
                    }
                    if ($user_id != 'todos') {
                        $kardex_productos->where("user_id", $user_id);
                    }

                    $kardex_productos = $kardex_productos->get();
                    if (count($kardex_productos) > 0) {
                        foreach ($kardex_productos as $key => $value) {
                            $sheet->setCellValue('A' . $fila,  $value->fecha_hora);
                            $sheet->setCellValue('B' . $fila,  $value->user->full_name);
                            $sheet->setCellValue('C' . $fila, $value['detalle']);
                            $sheet->setCellValue('D' . $fila,  $value['cantidad_ingreso']);
                            $sheet->setCellValue('E' . $fila, $value['cantidad_salida']);
                            $sheet->setCellValue('F' . $fila, $value['cantidad_saldo']);
                            $sheet->setCellValue('G' . $fila, number_format($value['cu'], 2, '.', ','));
                            $sheet->setCellValue('H' . $fila,  $value['monto_ingreso']);
                            $sheet->setCellValue('I' . $fila,  $value['monto_salida']);
                            $sheet->setCellValue('J' . $fila, number_format($value['monto_saldo'], 2, '.', ','));
                            $sheet->getStyle('A' . $fila . ':J' . $fila)->applyFromArray($this->bodyTabla);
                            $fila++;
                        }
                    } else {
                        $sheet->setCellValue('A' . $fila, 'NO SE ENCONTRARÓN REGISTROS');
                        $sheet->mergeCells("A" . $fila . ":J" . $fila);  //COMBINAR CELDAS
                    }
                    $fila += 3;
                }
                $fila += 3;
            }

            $sheet->getColumnDimension('A')->setWidth(20);
            $sheet->getColumnDimension('B')->setWidth(15);
            $sheet->getColumnDimension('C')->setWidth(15);
            $sheet->getColumnDimension('D')->setWidth(10);
            $sheet->getColumnDimension('E')->setWidth(20);
            $sheet->getColumnDimension('F')->setWidth(12);
            $sheet->getColumnDimension('G')->setWidth(15);
            $sheet->getColumnDimension('H')->setWidth(15);
            $sheet->getColumnDimension('I')->setWidth(13);
            $sheet->getColumnDimension('J')->setWidth(13);

            foreach (range('A', 'J') as $columnID) {
                $sheet->getStyle($columnID)->getAlignment()->setWrapText(true);
            }

            $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
            $sheet->getPageMargins()->setTop(0.5);
            $sheet->getPageMargins()->setRight(0.1);
            $sheet->getPageMargins()->setLeft(0.1);
            $sheet->getPageMargins()->setBottom(0.1);
            $sheet->getPageSetup()->setPrintArea('A:J');
            $sheet->getPageSetup()->setFitToWidth(1);
            $sheet->getPageSetup()->setFitToHeight(0);

            return response()->streamDownload(
                function () use ($spreadsheet) {
                    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
                    $writer->save('php://output');
                },
                'productos_' . time() . '.xlsx',
                [
                    'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                ]
            );
        }
    }

    public function solicitud_ingresos(Request $request)
    {
        ini_set('memory_limit', '1024M');
        set_time_limit(-1);
        $proveedor_id =  $request->proveedor_id;
        $fecha =  $request->fecha;
        $estado =  $request->estado;
        $solicitud_ingresos = SolicitudIngreso::select("solicitud_ingresos.*");

        if ($proveedor_id != 'todos') {
            $solicitud_ingresos->where('proveedor_id', $proveedor_id);
        }

        if ($estado != 'todos') {
            $solicitud_ingresos->where('estado', $estado);
        }

        if ($fecha) {
            $solicitud_ingresos->where('fecha_ingreso', $fecha);
        }

        $solicitud_ingresos = $solicitud_ingresos->get();

        if ($request->tipo == 'pdf') {
            $pdf = PDF::loadView('reportes.solicitud_ingresos', compact('solicitud_ingresos'))->setPaper('letter', 'portrait');

            // ENUMERAR LAS PÁGINAS USANDO CANVAS
            $pdf->output();
            $dom_pdf = $pdf->getDomPDF();
            $canvas = $dom_pdf->get_canvas();
            $alto = $canvas->get_height();
            $ancho = $canvas->get_width();
            $canvas->page_text($ancho - 90, $alto - 25, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 9, array(0, 0, 0));

            return $pdf->download('Usuarios.pdf');
        } else {
            $spreadsheet = new Spreadsheet();
            $spreadsheet->getProperties()
                ->setCreator("ADMIN")
                ->setLastModifiedBy('Administración')
                ->setTitle('Registros')
                ->setSubject('Registros')
                ->setDescription('Registros')
                ->setKeywords('PHPSpreadsheet')
                ->setCategory('Listado');

            $sheet = $spreadsheet->getActiveSheet();

            $spreadsheet->getDefaultStyle()->getFont()->setName('Arial');

            $fila = 1;
            if (file_exists(public_path() . '/imgs/' . $this->configuracion->logo)) {
                $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                $drawing->setName('logo');
                $drawing->setDescription('logo');
                $drawing->setPath(public_path() . '/imgs/' . $this->configuracion->logo); // put your path and image here
                $drawing->setCoordinates('A' . $fila);
                $drawing->setOffsetX(5);
                $drawing->setOffsetY(0);
                $drawing->setHeight(60);
                $drawing->setWorksheet($sheet);
            }

            $fila = 2;
            $sheet->setCellValue('A' . $fila, $this->configuracion->nombre_sistema);
            $sheet->mergeCells("A" . $fila . ":G" . $fila);  //COMBINAR CELDAS
            $sheet->getStyle('A' . $fila . ':G' . $fila)->getAlignment()->setHorizontal('center');
            $sheet->getStyle('A' . $fila . ':G' . $fila)->applyFromArray($this->titulo);
            $fila++;
            $sheet->setCellValue('A' . $fila, "SOLICITUDES DE INGRESO");
            $sheet->mergeCells("A" . $fila . ":G" . $fila);  //COMBINAR CELDAS
            $sheet->getStyle('A' . $fila . ':G' . $fila)->getAlignment()->setHorizontal('center');
            $sheet->getStyle('A' . $fila . ':G' . $fila)->applyFromArray($this->titulo);
            $fila++;
            $fila++;
            foreach ($solicitud_ingresos as $key => $item) {
                $sheet->setCellValue('A' . $fila, 'CÓDIGO:');
                $sheet->setCellValue('B' . $fila, $item->codigo);
                $sheet->setCellValue('C' . $fila, 'PROVEEDOR:');
                $sheet->setCellValue('D' . $fila, $item->proveedor->razon_social);
                $sheet->mergeCells("D" . $fila . ":G" . $fila);  //COMBINAR CELDAS
                $sheet->getStyle('A' . $fila . ':G' . $fila)->applyFromArray($this->bodyTabla);
                $fila++;
                $sheet->setCellValue('A' . $fila, 'FECHA:');
                $sheet->setCellValue('B' . $fila, $item->fecha_c);
                $sheet->setCellValue('C' . $fila, 'USUARIO SOLICITANTE:');
                $sheet->setCellValue('D' . $fila, $item->user->full_name);
                $sheet->mergeCells("D" . $fila . ":E" . $fila);  //COMBINAR CELDAS
                $sheet->setCellValue('F' . $fila, 'ESTADO');
                $sheet->setCellValue('G' . $fila, $item->estado);
                $sheet->getStyle('A' . $fila . ':G' . $fila)->applyFromArray($this->bodyTabla);
                $fila++;
                $sheet->setCellValue('A' . $fila, 'N°');
                $sheet->setCellValue('B' . $fila, 'CÓD. PRODUCTO');
                $sheet->setCellValue('C' . $fila, 'PRODUCTO');
                $sheet->setCellValue('D' . $fila, 'MARCA');
                $sheet->setCellValue('E' . $fila, 'CATEGORÍA');
                $sheet->setCellValue('F' . $fila, 'CANTIDAD');
                $sheet->setCellValue('G' . $fila, 'CANTIDAD FÍSICA');
                $sheet->getStyle('A' . $fila . ':G' . $fila)->applyFromArray($this->headerTabla);
                $fila++;
                foreach ($item->solicitud_ingreso_detalles as $key => $si) {
                    $sheet->setCellValue('A' . $fila, $key + 1);
                    $sheet->setCellValue('B' . $fila, $si->producto->codigo);
                    $sheet->setCellValue('C' . $fila, $si->producto->nombre . ' ' . $si->producto->unidad_medida->nomnbre);
                    $sheet->setCellValue('D' . $fila, $si->producto->marca->nombre);
                    $sheet->setCellValue('E' . $fila, $si->producto->categoria->nombre);
                    $sheet->setCellValue('F' . $fila, $si->cantidad);
                    $sheet->setCellValue('G' . $fila, $si->cantidad_fisica);
                    $sheet->getStyle('A' . $fila . ':G' . $fila)->applyFromArray($this->bodyTabla);
                    $fila++;
                }
                $sheet->setCellValue('A' . $fila, 'TOTAL');
                $sheet->mergeCells("A" . $fila . ":E" . $fila);  //COMBINAR CELDAS
                $sheet->setCellValue('F' . $fila, $item->cantidad_total);
                $sheet->setCellValue('G' . $fila, $item->solicitud_ingreso_detalles->sum("cantidad_fisica"));
                $sheet->getStyle('A' . $fila . ':G' . $fila)->applyFromArray($this->headerTabla);
                $fila += 4;
            }


            $sheet->getColumnDimension('A')->setWidth(10);
            $sheet->getColumnDimension('B')->setWidth(15);
            $sheet->getColumnDimension('C')->setWidth(15);
            $sheet->getColumnDimension('D')->setWidth(10);
            $sheet->getColumnDimension('E')->setWidth(20);
            $sheet->getColumnDimension('F')->setWidth(12);
            $sheet->getColumnDimension('G')->setWidth(15);

            foreach (range('A', 'G') as $columnID) {
                $sheet->getStyle($columnID)->getAlignment()->setWrapText(true);
            }

            $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
            $sheet->getPageMargins()->setTop(0.5);
            $sheet->getPageMargins()->setRight(0.1);
            $sheet->getPageMargins()->setLeft(0.1);
            $sheet->getPageMargins()->setBottom(0.1);
            $sheet->getPageSetup()->setPrintArea('A:G');
            $sheet->getPageSetup()->setFitToWidth(1);
            $sheet->getPageSetup()->setFitToHeight(0);

            return response()->streamDownload(
                function () use ($spreadsheet) {
                    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
                    $writer->save('php://output');
                },
                'solicitud_ingresos_' . time() . '.xlsx',
                [
                    'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                ]
            );
        }
    }

    public function orden_salidas(Request $request)
    {
        ini_set('memory_limit', '1024M');
        set_time_limit(-1);
        $sucursal_id =  $request->sucursal_id;
        $fecha =  $request->fecha;
        $estado =  $request->estado;
        $orden_salidas = OrdenSalida::select("orden_salidas.*");

        if ($sucursal_id != 'todos') {
            $orden_salidas->where('sucursal_id', $sucursal_id);
        }

        if ($estado != 'todos') {
            $orden_salidas->where('estado', $estado);
        }

        if ($fecha) {
            $orden_salidas->where('fecha', $fecha);
        }

        $orden_salidas = $orden_salidas->get();

        if ($request->tipo == 'pdf') {
            $pdf = PDF::loadView('reportes.orden_salidas', compact('orden_salidas'))->setPaper('letter', 'portrait');

            // ENUMERAR LAS PÁGINAS USANDO CANVAS
            $pdf->output();
            $dom_pdf = $pdf->getDomPDF();
            $canvas = $dom_pdf->get_canvas();
            $alto = $canvas->get_height();
            $ancho = $canvas->get_width();
            $canvas->page_text($ancho - 90, $alto - 25, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 9, array(0, 0, 0));

            return $pdf->download('Usuarios.pdf');
        } else {
            $spreadsheet = new Spreadsheet();
            $spreadsheet->getProperties()
                ->setCreator("ADMIN")
                ->setLastModifiedBy('Administración')
                ->setTitle('Registros')
                ->setSubject('Registros')
                ->setDescription('Registros')
                ->setKeywords('PHPSpreadsheet')
                ->setCategory('Listado');

            $sheet = $spreadsheet->getActiveSheet();

            $spreadsheet->getDefaultStyle()->getFont()->setName('Arial');

            $fila = 1;
            if (file_exists(public_path() . '/imgs/' . $this->configuracion->logo)) {
                $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                $drawing->setName('logo');
                $drawing->setDescription('logo');
                $drawing->setPath(public_path() . '/imgs/' . $this->configuracion->logo); // put your path and image here
                $drawing->setCoordinates('A' . $fila);
                $drawing->setOffsetX(5);
                $drawing->setOffsetY(0);
                $drawing->setHeight(60);
                $drawing->setWorksheet($sheet);
            }

            $fila = 2;
            $sheet->setCellValue('A' . $fila, $this->configuracion->nombre_sistema);
            $sheet->mergeCells("A" . $fila . ":F" . $fila);  //COMBINAR CELDAS
            $sheet->getStyle('A' . $fila . ':F' . $fila)->getAlignment()->setHorizontal('center');
            $sheet->getStyle('A' . $fila . ':F' . $fila)->applyFromArray($this->titulo);
            $fila++;
            $sheet->setCellValue('A' . $fila, "ÓRDENDES DE SALIDA");
            $sheet->mergeCells("A" . $fila . ":F" . $fila);  //COMBINAR CELDAS
            $sheet->getStyle('A' . $fila . ':F' . $fila)->getAlignment()->setHorizontal('center');
            $sheet->getStyle('A' . $fila . ':F' . $fila)->applyFromArray($this->titulo);
            $fila++;
            $fila++;
            foreach ($orden_salidas as $key => $item) {
                $sheet->setCellValue('A' . $fila, 'CÓDIGO:');
                $sheet->setCellValue('B' . $fila, $item->codigo);
                $sheet->setCellValue('C' . $fila, 'SUCURSAL/VEHÍCULO:');
                $sheet->setCellValue('D' . $fila, $item->sucursal->nombre);
                $sheet->setCellValue('E' . $fila, 'USUARIO SOLICITANTE:');
                $sheet->setCellValue('F' . $fila, $item->user_solicitante->full_name);
                $sheet->getStyle('A' . $fila . ':F' . $fila)->applyFromArray($this->bodyTabla);
                $fila++;
                $sheet->setCellValue('A' . $fila, 'FECHA:');
                $sheet->setCellValue('B' . $fila, $item->fecha_c);
                $sheet->setCellValue('C' . $fila, 'USUARIO APROBADOR:');
                $sheet->setCellValue('D' . $fila, $item->user_aprobador->full_name);
                $sheet->setCellValue('E' . $fila, 'ESTADO');
                $sheet->setCellValue('F' . $fila, $item->estado);
                $sheet->getStyle('A' . $fila . ':F' . $fila)->applyFromArray($this->bodyTabla);
                $fila++;
                $sheet->setCellValue('A' . $fila, 'N°');
                $sheet->setCellValue('B' . $fila, 'CÓD. PRODUCTO');
                $sheet->setCellValue('C' . $fila, 'PRODUCTO');
                $sheet->mergeCells("C" . $fila . ":D" . $fila);  //COMBINAR CELDAS
                $sheet->setCellValue('E' . $fila, 'CANTIDAD');
                $sheet->setCellValue('F' . $fila, 'CANTIDAD FÍSICA');
                $sheet->getStyle('A' . $fila . ':F' . $fila)->applyFromArray($this->headerTabla);
                $fila++;
                foreach ($item->orden_salida_detalles as $key => $si) {
                    $sheet->setCellValue('A' . $fila, $key + 1);
                    $sheet->setCellValue('B' . $fila, $si->producto->codigo);
                    $sheet->setCellValue('C' . $fila, $si->producto->nombre . ' ' . $si->producto->unidad_medida->nomnbre);
                    $sheet->mergeCells("C" . $fila . ":D" . $fila);  //COMBINAR CELDAS
                    $sheet->setCellValue('E' . $fila, $si->cantidad);
                    $sheet->setCellValue('F' . $fila, $si->cantidad_fisica);
                    $sheet->getStyle('A' . $fila . ':F' . $fila)->applyFromArray($this->bodyTabla);
                    $fila++;
                }
                $sheet->setCellValue('A' . $fila, 'TOTAL');
                $sheet->mergeCells("A" . $fila . ":D" . $fila);  //COMBINAR CELDAS
                $sheet->setCellValue('E' . $fila, $item->cantidad_total);
                $sheet->setCellValue('F' . $fila, $item->orden_salida_detalles->sum("cantidad_fisica"));
                $sheet->getStyle('A' . $fila . ':F' . $fila)->applyFromArray($this->headerTabla);
                $fila += 4;
            }


            $sheet->getColumnDimension('A')->setWidth(10);
            $sheet->getColumnDimension('B')->setWidth(15);
            $sheet->getColumnDimension('C')->setWidth(20);
            $sheet->getColumnDimension('D')->setWidth(15);
            $sheet->getColumnDimension('E')->setWidth(20);
            $sheet->getColumnDimension('F')->setWidth(12);

            foreach (range('A', 'F') as $columnID) {
                $sheet->getStyle($columnID)->getAlignment()->setWrapText(true);
            }

            $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
            $sheet->getPageMargins()->setTop(0.5);
            $sheet->getPageMargins()->setRight(0.1);
            $sheet->getPageMargins()->setLeft(0.1);
            $sheet->getPageMargins()->setBottom(0.1);
            $sheet->getPageSetup()->setPrintArea('A:F');
            $sheet->getPageSetup()->setFitToWidth(1);
            $sheet->getPageSetup()->setFitToHeight(0);

            return response()->streamDownload(
                function () use ($spreadsheet) {
                    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
                    $writer->save('php://output');
                },
                'orden_salidas_' . time() . '.xlsx',
                [
                    'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                ]
            );
        }
    }

    public function devolucions(Request $request)
    {
        ini_set('memory_limit', '1024M');
        set_time_limit(-1);
        $sucursal_id =  $request->sucursal_id;
        $fecha =  $request->fecha;
        $estado =  $request->estado;
        $devolucion_stocks = DevolucionStock::select("devolucion_stocks.*");

        if ($sucursal_id != 'todos') {
            $devolucion_stocks->where('sucursal_id', $sucursal_id);
        }

        if ($estado != 'todos') {
            $devolucion_stocks->where('estado', $estado);
        }

        if ($fecha) {
            $devolucion_stocks->where('fecha', $fecha);
        }

        $devolucion_stocks = $devolucion_stocks->get();

        if ($request->tipo == 'pdf') {
            $pdf = PDF::loadView('reportes.devolucions', compact('devolucion_stocks'))->setPaper('letter', 'portrait');

            // ENUMERAR LAS PÁGINAS USANDO CANVAS
            $pdf->output();
            $dom_pdf = $pdf->getDomPDF();
            $canvas = $dom_pdf->get_canvas();
            $alto = $canvas->get_height();
            $ancho = $canvas->get_width();
            $canvas->page_text($ancho - 90, $alto - 25, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 9, array(0, 0, 0));

            return $pdf->download('Usuarios.pdf');
        } else {
            $spreadsheet = new Spreadsheet();
            $spreadsheet->getProperties()
                ->setCreator("ADMIN")
                ->setLastModifiedBy('Administración')
                ->setTitle('Registros')
                ->setSubject('Registros')
                ->setDescription('Registros')
                ->setKeywords('PHPSpreadsheet')
                ->setCategory('Listado');

            $sheet = $spreadsheet->getActiveSheet();

            $spreadsheet->getDefaultStyle()->getFont()->setName('Arial');

            $fila = 1;
            if (file_exists(public_path() . '/imgs/' . $this->configuracion->logo)) {
                $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                $drawing->setName('logo');
                $drawing->setDescription('logo');
                $drawing->setPath(public_path() . '/imgs/' . $this->configuracion->logo); // put your path and image here
                $drawing->setCoordinates('A' . $fila);
                $drawing->setOffsetX(5);
                $drawing->setOffsetY(0);
                $drawing->setHeight(60);
                $drawing->setWorksheet($sheet);
            }

            $fila = 2;
            $sheet->setCellValue('A' . $fila, $this->configuracion->nombre_sistema);
            $sheet->mergeCells("A" . $fila . ":F" . $fila);  //COMBINAR CELDAS
            $sheet->getStyle('A' . $fila . ':F' . $fila)->getAlignment()->setHorizontal('center');
            $sheet->getStyle('A' . $fila . ':F' . $fila)->applyFromArray($this->titulo);
            $fila++;
            $sheet->setCellValue('A' . $fila, "DEVOLUCIONES DE STOCK");
            $sheet->mergeCells("A" . $fila . ":F" . $fila);  //COMBINAR CELDAS
            $sheet->getStyle('A' . $fila . ':F' . $fila)->getAlignment()->setHorizontal('center');
            $sheet->getStyle('A' . $fila . ':F' . $fila)->applyFromArray($this->titulo);
            $fila++;
            $fila++;
            foreach ($devolucion_stocks as $key => $item) {
                $sheet->setCellValue('A' . $fila, 'SUCURSAL/VEHÍCULO:');
                $sheet->setCellValue('B' . $fila, $item->sucursal->nombre);
                $sheet->mergeCells("B" . $fila . ":C" . $fila);  //COMBINAR CELDAS
                $sheet->setCellValue('D' . $fila, 'ENCARGADO:');
                $sheet->setCellValue('E' . $fila, $item->sucursal->user->full_name);
                $sheet->mergeCells("E" . $fila . ":F" . $fila);  //COMBINAR CELDAS
                $sheet->getStyle('A' . $fila . ':F' . $fila)->applyFromArray($this->bodyTabla);
                $fila++;
                $sheet->setCellValue('A' . $fila, 'FECHA:');
                $sheet->setCellValue('B' . $fila, $item->fecha_c);
                $sheet->setCellValue('C' . $fila, 'USUARIO APROBADOR:');
                $sheet->setCellValue('D' . $fila, $item->user_verificador->full_name);
                $sheet->setCellValue('E' . $fila, 'ESTADO');
                $sheet->setCellValue('F' . $fila, $item->estado);
                $sheet->getStyle('A' . $fila . ':F' . $fila)->applyFromArray($this->bodyTabla);
                $fila++;
                $sheet->setCellValue('A' . $fila, 'N°');
                $sheet->setCellValue('B' . $fila, 'CÓD. PRODUCTO');
                $sheet->setCellValue('C' . $fila, 'PRODUCTO');
                $sheet->mergeCells("C" . $fila . ":D" . $fila);  //COMBINAR CELDAS
                $sheet->setCellValue('E' . $fila, 'CANTIDAD');
                $sheet->setCellValue('F' . $fila, 'CANTIDAD FÍSICA');
                $sheet->getStyle('A' . $fila . ':F' . $fila)->applyFromArray($this->headerTabla);
                $fila++;
                foreach ($item->devolucion_stock_detalles as $key => $si) {
                    $sheet->setCellValue('A' . $fila, $key + 1);
                    $sheet->setCellValue('B' . $fila, $si->producto->codigo);
                    $sheet->setCellValue('C' . $fila, $si->producto->nombre . ' ' . $si->producto->unidad_medida->nomnbre);
                    $sheet->mergeCells("C" . $fila . ":D" . $fila);  //COMBINAR CELDAS
                    $sheet->setCellValue('E' . $fila, $si->cantidad);
                    $sheet->setCellValue('F' . $fila, $si->cantidad_fisica);
                    $sheet->getStyle('A' . $fila . ':F' . $fila)->applyFromArray($this->bodyTabla);
                    $fila++;
                }
                $sheet->setCellValue('A' . $fila, 'TOTAL');
                $sheet->mergeCells("A" . $fila . ":D" . $fila);  //COMBINAR CELDAS
                $sheet->setCellValue('E' . $fila, $item->cantidad_total);
                $sheet->setCellValue('F' . $fila, $item->devolucion_stock_detalles->sum("cantidad_fisica"));
                $sheet->getStyle('A' . $fila . ':F' . $fila)->applyFromArray($this->headerTabla);
                $fila += 4;
            }


            $sheet->getColumnDimension('A')->setWidth(10);
            $sheet->getColumnDimension('B')->setWidth(15);
            $sheet->getColumnDimension('C')->setWidth(20);
            $sheet->getColumnDimension('D')->setWidth(15);
            $sheet->getColumnDimension('E')->setWidth(20);
            $sheet->getColumnDimension('F')->setWidth(12);

            foreach (range('A', 'F') as $columnID) {
                $sheet->getStyle($columnID)->getAlignment()->setWrapText(true);
            }

            $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
            $sheet->getPageMargins()->setTop(0.5);
            $sheet->getPageMargins()->setRight(0.1);
            $sheet->getPageMargins()->setLeft(0.1);
            $sheet->getPageMargins()->setBottom(0.1);
            $sheet->getPageSetup()->setPrintArea('A:F');
            $sheet->getPageSetup()->setFitToWidth(1);
            $sheet->getPageSetup()->setFitToHeight(0);

            return response()->streamDownload(
                function () use ($spreadsheet) {
                    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
                    $writer->save('php://output');
                },
                'devolucions_' . time() . '.xlsx',
                [
                    'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                ]
            );
        }
    }

    public function orden_ventas(Request $request)
    {
        ini_set('memory_limit', '1024M');
        set_time_limit(-1);
        $sucursal_id =  $request->sucursal_id;
        $cliente_id =  $request->cliente_id;
        $fecha_ini =  $request->fecha_ini;
        $fecha_fin =  $request->fecha_fin;
        $orden_ventas = OrdenVenta::select("orden_ventas.*");

        if ($sucursal_id != 'todos') {
            $orden_ventas->where('sucursal_id', $sucursal_id);
        }
        if ($fecha_ini && $fecha_fin) {
            $orden_ventas->whereBetween('fecha', [$fecha_ini, $fecha_fin]);
        }

        $orden_ventas = $orden_ventas->get();

        if ($request->tipo == 'pdf') {
            $pdf = PDF::loadView('reportes.orden_ventas', compact('orden_ventas'))->setPaper('letter', 'portrait');

            // ENUMERAR LAS PÁGINAS USANDO CANVAS
            $pdf->output();
            $dom_pdf = $pdf->getDomPDF();
            $canvas = $dom_pdf->get_canvas();
            $alto = $canvas->get_height();
            $ancho = $canvas->get_width();
            $canvas->page_text($ancho - 90, $alto - 25, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 9, array(0, 0, 0));

            return $pdf->download('Usuarios.pdf');
        } else {
            $spreadsheet = new Spreadsheet();
            $spreadsheet->getProperties()
                ->setCreator("ADMIN")
                ->setLastModifiedBy('Administración')
                ->setTitle('Registros')
                ->setSubject('Registros')
                ->setDescription('Registros')
                ->setKeywords('PHPSpreadsheet')
                ->setCategory('Listado');

            $sheet = $spreadsheet->getActiveSheet();

            $spreadsheet->getDefaultStyle()->getFont()->setName('Arial');

            $fila = 1;
            if (file_exists(public_path() . '/imgs/' . $this->configuracion->logo)) {
                $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                $drawing->setName('logo');
                $drawing->setDescription('logo');
                $drawing->setPath(public_path() . '/imgs/' . $this->configuracion->logo); // put your path and image here
                $drawing->setCoordinates('A' . $fila);
                $drawing->setOffsetX(5);
                $drawing->setOffsetY(0);
                $drawing->setHeight(60);
                $drawing->setWorksheet($sheet);
            }

            $fila = 2;
            $sheet->setCellValue('A' . $fila, $this->configuracion->nombre_sistema);
            $sheet->mergeCells("A" . $fila . ":F" . $fila);  //COMBINAR CELDAS
            $sheet->getStyle('A' . $fila . ':F' . $fila)->getAlignment()->setHorizontal('center');
            $sheet->getStyle('A' . $fila . ':F' . $fila)->applyFromArray($this->titulo);
            $fila++;
            $sheet->setCellValue('A' . $fila, "ORDENES DE VENTAS");
            $sheet->mergeCells("A" . $fila . ":F" . $fila);  //COMBINAR CELDAS
            $sheet->getStyle('A' . $fila . ':F' . $fila)->getAlignment()->setHorizontal('center');
            $sheet->getStyle('A' . $fila . ':F' . $fila)->applyFromArray($this->titulo);
            $fila++;
            $fila++;
            foreach ($orden_ventas as $key => $item) {
                $sheet->setCellValue('A' . $fila, 'SUCURSAL/VEHÍCULO:');
                $sheet->setCellValue('B' . $fila, $item->sucursal->nombre);
                $sheet->mergeCells("B" . $fila . ":C" . $fila);  //COMBINAR CELDAS
                $sheet->setCellValue('D' . $fila, 'ENCARGADO:');
                $sheet->setCellValue('E' . $fila, $item->sucursal->user->full_name);
                $sheet->mergeCells("E" . $fila . ":F" . $fila);  //COMBINAR CELDAS
                $sheet->getStyle('A' . $fila . ':F' . $fila)->applyFromArray($this->bodyTabla);
                $fila++;
                $sheet->setCellValue('A' . $fila, 'FECHA:');
                $sheet->setCellValue('B' . $fila, $item->fecha_c);
                $sheet->setCellValue('C' . $fila, 'USUARIO APROBADOR:');
                $sheet->setCellValue('D' . $fila, $item->user_verificador->full_name);
                $sheet->setCellValue('E' . $fila, 'ESTADO');
                $sheet->setCellValue('F' . $fila, $item->estado);
                $sheet->getStyle('A' . $fila . ':F' . $fila)->applyFromArray($this->bodyTabla);
                $fila++;
                $sheet->setCellValue('A' . $fila, 'N°');
                $sheet->setCellValue('B' . $fila, 'CÓD. PRODUCTO');
                $sheet->setCellValue('C' . $fila, 'PRODUCTO');
                $sheet->mergeCells("C" . $fila . ":D" . $fila);  //COMBINAR CELDAS
                $sheet->setCellValue('E' . $fila, 'CANTIDAD');
                $sheet->setCellValue('F' . $fila, 'CANTIDAD FÍSICA');
                $sheet->getStyle('A' . $fila . ':F' . $fila)->applyFromArray($this->headerTabla);
                $fila++;
                foreach ($item->devolucion_stock_detalles as $key => $si) {
                    $sheet->setCellValue('A' . $fila, $key + 1);
                    $sheet->setCellValue('B' . $fila, $si->producto->codigo);
                    $sheet->setCellValue('C' . $fila, $si->producto->nombre . ' ' . $si->producto->unidad_medida->nomnbre);
                    $sheet->mergeCells("C" . $fila . ":D" . $fila);  //COMBINAR CELDAS
                    $sheet->setCellValue('E' . $fila, $si->cantidad);
                    $sheet->setCellValue('F' . $fila, $si->cantidad_fisica);
                    $sheet->getStyle('A' . $fila . ':F' . $fila)->applyFromArray($this->bodyTabla);
                    $fila++;
                }
                $sheet->setCellValue('A' . $fila, 'TOTAL');
                $sheet->mergeCells("A" . $fila . ":D" . $fila);  //COMBINAR CELDAS
                $sheet->setCellValue('E' . $fila, $item->cantidad_total);
                $sheet->setCellValue('F' . $fila, $item->devolucion_stock_detalles->sum("cantidad_fisica"));
                $sheet->getStyle('A' . $fila . ':F' . $fila)->applyFromArray($this->headerTabla);
                $fila += 4;
            }


            $sheet->getColumnDimension('A')->setWidth(10);
            $sheet->getColumnDimension('B')->setWidth(15);
            $sheet->getColumnDimension('C')->setWidth(20);
            $sheet->getColumnDimension('D')->setWidth(15);
            $sheet->getColumnDimension('E')->setWidth(20);
            $sheet->getColumnDimension('F')->setWidth(12);

            foreach (range('A', 'F') as $columnID) {
                $sheet->getStyle($columnID)->getAlignment()->setWrapText(true);
            }

            $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
            $sheet->getPageMargins()->setTop(0.5);
            $sheet->getPageMargins()->setRight(0.1);
            $sheet->getPageMargins()->setLeft(0.1);
            $sheet->getPageMargins()->setBottom(0.1);
            $sheet->getPageSetup()->setPrintArea('A:F');
            $sheet->getPageSetup()->setFitToWidth(1);
            $sheet->getPageSetup()->setFitToHeight(0);

            return response()->streamDownload(
                function () use ($spreadsheet) {
                    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
                    $writer->save('php://output');
                },
                'orden_ventas_' . time() . '.xlsx',
                [
                    'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                ]
            );
        }
    }

    public function utilidad_ordens(Request $request)
    {
        ini_set('memory_limit', '1024M');
        set_time_limit(-1);
        $sucursal_id =  $request->sucursal_id;
        $anio =  $request->anio;

        $meses = [
            "01" => "ENERO",
            "02" => "FEBRERO",
            "03" => "MARZO",
            "04" => "ABRIL",
            "05" => "MAYO",
            "06" => "JUNIO",
            "07" => "JULIO",
            "08" => "AGOSTO",
            "09" => "SEPTIEMBRE",
            "10" => "OCTUBRE",
            "11" => "NOVIEMBRE",
            "12" => "DICIEMBRE",
        ];

        if ($request->tipo == 'pdf') {
            $pdf = PDF::loadView('reportes.utilidad_ordens', compact(
                'sucursal_id',
                "anio",
                'meses'
            ))->setPaper('letter', 'portrait');

            // ENUMERAR LAS PÁGINAS USANDO CANVAS
            $pdf->output();
            $dom_pdf = $pdf->getDomPDF();
            $canvas = $dom_pdf->get_canvas();
            $alto = $canvas->get_height();
            $ancho = $canvas->get_width();
            $canvas->page_text($ancho - 90, $alto - 25, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 9, array(0, 0, 0));

            return $pdf->download('UtilidadOrdens.pdf');
        } else {
            $spreadsheet = new Spreadsheet();
            $spreadsheet->getProperties()
                ->setCreator("ADMIN")
                ->setLastModifiedBy('Administración')
                ->setTitle('Registros')
                ->setSubject('Registros')
                ->setDescription('Registros')
                ->setKeywords('PHPSpreadsheet')
                ->setCategory('Listado');

            $sheet = $spreadsheet->getActiveSheet();

            $spreadsheet->getDefaultStyle()->getFont()->setName('Arial');

            $fila = 1;
            if (file_exists(public_path() . '/imgs/' . $this->configuracion->logo)) {
                $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                $drawing->setName('logo');
                $drawing->setDescription('logo');
                $drawing->setPath(public_path() . '/imgs/' . $this->configuracion->logo); // put your path and image here
                $drawing->setCoordinates('A' . $fila);
                $drawing->setOffsetX(5);
                $drawing->setOffsetY(0);
                $drawing->setHeight(60);
                $drawing->setWorksheet($sheet);
            }

            $fila = 2;
            $sheet->setCellValue('A' . $fila, $this->configuracion->nombre_sistema);
            $sheet->mergeCells("A" . $fila . ":D" . $fila);  //COMBINAR CELDAS
            $sheet->getStyle('A' . $fila . ':D' . $fila)->getAlignment()->setHorizontal('center');
            $sheet->getStyle('A' . $fila . ':D' . $fila)->applyFromArray($this->titulo);
            $fila++;
            $sheet->setCellValue('A' . $fila, "UTILIDAD DE ORDENDES DE VENTA");
            $sheet->mergeCells("A" . $fila . ":D" . $fila);  //COMBINAR CELDAS
            $sheet->getStyle('A' . $fila . ':D' . $fila)->getAlignment()->setHorizontal('center');
            $sheet->getStyle('A' . $fila . ':D' . $fila)->applyFromArray($this->titulo);
            $fila++;
            $fila++;
            $sheet->setCellValue('A' . $fila, 'MES');
            $sheet->setCellValue('B' . $fila, 'TOTAL VENTA');
            $sheet->setCellValue('C' . $fila, 'COMPRA');
            $sheet->setCellValue('D' . $fila, 'TOTAL');
            $sheet->getStyle('A' . $fila . ':D' . $fila)->applyFromArray($this->headerTabla);
            $fila++;

            $total_final1 = 0;
            $total_final2 = 0;
            $total_final3 = 0;

            foreach ($meses as $key => $value) {
                $orden_ventas = OrdenVenta::select('orden_ventas.*');
                if ($sucursal_id != 'todos') {
                    $orden_ventas->where('sucursal_id', $sucursal_id);
                }
                $orden_ventas->where('fecha', 'LIKE', "$anio-$key%");
                $total_ventas = $orden_ventas->where('estado', 'FINALIZADO')->sum('total_f');

                $solicitud_ingreso_detalles = SolicitudIngresoDetalle::select(
                    'solicitud_ingreso_detalles.*',
                );
                if ($sucursal_id != 'todos') {
                    $solicitud_ingreso_detalles->whereHas('solicitud_ingreso', function ($query) use (
                        $sucursal_id,
                    ) {
                        $query->where('sucursal_id', $sucursal_id);
                    });
                }

                $solicitud_ingreso_detalles->whereHas('solicitud_ingreso', function ($query) use (
                    $key,
                    $anio,
                ) {
                    $query->whereIn('verificado', [1, 2]);
                    $query->where('fecha_ingreso', 'LIKE', "$anio-$key%");
                });

                $total_compras = $solicitud_ingreso_detalles->sum(DB::raw('cantidad_fisica * costo'));

                $saldo = (float) $total_ventas - (float) $total_compras;

                $total_final1 += (float) $total_ventas;
                $total_final2 += (float) $total_compras;
                $total_final3 += (float) $saldo;

                $sheet->setCellValue('A' . $fila, $value);
                $sheet->setCellValue('B' . $fila, $total_ventas);
                $sheet->setCellValue('C' . $fila, $total_compras);
                $sheet->setCellValue('D' . $fila, number_format($saldo, 2, ".", ""));
                $sheet->getStyle('A' . $fila . ':D' . $fila)->applyFromArray($this->bodyTabla);
                $fila++;
            }
            $sheet->setCellValue('A' . $fila, "TOTAL");
            $sheet->setCellValue('B' . $fila, number_format($total_final1, 2, '.', ''));
            $sheet->setCellValue('C' . $fila, number_format($total_final2, 2, '.', ''));
            $sheet->setCellValue('D' . $fila, number_format($total_final3, 2, '.', ''));
            $sheet->getStyle('A' . $fila . ':D' . $fila)->applyFromArray($this->headerTabla);

            $sheet->getColumnDimension('A')->setWidth(15);
            $sheet->getColumnDimension('B')->setWidth(15);
            $sheet->getColumnDimension('C')->setWidth(15);
            $sheet->getColumnDimension('D')->setWidth(15);

            foreach (range('A', 'D') as $columnID) {
                $sheet->getStyle($columnID)->getAlignment()->setWrapText(true);
            }

            $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
            $sheet->getPageMargins()->setTop(0.5);
            $sheet->getPageMargins()->setRight(0.1);
            $sheet->getPageMargins()->setLeft(0.1);
            $sheet->getPageMargins()->setBottom(0.1);
            $sheet->getPageSetup()->setPrintArea('A:D');
            $sheet->getPageSetup()->setFitToWidth(1);
            $sheet->getPageSetup()->setFitToHeight(0);

            return response()->streamDownload(
                function () use ($spreadsheet) {
                    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
                    $writer->save('php://output');
                },
                'utilidad_ordens_' . time() . '.xlsx',
                [
                    'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                ]
            );
        }
    }

    public function cuenta_cobrars(Request $request)
    {
        ini_set('memory_limit', '1024M');
        set_time_limit(-1);
        $cliente_id =  $request->cliente_id;
        $cuenta_cobrars = CuentaCobrar::select("cuenta_cobrars.*");

        if ($cliente_id != 'todos') {
            $cuenta_cobrars->where('cliente_id', $cliente_id);
        }
        $cuenta_cobrars = $cuenta_cobrars->get();

        if ($request->tipo == 'pdf') {
            $pdf = PDF::loadView('reportes.cuenta_cobrars', compact('cuenta_cobrars'))->setPaper('letter', 'portrait');
            // ENUMERAR LAS PÁGINAS USANDO CANVAS
            $pdf->output();
            $dom_pdf = $pdf->getDomPDF();
            $canvas = $dom_pdf->get_canvas();
            $alto = $canvas->get_height();
            $ancho = $canvas->get_width();
            $canvas->page_text($ancho - 90, $alto - 25, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 9, array(0, 0, 0));

            return $pdf->download('Usuarios.pdf');
        } else {
            $spreadsheet = new Spreadsheet();
            $spreadsheet->getProperties()
                ->setCreator("ADMIN")
                ->setLastModifiedBy('Administración')
                ->setTitle('Registros')
                ->setSubject('Registros')
                ->setDescription('Registros')
                ->setKeywords('PHPSpreadsheet')
                ->setCategory('Listado');

            $sheet = $spreadsheet->getActiveSheet();

            $spreadsheet->getDefaultStyle()->getFont()->setName('Arial');

            $fila = 1;
            if (file_exists(public_path() . '/imgs/' . $this->configuracion->logo)) {
                $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                $drawing->setName('logo');
                $drawing->setDescription('logo');
                $drawing->setPath(public_path() . '/imgs/' . $this->configuracion->logo); // put your path and image here
                $drawing->setCoordinates('A' . $fila);
                $drawing->setOffsetX(5);
                $drawing->setOffsetY(0);
                $drawing->setHeight(60);
                $drawing->setWorksheet($sheet);
            }

            $fila = 2;
            $sheet->setCellValue('A' . $fila, $this->configuracion->nombre_sistema);
            $sheet->mergeCells("A" . $fila . ":G" . $fila);  //COMBINAR CELDAS
            $sheet->getStyle('A' . $fila . ':G' . $fila)->getAlignment()->setHorizontal('center');
            $sheet->getStyle('A' . $fila . ':G' . $fila)->applyFromArray($this->titulo);
            $fila++;
            $sheet->setCellValue('A' . $fila, "CUENTAS POR COBRAR");
            $sheet->mergeCells("A" . $fila . ":G" . $fila);  //COMBINAR CELDAS
            $sheet->getStyle('A' . $fila . ':G' . $fila)->getAlignment()->setHorizontal('center');
            $sheet->getStyle('A' . $fila . ':G' . $fila)->applyFromArray($this->titulo);
            $fila++;
            $fila++;
            $sheet->setCellValue('A' . $fila, 'N°');
            $sheet->setCellValue('B' . $fila, 'FECHA');
            $sheet->setCellValue('C' . $fila, 'CLIENTE');
            $sheet->setCellValue('D' . $fila, 'CÓD. ORDEN VENTA');
            $sheet->setCellValue('E' . $fila, 'TOTAL');
            $sheet->setCellValue('F' . $fila, 'CANCELADO');
            $sheet->setCellValue('G' . $fila, 'SALDO');
            $sheet->getStyle('A' . $fila . ':G' . $fila)->applyFromArray($this->headerTabla);
            $fila++;

            foreach ($cuenta_cobrars as $key => $item) {
                $sheet->setCellValue('A' . $fila, $key + 1);
                $sheet->setCellValue('B' . $fila, $item->fecha_c);
                $sheet->setCellValue('C' . $fila, $item->cliente->razon_social);
                $sheet->setCellValue('D' . $fila, $item->orden_venta->codigo);
                $sheet->setCellValue('E' . $fila, $item->total);
                $sheet->setCellValue('F' . $fila, $item->cancelado);
                $sheet->setCellValue('G' . $fila, $item->saldo);
                $sheet->getStyle('A' . $fila . ':G' . $fila)->applyFromArray($this->bodyTabla);
                $fila++;
            }
            $total = $cuenta_cobrars->sum('total');
            $cancelado = $cuenta_cobrars->sum('cancelado');
            $saldo = $cuenta_cobrars->sum('saldo');
            $sheet->setCellValue('A' . $fila, "TOTAL");
            $sheet->mergeCells("A" . $fila . ":D" . $fila);  //COMBINAR CELDAS
            $sheet->setCellValue('E' . $fila, $total);
            $sheet->setCellValue('F' . $fila, $cancelado);
            $sheet->setCellValue('G' . $fila, $saldo);
            $sheet->getStyle('A' . $fila . ':G' . $fila)->applyFromArray($this->headerTabla);

            $sheet->getColumnDimension('A')->setWidth(7);
            $sheet->getColumnDimension('B')->setWidth(15);
            $sheet->getColumnDimension('C')->setWidth(15);
            $sheet->getColumnDimension('D')->setWidth(10);
            $sheet->getColumnDimension('E')->setWidth(12);
            $sheet->getColumnDimension('F')->setWidth(12);
            $sheet->getColumnDimension('G')->setWidth(12);

            foreach (range('A', 'G') as $columnID) {
                $sheet->getStyle($columnID)->getAlignment()->setWrapText(true);
            }

            $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
            $sheet->getPageMargins()->setTop(0.5);
            $sheet->getPageMargins()->setRight(0.1);
            $sheet->getPageMargins()->setLeft(0.1);
            $sheet->getPageMargins()->setBottom(0.1);
            $sheet->getPageSetup()->setPrintArea('A:G');
            $sheet->getPageSetup()->setFitToWidth(1);
            $sheet->getPageSetup()->setFitToHeight(0);

            return response()->streamDownload(
                function () use ($spreadsheet) {
                    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
                    $writer->save('php://output');
                },
                'cuenta_cobrars_' . time() . '.xlsx',
                [
                    'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                ]
            );
        }
    }
}
