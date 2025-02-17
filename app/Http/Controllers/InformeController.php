<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class InformeController extends Controller
{
    public function index()
    {
        if (!session('user')) {
            return redirect('/login');
        } else if ((session('user')['cod_rol'] == 1 || session('user')['cod_rol'] == 2)) {
            return redirect('/main');
        }

        return view('informes.informes');
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
        try {
            // Obtener datos del informe
            $informe = DB::select("
                SELECT * FROM pst_2.dbo.fn_GetInformesDiarios(?)
                WHERE orden_turno = ?
            ", [$fecha, $turno])[0];

            // Obtener información por sala
            $informacion_sala = DB::select("
                SELECT * FROM pst_2.dbo.fn_GetInformacionPorSala(?, ?)
            ", [$fecha, $turno]);

            // Obtener detalle de procesamiento
            $detalle_procesamiento = DB::select("
                SELECT * FROM pst_2.dbo.fn_GetDetalleProcesamiento(?, ?)
            ", [$fecha, $turno]);

            // Obtener tiempos muertos
            $tiempos_muertos = DB::select("
                SELECT * FROM pst_2.dbo.fn_GetTiemposMuertos(?, ?)
            ", [$fecha, $turno]);

            return view('informes.detalle-turno', compact(
                'fecha',
                'turno',
                'informe',
                'informacion_sala',
                'detalle_procesamiento',
                'tiempos_muertos'
            ));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al cargar el detalle del turno: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            \Log::info('Datos recibidos:', $request->all());

            $request->validate([
                'fecha_turno' => 'required|date',
                'cod_turno' => 'required|integer|min:1',
                'cod_jefe_turno' => 'required',
                'comentarios' => 'required|string',
                'salas' => 'required|array|min:1',
                'salas.*.cod_sala' => 'required|integer|min:1',
                'salas.*.dotacion_real' => 'required|integer|min:0',
                'salas.*.dotacion_esperada' => 'required|integer|min:0',
                'salas.*.kilos_entrega' => 'required|numeric|min:0',
                'salas.*.kilos_recepcion' => 'required|numeric|min:0',
                'salas.*.horas_trabajadas' => 'required|numeric|min:0',
                'salas.*.tiempo_muerto_minutos' => 'required|integer|min:0',
                'salas.*.rendimiento' => 'required|numeric|min:0',
                'salas.*.productividad' => 'required|numeric|min:0',
            ]);

            DB::beginTransaction();

            // Formatear las fechas correctamente para SQL Server
            $fechaTurno = Carbon::parse($request->fecha_turno)->format('Ymd'); // Formato SQL Server
            $fechaCreacion = Carbon::now()->format('Ymd H:i:s'); // Formato SQL Server

            // Crear el informe con fechas formateadas
            $informe = DB::table('pst_2.dbo.informes_turno')->insertGetId([
                'fecha_turno' => $fechaTurno,
                'cod_turno' => (int) $request->cod_turno,
                'cod_jefe_turno' => $request->cod_jefe_turno,
                'cod_usuario_crea' => auth()->id(),
                'comentarios' => $request->comentarios,
                'fecha_creacion' => $fechaCreacion,
                'estado' => 1
            ]);

            // Insertar detalles por sala
            foreach ($request->salas as $sala) {
                DB::table('pst_2.dbo.detalle_informe_sala')->insert([
                    'cod_informe' => $informe,
                    'cod_sala' => (int) $sala['cod_sala'],
                    'dotacion_real' => (int) $sala['dotacion_real'],
                    'dotacion_esperada' => (int) $sala['dotacion_esperada'],
                    'kilos_entrega' => (float) $sala['kilos_entrega'],
                    'kilos_recepcion' => (float) $sala['kilos_recepcion'],
                    'horas_trabajadas' => (float) $sala['horas_trabajadas'],
                    'tiempo_muerto_minutos' => (int) $sala['tiempo_muerto_minutos'],
                    'rendimiento' => (float) $sala['rendimiento'],
                    'productividad' => (float) $sala['productividad']
                ]);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Informe guardado correctamente',
                'cod_informe' => $informe
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error al guardar informe:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'datos' => $request->all()
            ]);
            return response()->json([
                'status' => 'error',
                'message' => 'Error al guardar el informe: ' . $e->getMessage()
            ], 500);
        }
    }

    public function validarInforme(Request $request)
    {
        try {
            $fechaTurno = Carbon::parse($request->fecha)->format('Ymd');
            $existeInforme = DB::table('pst_2.dbo.informes_turno')
                ->where('fecha_turno', $fechaTurno)
                ->where('cod_turno', $request->turno)
                ->exists();

            return response()->json([
                'status' => 'success',
                'existe' => $existeInforme
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al validar informe: ' . $e->getMessage()
            ], 500);
        }
    }
}