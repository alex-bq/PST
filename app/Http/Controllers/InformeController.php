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
        } else if ((session('user')['cod_rol'] == 1 || session('user')['cod_rol'] == 2)) {
            return redirect('/main');
        }

        return view('informes');
    }

    public function getInformesDiarios($fecha)
    {
        try {
            // Validar que la fecha tenga el formato correcto
            if (!Carbon::createFromFormat('Y-m-d', $fecha)) {
                return response()->json(['error' => 'Formato de fecha inválido'], 400);
            }

            // Llamar a la función usando el nombre completo de la base de datos
            $informes = DB::select("
                SELECT * FROM pst_2.dbo.fn_GetInformesDiarios(?)",
                [$fecha]
            );

            // Si no hay resultados, devolver un array vacío en lugar de null
            if (empty($informes)) {
                return response()->json([], 200);
            }

            // Log para debugging
            \Log::info('Informes obtenidos:', ['fecha' => $fecha, 'cantidad' => count($informes)]);

            return response()->json($informes);

        } catch (\Exception $e) {
            // Log del error para debugging
            \Log::error('Error en getInformesDiarios:', [
                'fecha' => $fecha,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'Error al obtener los informes',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getDetalleTurno($fecha, $turno)
    {
        $informe = DB::select("
            SELECT * FROM pst_2.dbo.fn_GetInformesDiarios(?)
            WHERE orden_turno = ?",
            [$fecha, $turno]
        )[0];

        $informacion_sala = DB::select("
            SELECT * FROM pst_2.dbo.fn_GetInformacionPorSala(?, ?)",
            [$fecha, $turno]
        );

        $detalle_procesamiento = DB::select("
            SELECT * FROM pst_2.dbo.fn_GetDetalleProcesamiento(?, ?)",
            [$fecha, $turno]
        );

        $tiempos_muertos = DB::select("
            SELECT * FROM pst_2.dbo.fn_GetTiemposMuertos(?, ?)",
            [$fecha, $turno]
        );

        return view('detalle-turno', compact(
            'fecha',
            'turno',
            'informe',
            'informacion_sala',
            'detalle_procesamiento',
            'tiempos_muertos'
        ));
    }
}