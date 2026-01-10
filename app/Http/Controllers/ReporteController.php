<?php

namespace App\Http\Controllers;

use App\Models\Ajuste;
use App\Models\AjusteReposicion;
use App\Models\Categoria;
use App\Models\Cliente;
use App\Models\Configuracion;
use App\Models\CuentaCobrar;
use App\Models\DevolucionClienteDetalle;
use App\Models\DevolucionStock;
use App\Models\Gasto;
use App\Models\HistorialAccion;
use App\Models\Inscripcion;
use App\Models\KardexProducto;
use App\Models\Marca;
use App\Models\MovimientoHora;
use App\Models\OrdenSalida;
use App\Models\OrdenSalidaDetalle;
use App\Models\OrdenVenta;
use App\Models\OrdenVentaDetalle;
use App\Models\ParametroSucursal;
use App\Models\Producto;
use App\Models\Proveedor;
use App\Models\SolicitudIngreso;
use App\Models\SolicitudIngresoDetalle;
use App\Models\Sucursal;
use App\Models\SucursalProducto;
use App\Models\TransferenciaDetalle;
use App\Models\User;
use App\Services\ReporteService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use PDF;
use Carbon\Carbon;
use DateTime;
use FPDF;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

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

    public $headerTabla2 = [
        'font' => [
            'bold' => true,
            'size' => 10,
            'color' => ['argb' => '000000'],
        ],
        'alignment' => [
            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
        ],
        'borders' => [
            'allBorders' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            ],
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

    public $bg0 = [
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'color' => ['rgb' => 'cff3f3']
        ],
    ];

    public $bg1 = [
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'color' => ['rgb' => 'ffe9ff']
        ],
    ];

    public $bg2 = [
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'color' => ['rgb' => 'f7ffe0']
        ],
    ];

    public $bg3 = [
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'color' => ['rgb' => 'ecfcdd']
        ],
    ];

    public $bg4 = [
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'color' => ['rgb' => 'faeee4']
        ],
    ];

    protected array $bgStyles = [];
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
        $this->bgStyles = [
            $this->bg0,
            $this->bg1,
            $this->bg2,
            $this->bg3,
            $this->bg4,
        ];
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

        $sucursals = $sucursals->where("estado", 1)->get();

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
                    $sheet->setCellValue('G' . $fila, 'Bs ' . $item->precio);
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
        $fecha_ini =  $request->fecha_ini;
        $fecha_fin =  $request->fecha_fin;
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

            if ($sucursal_id != 'todos') {
                $pdf = PDF::loadView('reportes.movimiento_inventario_sucursal', compact('sucursals', 'productos', 'array_dias', 'array_meses', 'fecha_ini', 'fecha_fin', 'user_id', 'tipo_movimiento'))->setPaper('legal', 'landscape');
            } else {
                $pdf = PDF::loadView('reportes.movimiento_inventario', compact('sucursals', 'productos', 'array_dias', 'array_meses', 'fecha_ini', 'fecha_fin', 'user_id', 'tipo_movimiento'))->setPaper('legal', 'landscape');
            }


            // ENUMERAR LAS PÁGINAS USANDO CANVAS
            $pdf->output();
            $dom_pdf = $pdf->getDomPDF();
            $canvas = $dom_pdf->get_canvas();
            $alto = $canvas->get_height();
            $ancho = $canvas->get_width();
            $canvas->page_text($ancho - 90, $alto - 25, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 9, array(0, 0, 0));

            return $pdf->download('Usuarios.pdf');
        } else {

            if ($sucursal_id != 'todos') {
                return $this->movimiento_inventario_sucursal($sucursals, $productos, $fecha_ini, $fecha_fin);
            }

            $parametroSucursal = ParametroSucursal::first();
            $horaInicial = date('H:i', strtotime($parametroSucursal->valor1)) ?? '08:00';
            $horaFinal = date('H:i', strtotime($parametroSucursal->valor2)) ?? '20:00';

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
            $colIndex = 4;
            $colStart = Coordinate::stringFromColumnIndex($colIndex);
            $indexFinal = (count($sucursals) * 3) + 6;
            $colEnd   = Coordinate::stringFromColumnIndex($indexFinal);
            $sheet->setCellValue('A' . $fila, $this->configuracion->nombre_sistema);
            $sheet->mergeCells("A" . $fila . ":" . $colEnd . $fila);  //COMBINAR CELDAS
            $sheet->getStyle('A' . $fila . ':' . $colEnd . $fila)->getAlignment()->setHorizontal('center');
            $sheet->getStyle('A' . $fila . ':' . $colEnd . $fila)->applyFromArray($this->titulo);
            $fila++;
            $sheet->setCellValue('A' . $fila, "MOVIMIENTOS DE INVENTARIO");
            $sheet->mergeCells("A" . $fila . ":" . $colEnd . $fila);  //COMBINAR CELDAS
            $sheet->getStyle('A' . $fila . ':' . $colEnd . $fila)->getAlignment()->setHorizontal('center');
            $sheet->getStyle('A' . $fila . ':' . $colEnd . $fila)->applyFromArray($this->titulo);
            $fila++;
            $sheet->setCellValue('A' . $fila, "Del " . date("d/m/Y", strtotime($fecha_ini)) . " al " . date("d/m/Y", strtotime($fecha_fin)));
            $sheet->mergeCells("A" . $fila . ":" . $colEnd . $fila);  //COMBINAR CELDAS
            $sheet->getStyle('A' . $fila . ':' . $colEnd . $fila)->getAlignment()->setHorizontal('center');
            $sheet->getStyle('A' . $fila . ':' . $colEnd . $fila)->applyFromArray($this->titulo);
            // $fila++;
            // $sheet->setCellValue('A' . $fila, "Encargado: " . $sucursal->user->full_name);
            // $sheet->mergeCells("A" . $fila . ":".$colEnd . $fila);  //COMBINAR CELDAS
            // $sheet->getStyle('A' . $fila . ':'.$colEnd . $fila)->getAlignment()->setHorizontal('center');
            // $sheet->getStyle('A' . $fila . ':'.$colEnd . $fila)->applyFromArray($this->titulo);
            $fila++;
            $fila++;
            $sheet->setCellValue('A' . $fila, 'N°');
            $sheet->setCellValue('B' . $fila, 'PRODUCTO');
            $sheet->setCellValue('C' . $fila, 'UNIDAD DE MEDIDA');

            $colIndex = 4;

            foreach ($sucursals as $i => $sucursal) {
                $colorIndex = $i % count($this->bgStyles);

                // Columna inicio y fin del bloque de 5
                $colStart = Coordinate::stringFromColumnIndex($colIndex);
                // Título de la sucursal (fila superior)
                $sheet->setCellValue($colStart . $fila, "STOCK INICIAL " . $sucursal->nombre . "\n" . $horaInicial);
                $sheet->getStyle("{$colStart}{$fila}:{$colEnd}{$fila}")
                    ->getAlignment()
                    ->setHorizontal('center')
                    ->setTextRotation(90); // 🔹 TEXTO VERTICAL
                $sheet->getStyle("{$colStart}{$fila}:{$colStart}{$fila}")->applyFromArray($this->bg0);
                $colIndex++;
            }
            $colStart   = Coordinate::stringFromColumnIndex($colIndex);
            $sheet->setCellValue($colStart . $fila, "TOTAL STOCK INICIAL \n" . $horaInicial);
            $sheet->getStyle("{$colStart}{$fila}:{$colStart}{$fila}")
                ->getAlignment()
                ->setHorizontal('center')
                ->setTextRotation(90); // 🔹 TEXTO VERTICAL
            $sheet->getStyle("{$colStart}{$fila}:{$colStart}{$fila}")->applyFromArray($this->bg1);
            $colIndex++;
            foreach ($sucursals as $i => $sucursal) {
                $colorIndex = $i % count($this->bgStyles);

                // Columna inicio y fin del bloque de 5
                $colStart = Coordinate::stringFromColumnIndex($colIndex);
                // Título de la sucursal (fila superior)
                $sheet->setCellValue($colStart . $fila, "VENTAS " . $sucursal->nombre);
                $sheet->getStyle("{$colStart}{$fila}:{$colStart}{$fila}")
                    ->getAlignment()->setHorizontal('center')
                    ->setTextRotation(90); // 🔹 TEXTO VERTICAL

                $sheet->getStyle("{$colStart}{$fila}:{$colStart}{$fila}")->applyFromArray($this->bg2);
                $colIndex++;
            }
            $colStart   = Coordinate::stringFromColumnIndex($colIndex);
            $sheet->setCellValue($colStart . $fila, "TOTAL VENTAS");
            $sheet->getStyle("{$colStart}{$fila}:{$colEnd}{$fila}")
                ->getAlignment()
                ->setHorizontal('center')
                ->setTextRotation(90); // 🔹 TEXTO VERTICAL
            $sheet->getStyle("{$colStart}{$fila}:{$colStart}{$fila}")->applyFromArray($this->bg3);
            $colIndex++;
            foreach ($sucursals as $i => $sucursal) {
                $colorIndex = $i % count($this->bgStyles);

                // Columna inicio y fin del bloque de 5
                $colStart = Coordinate::stringFromColumnIndex($colIndex);
                // Título de la sucursal (fila superior)
                $sheet->setCellValue($colStart . $fila, "STOCK FINAL " . $sucursal->nombre . "\n" . $horaFinal);
                $sheet->getStyle("{$colStart}{$fila}:{$colStart}{$fila}")
                    ->getAlignment()->setHorizontal('center')
                    ->setTextRotation(90); // 🔹 TEXTO VERTICAL
                $sheet->getStyle("{$colStart}{$fila}:{$colStart}{$fila}")->applyFromArray($this->bg4);
                $colIndex++;
            }
            $colStart   = Coordinate::stringFromColumnIndex($colIndex);
            $sheet->setCellValue($colStart . $fila, "TOTAL STOCK FINAL \n" . $horaFinal);
            $sheet->getStyle("{$colStart}{$fila}:{$colStart}{$fila}")
                ->getAlignment()
                ->setHorizontal('center')
                ->setTextRotation(90); // 🔹 TEXTO VERTICAL
            $sheet->getStyle("{$colStart}{$fila}:{$colStart}{$fila}")->applyFromArray($this->bg0);

            $colStart = Coordinate::stringFromColumnIndex($colIndex);

            $sheet->getStyle('A' . $fila . ':' . 'C' . $fila)->applyFromArray($this->headerTabla);
            $sheet->getStyle('D' . $fila . ':' . $colEnd . $fila)->applyFromArray($this->headerTabla2);
            $fila++;
            $cont = 1;
            foreach ($productos as $key => $producto) {
                $colIndex = 4;
                $sheet->setCellValue('A' . $fila, ($key + 1));
                $sheet->setCellValue('B' . $fila, $producto->nombre);
                $sheet->setCellValue('C' . $fila, $producto->unidad_medida->nombre);

                $total_inicial = 0;
                foreach ($sucursals as $i =>  $item) {
                    $stock_inicial = MovimientoHora::where('sucursal_id', $item->id)
                        ->where('producto_id', $producto->id)
                        ->whereBetween('fecha', [$fecha_ini, $fecha_fin])
                        ->sum('cantidad_inicial');

                    $sheet->setCellValue(
                        Coordinate::stringFromColumnIndex($colIndex) . $fila,
                        $stock_inicial
                    );
                    $total_inicial += (float) $stock_inicial;
                    $colIndex++;
                }
                $sheet->setCellValue(
                    Coordinate::stringFromColumnIndex($colIndex) . $fila,
                    $total_inicial
                );
                $colStart = Coordinate::stringFromColumnIndex($colIndex);
                $sheet->getStyle("{$colStart}{$fila}:{$colStart}{$fila}")->applyFromArray($this->bg1);
                $colIndex++;
                $total_ventas = 0;
                foreach ($sucursals as $i =>  $item) {
                    $cantidad_vendida = OrdenVentaDetalle::whereHas('orden_venta', function (
                        $query,
                    ) use ($fecha_ini, $fecha_fin, $item) {
                        $query->where('sucursal_id', $item->id);
                        $query->whereBetween('fecha', [$fecha_ini, $fecha_fin]);
                    })
                        ->where('producto_id', $producto->id)
                        ->sum('cantidad');
                    $sheet->setCellValue(
                        Coordinate::stringFromColumnIndex($colIndex) . $fila,
                        $cantidad_vendida
                    );
                    $total_ventas += (float) $cantidad_vendida;
                    $colIndex++;
                }
                $sheet->setCellValue(
                    Coordinate::stringFromColumnIndex($colIndex) . $fila,
                    $total_ventas
                );
                $colStart = Coordinate::stringFromColumnIndex($colIndex);
                $sheet->getStyle("{$colStart}{$fila}:{$colStart}{$fila}")->applyFromArray($this->bg3);
                $colIndex++;
                $total_final = 0;
                foreach ($sucursals as $i =>  $item) {
                    $stock_final = MovimientoHora::where('sucursal_id', $item->id)
                        ->where('producto_id', $producto->id)
                        ->whereBetween('fecha', [$fecha_ini, $fecha_fin])
                        ->sum('cantidad_final');
                    $sheet->setCellValue(
                        Coordinate::stringFromColumnIndex($colIndex) . $fila,
                        $stock_final
                    );
                    $total_final += (float) $stock_final;
                    $colIndex++;
                }
                $sheet->setCellValue(
                    Coordinate::stringFromColumnIndex($colIndex) . $fila,
                    $total_final
                );
                $colStart = Coordinate::stringFromColumnIndex($colIndex);
                $sheet->getStyle("{$colStart}{$fila}:{$colStart}{$fila}")->applyFromArray($this->bg0);

                $sheet->getStyle('A' . $fila . ':' . $colEnd . $fila)->applyFromArray($this->bodyTabla);
                $fila++;
            }

            $sheet->getColumnDimension('A')->setWidth(6);
            $sheet->getColumnDimension('B')->setWidth(25);
            $sheet->getColumnDimension('C')->setWidth(15);
            $sheet->getColumnDimension('D')->setWidth(15);
            $sheet->getColumnDimension('E')->setWidth(15);
            $sheet->getColumnDimension('F')->setWidth(15);
            $sheet->getColumnDimension('G')->setWidth(15);

            foreach (range('A', $colEnd) as $columnID) {
                $sheet->getStyle($columnID)->getAlignment()->setWrapText(true);
            }

            $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
            $sheet->getPageMargins()->setTop(0.5);
            $sheet->getPageMargins()->setRight(0.1);
            $sheet->getPageMargins()->setLeft(0.1);
            $sheet->getPageMargins()->setBottom(0.1);
            $sheet->getPageSetup()->setPrintArea('A:' . $colEnd);
            $sheet->getPageSetup()->setFitToWidth(1);
            $sheet->getPageSetup()->setFitToHeight(0);

            return response()->streamDownload(
                function () use ($spreadsheet) {
                    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
                    $writer->save('php://output');
                },
                'movimiento_inventario_' . time() . '.xlsx',
                [
                    'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                ]
            );
        }
    }

    private function movimiento_inventario_sucursal($sucursals, $productos, $fecha_ini, $fecha_fin)
    {
        $parametroSucursal = ParametroSucursal::first();
        $horaInicial = date('H:i', strtotime($parametroSucursal->valor1)) ?? '08:00';
        $horaFinal = date('H:i', strtotime($parametroSucursal->valor2)) ?? '20:00';
        $sucursal = $sucursals[0];
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
        $colIndex = 4;

        // INGRESOS
        $ingresos = [];
        if ($sucursal->almacen == 1) {
            // CENTRAL
            $ingresos = SolicitudIngreso::whereBetween('fecha_ingreso', [$fecha_ini, $fecha_fin])
                ->whereIn('verificado', [1, 2, 3])
                ->get();
        }
        if ($sucursal->almacen == 2) {
            // AJUSTE
            $ingresos = Ajuste::whereBetween('fecha', [$fecha_ini, $fecha_fin])->get();
        }
        if ($sucursal->almacen == 0) {
            // NORMAL
            $ingresos = OrdenSalida::whereBetween('fecha', [$fecha_ini, $fecha_fin])
                ->where('sucursal_id', $sucursal->id)
                ->get();
        }
        // SALIDAS
        $salidas = [];
        if ($sucursal->almacen == 1) {
            // CENTRAL
            $salidas = OrdenSalida::whereBetween('fecha', [$fecha_ini, $fecha_fin])
                ->whereIn('verificado', [1, 2])
                ->get();
        }
        if ($sucursal->almacen == 2) {
            // AJUSTE
            $salidas = AjusteReposicion::whereBetween('fecha', [$fecha_ini, $fecha_fin])->get();
        }
        if ($sucursal->almacen == 0) {
            // NORMAL
            $salidas = OrdenVenta::whereBetween('fecha', [$fecha_ini, $fecha_fin])
                ->where('sucursal_id', $sucursal->id)
                ->where('verificado', 2)
                ->get();
        }

        $colStart = Coordinate::stringFromColumnIndex($colIndex);
        $indexFinal = count($ingresos) + count($salidas) + 9;
        $colEnd   = Coordinate::stringFromColumnIndex($indexFinal);

        $sheet->setCellValue('A' . $fila, $this->configuracion->nombre_sistema);
        $sheet->mergeCells("A" . $fila . ":" . $colEnd . $fila);  //COMBINAR CELDAS
        $sheet->getStyle('A' . $fila . ':' . $colEnd . $fila)->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A' . $fila . ':' . $colEnd . $fila)->applyFromArray($this->titulo);
        $fila++;
        $sheet->setCellValue('A' . $fila, "MOVIMIENTOS DE INVENTARIO");
        $sheet->mergeCells("A" . $fila . ":" . $colEnd . $fila);  //COMBINAR CELDAS
        $sheet->getStyle('A' . $fila . ':' . $colEnd . $fila)->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A' . $fila . ':' . $colEnd . $fila)->applyFromArray($this->titulo);
        $fila++;
        $sheet->setCellValue('A' . $fila, $sucursal->nombre);
        $sheet->mergeCells("A" . $fila . ":" . $colEnd . $fila);  //COMBINAR CELDAS
        $sheet->getStyle('A' . $fila . ':' . $colEnd . $fila)->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A' . $fila . ':' . $colEnd . $fila)->applyFromArray($this->titulo);
        $fila++;
        $sheet->setCellValue('A' . $fila, "Del " . date("d/m/Y", strtotime($fecha_ini)) . " al " . date("d/m/Y", strtotime($fecha_fin)));
        $sheet->mergeCells("A" . $fila . ":" . $colEnd . $fila);  //COMBINAR CELDAS
        $sheet->getStyle('A' . $fila . ':' . $colEnd . $fila)->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A' . $fila . ':' . $colEnd . $fila)->applyFromArray($this->titulo);
        // $fila++;
        // $sheet->setCellValue('A' . $fila, "Encargado: " . $sucursal->user->full_name);
        // $sheet->mergeCells("A" . $fila . ":".$colEnd . $fila);  //COMBINAR CELDAS
        // $sheet->getStyle('A' . $fila . ':'.$colEnd . $fila)->getAlignment()->setHorizontal('center');
        // $sheet->getStyle('A' . $fila . ':'.$colEnd . $fila)->applyFromArray($this->titulo);
        $fila++;
        $fila++;
        $sheet->setCellValue('A' . $fila, 'N°');
        $sheet->setCellValue('B' . $fila, 'PRODUCTO');
        $sheet->setCellValue('C' . $fila, 'UNIDAD DE MEDIDA');

        $colIndex = 4;
        $colStart   = Coordinate::stringFromColumnIndex($colIndex);
        $sheet->setCellValue($colStart . $fila, "STOCK INICIAL \n" . $horaInicial);
        $sheet->getStyle("{$colStart}{$fila}:{$colStart}{$fila}")
            ->getAlignment()
            ->setHorizontal('center')
            ->setTextRotation(90); // 🔹 TEXTO VERTICAL
        $sheet->getStyle("{$colStart}{$fila}:{$colStart}{$fila}")->applyFromArray($this->bg0);
        $colIndex++;
        foreach ($ingresos as $i => $item) {
            $colorIndex = $i % count($this->bgStyles);

            // Columna inicio y fin del bloque de 5
            $colStart = Coordinate::stringFromColumnIndex($colIndex);
            // Título de la sucursal (fila superior)
            $sheet->setCellValue($colStart . $fila, "INGRESO A " . $sucursal->nombre);
            $sheet->getStyle("{$colStart}{$fila}:{$colEnd}{$fila}")
                ->getAlignment()
                ->setHorizontal('center')
                ->setTextRotation(90); // 🔹 TEXTO VERTICAL
            $colIndex++;
        }
        $colStart   = Coordinate::stringFromColumnIndex($colIndex);
        $sheet->setCellValue($colStart . $fila, "TOTAL INGRESOS " . $sucursal->nombre);
        $sheet->getStyle("{$colStart}{$fila}:{$colStart}{$fila}")
            ->getAlignment()
            ->setHorizontal('center')
            ->setTextRotation(90); // 🔹 TEXTO VERTICAL
        $sheet->getStyle("{$colStart}{$fila}:{$colStart}{$fila}")->applyFromArray($this->bg1);
        $colIndex++;
        foreach ($salidas as $i => $item) {
            $colorIndex = $i % count($this->bgStyles);

            // Columna inicio y fin del bloque de 5
            $colStart = Coordinate::stringFromColumnIndex($colIndex);
            // Título de la sucursal (fila superior)
            $sheet->setCellValue($colStart . $fila, "SALIDAS DE " . $sucursal->nombre);
            $sheet->getStyle("{$colStart}{$fila}:{$colStart}{$fila}")
                ->getAlignment()->setHorizontal('center')
                ->setTextRotation(90); // 🔹 TEXTO VERTICAL
            $colIndex++;
        }
        $colStart   = Coordinate::stringFromColumnIndex($colIndex);
        $sheet->setCellValue($colStart . $fila, "TOTAL SALIDAS " . $sucursal->nombre);
        $sheet->getStyle("{$colStart}{$fila}:{$colEnd}{$fila}")
            ->getAlignment()
            ->setHorizontal('center')
            ->setTextRotation(90); // 🔹 TEXTO VERTICAL
        $sheet->getStyle("{$colStart}{$fila}:{$colStart}{$fila}")->applyFromArray($this->bg2);
        $colIndex++;

        $colStart   = Coordinate::stringFromColumnIndex($colIndex);
        $sheet->setCellValue($colStart . $fila, "STOCK EN " . $sucursal->nombre);
        $sheet->getStyle("{$colStart}{$fila}:{$colStart}{$fila}")
            ->getAlignment()
            ->setHorizontal('center')
            ->setTextRotation(90); // 🔹 TEXTO VERTICAL
        $sheet->getStyle("{$colStart}{$fila}:{$colStart}{$fila}")->applyFromArray($this->bg3);

        $colIndex++;
        $colStart   = Coordinate::stringFromColumnIndex($colIndex);
        $sheet->setCellValue($colStart . $fila, "VENTAS");
        $sheet->getStyle("{$colStart}{$fila}:{$colStart}{$fila}")
            ->getAlignment()
            ->setHorizontal('center')
            ->setTextRotation(90); // 🔹 TEXTO VERTICAL
        $sheet->getStyle("{$colStart}{$fila}:{$colStart}{$fila}")->applyFromArray($this->bg4);

        $colIndex++;
        $colStart   = Coordinate::stringFromColumnIndex($colIndex);
        $sheet->setCellValue($colStart . $fila, "STOCK " . $sucursal->nombre . " \n" . $horaFinal);
        $sheet->getStyle("{$colStart}{$fila}:{$colStart}{$fila}")
            ->getAlignment()
            ->setHorizontal('center')
            ->setTextRotation(90); // 🔹 TEXTO VERTICAL
        $sheet->getStyle("{$colStart}{$fila}:{$colStart}{$fila}")->applyFromArray($this->bg0);

        $sheet->getStyle('A' . $fila . ':' . 'C' . $fila)->applyFromArray($this->headerTabla);
        $sheet->getStyle('D' . $fila . ':' . $colEnd . $fila)->applyFromArray($this->headerTabla2);
        $fila++;
        $cont = 1;
        foreach ($productos as $key => $producto) {
            $colIndex = 4;
            $sheet->setCellValue('A' . $fila, ($key + 1));
            $sheet->setCellValue('B' . $fila, $producto->nombre);
            $sheet->setCellValue('C' . $fila, $producto->unidad_medida->nombre);
            $stock_inicial = MovimientoHora::where('sucursal_id', $sucursal->id)
                ->where('producto_id', $producto->id)
                ->whereBetween('fecha', [$fecha_ini, $fecha_fin])
                ->sum('cantidad_inicial');

            $sheet->setCellValue(
                Coordinate::stringFromColumnIndex($colIndex) . $fila,
                $stock_inicial
            );
            $colStart   = Coordinate::stringFromColumnIndex($colIndex);
            $sheet->getStyle("{$colStart}{$fila}:{$colStart}{$fila}")->applyFromArray($this->bg0);
            $colIndex++;
            $total_ingresos = 0;
            foreach ($ingresos as $i =>  $item) {
                if ($sucursal->almacen == 1) {
                    // central
                    $cantidad_ingreso = SolicitudIngresoDetalle::where(
                        'solicitud_ingreso_id',
                        $item->id,
                    )
                        ->where('producto_id', $producto->id)
                        ->sum('cantidad_fisica');
                }

                if ($sucursal->almacen == 2) {
                    // ajuste
                    $cantidad_ingreso = Ajuste::where('producto_id', $producto->id)
                        ->whereBetween('fecha', [$fecha_ini, $fecha_fin])
                        ->sum('cantidad');
                }

                if ($sucursal->almacen == 0) {
                    // normal
                    $cantidad_ingreso = OrdenSalidaDetalle::where('orden_salida_id', $item->id)
                        ->where('producto_id', $producto->id)
                        ->sum('cantidad_fisica');
                }

                $sheet->setCellValue(
                    Coordinate::stringFromColumnIndex($colIndex) . $fila,
                    $cantidad_ingreso
                );
                $total_ingresos += (float) $cantidad_ingreso;
                $colIndex++;
            }
            $sheet->setCellValue(
                Coordinate::stringFromColumnIndex($colIndex) . $fila,
                $total_ingresos
            );
            $colStart = Coordinate::stringFromColumnIndex($colIndex);
            $sheet->getStyle("{$colStart}{$fila}:{$colStart}{$fila}")->applyFromArray($this->bg1);
            $colIndex++;
            $total_salidas = 0;
            foreach ($salidas as $i =>  $item) {
                $cantidad_salida = 0;
                if ($sucursal->almacen == 1) {
                    // central
                    $cantidad_salida = OrdenSalidaDetalle::where('orden_salida_id', $item->id)
                        ->where('producto_id', $producto->id)
                        ->sum('cantidad_fisica');
                }

                if ($sucursal->almacen == 2) {
                    // ajuste
                    $cantidad_ingreso = AjusteReposicion::where('producto_id', $producto->id)
                        ->whereBetween('fecha', [$fecha_ini, $fecha_fin])
                        ->sum('cantidad');
                }

                if ($sucursal->almacen == 0) {
                    // normal
                    $cantidad_ingreso = OrdenVentaDetalle::where('orden_venta_id', $item->id)
                        ->where('producto_id', $producto->id)
                        ->sum('cantidad');
                }

                $sheet->setCellValue(
                    Coordinate::stringFromColumnIndex($colIndex) . $fila,
                    $cantidad_salida
                );
                $total_salidas += (float) $cantidad_salida;
                $colIndex++;
            }
            $sheet->setCellValue(
                Coordinate::stringFromColumnIndex($colIndex) . $fila,
                $total_salidas
            );
            $colStart = Coordinate::stringFromColumnIndex($colIndex);
            $sheet->getStyle("{$colStart}{$fila}:{$colStart}{$fila}")->applyFromArray($this->bg2);
            $colIndex++;
            $ventas = 0;
            if ($sucursal->almacen == 0) {
                $ventas = OrdenVentaDetalle::whereHas('orden_venta', function ($query) use (
                    $fecha_ini,
                    $fecha_fin,
                ) {
                    $query->whereBetween('fecha', [$fecha_ini, $fecha_fin]);
                    $query->where('verificado', 2);
                })
                    ->where('producto_id', $producto->id)
                    ->sum('cantidad');
            }
            $stock_sucursal = (float) $stock_inicial + (float) $total_ingresos - (float) $total_salidas;
            $sheet->setCellValue(
                Coordinate::stringFromColumnIndex($colIndex) . $fila,
                $stock_sucursal
            );
            $colStart = Coordinate::stringFromColumnIndex($colIndex);
            $sheet->getStyle("{$colStart}{$fila}:{$colStart}{$fila}")->applyFromArray($this->bg3);
            $colIndex++;
            $sheet->setCellValue(
                Coordinate::stringFromColumnIndex($colIndex) . $fila,
                $ventas
            );
            $colStart = Coordinate::stringFromColumnIndex($colIndex);
            $sheet->getStyle("{$colStart}{$fila}:{$colStart}{$fila}")->applyFromArray($this->bg4);
            $colIndex++;
            $stock_final = MovimientoHora::where('sucursal_id', $sucursal->id)
                ->whereBetween('fecha', [$fecha_ini, $fecha_fin])
                ->sum('cantidad_final');
            $sheet->setCellValue(
                Coordinate::stringFromColumnIndex($colIndex) . $fila,
                $stock_final
            );

            $colStart = Coordinate::stringFromColumnIndex($colIndex);
            $sheet->getStyle("{$colStart}{$fila}:{$colStart}{$fila}")->applyFromArray($this->bg0);
            $sheet->getStyle('A' . $fila . ':' . $colEnd . $fila)->applyFromArray($this->bodyTabla);
            $fila++;
        }

        $sheet->getColumnDimension('A')->setWidth(6);
        $sheet->getColumnDimension('B')->setWidth(25);
        $sheet->getColumnDimension('C')->setWidth(15);
        $sheet->getColumnDimension('D')->setWidth(15);
        $sheet->getColumnDimension('E')->setWidth(15);
        $sheet->getColumnDimension('F')->setWidth(15);
        $sheet->getColumnDimension('G')->setWidth(15);

        foreach (range('A', $colEnd) as $columnID) {
            $sheet->getStyle($columnID)->getAlignment()->setWrapText(true);
        }

        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        $sheet->getPageMargins()->setTop(0.5);
        $sheet->getPageMargins()->setRight(0.1);
        $sheet->getPageMargins()->setLeft(0.1);
        $sheet->getPageMargins()->setBottom(0.1);
        $sheet->getPageSetup()->setPrintArea('A:' . $colEnd);
        $sheet->getPageSetup()->setFitToWidth(1);
        $sheet->getPageSetup()->setFitToHeight(0);

        return response()->streamDownload(
            function () use ($spreadsheet) {
                $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
                $writer->save('php://output');
            },
            'movimiento_inventario_' . time() . '.xlsx',
            [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ]
        );
    }

    public function movimiento_inventario_g(Request $request)
    {
        ini_set('memory_limit', '1024M');
        set_time_limit(-1);
        $producto_id =  $request->producto_id;
        $sucursal_id =  $request->sucursal_id;
        $user_id =  $request->user_id;
        $tipo_movimiento =  $request->tipo_movimiento;
        $fecha_ini =  $request->fecha_ini;
        $fecha_fin =  $request->fecha_fin;
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

        if ($sucursal_id != 'todos') {
            $html = view('reportes.parcial.movimiento_inventario_sucursal', compact('sucursals', 'productos', 'array_dias', 'array_meses', 'fecha_ini', 'fecha_fin', 'user_id', 'tipo_movimiento'))->render();
        } else {
            $html = view('reportes.parcial.movimiento_inventario', compact('sucursals', 'productos', 'array_dias', 'array_meses', 'fecha_ini', 'fecha_fin', 'user_id', 'tipo_movimiento'))->render();
        }

        return response()->JSON($html);
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
            $pdf = PDF::loadView('reportes.solicitud_ingresos', compact('solicitud_ingresos', 'fecha'))->setPaper('letter', 'portrait');

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
            $sheet->mergeCells("A" . $fila . ":H" . $fila);  //COMBINAR CELDAS
            $sheet->getStyle('A' . $fila . ':H' . $fila)->getAlignment()->setHorizontal('center');
            $sheet->getStyle('A' . $fila . ':H' . $fila)->applyFromArray($this->titulo);
            $fila++;
            $sheet->setCellValue('A' . $fila, "SOLICITUDES DE INGRESO");
            $sheet->mergeCells("A" . $fila . ":H" . $fila);  //COMBINAR CELDAS
            $sheet->getStyle('A' . $fila . ':H' . $fila)->getAlignment()->setHorizontal('center');
            $sheet->getStyle('A' . $fila . ':H' . $fila)->applyFromArray($this->titulo);
            $fila++;
            $sheet->setCellValue('A' . $fila, "FECHA: " . date("d/m/Y", strtotime($fecha)));
            $sheet->mergeCells("A" . $fila . ":H" . $fila);  //COMBINAR CELDAS
            $sheet->getStyle('A' . $fila . ':H' . $fila)->getAlignment()->setHorizontal('center');
            $sheet->getStyle('A' . $fila . ':H' . $fila)->applyFromArray($this->titulo);

            $fila++;
            $fila++;
            foreach ($solicitud_ingresos as $key => $item) {
                $sheet->setCellValue('A' . $fila, 'CÓDIGO:');
                $sheet->setCellValue('B' . $fila, $item->codigo);
                $sheet->setCellValue('C' . $fila, 'PROVEEDOR:');
                $sheet->setCellValue('D' . $fila, $item->proveedor->razon_social);
                $sheet->mergeCells("D" . $fila . ":H" . $fila);  //COMBINAR CELDAS
                $sheet->getStyle('A' . $fila . ':H' . $fila)->applyFromArray($this->bodyTabla);
                $fila++;
                $sheet->setCellValue('A' . $fila, 'FECHA:');
                $sheet->setCellValue('B' . $fila, $item->fecha_c);
                $sheet->setCellValue('C' . $fila, 'USUARIO SOLICITANTE:');
                $sheet->setCellValue('D' . $fila, $item->user->full_name);
                $sheet->mergeCells("D" . $fila . ":E" . $fila);  //COMBINAR CELDAS
                $sheet->setCellValue('F' . $fila, 'ESTADO');
                $sheet->setCellValue('G' . $fila, $item->estado);
                $sheet->mergeCells("G" . $fila . ":H" . $fila);  //COMBINAR CELDAS
                $sheet->getStyle('A' . $fila . ':H' . $fila)->applyFromArray($this->bodyTabla);
                $fila++;
                $sheet->setCellValue('A' . $fila, 'N°');
                $sheet->setCellValue('B' . $fila, 'CÓD. PRODUCTO');
                $sheet->setCellValue('C' . $fila, 'PRODUCTO');
                $sheet->setCellValue('D' . $fila, 'MARCA');
                $sheet->setCellValue('E' . $fila, 'CATEGORÍA');
                $sheet->setCellValue('F' . $fila, 'CANTIDAD');
                $sheet->setCellValue('G' . $fila, 'CANTIDAD STOCK DE AJUSTES');
                $sheet->setCellValue('H' . $fila, 'CANTIDAD FÍSICA');
                $sheet->getStyle('A' . $fila . ':H' . $fila)->applyFromArray($this->headerTabla);
                $fila++;
                $total_ajuste = 0;
                foreach ($item->solicitud_ingreso_detalles as $key => $si) {
                    $ajuste = (float) $si->cantidad - (float) $si->cantidad_fisica;
                    $total_ajuste += (float) $ajuste;
                    $sheet->setCellValue('A' . $fila, $key + 1);
                    $sheet->setCellValue('B' . $fila, $si->producto->codigo);
                    $sheet->setCellValue('C' . $fila, $si->producto->nombre . ' ' . $si->producto->unidad_medida->nomnbre);
                    $sheet->setCellValue('D' . $fila, $si->producto->marca->nombre);
                    $sheet->setCellValue('E' . $fila, $si->producto->categoria->nombre);
                    $sheet->setCellValue('F' . $fila, $si->cantidad);
                    $sheet->setCellValue('G' . $fila, $ajuste);
                    $sheet->setCellValue('H' . $fila, $si->cantidad_fisica);
                    $sheet->getStyle('A' . $fila . ':H' . $fila)->applyFromArray($this->bodyTabla);
                    $fila++;
                }
                $sheet->setCellValue('A' . $fila, 'TOTAL');
                $sheet->mergeCells("A" . $fila . ":E" . $fila);  //COMBINAR CELDAS
                $sheet->setCellValue('F' . $fila, $item->cantidad_total);
                $sheet->setCellValue('G' . $fila, $total_ajuste);
                $sheet->setCellValue('H' . $fila, $item->solicitud_ingreso_detalles->sum("cantidad_fisica"));
                $sheet->getStyle('A' . $fila . ':H' . $fila)->applyFromArray($this->headerTabla);
                $fila += 4;
            }


            $sheet->getColumnDimension('A')->setWidth(10);
            $sheet->getColumnDimension('B')->setWidth(15);
            $sheet->getColumnDimension('C')->setWidth(15);
            $sheet->getColumnDimension('D')->setWidth(10);
            $sheet->getColumnDimension('E')->setWidth(20);
            $sheet->getColumnDimension('F')->setWidth(12);
            $sheet->getColumnDimension('G')->setWidth(15);
            $sheet->getColumnDimension('H')->setWidth(15);

            foreach (range('A', 'H') as $columnID) {
                $sheet->getStyle($columnID)->getAlignment()->setWrapText(true);
            }

            $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
            $sheet->getPageMargins()->setTop(0.5);
            $sheet->getPageMargins()->setRight(0.1);
            $sheet->getPageMargins()->setLeft(0.1);
            $sheet->getPageMargins()->setBottom(0.1);
            $sheet->getPageSetup()->setPrintArea('A:H');
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
            $pdf = PDF::loadView('reportes.orden_salidas', compact('orden_salidas', 'fecha'))->setPaper('letter', 'portrait');

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
            $sheet->setCellValue('A' . $fila, "FECHA: " . date("d/m/Y", strtotime($fecha)));
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
            $pdf = PDF::loadView('reportes.devolucions', compact('devolucion_stocks', 'fecha'))->setPaper('letter', 'portrait');

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
            $sheet->setCellValue('A' . $fila, "FECHA: " . date("d/m/Y", strtotime($fecha)));
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
        $sucursal_id =  $request->sucursal_id;
        $sucursals = Sucursal::select("sucursals.*")
            ->where("almacen", 0);
        if ($sucursal_id != 'todos') {
            $sucursals->where('id', $sucursal_id);
        }
        $sucursals = $sucursals->where("estado", 1)->get();

        $productos = Producto::select("productos.*");
        $productos = $productos->where("estado", 1)->get();
        $clientes = Cliente::select("clientes.*");
        if ($cliente_id != 'todos') {
            $clientes->where("id", $cliente_id);
        }
        $clientes = $clientes->where("estado", 1)->get();

        if ($request->tipo == 'pdf') {
            $pdf = PDF::loadView('reportes.orden_ventas', compact('sucursals', 'productos', 'clientes', 'fecha_ini', 'fecha_fin'))->setPaper('legal', 'landscape');

            // ENUMERAR LAS PÁGINAS USANDO CANVAS
            $pdf->output();
            $dom_pdf = $pdf->getDomPDF();
            $canvas = $dom_pdf->get_canvas();
            $alto = $canvas->get_height();
            $ancho = $canvas->get_width();
            $canvas->page_text($ancho - 90, $alto - 25, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 9, array(0, 0, 0));

            return $pdf->download('Usuarios.pdf');
        } else {
            $colIndex = 4;

            // INGRESOS

            $colStart = Coordinate::stringFromColumnIndex($colIndex);
            $indexFinal = count($clientes) + 6;
            $colEnd   = Coordinate::stringFromColumnIndex($indexFinal);

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
            $fila++;
            foreach ($sucursals as $sucursal) {
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

                $sheet->setCellValue('A' . $fila, $this->configuracion->nombre_sistema);
                $sheet->mergeCells("A" . $fila . ":" . $colEnd . $fila);  //COMBINAR CELDAS
                $sheet->getStyle('A' . $fila . ':' . $colEnd . $fila)->getAlignment()->setHorizontal('center');
                $sheet->getStyle('A' . $fila . ':' . $colEnd . $fila)->applyFromArray($this->titulo);
                $fila++;
                $sheet->setCellValue('A' . $fila, "ORDENDES DE VENTAS");
                $sheet->mergeCells("A" . $fila . ":" . $colEnd . $fila);  //COMBINAR CELDAS
                $sheet->getStyle('A' . $fila . ':' . $colEnd . $fila)->getAlignment()->setHorizontal('center');
                $sheet->getStyle('A' . $fila . ':' . $colEnd . $fila)->applyFromArray($this->titulo);
                $fila++;
                $sheet->setCellValue('A' . $fila, $sucursal->nombre);
                $sheet->mergeCells("A" . $fila . ":" . $colEnd . $fila);  //COMBINAR CELDAS
                $sheet->getStyle('A' . $fila . ':' . $colEnd . $fila)->getAlignment()->setHorizontal('center');
                $sheet->getStyle('A' . $fila . ':' . $colEnd . $fila)->applyFromArray($this->titulo);
                $fila++;
                $sheet->setCellValue('A' . $fila, "Del " . date("d/m/Y", strtotime($fecha_ini)) . " al " . date("d/m/Y", strtotime($fecha_fin)));
                $sheet->mergeCells("A" . $fila . ":" . $colEnd . $fila);  //COMBINAR CELDAS
                $sheet->getStyle('A' . $fila . ':' . $colEnd . $fila)->getAlignment()->setHorizontal('center');
                $sheet->getStyle('A' . $fila . ':' . $colEnd . $fila)->applyFromArray($this->titulo);
                // $fila++;
                // $sheet->setCellValue('A' . $fila, "Encargado: " . $sucursal->user->full_name);
                // $sheet->mergeCells("A" . $fila . ":".$colEnd . $fila);  //COMBINAR CELDAS
                // $sheet->getStyle('A' . $fila . ':'.$colEnd . $fila)->getAlignment()->setHorizontal('center');
                // $sheet->getStyle('A' . $fila . ':'.$colEnd . $fila)->applyFromArray($this->titulo);
                $fila++;
                $fila++;
                $sheet->setCellValue('A' . $fila, 'N°');
                $sheet->setCellValue('B' . $fila, 'PRODUCTO');
                $sheet->setCellValue('C' . $fila, 'UNIDAD DE MEDIDA');

                $colIndex = 4;
                foreach ($clientes as $i => $item) {
                    $colorIndex = $i % count($this->bgStyles);

                    // Columna inicio y fin del bloque de 5
                    $colStart = Coordinate::stringFromColumnIndex($colIndex);
                    // Título de la sucursal (fila superior)
                    $sheet->setCellValue($colStart . $fila, $item->razon_social);
                    $sheet->getStyle("{$colStart}{$fila}:{$colEnd}{$fila}")
                        ->getAlignment()
                        ->setHorizontal('center')
                        ->setTextRotation(90); // 🔹 TEXTO VERTICAL
                    $colIndex++;
                }
                $colStart   = Coordinate::stringFromColumnIndex($colIndex);
                $sheet->setCellValue($colStart . $fila, "STOCK DIARIO ");
                $sheet->getStyle("{$colStart}{$fila}:{$colStart}{$fila}")
                    ->getAlignment()
                    ->setHorizontal('center')
                    ->setTextRotation(90); // 🔹 TEXTO VERTICAL
                $sheet->getStyle("{$colStart}{$fila}:{$colStart}{$fila}")->applyFromArray($this->bg0);
                $colIndex++;
                $colStart   = Coordinate::stringFromColumnIndex($colIndex);
                $sheet->setCellValue($colStart . $fila, "VENTAS ");
                $sheet->getStyle("{$colStart}{$fila}:{$colStart}{$fila}")
                    ->getAlignment()
                    ->setHorizontal('center')
                    ->setTextRotation(90); // 🔹 TEXTO VERTICAL
                $sheet->getStyle("{$colStart}{$fila}:{$colStart}{$fila}")->applyFromArray($this->bg1);
                $colIndex++;
                $colStart   = Coordinate::stringFromColumnIndex($colIndex);
                $sheet->setCellValue($colStart . $fila, "DEVOLUCIÓN ");
                $sheet->getStyle("{$colStart}{$fila}:{$colStart}{$fila}")
                    ->getAlignment()
                    ->setHorizontal('center')
                    ->setTextRotation(90); // 🔹 TEXTO VERTICAL
                $sheet->getStyle("{$colStart}{$fila}:{$colStart}{$fila}")->applyFromArray($this->bg2);
                $sheet->getStyle('A' . $fila . ':' . 'C' . $fila)->applyFromArray($this->headerTabla);
                $sheet->getStyle('D' . $fila . ':' . $colEnd . $fila)->applyFromArray($this->headerTabla2);
                $fila++;
                $cont = 1;
                foreach ($productos as $key => $producto) {
                    $colIndex = 4;
                    $sheet->setCellValue('A' . $fila, ($key + 1));
                    $sheet->setCellValue('B' . $fila, $producto->nombre);
                    $sheet->setCellValue('C' . $fila, $producto->unidad_medida->nombre);
                    foreach ($clientes as $i =>  $cliente) {
                        $cantidad = OrdenVentaDetalle::whereHas('orden_venta', function (
                            $query,
                        ) use ($sucursal, $cliente, $fecha_ini, $fecha_fin) {
                            $query->where('sucursal_id', $sucursal->id);
                            $query->where('cliente_id', $cliente->id);
                            $query->whereBetween('fecha', [$fecha_ini, $fecha_fin]);
                            $query->where('verificado', 2);
                        })
                            ->where('producto_id', $producto->id)
                            ->sum('cantidad');

                        $sheet->setCellValue(
                            Coordinate::stringFromColumnIndex($colIndex) . $fila,
                            $cantidad
                        );
                        $colIndex++;
                    }
                    $stock_diario = SucursalProducto::where('sucursal_id', $sucursal->id)
                        ->where('producto_id', $producto->id)
                        ->value('stock_actual');
                    $sheet->setCellValue(
                        Coordinate::stringFromColumnIndex($colIndex) . $fila,
                        $stock_diario
                    );
                    $colStart = Coordinate::stringFromColumnIndex($colIndex);
                    $sheet->getStyle("{$colStart}{$fila}:{$colStart}{$fila}")->applyFromArray($this->bg0);
                    $colIndex++;

                    $ventas = OrdenVentaDetalle::whereHas('orden_venta', function ($query) use (
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
                    $sheet->setCellValue(
                        Coordinate::stringFromColumnIndex($colIndex) . $fila,
                        $ventas
                    );
                    $colStart = Coordinate::stringFromColumnIndex($colIndex);
                    $sheet->getStyle("{$colStart}{$fila}:{$colStart}{$fila}")->applyFromArray($this->bg1);
                    $colIndex++;

                    $devoluciones = DevolucionClienteDetalle::whereHas(
                        'devolucion_cliente',
                        function ($query) use ($sucursal, $cliente, $fecha_ini, $fecha_fin) {
                            $query->where('sucursal_id', $sucursal->id);
                            // $query->where('cliente_id', $cliente->id);
                            $query->whereBetween('fecha', [$fecha_ini, $fecha_fin]);
                        },
                    )
                        ->where('producto_id', $producto->id)
                        ->sum('cantidad');
                    $sheet->setCellValue(
                        Coordinate::stringFromColumnIndex($colIndex) . $fila,
                        $devoluciones
                    );
                    $colStart = Coordinate::stringFromColumnIndex($colIndex);
                    $sheet->getStyle("{$colStart}{$fila}:{$colStart}{$fila}")->applyFromArray($this->bg2);
                    $sheet->getStyle('A' . $fila . ':' . $colEnd . $fila)->applyFromArray($this->bodyTabla);
                    $fila++;
                }
                $fila += 5;
            }

            $sheet->getColumnDimension('A')->setWidth(6);
            $sheet->getColumnDimension('B')->setWidth(25);
            $sheet->getColumnDimension('C')->setWidth(15);
            $sheet->getColumnDimension('D')->setWidth(15);
            $sheet->getColumnDimension('E')->setWidth(15);
            $sheet->getColumnDimension('F')->setWidth(15);
            $sheet->getColumnDimension('G')->setWidth(15);

            foreach (range('A', $colEnd) as $columnID) {
                $sheet->getStyle($columnID)->getAlignment()->setWrapText(true);
            }

            $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
            $sheet->getPageMargins()->setTop(0.5);
            $sheet->getPageMargins()->setRight(0.1);
            $sheet->getPageMargins()->setLeft(0.1);
            $sheet->getPageMargins()->setBottom(0.1);
            $sheet->getPageSetup()->setPrintArea('A:' . $colEnd);
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
        $fecha_ini =  $request->fecha_ini;
        $fecha_fin =  $request->fecha_fin;
        $productos = Producto::where("estado", 1)->get();

        if ($request->tipo == 'pdf') {
            $pdf = PDF::loadView('reportes.utilidad_ordens', compact(
                'sucursal_id',
                "fecha_ini",
                "fecha_fin",
                "productos",
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
            $sheet->mergeCells("A" . $fila . ":G" . $fila);  //COMBINAR CELDAS
            $sheet->getStyle('A' . $fila . ':G' . $fila)->getAlignment()->setHorizontal('center');
            $sheet->getStyle('A' . $fila . ':G' . $fila)->applyFromArray($this->titulo);
            $fila++;
            $sheet->setCellValue('A' . $fila, "UTILIDAD DE ORDENDES DE VENTA");
            $sheet->mergeCells("A" . $fila . ":G" . $fila);  //COMBINAR CELDAS
            $sheet->getStyle('A' . $fila . ':G' . $fila)->getAlignment()->setHorizontal('center');
            $sheet->getStyle('A' . $fila . ':G' . $fila)->applyFromArray($this->titulo);
            $fila++;
            $sheet->setCellValue('A' . $fila, "Del " . date("d/m/Y", strtotime($fecha_ini)) . ' al ' . date("d/m/Y", strtotime($fecha_fin)));
            $sheet->mergeCells("A" . $fila . ":G" . $fila);  //COMBINAR CELDAS
            $sheet->getStyle('A' . $fila . ':G' . $fila)->getAlignment()->setHorizontal('center');
            $sheet->getStyle('A' . $fila . ':G' . $fila)->applyFromArray($this->titulo);
            $fila++;
            $fila++;
            $sheet->setCellValue('A' . $fila, 'PRODUCTOS');
            $sheet->setCellValue('B' . $fila, 'UNIDAD');
            $sheet->setCellValue('C' . $fila, 'CANTIDAD VENDIDA');
            $sheet->setCellValue('D' . $fila, 'TOTAL');
            $sheet->setCellValue('E' . $fila, 'CANTIDAD COMPRADA');
            $sheet->setCellValue('F' . $fila, 'TOTAL');
            $sheet->setCellValue('G' . $fila, 'UTILIDAD');
            $sheet->getStyle('A' . $fila . ':G' . $fila)->applyFromArray($this->headerTabla);
            $fila++;


            $total_final1 = 0;
            $total_final2 = 0;
            $total_final3 = 0;
            $total_final4 = 0;
            $total_final5 = 0;

            foreach ($productos as $key => $value) {
                $orden_venta_detalles = OrdenVentaDetalle::select('orden_venta_detalles.*')->where(
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

                $solicitud_ingreso_detalles = SolicitudIngresoDetalle::select(
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
                $sheet->setCellValue('A' . $fila, $value->nombre);
                $sheet->setCellValue('B' . $fila, $value->unidad_medida->nombre);
                $sheet->setCellValue('C' . $fila, $total_ventas_cantidad);
                $sheet->setCellValue('D' . $fila, number_format($total_ventas, 2, ".", ","));
                $sheet->setCellValue('E' . $fila, $total_compras_cantidad);
                $sheet->setCellValue('F' . $fila, number_format($total_compras, 2, ".", ","));
                $sheet->setCellValue('G' . $fila, number_format($saldo, 2, ".", ","));
                $sheet->getStyle('A' . $fila . ':G' . $fila)->applyFromArray($this->bodyTabla);
                $fila++;
            }
            $sheet->setCellValue('A' . $fila, "TOTAL");
            $sheet->mergeCells("A" . $fila . ":B" . $fila);  //COMBINAR CELDAS
            $sheet->setCellValue('C' . $fila, number_format($total_final1, 2, '.', ','));
            $sheet->setCellValue('D' . $fila, number_format($total_final2, 2, '.', ','));
            $sheet->setCellValue('E' . $fila, number_format($total_final3, 2, '.', ','));
            $sheet->setCellValue('F' . $fila, number_format($total_final4, 2, '.', ','));
            $sheet->setCellValue('G' . $fila, number_format($total_final5, 2, '.', ','));
            $sheet->getStyle('A' . $fila . ':G' . $fila)->applyFromArray($this->headerTabla);
            $sheet->getColumnDimension('A')->setWidth(15);
            $sheet->getColumnDimension('B')->setWidth(15);
            $sheet->getColumnDimension('C')->setWidth(15);
            $sheet->getColumnDimension('D')->setWidth(15);
            $sheet->getColumnDimension('E')->setWidth(15);
            $sheet->getColumnDimension('F')->setWidth(15);
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
                'utilidad_ordens_' . time() . '.xlsx',
                [
                    'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                ]
            );
        }
    }

    public function utilidad_ordens_g(Request $request)
    {
        $sucursal_id =  $request->sucursal_id;
        $fecha_ini =  $request->fecha_ini;
        $fecha_fin =  $request->fecha_fin;
        $productos = Producto::where("estado", 1)->get();

        $categories = [];
        $data = [];


        foreach ($productos as $key => $value) {
            $orden_venta_detalles = OrdenVentaDetalle::select('orden_venta_detalles.*')->where(
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

            $solicitud_ingreso_detalles = SolicitudIngresoDetalle::select(
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
            $saldo = (float) $total_ventas - (float) $total_compras;

            $data[] = [
                'name' => $value->nombre,
                'y' => (float) $saldo,
            ];
        }

        return response()->JSON([
            "categories" => $categories,
            "data" => $data,
        ]);
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
                $sheet->setCellValue('E' . $fila, number_format($item->total, 2, ".", ","));
                $sheet->setCellValue('F' . $fila, number_format($item->cancelado, 2, ".", ","));
                $sheet->setCellValue('G' . $fila, number_format($item->saldo, 2, ".", ","));
                $sheet->getStyle('A' . $fila . ':G' . $fila)->applyFromArray($this->bodyTabla);
                $fila++;
            }
            $total = $cuenta_cobrars->sum('total');
            $cancelado = $cuenta_cobrars->sum('cancelado');
            $saldo = $cuenta_cobrars->sum('saldo');
            $sheet->setCellValue('A' . $fila, "TOTAL");
            $sheet->mergeCells("A" . $fila . ":D" . $fila);  //COMBINAR CELDAS
            $sheet->setCellValue('E' . $fila, number_format($total, 2, ".", ","));
            $sheet->setCellValue('F' . $fila, number_format($cancelado, 2, ".", ","));
            $sheet->setCellValue('G' . $fila, number_format($saldo, 2, ".", ","));
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

    public function rotacion(Request $request)
    {
        ini_set('memory_limit', '1024M');
        set_time_limit(-1);
        $producto_id =  $request->producto_id;
        $fecha_ini =  $request->fecha_ini;
        $fecha_fin =  $request->fecha_fin;
        $productosMasVendidos = OrdenVentaDetalle::select(
            'producto_id',
            DB::raw('SUM(cantidad) as total_vendido')
        );
        if ($producto_id != 'todos') {
            $productosMasVendidos->where('producto_id', $producto_id);
        }

        if ($fecha_ini && $fecha_fin) {
            $productosMasVendidos->whereHas('orden_venta', function ($query) use ($fecha_fin, $fecha_ini) {
                $query->whereBetween("fecha", [$fecha_ini, $fecha_fin]);
                $query->where('verificado', 2);
            });
        }

        $productosMasVendidos =   $productosMasVendidos
            ->whereHas("orden_venta", function ($query) {
                $query->where("estado", "FINALIZADO");
            })
            ->groupBy('producto_id')
            ->orderByDesc('total_vendido')
            ->take(10)->get();

        if ($request->tipo == 'pdf') {
            $pdf = PDF::loadView('reportes.rotacion', compact('productosMasVendidos', 'fecha_ini', 'fecha_fin'))->setPaper('letter', 'portrait');

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
            $sheet->mergeCells("A" . $fila . ":C" . $fila);  //COMBINAR CELDAS
            $sheet->getStyle('A' . $fila . ':C' . $fila)->getAlignment()->setHorizontal('center');
            $sheet->getStyle('A' . $fila . ':C' . $fila)->applyFromArray($this->titulo);
            $fila++;
            $sheet->setCellValue('A' . $fila, "ROTACIÓN DE PRODUCTOS");
            $sheet->mergeCells("A" . $fila . ":C" . $fila);  //COMBINAR CELDAS
            $sheet->getStyle('A' . $fila . ':C' . $fila)->getAlignment()->setHorizontal('center');
            $sheet->getStyle('A' . $fila . ':C' . $fila)->applyFromArray($this->titulo);
            $fila++;
            $sheet->setCellValue('A' . $fila, "Del " . date("d/m/Y", strtotime($fecha_ini)) . ' al ' . date("d/m/Y", strtotime($fecha_fin)));
            $sheet->mergeCells("A" . $fila . ":C" . $fila);  //COMBINAR CELDAS
            $sheet->getStyle('A' . $fila . ':C' . $fila)->getAlignment()->setHorizontal('center');
            $sheet->getStyle('A' . $fila . ':C' . $fila)->applyFromArray($this->titulo);
            $fila++;
            $fila++;
            $sheet->setCellValue('A' . $fila, 'N°');
            $sheet->setCellValue('B' . $fila, 'PRODUCTO');
            $sheet->setCellValue('C' . $fila, 'CANTIDAD VENDIDA');
            $sheet->getStyle('A' . $fila . ':C' . $fila)->applyFromArray($this->headerTabla);
            $fila++;

            foreach ($productosMasVendidos as $key => $item) {
                $sheet->setCellValue('A' . $fila, $key + 1);
                $sheet->setCellValue('B' . $fila, $item->producto->nombre);
                $sheet->setCellValue('C' . $fila, $item->total_vendido);
                $sheet->getStyle('A' . $fila . ':C' . $fila)->applyFromArray($this->bodyTabla);
                $fila++;
            }

            $sheet->getColumnDimension('A')->setWidth(10);
            $sheet->getColumnDimension('B')->setWidth(40);
            $sheet->getColumnDimension('C')->setWidth(15);

            foreach (range('A', 'C') as $columnID) {
                $sheet->getStyle($columnID)->getAlignment()->setWrapText(true);
            }

            $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
            $sheet->getPageMargins()->setTop(0.5);
            $sheet->getPageMargins()->setRight(0.1);
            $sheet->getPageMargins()->setLeft(0.1);
            $sheet->getPageMargins()->setBottom(0.1);
            $sheet->getPageSetup()->setPrintArea('A:C');
            $sheet->getPageSetup()->setFitToWidth(1);
            $sheet->getPageSetup()->setFitToHeight(0);

            return response()->streamDownload(
                function () use ($spreadsheet) {
                    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
                    $writer->save('php://output');
                },
                'rotacion_' . time() . '.xlsx',
                [
                    'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                ]
            );
        }
    }

    public function gastos(Request $request)
    {
        ini_set('memory_limit', '1024M');
        set_time_limit(-1);
        $fecha_ini =  $request->fecha_ini;
        $fecha_fin =  $request->fecha_fin;
        $gastos = Gasto::select("gastos.*");
        if ($fecha_ini  && $fecha_fin) {
            $gastos->whereBetween('fecha', [$fecha_ini, $fecha_fin]);
        }

        $gastos = $gastos->get();

        if ($request->tipo == 'pdf') {
            $pdf = PDF::loadView('reportes.gastos', compact('gastos', 'fecha_ini', 'fecha_fin'))->setPaper('letter', 'portrait');

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
            $sheet->mergeCells("A" . $fila . ":D" . $fila);  //COMBINAR CELDAS
            $sheet->getStyle('A' . $fila . ':D' . $fila)->getAlignment()->setHorizontal('center');
            $sheet->getStyle('A' . $fila . ':D' . $fila)->applyFromArray($this->titulo);
            $fila++;
            $sheet->setCellValue('A' . $fila, "LISTA DE GASTOS");
            $sheet->mergeCells("A" . $fila . ":D" . $fila);  //COMBINAR CELDAS
            $sheet->getStyle('A' . $fila . ':D' . $fila)->getAlignment()->setHorizontal('center');
            $sheet->getStyle('A' . $fila . ':D' . $fila)->applyFromArray($this->titulo);
            $fila++;
            $sheet->setCellValue('A' . $fila, "Del " . date("d/m/Y", strtotime($fecha_ini)) . ' al ' . date("d/m/Y", strtotime($fecha_fin)));
            $sheet->mergeCells("A" . $fila . ":D" . $fila);  //COMBINAR CELDAS
            $sheet->getStyle('A' . $fila . ':D' . $fila)->getAlignment()->setHorizontal('center');
            $sheet->getStyle('A' . $fila . ':D' . $fila)->applyFromArray($this->titulo);
            $fila++;
            $fila++;
            $sheet->setCellValue('A' . $fila, 'N°');
            $sheet->setCellValue('B' . $fila, 'DESCRIPCIÓN');
            $sheet->setCellValue('C' . $fila, 'FECHA');
            $sheet->setCellValue('D' . $fila, 'MONTO');
            $sheet->getStyle('A' . $fila . ':D' . $fila)->applyFromArray($this->headerTabla);
            $fila++;

            foreach ($gastos as $key => $item) {
                $sheet->setCellValue('A' . $fila, $key + 1);
                $sheet->setCellValue('B' . $fila, $item->descripcion);
                $sheet->setCellValue('C' . $fila, $item->fecha_c);
                $sheet->setCellValue('D' . $fila, $item->monto);
                $sheet->getStyle('A' . $fila . ':D' . $fila)->applyFromArray($this->bodyTabla);
                $fila++;
            }
            $sheet->setCellValue('A' . $fila, 'TOTAL');
            $sheet->mergeCells("A" . $fila . ":C" . $fila);  //COMBINAR CELDAS
            $sheet->setCellValue('D' . $fila, number_format($gastos->sum("monto"), 2, ".", ","));
            $sheet->getStyle('A' . $fila . ':D' . $fila)->applyFromArray($this->headerTabla);

            $sheet->getColumnDimension('A')->setWidth(8);
            $sheet->getColumnDimension('B')->setWidth(35);
            $sheet->getColumnDimension('C')->setWidth(16);
            $sheet->getColumnDimension('D')->setWidth(16);

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
                'gastos_' . time() . '.xlsx',
                [
                    'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                ]
            );
        }
    }

    public function diario_salidas(Request $request)
    {
        ini_set('memory_limit', '1024M');
        set_time_limit(-1);
        $sucursal_id =  $request->sucursal_id;
        $fecha =  $request->fecha;

        $sucursals = Sucursal::select("sucursals.*");
        if ($sucursal_id != 'todos') {
            $sucursals->where("id", $sucursal_id);
        }

        $sucursals = $sucursals->where("estado", 1)->get();


        $productos = Producto::select("productos.*");
        $productos = $productos->where("estado", 1)->get();

        if ($request->tipo == 'pdf') {
            $pdf = PDF::loadView('reportes.diario_salidas', compact(
                "sucursals",
                "productos",
                "fecha",
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
                $sheet->mergeCells("A" . $fila . ":G" . $fila);  //COMBINAR CELDAS
                $sheet->getStyle('A' . $fila . ':G' . $fila)->getAlignment()->setHorizontal('center');
                $sheet->getStyle('A' . $fila . ':G' . $fila)->applyFromArray($this->titulo);
                $fila++;
                $sheet->setCellValue('A' . $fila, "REPORTE DIARIO DE SALIDAS POR SUCURSAL");
                $sheet->mergeCells("A" . $fila . ":G" . $fila);  //COMBINAR CELDAS
                $sheet->getStyle('A' . $fila . ':G' . $fila)->getAlignment()->setHorizontal('center');
                $sheet->getStyle('A' . $fila . ':G' . $fila)->applyFromArray($this->titulo);
                $fila++;
                $sheet->setCellValue('A' . $fila, "Sucursal: " . $sucursal->nombre);
                $sheet->mergeCells("A" . $fila . ":G" . $fila);  //COMBINAR CELDAS
                $sheet->getStyle('A' . $fila . ':G' . $fila)->getAlignment()->setHorizontal('center');
                $sheet->getStyle('A' . $fila . ':G' . $fila)->applyFromArray($this->titulo);
                $fila++;
                $sheet->setCellValue('A' . $fila, "Fecha: " . date("d/m/Y", strtotime($fecha)));
                $sheet->mergeCells("A" . $fila . ":G" . $fila);  //COMBINAR CELDAS
                $sheet->getStyle('A' . $fila . ':G' . $fila)->getAlignment()->setHorizontal('center');
                $sheet->getStyle('A' . $fila . ':G' . $fila)->applyFromArray($this->titulo);
                $fila++;
                $sheet->setCellValue('A' . $fila, "Encargado: " . $sucursal->user->full_name);
                $sheet->mergeCells("A" . $fila . ":G" . $fila);  //COMBINAR CELDAS
                $sheet->getStyle('A' . $fila . ':G' . $fila)->getAlignment()->setHorizontal('center');
                $sheet->getStyle('A' . $fila . ':G' . $fila)->applyFromArray($this->titulo);
                $fila++;
                $fila++;
                $sheet->setCellValue('A' . $fila, 'N°');
                $sheet->setCellValue('B' . $fila, 'PRODUCTO');
                $sheet->setCellValue('C' . $fila, 'SALDO INICIAL');
                $sheet->setCellValue('D' . $fila, 'VENTAS REALIZADAS');
                $sheet->setCellValue('E' . $fila, 'DEVOLUCIONES');
                $sheet->setCellValue('F' . $fila, 'PRODUCTOS AÑADIDOS');
                $sheet->setCellValue('G' . $fila, 'SALDO FINAL');
                $sheet->getStyle('A' . $fila . ':G' . $fila)->applyFromArray($this->headerTabla);
                $fila++;

                $cont = 1;
                $sucursal_id = $sucursal->id;
                foreach ($productos as $key => $producto) {
                    // SALDO INICIAL
                    $kardex_inicial = KardexProducto::where('producto_id', $producto->id)
                        ->where('fecha', $fecha)
                        ->where('sucursal_id', $sucursal_id)
                        ->get()
                        ->first();
                    if ($kardex_inicial) {
                        if ($kardex_inicial->tipo_is == 'EGRESO') {
                            $kardex_inicial = KardexProducto::where('producto_id', $producto->id)
                                ->where('id', '<', $kardex_inicial->id)
                                ->where('tipo_is', 'INGRESO')
                                ->where('sucursal_id', $sucursal_id)
                                ->get()
                                ->last();
                        }
                        $saldo_inicial = $kardex_inicial->cantidad_saldo;
                    } else {
                        $saldo_inicial = 0;
                    }

                    // ventas realizadas
                    $ventas_realizadas = OrdenVentaDetalle::where('producto_id', $producto->id);
                    $ventas_realizadas->whereHas('orden_venta', function ($query) use ($fecha, $sucursal_id) {
                        $query->where('fecha', $fecha)->where('sucursal_id', $sucursal_id);
                        $query->where('verificado', 2);
                    });
                    $ventas_realizadas = $ventas_realizadas->sum('cantidad');

                    // DEVOLUCIONES
                    $devoluciones = DevolucionClienteDetalle::where('producto_id', $producto->id);
                    $devoluciones->whereHas('devolucion_cliente', function ($query) use ($fecha, $sucursal_id) {
                        $query->where('fecha', $fecha)->where('sucursal_id', $sucursal_id);
                    });
                    $devoluciones = $devoluciones->sum('cantidad');

                    // INGRESOS ADICIONALES
                    $ingresos_adicionales = KardexProducto::where('producto_id', $producto->id)
                        ->where('fecha', $fecha)
                        ->where('tipo_is', 'INGRESO')
                        ->where('sucursal_id', $sucursal_id)
                        ->sum('cantidad_ingreso');
                    // SALDO FINAL
                    $kardex_final = KardexProducto::where('producto_id', $producto->id)
                        ->where('fecha', $fecha)
                        ->where('sucursal_id', $sucursal_id)
                        ->where('tipo_registro', '!=', 'DEVOLUCIÓN DE STOCK')
                        ->where('id', '>', $kardex_inicial ? $kardex_inicial->id : 0)
                        ->get()
                        ->last();
                    $saldo_final = $kardex_final ? $kardex_final->cantidad_saldo : 0;


                    $sheet->setCellValue('A' . $fila, $cont++);
                    $sheet->setCellValue('B' . $fila, $producto->nombre . ' ' . $producto->unidad_medida->nombre);
                    $sheet->setCellValue('C' . $fila, $saldo_inicial);
                    $sheet->setCellValue('D' . $fila, $ventas_realizadas);
                    $sheet->setCellValue('E' . $fila, $devoluciones);
                    $sheet->setCellValue('F' . $fila, $ingresos_adicionales);
                    $sheet->setCellValue('G' . $fila, $saldo_final);
                    $sheet->getStyle('A' . $fila . ':G' . $fila)->applyFromArray($this->bodyTabla);
                    $fila++;
                }
                $fila += 4;
            }

            $sheet->getColumnDimension('A')->setWidth(6);
            $sheet->getColumnDimension('B')->setWidth(25);
            $sheet->getColumnDimension('C')->setWidth(15);
            $sheet->getColumnDimension('D')->setWidth(15);
            $sheet->getColumnDimension('E')->setWidth(15);
            $sheet->getColumnDimension('F')->setWidth(15);
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
                'diario_salidas_' . time() . '.xlsx',
                [
                    'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                ]
            );
        }
    }

    public function movimientos_abastecimiento(Request $request)
    {
        ini_set('memory_limit', '1024M');
        set_time_limit(-1);
        $sucursal_id =  $request->sucursal_id;
        $unidad_medida_id =  $request->unidad_medida_id;
        $producto_id =  $request->producto_id;
        $fecha_ini =  $request->fecha_ini;
        $fecha_fin =  $request->fecha_fin;

        $sucursals = Sucursal::select("sucursals.*");
        if ($sucursal_id != 'todos') {
            $sucursals->where("id", $sucursal_id);
        }

        $sucursals = $sucursals->where("estado", 1)->get();

        $productos = Producto::select("productos.*");
        if ($unidad_medida_id != 'todos') {
            $productos->where("unidad_medida_id", $unidad_medida_id)->get();
        }
        if ($producto_id != 'todos') {
            $productos->where("id", $producto_id)->get();
        }
        $productos = $productos->where("estado", 1)->get();

        if ($request->tipo == 'pdf') {
            $pdf = PDF::loadView('reportes.movimientos_abastecimiento', compact(
                "sucursals",
                "productos",
                "fecha_ini",
                "fecha_fin",
            ))->setPaper('letter', 'landscape');

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
            $fechaInicio = new DateTime($fecha_ini);
            $fechaFin = new DateTime($fecha_fin);

            $dias = $fechaInicio->diff($fechaFin)->days + 1;
            $cols = ["D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S"];
            $lastCol = $cols[(int)$dias - 1];

            foreach ($sucursals as $sucursal) {
                $sheet->setCellValue('A' . $fila, $this->configuracion->nombre_sistema);
                $sheet->mergeCells("A" . $fila . ":" . $lastCol . $fila);  //COMBINAR CELDAS
                $sheet->getStyle('A' . $fila . ':' . $lastCol . $fila)->getAlignment()->setHorizontal('center');
                $sheet->getStyle('A' . $fila . ':' . $lastCol . $fila)->applyFromArray($this->titulo);
                $fila++;
                $sheet->setCellValue('A' . $fila, "REPORTE SEMANAL DE MOVIMIENTOS Y ABASTECIMIENTO");
                $sheet->mergeCells("A" . $fila . ":" . $lastCol . $fila);  //COMBINAR CELDAS
                $sheet->getStyle('A' . $fila . ':' . $lastCol . $fila)->getAlignment()->setHorizontal('center');
                $sheet->getStyle('A' . $fila . ':' . $lastCol . $fila)->applyFromArray($this->titulo);
                $fila++;
                $sheet->setCellValue('A' . $fila, "Del " . date("d/m/Y", strtotime($fecha_ini)) . ' al ' . date("d/m/Y", strtotime($fecha_fin)));
                $sheet->mergeCells("A" . $fila . ":" . $lastCol . $fila);  //COMBINAR CELDAS
                $sheet->getStyle('A' . $fila . ':' . $lastCol . $fila)->getAlignment()->setHorizontal('center');
                $sheet->getStyle('A' . $fila . ':' . $lastCol . $fila)->applyFromArray($this->titulo);
                $fila++;
                $sheet->setCellValue('A' . $fila, "Sucursal: " . $sucursal->nombre);
                $sheet->mergeCells("A" . $fila . ":" . $lastCol . $fila);  //COMBINAR CELDAS
                $sheet->getStyle('A' . $fila . ':' . $lastCol . $fila)->getAlignment()->setHorizontal('center');
                $sheet->getStyle('A' . $fila . ':' . $lastCol . $fila)->applyFromArray($this->titulo);
                $fila++;
                $sheet->setCellValue('A' . $fila, "Encargado: " . $sucursal->user->full_name);
                $sheet->mergeCells("A" . $fila . ":" . $lastCol . $fila);  //COMBINAR CELDAS
                $sheet->getStyle('A' . $fila . ':' . $lastCol . $fila)->getAlignment()->setHorizontal('center');
                $sheet->getStyle('A' . $fila . ':' . $lastCol . $fila)->applyFromArray($this->titulo);
                $fila++;
                $fila++;
                $sheet->setCellValue('A' . $fila, 'N°');
                $sheet->setCellValue('B' . $fila, 'CÓD. PRODUCTO');
                $sheet->setCellValue('C' . $fila, 'PRODUCTO');
                $fecha_aux = date("Y-m-d", strtotime($fecha_ini));
                foreach ($cols as $c) {
                    $text_add = "\n (SALIDA)";
                    if ($fecha_aux == $fecha_fin) {
                        $text_add = "\n (SALDO)";
                    }
                    $sheet->setCellValue($c . $fila, $fecha_aux . $text_add);
                    if ($c == $lastCol) {
                        break;
                    }
                    $fecha_aux = date('Y-m-d', strtotime($fecha_aux . ' +1days'));
                }
                $sheet->getStyle('A' . $fila . ':' . $lastCol . $fila)->applyFromArray($this->headerTabla);
                $fila++;

                $cont = 1;
                $sucursal_id = $sucursal->id;
                foreach ($productos as $key => $producto) {
                    $fecha_aux = date('Y-m-d', strtotime($fecha_ini));
                    $sheet->setCellValue('A' . $fila, $cont++);
                    $sheet->setCellValue('B' . $fila, $producto->codigo);
                    $sheet->setCellValue('C' . $fila, $producto->nombre . ' ' . $producto->unidad_medida->nombre);
                    foreach ($cols as $c) {
                        if ($fecha_aux < $fecha_fin) {
                            // ventas realizadas
                            $ventas_realizadas = OrdenVentaDetalle::where(
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
                            $total = KardexProducto::where('producto_id', $producto->id)
                                ->where('fecha', $fecha_aux)
                                ->where('sucursal_id', $sucursal_id)
                                ->where('tipo_registro', '!=', 'DEVOLUCIÓN DE STOCK')
                                ->get()
                                ->last();
                            $total = $total ? $total->cantidad_saldo : 0;
                        }
                        $sheet->setCellValue($c . $fila, $total);
                        if ($c == $lastCol) {
                            break;
                        }
                        $fecha_aux = date('Y-m-d', strtotime($fecha_aux . ' +1days'));
                    }
                    $sheet->getStyle('A' . $fila . ':' . $lastCol . $fila)->applyFromArray($this->bodyTabla);
                    $fila++;
                }
                $fila += 4;
            }

            $sheet->getColumnDimension('A')->setWidth(6);
            $sheet->getColumnDimension('B')->setWidth(25);
            $sheet->getColumnDimension('C')->setWidth(15);
            $sheet->getColumnDimension('D')->setWidth(15);
            $sheet->getColumnDimension('E')->setWidth(15);
            $sheet->getColumnDimension('F')->setWidth(15);
            $sheet->getColumnDimension('G')->setWidth(15);

            foreach (range('A', $lastCol) as $columnID) {
                $sheet->getStyle($columnID)->getAlignment()->setWrapText(true);
            }

            $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
            $sheet->getPageMargins()->setTop(0.5);
            $sheet->getPageMargins()->setRight(0.1);
            $sheet->getPageMargins()->setLeft(0.1);
            $sheet->getPageMargins()->setBottom(0.1);
            $sheet->getPageSetup()->setPrintArea('A:' . $lastCol);
            $sheet->getPageSetup()->setFitToWidth(1);
            $sheet->getPageSetup()->setFitToHeight(0);

            return response()->streamDownload(
                function () use ($spreadsheet) {
                    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
                    $writer->save('php://output');
                },
                'movimientos_abastecimiento_' . time() . '.xlsx',
                [
                    'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                ]
            );
        }
    }


    public function saldos_almacen_central(Request $request)
    {
        ini_set('memory_limit', '1024M');
        set_time_limit(-1);
        $sucursal_id =  $request->sucursal_id;
        $unidad_medida_id =  $request->unidad_medida_id;
        $producto_id =  $request->producto_id;
        $fecha =  $request->fecha;

        $sucursals = Sucursal::select("sucursals.*");
        if ($sucursal_id != 'todos') {
            $sucursals->where("id", $sucursal_id);
        }

        $sucursals = $sucursals->where("almacen", 0)
            ->where("estado", 1)->get();

        $productos = Producto::select("productos.*");
        if ($unidad_medida_id != 'todos') {
            $productos->where("unidad_medida_id", $unidad_medida_id);
        }
        if ($producto_id != 'todos') {
            $productos->where("id", $producto_id);
        }

        $productos = $productos->where("estado", 1)->get();

        if ($request->tipo == 'pdf') {
            $pdf = PDF::loadView('reportes.saldos_almacen_central', compact(
                "sucursals",
                "productos",
                "fecha",
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

            $sheet->setCellValue('A' . $fila, $this->configuracion->nombre_sistema);
            $sheet->mergeCells("A" . $fila . ":F" . $fila);  //COMBINAR CELDAS
            $sheet->getStyle('A' . $fila . ':F' . $fila)->getAlignment()->setHorizontal('center');
            $sheet->getStyle('A' . $fila . ':F' . $fila)->applyFromArray($this->titulo);
            $fila++;
            $sheet->setCellValue('A' . $fila, "REPORTE DIARIO DE SALIDAS POR SUCURSAL");
            $sheet->mergeCells("A" . $fila . ":F" . $fila);  //COMBINAR CELDAS
            $sheet->getStyle('A' . $fila . ':F' . $fila)->getAlignment()->setHorizontal('center');
            $sheet->getStyle('A' . $fila . ':F' . $fila)->applyFromArray($this->titulo);
            $fila++;
            $sheet->setCellValue('A' . $fila, "FECHA: " . date("d/m/Y", strtotime($fecha)));
            $sheet->mergeCells("A" . $fila . ":F" . $fila);  //COMBINAR CELDAS
            $sheet->getStyle('A' . $fila . ':F' . $fila)->getAlignment()->setHorizontal('center');
            $sheet->getStyle('A' . $fila . ':F' . $fila)->applyFromArray($this->titulo);
            $fila++;
            $fila++;
            $sheet->setCellValue('A' . $fila, 'N°');
            $sheet->setCellValue('B' . $fila, 'CÓD. PRODUCTO');
            $sheet->setCellValue('C' . $fila, 'PRODUCTO');
            $sheet->setCellValue('D' . $fila, 'LLEGADA PRODUCTO');
            $sheet->setCellValue('E' . $fila, 'SALIDA A SUCURSALES');
            $sheet->setCellValue('F' . $fila, 'INICIO SALDOS');
            $sheet->getStyle('A' . $fila . ':F' . $fila)->applyFromArray($this->headerTabla);
            $fila++;

            $cont = 1;
            foreach ($productos as $key => $producto) {
                // ABASTECIMIENTO
                $kardex_ingreso = KardexProducto::where('producto_id', $producto->id)
                    ->where('fecha', $fecha)
                    ->where('tipo_registro', 'SOLICITUD INGRESO')
                    ->where('sucursal_id', 1)
                    ->get()
                    ->first();
                $total_ingreso = $kardex_ingreso ? $kardex_ingreso->cantidad_ingreso : 0;

                // SALIDAS A SUCURSAL
                $kardex_salida = KardexProducto::where('producto_id', $producto->id)
                    ->where('fecha', $fecha)
                    ->where('tipo_registro', 'ORDEN DE SALIDA')
                    ->where('sucursal_id', 1)
                    ->get()
                    ->first();
                $total_salidas = $kardex_salida ? $kardex_salida->cantidad_salida : 0;

                // SALDO INICIAL
                $kardex_inicial = KardexProducto::where('producto_id', $producto->id)
                    ->where('fecha', $fecha)
                    ->where('sucursal_id', 1)
                    ->get()
                    ->first();
                $saldo_inicial = 0;
                if ($kardex_inicial) {
                    if ($kardex_inicial->tipo_is == 'EGRESO') {
                        $kardex_inicial = KardexProducto::where('producto_id', $producto->id)
                            ->where('id', '<', $kardex_inicial->id)
                            // ->where('tipo_is', 'INGRESO')
                            ->where('sucursal_id', 1)
                            ->get()
                            ->last();
                    }
                    $saldo_inicial = $kardex_inicial->cantidad_saldo;
                }

                $sheet->setCellValue('A' . $fila, $cont++);
                $sheet->setCellValue('B' . $fila, $producto->codigo);
                $sheet->setCellValue('C' . $fila, $producto->nombre . ' ' . $producto->unidad_medida->nombre);
                $sheet->setCellValue('D' . $fila, $total_ingreso);
                $sheet->setCellValue('E' . $fila, $total_salidas);
                $sheet->setCellValue('F' . $fila, $saldo_inicial);
                $sheet->getStyle('A' . $fila . ':F' . $fila)->applyFromArray($this->bodyTabla);
                $fila++;
            }

            $sheet->getColumnDimension('A')->setWidth(6);
            $sheet->getColumnDimension('B')->setWidth(25);
            $sheet->getColumnDimension('C')->setWidth(15);
            $sheet->getColumnDimension('D')->setWidth(15);
            $sheet->getColumnDimension('E')->setWidth(15);
            $sheet->getColumnDimension('F')->setWidth(15);

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
                'saldos_almacen_central_' . time() . '.xlsx',
                [
                    'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                ]
            );
        }
    }

    public function diario_vehiculos(Request $request)
    {
        ini_set('memory_limit', '1024M');
        set_time_limit(-1);
        $sucursal_id =  $request->sucursal_id;
        $fecha =  $request->fecha;

        $sucursals = Sucursal::select("sucursals.*");
        if ($sucursal_id != 'todos') {
            $sucursals->where("id", $sucursal_id);
        }

        $sucursals = $sucursals->where("almacen", 0)
            ->where("estado", 1)->get();


        $productos = Producto::select("productos.*");
        $productos = $productos->where("estado", 1)->get();

        if ($request->tipo == 'pdf') {
            $pdf = PDF::loadView('reportes.diario_vehiculos', compact(
                "sucursals",
                "productos",
                "fecha",
            ))->setPaper('legal', 'landscape');

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

            $colIndex = 4;

            foreach ($sucursals as $key => $sucursal) {
                // Columna inicio y fin del bloque de 5
                $colStart = Coordinate::stringFromColumnIndex($colIndex);
                $colEnd   = Coordinate::stringFromColumnIndex($colIndex + 4);

                $colIndex += 5;
            }
            $colStart = Coordinate::stringFromColumnIndex($colIndex);
            $colEnd   = Coordinate::stringFromColumnIndex($colIndex + 4);

            $sheet->setCellValue('A' . $fila, $this->configuracion->nombre_sistema);
            $sheet->mergeCells("A" . $fila . ":" . $colEnd . $fila);  //COMBINAR CELDAS
            $sheet->getStyle('A' . $fila . ':' . $colEnd . $fila)->getAlignment()->setHorizontal('center');
            $sheet->getStyle('A' . $fila . ':' . $colEnd . $fila)->applyFromArray($this->titulo);
            $fila++;
            $sheet->setCellValue('A' . $fila, "CONTROL DIARIO DE SUCURSALES(VEHÍCULOS)");
            $sheet->mergeCells("A" . $fila . ":" . $colEnd . $fila);  //COMBINAR CELDAS
            $sheet->getStyle('A' . $fila . ':' . $colEnd . $fila)->getAlignment()->setHorizontal('center');
            $sheet->getStyle('A' . $fila . ':' . $colEnd . $fila)->applyFromArray($this->titulo);
            $fila++;
            $sheet->setCellValue('A' . $fila, "FECHA: " . date("d/m/Y", strtotime($fecha)));
            $sheet->mergeCells("A" . $fila . ":" . $colEnd . $fila);  //COMBINAR CELDAS
            $sheet->getStyle('A' . $fila . ':' . $colEnd . $fila)->getAlignment()->setHorizontal('center');
            $sheet->getStyle('A' . $fila . ':' . $colEnd . $fila)->applyFromArray($this->titulo);
            // $fila++;
            // $sheet->setCellValue('A' . $fila, "Sucursal: " . $sucursal->nombre);
            // $sheet->mergeCells("A" . $fila . ":".$colEnd . $fila);  //COMBINAR CELDAS
            // $sheet->getStyle('A' . $fila . ':'.$colEnd . $fila)->getAlignment()->setHorizontal('center');
            // $sheet->getStyle('A' . $fila . ':'.$colEnd . $fila)->applyFromArray($this->titulo);
            // $fila++;
            // $sheet->setCellValue('A' . $fila, "Encargado: " . $sucursal->user->full_name);
            // $sheet->mergeCells("A" . $fila . ":".$colEnd . $fila);  //COMBINAR CELDAS
            // $sheet->getStyle('A' . $fila . ':'.$colEnd . $fila)->getAlignment()->setHorizontal('center');
            // $sheet->getStyle('A' . $fila . ':'.$colEnd . $fila)->applyFromArray($this->titulo);
            $fila++;
            $fila++;
            $sheet->setCellValue('A' . $fila, 'N°');
            $sheet->mergeCells("A" . $fila . ":A" . $fila + 1);  //COMBINAR CELDAS
            $sheet->setCellValue('B' . $fila, 'PRODUCTO');
            $sheet->mergeCells("B" . $fila . ":B" . $fila + 1);  //COMBINAR CELDAS
            $sheet->setCellValue('C' . $fila, 'UNIDAD DE MEDIDA');
            $sheet->mergeCells("C" . $fila . ":C" . $fila + 1);  //COMBINAR CELDAS

            $colIndex = 4;
            // Subcolumnas (fila inferior)
            $subHeaders = ['AÑADIDOS', 'CANTIDAD ENTREGADA', 'DEVOLUCIONES', 'DIFERENCIAS/FALTANTES', 'SALDO FINAL'];

            foreach ($sucursals as $i => $sucursal) {
                $colorIndex = $i % count($this->bgStyles);

                // Columna inicio y fin del bloque de 5
                $colStart = Coordinate::stringFromColumnIndex($colIndex);
                $colEnd   = Coordinate::stringFromColumnIndex($colIndex + 4);

                // Título de la sucursal (fila superior)
                $sheet->setCellValue($colStart . $fila, $sucursal->nombre);
                $sheet->mergeCells("{$colStart}{$fila}:{$colEnd}{$fila}");
                $sheet->getStyle("{$colStart}{$fila}:{$colEnd}{$fila}")
                    ->getAlignment()->setHorizontal('center');

                $sheet->getStyle("{$colStart}{$fila}:{$colEnd}{$fila}")
                    ->applyFromArray($this->bgStyles[$colorIndex]);

                foreach ($subHeaders as $i => $text) {
                    $col = Coordinate::stringFromColumnIndex($colIndex + $i);
                    $sheet->setCellValue($col . ($fila + 1), $text);
                    $sheet->getStyle($col . ($fila + 1))
                        ->getAlignment()
                        ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                        ->setVertical(Alignment::VERTICAL_CENTER)
                        ->setTextRotation(90); // 🔹 TEXTO VERTICAL
                    $sheet->getStyle($col . ($fila + 1))
                        ->applyFromArray($this->bgStyles[$colorIndex]);
                }
                $colIndex += 5;
            }

            foreach ($subHeaders as $i => $text) {
                $col = Coordinate::stringFromColumnIndex($colIndex + $i);
                $sheet->setCellValue($col . ($fila + 1), $text);
                $sheet->getStyle($col . ($fila + 1))
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setVertical(Alignment::VERTICAL_CENTER)
                    ->setTextRotation(90); // 🔹 TEXTO VERTICAL
            }
            $colStart = Coordinate::stringFromColumnIndex($colIndex);
            $colEnd   = Coordinate::stringFromColumnIndex($colIndex + 4);

            $sheet->getStyle('A' . $fila . ':' . 'C' . $fila)->applyFromArray($this->headerTabla);
            $sheet->getStyle('D' . $fila . ':' . $colEnd . $fila)->applyFromArray($this->headerTabla2);

            $sheet->setCellValue($colStart . $fila, 'SALDOS FINALES');
            $sheet->getStyle($colStart . $fila . ':' . $colEnd . $fila + 1)->getAlignment()->setHorizontal('center');
            $sheet->mergeCells($colStart . $fila . ":" . $colEnd . $fila);  //COMBINAR CELDAS
            $fila++;
            $sheet->getStyle('A' . $fila . ':' . $colEnd . $fila)->applyFromArray($this->headerTabla2);
            $fila++;
            $cont = 1;
            foreach ($productos as $key => $producto) {

                $total1 = 0;
                $total2 = 0;
                $total3 = 0;
                $total4 = 0;
                $total5 = 0;
                $colIndex = 4;

                foreach ($sucursals as $i =>  $sucursal) {
                    $colorIndex = $i % count($this->bgStyles);

                    $sucursal_id = $sucursal->id;
                    // INGRESOS ADICIONALES
                    $ingresos_adicionales = KardexProducto::where('producto_id', $producto->id)
                        ->where('fecha', $fecha)
                        ->where('tipo_is', 'INGRESO')
                        ->where('sucursal_id', $sucursal_id)
                        ->sum('cantidad_ingreso');

                    // SALDO INICIAL
                    $kardex_inicial = KardexProducto::where('producto_id', $producto->id)
                        ->where('fecha', $fecha)
                        ->where('sucursal_id', $sucursal_id)
                        ->get()
                        ->first();
                    if ($kardex_inicial) {
                        if ($kardex_inicial->tipo_is == 'EGRESO') {
                            $kardex_inicial = KardexProducto::where('producto_id', $producto->id)
                                ->where('id', '<', $kardex_inicial->id)
                                ->where('tipo_is', 'INGRESO')
                                ->where('sucursal_id', $sucursal_id)
                                ->get()
                                ->last();
                        }
                        $saldo_inicial = $kardex_inicial->cantidad_saldo;
                    } else {
                        $saldo_inicial = 0;
                    }

                    // ENTREGADOS
                    // ventas realizadas
                    $ventas_realizadas = OrdenVentaDetalle::where('producto_id', $producto->id);
                    $ventas_realizadas->whereHas('orden_venta', function ($query) use ($fecha, $sucursal_id) {
                        $query->where('fecha', $fecha)->where('sucursal_id', $sucursal_id);
                        $query->where('verificado', 2);
                    });
                    $ventas_realizadas = $ventas_realizadas->sum('cantidad');

                    // ventas realizadas
                    $transferencias = TransferenciaDetalle::where('producto_id', $producto->id);
                    $transferencias->whereHas('transferencia', function ($query) use ($fecha, $sucursal_id) {
                        $query->where('fecha', $fecha)->where('sucursal_id', $sucursal_id);
                    });
                    $transferencias = $transferencias->sum('cantidad_fisica');
                    $total_entregados = (float) $ventas_realizadas + (float) $transferencias;

                    // DEVOLUCIONES
                    $devoluciones = DevolucionClienteDetalle::where('producto_id', $producto->id);
                    $devoluciones->whereHas('devolucion_cliente', function ($query) use ($fecha, $sucursal_id) {
                        $query->where('fecha', $fecha)->where('sucursal_id', $sucursal_id);
                    });
                    $devoluciones = $devoluciones->sum('cantidad');

                    // FALTANTES
                    $faltantes = TransferenciaDetalle::where('producto_id', $producto->id);
                    $faltantes->whereHas('transferencia', function ($query) use ($fecha, $sucursal_id) {
                        $query->where('fecha', $fecha)->where('sucursal_id', $sucursal_id);
                    });
                    $faltantes = $faltantes->sum(DB::raw('cantidad - cantidad_fisica'));

                    $faltantes = 0;
                    // SALDO FINAL
                    $kardex_final = KardexProducto::where('producto_id', $producto->id)
                        ->where('fecha', $fecha)
                        ->where('sucursal_id', $sucursal_id)
                        ->where('tipo_registro', '!=', 'DEVOLUCIÓN DE STOCK')
                        ->where('id', '>', $kardex_inicial ? $kardex_inicial->id : 0)
                        ->get()
                        ->last();
                    $saldo_final = $kardex_final ? $kardex_final->cantidad_saldo : 0;


                    $sheet->setCellValue('A' . $fila, ($key + 1));
                    $sheet->setCellValue('B' . $fila, $producto->nombre);
                    $sheet->setCellValue('C' . $fila, $producto->unidad_medida->nombre);
                    $sheet->setCellValue(
                        Coordinate::stringFromColumnIndex($colIndex) . $fila,
                        $ingresos_adicionales
                    );
                    $cell = Coordinate::stringFromColumnIndex($colIndex) . $fila;
                    $sheet->getStyle($cell)
                        ->applyFromArray($this->bgStyles[$colorIndex]);

                    $sheet->setCellValue(
                        Coordinate::stringFromColumnIndex($colIndex + 1) . $fila,
                        $total_entregados
                    );

                    $cell = Coordinate::stringFromColumnIndex($colIndex + 1) . $fila;
                    $sheet->getStyle($cell)
                        ->applyFromArray($this->bgStyles[$colorIndex]);

                    $sheet->setCellValue(
                        Coordinate::stringFromColumnIndex($colIndex + 2) . $fila,
                        $devoluciones
                    );
                    $cell = Coordinate::stringFromColumnIndex($colIndex + 2) . $fila;
                    $sheet->getStyle($cell)
                        ->applyFromArray($this->bgStyles[$colorIndex]);

                    $sheet->setCellValue(
                        Coordinate::stringFromColumnIndex($colIndex + 3) . $fila,
                        $faltantes
                    );
                    $cell = Coordinate::stringFromColumnIndex($colIndex + 3) . $fila;
                    $sheet->getStyle($cell)
                        ->applyFromArray($this->bgStyles[$colorIndex]);

                    $sheet->setCellValue(
                        Coordinate::stringFromColumnIndex($colIndex + 4) . $fila,
                        $saldo_final
                    );
                    $cell = Coordinate::stringFromColumnIndex($colIndex + 4) . $fila;
                    $sheet->getStyle($cell)
                        ->applyFromArray($this->bgStyles[$colorIndex]);

                    $total1 += (float) $ingresos_adicionales;
                    $total2 += (float) $total_entregados;
                    $total3 += (float) $devoluciones;
                    $total4 += (float) $faltantes;
                    $total5 += (float) $saldo_final;
                    $colIndex += 5;
                }

                $sheet->setCellValue(
                    Coordinate::stringFromColumnIndex($colIndex) . $fila,
                    $total1
                );

                $sheet->setCellValue(
                    Coordinate::stringFromColumnIndex($colIndex + 1) . $fila,
                    $total2
                );

                $sheet->setCellValue(
                    Coordinate::stringFromColumnIndex($colIndex + 2) . $fila,
                    $total3
                );

                $sheet->setCellValue(
                    Coordinate::stringFromColumnIndex($colIndex + 3) . $fila,
                    $total4
                );

                $sheet->setCellValue(
                    Coordinate::stringFromColumnIndex($colIndex + 4) . $fila,
                    $total5
                );

                $sheet->getStyle('A' . $fila . ':' . $colEnd . $fila)->applyFromArray($this->bodyTabla);
                $fila++;
            }


            $sheet->getColumnDimension('A')->setWidth(6);
            $sheet->getColumnDimension('B')->setWidth(25);
            $sheet->getColumnDimension('C')->setWidth(15);
            $sheet->getColumnDimension('D')->setWidth(15);
            $sheet->getColumnDimension('E')->setWidth(15);
            $sheet->getColumnDimension('F')->setWidth(15);
            $sheet->getColumnDimension('G')->setWidth(15);

            foreach (range('A', $colEnd) as $columnID) {
                $sheet->getStyle($columnID)->getAlignment()->setWrapText(true);
            }

            $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
            $sheet->getPageMargins()->setTop(0.5);
            $sheet->getPageMargins()->setRight(0.1);
            $sheet->getPageMargins()->setLeft(0.1);
            $sheet->getPageMargins()->setBottom(0.1);
            $sheet->getPageSetup()->setPrintArea('A:' . $colEnd);
            $sheet->getPageSetup()->setFitToWidth(1);
            $sheet->getPageSetup()->setFitToHeight(0);

            return response()->streamDownload(
                function () use ($spreadsheet) {
                    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
                    $writer->save('php://output');
                },
                'diario_vehiculos_' . time() . '.xlsx',
                [
                    'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                ]
            );
        }
    }
}
