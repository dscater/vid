<?php

namespace Database\Seeders;

use App\Models\Modulo;
use Illuminate\Database\Seeder;

class ModuloTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // GESTIÓN DE USUARIOS
        Modulo::create([
            "modulo" => "Gestión de usuarios",
            "nombre" => "usuarios.index",
            "accion" => "VER",
            "descripcion" => "VER LA LISTA DE USUARIOS"
        ]);

        Modulo::create([
            "modulo" => "Gestión de usuarios",
            "nombre" => "usuarios.create",
            "accion" => "CREAR",
            "descripcion" => "CREAR USUARIOS"
        ]);

        Modulo::create([
            "modulo" => "Gestión de usuarios",
            "nombre" => "usuarios.edit",
            "accion" => "EDITAR",
            "descripcion" => "EDITAR USUARIOS"
        ]);

        Modulo::create([
            "modulo" => "Gestión de usuarios",
            "nombre" => "usuarios.destroy",
            "accion" => "ELIMINAR",
            "descripcion" => "ELIMINAR USUARIOS"
        ]);

        // ROLES Y PERMISOS
        Modulo::create([
            "modulo" => "Roles y Permisos",
            "nombre" => "roles.index",
            "accion" => "VER",
            "descripcion" => "VER LA LISTA DE ROLES Y PERMISOS"
        ]);

        Modulo::create([
            "modulo" => "Roles y Permisos",
            "nombre" => "roles.create",
            "accion" => "CREAR",
            "descripcion" => "CREAR ROLES Y PERMISOS"
        ]);

        Modulo::create([
            "modulo" => "Roles y Permisos",
            "nombre" => "roles.edit",
            "accion" => "EDITAR",
            "descripcion" => "EDITAR ROLES Y PERMISOS"
        ]);

        Modulo::create([
            "modulo" => "Roles y Permisos",
            "nombre" => "roles.destroy",
            "accion" => "ELIMINAR",
            "descripcion" => "ELIMINAR ROLES Y PERMISOS"
        ]);

        // CONFIGURACIÓN DEL SISTEMA
        Modulo::create([
            "modulo" => "Configuración",
            "nombre" => "configuracions.index",
            "accion" => "VER",
            "descripcion" => "VER INFORMACIÓN DE LA CONFIGURACIÓN DEL SISTEMA"
        ]);

        Modulo::create([
            "modulo" => "Configuración",
            "nombre" => "configuracions.edit",
            "accion" => "EDITAR",
            "descripcion" => "EDITAR LA CONFIGURACIÓN DEL SISTEMA"
        ]);

        // PREINSCRIPCIÓN
        Modulo::create([
            "modulo" => "Preinscripción",
            "nombre" => "postulantes.preinscripcion",
            "accion" => "CREAR",
            "descripcion" => "CREAR REGISTROS DE PREINSCRIPCIÓN"
        ]);

        Modulo::create([
            "modulo" => "Preinscripción",
            "nombre" => "postulantes.index",
            "accion" => "VER",
            "descripcion" => "VER LA LISTA DE POSTULANTES"
        ]);

        Modulo::create([
            "modulo" => "Preinscripción",
            "nombre" => "inscripcions.reenviarCorreo",
            "accion" => "REENVIAR CORREO",
            "descripcion" => "VER LA CONFIGURACIÓN DEL SISTEMA"
        ]);

        // EVALUACION MEDICA
        Modulo::create([
            "modulo" => "Evaluación Médica",
            "nombre" => "evaluacion_medicas.index",
            "accion" => "VER LAS EVALUACIONES MÉDICAS",
            "descripcion" => "VER LA LISTA DE EVALUACIONES MÉDICAS"
        ]);

        Modulo::create([
            "modulo" => "Evaluación Médica",
            "nombre" => "evaluacion_medicas.subir",
            "accion" => "SUBIR ARCHIVOS",
            "descripcion" => "SUBIR ARCHIVOS DE EVALUACIONES MÉDICAS"
        ]);

        // EVALUACION ÁREA FÍSICA
        Modulo::create([
            "modulo" => "Evaluación del área de aptitud Física",
            "nombre" => "evaluacion_fisicas.index",
            "accion" => "VER LAS EVALUACIONES DEL ÁREA DE APTITUD FÍSICA",
            "descripcion" => "VER LA LISTA DE EVALUACIONES DEL ÁREA DE APTITUD FÍSICA"
        ]);

        Modulo::create([
            "modulo" => "Evaluación del área de aptitud Física",
            "nombre" => "evaluacion_fisicas.subir",
            "accion" => "SUBIR ARCHIVOS",
            "descripcion" => "SUBIR ARCHIVOS DE EVALUACIONES DEL ÁREA DE APTITUD FÍSICA"
        ]);

        // EVALUACION DE INSTRUCCIÓN POLICIAL
        Modulo::create([
            "modulo" => "Evaluación del área de instrucción Policial",
            "nombre" => "evaluacion_instruccions.index",
            "accion" => "VER LAS EVALUACIONES DEL ÁREA DE INSTRUCCIÓN POLICIAL",
            "descripcion" => "VER LA LISTA DE EVALUACIONES DEL ÁREA DE INSTRUCCIÓN POLICIAL"
        ]);

        Modulo::create([
            "modulo" => "Evaluación del área de instrucción Policial",
            "nombre" => "evaluacion_instruccions.subir",
            "accion" => "SUBIR ARCHIVOS",
            "descripcion" => "SUBIR ARCHIVOS DE EVALUACIONES DEL ÁREA DE INSTRUCCIÓN POLICIAL"
        ]);

        // EVALUACION DE CONOCIMIENTO
        Modulo::create([
            "modulo" => "Evaluación del área de conocimientos",
            "nombre" => "evaluacion_conocimientos.index",
            "accion" => "VER LAS EVALUACIONES DEL ÁREA DE CONOCIMIENTOS",
            "descripcion" => "VER LA LISTA DE EVALUACIONES DEL ÁREA DE CONOCIMIENTOS"
        ]);

        Modulo::create([
            "modulo" => "Evaluación del área de conocimientos",
            "nombre" => "evaluacion_conocimientos.subir",
            "accion" => "SUBIR ARCHIVOS",
            "descripcion" => "SUBIR ARCHIVOS DE EVALUACIONES DEL ÁREA DE CONOCIMIENTOS"
        ]);

        // PREFACULTATIVO - LENGUA CASTELLANA
        Modulo::create([
            "modulo" => "Lengua Castellana",
            "nombre" => "lengua_castellanas.index",
            "accion" => "VER MATERIA LENGUA CASTELLANA",
            "descripcion" => "VER LA LISTA DE NOTAS DE LENGUA CASTELLANA"
        ]);

        Modulo::create([
            "modulo" => "Lengua Castellana",
            "nombre" => "lengua_castellanas.subir",
            "accion" => "SUBIR ARCHIVOS",
            "descripcion" => "SUBIR ARCHIVOS DE LENGUA CASTELLANA"
        ]);

        // PREFACULTATIVO - MATEMÁTICAS Y FÍSICA
        Modulo::create([
            "modulo" => "Matemáticas y Física",
            "nombre" => "matematica_fisicas.index",
            "accion" => "VER MATERIA MATEMÁTICAS Y FÍSICA",
            "descripcion" => "VER LA LISTA DE NOTAS DE MATEMÁTICAS Y FÍSICA"
        ]);

        Modulo::create([
            "modulo" => "Matemáticas y Física",
            "nombre" => "matematica_fisicas.subir",
            "accion" => "SUBIR ARCHIVOS",
            "descripcion" => "SUBIR ARCHIVOS DE MATEMÁTICAS Y FÍSICA"
        ]);

        // PREFACULTATIVO - CIENCIAS SOCIALES
        Modulo::create([
            "modulo" => "Ciencias Sociales",
            "nombre" => "ciencia_socials.index",
            "accion" => "VER MATERIA CIENCIAS SOCIALES",
            "descripcion" => "VER LA LISTA DE NOTAS DE CIENCIAS SOCIALES"
        ]);

        Modulo::create([
            "modulo" => "Ciencias Sociales",
            "nombre" => "ciencia_socials.subir",
            "accion" => "SUBIR ARCHIVOS",
            "descripcion" => "SUBIR ARCHIVOS DE CIENCIAS SOCIALES"
        ]);

        // PREFACULTATIVO - HISTORIA POLICIAL
        Modulo::create([
            "modulo" => "Historia Policial",
            "nombre" => "historial_policials.index",
            "accion" => "VER MATERIA HISTORIA POLICIAL",
            "descripcion" => "VER LA LISTA DE NOTAS DE HISTORIA POLICIAL"
        ]);

        Modulo::create([
            "modulo" => "Historia Policial",
            "nombre" => "historial_policials.subir",
            "accion" => "SUBIR ARCHIVOS",
            "descripcion" => "SUBIR ARCHIVOS DE HISTORIA POLICIAL"
        ]);

        // PREFACULTATIVO - INSTRUCCIÓN POLICIAL
        Modulo::create([
            "modulo" => "Instrucción Policial",
            "nombre" => "instruccion_policials.index",
            "accion" => "VER MATERIA INSTRUCCIÓN POLICIAL",
            "descripcion" => "VER LA LISTA DE NOTAS DE INSTRUCCIÓN POLICIAL"
        ]);

        Modulo::create([
            "modulo" => "Instrucción Policial",
            "nombre" => "instruccion_policials.subir",
            "accion" => "SUBIR ARCHIVOS",
            "descripcion" => "SUBIR ARCHIVOS DE INSTRUCCIÓN POLICIAL"
        ]);

        // PREFACULTATIVO - ACONDICIONAMIENTO FÍSICO
        Modulo::create([
            "modulo" => "Acondicionamiento Físico",
            "nombre" => "acondicionamiento_fisicos.index",
            "accion" => "VER MATERIA ACONDICIONAMIENTO FÍSICO",
            "descripcion" => "VER LA LISTA DE NOTAS DE ACONDICIONAMIENTO FÍSICO"
        ]);

        Modulo::create([
            "modulo" => "Acondicionamiento Físico",
            "nombre" => "acondicionamiento_fisicos.subir",
            "accion" => "SUBIR ARCHIVOS",
            "descripcion" => "SUBIR ARCHIVOS DE ACONDICIONAMIENTO FÍSICO"
        ]);

        // VERIFICAR REQUISITOS
        Modulo::create([
            "modulo" => "Verificar Requisitos",
            "nombre" => "requisitos.buscar",
            "accion" => "VERIFICAR REQUISITOS",
            "descripcion" => "VER Y VERIFICAR LOS REQUISITOS DE LOS POSTULANTES"
        ]);

        // COMUNICADOS
        Modulo::create([
            "modulo" => "Comunicados",
            "nombre" => "comunicados.index",
            "accion" => "VER",
            "descripcion" => "VER LA LISTA DE COMUNICADOS"
        ]);

        Modulo::create([
            "modulo" => "Comunicados",
            "nombre" => "comunicados.create",
            "accion" => "CREAR",
            "descripcion" => "CREAR COMUNICADOS"
        ]);

        Modulo::create([
            "modulo" => "Comunicados",
            "nombre" => "comunicados.edit",
            "accion" => "EDITAR",
            "descripcion" => "EDITAR COMUNICADOS"
        ]);

        Modulo::create([
            "modulo" => "Comunicados",
            "nombre" => "comunicados.destroy",
            "accion" => "ELIMINAR",
            "descripcion" => "ELIMINAR COMUNICADOS"
        ]);

        // DOCUMENTOS DE DESCARGA
        Modulo::create([
            "modulo" => "Documentos de Descarga",
            "nombre" => "descarga_documentos.index",
            "accion" => "VER",
            "descripcion" => "VER LA LISTA DE DOCUMENTOS DE DESCARGA"
        ]);

        Modulo::create([
            "modulo" => "Documentos de Descarga",
            "nombre" => "descarga_documentos.create",
            "accion" => "CREAR",
            "descripcion" => "CREAR DOCUMENTOS DE DESCARGA"
        ]);

        Modulo::create([
            "modulo" => "Documentos de Descarga",
            "nombre" => "descarga_documentos.edit",
            "accion" => "EDITAR",
            "descripcion" => "EDITAR DOCUMENTOS DE DESCARGA"
        ]);

        Modulo::create([
            "modulo" => "Documentos de Descarga",
            "nombre" => "descarga_documentos.destroy",
            "accion" => "ELIMINAR",
            "descripcion" => "ELIMINAR DOCUMENTOS DE DESCARGA"
        ]);

        // REPORTES
        Modulo::create([
            "modulo" => "Reportes",
            "nombre" => "reportes.usuarios",
            "accion" => "REPORTE LISTA DE USUARIOS",
            "descripcion" => "GENERAR REPORTES DE LISTA DE USUARIOS"
        ]);

        Modulo::create([
            "modulo" => "Reportes",
            "nombre" => "reportes.postulantes",
            "accion" => "REPORTE DE PREINSCRITOS",
            "descripcion" => "GENERAR LISTA DE PREINSCRITOS"
        ]);

        Modulo::create([
            "modulo" => "Reportes",
            "nombre" => "reportes.inscritos",
            "accion" => "REPORTE DE INSCRITOS",
            "descripcion" => "GENERAR LISTA DE INSCRITOS"
        ]);

        Modulo::create([
            "modulo" => "Reportes",
            "nombre" => "reportes.ginscripcions",
            "accion" => "REPORTE GRÁFICO",
            "descripcion" => "GENERAR REPORTE GRÁFICO DE PREINSCRITOS"
        ]);

        Modulo::create([
            "modulo" => "Reportes",
            "nombre" => "reportes.historial_accions",
            "accion" => "REPORTE HISTORIAL DE ACCIONES",
            "descripcion" => "GENERAR REPORTE HISTORIAL DE ACCIONES"
        ]);

        // REVOCAR ACCESO
        Modulo::create([
            "modulo" => "Revocar Acceso",
            "nombre" => "revocar_accesos.habilitar",
            "accion" => "REVOCAR ACCESOS - HABILITAR",
            "descripcion" => "HABILITAR EL ACCESO DE POSTULANTES"
        ]);

        Modulo::create([
            "modulo" => "Revocar Acceso",
            "nombre" => "revocar_accesos.deshabilitar",
            "accion" => "REVOCAR ACCESOS - DESHABILITAR",
            "descripcion" => "DESHABILITAR EL ACCESO DE POSTULANTES"
        ]);

        // BACKUP
        Modulo::create([
            "modulo" => "Backup Base de Datos",
            "nombre" => "backup_db",
            "accion" => "BACKUP BASE DE DATOS",
            "descripcion" => "DESCARGAR EL BACKUP DE LA BASE DE DATO"
        ]);
    }
}
