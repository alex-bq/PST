@extends('layouts.main-iframe')

@section('title', isset($informe) ? 'Editar Informe de Turno' : 'Crear Informe de Turno')

@section('styles')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <style>
        .desarrollo-pendiente {
            background: linear-gradient(45deg, #fbbf24, #f59e0b);
            color: white;
            font-weight: bold;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 10px;
            animation: pulse 2s infinite;
        }

        .estado-guardado {
            color: #10b981;
            font-size: 12px;
            opacity: 0;
            transition: all 0.3s ease;
            transform: translateY(-5px);
        }

        .estado-guardado.show {
            opacity: 1;
            transform: translateY(0);
        }

        .estado-guardando {
            color: #f59e0b;
            font-size: 12px;
            animation: pulse 1.5s infinite;
        }

        /* Animación para el estado de guardando */
        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }
        }



        /* Estilos para feedback de error */
        .error-feedback {
            color: #ef4444;
            font-size: 11px;
            background: #fef2f2;
            padding: 4px 8px;
            border-radius: 4px;
            border-left: 3px solid #ef4444;
        }

        .drag-drop-zone {
            border: 2px dashed #d1d5db;
            transition: all 0.3s ease;
        }

        .drag-drop-zone.dragover {
            border-color: #3b82f6;
            background-color: #eff6ff;
        }

        .foto-thumbnail {
            position: relative;
            display: inline-block;
            margin: 8px;
        }

        .foto-eliminar {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #ef4444;
            color: white;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 12px;
        }

        /* ===== ESTILOS PARA SISTEMA DE FOTOS ===== */

        /* Zona de drag & drop */
        #drag-drop-zone {
            transition: all 0.3s ease;
            cursor: pointer;
        }

        #drag-drop-zone:hover {
            border-color: #3b82f6;
            background-color: #eff6ff;
        }

        #drag-drop-zone.dragover {
            border-color: #10b981;
            background-color: #ecfdf5;
            transform: scale(1.02);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        /* Grid de fotos */
        .foto-thumbnail {
            position: relative;
            border-radius: 8px;
            overflow: hidden;
            transition: all 0.3s ease;
            background: white;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .foto-thumbnail:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        /* Botón eliminar foto */
        .foto-eliminar {
            position: absolute;
            top: 4px;
            right: 4px;
            background: rgba(239, 68, 68, 0.9);
            color: white;
            border: none;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            font-size: 14px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: all 0.2s ease;
        }

        .foto-thumbnail:hover .foto-eliminar {
            opacity: 1;
        }

        .foto-eliminar:hover {
            background: rgba(239, 68, 68, 1);
            transform: scale(1.1);
        }

        /* Estado de subida de fotos */
        #upload-status {
            padding: 8px 16px;
            border-radius: 6px;
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            margin-top: 8px;
        }

        /* Animaciones para contador de fotos */
        #contador-fotos {
            transition: all 0.3s ease;
        }

        .contador-actualizado {
            color: #10b981;
            font-weight: 600;
            transform: scale(1.1);
        }

        /* Estilos para logs de debugging en consola */
        .log-foto-subida {
            color: #059669;
            font-weight: bold;
        }

        .log-foto-error {
            color: #dc2626;
            font-weight: bold;
        }

        /* Feedback visual mejorado para estados */
        .estado-subiendo {
            background: #fef3c7;
            color: #d97706;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            animation: pulse 1.5s infinite;
        }

        .estado-subido {
            background: #d1fae5;
            color: #065f46;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
        }

        /* Estilos para el modal de detalle de planilla (sin animaciones) */
        #modalDetallePlanilla {
            backdrop-filter: blur(1px);
        }
    </style>
@endsection

