<?php

namespace App\Http\Controllers;

use App\Models\Configuracion;
use App\Models\HistorialAccion;
use App\Models\Inscripcion;
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

    public function usuarios()
    {
        return Inertia::render("Admin/Reportes/Usuarios");
    }

    public function r_usuarios(Request $request)
    {
        ini_set('memory_limit', '1024M');
        set_time_limit(-1);
        $tipo =  $request->tipo;
        $usuarios = User::select("users.*")
            ->where('id', '!=', 1)
            ->where('tipo', '!=', "POSTULANTE");

        if ($tipo != 'todos') {
            $request->validate([
                'tipo' => 'required',
            ]);
            $usuarios->where('role_id', $tipo);
        }

        $usuarios = $usuarios->orderBy("paterno", "ASC")->get();

        $pdf = PDF::loadView('reportes.usuarios', compact('usuarios'))->setPaper('legal', 'landscape');

        // ENUMERAR LAS PÁGINAS USANDO CANVAS
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $alto = $canvas->get_height();
        $ancho = $canvas->get_width();
        $canvas->page_text($ancho - 90, $alto - 25, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 9, array(0, 0, 0));

        return $pdf->stream('usuarios.pdf');
    }

    public function postulantes()
    {
        return Inertia::render("Admin/Reportes/Postulantes");
    }

    public function r_postulantes(Request $request)
    {
        ini_set('memory_limit', '1024M');
        set_time_limit(-1);
        $tipoR = $request->tipoR;
        $unidad =  $request->unidad;
        $fecha_ini =  $request->fecha_ini;
        $fecha_fin =  $request->fecha_fin;
        $inscripcions = Inscripcion::select("inscripcions.*")
            ->join("postulantes", "postulantes.id", "=", "inscripcions.postulante_id");

        if ($unidad != 'todos') {
            $request->validate([
                'unidad' => 'required',
            ]);
            $inscripcions->where('unidad', $unidad);
        }

        if ($fecha_ini && $fecha_fin) {
            $inscripcions->whereBetween('inscripcions.fecha_registro', [$fecha_ini, $fecha_fin]);
        }

        $inscripcions = $inscripcions->orderBy("postulantes.paterno", "ASC")
            ->where("estado", "PREINSCRITO")->get();

        if ($tipoR == 'pdf') {
            $pdf = new ReporteService("LISTA DE PREINSCRITOS", true);
            $pdf->AliasNbPages(); // Necesario para {nb}
            $pdf->SetMargins(3, 2);
            $pdf->AddPage("L");
            $pdf->SetFont('Arial', '', 10);

            $ancho = 40;
            $font_size = 6;
            // Encabezado de tabla
            $pdf->SetFont('Arial', 'B', $font_size);
            $pdf->CellUtf8(7, 7, 'N°', 1);
            $pdf->CellUtf8(16, 7, 'UNIDAD', 1);
            $pdf->CellUtf8(35, 7, 'NOMBRE POSTULANTE', 1);
            $pdf->CellUtf8(16, 7, 'C.I.', 1);
            $pdf->CellUtf8(16, 7, 'FECHA NAC.', 1);
            $pdf->CellUtf8(17, 7, 'CELULAR', 1);
            $pdf->CellUtf8($ancho, 7, 'CORREO', 1);
            $pdf->CellUtf8(16, 7, 'NRO. CUENTA', 1);
            $pdf->CellUtf8(37, 7, 'LUGAR PREINSCRIPCIÓN', 1);
            $pdf->CellUtf8(17, 7, 'SEDE', 1);
            $pdf->CellUtf8($ancho, 7, 'OBSERVACIÓN', 1);
            $pdf->CellUtf8(16, 7, 'ACCESO', 1);
            $pdf->CellUtf8(17, 7, 'FECHA REG.', 1);
            $pdf->Ln();
            $cont = 1;
            foreach ($inscripcions as $registro) {
                $font_size = 6;
                $pdf->SetFont('Arial', '', $font_size);
                $pdf->CellUtf8(7, 6, $cont++, 1);
                $pdf->CellUtf8(16, 6, $registro->unidad, 1);

                $txt = $registro->postulante->full_name;
                while ($pdf->GetStringWidth($txt) > 35) {
                    $font_size--; // reducir tamaño
                    $pdf->SetFont('Arial', '', $font_size);
                }
                $pdf->CellUtf8(35, 6, $registro->postulante->full_name, 1);
                $font_size = 6;
                $pdf->SetFont('Arial', '', $font_size);
                $pdf->CellUtf8(16, 6, $registro->postulante->full_ci, 1);
                $pdf->CellUtf8(16, 6, $registro->postulante->fecha_nac_t, 1);
                $pdf->CellUtf8(17, 6, $registro->postulante->cel, 1);
                $txt = $registro->correo;
                while ($pdf->GetStringWidth($txt) > $ancho) {
                    $font_size--; // reducir tamaño
                    $pdf->SetFont('Arial', '', $font_size);
                }
                $pdf->CellUtf8($ancho, 6, $registro->correo, 1);
                $font_size = 6;
                $pdf->SetFont('Arial', '', $font_size);
                $pdf->CellUtf8(16, 6, $registro->postulante->nro_cuenta, 1);
                $txt = $registro->lugar_preins;
                while ($pdf->GetStringWidth($txt) > 37) {
                    $font_size--; // reducir tamaño
                    $pdf->SetFont('Arial', '', $font_size);
                }
                $pdf->CellUtf8(37, 6, $registro->lugar_preins, 1);
                $pdf->CellUtf8(17, 6, $registro->sede, 1);
                $txt = $registro->observacion;
                while ($pdf->GetStringWidth($txt) > $ancho) {
                    $font_size--; // reducir tamaño
                    $pdf->SetFont('Arial', '', $font_size);
                }
                $pdf->CellUtf8($ancho, 6, $registro->observacion, 1);
                $font_size = 6;
                $pdf->SetFont('Arial', '', $font_size);
                $pdf->CellUtf8(16, 6, $registro->user->acceso == 1 ? 'HABILITADO' : 'DENEGADO', 1);
                $pdf->CellUtf8(17, 6, $registro->fecha_registro_t, 1);
                $pdf->Ln();
                if ($pdf->GetY() > 270) {
                    $pdf->AddPage();
                }
            }

            // Guardar PDF o forzar descarga
            return response($pdf->Output('S'), 200)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'inline; filename="report.pdf"');
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
            $sheet->setCellValue('A' . $fila, "LISTA DE PREINSCRITOS");
            $sheet->mergeCells("A" . $fila . ":M" . $fila);  //COMBINAR CELDAS
            $sheet->getStyle('A' . $fila . ':M' . $fila)->getAlignment()->setHorizontal('center');
            $sheet->getStyle('A' . $fila . ':M' . $fila)->applyFromArray($this->titulo);
            $fila++;
            $fila++;
            $sheet->setCellValue('A' . $fila, 'N°');
            $sheet->setCellValue('B' . $fila, 'UNIDAD');
            $sheet->setCellValue('C' . $fila, 'NOMBRE POSTULANTE');
            $sheet->setCellValue('D' . $fila, 'C.I.');
            $sheet->setCellValue('E' . $fila, 'FECHA NAC.');
            $sheet->setCellValue('F' . $fila, 'CELULAR');
            $sheet->setCellValue('G' . $fila, 'CORREO');
            $sheet->setCellValue('H' . $fila, 'NRO. CUENTA');
            $sheet->setCellValue('I' . $fila, 'LUGAR PREINSCRIPCIÓN');
            $sheet->setCellValue('J' . $fila, 'SEDE');
            $sheet->setCellValue('K' . $fila, 'OBSERVACIÓN');
            $sheet->setCellValue('L' . $fila, 'ACCESO');
            $sheet->setCellValue('M' . $fila, 'FECHA DE REGISTRO');
            $sheet->getStyle('A' . $fila . ':M' . $fila)->applyFromArray($this->headerTabla);
            $fila++;

            foreach ($inscripcions as $key => $inscripcion) {
                $sheet->setCellValue('A' . $fila, $key + 1);
                $sheet->setCellValue('B' . $fila, $inscripcion->unidad);
                $sheet->setCellValue('C' . $fila, $inscripcion->postulante->full_name);
                $sheet->setCellValue('D' . $fila, $inscripcion->postulante->full_ci);
                $sheet->setCellValue('E' . $fila, $inscripcion->postulante->fecha_nac_t);
                $sheet->setCellValue('F' . $fila, $inscripcion->postulante->cel);
                $sheet->setCellValue('G' . $fila, $inscripcion->correo);
                $sheet->setCellValue('H' . $fila, $inscripcion->postulante->nro_cuenta);
                $sheet->setCellValue('I' . $fila, $inscripcion->lugar_preins);
                $sheet->setCellValue('J' . $fila, $inscripcion->sede);
                $sheet->setCellValue('K' . $fila, $inscripcion->observacion);
                $sheet->setCellValue('L' . $fila, $inscripcion->user->acceso == 1 ? 'HABILITADO' : 'DENEGADO');
                $sheet->setCellValue('M' . $fila, $inscripcion->fecha_registro_t);
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

            // DESCARGA DEL ARCHIVO
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="preinscritos' . time() . '.xlsx"');
            header('Cache-Control: max-age=0');
            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save('php://output');
        }
    }

    public function inscritos()
    {
        return Inertia::render("Admin/Reportes/Inscritos");
    }

    public function r_inscritos(Request $request)
    {
        ini_set('memory_limit', '1024M');
        set_time_limit(-1);
        $tipoR = $request->tipoR;
        $unidad =  $request->unidad;
        $fecha_ini =  $request->fecha_ini;
        $fecha_fin =  $request->fecha_fin;
        $inscripcions = Inscripcion::select(
            "inscripcions.*",
            DB::raw("DATE_FORMAT(requisitos.created_at, '%d/%m/%Y %H:%i:%s') as requisitos_created_at")
        )
            ->join("postulantes", "postulantes.id", "=", "inscripcions.postulante_id")
            ->join("requisitos", "requisitos.inscripcion_id", "=", "inscripcions.id");

        if ($unidad != 'todos') {
            $request->validate([
                'unidad' => 'required',
            ]);
            $inscripcions->where('unidad', $unidad);
        }

        if ($fecha_ini && $fecha_fin) {
            $inscripcions->whereBetween('inscripcions.fecha_registro', [$fecha_ini, $fecha_fin]);
        }

        $inscripcions = $inscripcions->orderBy("postulantes.paterno", "ASC")
            ->where("estado", "INSCRITO")->get();

        if ($tipoR == 'pdf') {
            $pdf = new ReporteService("LISTA DE INSCRITOS", true);
            $pdf->AliasNbPages(); // Necesario para {nb}
            $pdf->SetMargins(1, 1);
            $pdf->AddPage("L");
            $pdf->SetFont('Arial', '', 10);

            $ancho = 36;
            $font_size = 6;
            // Encabezado de tabla
            $pdf->SetFont('Arial', 'B', $font_size);
            $pdf->CellUtf8(7, 7, 'N°', 1);
            $pdf->CellUtf8(16, 7, 'UNIDAD', 1);
            $pdf->CellUtf8(35, 7, 'NOMBRE POSTULANTE', 1);
            $pdf->CellUtf8(16, 7, 'C.I.', 1);
            $pdf->CellUtf8(16, 7, 'FECHA NAC.', 1);
            $pdf->CellUtf8(17, 7, 'CELULAR', 1);
            $pdf->CellUtf8($ancho, 7, 'CORREO', 1);
            $pdf->CellUtf8(16, 7, 'NRO. CUENTA', 1);
            $pdf->CellUtf8(35, 7, 'LUGAR PREINSCRIPCIÓN', 1);
            $pdf->CellUtf8(17, 7, 'SEDE', 1);
            $pdf->CellUtf8(28, 7, 'OBSERVACIÓN', 1);
            $pdf->CellUtf8(15, 7, 'ACCESO', 1);
            $pdf->CellUtf8(25, 7, 'FECHA INSC.', 1);
            $pdf->CellUtf8(16, 7, 'FECHA REG.', 1);
            $pdf->Ln();
            $cont = 1;
            foreach ($inscripcions as $registro) {
                $font_size = 6;
                $pdf->SetFont('Arial', '', $font_size);
                $pdf->CellUtf8(7, 6, $cont++, 1);
                $pdf->CellUtf8(16, 6, $registro->unidad, 1);

                $txt = $registro->postulante->full_name;
                while ($pdf->GetStringWidth($txt) > 34) {
                    $font_size--; // reducir tamaño
                    $pdf->SetFont('Arial', '', $font_size);
                }
                $pdf->CellUtf8(35, 6, $registro->postulante->full_name, 1);
                $font_size = 6;
                $pdf->SetFont('Arial', '', $font_size);
                $pdf->CellUtf8(16, 6, $registro->postulante->full_ci, 1);
                $pdf->CellUtf8(16, 6, $registro->postulante->fecha_nac_t, 1);
                $pdf->CellUtf8(17, 6, $registro->postulante->cel, 1);
                $txt = $registro->correo;
                while ($pdf->GetStringWidth($txt) > $ancho) {
                    $font_size--; // reducir tamaño
                    $pdf->SetFont('Arial', '', $font_size);
                }
                $pdf->CellUtf8($ancho, 6, $registro->correo, 1);
                $font_size = 6;
                $pdf->SetFont('Arial', '', $font_size);
                $pdf->CellUtf8(16, 6, $registro->postulante->nro_cuenta, 1);
                $txt = $registro->lugar_preins;
                while ($pdf->GetStringWidth($txt) > 35) {
                    $font_size--; // reducir tamaño
                    $pdf->SetFont('Arial', '', $font_size);
                }
                $pdf->CellUtf8(35, 6, $registro->lugar_preins, 1);
                $pdf->CellUtf8(17, 6, $registro->sede, 1);
                $txt = $registro->observacion;
                while ($pdf->GetStringWidth($txt) > 28) {
                    $font_size--; // reducir tamaño
                    $pdf->SetFont('Arial', '', $font_size);
                }
                $pdf->CellUtf8(28, 6, $registro->observacion, 1);
                $font_size = 6;
                $pdf->SetFont('Arial', '', $font_size);
                $pdf->CellUtf8(15, 6, $registro->user->acceso == 1 ? 'HABILITADO' : 'DENEGADO', 1);
                $pdf->CellUtf8(25, 6, $registro->requisitos_created_at, 1);
                $pdf->CellUtf8(16, 6, $registro->fecha_registro_t, 1);
                $pdf->Ln();
                if ($pdf->GetY() > 270) {
                    $pdf->AddPage();
                }
            }

            // Guardar PDF o forzar descarga
            return response($pdf->Output('S'), 200)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'inline; filename="report.pdf"');
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
            $sheet->mergeCells("A" . $fila . ":N" . $fila);  //COMBINAR CELDAS
            $sheet->getStyle('A' . $fila . ':N' . $fila)->getAlignment()->setHorizontal('center');
            $sheet->getStyle('A' . $fila . ':N' . $fila)->applyFromArray($this->titulo);
            $fila++;
            $sheet->setCellValue('A' . $fila, "LISTA DE INSCRITOS");
            $sheet->mergeCells("A" . $fila . ":N" . $fila);  //COMBINAR CELDAS
            $sheet->getStyle('A' . $fila . ':N' . $fila)->getAlignment()->setHorizontal('center');
            $sheet->getStyle('A' . $fila . ':N' . $fila)->applyFromArray($this->titulo);
            $fila++;
            $fila++;
            $sheet->setCellValue('A' . $fila, 'N°');
            $sheet->setCellValue('B' . $fila, 'UNIDAD');
            $sheet->setCellValue('C' . $fila, 'NOMBRE POSTULANTE');
            $sheet->setCellValue('D' . $fila, 'C.I.');
            $sheet->setCellValue('E' . $fila, 'FECHA NAC.');
            $sheet->setCellValue('F' . $fila, 'CELULAR');
            $sheet->setCellValue('G' . $fila, 'CORREO');
            $sheet->setCellValue('H' . $fila, 'NRO. CUENTA');
            $sheet->setCellValue('I' . $fila, 'LUGAR PREINSCRIPCIÓN');
            $sheet->setCellValue('J' . $fila, 'SEDE');
            $sheet->setCellValue('K' . $fila, 'OBSERVACIÓN');
            $sheet->setCellValue('L' . $fila, 'ACCESO');
            $sheet->setCellValue('M' . $fila, 'FECHA DE INSCRIPCIÓN');
            $sheet->setCellValue('N' . $fila, 'FECHA DE REGISTRO');
            $sheet->getStyle('A' . $fila . ':N' . $fila)->applyFromArray($this->headerTabla);
            $fila++;

            foreach ($inscripcions as $key => $inscripcion) {
                $sheet->setCellValue('A' . $fila, $key + 1);
                $sheet->setCellValue('B' . $fila, $inscripcion->unidad);
                $sheet->setCellValue('C' . $fila, $inscripcion->postulante->full_name);
                $sheet->setCellValue('D' . $fila, $inscripcion->postulante->full_ci);
                $sheet->setCellValue('E' . $fila, $inscripcion->postulante->fecha_nac_t);
                $sheet->setCellValue('F' . $fila, $inscripcion->postulante->cel);
                $sheet->setCellValue('G' . $fila, $inscripcion->correo);
                $sheet->setCellValue('H' . $fila, $inscripcion->postulante->nro_cuenta);
                $sheet->setCellValue('I' . $fila, $inscripcion->lugar_preins);
                $sheet->setCellValue('J' . $fila, $inscripcion->sede);
                $sheet->setCellValue('K' . $fila, $inscripcion->observacion);
                $sheet->setCellValue('L' . $fila, $inscripcion->user->acceso == 1 ? 'HABILITADO' : 'DENEGADO');
                $sheet->setCellValue('M' . $fila, $inscripcion->requisitos_created_at);
                $sheet->setCellValue('N' . $fila, $inscripcion->fecha_registro_t);
                $sheet->getStyle('A' . $fila . ':N' . $fila)->applyFromArray($this->bodyTabla);
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
            $sheet->getColumnDimension('J')->setWidth(12);
            $sheet->getColumnDimension('K')->setWidth(12);
            $sheet->getColumnDimension('L')->setWidth(12);
            $sheet->getColumnDimension('M')->setWidth(12);
            $sheet->getColumnDimension('N')->setWidth(12);

            foreach (range('A', 'N') as $columnID) {
                $sheet->getStyle($columnID)->getAlignment()->setWrapText(true);
            }

            $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
            $sheet->getPageMargins()->setTop(0.5);
            $sheet->getPageMargins()->setRight(0.1);
            $sheet->getPageMargins()->setLeft(0.1);
            $sheet->getPageMargins()->setBottom(0.1);
            $sheet->getPageSetup()->setPrintArea('A:N');
            $sheet->getPageSetup()->setFitToWidth(1);
            $sheet->getPageSetup()->setFitToHeight(0);

            // DESCARGA DEL ARCHIVO
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="inscritos' . time() . '.xlsx"');
            header('Cache-Control: max-age=0');
            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save('php://output');
        }
    }

    public function ginscripcions()
    {
        return Inertia::render("Admin/Reportes/GInscripcions");
    }
    public function r_ginscripcions(Request $request)
    {
        $unidad = $request->unidad;
        $tipoR = $request->tipoR;

        $fecha_ini = $request->fecha_ini;
        $fecha_fin = $request->fecha_fin;

        $unidads = ["ANAPOL", "FATESCIPOL", "ESBAPOLMUS"];
        if ($unidad != 'todos') {
            $unidads = [$unidad];
        }

        $colores = [
            "ANAPOL" => "#28a745",   // verde
            "FATESCIPOL" => "#ffc107",  // amarillo
            "ESBAPOLMUS" => "#fd7e14",   // naranja
        ];

        $data = [];
        foreach ($unidads as $key => $unidad) {
            $t_unidad = Inscripcion::where("unidad", $unidad)->where("status", 1);
            if ($fecha_ini && $fecha_fin) {
                $t_unidad->whereBetween("fecha_registro", [$fecha_ini, $fecha_fin]);
            }
            if ($tipoR != 'todos') {
                $t_unidad->where("estado", $tipoR);
            }

            $t_unidad = $t_unidad->count();

            $data[] = [
                'name' => $unidad,
                'y' => (float) $t_unidad,
                'color' => $colores[$unidad] ?? '#000000'
            ];
        }

        return response()->JSON([
            "categories" => $unidads,
            "data" => $data,
        ]);
    }

    public function historial_accions()
    {
        return Inertia::render("Admin/Reportes/HistorialAccions");
    }

    public function r_historial_accions(Request $request)
    {
        ini_set('memory_limit', '1024M');
        set_time_limit(-1);
        $user_id =  $request->user_id;
        $historial_accions = HistorialAccion::select("historial_accions.*")
            ->join("users", "users.id", "=", "historial_accions.user_id")
            ->where('user_id', '!=', 1);

        if ($user_id != 'todos') {
            $request->validate([
                'user_id' => 'required',
            ]);
            $historial_accions->where('user_id', $user_id);
        }

        $historial_accions = $historial_accions->get();

        $pdf = new ReporteService("HISTORIAL DE ACCIONES", true);
        $pdf->AliasNbPages(); // Necesario para {nb}
        $pdf->SetMargins(7, 5);
        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 10);

        $ancho = 20;
        $font_size = 6;
        // Encabezado de tabla
        $pdf->SetFont('Arial', 'B', $font_size);
        $pdf->CellUtf8(14, 7, 'USUARIO', 1);
        $pdf->CellUtf8(20, 7, 'ACCIÓN', 1);
        $pdf->CellUtf8(60, 7, 'DESCRIPCIÓN', 1);
        $pdf->CellUtf8($ancho, 7, 'MÓDULO', 1);
        $pdf->CellUtf8(30, 7, 'ORIGINAL', 1);
        $pdf->CellUtf8(30, 7, 'NUEVO', 1);
        $pdf->CellUtf8(22, 7, 'FECHA REG.', 1);
        $pdf->Ln();
        $cont = 1;
        $maxLength = 20; // caracteres a mostrar
        $font_size = 6;
        $pdf->SetFont('Arial', '', $font_size);
        foreach ($historial_accions as $registro) {
            $pdf->SetFont('Arial', '', $font_size);
            $pdf->CellUtf8(14, 6, $registro->user->usuario, 1);
            $pdf->CellUtf8(20, 6, $registro->accion, 1);

            $txt = $registro->descripcion;
            while ($pdf->GetStringWidth($txt) > 60) {
                $font_size--; // reducir tamaño
                $pdf->SetFont('Arial', '', $font_size);
            }
            $pdf->CellUtf8(60, 6, $registro->descripcion, 1);
            $font_size = 6;
            $pdf->SetFont('Arial', '', $font_size);
            $pdf->CellUtf8($ancho, 6, $registro->modulo, 1);
            $original = json_encode($registro->datos_original);
            $nuevo = json_encode($registro->datos_nuevo);
            $original = strlen($original) > $maxLength ? substr($original, 0, $maxLength) . '...' : $original;
            if (mb_strtolower($nuevo) != 'null') {
                $nuevo = strlen($nuevo) > $maxLength ? substr($nuevo, 0, $maxLength) . '...' : $nuevo;
            } else {
                $nuevo = "";
            }
            $pdf->CellUtf8(30, 6, $original, 1);
            $pdf->CellUtf8(30, 6, $nuevo, 1);
            $pdf->CellUtf8(22, 6, $registro->fecha_hora_t, 1);
            $pdf->Ln();
            if ($pdf->GetY() > 270) {
                $pdf->AddPage();
            }
        }

        // Guardar PDF o forzar descarga
        return response($pdf->Output('S'), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="report.pdf"');

        $pdf = PDF::loadView('reportes.historial_accions', compact('historial_accions'))->setPaper('letter', 'landscape');

        // ENUMERAR LAS PÁGINAS USANDO CANVAS
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $alto = $canvas->get_height();
        $ancho = $canvas->get_width();
        $canvas->page_text($ancho - 90, $alto - 25, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 9, array(0, 0, 0));

        return $pdf->stream('historial_accions.pdf');
    }
}
