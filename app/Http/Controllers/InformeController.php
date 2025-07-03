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
        } else if (session('user')['cod_rol'] == 1 || session('user')['cod_rol'] == 2) {
            return redirect('/main');
        }
        $turnos = DB::table('administracion.dbo.tipos_turno')
            ->select('id', 'nombre')
            ->orderBy('id')
            ->get();


        return view('informes.informes', compact('turnos'));
    }

    public function search(Request $request)
    {
        try {
            \Log::info('Parámetros de búsqueda:', $request->all());

            $query = DB::table('pst.dbo.informes_turno as i')
                ->join('administracion.dbo.tipos_turno as t', 'i.cod_turno', '=', 't.id')
                ->join('pst.dbo.usuarios_pst as u', 'i.cod_jefe_turno', '=', 'u.cod_usuario')
                ->select(
                    'i.fecha_turno',
                    'i.cod_turno as turno',
                    't.nombre',
                    DB::raw("CONCAT(u.nombre, ' ', u.apellido) as jefe_turno"),
                    DB::raw('0 as total_kilos_entrega'),
                    DB::raw('0 as total_kilos_recepcion')
                )
                ->where('i.estado', '=', 1);

            // Aplicar filtros si existen
            if ($request->filled('turno')) {
                $query->where('i.cod_turno', '=', $request->turno);
            }

            // Si no hay filtro de fecha específico, limitar a 1 mes
            if (!$request->filled('fecha')) {
                $unMesAtras = now()->subMonth()->format('Y-m-d');
                $query->whereDate('i.fecha_turno', '>=', $unMesAtras);
            } else {
                $query->whereDate('i.fecha_turno', '=', $request->fecha);
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

    public function getInformesDiarios($fecha)
    {
        try {
            // Validar que la fecha tenga el formato correcto
            if (!Carbon::createFromFormat('Y-m-d', $fecha)) {
                return response()->json(['error' => 'Formato de fecha inválido'], 400);
            }

            // Consulta directa sin función problemática
            $informes = DB::select("
                SELECT 
                    i.cod_informe,
                    i.fecha_turno,
                    i.cod_turno as orden_turno,
                    tt.nombre as turno,
                    CONCAT(u.nombre, ' ', u.apellido) as jefe_turno_nom,
                    u.cod_usuario as jefe_turno,
                    i.comentarios,
                    i.estado,
                    0 as dotacion_total,
                    0 as dotacion_esperada,
                    0 as total_kilos_entrega,
                    0 as total_kilos_recepcion
                FROM pst.dbo.informes_turno i
                JOIN administracion.dbo.tipos_turno tt ON i.cod_turno = tt.id
                JOIN pst.dbo.usuarios_pst u ON i.cod_jefe_turno = u.cod_usuario
                WHERE i.fecha_turno = ? 
                AND i.estado IN (0, 1)  -- Incluir borradores y finalizados
                AND tt.activo = 1
                ORDER BY i.cod_turno
            ", [$fecha]);

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
            // Validar que la fecha y el turno tengan el formato correcto
            if (!Carbon::createFromFormat('Y-m-d', $fecha) || !is_numeric($turno)) {
                return response()->json(['error' => 'Formato de fecha o turno inválido'], 400);
            }

            // Consulta directa para obtener informe específico de un turno
            $informe = DB::select("
                SELECT 
                    i.cod_informe,
                    i.fecha_turno,
                    i.cod_turno as orden_turno,
                    tt.nombre as turno,
                    CONCAT(u.nombre, ' ', u.apellido) as jefe_turno_nom,
                    u.cod_usuario as jefe_turno,
                    i.comentarios,
                    i.estado,
                    0 as dotacion_total,
                    0 as dotacion_esperada,
                    0 as total_kilos_entrega,
                    0 as total_kilos_recepcion
                FROM pst.dbo.informes_turno i
                JOIN administracion.dbo.tipos_turno tt ON i.cod_turno = tt.id
                JOIN pst.dbo.usuarios_pst u ON i.cod_jefe_turno = u.cod_usuario
                WHERE i.fecha_turno = ? 
                AND i.cod_turno = ?
                AND i.estado IN (0, 1)
                AND tt.activo = 1
            ", [$fecha, $turno]);

            // Si no hay resultados, devolver un array vacío en lugar de null
            if (empty($informe)) {
                return response()->json([], 200);
            }

            // Log para debugging
            \Log::info('Informe específico obtenido:', ['fecha' => $fecha, 'turno' => $turno]);

            return response()->json($informe[0]); // Devolver solo el primer resultado

        } catch (\Exception $e) {
            // Log del error para debugging
            \Log::error('Error en getDetalleTurno:', [
                'fecha' => $fecha,
                'turno' => $turno,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'Error al obtener el detalle del turno',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            \Log::info('Datos recibidos:', $request->all());

            // Verificar si ya existe un informe para esta fecha y turno
            $fechaTurnoFormateada = Carbon::parse($request->fecha_turno)->format('Y-m-d');
            $existeInforme = DB::table('pst.dbo.informes_turno')
                ->where('fecha_turno', $fechaTurnoFormateada)
                ->where('cod_turno', (int) $request->cod_turno)
                ->exists();

            if ($existeInforme) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Ya existe un informe para esta fecha y turno. No se puede crear un duplicado.'
                ], 422);
            }

            $request->validate([
                'fecha_turno' => 'required|date',
                'cod_turno' => 'required|integer|min:1',
                'cod_jefe_turno' => 'required|string',
                'comentarios_salas' => 'nullable|array',
                'comentarios_salas.*.cod_sala' => 'required|integer|min:1',
                'comentarios_salas.*.comentarios' => 'required|string|max:2000'
            ]);

            DB::beginTransaction();

            // Formatear la fecha correctamente para SQL Server
            $fechaTurno = Carbon::parse($request->fecha_turno)->format('Y-m-d');
            $fechaCreacion = Carbon::now()->format('Y-m-d H:i:s');

            // Crear el informe principal (estructura simplificada)
            $codInforme = DB::table('pst.dbo.informes_turno')->insertGetId([
                'fecha_turno' => $fechaTurno,
                'cod_turno' => (int) $request->cod_turno,
                'cod_jefe_turno' => $request->cod_jefe_turno,
                'cod_usuario_crea' => session('user.cod_usuario'),
                'fecha_creacion' => $fechaCreacion,
                'estado' => 1
            ]);

            // Guardar comentarios por sala (si existen)
            if (!empty($request->comentarios_salas)) {
                foreach ($request->comentarios_salas as $comentario) {
                    DB::table('pst.dbo.comentarios_informe_sala')->insert([
                        'cod_informe' => $codInforme,
                        'cod_sala' => (int) $comentario['cod_sala'],
                        'comentarios' => $comentario['comentarios'],
                        'fecha_creacion' => $fechaCreacion
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Informe guardado correctamente',
                'cod_informe' => $codInforme
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

    // ===== NUEVO SISTEMA DE BORRADOR AUTOMÁTICO =====

    /**
     * Crear informe borrador automáticamente al hacer click en "Crear Informe"
     */
    public function crearBorrador($fecha, $turno)
    {
        try {
            \Log::info('Creando borrador automático:', ['fecha' => $fecha, 'turno' => $turno]);

            // Verificar si ya existe un informe para esta fecha y turno
            $existeInforme = DB::table('pst.dbo.informes_turno')
                ->where('fecha_turno', $fecha)
                ->where('cod_turno', (int) $turno)
                ->first();

            if ($existeInforme) {
                // Si ya existe, redirigir a editarlo
                return redirect()->route('informes.editar', [
                    'cod_informe' => $existeInforme->cod_informe
                ])->with('info', 'Este informe ya existe. Puedes editarlo o finalizarlo.');
            }

            // Obtener el jefe de turno más común para esta fecha/turno
            $jefeInfo = DB::select("
                SELECT TOP 1 
                    p.cod_jefe_turno,
                    CONCAT(u.nombre, ' ', u.apellido) as jefe_nombre
                FROM pst.dbo.planillas_pst p 
                JOIN pst.dbo.usuarios_pst u ON p.cod_jefe_turno = u.cod_usuario
                WHERE p.fec_turno = ? 
                AND p.cod_turno = ? 
                AND p.guardado = 1
                AND p.cod_jefe_turno IS NOT NULL
                GROUP BY p.cod_jefe_turno, u.nombre, u.apellido
                ORDER BY COUNT(*) DESC
            ", [$fecha, $turno]);

            if (empty($jefeInfo)) {
                return redirect()->back()->with('error', 'No se encontraron planillas guardadas para esta fecha y turno.');
            }

            $jefe = $jefeInfo[0];

            DB::beginTransaction();

            // Crear el informe borrador
            $codInforme = DB::table('pst.dbo.informes_turno')->insertGetId([
                'fecha_turno' => $fecha,
                'cod_turno' => (int) $turno,
                'cod_jefe_turno' => $jefe->cod_jefe_turno,
                'cod_usuario_crea' => session('user.cod_usuario'),
                'fecha_creacion' => Carbon::now()->format('Y-m-d H:i:s'),
                'estado' => 0, // BORRADOR
                'comentarios' => null // Se completará después
            ]);

            DB::commit();

            \Log::info('Borrador creado exitosamente:', [
                'cod_informe' => $codInforme,
                'fecha' => $fecha,
                'turno' => $turno,
                'jefe' => $jefe->jefe_nombre
            ]);

            // Redirigir a la vista de edición del borrador
            return redirect()->route('informes.editar', ['cod_informe' => $codInforme])
                ->with('success', 'Informe borrador creado. Puedes agregar comentarios y fotos.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error al crear borrador:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'fecha' => $fecha,
                'turno' => $turno
            ]);
            return redirect()->back()->with('error', 'Error al crear el informe: ' . $e->getMessage());
        }
    }

    /**
     * Formatea fecha para usar en consultas SQL Server
     * Maneja diferentes formatos de entrada y retorna YYYY-MM-DD
     */
    private function formatearFecha($fecha)
    {
        try {
            // Si la fecha ya está en formato YYYY-MM-DD, usarla directamente
            if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha)) {
                return $fecha;
            }

            // Si la fecha está en formato dd/mm/yyyy, parsearla específicamente
            if (preg_match('/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/', $fecha, $matches)) {
                $dia = str_pad($matches[1], 2, '0', STR_PAD_LEFT);
                $mes = str_pad($matches[2], 2, '0', STR_PAD_LEFT);
                $año = $matches[3];
                return "$año-$mes-$dia";
            }

            // Intentar con Carbon como fallback
            return \Carbon\Carbon::parse($fecha)->format('Y-m-d');
        } catch (\Exception $e) {
            \Log::warning('Error al formatear fecha', [
                'fecha_original' => $fecha,
                'error' => $e->getMessage()
            ]);
            return $fecha; // Retornar fecha original en caso de error
        }
    }

    /**
     * Vista de edición del informe (borrador o completado)
     */
    public function editar($cod_informe)
    {
        try {
            // Obtener datos del informe
            $informe = DB::table('pst.dbo.informes_turno as i')
                ->join('administracion.dbo.tipos_turno as t', 'i.cod_turno', '=', 't.id')
                ->join('pst.dbo.usuarios_pst as u', 'i.cod_jefe_turno', '=', 'u.cod_usuario')
                ->select(
                    'i.*',
                    't.nombre as turno_nombre',
                    DB::raw("CONCAT(u.nombre, ' ', u.apellido) as jefe_turno_nom")
                )
                ->where('i.cod_informe', $cod_informe)
                ->first();

            if (!$informe) {
                return redirect()->back()->with('error', 'Informe no encontrado.');
            }

            // Verificar permisos - solo el creador puede editar
            // TODO: Revisar verificación de permisos después
            /*
            if ($informe->cod_usuario_crea != session('user.cod_usuario')) {
                return redirect()->back()->with('error', 'No tienes permisos para editar este informe.');
            }
            */

            $fecha = $informe->fecha_turno;
            $turno = $informe->cod_turno;

            // Convertir fecha al formato correcto YYYY-MM-DD para SQL Server
            $fecha_formateada = $this->formatearFecha($fecha);

            $horarios = DB::select("
                SELECT * FROM pst.dbo.fn_GetHorariosTurno(?, ?)
            ", [$fecha_formateada, $turno]);

            $horarios_data = !empty($horarios) ? $horarios[0] : null;



            // Obtener datos básicos del informe - SIN detalle_informe_sala
            $informeData = (object) [
                'cod_informe' => $informe->cod_informe,
                'fecha_turno' => $fecha,
                'orden_turno' => $turno,
                'turno' => $informe->turno_nombre,
                'jefe_turno_nom' => $informe->jefe_turno_nom,
                'cod_jefe_turno' => $informe->cod_jefe_turno,
                'estado' => $informe->estado,
                'comentarios' => $informe->comentarios,
                'fecha_creacion' => $informe->fecha_creacion,
                'fecha_finalizacion' => $informe->fecha_finalizacion ?? null,
                // Agregar horarios del turno
                'hora_inicio' => $horarios_data->hora_inicio ?? null,
                'hora_fin' => $horarios_data->hora_fin ?? null,
                'horas_trabajadas' => $horarios_data->horas_trabajadas ?? null,
                'tiene_colacion' => $horarios_data->tiene_colacion ?? 0,
                'hora_inicio_colacion' => $horarios_data->hora_inicio_colacion ?? null,
                'hora_fin_colacion' => $horarios_data->hora_fin_colacion ?? null,
                // Valores por defecto hasta que se implementen los cálculos
                'dotacion_total' => 0,
                'dotacion_esperada' => 0,
                'total_kilos_entrega' => 0,
                'total_kilos_recepcion' => 0
            ];



            // Obtener información por sala usando función existente
            $informacion_sala = DB::select("
                SELECT * FROM pst.dbo.fn_GetInformacionPorSala(?, ?)
            ", [$fecha_formateada, $turno]);

            // Obtener detalle de procesamiento usando función existente  
            $detalle_procesamiento = DB::select("
                SELECT * FROM pst.dbo.fn_GetDetalleProcesamiento(?, ?)
                ORDER BY 
                descripcion,
                calidad,
                corte_final
            ", [$fecha_formateada, $turno]);

            // Obtener tiempos muertos usando función existente
            $tiempos_muertos = DB::select("
                SELECT * FROM pst.dbo.fn_GetTiemposMuertos(?, ?)
            ", [$fecha_formateada, $turno]);

            // Obtener detalle de planillas para el modal (CONSULTA CORREGIDA)
            try {
                $planillas_detalle = DB::select("
                SELECT 
                        p.cod_planilla as numero_planilla,
                        CONCAT(u.nombre, ' ', u.apellido) as trabajador_nombre,
                        p.tiempo_trabajado as horas_trabajadas,
                        dp.dotacion,
                    dp.kilos_entrega,
                        dp.kilos_recepcion as pst_total,
                        emp.descripcion,
                        dp.cod_sala,
                        p.cod_tipo_planilla,
                        p.guardado,
                        s.nombre as sala_nombre
                FROM pst.dbo.planillas_pst p
                LEFT JOIN pst.dbo.usuarios_pst u ON p.cod_planillero = u.cod_usuario
                    LEFT JOIN pst.dbo.detalle_planilla_pst dp ON p.cod_planilla = dp.cod_planilla
                LEFT JOIN bdsystem.dbo.empresas emp ON p.cod_empresa = emp.cod_empresa
                    LEFT JOIN pst.dbo.sala s ON dp.cod_sala = s.cod_sala
                WHERE p.fec_turno = ? 
                AND p.cod_turno = ? 
                AND p.guardado = 1
                    ORDER BY emp.descripcion, s.nombre, p.cod_planilla
            ", [$fecha_formateada, $turno]);
            } catch (\Exception $e) {
                // Si hay error con las planillas, usar array vacío
                $planillas_detalle = [];
                \Log::error('Error obteniendo planillas: ' . $e->getMessage(), [
                    'fecha' => $fecha,
                    'turno' => $turno,
                    'sql_error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }

            // Datos básicos para la vista
            $valores_tarjetas_por_empresa = collect();
            $dotacion_maxima_grupos = collect();

            // === CALCULAR VALORES REALES POR EMPRESA ===

            // Obtener todas las empresas únicas de los datos
            $empresas_unicas = collect($detalle_procesamiento)->pluck('descripcion')->map(function ($nombre) {
                return trim($nombre);
            })->unique()->filter();

            // Procesar cada combinación sala/tipo/empresa
            foreach ($informacion_sala as $sala_info) {
                // Crear clave para agrupación
                $grupo_key = $sala_info->cod_sala . '-' . $sala_info->cod_tipo_planilla;

                // Obtener empresas para esta sala/tipo de planilla
                $empresas_en_sala = collect($detalle_procesamiento)
                    ->where('cod_sala', $sala_info->cod_sala)
                    ->where('cod_tipo_planilla', $sala_info->cod_tipo_planilla)
                    ->pluck('descripcion')
                    ->map(function ($nombre) {
                        return trim($nombre);
                    })
                    ->unique();

                foreach ($empresas_en_sala as $empresa_nombre) {
                    $empresa_key = $sala_info->cod_sala . '-' . $sala_info->cod_tipo_planilla . '-' . $empresa_nombre;

                    // === CALCULAR VALORES REALES DE PLANILLAS ===



                    $planillas_empresa = collect($planillas_detalle)->filter(function ($planilla) use ($empresa_nombre, $sala_info) {
                        // Limpiar y comparar nombres de empresa
                        $planilla_empresa_clean = isset($planilla->descripcion) ? trim($planilla->descripcion) : '';
                        $empresa_nombre_clean = trim($empresa_nombre);

                        $coincide_empresa = $planilla_empresa_clean === $empresa_nombre_clean;
                        $coincide_sala = $planilla->cod_sala == $sala_info->cod_sala;
                        $coincide_tipo = $planilla->cod_tipo_planilla == $sala_info->cod_tipo_planilla;

                        return $coincide_empresa && $coincide_sala && $coincide_tipo;
                    });



                    // Calcular valores reales de planillas
                    $total_horas = $planillas_empresa->sum(function ($p) {
                        return floatval($p->horas_trabajadas ?? 0);
                    });

                    $total_entrega = $planillas_empresa->sum(function ($p) {
                        return floatval($p->kilos_entrega ?? 0);
                    });

                    $total_pst = $planillas_empresa->sum(function ($p) {
                        return floatval($p->pst_total ?? 0);
                    });

                    $max_dotacion = $planillas_empresa->max(function ($p) {
                        return intval($p->dotacion ?? 0);
                    }) ?? 0;



                    // === CALCULAR PST OBJETIVO DE PRODUCTOS ===
                    $productos_empresa = collect($detalle_procesamiento)
                        ->where('cod_sala', $sala_info->cod_sala)
                        ->where('cod_tipo_planilla', $sala_info->cod_tipo_planilla)
                        ->filter(function ($producto) use ($empresa_nombre) {
                            return isset($producto->descripcion) &&
                                trim($producto->descripcion) === $empresa_nombre;
                        });

                    // Filtrar productos objetivo
                    $productos_objetivo = $productos_empresa->filter(function ($producto) {
                        return $producto->es_producto_objetivo == 1 ||
                            $producto->es_producto_objetivo === '1' ||
                            $producto->es_producto_objetivo === true;
                    });

                    $kilos_objetivo = $productos_objetivo->sum(function ($p) {
                        return floatval($p->kilos ?? 0);
                    });

                    $total_piezas = $productos_empresa->sum(function ($p) {
                        return floatval($p->piezas ?? 0);
                    });

                    // Guardar valores calculados reales
                    $valores_calculados = [
                        'dotacion' => $max_dotacion,
                        'horas_reales' => $total_horas,
                        'entrega_mp' => $total_entrega,
                        'pst_objetivo' => $kilos_objetivo,
                        'pst_total' => $total_pst,
                        'piezas_total' => $total_piezas
                    ];

                    $valores_tarjetas_por_empresa->put($empresa_key, $valores_calculados);



                    // Guardar dotación máxima para el grupo
                    $dotacion_actual = $dotacion_maxima_grupos->get($grupo_key, 0);
                    $dotacion_maxima_grupos->put($grupo_key, max($dotacion_actual, $max_dotacion));
                }
            }

            // Obtener comentarios existentes por sala
            $comentarios_existentes = DB::table('pst.dbo.comentarios_informe_sala')
                ->where('cod_informe', $cod_informe)
                ->get()
                ->keyBy('cod_sala');

            // Obtener fotos existentes  
            $fotos_existentes = DB::table('pst.dbo.fotos_informe')
                ->where('cod_informe', $cod_informe)
                ->where('activo', 1)
                ->orderBy('fecha_subida', 'desc')
                ->get();

            // Usar la vista detalle-turno con datos para edición
            return view('informes.detalle-turno', compact(
                'informe', // El informe existente
                'informeData', // Los datos calculados (simplificados)
                'informacion_sala',
                'detalle_procesamiento',
                'tiempos_muertos',
                'planillas_detalle',
                'valores_tarjetas_por_empresa',
                'dotacion_maxima_grupos',
                'comentarios_existentes',
                'fotos_existentes',
                'fecha',
                'turno'
            ));

        } catch (\Exception $e) {
            \Log::error('Error en editar informe:', [
                'cod_informe' => $cod_informe,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'Error al cargar el informe: ' . $e->getMessage());
        }
    }

    // ===== MÉTODOS AJAX PARA AUTO-GUARDADO =====

    /**
     * Actualizar comentario de sala (AJAX)
     * TODO: Implementar cuando exista la tabla comentarios_informe_sala
     */
    public function actualizarComentario(Request $request)
    {
        try {
            $request->validate([
                'cod_informe' => 'required|integer',
                'cod_sala' => 'required|integer',
                'comentarios' => 'nullable|string|max:2000'
            ]);

            $cod_informe = $request->cod_informe;
            $cod_sala = $request->cod_sala;
            $comentarios = $request->comentarios;

            // Verificar que el informe existe y pertenece al usuario
            $informe = DB::table('pst.dbo.informes_turno')
                ->where('cod_informe', $cod_informe)
                ->where('cod_jefe_turno', session('user.cod_usuario'))
                ->first();

            if (!$informe) {
                return response()->json(['error' => 'Informe no encontrado o sin permisos'], 403);
            }

            // Si el informe está finalizado (estado = 1), no permitir edición
            if ($informe->estado == 1) {
                return response()->json(['error' => 'No se puede editar un informe finalizado'], 403);
            }

            // Buscar si ya existe el comentario para esta sala
            $comentarioExistente = DB::table('pst.dbo.comentarios_informe_sala')
                ->where('cod_informe', $cod_informe)
                ->where('cod_sala', $cod_sala)
                ->first();

            if ($comentarioExistente) {
                // Actualizar comentario existente
                if (empty($comentarios)) {
                    // Si el comentario está vacío, eliminar el registro
                    DB::table('pst.dbo.comentarios_informe_sala')
                        ->where('cod_comentario', $comentarioExistente->cod_comentario)
                        ->delete();
                } else {
                    DB::table('pst.dbo.comentarios_informe_sala')
                        ->where('cod_comentario', $comentarioExistente->cod_comentario)
                        ->update([
                            'comentarios' => $comentarios,
                            'fecha_creacion' => \Carbon\Carbon::now()
                        ]);
                }
            } else if (!empty($comentarios)) {
                // Crear nuevo comentario si no existe y no está vacío
                DB::table('pst.dbo.comentarios_informe_sala')->insert([
                    'cod_informe' => $cod_informe,
                    'cod_sala' => $cod_sala,
                    'comentarios' => $comentarios,
                    'fecha_creacion' => \Carbon\Carbon::now()
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Comentario guardado automáticamente',
                'timestamp' => \Carbon\Carbon::now()->format('H:i:s')
            ]);

        } catch (\Exception $e) {
            \Log::error('Error actualizando comentario:', [
                'error' => $e->getMessage(),
                'datos' => $request->all()
            ]);
            return response()->json(['error' => 'Error al guardar comentario: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Subir foto (AJAX)
     */
    public function subirFoto(Request $request)
    {
        try {
            $request->validate([
                'cod_informe' => 'required|integer',
                'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120' // 5MB max
            ]);

            $cod_informe = $request->cod_informe;

            // Verificar permisos
            $informe = DB::table('pst.dbo.informes_turno')
                ->where('cod_informe', $cod_informe)
                ->where('cod_jefe_turno', session('user.cod_usuario'))
                ->first();

            if (!$informe || $informe->estado == 1) {
                return response()->json(['error' => 'Sin permisos o informe finalizado'], 403);
            }

            $foto = $request->file('foto');
            $nombreOriginal = $foto->getClientOriginalName();
            $extension = $foto->getClientOriginalExtension();
            $nombreUnico = $cod_informe . '_' . time() . '_' . uniqid() . '.' . $extension;

            // Crear directorio si no existe
            $directorioFotos = storage_path('app/public/informes_fotos');
            if (!file_exists($directorioFotos)) {
                mkdir($directorioFotos, 0755, true);
            }

            // Guardar en storage/app/public/informes_fotos/
            $rutaArchivo = $foto->storeAs('informes_fotos', $nombreUnico, 'public');

            // NUEVO: También copiar a public/storage para accesibilidad web (workaround para sistemas sin enlaces simbólicos)
            $directorioPublico = public_path('storage/informes_fotos');
            if (!file_exists($directorioPublico)) {
                mkdir($directorioPublico, 0755, true);
            }

            // Copiar archivo a public/storage/informes_fotos/
            $rutaPublica = public_path('storage/informes_fotos/' . $nombreUnico);
            copy(storage_path('app/public/informes_fotos/' . $nombreUnico), $rutaPublica);

            // Guardar en base de datos y obtener ID
            $idFoto = DB::table('pst.dbo.fotos_informe')->insertGetId([
                'cod_informe' => $cod_informe,
                'nombre_original' => $nombreOriginal,
                'nombre_archivo' => $nombreUnico,
                'ruta_archivo' => $rutaArchivo,
                'tamaño_archivo' => $foto->getSize(),
                'tipo_mime' => $foto->getMimeType(),
                'fecha_subida' => \Carbon\Carbon::now(),
                'cod_usuario_subida' => session('user.cod_usuario'),
                'activo' => 1
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Foto subida correctamente',
                'foto' => [
                    'id' => $idFoto, // ✅ AGREGADO: ID necesario para eliminar
                    'nombre_original' => $nombreOriginal,
                    'url' => asset('storage/' . $rutaArchivo),
                    'fecha_subida' => \Carbon\Carbon::now()->format('d/m/Y H:i')
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Error subiendo foto:', [
                'error' => $e->getMessage(),
                'datos' => $request->all()
            ]);
            return response()->json(['error' => 'Error al subir foto: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Eliminar foto (AJAX)
     */
    public function eliminarFoto(Request $request)
    {
        try {
            $request->validate([
                'id_foto' => 'required|integer'
            ]);

            $foto = DB::table('pst.dbo.fotos_informe as f')
                ->join('pst.dbo.informes_turno as i', 'f.cod_informe', '=', 'i.cod_informe')
                ->where('f.id_foto', $request->id_foto)
                ->where('i.cod_jefe_turno', session('user.cod_usuario')) // Corregir campo
                ->where('i.estado', 0) // Solo borradores
                ->select('f.*')
                ->first();

            if (!$foto) {
                return response()->json(['error' => 'Foto no encontrada o sin permisos'], 403);
            }

            // Eliminar archivo físico de storage/app/public
            $rutaCompleta = storage_path('app/public/' . $foto->ruta_archivo);
            if (file_exists($rutaCompleta)) {
                unlink($rutaCompleta);
            }

            // NUEVO: También eliminar de public/storage (workaround para sistemas sin enlaces simbólicos)
            $rutaPublica = public_path('storage/' . $foto->ruta_archivo);
            if (file_exists($rutaPublica)) {
                unlink($rutaPublica);
            }

            // Eliminar de base de datos
            DB::table('pst.dbo.fotos_informe')
                ->where('id_foto', $request->id_foto)
                ->delete();

            return response()->json([
                'success' => true,
                'message' => 'Foto eliminada correctamente'
            ]);

        } catch (\Exception $e) {
            \Log::error('Error eliminando foto:', $e->getMessage());
            return response()->json(['error' => 'Error al eliminar foto'], 500);
        }
    }

    /**
     * Finalizar informe (cambiar estado de borrador a completado)
     */
    public function finalizar(Request $request)
    {
        try {
            $request->validate([
                'cod_informe' => 'required|integer'
            ]);

            $cod_informe = $request->cod_informe;

            // Verificar permisos y que sea borrador
            $informe = DB::table('pst.dbo.informes_turno')
                ->where('cod_informe', $cod_informe)
                ->where('cod_jefe_turno', session('user.cod_usuario')) // Corregir: usar jefe de turno
                ->where('estado', 0) // Solo borradores
                ->first();

            if (!$informe) {
                return response()->json(['error' => 'Informe no encontrado o ya finalizado'], 403);
            }

            // Actualizar estado a finalizado
            DB::table('pst.dbo.informes_turno')
                ->where('cod_informe', $cod_informe)
                ->update([
                    'estado' => 1,
                    'fecha_finalizacion' => \Carbon\Carbon::now()
                ]);

            return response()->json([
                'success' => true,
                'message' => 'Informe finalizado correctamente',
                'redirect_url' => route('mis-informes')
            ]);

        } catch (\Exception $e) {
            \Log::error('Error finalizando informe:', $e->getMessage());
            return response()->json(['error' => 'Error al finalizar informe'], 500);
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
            // Asegurar formato correcto de fecha para SQL Server (YYYY-MM-DD)
            $fecha_formateada = \Carbon\Carbon::parse($fecha)->format('Y-m-d');

            // Obtener datos básicos del informe - SOLO campos que existen
            $informe = DB::select("
                SELECT 
                    i.cod_informe,
                    i.fecha_turno as fecha,
                    t.nombre as turno,
                    i.cod_turno as orden_turno,
                    CONCAT(u.nombre, ' ', u.apellido) as jefe_turno_nom,
                    u.cod_usuario as jefe_turno,
                    i.comentarios,
                    i.estado,
                    i.fecha_creacion,
                    i.fecha_finalizacion
                FROM pst.dbo.informes_turno i
                JOIN administracion.dbo.tipos_turno t ON i.cod_turno = t.id
                JOIN pst.dbo.usuarios_pst u ON i.cod_jefe_turno = u.cod_usuario
                WHERE i.fecha_turno = ? AND i.cod_turno = ?
            ", [$fecha_formateada, $turno]);

            if (empty($informe)) {
                return redirect()->back()->with('error', 'Informe no encontrado.');
            }

            $informe = $informe[0];

            // Obtener horarios del turno
            $horarios = DB::select("
                SELECT * FROM pst.dbo.fn_GetHorariosTurno(?, ?)
            ", [$fecha_formateada, $turno]);

            $horarios_data = !empty($horarios) ? $horarios[0] : null;

            // Agregar horarios al objeto informe
            if ($horarios_data) {
                $informe->hora_inicio = $horarios_data->hora_inicio;
                $informe->hora_fin = $horarios_data->hora_fin;
                $informe->horas_trabajadas = $horarios_data->horas_trabajadas;
                $informe->tiene_colacion = $horarios_data->tiene_colacion;
                $informe->hora_inicio_colacion = $horarios_data->hora_inicio_colacion;
                $informe->hora_fin_colacion = $horarios_data->hora_fin_colacion;
            }

            // Obtener información por sala usando función existente
            $informacion_sala = DB::select("
                SELECT * FROM pst.dbo.fn_GetInformacionPorSala(?, ?)
            ", [$fecha_formateada, $turno]);

            // Obtener detalle de procesamiento
            $detalle_procesamiento = DB::select("
                SELECT * FROM pst.dbo.fn_GetDetalleProcesamiento(?, ?)
                ORDER BY 
                descripcion,
                calidad,
                corte_final
            ", [$fecha_formateada, $turno]);

            // Obtener tiempos muertos
            $tiempos_muertos = DB::select("
                SELECT * FROM pst.dbo.fn_GetTiemposMuertos(?, ?)
            ", [$fecha_formateada, $turno]);

            // Obtener detalle de planillas para referencia
            try {
                $planillas_detalle = DB::select("
                    SELECT 
                        p.cod_planilla as numero_planilla,
                        CONCAT(u.nombre, ' ', u.apellido) as trabajador_nombre,
                        p.tiempo_trabajado as horas_trabajadas,
                        dp.dotacion,
                        dp.kilos_entrega,
                        dp.kilos_recepcion as pst_total,
                        emp.descripcion,
                        dp.cod_sala,
                        p.cod_tipo_planilla,
                        p.guardado,
                        s.nombre as sala_nombre
                    FROM pst.dbo.planillas_pst p
                    LEFT JOIN pst.dbo.usuarios_pst u ON p.cod_planillero = u.cod_usuario
                    LEFT JOIN pst.dbo.detalle_planilla_pst dp ON p.cod_planilla = dp.cod_planilla
                    LEFT JOIN bdsystem.dbo.empresas emp ON p.cod_empresa = emp.cod_empresa
                    LEFT JOIN pst.dbo.sala s ON dp.cod_sala = s.cod_sala
                    WHERE p.fec_turno = ?
                    AND p.cod_turno = ?
                    AND p.guardado = 1
                    ORDER BY emp.descripcion, s.nombre, p.cod_planilla
                ", [$fecha_formateada, $turno]);
            } catch (\Exception $e) {
                $planillas_detalle = [];
            }

            // Calcular resumen básico usando los datos disponibles
            $resumen = (object) [
                'cod_informe' => $informe->cod_informe,
                'fecha_turno' => $informe->fecha,
                'cod_turno' => $informe->orden_turno,
                'dotacion_total_real' => collect($informacion_sala)->sum('dotacion_real'),
                'dotacion_total_esperada' => collect($informacion_sala)->sum('dotacion_esperada'),
                'porcentaje_ausentismo' => 0,
                'total_kilos_entrega' => collect($informacion_sala)->sum('kilos_entrega_total'),
                'total_kilos_recepcion' => collect($informacion_sala)->sum('kilos_recepcion_total'),
                'rendimiento_promedio' => collect($informacion_sala)->avg('rendimiento'),
                'productividad_promedio' => collect($informacion_sala)->avg('productividad')
            ];

            // === OBTENER COMENTARIOS POR SALA ===
            $comentarios_salas = DB::table('pst.dbo.comentarios_informe_sala as c')
                ->join('pst.dbo.informes_turno as i', 'c.cod_informe', '=', 'i.cod_informe')
                ->join('pst.dbo.sala as s', 'c.cod_sala', '=', 's.cod_sala')
                ->where('i.fecha_turno', $fecha_formateada)
                ->where('i.cod_turno', $turno)
                ->select(
                    'c.cod_sala',
                    's.nombre as sala_nombre',
                    'c.comentarios',
                    'c.fecha_creacion'
                )
                ->get()
                ->keyBy('cod_sala');

            // === OBTENER FOTOS DEL INFORME ===
            $fotos_informe = DB::table('pst.dbo.fotos_informe as f')
                ->join('pst.dbo.informes_turno as i', 'f.cod_informe', '=', 'i.cod_informe')
                ->where('i.fecha_turno', $fecha_formateada)
                ->where('i.cod_turno', $turno)
                ->where('f.activo', 1)
                ->select(
                    'f.id_foto',
                    'f.nombre_original',
                    'f.nombre_archivo',
                    'f.ruta_archivo',
                    'f.tamaño_archivo',
                    'f.fecha_subida'
                )
                ->orderBy('f.fecha_subida', 'desc')
                ->get();

            return view('informes.show', compact(
                'fecha',
                'turno',
                'informe',
                'informacion_sala',
                'detalle_procesamiento',
                'tiempos_muertos',
                'planillas_detalle',
                'resumen',
                'comentarios_salas',
                'fotos_informe'
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