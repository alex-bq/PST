<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MisInformesController extends Controller
{
    public function index()
    {
        if (!session('user')) {
            return redirect('/login');
        } else if (session('user')['cod_rol'] == 1 || session('user')['cod_rol'] == 2) {
            return redirect('/main');
        }

        $user_id = session('user.cod_usuario');
        $user_rol = session('user.cod_rol');
        $fecha_limite = now()->subDays(7)->format('Y-m-d'); // Fecha de hace 7 días

        // Obtener los turnos de la base de datos
        $turnos = DB::table('administracion.dbo.tipos_turno')
            ->select('id', 'nombre')
            ->orderBy('id')
            ->get();

        // SECCIÓN 1: INFORMES PENDIENTES POR CREAR
        // Modificar consulta según el rol
        if ($user_rol == 3) { // Administrador ve todos los informes pendientes
            $informesPendientes = DB::select("
                SELECT
                    p.fec_turno,
                    t.id as turno,
                    t.nombre as nombre_turno,
                    COUNT(DISTINCT p.cod_planilla) as cantidad_planillas,
                    CONCAT(u.nombre, ' ', u.apellido) as jefe_turno,
                    SUM(dp.kilos_entrega) as total_kilos_entrega,
                    SUM(dp.kilos_recepcion) as total_kilos_recepcion
                FROM pst.dbo.planillas_pst p
                JOIN administracion.dbo.tipos_turno t ON p.cod_turno = t.id
                JOIN pst.dbo.usuarios_pst u ON p.cod_jefe_turno = u.cod_usuario
                JOIN pst.dbo.detalle_planilla_pst dp ON p.cod_planilla = dp.cod_planilla
                LEFT JOIN pst.dbo.informes_turno i ON p.fec_turno = i.fecha_turno 
                    AND p.cod_turno = i.cod_turno
                WHERE p.guardado = 1 
                    AND i.cod_informe IS NULL
                    AND p.fec_turno >= ?
                GROUP BY 
                    p.fec_turno,
                    t.id,
                    t.nombre,
                    u.nombre,
                    u.apellido
                ORDER BY p.fec_turno DESC, t.id",
                [$fecha_limite]
            );
        } else { // Jefe de turno ve solo sus informes
            $informesPendientes = DB::select("
                SELECT
                    p.fec_turno,
                    t.id as turno,
                    t.nombre as nombre_turno,
                    COUNT(DISTINCT p.cod_planilla) as cantidad_planillas,
                    CONCAT(u.nombre, ' ', u.apellido) as jefe_turno,
                    SUM(dp.kilos_entrega) as total_kilos_entrega,
                    SUM(dp.kilos_recepcion) as total_kilos_recepcion
                FROM pst.dbo.planillas_pst p
                JOIN administracion.dbo.tipos_turno t ON p.cod_turno = t.id
                JOIN pst.dbo.usuarios_pst u ON p.cod_jefe_turno = u.cod_usuario
                JOIN pst.dbo.detalle_planilla_pst dp ON p.cod_planilla = dp.cod_planilla
                LEFT JOIN pst.dbo.informes_turno i ON p.fec_turno = i.fecha_turno 
                    AND p.cod_turno = i.cod_turno
                WHERE u.cod_usuario = ? 
                    AND p.guardado = 1 
                    AND i.cod_informe IS NULL
                    AND p.fec_turno >= ?
                GROUP BY 
                    p.fec_turno,
                    t.id,
                    t.nombre,
                    u.nombre,
                    u.apellido
                ORDER BY p.fec_turno DESC, t.id",
                [$user_id, $fecha_limite]
            );
        }

        // SECCIÓN 2: MIS INFORMES CREADOS (solo del usuario actual)
        $misInformesCreados = DB::select("
            SELECT
                i.fecha_turno as fec_turno,
                t.id as turno,
                t.nombre as nombre_turno,
                i.cod_informe,
                CONCAT(u.nombre, ' ', u.apellido) as jefe_turno,
                i.estado,
                FORMAT(i.fecha_creacion, 'dd/MM/yyyy HH:mm') as fecha_creacion_formatted,
                i.fecha_creacion
            FROM pst.dbo.informes_turno i
            JOIN administracion.dbo.tipos_turno t ON i.cod_turno = t.id
            JOIN pst.dbo.usuarios_pst u ON i.cod_jefe_turno = u.cod_usuario
            WHERE i.cod_jefe_turno = ? 
                AND i.fecha_turno >= ?
            ORDER BY i.fecha_turno DESC, t.id",
            [$user_id, $fecha_limite]
        );

        return view('informes.mis-informes', compact('informesPendientes', 'misInformesCreados', 'turnos'));
    }

    // SECCIÓN 3: BÚSQUEDA GENERAL DE TODOS LOS INFORMES
    public function search(Request $request)
    {
        try {
            \Log::info('Parámetros de búsqueda:', $request->all());

            $query = DB::table('pst.dbo.informes_turno as i')
                ->join('administracion.dbo.tipos_turno as t', 'i.cod_turno', '=', 't.id')
                ->join('pst.dbo.usuarios_pst as u', DB::raw('CAST(i.cod_jefe_turno AS INT)'), '=', 'u.cod_usuario')
                ->select(
                    'i.fecha_turno',
                    'i.cod_turno as turno',
                    't.nombre',
                    DB::raw("CONCAT(u.nombre, ' ', u.apellido) as jefe_turno"),
                    'i.estado',
                    DB::raw("FORMAT(i.fecha_creacion, 'dd/MM/yyyy HH:mm') as fecha_creacion_formatted"),
                    'i.fecha_creacion',
                    'i.cod_informe'
                )
                ->where('i.estado', '=', 1); // Solo informes completados

            // Aplicar filtros si existen
            if ($request->filled('fecha')) {
                $query->whereDate('i.fecha_turno', '=', $request->fecha);
            }

            if ($request->filled('turno')) {
                $query->where('i.cod_turno', '=', $request->turno);
            }

            if ($request->filled('jefe_turno')) {
                $query->where(DB::raw('CAST(i.cod_jefe_turno AS INT)'), '=', $request->jefe_turno);
            }

            // Si no hay filtro de fecha específico, limitar a 3 meses
            if (!$request->filled('fecha')) {
                $tresMesesAtras = now()->subMonths(3)->format('Y-m-d');
                $query->whereDate('i.fecha_turno', '>=', $tresMesesAtras);
            }

            $results = $query->orderBy('i.fecha_turno', 'desc')
                ->orderBy('i.cod_turno', 'asc')
                ->get();

            \Log::info('Resultados encontrados:', ['count' => count($results)]);

            return response()->json($results);

        } catch (\Exception $e) {
            \Log::error('Error en búsqueda:', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);

            return response()->json([
                'error' => 'Error al realizar la búsqueda: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($cod_informe)
    {
        try {
            \Log::info('Iniciando eliminación de informe', ['cod_informe' => $cod_informe]);

            // Verificar permisos - solo el creador o admin puede eliminar
            $informe = DB::table('pst.dbo.informes_turno')
                ->where('cod_informe', $cod_informe)
                ->first();

            if (!$informe) {
                return redirect()->route('mis-informes')
                    ->with('error', 'Informe no encontrado');
            }

            $user_rol = session('user.cod_rol');
            $user_id = session('user.cod_usuario');

            // Solo el jefe de turno que creó el informe o un admin puede eliminarlo
            if ($user_rol != 3 && $informe->cod_jefe_turno != $user_id) {
                return redirect()->route('mis-informes')
                    ->with('error', 'No tienes permisos para eliminar este informe');
            }

            // Usar transacción para seguridad
            DB::beginTransaction();

            // PASO 1: Eliminar comentarios relacionados
            $comentarios_eliminados = DB::table('pst.dbo.comentarios_informe_sala')
                ->where('cod_informe', $cod_informe)
                ->delete();

            \Log::info('Comentarios eliminados', ['cantidad' => $comentarios_eliminados]);

            // PASO 2: Eliminar fotos relacionadas (y archivos físicos)
            $fotos = DB::table('pst.dbo.fotos_informe')
                ->where('cod_informe', $cod_informe)
                ->get();

            foreach ($fotos as $foto) {
                // Eliminar archivo físico si existe
                $rutaCompleta = storage_path('app/public/' . $foto->ruta_archivo);
                if (file_exists($rutaCompleta)) {
                    unlink($rutaCompleta);
                    \Log::info('Archivo físico eliminado', ['ruta' => $rutaCompleta]);
                }
            }

            $fotos_eliminadas = DB::table('pst.dbo.fotos_informe')
                ->where('cod_informe', $cod_informe)
                ->delete();

            \Log::info('Fotos eliminadas', ['cantidad' => $fotos_eliminadas]);

            // PASO 3: Sin detalles de sala que eliminar (tabla no existe)
            $detalle_eliminado = 0;
            \Log::info('Sin detalles de sala que eliminar');

            // PASO 4: Finalmente eliminar el informe principal
            $informe_eliminado = DB::table('pst.dbo.informes_turno')
                ->where('cod_informe', $cod_informe)
                ->delete();

            DB::commit();

            \Log::info('Informe eliminado exitosamente', [
                'cod_informe' => $cod_informe,
                'comentarios' => $comentarios_eliminados,
                'fotos' => $fotos_eliminadas,
                'detalles' => $detalle_eliminado
            ]);

            return redirect()->route('mis-informes')
                ->with('success', 'Informe y todos sus datos relacionados eliminados correctamente');

        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Error al eliminar informe', [
                'cod_informe' => $cod_informe,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('mis-informes')
                ->with('error', 'Error al eliminar el informe: ' . $e->getMessage());
        }
    }

    // Método para obtener jefes de turno para el filtro de búsqueda
    public function getJefesTurno()
    {
        try {
            \Log::info('Iniciando getJefesTurno - versión simplificada');

            // Consulta SQL directa para evitar problemas de tipos de datos
            $jefes = DB::select("
                SELECT DISTINCT 
                    u.cod_usuario, 
                    CONCAT(u.nombre, ' ', u.apellido) as nombre_completo
                FROM pst.dbo.usuarios_pst u
                INNER JOIN pst.dbo.informes_turno i ON CAST(u.cod_usuario AS NUMERIC) = i.cod_jefe_turno
                WHERE u.activo = 1
                ORDER BY u.nombre, u.apellido
            ");

            \Log::info('Jefes de turno encontrados:', ['count' => count($jefes)]);

            return response()->json($jefes);
        } catch (\Exception $e) {
            \Log::error('Error al obtener jefes de turno:', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);

            // Si hay error, devolver al menos algunos usuarios activos
            try {
                $usuariosActivos = DB::select("
                    SELECT cod_usuario, CONCAT(nombre, ' ', apellido) as nombre_completo
                    FROM pst.dbo.usuarios_pst 
                    WHERE activo = 1 AND cod_rol = 4
                    ORDER BY nombre, apellido
                ");

                \Log::info('Fallback: usuarios activos rol 4:', ['count' => count($usuariosActivos)]);
                return response()->json($usuariosActivos);

            } catch (\Exception $e2) {
                \Log::error('Error en fallback:', ['error' => $e2->getMessage()]);
                return response()->json(['error' => 'Error al obtener jefes de turno'], 500);
            }
        }
    }
}
