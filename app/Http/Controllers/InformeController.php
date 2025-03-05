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
        $turnos = DB::table('bdsystem.dbo.turno')
            ->select('CodTurno', 'NomTurno')
            ->orderBy('CodTurno')
            ->get();


        return view('informes.informes', compact('turnos'));
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

    public function getInformesDiarios($fecha)
    {
        try {
            // Validar que la fecha tenga el formato correcto
            if (!Carbon::createFromFormat('Y-m-d', $fecha)) {
                return response()->json(['error' => 'Formato de fecha inválido'], 400);
            }

            // Llamar a la función usando el nombre completo de la base de datos
            $informes = DB::select("
                SELECT * FROM pst.dbo.fn_GetInformesDiarios(?)",
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
                SELECT * FROM pst.dbo.fn_GetInformesDiarios(?)
                WHERE orden_turno = ?
            ", [$fecha, $turno])[0];

            // Obtener información por sala
            $informacion_sala = DB::select("
                SELECT * FROM pst.dbo.fn_GetInformacionPorSala(?, ?)
            ", [$fecha, $turno]);

            // Obtener detalle de procesamiento
            $detalle_procesamiento = DB::select("
                SELECT * FROM pst.dbo.fn_GetDetalleProcesamiento(?, ?)
            ", [$fecha, $turno]);

            // Obtener suma de kilos para porciones terminadas
            $porcionTerminada = DB::select("
                SELECT SUM(kilos) AS porcionTerminada
                FROM pst.dbo.fn_GetDetalleProcesamiento(?, ?)
                WHERE corte_final IN ('PORCION SIN PIEL', 'PORCION CON PIEL', 'PORCIONES')
            ", [$fecha, $turno])[0]->porcionTerminada ?? 0;

            // Obtener tiempos muertos
            $tiempos_muertos = DB::select("
                SELECT * FROM pst.dbo.fn_GetTiemposMuertos(?, ?)
            ", [$fecha, $turno]);

            // Obtener datos de empaque premium
            $empaque_premium = DB::select("
                SELECT 
                    CAST(Registro_Sistema AS DATE) AS Fecha,
                    N_Turno AS Turno,
                    Producto,
                    Empresa,
                    COUNT(DISTINCT N_Lote) AS Cantidad_Lotes,
                    SUM(CAST(N_PNom AS FLOAT)) AS Total_Kilos,
                    SUM(piezas) AS Total_Piezas
                FROM bdsystem.dbo.v_empaque
                WHERE 
                    CAST(Registro_Sistema AS DATE) = ?
                    AND N_IDTurno = ?
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
            ", [$fecha, $turno]);

            return view('informes.detalle-turno', compact(
                'fecha',
                'turno',
                'informe',
                'informacion_sala',
                'detalle_procesamiento',
                'porcionTerminada',
                'tiempos_muertos',
                'empaque_premium'
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
                'd_real_empaque' => 'nullable|integer|min:0',
                'd_esperada_empaque' => 'nullable|integer|min:0',
                'salas' => 'required|array|min:1',
                'salas.*.cod_sala' => 'required|integer|min:1',
                'salas.*.dotacion_real' => 'required|integer|min:0',
                'salas.*.dotacion_esperada' => 'required|integer|min:0',
                'salas.*.kilos_entrega' => 'required|numeric|min:0',
                'salas.*.kilos_recepcion' => 'required|numeric|min:0',
                'salas.*.kilos_premium' => 'required|numeric|min:0',
                'salas.*.piezas_entrega' => 'required|integer|min:0',
                'salas.*.piezas_recepcion' => 'required|integer|min:0',
                'salas.*.horas_trabajadas' => 'required|numeric|min:0',
                'salas.*.tiempo_muerto_minutos' => 'required|integer|min:0',
                'salas.*.rendimiento' => 'required|numeric|min:0',
                'salas.*.productividad' => 'required|numeric|min:0',
                'salas.*.premium' => 'required|numeric|min:0',
                'salas.*.tipo_planilla' => 'required|string',
            ]);

            DB::beginTransaction();

            // Formatear las fechas correctamente para SQL Server
            $fechaTurno = Carbon::parse($request->fecha_turno)->format('Ymd');
            $fechaCreacion = Carbon::now()->format('Ymd H:i:s');

            // Crear el informe
            $informe = DB::table('pst.dbo.informes_turno')->insertGetId([
                'fecha_turno' => $fechaTurno,
                'cod_turno' => (int) $request->cod_turno,
                'cod_jefe_turno' => $request->cod_jefe_turno,
                'cod_usuario_crea' => auth()->id(),
                'comentarios' => $request->comentarios,
                'fecha_creacion' => $fechaCreacion,
                'd_real_empaque' => (int) $request->d_real_empaque,
                'd_esperada_empaque' => (int) $request->d_esperada_empaque,
                'estado' => 1
            ]);

            // Insertar detalles por sala
            foreach ($request->salas as $sala) {
                DB::table('pst.dbo.detalle_informe_sala')->insert([
                    'cod_informe' => $informe,
                    'cod_sala' => (int) $sala['cod_sala'],
                    'dotacion_real' => (int) $sala['dotacion_real'],
                    'dotacion_esperada' => (int) $sala['dotacion_esperada'],
                    'kilos_entrega' => (float) $sala['kilos_entrega'],
                    'kilos_recepcion' => (float) $sala['kilos_recepcion'],
                    'kilos_premium' => (float) $sala['kilos_premium'],
                    'piezas_entrega' => (int) $sala['piezas_entrega'],
                    'piezas_recepcion' => (int) $sala['piezas_recepcion'],
                    'horas_trabajadas' => (float) $sala['horas_trabajadas'],
                    'tiempo_muerto_minutos' => (int) $sala['tiempo_muerto_minutos'],
                    'rendimiento' => (float) $sala['rendimiento'],
                    'productividad' => (float) $sala['productividad'],
                    'premium' => (float) $sala['premium'],
                    'tipo_planilla' => $sala['tipo_planilla']
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
            $existeInforme = DB::table('pst.dbo.informes_turno')
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

    public function show($fecha, $turno)
    {
        try {
            // Obtener datos base del informe y sus detalles
            $informe = DB::select("
                SELECT 
                    i.cod_informe,
                    i.fecha_turno as fecha,
                    t.NomTurno as turno,
                    i.cod_turno as orden_turno,
                    CONCAT(u.nombre, ' ', u.apellido) as jefe_turno_nom,
                    u.cod_usuario as jefe_turno,
                    i.comentarios,
                    i.d_real_empaque,
                    i.d_esperada_empaque
                FROM pst.dbo.informes_turno i
                JOIN bdsystem.dbo.turno t ON i.cod_turno = t.CodTurno
                JOIN pst.dbo.usuarios_pst u ON i.cod_jefe_turno = u.cod_usuario
                JOIN pst.dbo.detalle_informe_sala d ON i.cod_informe = d.cod_informe
                WHERE i.fecha_turno = ? AND i.cod_turno = ?
                GROUP BY 
                    i.cod_informe,
                    i.fecha_turno,
                    t.NomTurno,
                    i.cod_turno,
                    u.nombre,
                    u.apellido,
                    u.cod_usuario,
                    i.comentarios,
                    i.d_real_empaque,
                    i.d_esperada_empaque
            ", [$fecha, $turno])[0];

            // Obtener información por sala desde la tabla
            $informacion_sala = DB::select("
                SELECT 
    s.nombre as nombre_sala,
    s.cod_sala as cod_sala,
    d.tipo_planilla as tipo_planilla,
    CASE d.tipo_planilla 
        WHEN 'Filete' THEN 1
        WHEN 'Porciones' THEN 2
        WHEN 'HG' THEN 4
        ELSE NULL
    END as cod_tipo_planilla,
    d.horas_trabajadas,
    d.kilos_entrega as kilos_entrega_total,
    d.kilos_recepcion as kilos_recepcion_total,
    d.piezas_entrega as piezas_entrega_total,
    d.piezas_recepcion as piezas_recepcion_total,
    d.dotacion_real,
    d.dotacion_esperada
FROM pst.dbo.detalle_informe_sala d
JOIN pst.dbo.sala s ON d.cod_sala = s.cod_sala
JOIN pst.dbo.informes_turno i ON i.cod_informe = d.cod_informe
WHERE d.cod_informe = ?
            ", [$informe->cod_informe]);

            // Obtener detalle de procesamiento
            $detalle_procesamiento = DB::select("
                SELECT * FROM pst.dbo.fn_GetDetalleProcesamiento(?, ?)
            ", [$fecha, $turno]);

            // Obtener tiempos muertos
            $tiempos_muertos = DB::select("
                SELECT * FROM pst.dbo.fn_GetTiemposMuertos(?, ?)
            ", [$fecha, $turno]);

            // Obtener resumen
            $resumen = DB::select("
                SELECT 
                    i.cod_informe,
                    i.fecha_turno,
                    i.cod_turno,
                    SUM(d.dotacion_real) as dotacion_total_real,
                    SUM(d.dotacion_esperada) as dotacion_total_esperada,
                    CASE 
                        WHEN SUM(d.dotacion_esperada) > 0 
                        THEN ((SUM(d.dotacion_esperada) - SUM(d.dotacion_real)) * 100.0 / SUM(d.dotacion_esperada))
                        ELSE 0 
                    END as porcentaje_ausentismo,
                    SUM(d.kilos_entrega) as total_kilos_entrega,
                    SUM(d.kilos_recepcion) as total_kilos_recepcion,
                    AVG(d.rendimiento) as rendimiento_promedio,
                    AVG(d.productividad) as productividad_promedio
                FROM pst.dbo.informes_turno i
                JOIN pst.dbo.detalle_informe_sala d ON i.cod_informe = d.cod_informe
                WHERE i.cod_informe = ?
                GROUP BY i.cod_informe, i.fecha_turno, i.cod_turno
            ", [$informe->cod_informe])[0];

            // Obtener datos de empaque premium
            $empaque_premium = DB::select("
                SELECT 
                    CAST(Registro_Sistema AS DATE) AS Fecha,
                    N_Turno AS Turno,
                    Producto,
                    Empresa,
                    COUNT(DISTINCT N_Lote) AS Cantidad_Lotes,
                    SUM(CAST(N_PNom AS FLOAT)) AS Total_Kilos,
                    SUM(piezas) AS Total_Piezas
                FROM bdsystem.dbo.v_empaque
                WHERE 
                    CAST(Registro_Sistema AS DATE) = ?
                    AND N_IDTurno = ?
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
            ", [$fecha, $turno]);

            return view('informes.show', compact(
                'fecha',
                'turno',
                'informe',
                'informacion_sala',
                'detalle_procesamiento',
                'tiempos_muertos',
                'empaque_premium'
            ));

        } catch (\Exception $e) {
            \Log::error('Error en show:', [
                'fecha' => $fecha,
                'turno' => $turno,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'Error al cargar el informe: ' . $e->getMessage());
        }
    }
}