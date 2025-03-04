<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MisInformesController extends Controller
{
    public function index()
    {
        $user_id = session('user.cod_usuario');
        $fecha_limite = now()->subDays(15)->format('Y-m-d'); // Fecha de hace 7 días

        // Obtener los turnos de la base de datos
        $turnos = DB::table('bdsystem.dbo.turno')
            ->select('CodTurno', 'NomTurno')
            ->orderBy('CodTurno')
            ->get();

        $informesPendientes = DB::select("
            SELECT
                p.fec_turno,
                t.CodTurno as turno,
                t.NomTurno as Nomturno,
                COUNT(DISTINCT p.cod_planilla) as cantidad_planillas,
                CONCAT(u.nombre, ' ', u.apellido) as jefe_turno,
                SUM(dp.kilos_entrega) as total_kilos_entrega,
                SUM(dp.kilos_recepcion) as total_kilos_recepcion
            FROM pst.dbo.planillas_pst p
            JOIN bdsystem.dbo.turno t ON p.cod_turno = t.CodTurno
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
                t.CodTurno,
                t.NomTurno,
                u.nombre,
                u.apellido
            ORDER BY p.fec_turno DESC, t.CodTurno",
            [$user_id, $fecha_limite]
        );

        // // Agregar estos dd() para depuración
        // dd([
        //     'user_id' => $user_id,
        //     'informesPendientes' => $informesPendientes,
        //     'sql' => DB::getQueryLog() // Necesitas habilitar DB::enableQueryLog() al inicio
        // ]);

        // Informes ya creados
        $informesCreados = DB::select("
            SELECT
                i.fecha_turno as fec_turno,
                t.CodTurno as turno,
                t.NomTurno as Nomturno,
                i.cod_informe,
                CONCAT(u.nombre, ' ', u.apellido) as jefe_turno,
                SUM(d.kilos_entrega) as total_kilos_entrega,
                SUM(d.kilos_recepcion) as total_kilos_recepcion
            FROM pst.dbo.informes_turno i
            JOIN bdsystem.dbo.turno t ON i.cod_turno = t.CodTurno
            JOIN pst.dbo.usuarios_pst u ON i.cod_jefe_turno = u.cod_usuario
            JOIN pst.dbo.detalle_informe_sala d ON i.cod_informe = d.cod_informe
            WHERE i.cod_jefe_turno = ? 
                AND i.estado = 1
                AND i.fecha_turno >= ?
            GROUP BY 
                i.fecha_turno,
                t.CodTurno,
                t.NomTurno,
                i.cod_informe,
                u.nombre,
                u.apellido
            ORDER BY i.fecha_turno DESC, t.CodTurno",
            [$user_id, $fecha_limite]
        );

        return view('informes.mis-informes', compact('informesPendientes', 'informesCreados', 'turnos'));
    }

    public function destroy($cod_informe)
    {
        try {
            // Primero eliminamos los detalles
            DB::table('pst.dbo.detalle_informe_sala')
                ->where('cod_informe', $cod_informe)
                ->delete();

            // Luego eliminamos el informe
            DB::table('pst.dbo.informes_turno')
                ->where('cod_informe', $cod_informe)
                ->delete();

            return redirect()->route('mis-informes')
                ->with('success', 'Informe eliminado correctamente');
        } catch (\Exception $e) {
            return redirect()->route('mis-informes')
                ->with('error', 'Error al eliminar el informe');
        }
    }

    public function search(Request $request)
    {
        try {
            \Log::info('Parámetros de búsqueda:', $request->all());

            $query = DB::table('pst.dbo.informes_turno as i')
                ->join('bdsystem.dbo.turno as t', 'i.cod_turno', '=', 't.CodTurno')
                ->join('pst.dbo.usuarios_pst as u', 'i.cod_jefe_turno', '=', 'u.cod_usuario')
                ->join('pst.dbo.detalle_informe_sala as d', 'i.cod_informe', '=', 'd.cod_informe')
                ->select(
                    'i.fecha_turno',
                    'i.cod_turno as turno',
                    't.NomTurno',
                    DB::raw("CONCAT(u.nombre, ' ', u.apellido) as jefe_turno"),
                    DB::raw('SUM(d.kilos_entrega) as total_kilos_entrega'),
                    DB::raw('SUM(d.kilos_recepcion) as total_kilos_recepcion')
                )
                ->where('i.estado', '=', 1);

            if ($request->filled('fecha')) {
                $query->whereDate('i.fecha_turno', '=', $request->fecha);
            }

            if ($request->filled('turno')) {
                $query->where('i.cod_turno', '=', $request->turno);
            }

            $results = $query->groupBy(
                'i.fecha_turno',
                'i.cod_turno',
                't.NomTurno',
                'u.nombre',
                'u.apellido'
            )
                ->orderBy('i.fecha_turno', 'desc')
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
}
