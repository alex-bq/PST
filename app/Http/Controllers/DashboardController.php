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

            // Obtener datos de producción
            $produccionQuery = DB::table('pst_2.dbo.vw_analisis_informes')
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
                SELECT * FROM pst_2.dbo.fn_tiempos_muertos_dashboard(
                    '{$inicioSemana->format('Y-m-d')}',
                    '{$finSemana->format('Y-m-d')}',
                    '{$tipoPlanilla}'
                )
            ");

            // Combinar los resultados
            $response = [
                'produccion' => $produccionQuery,
                'tiempos_muertos' => $tiemposMuertosQuery
            ];

            Log::info('Consulta ejecutada', [
                'fecha_seleccionada' => $fecha->format('Y-m-d'),
                'inicio_semana' => $inicioSemana->format('Y-m-d'),
                'fin_semana' => $finSemana->format('Y-m-d'),
                'tipo_planilla' => $tipoPlanilla,
                'cantidad_registros_produccion' => count($produccionQuery),
                'cantidad_registros_tiempos_muertos' => count($tiemposMuertosQuery)
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