@section('content')

    <body class="min-h-screen bg-gray-50">
        <!-- Header Moderno -->
        <div class="bg-white shadow-sm border-b sticky top-0 z-50">
            <div class="container mx-auto px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <button onclick="window.history.back()"
                            class="flex items-center gap-2 px-3 py-2 border border-gray-300 rounded-md hover:bg-gray-50">
                            <i data-lucide="arrow-left" class="h-4 w-4"></i>
                            Volver
                        </button>
                        <div>
                            @if(isset($informe) && isset($informe->cod_informe))
                                <!-- MODO EDICIÓN -->
                                <h1 class="text-xl font-bold">
                                    @if($informe->estado == 0)
                                        📝 Editando Informe (Borrador)
                                    @else
                                        📄 Visualizando Informe (Completado)
                                    @endif
                                </h1>
                                <p class="text-sm text-gray-600">
                                    {{ \Carbon\Carbon::parse($fecha)->format('d/m/Y') }} - {{ $informe->turno_nombre }} - Jefe:
                                    {{ $informe->jefe_turno_nom }}
                                </p>
                                <p class="text-xs">
                                    @if(isset($informeData->hora_inicio) && isset($informeData->hora_fin))
                                        <span class="text-blue-600">
                                            🕐 {{ $informeData->hora_inicio }} - {{ $informeData->hora_fin }}
                                            @if(isset($informeData->horas_trabajadas))
                                                ({{ number_format($informeData->horas_trabajadas, 1) }}h)
                                            @endif
                                        </span>
                                        @if(isset($informeData->tiene_colacion) && $informeData->tiene_colacion && isset($informeData->hora_inicio_colacion))
                                            | <span class="text-orange-600">
                                                ☕ {{ $informeData->hora_inicio_colacion }} - {{ $informeData->hora_fin_colacion }}
                                            </span>
                                        @endif
                                    @else
                                        <span class="text-gray-600">⚠️ Horarios pendientes de configuración</span>
                                    @endif
                                    @if(isset($informeData->fecha_finalizacion) && $informeData->fecha_finalizacion)
                                        | <span class="text-green-600">
                                            ✅ Finalizado:
                                            {{ \Carbon\Carbon::parse($informeData->fecha_finalizacion)->format('d/m/Y H:i') }}
                                        </span>
                                    @endif
                                </p>
                            @else
                                <!-- MODO CREACIÓN -->
                                <h1 class="text-xl font-bold">🆕 Crear Informe de Turno</h1>
                                <p class="text-sm text-gray-600">
                                    {{ \Carbon\Carbon::parse($fecha)->format('d/m/Y') }} - {{ $informe->turno ?? 'Turno' }} -
                                    Jefe: {{ $informe->jefe_turno_nom ?? 'Jefe' }}
                                </p>
                            @endif

                            @if(!isset($informe->cod_informe))
                                <!-- MODO CREACIÓN -->
                                <p class="text-xs">
                                    @if(isset($informe->hora_inicio) && isset($informe->hora_fin))
                                        <span class="text-blue-600">
                                            🕐 {{ $informe->hora_inicio }} - {{ $informe->hora_fin }}
                                            @if(isset($informe->horas_trabajadas))
                                                ({{ number_format($informe->horas_trabajadas, 1) }}h)
                                            @endif
                                        </span>
                                        @if(isset($informe->tiene_colacion) && $informe->tiene_colacion && isset($informe->hora_inicio_colacion))
                                            | <span class="text-orange-600">
                                                ☕ {{ $informe->hora_inicio_colacion }} - {{ $informe->hora_fin_colacion }}
                                            </span>
                                        @endif
                                    @else
                                        <span class="text-gray-600">⚠️ Horarios pendientes de configuración</span>
                                    @endif
                                </p>
                            @endif
                        </div>
                    </div>

                    @if(isset($informe->cod_informe) && $informe->estado == 0)
                        <!-- MODO EDICIÓN - Botón Finalizar -->
                        <button onclick="finalizarInforme()"
                            class="flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                            <i data-lucide="check-circle" class="h-4 w-4"></i>
                            Finalizar Informe
                        </button>
                    @elseif(isset($informe->cod_informe) && $informe->estado == 1)
                        <!-- INFORME COMPLETADO -->
                        <div class="flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-600 rounded-md">
                            <i data-lucide="lock" class="h-4 w-4"></i>
                            Informe Finalizado
                        </div>
                    @else
                        <!-- MODO CREACIÓN - Botón Guardar (crear borrador) -->
                        <button onclick="guardarInforme()"
                            class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            <i data-lucide="save" class="h-4 w-4"></i>
                            Crear Borrador
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <div class="container mx-auto p-6 space-y-6">
            @php
                // Organizar datos por sala -> tipo de planilla -> empresa
                $salas_agrupadas = collect($informacion_sala)->groupBy('nombre_sala');

                // Agrupar productos por empresa para modales
                $productos_por_empresa = collect($detalle_procesamiento)
                    ->groupBy(function ($item) {
                        return $item->cod_sala . '-' . $item->cod_tipo_planilla . '-' . $item->descripcion;
                    });
            @endphp

            <!-- Secciones por Sala -->
            @foreach($salas_agrupadas as $sala_nombre => $datos_sala)
                <div class="bg-white rounded-lg shadow-sm">
                    <div class="p-6 border-b">
                        <h2 class="text-lg font-semibold">{{ $sala_nombre }}</h2>
                    </div>
                    <div class="p-6 space-y-6">
                        @foreach($datos_sala as $sala)
                            <!-- Proceso/Tipo de Planilla -->
                            <div class="border rounded-lg p-4 space-y-4">
                                <div class="flex items-center justify-between">
                                    <h3 class="font-semibold text-lg">{{ $sala->tipo_planilla }}</h3>
                                    @php
                                        // Obtener empresas para esta sala y tipo
                                        $empresas_en_sala = collect($detalle_procesamiento)
                                            ->where('cod_sala', $sala->cod_sala)
                                            ->where('cod_tipo_planilla', $sala->cod_tipo_planilla)
                                            ->pluck('descripcion')
                                            ->unique();

                                        $total_empresas = $empresas_en_sala->count();
                                    @endphp
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-md text-sm font-medium">
                                        ✅ {{ $total_empresas }} {{ $total_empresas == 1 ? 'Empresa' : 'Empresas' }}
                                    </span>
                                </div>

                                @if($total_empresas > 0)
                                    <!-- Mostrar datos por empresa -->
                                    @foreach($empresas_en_sala as $empresa_nombre)
                                        @php
                                            // Obtener datos específicos de esta empresa
                                            $productos_empresa = collect($detalle_procesamiento)
                                                ->where('cod_sala', $sala->cod_sala)
                                                ->where('cod_tipo_planilla', $sala->cod_tipo_planilla)
                                                ->where('descripcion', $empresa_nombre);

                                            // Obtener valores para esta empresa (si existen)
                                            $empresa_key = $sala->cod_sala . '-' . $sala->cod_tipo_planilla . '-' . trim($empresa_nombre);
                                            $valores_empresa = isset($valores_tarjetas_por_empresa) ?
                                                $valores_tarjetas_por_empresa->get($empresa_key, []) : [];



                                            // Obtener dotación máxima para el grupo (si existe)
                                            $grupo_key = $sala->cod_sala . '-' . $sala->cod_tipo_planilla;
                                            $dotacion_max = isset($dotacion_maxima_grupos) ?
                                                $dotacion_maxima_grupos->get($grupo_key, 0) : 0;

                                            // === CALCULAR LAS 4 PRODUCTIVIDADES ===
                                            $dotacion = $dotacion_max > 0 ? $dotacion_max : 1; // Evitar división por 0
                                            $horas_efectivas = ($valores_empresa['horas_reales'] ?? $sala->horas_efectivas ?? 0);
                                            $horas_turno = isset($informeData) ? ($informeData->horas_trabajadas ?? 0) : 0;
                                            $kilos_objetivo = $valores_empresa['pst_objetivo'] ?? 0;
                                            $kilos_pst_total = $valores_empresa['pst_total'] ?? $sala->kilos_recepcion ?? 0;

                                            // NUEVA VARIABLE: Calcular entrega de materia prima
                                            $entrega_mp = $valores_empresa['entrega_mp'] ?? $sala->kilos_entrega ?? 0;

                                            // Evitar división por 0 en horas
                                            $horas_efectivas = $horas_efectivas > 0 ? $horas_efectivas : 1;
                                            $horas_turno = $horas_turno > 0 ? $horas_turno : 1;

                                            // Calcular las 4 productividades
                                            $productividad_objetivo_efectivas = round($kilos_objetivo / ($dotacion * $horas_efectivas), 2);
                                            $productividad_objetivo_turno = round($kilos_objetivo / ($dotacion * $horas_turno), 2);
                                            $productividad_total_efectivas = round($kilos_pst_total / ($dotacion * $horas_efectivas), 2);
                                            $productividad_total_turno = round($kilos_pst_total / ($dotacion * $horas_turno), 2);

                                            // NUEVO CÁLCULO: Rendimiento (PST Objetivo / Entrega MP) × 100
                                            $rendimiento = $entrega_mp > 0 ? round(($kilos_objetivo / $entrega_mp) * 100, 2) : 0;
                                        @endphp

                                        <div class="bg-gray-50 rounded-lg p-4 space-y-4">
                                            <!-- Header de empresa -->
                                            <div class="flex items-center justify-between">
                                                <h4 class="font-medium text-gray-900 flex items-center gap-2">
                                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-md text-sm font-medium">
                                                        🏢 {{ $empresa_nombre }}
                                                    </span>
                                                    <span class="text-xs text-gray-500">
                                                        {{ $productos_empresa->count() }} productos
                                                    </span>
                                                </h4>
                                                <div class="flex gap-2">
                                                    <button
                                                        onclick="mostrarModal('planillas', '{{ $empresa_nombre }}', {{ $sala->cod_sala }}, {{ $sala->cod_tipo_planilla }})"
                                                        class="flex items-center gap-2 px-3 py-2 border border-gray-300 rounded-md hover:bg-gray-50">
                                                        <i data-lucide="file-text" class="h-4 w-4"></i>
                                                        Planillas
                                                    </button>
                                                    <button
                                                        onclick="mostrarModal('productos', '{{ $empresa_nombre }}', {{ $sala->cod_sala }}, {{ $sala->cod_tipo_planilla }})"
                                                        class="flex items-center gap-2 px-3 py-2 border border-gray-300 rounded-md hover:bg-gray-50">
                                                        <i data-lucide="package" class="h-4 w-4"></i>
                                                        Productos
                                                    </button>
                                                    <button
                                                        onclick="mostrarModal('tiempos', '{{ $empresa_nombre }}', {{ $sala->cod_sala }}, {{ $sala->cod_tipo_planilla }})"
                                                        class="flex items-center gap-2 px-3 py-2 border border-gray-300 rounded-md hover:bg-gray-50">
                                                        <i data-lucide="clock" class="h-4 w-4"></i>
                                                        Tiempos Muertos
                                                    </button>
                                                </div>
                                            </div>

                                            <!-- Datos operacionales básicos -->
                                            <div class="grid grid-cols-2 md:grid-cols-6 gap-3 mb-4">
                                                <div class="bg-white p-3 rounded text-center">
                                                    <p class="text-xs text-gray-600">Dotación</p>
                                                    <p class="font-semibold text-lg">{{ $dotacion_max }}</p>
                                                </div>
                                                <div class="bg-white p-3 rounded text-center">
                                                    <p class="text-xs text-gray-600">Horas Efectivas</p>
                                                    <p class="font-semibold text-gray-900">
                                                        {{ number_format($horas_efectivas, 1) }}h
                                                    </p>
                                                </div>
                                                <div class="bg-white p-3 rounded text-center">
                                                    <p class="text-xs text-gray-600">Horas Turno</p>
                                                    <p class="font-semibold text-blue-600">
                                                        {{ number_format($horas_turno, 1) }}h
                                                    </p>
                                                </div>
                                                <div class="bg-white p-3 rounded text-center">
                                                    <p class="text-xs text-gray-600">Entrega MP</p>
                                                    <p class="font-semibold text-purple-700">
                                                        {{ number_format($entrega_mp, 2) }} kg
                                                    </p>
                                                </div>
                                                <div class="bg-white p-3 rounded text-center">
                                                    <p class="text-xs text-gray-600">PST Objetivo</p>
                                                    <p class="font-semibold text-green-700">
                                                        {{ number_format($kilos_objetivo, 2) }} kg
                                                    </p>
                                                </div>
                                                <div class="bg-white p-3 rounded text-center">
                                                    <p class="text-xs text-gray-600">PST Total</p>
                                                    <p class="font-semibold text-blue-700">
                                                        {{ number_format($kilos_pst_total, 2) }} kg
                                                    </p>
                                                </div>
                                            </div>

                                            <!-- PRODUCTIVIDADES (4 tipos) -->
                                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                                <h5 class="text-sm font-semibold text-yellow-800 mb-3 flex items-center gap-2">
                                                    📊 Productividades (kg/persona/hora)
                                                    y Rendimiento
                                                </h5>
                                                <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
                                                    <div class="bg-white p-3 rounded text-center border-l-4 border-green-500">
                                                        <p class="text-xs text-gray-600 mb-1">Objetivo + Efectivas</p>
                                                        <p class="font-bold text-green-700">
                                                            {{ $productividad_objetivo_efectivas }}
                                                        </p>
                                                        <p class="text-xs text-gray-500">
                                                            {{ $kilos_objetivo }}kg ÷ ({{ $dotacion }} ×
                                                            {{ number_format($horas_efectivas, 1) }}h)
                                                        </p>
                                                    </div>
                                                    <div class="bg-white p-3 rounded text-center border-l-4 border-green-400">
                                                        <p class="text-xs text-gray-600 mb-1">Objetivo + Turno</p>
                                                        <p class="font-bold text-green-600">
                                                            {{ $productividad_objetivo_turno }}
                                                        </p>
                                                        <p class="text-xs text-gray-500">
                                                            {{ $kilos_objetivo }}kg ÷ ({{ $dotacion }} ×
                                                            {{ number_format($horas_turno, 1) }}h)
                                                        </p>
                                                    </div>
                                                    <div class="bg-white p-3 rounded text-center border-l-4 border-blue-500">
                                                        <p class="text-xs text-gray-600 mb-1">Total + Efectivas</p>
                                                        <p class="font-bold text-blue-700">
                                                            {{ $productividad_total_efectivas }}
                                                        </p>
                                                        <p class="text-xs text-gray-500">
                                                            {{ $kilos_pst_total }}kg ÷ ({{ $dotacion }} ×
                                                            {{ number_format($horas_efectivas, 1) }}h)
                                                        </p>
                                                    </div>
                                                    <div class="bg-white p-3 rounded text-center border-l-4 border-blue-400">
                                                        <p class="text-xs text-gray-600 mb-1">Total + Turno</p>
                                                        <p class="font-bold text-blue-600">
                                                            {{ $productividad_total_turno }}
                                                        </p>
                                                        <p class="text-xs text-gray-500">
                                                            {{ $kilos_pst_total }}kg ÷ ({{ $dotacion }} ×
                                                            {{ number_format($horas_turno, 1) }}h)
                                                        </p>
                                                    </div>
                                                    <div class="bg-white p-3 rounded text-center border-l-4 border-orange-500">
                                                        <p class="text-xs text-gray-600 mb-1">Rendimiento</p>
                                                        <p class="font-bold text-orange-700">
                                                            {{ $rendimiento }}%
                                                        </p>
                                                        <p class="text-xs text-gray-500">
                                                            {{ number_format($kilos_objetivo, 0) }}kg ÷
                                                            {{ number_format($entrega_mp, 0) }}kg × 100
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        @endforeach

                        <!-- COMENTARIOS POR SALA (Solo en modo edición) -->
                        @if(isset($informe->cod_informe))
                            @php
                                // Obtener el código de sala del primer proceso (todas las salas agrupadas tienen el mismo cod_sala)
                                $primera_sala = $datos_sala->first();
                                $cod_sala_actual = $primera_sala ? $primera_sala->cod_sala : null;
                            @endphp

                            @if($cod_sala_actual)
                                <div class="border-t pt-6 mt-6">
                                    <div class="bg-blue-50 rounded-lg p-4">
                                        <div class="flex items-center justify-between mb-3">
                                            <h4 class="font-medium text-gray-900 flex items-center gap-2">
                                                💬 Comentarios de {{ $sala_nombre }}
                                                <span class="text-xs text-gray-500 font-normal">(Sala {{ $cod_sala_actual }})</span>
                                            </h4>
                                            <span id="estado_{{ $cod_sala_actual }}" class="estado-guardado text-green-600 text-sm">
                                                <!-- Estado de guardado aparecerá aquí -->
                                            </span>
                                        </div>

                                        @if($informe->estado == 0)
                                            <!-- Informe en borrador - editable -->
                                            <textarea id="comentario_{{ $cod_sala_actual }}"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 resize-none bg-white"
                                                rows="3" placeholder="Agregar comentarios generales sobre {{ $sala_nombre }}..."
                                                data-cod-sala="{{ $cod_sala_actual }}"
                                                onblur="guardarComentario({{ $cod_sala_actual }})">{{ isset($comentarios_existentes[$cod_sala_actual]) ? $comentarios_existentes[$cod_sala_actual]->comentarios : '' }}</textarea>
                                            <p class="text-xs text-gray-500 mt-2 flex items-center gap-1">
                                                <i data-lucide="save" class="h-3 w-3"></i>
                                                Se guarda automáticamente al salir del campo
                                            </p>
                                        @else
                                            <!-- Informe finalizado - solo lectura -->
                                            <div class="bg-white p-4 rounded-md border">
                                                @if(isset($comentarios_existentes[$cod_sala_actual]) && $comentarios_existentes[$cod_sala_actual]->comentarios)
                                                    <p class="text-gray-700 whitespace-pre-wrap">
                                                        {{ $comentarios_existentes[$cod_sala_actual]->comentarios }}
                                                    </p>
                                                @else
                                                    <p class="text-gray-500 italic">Sin comentarios registrados para esta sala</p>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            @endforeach

            <!-- SECCIÓN DE FOTOS GENERAL (Solo en modo edición) -->
            @if(isset($informe->cod_informe))
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-semibold flex items-center gap-2">
                            📸 Fotos del Informe
                        </h2>
                        <span id="contador-fotos" class="text-sm text-gray-500">
                            {{ isset($fotos_existentes) ? count($fotos_existentes) : 0 }} fotos
                        </span>
                    </div>

                    @if($informe->estado == 0)
                        <!-- Informe en borrador - se pueden subir fotos -->
                        <div class="space-y-4">
                            <!-- Zona de arrastre y soltar -->
                            <div id="drag-drop-zone"
                                class="drag-drop-zone border-2 border-dashed border-gray-300 rounded-lg p-8 text-center">
                                <div class="space-y-2">
                                    <i data-lucide="upload" class="h-12 w-12 text-gray-400 mx-auto"></i>
                                    <p class="text-lg font-medium text-gray-600">Arrastra fotos aquí o haz click para seleccionar
                                    </p>
                                    <p class="text-sm text-gray-500">Formatos: JPG, PNG, WEBP (Max: 10MB por foto)</p>
                                    <input type="file" id="file-input" multiple accept="image/*" class="hidden">
                                    <button type="button"
                                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                        <i data-lucide="camera" class="h-4 w-4 mr-2"></i>
                                        Seleccionar Fotos
                                    </button>
                                </div>
                            </div>

                            <!-- NUEVO: Preview de fotos antes de subir -->
                            <div id="preview-container" class="hidden mt-4 space-y-4">
                                <h4 class="font-medium text-gray-700">Fotos seleccionadas:</h4>
                                <div id="preview-grid" class="space-y-4">
                                    <!-- Los previews se generan dinámicamente aquí -->
                                </div>
                                <button id="btn-subir-todas" onclick="subirTodasLasFotos()"
                                    class="w-full bg-green-600 text-white py-2 rounded-md hover:bg-green-700">
                                    Subir Todas las Fotos
                                </button>
                            </div>

                            <!-- Estado de subida -->
                            <div id="upload-status" class="hidden">
                                <div class="bg-blue-50 border border-blue-200 rounded-md p-3">
                                    <div class="flex items-center">
                                        <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-blue-600 mr-3"></div>
                                        <span class="text-blue-700">Subiendo fotos...</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Grid de fotos existentes -->
                    <div id="fotos-grid" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mt-4">
                        @if(isset($fotos_existentes))
                            @foreach($fotos_existentes as $foto)
                                <div class="foto-thumbnail" data-foto-id="{{ $foto->id_foto }}"
                                    data-comentario="{{ $foto->comentario ?? '' }}">
                                    <img src="{{ asset('storage/' . $foto->ruta_archivo) }}" alt="{{ $foto->nombre_original }}"
                                        class="w-full h-32 object-cover rounded-lg border border-gray-200 cursor-pointer"
                                        onclick="ampliarFoto('{{ asset('storage/' . $foto->ruta_archivo) }}', '{{ $foto->nombre_original }}', '{{ $foto->comentario ?? '' }}')"
                                        onerror="console.error('❌ Error cargando imagen existente:', this.src)">
                                    @if($informe->estado == 0)
                                        <button onclick="eliminarFoto({{ $foto->id_foto }})" class="foto-eliminar" title="Eliminar foto">
                                            ×
                                        </button>
                                    @endif
                                    <div class="p-2 space-y-1">
                                        <p class="text-xs text-gray-500 truncate" title="{{ $foto->nombre_original }}">
                                            {{ $foto->nombre_original }}
                                        </p>
                                        @if($informe->estado == 0)
                                            <!-- COMENTARIO EDITABLE -->
                                            <p class="comentario-foto text-xs text-blue-600 cursor-pointer hover:text-blue-800"
                                                onclick="editarComentarioFoto({{ $foto->id_foto }})" title="Click para editar comentario">
                                                {{ $foto->comentario ?: 'Agregar comentario...' }}
                                            </p>
                                        @else
                                            <!-- COMENTARIO SOLO LECTURA -->
                                            <p class="text-xs text-gray-600">
                                                {{ $foto->comentario ?: 'Sin comentario' }}
                                            </p>
                                        @endif
                                        <p class="text-xs text-gray-400">
                                            {{ \Carbon\Carbon::parse($foto->fecha_subida)->format('d/m H:i') }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>

                    @if((!isset($fotos_existentes) || count($fotos_existentes) == 0) && $informe->estado == 1)
                        <div class="text-center py-8 text-gray-500">
                            <i data-lucide="image" class="h-16 w-16 mx-auto mb-4 text-gray-300"></i>
                            <p>Sin fotos adjuntas en este informe</p>
                        </div>
                    @endif
                </div>

                <!-- NUEVO: Modal para editar comentario de foto -->
                <div id="modal-comentario-foto"
                    class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 z-50 flex items-center justify-center">
                    <div class="bg-white rounded-lg shadow-xl p-6 w-96 max-w-md mx-4">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Editar Comentario</h3>
                            <button onclick="cerrarModalComentario()" class="text-gray-400 hover:text-gray-600">
                                <i data-lucide="x" class="h-5 w-5"></i>
                            </button>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Describe qué muestra esta foto:
                                </label>
                                <textarea id="comentario-textarea" rows="3" maxlength="500" placeholder="Agregar comentario..."
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"></textarea>
                                <div class="text-right mt-1">
                                    <span id="contador-caracteres" class="text-xs text-gray-500">0/500</span>
                                </div>
                            </div>

                            <div class="flex justify-end space-x-3">
                                <button onclick="cerrarModalComentario()"
                                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                                    Cancelar
                                </button>
                                <button onclick="guardarComentarioFoto()"
                                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
                                    Guardar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- NUEVO: Modal para ampliar fotos -->
                <div id="modal-foto-ampliada"
                    class="hidden fixed inset-0 bg-black bg-opacity-80 z-50 flex items-center justify-center p-4">
                    <div class="relative max-w-6xl max-h-full flex flex-col bg-white rounded-lg overflow-hidden">
                        <div class="relative flex-shrink-0">
                            <img id="img-ampliada" src="" alt="Foto ampliada" class="max-w-full max-h-[80vh] object-contain">
                            <button onclick="cerrarFotoAmpliada()"
                                class="absolute top-4 right-4 text-white bg-black bg-opacity-50 rounded-full p-2 hover:bg-opacity-75 transition-all">
                                <i data-lucide="x" class="w-6 h-6"></i>
                            </button>
                        </div>
                        <div id="info-foto-ampliada" class="bg-white text-gray-800 px-6 py-4 border-t border-gray-200">
                            <!-- Información de la foto se carga dinámicamente -->
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Modal para mostrar detalles -->
        <div id="modal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
            <div class="bg-white rounded-lg max-w-4xl max-h-[90vh] overflow-y-auto m-4">
                <div class="p-6 border-b flex justify-between items-center">
                    <h3 id="modal-title" class="text-lg font-semibold"></h3>
                    <button onclick="cerrarModal()" class="text-gray-500 hover:text-gray-700">
                        <i data-lucide="x" class="h-6 w-6"></i>
                    </button>
                </div>
                <div id="modal-content" class="p-6">
                    <!-- Contenido se carga dinámicamente -->
                </div>
            </div>
        </div>

        <!-- Modal para mostrar detalle de planilla individual (Tailwind con centrado Bootstrap) -->
        <div id="modalDetallePlanilla" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50"
            style="align-items: center; justify-content: center;">
            <div class="bg-white rounded-lg" style="max-width: 85vw; width: 85vw; max-height: 85vh; position: relative;">
                <!-- Botón cerrar flotante -->
                <button type="button" onclick="cerrarModalDetallePlanilla()"
                    style="position: absolute; top: 10px; right: 10px; z-index: 1000; background-color: rgba(255,255,255,0.9); border: none; border-radius: 50%; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 18px; color: #666;"
                    aria-label="Close">✕</button>
                <!-- Aquí se mostrará la información de la planilla -->
                <iframe id="iframePlanillaDetalle" style="width:100%;height:85vh;border:none;border-radius:8px;"
                    frameborder="0"></iframe>
            </div>
        </div>
    </body>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // ===== VARIABLE    S GLOBALES =====
        // Datos PHP para JavaScript
        const detalleProcesamientoPorEmpresa = @json($detalle_procesamiento);
        const tiemposMuertosPorEmpresa = @json($tiempos_muertos);
        const planillasDetalle = @json($planillas_detalle ?? []);
        const informacionSala = @json($informacion_sala);
        const empaqueData = @json($empaque_premium ?? []);

        // Variables del informe
        const fechaTurno = '{{ $fecha }}';
        const codTurno = {{ $informeActual->orden_turno ?? $informe->orden_turno ?? 0 }};
        const codJefeTurno = {{ $informeActual->cod_jefe_turno ?? $informe->cod_jefe_turno ?? session('user.cod_usuario') }};
        const jefeNombre = '{{ $informeActual->jefe_turno_nom ?? $informe->jefe_turno_nom ?? '' }}';

        // Variables de edición (si está en modo edición)
        @if(isset($informe->cod_informe))
            const codInforme = {{ $informe->cod_informe }};
            const estadoInforme = {{ $informe->estado }};
            const modoEdicion = true;
        @else
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    const codInforme = null;
            const es    tadoInforme = null;
            const modoEdicion = false;
        @endif

                                                                                                                                                                                                                                                                                                                                                // Valores ya calculados desde el backend
                                                                                                                                                                                                                                                                                                                                                const valoresCalculados = @json($valores_tarjetas_por_empresa);
        console.log('📊 Valores calculados desde backend:', valoresCalculados);

        // Función para obtener valores reales de una empresa
        function obtenerValoresEmpresa(empresa, codSala, codTipoPlanilla) {
            // Construir clave asegurando que todo sea string
            const empresaKey = String(codSala) + '-' + String(codTipoPlanilla) + '-' + empresa.trim();
            const valores = valoresCalculados[empresaKey];

            if (!valores) {
                console.log('⚠️ No se encontraron valores para:', empresaKey);
                return {
                    dotacion: 0,
                    horas: 0,
                    entregaMP: 0,
                    pstObjetivo: 0,
                    pstTotal: 0
                };
            }

            return {
                dotacion: valores.dotacion || 0,
                horas: valores.horas_reales || 0,
                entregaMP: valores.entrega_mp || 0,
                pstObjetivo: valores.pst_objetivo || 0,
                pstTotal: valores.pst_total || 0
            };
        }

        // Función para actualizar un card específico
        function actualizarCardEmpresaReal(empresa, codSala, codTipoPlanilla) {
            const valores = obtenerValoresEmpresa(empresa, codSala, codTipoPlanilla);

            // Buscar el card correspondiente
            const cards = document.querySelectorAll('.bg-gray-50.rounded-lg.p-4.space-y-4');

            for (let card of cards) {
                const empresaSpan = card.querySelector('.bg-blue-100.text-blue-800');
                if (empresaSpan && empresaSpan.textContent.includes(empresa.trim())) {
                    // Verificar que sea de la sala y tipo correcto
                    const botones = card.querySelectorAll('button[onclick*="mostrarModal"]');
                    if (botones.length > 0) {
                        const botonTexto = botones[0].getAttribute('onclick');
                        if (botonTexto.includes(`${codSala}`) && botonTexto.includes(`${codTipoPlanilla}`)) {
                            // Actualizar valores en el card
                            const grid = card.querySelector('.grid.grid-cols-2.md\\:grid-cols-6');
                            if (grid) {
                                const divs = grid.querySelectorAll('.bg-white.p-3.rounded.text-center');

                                if (divs[0]) { // Dotación
                                    const p = divs[0].querySelector('p.font-semibold');
                                    if (p) p.textContent = valores.dotacion;
                                }

                                if (divs[1]) { // Horas Efectivas
                                    const p = divs[1].querySelector('p.font-semibold');
                                    if (p) p.textContent = valores.horas.toFixed(1) + 'h';
                                }

                                if (divs[2]) { // Horas Turno
                                    const p = divs[2].querySelector('p.font-semibold');
                                    if (p) {
                                        // Obtener horas turno desde los datos del informe
                                        const horasTurno = {{ isset($informeData) ? ($informeData->horas_trabajadas ?? 0) : 0 }};
                                        p.textContent = horasTurno.toFixed(1) + 'h';
                                    }
                                }

                                if (divs[3]) { // Entrega MP
                                    const p = divs[3].querySelector('p.font-semibold');
                                    if (p) p.textContent = valores.entregaMP.toFixed(2) + ' kg';
                                }

                                if (divs[4]) { // PST Objetivo
                                    const p = divs[4].querySelector('p.font-semibold');
                                    if (p) p.textContent = valores.pstObjetivo.toFixed(2) + ' kg';
                                }

                                if (divs[5]) { // PST Total
                                    const p = divs[5].querySelector('p.font-semibold');
                                    if (p) p.textContent = valores.pstTotal.toFixed(2) + ' kg';
                                }
                            }

                            // Actualizar sección de productividades - agregar rendimiento
                            const prodGrid = card.querySelector('.bg-yellow-50 .grid.grid-cols-2.md\\:grid-cols-5');
                            if (prodGrid) {
                                const prodDivs = prodGrid.querySelectorAll('.bg-white.p-3.rounded.text-center');

                                // Calcular rendimiento
                                const rendimiento = valores.entregaMP > 0 ? ((valores.pstObjetivo / valores.entregaMP) * 100) : 0;

                                if (prodDivs[4]) { // Rendimiento (quinta tarjeta)
                                    const p = prodDivs[4].querySelector('p.font-bold');
                                    if (p) p.textContent = rendimiento.toFixed(2) + '%';

                                    const calc = prodDivs[4].querySelector('p.text-xs.text-gray-500');
                                    if (calc) calc.textContent = `${valores.pstObjetivo.toFixed(0)}kg ÷ ${valores.entregaMP.toFixed(0)}kg × 100`;
                                }
                            }

                            console.log(`✅ Card actualizado: ${empresa} - Dotación: ${valores.dotacion}, Horas: ${valores.horas.toFixed(1)}h, PST: ${valores.pstTotal.toFixed(2)}kg, Rendimiento: ${((valores.entregaMP > 0 ? (valores.pstObjetivo / valores.entregaMP) * 100 : 0)).toFixed(2)}%`);
                            break;
                        }
                    }
                }
            }
        }

        // Función para inicializar todos los cards
        function inicializarTodosLosCards() {
            console.log('🔄 Inicializando cards con valores calculados desde el backend...');
            console.log('📊 Valores disponibles:', Object.keys(valoresCalculados).length);

            const cards = document.querySelectorAll('.bg-gray-50.rounded-lg.p-4.space-y-4');
            let cardsActualizados = 0;

            for (let card of cards) {
                const empresaSpan = card.querySelector('.bg-blue-100.text-blue-800');
                if (empresaSpan) {
                    const empresa = empresaSpan.textContent.replace('🏢 ', '').trim();
                    const boton = card.querySelector('button[onclick*="mostrarModal"]');
                    if (boton) {
                        const onclick = boton.getAttribute('onclick');
                        const matches = onclick.match(/mostrarModal\('planillas',\s*'[^']+',\s*(\d+),\s*(\d+)\)/);
                        if (matches) {
                            const codSala = parseInt(matches[1]);
                            const codTipoPlanilla = parseInt(matches[2]);
                            actualizarCardEmpresaReal(empresa, codSala, codTipoPlanilla);
                            cardsActualizados++;
                        }
                    }
                }
            }

            console.log(`🎯 Total de cards actualizados: ${cardsActualizados}`);
        }

        // Ejecutar la inicialización cuando todo esté listo
        function ejecutarInicializacion() {
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', inicializarTodosLosCards);
            } else {
                inicializarTodosLosCards();
            }
        }

        // Ejecutar inmediatamente si el DOM ya está listo, o esperar si no
        ejecutarInicializacion();

        // También ejecutar después de un pequeño delay para asegurar que todo esté cargado
        setTimeout(inicializarTodosLosCards, 1000);

        // ===== FUNCIONES DE AUTO-GUARDADO =====

        /**
         * Guardar comentario de sala (AJAX)
         */
        function guardarComentario(codSala) {
            // Verificaciones de seguridad
            if (!modoEdicion || estadoInforme !== 0) {
                console.log('🚫 Guardado bloqueado: No está en modo edición o el informe está finalizado');
                return;
            }

            if (!codInforme) {
                console.error('❌ Error: No hay código de informe disponible');
                return;
            }

            const textarea = document.getElementById(`comentario_${codSala}`);
            const estadoSpan = document.getElementById(`estado_${codSala}`);

            if (!textarea || !estadoSpan) {
                console.error('❌ Error: No se encontraron elementos DOM necesarios');
                return;
            }

            const comentarios = textarea.value.trim();
            console.log(`💾 Guardando comentario sala ${codSala}:`, comentarios.substring(0, 50) + '...');

            // Mostrar estado guardando
            estadoSpan.innerHTML = '<span class="estado-guardando text-yellow-600">💾 Guardando...</span>';
            estadoSpan.classList.add('show');

            // Hacer petición AJAX
            fetch('{{ route('informes.actualizarComentario') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    cod_informe: codInforme,
                    cod_sala: codSala,
                    comentarios: comentarios
                })
            })
                .then(response => {
                    console.log('📡 Respuesta del servidor:', response.status);
                    if (!response.ok) {
                        throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('✅ Datos recibidos:', data);

                    if (data.success) {
                        // Mostrar estado guardado
                        estadoSpan.innerHTML = '<i data-lucide="check-circle" class="h-4 w-4 inline text-green-600"></i> <span class="text-green-600">Guardado</span>';
                        estadoSpan.classList.add('show');
                        lucide.createIcons();

                        console.log(`✅ Comentario guardado exitosamente - Sala ${codSala} a las ${data.timestamp || 'N/A'}`);

                        // Ocultar después de 3 segundos
                        setTimeout(() => {
                            estadoSpan.classList.remove('show');
                        }, 3000);
                    } else {
                        throw new Error(data.error || 'Error desconocido del servidor');
                    }
                })
                .catch(error => {
                    console.error('❌ Error guardando comentario:', error);
                    estadoSpan.innerHTML = '<span class="text-red-500">❌ Error: ' + (error.message || 'Error de conexión') + '</span>';

                    // Mantener visible el error por más tiempo
                    setTimeout(() => {
                        estadoSpan.classList.remove('show');
                    }, 5000);
                });
        }

        /**
         * Subir fotos (AJAX) - Versión mejorada
         */
        function subirFotos(archivos) {
            // Verificaciones de seguridad
            if (!modoEdicion || estadoInforme !== 0) {
                console.log('🚫 Subida de fotos bloqueada: No está en modo edición o el informe está finalizado');
                return;
            }

            if (!codInforme) {
                console.error('❌ Error: No hay código de informe disponible');
                return;
            }

            console.log(`📸 Iniciando subida de ${archivos.length} foto(s)`);

            const uploadStatus = document.getElementById('upload-status');
            const contador = document.getElementById('contador-fotos');

            if (uploadStatus) {
                uploadStatus.classList.remove('hidden');
                uploadStatus.innerHTML = '<span class="text-blue-700">📤 Subiendo ' + archivos.length + ' foto(s)...</span>';
            }

            // Validar archivos antes de subir
            const archivosValidos = Array.from(archivos).filter(archivo => {
                const esImagen = archivo.type.startsWith('image/');
                const tamañoValido = archivo.size <= 5 * 1024 * 1024; // 5MB max

                if (!esImagen) {
                    console.warn(`⚠️ Archivo rechazado (no es imagen): ${archivo.name}`);
                    return false;
                }

                if (!tamañoValido) {
                    console.warn(`⚠️ Archivo rechazado (muy grande): ${archivo.name} (${(archivo.size / 1024 / 1024).toFixed(2)}MB)`);
                    return false;
                }

                return true;
            });

            if (archivosValidos.length === 0) {
                if (uploadStatus) uploadStatus.classList.add('hidden');
                Swal.fire('Error', 'No hay archivos válidos para subir. Solo se permiten imágenes menores a 5MB.', 'error');
                return;
            }

            if (archivosValidos.length !== archivos.length) {
                Swal.fire('Atención', `Se subirán solo ${archivosValidos.length} de ${archivos.length} archivos (algunos fueron rechazados)`, 'warning');
            }

            // Subir una por una con logs detallados
            const promesas = archivosValidos.map((archivo, index) => {
                console.log(`📤 Subiendo archivo ${index + 1}/${archivosValidos.length}: ${archivo.name}`);
                return subirFotoIndividual(archivo, index + 1, archivosValidos.length);
            });

            Promise.all(promesas)
                .then((resultados) => {
                    console.log(`✅ ${resultados.length} foto(s) subida(s) exitosamente`);
                    if (uploadStatus) {
                        uploadStatus.innerHTML = '<span class="text-green-600">✅ ' + resultados.length + ' foto(s) subida(s) correctamente</span>';
                        setTimeout(() => {
                            uploadStatus.classList.add('hidden');
                        }, 3000);
                    }
                    actualizarContadorFotos();
                })
                .catch(error => {
                    console.error('❌ Error subiendo fotos:', error);
                    if (uploadStatus) {
                        uploadStatus.innerHTML = '<span class="text-red-600">❌ Error al subir fotos</span>';
                        setTimeout(() => {
                            uploadStatus.classList.add('hidden');
                        }, 5000);
                    }
                    Swal.fire('Error', 'Error al subir algunas fotos. Revisa la consola para más detalles.', 'error');
                });
        }

        function subirFotoIndividual(archivo, numeroActual, total) {
            return new Promise((resolve, reject) => {
                console.log(`🔄 Procesando: ${archivo.name} (${(archivo.size / 1024 / 1024).toFixed(2)}MB)`);

                const formData = new FormData();
                formData.append('foto', archivo);
                formData.append('cod_informe', codInforme);

                fetch('{{ route('informes.subirFoto') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: formData
                })
                    .then(response => {
                        console.log(`📡 Respuesta del servidor para ${archivo.name}:`, response.status);
                        if (!response.ok) {
                            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log(`✅ Foto subida exitosamente: ${archivo.name}`, data);

                        if (data.success) {
                            agregarFotoAGrid(data.foto);
                            resolve(data);
                        } else {
                            throw new Error(data.error || 'Error desconocido del servidor');
                        }
                    })
                    .catch(error => {
                        console.error(`❌ Error subiendo ${archivo.name}:`, error);
                        reject(error);
                    });
            });
        }

        function agregarFotoAGrid(foto) {
            console.log('📷 Agregando foto al grid:', foto);

            const grid = document.getElementById('fotos-grid');
            if (!grid) {
                console.error('❌ Grid de fotos no encontrado');
                return;
            }

            // Verificar que tenemos todos los datos necesarios
            if (!foto.id) {
                console.error('❌ Error: foto.id es undefined:', foto);
                return;
            }

            const fotoDiv = document.createElement('div');
            fotoDiv.className = 'foto-thumbnail';
            fotoDiv.setAttribute('data-foto-id', foto.id);

            console.log(`✅ Creando elemento para foto ID: ${foto.id}`);

            fotoDiv.innerHTML = `
                                                                                    <img src="${foto.url}" alt="${foto.nombre_original}"
                                                                                        class="w-full h-32 object-cover rounded-lg border border-gray-200 cursor-pointer"
                                                                                        onclick="ampliarFoto('${foto.url}', '${foto.nombre_original}', '${foto.comentario || ''}')"
                                                                                        onerror="console.error('❌ Error cargando imagen:', this.src)">
                                                                                                            <button onclick="eliminarFoto(${foto.id})" class="foto-eliminar" title="Eliminar foto">×</button>
                                                                                                            <div class="p-2 space-y-1">
                                                                                                                <p class="text-xs text-gray-500 truncate" title="${foto.nombre_original}">${foto.nombre_original}</p>
                                                                                                                <p class="comentario-foto text-xs text-blue-600 cursor-pointer hover:text-blue-800" 
                                                                                                                   onclick="editarComentarioFoto(${foto.id})"
                                                                                                                   title="Click para editar comentario">
                                                                                                                    ${foto.comentario || 'Agregar comentario...'}
                                                                                                                </p>
                                                                                                                <p class="text-xs text-gray-400">${foto.fecha_subida}</p>
                                                                                                            </div>
                                                                                                        `;

            // NUEVO: Guardar comentario en el data attribute
            fotoDiv.setAttribute('data-comentario', foto.comentario || '');

            grid.appendChild(fotoDiv);
            console.log(`✅ Foto ID ${foto.id} agregada al grid exitosamente`);
        }

        /**
         * Eliminar foto (AJAX)
         - Versión mejorada
             */
        function eliminarFoto(idFoto) {
            // Verificaciones de seguridad
            if (!modoEdicion || estadoInforme !== 0) {
                console.log('🚫 Eliminación de foto bloqueada: No está en modo edición o el informe está finalizado');
                return;
            }

            console.log(`🗑️ Solicitando eliminación de foto ID: ${idFoto}`);

            Swal.fire({
                title: '¿Eliminar foto?',
                text: 'Esta acción no se puede deshacer',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280'
            }).then((result) => {
                if (result.isConfirmed) {
                    console.log(`🔄 Procediendo a eliminar foto ID: ${idFoto}`);

                    // Mostrar indicador visual en la foto mientras se elimina
                    const fotoElement = document.querySelector(`[data-foto-id="${idFoto}"]`);
                    if (fotoElement) {
                        fotoElement.style.opacity = '0.5';
                        fotoElement.style.filter = 'grayscale(100%)';

                        // Agregar overlay de eliminando
                        const overlay = document.createElement('div');
                        overlay.style.position = 'absolute';
                        overlay.style.top = '0';
                        overlay.style.left = '0';
                        overlay.style.right = '0';
                        overlay.style.bottom = '0';
                        overlay.style.backgroundColor = 'rgba(239, 68, 68, 0.8)';
                        overlay.style.display = 'flex';
                        overlay.style.alignItems = 'center';
                        overlay.style.justifyContent = 'center';
                        overlay.style.color = 'white';
                        overlay.style.fontSize = '12px';
                        overlay.style.borderRadius = '8px';
                        overlay.innerHTML = '🗑️ Eliminando...';

                        fotoElement.style.position = 'relative';
                        fotoElement.appendChild(overlay);
                    }

                    fetch('{{ route('informes.eliminarFoto') }}', {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ id_foto: idFoto })
                    })
                        .then(response => {
                            console.log(`📡 Respuesta del servidor para eliminación:`, response.status);
                            if (!response.ok) {
                                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                            }
                            return response.json();
                        })
                        .then(data => {
                            console.log(`✅ Respuesta de eliminación:`, data);

                            if (data.success) {
                                // Eliminar del DOM con animación
                                if (fotoElement) {
                                    fotoElement.style.transform = 'scale(0)';
                                    fotoElement.style.transition = 'all 0.3s ease';

                                    setTimeout(() => {
                                        fotoElement.remove();
                                        actualizarContadorFotos();
                                        console.log(`✅ Foto ID ${idFoto} eliminada exitosamente del DOM`);
                                    }, 300);
                                }

                                Swal.fire({
                                    title: '¡Eliminada!',
                                    text: 'Foto eliminada correctamente',
                                    icon: 'success',
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                            } else {
                                throw new Error(data.error || 'Error desconocido del servidor');
                            }
                        })
                        .catch(error => {
                            console.error(`❌ Error eliminando foto ID ${idFoto}:`, error);

                            // Restaurar apariencia original en caso de error
                            if (fotoElement) {
                                fotoElement.style.opacity = '1';
                                fotoElement.style.filter = 'none';
                                const overlay = fotoElement.querySelector('div[style*="rgba(239, 68, 68"]');
                                if (overlay) overlay.remove();
                            }

                            Swal.fire({
                                title: 'Error',
                                text: error.message || 'Error al eliminar foto',
                                icon: 'error',
                                confirmButtonText: 'Entendido'
                            });
                        });
                } else {
                    console.log(`❌ Eliminación de foto ID ${idFoto} cancelada por el usuario`);
                }
            });
        }

        function actualizarContadorFotos() {
            const contador = document.getElementById('contador-fotos');
            if (!contador) return;

            const fotos = document.querySelectorAll('.foto-thumbnail').length;
            const textoAnterior = contador.textContent;

            contador.textContent = `${fotos} foto${fotos !== 1 ? 's' : ''}`;

            // Agregar feedback visual cuando cambie el contador
            if (textoAnterior !== contador.textContent) {
                contador.classList.add('contador-actualizado');
                console.log(`📊 Contador de fotos actualizado: ${fotos} foto(s)`);

                // Quitar el estilo después de 2 segundos
                setTimeout(() => {
                    contador.classList.remove('contador-actualizado');
                }, 2000);
            }
        }

        /**
         * Finalizar informe (cambiar estado a completado)
         */
        function finalizarInforme() {
            if (!modoEdicion || estadoInforme !== 0) return;

            Swal.fire({
                title: '¿Finalizar informe?',
                text: 'Una vez finalizado no podrás editarlo más',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sí, finalizar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('{{ route('informes.finalizar') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ cod_informe: codInforme })
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    title: '¡Informe finalizado!',
                                    text: 'El informe ha sido completado exitosamente',
                                    icon: 'success'
                                }).then(() => {
                                    if (data.redirect_url) {
                                        window.location.href = data.redirect_url;
                                    } else {
                                        location.reload();
                                    }
                                });
                            } else {
                                Swal.fire('Error', data.error, 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Error finalizando informe:', error);
                            Swal.fire('Error', 'Error al finalizar informe', 'error');
                        });
                }
            });
        }

        // ===== EVENTOS DRAG & DROP PARA FOTOS =====

        // Inicializar eventos drag & drop
        function inicializarDragDrop() {
            const dropZone = document.getElementById('drag-drop-zone');
            const fileInput = document.getElementById('file-input');

            if (!dropZone || !fileInput) return;

            // Eventos drag and drop
            dropZone.addEventListener('dragover', (e) => {
                e.preventDefault();
                dropZone.classList.add('dragover');
            });

            dropZone.addEventListener('dragleave', (e) => {
                e.preventDefault();
                dropZone.classList.remove('dragover');
            });

            dropZone.addEventListener('drop', (e) => {
                e.preventDefault();
                dropZone.classList.remove('dragover');

                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    subirFotos(files);
                }
            });

            // Click en zona de drop
            dropZone.addEventListener('click', () => {
                fileInput.click();
            });

            // Cambio en input de archivo
            fileInput.addEventListener('change', (e) => {
                if (e.target.files.length > 0) {
                    subirFotos(e.target.files);
                }
            });
        }

        // ===== FUNCIONES DE PRUEBA =====

        /**
         * Función de prueba para verificar el guardado automático
         * Ejecuta desde la consola: probarGuardadoAutomatico()
         */
        function probarGuardadoAutomatico() {
            console.log('🧪 === PRUEBA DE GUARDADO AUTOMÁTICO ===');
            console.log('📊 Estado del sistema:');
            console.log('   - Modo edición:', modoEdicion);
            console.log('   - Estado informe:', estadoInforme);
            console.log('   - Código informe:', codInforme);

            const textareas = document.querySelectorAll('textarea[id^="comentario_"]');
            console.log(`   - Textareas encontrados: ${textareas.length}`);

            if (textareas.length === 0) {
                console.warn('⚠️ No se encontraron textareas de comentarios');
                return;
            }

            // Probar con el primer textarea encontrado
            const primeraTextarea = textareas[0];
            const codSala = primeraTextarea.getAttribute('data-cod-sala');

            console.log(`🎯 Probando con sala: ${codSala}`);

            // Agregar texto de prueba
            const textoOriginal = primeraTextarea.value;
            const textoPrueba = `Prueba de guardado automático - ${new Date().toLocaleTimeString()}`;

            primeraTextarea.value = textoPrueba;
            console.log('✏️ Texto de prueba agregado:', textoPrueba);

            // Simular evento blur (salir del campo)
            console.log('🔄 Ejecutando guardado...');
            guardarComentario(parseInt(codSala));

            // Restaurar texto original después de un momento
            setTimeout(() => {
                primeraTextarea.value = textoOriginal;
                console.log('🔄 Texto original restaurado');
                console.log('✅ Prueba completada. Revisa los mensajes de la consola y el feedback visual.');
            }, 5000);
        }

        /**
         * Función para probar todas las salas
         */
        function probarTodasLasSalas() {
            console.log('🧪 === PRUEBA COMPLETA - TODAS LAS SALAS ===');

            const textareas = document.querySelectorAll('textarea[id^="comentario_"]');

            textareas.forEach((textarea, index) => {
                const codSala = textarea.getAttribute('data-cod-sala');
                console.log(`🎯 Probando sala ${codSala} (${index + 1}/${textareas.length})`);

                setTimeout(() => {
                    const textoOriginal = textarea.value;
                    textarea.value = `Prueba automatizada sala ${codSala} - ${new Date().toLocaleTimeString()}`;
                    guardarComentario(parseInt(codSala));

                    // Restaurar después de 3 segundos
                    setTimeout(() => {
                        textarea.value = textoOriginal;
                    }, 3000);
                }, index * 1000); // Escalonar las pruebas
            });
        }

        // ===== INICIALIZACIÓN =====

        // Inicializar Lucide Icons
        document.addEventListener('DOMContentLoaded', function () {
            lucide.createIcons();

            // Configurar indicadores visuales de guardado automático
            if (modoEdicion && estadoInforme === 0) {
                configurarIndicadoresVisuales();

                // Inicializar sistema de fotos automáticamente
                inicializarDragDrop();
                console.log('📸 Sistema de fotos drag & drop ACTIVADO');
            }

            // NUEVO: Configurar contador de caracteres para comentarios de fotos
            const textarea = document.getElementById('comentario-textarea');
            const contador = document.getElementById('contador-caracteres');

            if (textarea && contador) {
                textarea.addEventListener('input', function () {
                    const longitud = this.value.length;
                    contador.textContent = `${longitud}/500`;

                    // Cambiar color si se acerca al límite
                    if (longitud > 450) {
                        contador.classList.add('text-red-500');
                        contador.classList.remove('text-gray-500');
                    } else {
                        contador.classList.add('text-gray-500');
                        contador.classList.remove('text-red-500');
                    }
                });
            }

            // Mensaje informativo para el desarrollador
            if (modoEdicion && estadoInforme === 0) {
                console.log('🚀 Sistema de comentarios automáticos ACTIVO');
                console.log('📸 Sistema de fotos drag & drop ACTIVO');
                console.log('🧪 Funciones de prueba disponibles:');
                console.log('   - probarGuardadoAutomatico() : Prueba comentarios en una sala');
                console.log('   - probarTodasLasSalas() : Prueba comentarios en todas las salas');
                console.log('   - probarSistemaFotos() : Prueba el sistema de fotos');
                console.log('   - mostrarEstadoSistema() : Muestra información del sistema');
                console.log('💡 Abre las DevTools (F12) para ver los logs detallados');
            }
        });

        /**
         * Función de prueba para el sistema de fotos
         */
        function probarSistemaFotos() {
            console.log('🧪 === PRUEBA DEL SISTEMA DE FOTOS ===');
            console.log('📊 Estado del sistema:');
            console.log('   - Modo edición:', modoEdicion);
            console.log('   - Estado informe:', estadoInforme);
            console.log('   - Código informe:', codInforme);

            const dropZone = document.getElementById('drag-drop-zone');
            const fileInput = document.getElementById('file-input');
            const fotosGrid = document.getElementById('fotos-grid');
            const uploadStatus = document.getElementById('upload-status');
            const contador = document.getElementById('contador-fotos');

            console.log('📋 Elementos encontrados:');
            console.log('   - Zona de drop:', dropZone ? '✅ Presente' : '❌ No encontrada');
            console.log('   - Input de archivo:', fileInput ? '✅ Presente' : '❌ No encontrado');
            console.log('   - Grid de fotos:', fotosGrid ? '✅ Presente' : '❌ No encontrado');
            console.log('   - Status de subida:', uploadStatus ? '✅ Presente' : '❌ No encontrado');
            console.log('   - Contador:', contador ? '✅ Presente' : '❌ No encontrado');

            const fotosActuales = document.querySelectorAll('.foto-thumbnail');
            console.log(`   - Fotos actuales en grid: ${fotosActuales.length}`);

            if (fileInput) {
                console.log('🎯 Para probar la subida de fotos:');
                console.log('   1. Arrastra imágenes al área de drop');
                console.log('   2. O haz click en el botón "Seleccionar Fotos"');
                console.log('   3. Observa los logs en la consola durante la subida');

                // Simular click en el botón para abrir selector
                console.log('🔄 Simulando apertura del selector de archivos...');
                // fileInput.click(); // Comentado para no abrir realmente el diálogo
                console.log('✅ Prueba de elementos completada. El sistema está listo para recibir fotos.');
            } else {
                console.warn('⚠️ No se puede probar: Input de archivo no encontrado');
            }
        }

        /**
         * Función para mostrar estado completo del sistema
         */
        function mostrarEstadoSistema() {
            console.log('🔍 === ESTADO COMPLETO DEL SISTEMA ===');
            console.log('📊 Variables principales:');
            console.log('   - codInforme:', codInforme);
            console.log('   - modoEdicion:', modoEdicion);
            console.log('   - estadoInforme:', estadoInforme);

            console.log('💬 Sistema de comentarios:');
            const textareas = document.querySelectorAll('textarea[id^="comentario_"]');
            console.log(`   - Textareas de comentarios: ${textareas.length}`);

            textareas.forEach((textarea, index) => {
                const codSala = textarea.getAttribute('data-cod-sala');
                const estadoSpan = document.getElementById(`estado_${codSala}`);
                console.log(`     Sala ${codSala}: ${textarea.value.length} caracteres, estado: ${estadoSpan ? 'presente' : 'faltante'}`);
            });

            console.log('📸 Sistema de fotos:');
            const fotosActuales = document.querySelectorAll('.foto-thumbnail');
            console.log(`   - Fotos en grid: ${fotosActuales.length}`);

            const dropZone = document.getElementById('drag-drop-zone');
            const fileInput = document.getElementById('file-input');
            console.log(`   - Drop zone: ${dropZone ? 'activa' : 'inactiva'}`);
            console.log(`   - File input: ${fileInput ? 'presente' : 'ausente'}`);

            console.log('🔗 Rutas configuradas:');
            console.log('   - Actualizar comentario: {{ route('informes.actualizarComentario') }}');
            console.log('   - Subir foto: {{ route('informes.subirFoto') }}');
            console.log('   - Eliminar foto: {{ route('informes.eliminarFoto') }}');
            console.log('   - Finalizar: {{ route('informes.finalizar') }}');

        }

        async function guardarInforme() {
            try {
                // Confirmar antes de guardar
                const confirmacion = await Swal.fire({
                    title: '💾 ¿Guardar Informe?',
                    text: `Se creará el informe para ${fechaTurno} - Turno ${codTurno}`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, Guardar',
                    cancelButtonText: 'Cancelar',
                    confirmButtonColor: '#3b82f6',
                    cancelButtonColor: '#6b7280'
                });

                if (!confirmacion.isConfirmed) {
                    return;
                }

                // Mostrar loading
                Swal.fire({
                    title: 'Guardando Informe...',
                    text: 'Por favor espere mientras se procesa la información',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Recolectar datos del formulario
                const datosInforme = recolectarDatosFormulario();

                // Validar datos básicos
                if (!validarDatos(datosInforme)) {
                    return;
                }

                // Enviar datos al servidor
                const response = await fetch('{{ route('informes.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(datosInforme)
                });

                const resultado = await response.json();

                if (response.ok && resultado.status === 'success') {
                    // Éxito
                    await Swal.fire({
                        title: '✅ ¡Informe Guardado!',
                        text: 'El informe se ha guardado correctamente',
                        icon: 'success',
                        confirmButtonText: 'Ver Mis Informes'
                    });

                    // Redirigir a mis informes
                    window.location.href = '{{ route('mis-informes') }}';
                } else {
                    // Error del servidor
                    Swal.fire({
                        title: '❌ Error al Guardar',
                        text: resultado.message || 'Ocurrió un error inesperado',
                        icon: 'error',
                        confirmButtonText: 'Intentar de Nuevo'
                    });
                }

            } catch (error) {
                console.error('Error al guardar informe:', error);
                Swal.fire({
                    title: '❌ Error de Conexión',
                    text: 'No se pudo conectar con el servidor. Verifique su conexión.',
                    icon: 'error',
                    confirmButtonText: 'Reintentar'
                });
            }
        }

        function recolectarDatosFormulario() {
            // Estructura simplificada para solo crear el registro base del informe
            return {
                fecha_turno: fechaTurno,
                cod_turno: codTurno,
                cod_jefe_turno: codJefeTurno,
                comentarios: '', // Se agregarán después via AJAX
                d_real_empaque: 0, // Se calculará automáticamente
                d_esperada_empaque: 0,
                horas_trabajadas_empaque: 0,
                tiempo_muerto_empaque: 0,
                productividad_empaque: 0,
                estado: 0 // Borrador
            };
        }

        function validarDatos(datos) {
            if (!datos.fecha_turno || !datos.cod_turno || !datos.cod_jefe_turno) {
                Swal.fire({
                    title: '❌ Datos Incompletos',
                    text: 'Faltan datos básicos del informe (fecha, turno, jefe)',
                    icon: 'error'
                });
                return false;
            }
            return true;
        }

        function encontrarCodigoSala(nombreSala) {
            // Buscar en los datos de información de sala
            const sala = informacionSala.find(s => s.nombre_sala === nombreSala);
            return sala ? sala.cod_sala : null;
        }

        // === INICIALIZACIÓN AUTOMÁTICA DE CARDS ===

        function esperarYInicializar() {
            // Verificar que los datos estén disponibles
            if (typeof planillasDetalle !== 'undefined' &&
                typeof detalleProcesamientoPorEmpresa !== 'undefined' &&
                planillasDetalle.length >= 0 &&
                detalleProcesamientoPorEmpresa.length >= 0) {

                console.log('🔄 Inicializando cards con datos reales...');
                console.log('📋 Planillas disponibles:', planillasDetalle.length);
                console.log('📦 Productos disponibles:', detalleProcesamientoPorEmpresa.length);

                inicializarTodosLosCards();
                console.log('✅ Cards inicializados con éxito');
            } else {
                console.log('⏳ Esperando datos... Reintentando en 200ms');
                setTimeout(esperarYInicializar, 200);
            }
        }

        // Ejecutar en diferentes momentos para asegurar carga
        document.addEventListener('DOMContentLoaded', esperarYInicializar);
        window.addEventListener('load', function () {
            setTimeout(esperarYInicializar, 100);
        });

        // También ejecutar después de que Lucide inicialice los iconos
        setTimeout(esperarYInicializar, 500);
        setTimeout(esperarYInicializar, 1500); // Backup para casos lentos

        // === FUNCIONES PARA MODALES (las existentes) ===

        function mostrarModal(tipo, empresa, codSala, codTipoPlanilla) {
            const modal = document.getElementById('modal');
            const modalTitle = document.getElementById('modal-title');
            const modalContent = document.getElementById('modal-content');

            let titulo, contenido;

            switch (tipo) {
                case 'planillas':
                    titulo = `📋 Planillas - ${empresa}`;
                    contenido = generarContenidoPlanillas(empresa, codSala, codTipoPlanilla);
                    break;
                case 'productos':
                    titulo = `📦 Productos - ${empresa}`;
                    contenido = generarContenidoProductos(empresa, codSala, codTipoPlanilla);
                    break;
                case 'tiempos':
                    titulo = `⏰ Tiempos Muertos - ${empresa}`;
                    contenido = generarContenidoTiemposMuertos(empresa, codSala, codTipoPlanilla);
                    break;
            }

            modalTitle.textContent = titulo;
            modalContent.innerHTML = contenido;

            // Regenerar iconos de Lucide en el contenido del modal
            lucide.createIcons();

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function generarContenidoPlanillas(empresa, codSala, codTipoPlanilla) {
            // Filtrar planillas por empresa, sala y tipo
            const planillasFiltradas = planillasDetalle.filter(item => {
                return item.descripcion && item.descripcion.trim() === empresa.trim() &&
                    item.cod_sala == codSala &&
                    item.cod_tipo_planilla == codTipoPlanilla;
            });

            if (planillasFiltradas.length === 0) {
                return `
                                                                                                                                                                                                                                                                                                                                                                    <div class="text-center text-gray-500 py-8">
                                                                                                                                                                                                                                                                                                                                                                        <i data-lucide="file-x" class="h-16 w-16 mx-auto mb-4 text-gray-400"></i>
                                                                                                                                                                                                                                                                                                                                                                        <h3 class="text-lg font-semibold mb-2">Sin Planillas</h3>
                                                                                                                                                                                                                                                                                                                                                                        <p>No se encontraron planillas para esta empresa, sala y proceso.</p>
                                                                                                                                                                                                                                                                                                                                                                    </div>
                                                                                                                                                                                                                                                                                                                                                                `;
            }

            let tabla = `
                                                                                                                                                                                                                                                                                                                                                                <div class="overflow-x-auto">
                                                                                                                                                                                                                                                                                                                                                                    <table class="w-full border-collapse border border-gray-300">
                                                                                                                                                                                                                                                                                                                                                                        <thead class="bg-gray-50">
                                                                                                                                                                                                                                                                                                                                                                            <tr>
                                                                                                                                                                                                                                                                                                                                                                                <th class="border border-gray-300 px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Planilla</th>
                                                                                                                                                                                                                                                                                                                                                                                <th class="border border-gray-300 px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Trabajador</th>
                                                                                                                                                                                                                                                                                                                                                                                <th class="border border-gray-300 px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Dotación</th>
                                                                                                                                                                                                                                                                                                                                                                                <th class="border border-gray-300 px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Horas Trabajadas</th>
                                                                                                                                                                                                                                                                                                                                                                                <th class="border border-gray-300 px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Kg Entrega</th>
                                                                                                                                                                                                                                                                                                                                                                                <th class="border border-gray-300 px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">PST Total</th>
                                                                                                                                                                                                                                                                                                                                                                            </tr>
                                                                                                                                                                                                                                                                                                                                                                        </thead>
                                                                                                                                                                                                                                                                                                                                                                        <tbody>
                                                                                                                                                                                                                                                                                                                                                                    `;

            planillasFiltradas.forEach(planilla => {
                const horasTrabajadas = planilla.horas_trabajadas ? Number(planilla.horas_trabajadas).toFixed(1) : '0.0';
                const kilosEntrega = planilla.kilos_entrega ? Number(planilla.kilos_entrega).toFixed(2) : '0.00';
                const pstTotal = planilla.pst_total ? Number(planilla.pst_total).toFixed(2) : '0.00';
                const dotacion = planilla.dotacion ? Number(planilla.dotacion) : 0;
                const trabajador = planilla.trabajador_nombre || 'Sin asignar';
                const empresa = planilla.descripcion || 'N/A';

                tabla += `
                                                                                                                                                                                                                                                                                                                                                                <tr class="hover:bg-gray-50 cursor-pointer" onclick="abrirModalDetallePlanilla(${planilla.numero_planilla})" title="Clic para ver detalle de la planilla">
                                                                                                                                                                                                                                                                                                                                                                    <td class="border border-gray-300 px-4 py-2 font-medium">#${planilla.numero_planilla}</td>
                                                                                                                                                                                                                                                                                                                                                                    <td class="border border-gray-300 px-4 py-2">${trabajador}</td>
                                                                                                                                                                                                                                                                                                                                                                    <td class="border border-gray-300 px-4 py-2 text-center font-medium text-orange-700">${dotacion}</td>
                                                                                                                                                                                                                                                                                                                                                                    <td class="border border-gray-300 px-4 py-2 text-right font-medium text-purple-700">${horasTrabajadas}h</td>
                                                                                                                                                                                                                                                                                                                                                                    <td class="border border-gray-300 px-4 py-2 text-right font-medium text-blue-700">${kilosEntrega} kg</td>
                                                                                                                                                                                                                                                                                                                                                                    <td class="border border-gray-300 px-4 py-2 text-right font-medium text-green-700">${pstTotal} kg</td>
                                                                                                                                                                                                                                                                                                                                                                </tr>
                                                                                                                                                                                                                                                                                                                                                            `;
            });

            const totalHoras = planillasFiltradas.reduce((sum, p) => sum + (Number(p.horas_trabajadas) || 0), 0);
            const totalEntrega = planillasFiltradas.reduce((sum, p) => sum + (Number(p.kilos_entrega) || 0), 0);
            const totalPst = planillasFiltradas.reduce((sum, p) => sum + (Number(p.pst_total) || 0), 0);
            const maxDotacion = planillasFiltradas.reduce((max, p) => Math.max(max, Number(p.dotacion) || 0), 0);

            tabla += `
                                                                                                                                                                                                                                                                                                                                                                </tbody>
                                                                                                                                                                                                                                                                                                                                                            </table>
                                                                                                                                                                                                                                                                                                                                                        </div>
                                                                                                                                                                                                                                                                                                                                                        <div class="mt-4 text-sm text-gray-600 bg-gray-50 p-4 rounded">
                                                                                                                                                                                                                                                                                                                                                            <div class="grid grid-cols-4 gap-4">
                                                                                                                                                                                                                                                                                                                                                                <div>
                                                                                                                                                                                                                                                                                                                                                                    <p><strong>Total planillas:</strong> ${planillasFiltradas.length}</p>
                                                                                                                                                                                                                                                                                                                                                                    <p><strong>Dotación total:</strong> <span class="text-orange-700 font-bold">${maxDotacion}</span></p>
                                                                                                                                                                                                                                                                                                                                                                </div>
                                                                                                                                                                                                                                                                                                                                                                <div>
                                                                                                                                                                                                                                                                                                                                                                <p><strong>Total horas:</strong> ${totalHoras.toFixed(1)}h</p>
                                                                                                                                                                                                                                                                                                                                                                </div>
                                                                                                                                                                                                                                                                                                                                                            <div>
                                                                                                                                                                                                                                                                                                                                                                <p><strong>Total entrega:</strong> ${totalEntrega.toFixed(2)} kg</p>
                                                                                                                                                                                                                                                                                                                                                            </div>
                                                                                                                                                                                                                                                                                                                                                            <div>
                                                                                                                                                                                                                                                                                                                                                                <p><strong>Total PST:</strong> ${totalPst.toFixed(2)} kg</p>
                                                                                                                                                                                                                                                                                                                                                            </div>
                                                                                                                                                                                                                                                                                                                                                            </div>
                                                                                                                                                                                                                                                                                                                                                        </div>
                                                                                                                                                                                                                                                                                                                                                    `;

            return tabla;
        }

        function generarContenidoProductos(empresa, codSala, codTipoPlanilla) {
            // Filtrar productos por empresa, sala y tipo
            const productosFiltrados = detalleProcesamientoPorEmpresa.filter(item => {
                return item.descripcion && item.descripcion.trim() === empresa.trim() &&
                    item.cod_sala == codSala &&
                    item.cod_tipo_planilla == codTipoPlanilla;
            });

            if (productosFiltrados.length === 0) {
                return `
                                                                                                                                                                                                                                                                                                                                                                    <div class="text-center text-gray-500 py-8">
                                                                                                                                                                                                                                                                                                                                                                        <i data-lucide="package-x" class="h-16 w-16 mx-auto mb-4 text-gray-400"></i>
                                                                                                                                                                                                                                                                                                                                                                        <h3 class="text-lg font-semibold mb-2">Sin Productos</h3>
                                                                                                                                                                                                                                                                                                                                                                        <p>No se encontraron productos para esta empresa, sala y proceso.</p>
                                                                                                                                                                                                                                                                                                                                                                    </div>
                                                                                                                                                                                                                                                                                                                                                                `;
            }

            let tabla = `
                                                                                                                                                                                                                                                                                                                                                                <div class="overflow-x-auto">
                                                                                                                                                                                                                                                                                                                                                                    <table class="w-full border-collapse border border-gray-300">
                                                                                                                                                                                                                                                                                                                                                                        <thead class="bg-gray-50">
                                                                                                                                                                                                                                                                                                                                                                            <tr>
                                                                                                                                                                                                                                                                                                                                                                                <th class="border border-gray-300 px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Especie</th>
                                                                                                                                                                                                                                                                                                                                                                                <th class="border border-gray-300 px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Producto</th>
                                                                                                                                                                                                                                                                                                                                                                                <th class="border border-gray-300 px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Calidad</th>
                                                                                                                                                                                                                                                                                                                                                                                <th class="border border-gray-300 px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Destino</th>
                                                                                                                                                                                                                                                                                                                                                                                <th class="border border-gray-300 px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Kg</th>
                                                                                                                                                                                                                                                                                                                                                                                <th class="border border-gray-300 px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">%</th>
                                                                                                                                                                                                                                                                                                                                                                                <th class="border border-gray-300 px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Objetivo</th>
                                                                                                                                                                                                                                                                                                                                                                            </tr>
                                                                                                                                                                                                                                                                                                                                                                        </thead>
                                                                                                                                                                                                                                                                                                                                                                        <tbody>
                                                                                                                                                                                                                                                                                                                                                                    `;

            const totalKilos = productosFiltrados.reduce((sum, p) => sum + Number(p.kilos), 0);

            productosFiltrados.forEach(producto => {
                const porcentaje = totalKilos > 0 ? ((Number(producto.kilos) / totalKilos) * 100).toFixed(1) : 0;
                const esCalidadPremium = producto.calidad === 'PREMIUM';
                // REVERTIDO: Nombre del producto sin especie
                const nombreProducto = `${producto.corte_inicial} → ${producto.corte_final}` +
                    (producto.calibre && producto.calibre !== 'SIN CALIBRE' ? ` → ${producto.calibre}` : '');
                const esObjetivo = producto.es_producto_objetivo == 1;

                tabla += `
                                                                                                                                                                                                                                                                                                                                                        <tr class="hover:bg-gray-50">
                                                                                                                                                                                                                                                                                                                                                            <td class="border border-gray-300 px-4 py-2">
                                                                                                                                                                                                                                                                                                                                                                <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded text-sm font-medium">
                                                                                                                                                                                                                                                                                                                                                                    ${producto.especie || 'Sin especie'}
                                                                                                                                                                                                                                                                                                                                                                </span>
                                                                                                                                                                                                                                                                                                                                                            </td>
                                                                                                                                                                                                                                                                                                                                                            <td class="border border-gray-300 px-4 py-2 font-medium">${nombreProducto}</td>
                                                                                                                                                                                                                                                                                                                                                            <td class="border border-gray-300 px-4 py-2">
                                                                                                                                                                                                                                                                                                                                                                <span class="px-2 py-1 ${esCalidadPremium ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800'} rounded text-sm">
                                                                                                                                                                                                                                                                                                                                                                    ${producto.calidad}
                                                                                                                                                                                                                                                                                                                                                                </span>
                                                                                                                                                                                                                                                                                                                                                            </td>
                                                                                                                                                                                                                                                                                                                                                            <td class="border border-gray-300 px-4 py-2">${producto.destino}</td>
                                                                                                                                                                                                                                                                                                                                                            <td class="border border-gray-300 px-4 py-2 text-right font-medium">${Number(producto.kilos).toFixed(2)} kg</td>
                                                                                                                                                                                                                                                                                                                                                            <td class="border border-gray-300 px-4 py-2 text-right">${porcentaje}%</td>
                                                                                                                                                                                                                                                                                                                                                            <td class="border border-gray-300 px-4 py-2 text-center">
                                                                                                                                                                                                                                                                                                                                                                <span class="px-2 py-1 ${esObjetivo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'} rounded text-sm font-medium">
                                                                                                                                                                                                                                                                                                                                                                    ${esObjetivo ? 'SÍ' : 'NO'}
                                                                                                                                                                                                                                                                                                                                                                </span>
                                                                                                                                                                                                                                                                                                                                                            </td>
                                                                                                                                                                                                                                                                                                                                                        </tr>
                                                                                                                                                                                                                                                                                                                                                    `;
            });

            const productosObjetivo = productosFiltrados.filter(p => p.es_producto_objetivo == 1);
            const kilosObjetivo = productosObjetivo.reduce((sum, p) => sum + Number(p.kilos), 0);

            tabla += `
                                                                                                                                                                                                                                                                                                                                                                </tbody>
                                                                                                                                                                                                                                                                                                                                                            </table>
                                                                                                                                                                                                                                                                                                                                                        </div>
                                                                                                                                                                                                                                                                                                                                                        <div class="mt-4 text-sm text-gray-600 bg-gray-50 p-4 rounded">
                                                                                                                                                                                                                                                                                                                                                            <div class="grid grid-cols-2 gap-4">
                                                                                                                                                                                                                                                                                                                                                                <div>
                                                                                                                                                                                                                                                                                                                                                                    <p><strong>Total productos:</strong> ${productosFiltrados.length}</p>
                                                                                                                                                                                                                                                                                                                                                                    <p><strong>Productos objetivo:</strong> ${productosObjetivo.length} (${((productosObjetivo.length / productosFiltrados.length) * 100).toFixed(1)}%)</p>
                                                                                                                                                                                                                                                                                                                                                                </div>
                                                                                                                                                                                                                                                                                                                                                                <div>
                                                                                                                                                                                                                                                                                                                                                                    <p><strong>Total kilos:</strong> ${totalKilos.toFixed(2)} kg</p>
                                                                                                                                                                                                                                                                                                                                                                    <p><strong>Kilos objetivo:</strong> ${kilosObjetivo.toFixed(2)} kg (${((kilosObjetivo / totalKilos) * 100).toFixed(1)}%)</p>
                                                                                                                                                                                                                                                                                                                                                                </div>
                                                                                                                                                                                                                                                                                                                                                            </div>
                                                                                                                                                                                                                                                                                                                                                        </div>
                                                                                                                                                                                                                                                                                                                                                    `;

            return tabla;
        }

        function generarContenidoTiemposMuertos(empresa, codSala, codTipoPlanilla) {
            // Filtrar tiempos muertos por sala y tipo (los tiempos muertos no son específicos por empresa)
            const tiemposFiltrados = tiemposMuertosPorEmpresa.filter(item => {
                return item.cod_sala == codSala && item.cod_tipo_planilla == codTipoPlanilla;
            });

            if (tiemposFiltrados.length === 0) {
                return `
                                                                                                                                                                                                                                                                                                                                                                    <div class="text-center text-gray-500 py-8">
                                                                                                                                                                                                                                                                                                                                                                        <i data-lucide="clock" class="h-16 w-16 mx-auto mb-4 text-gray-400"></i>
                                                                                                                                                                                                                                                                                                                                                                        <h3 class="text-lg font-semibold mb-2">Sin Tiempos Muertos</h3>
                                                                                                                                                                                                                                                                                                                                                                        <p>No se registraron tiempos muertos para esta sala y proceso.</p>
                                                                                                                                                                                                                                                                                                                                                                    </div>
                                                                                                                                                                                                                                                                                                                                                                `;
            }

            let contenido = `<div class="space-y-3">`;

            tiemposFiltrados.forEach(tiempo => {
                const minutos = Number(tiempo.duracion_minutos);
                const horas = (minutos / 60).toFixed(1);

                contenido += `
                                                                                                                                                                                                                                                                                                                                                                    <div class="border rounded-lg p-4 hover:bg-gray-50">
                                                                                                                                                                                                                                                                                                                                                                        <div class="flex justify-between items-start">
                                                                                                                                                                                                                                                                                                                                                                            <div class="flex-1">
                                                                                                                                                                                                                                                                                                                                                                                <p class="font-medium text-gray-900">${tiempo.motivo}</p>
                                                                                                                                                                                                                                                                                                                                                                                <p class="text-sm text-gray-600 mt-1">
                                                                                                                                                                                                                                                                                                                                                                                    <span class="inline-flex items-center gap-1">
                                                                                                                                                                                                                                                                                                                                                                                        <i data-lucide="building" class="h-4 w-4"></i>
                                                                                                                                                                                                                                                                                                                                                                                        Departamento: ${tiempo.nombre}
                                                                                                                                                                                                                                                                                                                                                                                    </span>
                                                                                                                                                                                                                                                                                                                                                                                </p>
                                                                                                                                                                                                                                                                                                                                                                            </div>
                                                                                                                                                                                                                                                                                                                                                                            <div class="text-right">
                                                                                                                                                                                                                                                                                                                                                                                <span class="px-3 py-1 bg-red-100 text-red-800 rounded-md text-sm font-medium">
                                                                                                                                                                                                                                                                                                                                                                                    ${horas}h (${minutos} min)
                                                                                                                                                                                                                                                                                                                                                                                </span>
                                                                                                                                                                                                                                                                                                                                                                            </div>
                                                                                                                                                                                                                                                                                                                                                                        </div>
                                                                                                                                                                                                                                                                                                                                                                    </div>
                                                                                                                                                                                                                                                                                                                                                                `;
            });

            contenido += `</div>`;

            const totalMinutos = tiemposFiltrados.reduce((sum, t) => sum + Number(t.duracion_minutos), 0);
            const totalHoras = (totalMinutos / 60).toFixed(1);

            contenido += `
                                                                                                                                                                                                                                                                                                                                                                <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                                                                                                                                                                                                                                                                                                                                                                    <h4 class="font-medium text-gray-900 mb-2">Resumen de Tiempos Muertos</h4>
                                                                                                                                                                                                                                                                                                                                                                    <div class="grid grid-cols-2 gap-4 text-sm">
                                                                                                                                                                                                                                                                                                                                                                        <div>
                                                                                                                                                                                                                                                                                                                                                                            <p><strong>Total eventos:</strong> ${tiemposFiltrados.length}</p>
                                                                                                                                                                                                                                                                                                                                                                            <p><strong>Tiempo perdido:</strong> ${totalHoras}h (${totalMinutos} min)</p>
                                                                                                                                                                                                                                                                                                                                                                        </div>
                                                                                                                                                                                                                                                                                                                                                                        <div>
                                                                                                                                                                                                                                                                                                                                                                            <p><strong>Promedio por evento:</strong> ${(totalMinutos / tiemposFiltrados.length).toFixed(1)} min</p>
                                                                                                                                                                                                                                                                                                                                                                            <p><strong>Impacto:</strong> <span class="text-red-600 font-medium">${totalHoras}h de producción perdida</span></p>
                                                                                                                                                                                                                                                                                                                                                                        </div>
                                                                                                                                                                                                                                                                                                                                                                    </div>
                                                                                                                                                                                                                                                                                                                                                                </div>
                                                                                                                                                                                                                                                                                                                                                            `;

            return contenido;
        }

        // Función cerrarModal original
        function cerrarModal() {
            const modal = document.getElementById('modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');

            // Actualizar cards después de cerrar modal
            setTimeout(() => {
                inicializarTodosLosCards();
            }, 100);
        }

        // Cerrar modal al hacer clic fuera
        document.getElementById('modal').addEventListener('click', function (e) {
            if (e.target === this) {
                cerrarModal();
            }
        });

        // ===== NUEVAS FUNCIONES PARA COMENTARIOS DE FOTOS =====

        /**
         * Abrir modal para editar comentario de foto
         */
        function editarComentarioFoto(idFoto) {
            console.log('📝 Editando comentario de foto ID:', idFoto);

            // Verificar permisos
            if (!modoEdicion || estadoInforme !== 0) {
                console.log('🚫 Edición bloqueada: informe no está en borrador');
                return;
            }

            const fotoElement = document.querySelector(`[data-foto-id="${idFoto}"]`);
            if (!fotoElement) {
                console.error('❌ Elemento de foto no encontrado');
                return;
            }

            const comentarioActual = fotoElement.getAttribute('data-comentario') || '';

            // Configurar modal
            const modal = document.getElementById('modal-comentario-foto');
            const textarea = document.getElementById('comentario-textarea');
            const contador = document.getElementById('contador-caracteres');

            textarea.value = comentarioActual;
            modal.dataset.fotoId = idFoto;

            // Actualizar contador de caracteres
            contador.textContent = `${comentarioActual.length}/500`;

            // Mostrar modal
            modal.classList.remove('hidden');
            textarea.focus();
        }

        /**
         * Cerrar modal de comentario
         */
        function cerrarModalComentario() {
            const modal = document.getElementById('modal-comentario-foto');
            modal.classList.add('hidden');
            modal.dataset.fotoId = '';
        }

        /**
         * Guardar comentario de foto
         */
        async function guardarComentarioFoto() {
            const modal = document.getElementById('modal-comentario-foto');
            const textarea = document.getElementById('comentario-textarea');
            const idFoto = modal.dataset.fotoId;
            const comentario = textarea.value.trim();

            if (!idFoto) {
                console.error('❌ ID de foto no disponible');
                return;
            }

            try {
                console.log('💾 Guardando comentario:', { idFoto, comentario });

                const response = await fetch('{{ route('informes.actualizarComentarioFoto') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        id_foto: parseInt(idFoto),
                        comentario: comentario
                    })
                });

                const data = await response.json();

                if (data.success) {
                    console.log('✅ Comentario guardado exitosamente');

                    // Actualizar UI
                    actualizarComentarioEnUI(idFoto, comentario);
                    cerrarModalComentario();

                    // Mostrar mensaje de éxito
                    mostrarMensaje('Comentario actualizado correctamente', 'success');
                } else {
                    throw new Error(data.error || 'Error desconocido');
                }

            } catch (error) {
                console.error('❌ Error guardando comentario:', error);
                mostrarMensaje('Error al guardar comentario: ' + error.message, 'error');
            }
        }

        /**
         * Actualizar comentario en la UI
         */
        function actualizarComentarioEnUI(idFoto, nuevoComentario) {
            const fotoElement = document.querySelector(`[data-foto-id="${idFoto}"]`);
            if (!fotoElement) {
                console.error('❌ Elemento de foto no encontrado para actualizar UI');
                return;
            }

            // Actualizar data attribute
            fotoElement.setAttribute('data-comentario', nuevoComentario);

            // Actualizar texto visible
            const comentarioElement = fotoElement.querySelector('.comentario-foto');
            if (comentarioElement) {
                comentarioElement.textContent = nuevoComentario || 'Agregar comentario...';
            }

            console.log('✅ UI actualizada para foto ID:', idFoto);
        }

        /**
         * Ampliar foto en modal
         */
        function ampliarFoto(src, nombre, comentario = '') {
            const modal = document.getElementById('modal-foto-ampliada');
            const img = document.getElementById('img-ampliada');
            const info = document.getElementById('info-foto-ampliada');

            img.src = src;

            // Crear contenido con nombre y comentario
            let contenidoInfo = `<p class="font-medium text-gray-900">${nombre}</p>`;
            if (comentario) {
                contenidoInfo += `<p class="text-sm text-blue-600 mt-2 bg-blue-50 p-2 rounded border-l-4 border-blue-400"><i data-lucide="message-circle" class="h-4 w-4 inline mr-1"></i>${comentario}</p>`;
            } else {
                contenidoInfo += `<p class="text-sm text-gray-500 mt-1 italic">Sin comentario</p>`;
            }

            info.innerHTML = contenidoInfo;
            modal.classList.remove('hidden');

            setTimeout(() => lucide.createIcons(), 100);
        }

        /**
         * Cerrar modal de foto ampliada
         */
        function cerrarFotoAmpliada() {
            const modal = document.getElementById('modal-foto-ampliada');
            modal.classList.add('hidden');
        }

        /**
         * Función para mostrar indicadores visuales de guardado automático
         */
        function configurarIndicadoresVisuales() {
            const textareas = document.querySelectorAll('textarea[id^="comentario_"]');

            textareas.forEach(textarea => {
                const codSala = textarea.getAttribute('data-cod-sala');
                const estadoSpan = document.getElementById(`estado_${codSala}`);

                // Evento cuando el usuario enfoca el textarea
                textarea.addEventListener('focus', function () {
                    if (modoEdicion && estadoInforme === 0) {
                        estadoSpan.innerHTML = '<span class="text-blue-500">✏️ Escribiendo... (se guarda automáticamente)</span>';
                        estadoSpan.classList.add('show');
                    }
                });

                // Evento cuando el usuario sale del textarea (blur)
                textarea.addEventListener('blur', function () {
                    // Solo cambiar el estado si no estamos guardando
                    setTimeout(() => {
                        if (!estadoSpan.innerHTML.includes('Guardando')) {
                            estadoSpan.classList.remove('show');
                        }
                    }, 100);
                });
            });
        }

        // Función para cerrar modal de detalle de planilla (sin animaciones)
        function cerrarModalDetallePlanilla() {
            const modal = document.getElementById('modalDetallePlanilla');

            // Cerrar instantáneamente
            modal.classList.add('hidden');
            modal.classList.remove('flex');

            // Limpiar iframe
            document.getElementById("iframePlanillaDetalle").src = '';

            // ✅ MEJORA: Limpiar sessionStorage si se cierra sin guardar
            sessionStorage.removeItem('modal_origen');
            sessionStorage.removeItem('informe_url');
        }

        // Función para abrir modal de detalle de planilla (sin animaciones)
        function abrirModalDetallePlanilla(codPlanilla) {
            // ✅ SOLUCIÓN: Guardar contexto en sessionStorage
            sessionStorage.setItem('modal_origen', 'informe');
            sessionStorage.setItem('informe_url', window.location.href);

            const url = "{{ url('/ver-planilla/') }}/" + codPlanilla;
            const modal = document.getElementById('modalDetallePlanilla');
            const iframe = document.getElementById("iframePlanillaDetalle");

            // Configurar iframe y mostrar instantáneamente
            iframe.src = url;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        // Cerrar modal de detalle de planilla al hacer clic fuera (comportamiento Bootstrap)
        document.getElementById('modalDetallePlanilla').addEventListener('click', function (e) {
            if (e.target === this) {
                cerrarModalDetallePlanilla();
            }
        });

        // NUEVO: Cerrar modal de foto ampliada al hacer clic fuera
        document.getElementById('modal-foto-ampliada').addEventListener('click', function (e) {
            if (e.target === this) {
                cerrarFotoAmpliada();
            }
        });

        // Cerrar modales con tecla Escape (priorizar en orden de importancia)
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                const modalComentario = document.getElementById('modal-comentario-foto');
                const modalFotoAmpliada = document.getElementById('modal-foto-ampliada');
                const modalDetallePlanilla = document.getElementById('modalDetallePlanilla');

                // Prioridad 1: Modal de comentario (más específico)
                if (!modalComentario.classList.contains('hidden')) {
                    cerrarModalComentario();
                }
                // Prioridad 2: Modal de foto ampliada
                else if (!modalFotoAmpliada.classList.contains('hidden')) {
                    cerrarFotoAmpliada();
                }
                // Prioridad 3: Modal de detalle de planilla
                else if (!modalDetallePlanilla.classList.contains('hidden')) {
                    cerrarModalDetallePlanilla();
                }
            }
        });
    </script>
@endsection