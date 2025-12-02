<?php

namespace App\Http\Controllers;

use App\Models\DescargaDocumento;
use App\Models\HistorialAccion;
use App\Models\Inscripcion;
use App\Models\Parametrizacion;
use App\Services\HistorialAccionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class InicioController extends Controller
{

    public function verificaLogin()
    {
        $sw = false;
        if (Auth::check()) {
            $sw = true;
        }

        return response()->JSON(["sw" => $sw]);
    }

    public function inicio()
    {
        $array_infos = UserController::getInfoBoxUser();
        return response()->JSON([
            "array_infos" => $array_infos,
        ]);
    }

    public function evaluaciones()
    {
        $evaluacionMedica = Auth::user()->inscripcion->evaluacion_medica ?? null;
        $evaluacionFisica = Auth::user()->inscripcion->evaluacion_fisica ?? null;
        $evaluacionInstruccion = Auth::user()->inscripcion->evaluacion_instruccion ?? null;
        $evaluacionConocimiento = Auth::user()->inscripcion->evaluacion_conocimiento ?? null;

        return Inertia::render("Admin/Postulante/Evaluaciones", compact(
            "evaluacionMedica",
            "evaluacionFisica",
            "evaluacionInstruccion",
            "evaluacionConocimiento",
        ));
    }
    public function vestibulares()
    {
        $lenguaCastellana = Auth::user()->inscripcion->lengua_castellana ?? null;
        $matematicaFisica = Auth::user()->inscripcion->matematica_fisica ?? null;
        $cienciaSocial = Auth::user()->inscripcion->ciencia_social ?? null;
        $historialPolicial = Auth::user()->inscripcion->historial_policial ?? null;
        $instruccionPolicial = Auth::user()->inscripcion->instruccion_policial ?? null;
        $acondicionamientoFisico = Auth::user()->inscripcion->acondicionamiento_fisico ?? null;

        return Inertia::render("Admin/Postulante/Vestibulares", compact([
            "lenguaCastellana",
            "matematicaFisica",
            "cienciaSocial",
            "historialPolicial",
            "instruccionPolicial",
            "acondicionamientoFisico",
        ]));
    }

    public function evaluacionMedica()
    {
        return Inertia::render("Admin/Postulante/Evaluacion");
    }

    public function evaluacionMedicaUbicacions()
    {
        return Inertia::render("Admin/Postulante/EvaluacionMedicaUbicacions");
    }

    public function asignarUsuarioPreinscripcion()
    {
        $hsitorialAccionService = new HistorialAccionService();

        // HISTORIAL ACCIÓN
        $historial_accions = HistorialAccion::where("modulo", "PREINSCRIPCIÓN")->get();
        foreach ($historial_accions as $item) {
            // inscripcion
            $inscripcion = Inscripcion::where("postulante_id", $item->datos_original["id"])->get();
            $total_ins = count($inscripcion);
            if ($total_ins > 1) {
                // dos inscripciones
                return "Dos inscripciones revisar";
            } else {
                // una inscripcion
                $insc = $inscripcion[0];
                $hsitorialAccionService->modificarAccion($item->id, $insc, null, ["postulante"]);
            }
        }
        return "Completado";
    }

    public function generarVideosHLS()
    {
        set_time_limit(-1);
        // Lista de videos para procesar
        $videos = [
            // 'portada' => public_path('videos/portada/CLIP PRINCIPAL SAD UNIPOL.mp4'),
            'anapol' => public_path('videos/anapol/CLIP ANAPOL.mp4'),
            // 'fatescipol' => public_path('videos/fatescipol/CLIP FATESCIPOL.mp4'),
            'esbapolmus' => public_path('videos/esbapolmus/CLIP ESBAPOLMUS.mp4'),
        ];
        $ffmpegPath = 'D:\\Programas\\ffmpeg-8.0-full_build\\bin\\ffmpeg.exe';
        foreach ($videos as $nombre => $input) {
            if (!file_exists($input)) {
                echo "No existe el archivo de entrada: $input\n";
                continue;
            }

            $outputFile = public_path("videos/{$nombre}/principal.m3u8");
            $outputDir = dirname($outputFile);

            // Crear carpeta si no existe
            if (!is_dir($outputDir)) {
                mkdir($outputDir, 0777, true);
            }

            // Ejecutar conversión
            // CON AUDIO
            // $cmd = '"' . $ffmpegPath . '" -i "' . $input . '" -c:v libx264 -profile:v baseline -level 3.0 -start_number 0 -hls_time 2 -hls_list_size 0 -f hls "' . $outputFile . '"';
            // SIN AUDIO ( -an quita el audio)
            $cmd = '"' . $ffmpegPath . '" -i "' . $input . '" -c:v libx264 -an -profile:v baseline -level 3.0 -start_number 0 -hls_time 2 -hls_list_size 0 -f hls "' . $outputFile . '"';


            exec($cmd . ' 2>&1', $outputLines, $returnVar);

            if ($returnVar !== 0) {
                echo "Error procesando {$nombre}:\n" . implode("\n", $outputLines) . "\n";
            } else {
                echo "Video HLS generado correctamente en: $outputFile\n";
            }
        }
    }

    public function login()
    {
        return Inertia::render("Auth/Login");
    }
}
