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
            $tipoPlanilla = $request->tipo_planilla ?? 'Filete'; // Filete por defecto

            // Obtener el inicio (lunes) y fin (domingo) de la semana
            $inicioSemana = $fecha->copy()->startOfWeek();
            $finSemana = $fecha->copy()->endOfWeek();

            $query = DB::table('pst_2.dbo.vw_analisis_informes')
                ->where('estado', 1)
                ->where('tipo_planilla', $tipoPlanilla)
                ->whereBetween('fecha_turno', [
                    $inicioSemana->format('Y-m-d'),
                    $finSemana->format('Y-m-d')
                ]);

            // Ordenamos por fecha y turno
            $query->orderBy('fecha_turno', 'desc')
                ->orderBy('cod_turno', 'asc');

            $results = $query->get();

            Log::info('Consulta ejecutada', [
                'fecha_seleccionada' => $fecha->format('Y-m-d'),
                'inicio_semana' => $inicioSemana->format('Y-m-d'),
                'fin_semana' => $finSemana->format('Y-m-d'),
                'tipo_planilla' => $tipoPlanilla,
                'cantidad_registros' => count($results)
            ]);

            return $results;

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