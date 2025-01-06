<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class InformeController extends Controller
{
    public function index()
    {
        if (!session('user')) {
            return redirect('/login');
        }

        $turnos = DB::select('SELECT codTurno, NomTurno FROM bdsystem.dbo.turno WHERE inactivo=0 ORDER BY NomTurno ASC;');
        $supervisores = DB::select('SELECT cod_usuario, nombre FROM pst_2.dbo.v_data_usuario WHERE cod_rol=2 AND activo = 1 ORDER BY nombre ASC;');

        return view('informes.index_informe', compact('turnos', 'supervisores'));
    }

    public function informePorTurno(Request $request)
    {
        $fecha = $request->get('fecha', date('Y-m-d'));
        $turno = $request->get('turno');
        $supervisor = $request->get('supervisor');

        $query = DB::table('pst_2.dbo.v_informe_por_turno')
            ->where('fecha', $fecha);

        if ($turno) {
            $query->where('cod_turno', $turno);
        }

        if ($supervisor) {
            $query->where('cod_supervisor', $supervisor);
        }

        $informe = $query->first();

        if ($request->ajax()) {
            return response()->json($informe);
        }

        return view('informes.turno', compact('informe'));
    }

    public function filtrarInformes(Request $request)
    {
        try {
            $fechaInicio = $request->get('fecha_inicio', Carbon::now()->subDays(7)->format('Y-m-d'));
            $fechaFin = $request->get('fecha_fin', Carbon::now()->format('Y-m-d'));
            $turno = $request->get('turno');
            $supervisor = $request->get('supervisor');

            // Primero, verificar si hay planillas para estas fechas
            $planillasExistentes = DB::table('pst_2.dbo.planillas_pst')
                ->whereBetween('fec_turno', [$fechaInicio, $fechaFin])
                ->where('guardado', 1)
                ->select('cod_planilla', 'fec_turno', 'cod_turno')
                ->get();

            \Log::info('Planillas encontradas:', [
                'total' => count($planillasExistentes),
                'planillas' => $planillasExistentes
            ]);

            // Consulta principal
            $informes = DB::table('pst_2.dbo.planillas_pst as p')
                ->join('pst_2.dbo.detalle_planilla_pst as dp', 'p.cod_planilla', '=', 'dp.cod_planilla')
                ->leftJoin('bdsystem.dbo.turno as t', 'p.cod_turno', '=', 't.codTurno')
                ->select([
                    'p.cod_planilla',
                    'p.fec_turno as fecha',
                    'p.cod_turno',
                    't.NomTurno as nombre_turno',
                    DB::raw('ISNULL(dp.dotacion, 0) as total_dotacion'),
                    DB::raw('ISNULL(CAST(dp.productividad AS DECIMAL(10,2)), 0) as promedio_productividad'),
                    DB::raw('ISNULL(CAST(dp.rendimiento AS DECIMAL(10,2)), 0) as promedio_rendimiento'),
                    DB::raw('ISNULL(CAST(dp.kilos_entrega AS DECIMAL(10,2)), 0) as total_kilos_entrega'),
                    DB::raw('ISNULL(CAST(dp.kilos_recepcion AS DECIMAL(10,2)), 0) as total_kilos_recepcion')
                ])
                ->where('p.guardado', 1)
                ->whereBetween('p.fec_turno', [$fechaInicio, $fechaFin]);

            if ($turno) {
                $informes->where('p.cod_turno', $turno);
            }

            if ($supervisor) {
                $informes->where('p.cod_supervisor', $supervisor);
            }

            // Obtener la consulta SQL para debugging
            $sqlQuery = $informes->toSql();
            $bindings = $informes->getBindings();

            // Ejecutar la consulta
            $resultados = $informes->get();

            // Log detallado
            \Log::info('Consulta de informes:', [
                'sql' => $sqlQuery,
                'bindings' => $bindings,
                'total_resultados' => count($resultados),
                'fechas' => [
                    'inicio' => $fechaInicio,
                    'fin' => $fechaFin
                ],
                'filtros' => [
                    'turno' => $turno,
                    'supervisor' => $supervisor
                ]
            ]);

            return response()->json([
                'informes' => $resultados,
                'debug' => [
                    'fecha_inicio' => $fechaInicio,
                    'fecha_fin' => $fechaFin,
                    'turno' => $turno,
                    'supervisor' => $supervisor,
                    'total_registros' => count($resultados),
                    'sql' => $sqlQuery,
                    'bindings' => $bindings,
                    'planillas_encontradas' => count($planillasExistentes)
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error en filtrarInformes: ' . $e->getMessage());
            return response()->json([
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'sql' => isset($sqlQuery) ? $sqlQuery : null,
                'bindings' => isset($bindings) ? $bindings : null
            ], 500);
        }
    }

    public function verDetalle(Request $request)
    {
        try {
            $fecha = $request->get('fecha');
            $turno = $request->get('turno');

            // Obtener información desde la vista v_informe_por_turno con turno y supervisor
            $detalle = DB::table('pst_2.dbo.v_informe_por_turno as v')
                ->leftJoin('bdsystem.dbo.turno as t', 'v.cod_turno', '=', 't.codTurno')
                ->leftJoin('pst_2.dbo.v_data_usuario as u', 'v.cod_supervisor', '=', 'u.cod_usuario')
                ->where('v.fecha', $fecha)
                ->where('v.cod_turno', $turno)
                ->select([
                    'v.*',
                    't.NomTurno as nombre_turno',
                    'u.nombre as nombre_supervisor'
                ])
                ->first();

            if (!$detalle) {
                return response()->json([
                    'error' => 'No se encontraron datos para la fecha y turno especificados'
                ], 404);
            }

            // Resto del código para desglose y tiempos muertos...
            $desglose = DB::table('pst_2.dbo.registro_planilla_pst as r')
                ->join('pst_2.dbo.planillas_pst as p', 'r.cod_planilla', '=', 'p.cod_planilla')
                ->leftJoin('pst_2.dbo.corte as c', 'r.cod_corte_fin', '=', 'c.cod_corte')
                ->leftJoin('pst_2.dbo.calidad as ca', 'r.cod_calidad', '=', 'ca.cod_cald')
                ->where('p.fec_turno', $fecha)
                ->where('p.cod_turno', $turno)
                ->select([
                    'c.nombre as nombre_corte',
                    'ca.nombre as nombre_calidad',
                    DB::raw('COUNT(*) as total_registros'),
                    DB::raw('SUM(r.piezas) as total_piezas'),
                    DB::raw('SUM(r.kilos) as total_kilos')
                ])
                ->groupBy('c.nombre', 'ca.nombre')
                ->get();

            // Tiempos muertos...
            $tiemposMuertos = DB::table('pst_2.dbo.tiempos_muertos as tm')
                ->join('pst_2.dbo.planillas_pst as p', 'tm.cod_planilla', '=', 'p.cod_planilla')
                ->where('p.fec_turno', $fecha)
                ->where('p.cod_turno', $turno)
                ->select([
                    'tm.causa as descripcion',
                    'tm.duracion_minutos',
                    'tm.hora_inicio',
                    'tm.hora_termino'
                ])
                ->get();

            // Obtener planillas relacionadas
            $planillas = DB::table('pst_2.dbo.planillas_pst as p')
                ->leftJoin('pst_2.dbo.detalle_planilla_pst as dp', 'p.cod_planilla', '=', 'dp.cod_planilla')
                ->leftJoin('bdsystem.dbo.turno as t', 'p.cod_turno', '=', 't.codTurno')
                ->leftJoin('pst_2.dbo.v_data_usuario as u', 'p.cod_supervisor', '=', 'u.cod_usuario')
                ->where('p.fec_turno', $fecha)
                ->where('p.cod_turno', $turno)
                ->where('p.guardado', 1)
                ->select([
                    'p.cod_planilla',
                    'p.fec_turno',
                    'p.cod_turno',
                    't.NomTurno as nombre_turno',
                    'u.nombre as nombre_supervisor',
                    'dp.dotacion',
                    DB::raw('CAST(dp.productividad AS DECIMAL(10,2)) as productividad'),
                    DB::raw('CAST(dp.rendimiento AS DECIMAL(10,2)) as rendimiento'),
                    DB::raw('CAST(dp.kilos_entrega AS DECIMAL(10,2)) as kilos_entrega'),
                    DB::raw('CAST(dp.kilos_recepcion AS DECIMAL(10,2)) as kilos_recepcion'),
                    'p.guardado'
                ])
                ->orderBy('p.cod_planilla')
                ->get();

            return response()->json([
                'detalle' => $detalle,
                'desglose' => $desglose,
                'tiempos_muertos' => $tiemposMuertos,
                'planillas' => $planillas
            ]);
        } catch (\Exception $e) {
            \Log::error('Error en verDetalle: ' . $e->getMessage());
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }
}