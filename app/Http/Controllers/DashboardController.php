<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function getData(Request $request)
    {
        if (!session('user')) {
            return redirect('/login');
        } else if ((session('user')['cod_rol'] == 1 || session('user')['cod_rol'] == 2)) {
            return redirect('/main');
        }

        try {
            // Establecer valores por defecto
            $fecha = $request->fecha ? Carbon::parse($request->fecha) : Carbon::today();
            $tipoPlanilla = $request->tipo_planilla ?? 'Filete';

            // Obtener el inicio (lunes) y fin (domingo) de la semana
            $inicioSemana = $fecha->copy()->startOfWeek();
            $finSemana = $fecha->copy()->endOfWeek();

            // Respuesta base
            $response = [];

            // Si es empaque, obtener datos específicos de empaque
            if ($tipoPlanilla === 'Empaque') {
                // Obtener datos de empaque premium
                $empaqueQuery = DB::select("
                    SELECT 
                        p.fec_turno AS fecha_turno,
                        p.cod_turno AS turno,
                        p.empresa_nombre AS Empresa,
                        p.lote_nombre AS Producto,
                        COUNT(DISTINCT p.lote_nombre) AS cantidad_lotes,
                        SUM(CAST(dp.kilos_terminado AS FLOAT)) AS total_kilos,
                        SUM(dp.piezas_recepcion) AS total_piezas
                    FROM pst.dbo.planillas_pst p
                    INNER JOIN pst.dbo.detalle_planilla_pst dp ON p.cod_planilla = dp.cod_planilla
                    INNER JOIN pst.dbo.sala s ON dp.cod_sala = s.cod_sala
                    WHERE 
                        p.fec_turno BETWEEN ? AND ?
                        AND p.guardado = 1
                        AND (
                            (p.cod_turno = 1 AND p.hora_inicio BETWEEN '08:00:00' AND '17:30:00')
                            OR 
                            (p.cod_turno = 3 AND (
                                p.hora_inicio BETWEEN '20:00:00' AND '23:59:59'
                                OR 
                                p.hora_inicio BETWEEN '00:00:00' AND '05:30:00'
                            ))
                        )
                    GROUP BY 
                        p.fec_turno,
                        p.cod_turno,
                        p.lote_nombre,
                        p.empresa_nombre
                    ORDER BY 
                        p.fec_turno DESC,
                        p.cod_turno,
                        SUM(CAST(dp.kilos_terminado AS FLOAT)) DESC
                ", [$inicioSemana->format('Y-m-d'), $finSemana->format('Y-m-d')]);

                // Obtener datos de productividad de empaque
                $productividadEmpaqueQuery = DB::select("
                    SELECT 
                        i.fecha_turno,
                        i.cod_turno,
                        t.NomTurno AS turno,
                        i.d_real_empaque AS dotacion_real,
                        i.d_esperada_empaque AS dotacion_esperada,
                        i.horas_trabajadas_empaque,
                        i.tiempo_muerto_empaque,
                        i.productividad_empaque
                    FROM pst.dbo.informes_turno i
                    -- Usar directamente cod_turno de informes_turno
                    WHERE 
                        i.fecha_turno BETWEEN ? AND ?
                        AND i.estado = 1
                    ORDER BY 
                        i.fecha_turno DESC,
                        i.cod_turno
                ", [$inicioSemana->format('Y-m-d'), $finSemana->format('Y-m-d')]);

                $response['empaque'] = $empaqueQuery;
                $response['productividad_empaque'] = $productividadEmpaqueQuery;
            } else {
                // Obtener datos de producción para otros tipos de planilla
                $produccionQuery = DB::table('pst.dbo.vw_analisis_informes')
                    ->where('estado', 1)
                    ->where('tipo_planilla', $tipoPlanilla)
                    ->whereBetween('fecha_turno', [
                        $inicioSemana->format('Y-m-d'),
                        $finSemana->format('Y-m-d')
                    ])
                    ->orderBy('fecha_turno', 'desc')
                    ->orderBy('cod_turno', 'asc')
                    ->get();

                // Obtener datos de tiempos muertos con parámetros correctamente formateados
                $tiemposMuertosQuery = DB::select("
                    SELECT * FROM pst.dbo.fn_tiempos_muertos_dashboard(
                        '{$inicioSemana->format('Y-m-d')}',
                        '{$finSemana->format('Y-m-d')}',
                        '{$tipoPlanilla}'
                    )
                ");

                $response['produccion'] = $produccionQuery;
                $response['tiempos_muertos'] = $tiemposMuertosQuery;
            }

            Log::info('Consulta ejecutada', [
                'fecha_seleccionada' => $fecha->format('Y-m-d'),
                'inicio_semana' => $inicioSemana->format('Y-m-d'),
                'fin_semana' => $finSemana->format('Y-m-d'),
                'tipo_planilla' => $tipoPlanilla
            ]);

            return response()->json($response);

        } catch (\Exception $e) {
            Log::error('Error en getData:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }
}