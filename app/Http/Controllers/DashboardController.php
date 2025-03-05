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
                        CAST(Registro_Sistema AS DATE) AS fecha_turno,
                        N_Turno AS turno,
                        Producto,
                        Empresa,
                        COUNT(DISTINCT N_Lote) AS cantidad_lotes,
                        SUM(CAST(N_PNom AS FLOAT)) AS total_kilos,
                        SUM(piezas) AS total_piezas
                    FROM bdsystem.dbo.v_empaque
                    WHERE 
                        CAST(Registro_Sistema AS DATE) BETWEEN ? AND ?
                        AND N_Calidad = 'PREMIUM'
                    GROUP BY 
                        CAST(Registro_Sistema AS DATE),
                        N_Turno,
                        Producto,
                        Empresa
                    ORDER BY 
                        CAST(Registro_Sistema AS DATE) DESC,
                        N_Turno,
                        SUM(CAST(N_PNom AS FLOAT)) DESC
                ", [$inicioSemana->format('Y-m-d'), $finSemana->format('Y-m-d')]);

                $response['empaque'] = $empaqueQuery;
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