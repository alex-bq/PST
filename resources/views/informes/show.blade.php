@extends('layouts.main-iframe')

@section('title', 'Visualización del Informe de Turno')

@section('styles')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <style>
        .foto-thumbnail {
            position: relative;
            display: inline-block;
            margin: 8px;
            border-radius: 8px;
            overflow: hidden;
            transition: all 0.3s ease;
            background: white;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            cursor: pointer;
        }

        .foto-thumbnail:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .modal {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.8);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            padding: 1rem;
        }

        .modal.active {
            display: flex;
        }

        .modal img {
            max-width: 90vw;
            max-height: 80vh;
            object-fit: contain;
            border-top-left-radius: 0.5rem;
            border-top-right-radius: 0.5rem;
            background: white;
        }

        /* Estilos específicos para el modal de fotos */
        #modal-foto .relative {
            background: white;
            border-radius: 0.5rem;
            overflow: hidden;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            max-width: 90vw;
            max-height: 90vh;
        }

        #foto-info {
            max-width: 600px;
            word-wrap: break-word;
        }

        /* Estilos para tabs */
        .tab-button {
            padding: 12px 24px;
            border-bottom: 3px solid transparent;
            color: #6b7280;
            font-weight: 500;
            transition: all 0.3s ease;
            cursor: pointer;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }

        .tab-button:hover {
            color: #374151;
            background-color: #f9fafb;
        }

        .tab-button.active {
            color: #2563eb;
            border-bottom-color: #2563eb;
            background-color: #eff6ff;
            font-weight: 600;
        }

        .tab-content {
            display: none!important;
            }

            .tab-content.active {
                display: block !important;
            }

            /* Animación para el estado finalizado */
            @keyframes pulse {

                0%,
                100% {
                    opacity: 1;
                }

                50% {
                    opacity: 0.5;
                }
            }

            /* Estilos para el modal de detalle de planilla (sin animaciones) */
            #modalDetallePlanilla {
                backdrop-filter: blur(1px);
            }

            /* Hacer que las filas de planilla se vean clicables */
            .cursor-pointer:hover {
                background-color: #f3f4f6 !important;
                transform: translateY(-1px);
                transition: all 0.2s ease;
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
                            <h1 class="text-xl font-bold">
                                Visualizando Informe (Completado)
                            </h1>
                            <p class="text-sm text-gray-600">
                                {{ \Carbon\Carbon::parse($fecha)->format('d/m/Y') }} - {{ $informe->turno }} - Jefe:
                                {{ $informe->jefe_turno_nom }}
                            </p>
                            <p class="text-xs">
                                @if(isset($informe->hora_inicio) && isset($informe->hora_fin))
                                    <span class="text-blue-600">
                                        {{ $informe->hora_inicio }} - {{ $informe->hora_fin }}
                                        @if(isset($informe->horas_trabajadas))
                                            ({{ number_format($informe->horas_trabajadas, 1) }}h)
                                        @endif
                                    </span>
                                    @if(isset($informe->tiene_colacion) && $informe->tiene_colacion && isset($informe->hora_inicio_colacion))
                                        | <span class="text-orange-600">
                                            {{ $informe->hora_inicio_colacion }} - {{ $informe->hora_fin_colacion }}
                                        </span>
                                    @endif
                                @else
                                    <span class="text-gray-600">Horarios pendientes de configuración</span>
                                @endif
                                @if(isset($informe->fecha_finalizacion) && $informe->fecha_finalizacion)
                                    | <span class="text-green-600">
                                        Finalizado:
                                        {{ \Carbon\Carbon::parse($informe->fecha_finalizacion)->format('d/m/Y H:i') }}
                                    </span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <!-- BOTÓN DESCARGAR PDF -->
                    <div class="flex gap-2">
                        <a href="{{ route('informes.downloadPDF', ['fecha' => $fecha, 'turno' => $turno]) }}"
                            target="_blank"
                            class="flex items-center gap-2 px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors">
                            <i data-lucide="download" class="h-4 w-4"></i>
                            Descargar PDF
                        </a>

                    </div>
                </div>
            </div>
        </div>

        <div class="container mx-auto p-6">
            @php
                // Organizar datos por sala
                $salas_agrupadas = collect($informacion_sala)->groupBy('nombre_sala');

                // Agrupar productos por empresa para acceso directo
                $productos_por_empresa = collect($detalle_procesamiento)
                    ->groupBy(function ($item) {
                        return $item->cod_sala . '-' . $item->cod_tipo_planilla . '-' . $item->descripcion;
                    });
            @endphp

            <!-- TABS POR SALA Y FOTOS -->
            <div class="bg-white rounded-lg shadow-sm mb-6">
                <!-- Navegación de tabs -->
                <div class="border-b border-gray-200">
                    <nav class="flex space-x-0">
                        @foreach($salas_agrupadas as $index => $datos_sala)
                            @php
                                $sala_nombre = $datos_sala->first()->nombre_sala;
                                $sala_codigo = $datos_sala->first()->cod_sala;
                            @endphp
                            <button class="tab-button {{ $index === 0 ? 'active' : '' }}"
                                onclick="cambiarTab('sala-{{ $sala_codigo }}', this)">
                                {{ $sala_nombre }}
                                <span class="ml-2 text-xs bg-gray-200 px-2 py-1 rounded-full">
                                    {{ $datos_sala->count() }} proceso{{ $datos_sala->count() != 1 ? 's' : '' }}
                                </span>
                            </button>
                        @endforeach
                        <!-- Tab para fotos -->
                        <button class="tab-button" onclick="cambiarTab('fotos-informe', this)">
                            Fotos del Informe
                            <span class="ml-2 text-xs bg-gray-200 px-2 py-1 rounded-full">
                                {{ $fotos_informe->count() }}
                            </span>
                        </button>
                    </nav>
                </div>

                <!-- Contenido de los tabs de salas -->
                @foreach($salas_agrupadas as $index => $datos_sala)
                    @php
                        $sala_nombre = $datos_sala->first()->nombre_sala;
                        $sala_codigo = $datos_sala->first()->cod_sala;
                    @endphp

                    <div id="sala-{{ $sala_codigo }}" class="tab-content {{ $index === 0 ? 'active' : '' }} p-6">
                        <div class="space-y-6">
                            <!-- Header de la sala -->
                            <div class="mb-6">
                                <h2 class="text-2xl font-bold text-gray-800 mb-2">{{ $sala_nombre }}</h2>
                                <p class="text-gray-600">Sala {{ $sala_codigo }} - {{ $datos_sala->count() }}
                                    proceso{{ $datos_sala->count() != 1 ? 's' : '' }}
                                    operativo{{ $datos_sala->count() != 1 ? 's' : '' }}</p>
                            </div>

                            <!-- COMENTARIOS DE LA SALA -->
                            @if($sala_codigo)
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
                                    <div class="flex items-center justify-between mb-4">
                                        <h4 class="font-bold text-lg text-gray-900 flex items-center gap-2">
                                            Comentarios de {{ $sala_nombre }}
                                            <span class="text-sm text-gray-500 font-normal">(Sala {{ $sala_codigo }})</span>
                                        </h4>
                                    </div>

                                    <div class="bg-white p-4 rounded-md border border-blue-200">
                                        @if(isset($comentarios_salas[$sala_codigo]) && $comentarios_salas[$sala_codigo]->comentarios)
                                            <p class="text-gray-700 whitespace-pre-wrap">
                                                {{ $comentarios_salas[$sala_codigo]->comentarios }}
                                            </p>
                                        @else
                                            <p class="text-gray-500 italic">Sin comentarios registrados para esta sala</p>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            @foreach($datos_sala as $sala)
                                <!-- Proceso/Tipo de Planilla -->
                                <div class="border rounded-lg p-6 space-y-6 bg-gray-50">
                                    <div class="flex items-center justify-between">
                                        <h3 class="font-semibold text-xl text-gray-800">{{ $sala->tipo_planilla }}</h3>
                                        @php
                                            // Obtener empresas para esta sala y tipo
                                            $empresas_en_sala = collect($detalle_procesamiento)
                                                ->where('cod_sala', $sala->cod_sala)
                                                ->where('cod_tipo_planilla', $sala->cod_tipo_planilla)
                                                ->pluck('descripcion')
                                                ->unique();

                                            $total_empresas = $empresas_en_sala->count();
                                        @endphp
                                        <span class="px-3 py-1 bg-green-100 text-green-800 rounded-md text-sm font-medium">
                                            {{ $total_empresas }} {{ $total_empresas == 1 ? 'Empresa' : 'Empresas' }}
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

                                                // Obtener planillas filtradas para calcular dotación correcta
                                                $planillas_para_dotacion = collect($planillas_detalle)->filter(function ($planilla) use ($empresa_nombre, $sala) {
                                                    return isset($planilla->descripcion) &&
                                                        trim($planilla->descripcion) === trim($empresa_nombre) &&
                                                        $planilla->cod_sala == $sala->cod_sala &&
                                                        $planilla->cod_tipo_planilla == $sala->cod_tipo_planilla;
                                                });

                                                // Calcular valores básicos
                                                $dotacion_max = $planillas_para_dotacion->max('dotacion') ?? 1;
                                                $horas_efectivas = $sala->horas_trabajadas ?? 1;
                                                $horas_turno = $informe->horas_trabajadas ?? 1;
                                                $kilos_objetivo = $productos_empresa->where('es_producto_objetivo', 1)->sum('kilos') ?? 0;
                                                $kilos_pst_total = $productos_empresa->sum('kilos') ?? 0;

                                                // NUEVA VARIABLE: Calcular entrega de materia prima para esta empresa
                                                $entrega_mp = $planillas_para_dotacion->sum('kilos_entrega') ?? 0;

                                                // Evitar división por 0 en horas
                                                $horas_efectivas = $horas_efectivas > 0 ? $horas_efectivas : 1;
                                                $horas_turno = $horas_turno > 0 ? $horas_turno : 1;

                                                // Calcular las 4 productividades
                                                $productividad_objetivo_efectivas = round($kilos_objetivo / ($dotacion_max * $horas_efectivas), 2);
                                                $productividad_objetivo_turno = round($kilos_objetivo / ($dotacion_max * $horas_turno), 2);
                                                $productividad_total_efectivas = round($kilos_pst_total / ($dotacion_max * $horas_efectivas), 2);
                                                $productividad_total_turno = round($kilos_pst_total / ($dotacion_max * $horas_turno), 2);

                                                // NUEVO CÁLCULO: Rendimiento (PST Objetivo / Entrega MP) × 100
                                                $rendimiento = $entrega_mp > 0 ? round(($kilos_objetivo / $entrega_mp) * 100, 2) : 0;

                                                // Obtener planillas únicas
                                                $planillas_unicas = $productos_empresa->pluck('n_planilla')->unique();

                                                // Obtener tiempos muertos para esta sala
                                                $tiempos_muertos_sala = collect($tiempos_muertos)->where('cod_sala', $sala->cod_sala);
                                            @endphp

                                            <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
                                                <!-- Header de empresa -->
                                                <div class="flex items-center justify-between mb-6">
                                                    <h4 class="font-bold text-lg text-gray-900 flex items-center gap-3">
                                                        <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-md text-base font-medium">
                                                            {{ $empresa_nombre }}
                                                        </span>
                                                        <span class="text-sm text-gray-500 font-normal">
                                                            {{ $productos_empresa->count() }} productos | {{ $planillas_unicas->count() }}
                                                            planillas
                                                        </span>
                                                    </h4>
                                                </div>

                                                <!-- Datos operacionales básicos -->
                                                <div class="grid grid-cols-2 md:grid-cols-6 gap-3 mb-6">
                                                    <div class="bg-gray-50 p-4 rounded-lg text-center">
                                                        <p class="text-xs text-gray-600 mb-1">Dotación</p>
                                                        <p class="font-semibold text-xl text-gray-800">{{ $dotacion_max }}</p>
                                                        <p class="text-xs text-gray-500">personas</p>
                                                    </div>
                                                    <div class="bg-gray-50 p-4 rounded-lg text-center">
                                                        <p class="text-xs text-gray-600 mb-1">Horas Efectivas</p>
                                                        <p class="font-semibold text-xl text-gray-800">
                                                            {{ number_format($horas_efectivas, 1) }}h
                                                        </p>
                                                    </div>
                                                    <div class="bg-gray-50 p-4 rounded-lg text-center">
                                                        <p class="text-xs text-gray-600 mb-1">Horas Turno</p>
                                                        <p class="font-semibold text-xl text-blue-600">
                                                            {{ number_format($horas_turno, 1) }}h
                                                        </p>
                                                    </div>
                                                    <div class="bg-gray-50 p-4 rounded-lg text-center">
                                                        <p class="text-xs text-gray-600 mb-1">Entrega MP</p>
                                                        <p class="font-semibold text-xl text-purple-700">
                                                            {{ number_format($entrega_mp, 0) }}
                                                        </p>
                                                        <p class="text-xs text-gray-500">kg</p>
                                                    </div>
                                                    <div class="bg-gray-50 p-4 rounded-lg text-center">
                                                        <p class="text-xs text-gray-600 mb-1">PST Objetivo</p>
                                                        <p class="font-semibold text-xl text-green-700">
                                                            {{ number_format($kilos_objetivo, 0) }}
                                                        </p>
                                                        <p class="text-xs text-gray-500">kg</p>
                                                    </div>
                                                    <div class="bg-gray-50 p-4 rounded-lg text-center">
                                                        <p class="text-xs text-gray-600 mb-1">PST Total</p>
                                                        <p class="font-semibold text-xl text-blue-700">
                                                            {{ number_format($kilos_pst_total, 0) }}
                                                        </p>
                                                        <p class="text-xs text-gray-500">kg</p>
                                                    </div>
                                                </div>

                                                <!-- PRODUCTIVIDADES (4 tipos) -->
                                                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-6">
                                                    <h5 class="text-base font-semibold text-yellow-800 mb-4 flex items-center gap-2">
                                                        Productividades (kg/persona/hora) y Rendimiento
                                                    </h5>
                                                    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                                                        <div
                                                            class="bg-white p-4 rounded-lg text-center border-l-4 border-green-500 shadow-sm">
                                                            <p class="text-xs text-gray-600 mb-2">Objetivo + Efectivas</p>
                                                            <p class="font-bold text-2xl text-green-700">
                                                                {{ $productividad_objetivo_efectivas }}
                                                            </p>
                                                            <p class="text-xs text-gray-500 mt-1">
                                                                {{ number_format($kilos_objetivo, 0) }}kg ÷ ({{ $dotacion_max }} ×
                                                                {{ number_format($horas_efectivas, 1) }}h)
                                                            </p>
                                                        </div>
                                                        <div
                                                            class="bg-white p-4 rounded-lg text-center border-l-4 border-green-400 shadow-sm">
                                                            <p class="text-xs text-gray-600 mb-2">Objetivo + Turno</p>
                                                            <p class="font-bold text-2xl text-green-600">
                                                                {{ $productividad_objetivo_turno }}
                                                            </p>
                                                            <p class="text-xs text-gray-500 mt-1">
                                                                {{ number_format($kilos_objetivo, 0) }}kg ÷ ({{ $dotacion_max }} ×
                                                                {{ number_format($horas_turno, 1) }}h)
                                                            </p>
                                                        </div>
                                                        <div
                                                            class="bg-white p-4 rounded-lg text-center border-l-4 border-blue-500 shadow-sm">
                                                            <p class="text-xs text-gray-600 mb-2">Total + Efectivas</p>
                                                            <p class="font-bold text-2xl text-blue-700">
                                                                {{ $productividad_total_efectivas }}
                                                            </p>
                                                            <p class="text-xs text-gray-500 mt-1">
                                                                {{ number_format($kilos_pst_total, 0) }}kg ÷ ({{ $dotacion_max }} ×
                                                                {{ number_format($horas_efectivas, 1) }}h)
                                                            </p>
                                                        </div>
                                                        <div
                                                            class="bg-white p-4 rounded-lg text-center border-l-4 border-blue-400 shadow-sm">
                                                            <p class="text-xs text-gray-600 mb-2">Total + Turno</p>
                                                            <p class="font-bold text-2xl text-blue-600">
                                                                {{ $productividad_total_turno }}
                                                            </p>
                                                            <p class="text-xs text-gray-500 mt-1">
                                                                {{ number_format($kilos_pst_total, 0) }}kg ÷ ({{ $dotacion_max }} ×
                                                                {{ number_format($horas_turno, 1) }}h)
                                                            </p>
                                                        </div>
                                                        <div
                                                            class="bg-white p-4 rounded-lg text-center border-l-4 border-orange-500 shadow-sm">
                                                            <p class="text-xs text-gray-600 mb-2">Rendimiento</p>
                                                            <p class="font-bold text-2xl text-orange-700">
                                                                {{ $rendimiento }}%
                                                            </p>
                                                            <p class="text-xs text-gray-500 mt-1">
                                                                {{ number_format($kilos_objetivo, 0) }}kg ÷
                                                                {{ number_format($entrega_mp, 0) }}kg × 100
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- SECCIÓN DE DETALLES -->
                                                <div class="space-y-6">

                                                    <!-- PRODUCTOS DETALLADOS -->
                                                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                                                        <h6 class="font-semibold text-blue-800 mb-4 flex items-center gap-2">
                                                            Productos ({{ $productos_empresa->count() }})
                                                        </h6>
                                                        @if($productos_empresa->count() > 0)
                                                            <div class="overflow-x-auto">
                                                                <table class="w-full border-collapse border border-gray-300">
                                                                    <thead class="bg-gray-50">
                                                                        <tr>
                                                                            <th
                                                                                class="border border-gray-300 px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                                                                Especie</th>
                                                                            <th
                                                                                class="border border-gray-300 px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                                                                Producto</th>
                                                                            <th
                                                                                class="border border-gray-300 px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">
                                                                                Calidad</th>
                                                                            <th
                                                                                class="border border-gray-300 px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">
                                                                                Destino</th>
                                                                            <th
                                                                                class="border border-gray-300 px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">
                                                                                Kg</th>
                                                                            <th
                                                                                class="border border-gray-300 px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">
                                                                                %</th>
                                                                            <th
                                                                                class="border border-gray-300 px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">
                                                                                Objetivo</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @php
                                                                            $total_kilos_empresa = $productos_empresa->sum('kilos');
                                                                        @endphp
                                                                        @foreach($productos_empresa as $producto)
                                                                            @php
                                                                                $porcentaje = $total_kilos_empresa > 0 ? (($producto->kilos / $total_kilos_empresa) * 100) : 0;
                                                                                $es_calidad_premium = ($producto->calidad ?? '') === 'PREMIUM';
                                                                                // REVERTIDO: Nombre del producto sin especie
                                                                                $nombre_producto = ($producto->corte_inicial ?? '') . ' → ' . ($producto->corte_final ?? '');
                                                                                if (isset($producto->calibre) && $producto->calibre !== 'SIN CALIBRE') {
                                                                                    $nombre_producto .= ' → ' . $producto->calibre;
                                                                                }
                                                                                $es_objetivo = $producto->es_producto_objetivo == 1;
                                                                            @endphp
                                                                            <tr class="hover:bg-gray-50">
                                                                                <td class="border border-gray-300 px-4 py-2">
                                                                                    <span
                                                                                        class="px-2 py-1 bg-purple-100 text-purple-800 rounded text-sm font-medium">
                                                                                        {{ $producto->especie ?? 'Sin especie' }}
                                                                                    </span>
                                                                                </td>
                                                                                <td class="border border-gray-300 px-4 py-2 font-medium">
                                                                                    {{ $nombre_producto }}
                                                                                </td>
                                                                                <td class="border border-gray-300 px-4 py-2">
                                                                                    <span
                                                                                        class="px-2 py-1 {{ $es_calidad_premium ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }} rounded text-sm">
                                                                                        {{ $producto->calidad ?? 'N/A' }}
                                                                                    </span>
                                                                                </td>
                                                                                <td class="border border-gray-300 px-4 py-2">
                                                                                    {{ $producto->destino ?? 'N/A' }}
                                                                                </td>
                                                                                <td class="border border-gray-300 px-4 py-2 text-right font-medium">
                                                                                    {{ number_format($producto->kilos, 2) }} kg
                                                                                </td>
                                                                                <td class="border border-gray-300 px-4 py-2 text-right">
                                                                                    {{ number_format($porcentaje, 1) }}%
                                                                                </td>
                                                                                <td class="border border-gray-300 px-4 py-2 text-center">
                                                                                    <span
                                                                                        class="px-2 py-1 {{ $es_objetivo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} rounded text-sm font-medium">
                                                                                        {{ $es_objetivo ? 'SÍ' : 'NO' }}
                                                                                    </span>
                                                                                </td>
                                                                            </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>

                                                            @php
                                                                $productos_objetivo = $productos_empresa->where('es_producto_objetivo', 1);
                                                                $kilos_objetivo = $productos_objetivo->sum('kilos');
                                                            @endphp

                                                            <!-- Resumen productos -->
                                                            <div class="mt-4 text-sm text-gray-600 bg-gray-50 p-4 rounded">
                                                                <div class="grid grid-cols-2 gap-4">
                                                                    <div>
                                                                        <p><strong>Total productos:</strong> {{ $productos_empresa->count() }}
                                                                        </p>
                                                                        <p><strong>Productos objetivo:</strong>
                                                                            {{ $productos_objetivo->count() }}
                                                                            ({{ $productos_empresa->count() > 0 ? number_format(($productos_objetivo->count() / $productos_empresa->count()) * 100, 1) : 0 }}%)
                                                                        </p>
                                                                    </div>
                                                                    <div>
                                                                        <p><strong>Total kilos:</strong>
                                                                            {{ number_format($total_kilos_empresa, 2) }} kg</p>
                                                                        <p><strong>Kilos objetivo:</strong>
                                                                            {{ number_format($kilos_objetivo, 2) }} kg
                                                                            ({{ $total_kilos_empresa > 0 ? number_format(($kilos_objetivo / $total_kilos_empresa) * 100, 1) : 0 }}%)
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @else
                                                            <div class="text-center text-gray-500 py-8">
                                                                <i data-lucide="package-x" class="h-16 w-16 mx-auto mb-4 text-gray-400"></i>
                                                                <h3 class="text-lg font-semibold mb-2">Sin Productos</h3>
                                                                <p>No se encontraron productos para esta empresa, sala y proceso.</p>
                                                            </div>
                                                        @endif
                                                    </div>

                                                    <!-- PLANILLAS DETALLADAS -->
                                                    <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                                                        <h6 class="font-semibold text-green-800 mb-4 flex items-center gap-2">
                                                            Planillas ({{ $planillas_unicas->count() }})
                                                        </h6>
                                                        @php
                                                            // Filtrar planillas detalladas por empresa, sala y tipo
                                                            $planillas_filtradas = collect($planillas_detalle)->filter(function ($planilla) use ($empresa_nombre, $sala) {
                                                                return isset($planilla->descripcion) &&
                                                                    trim($planilla->descripcion) === trim($empresa_nombre) &&
                                                                    $planilla->cod_sala == $sala->cod_sala &&
                                                                    $planilla->cod_tipo_planilla == $sala->cod_tipo_planilla;
                                                            });
                                                        @endphp

                                                        @if($planillas_filtradas->count() > 0)
                                                            <div class="overflow-x-auto">
                                                                <table class="w-full border-collapse border border-gray-300">
                                                                    <thead class="bg-gray-50">
                                                                        <tr>
                                                                            <th
                                                                                class="border border-gray-300 px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                                                                Planilla</th>
                                                                            <th
                                                                                class="border border-gray-300 px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">
                                                                                Trabajador</th>
                                                                            <th
                                                                                class="border border-gray-300 px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">
                                                                                Dotación</th>
                                                                            <th
                                                                                class="border border-gray-300 px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">
                                                                                Horas Trabajadas</th>
                                                                            <th
                                                                                class="border border-gray-300 px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">
                                                                                Kg Entrega</th>
                                                                            <th
                                                                                class="border border-gray-300 px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">
                                                                                PST Total</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach($planillas_filtradas as $planilla)
                                                                            <tr class="hover:bg-gray-50 cursor-pointer"
                                                                                onclick="abrirModalDetallePlanilla({{ $planilla->numero_planilla }})"
                                                                                title="Clic para ver detalle de la planilla">
                                                                                <td class="border border-gray-300 px-4 py-2 font-medium">
                                                                                    #{{ $planilla->numero_planilla ?? 'N/A' }}</td>
                                                                                <td class="border border-gray-300 px-4 py-2">
                                                                                    {{ $planilla->trabajador_nombre ?? 'Sin asignar' }}
                                                                                </td>
                                                                                <td
                                                                                    class="border border-gray-300 px-4 py-2 text-center font-medium text-orange-700">
                                                                                    {{ $planilla->dotacion ?? 0 }}
                                                                                </td>
                                                                                <td
                                                                                    class="border border-gray-300 px-4 py-2 text-right font-medium text-purple-700">
                                                                                    {{ number_format($planilla->horas_trabajadas ?? 0, 1) }}h
                                                                                </td>
                                                                                <td
                                                                                    class="border border-gray-300 px-4 py-2 text-right font-medium text-blue-700">
                                                                                    {{ number_format($planilla->kilos_entrega ?? 0, 2) }} kg
                                                                                </td>
                                                                                <td
                                                                                    class="border border-gray-300 px-4 py-2 text-right font-medium text-green-700">
                                                                                    {{ number_format($planilla->pst_total ?? 0, 2) }} kg
                                                                                </td>
                                                                            </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>

                                                            <!-- Resumen planillas -->
                                                            <div class="mt-4 text-sm text-gray-600 bg-gray-50 p-4 rounded">
                                                                <div class="grid grid-cols-4 gap-4">
                                                                    <div>
                                                                        <p><strong>Total planillas:</strong> {{ $planillas_filtradas->count() }}
                                                                        </p>
                                                                        <p><strong>Dotación total:</strong> <span
                                                                                class="text-orange-700 font-bold">{{ $planillas_filtradas->max('dotacion') ?? 0 }}</span>
                                                                        </p>
                                                                    </div>
                                                                    <div>
                                                                        <p><strong>Total horas:</strong>
                                                                            {{ number_format($planillas_filtradas->sum('horas_trabajadas'), 1) }}h
                                                                        </p>
                                                                    </div>
                                                                    <div>
                                                                        <p><strong>Total entrega:</strong>
                                                                            {{ number_format($planillas_filtradas->sum('kilos_entrega'), 2) }}
                                                                            kg</p>
                                                                    </div>
                                                                    <div>
                                                                        <p><strong>Total PST:</strong>
                                                                            {{ number_format($planillas_filtradas->sum('pst_total'), 2) }} kg
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @else
                                                            <div class="text-center text-gray-500 py-8">
                                                                <i data-lucide="file-x" class="h-16 w-16 mx-auto mb-4 text-gray-400"></i>
                                                                <h3 class="text-lg font-semibold mb-2">Sin Planillas</h3>
                                                                <p>No se encontraron planillas para esta empresa, sala y proceso.</p>
                                                            </div>
                                                        @endif
                                                    </div>

                                                    <!-- TIEMPOS MUERTOS DETALLADOS -->
                                                    <div class="bg-red-50 border border-red-200 rounded-lg p-6">
                                                        <h6 class="font-semibold text-red-800 mb-4 flex items-center gap-2">
                                                            Tiempos Muertos ({{ $tiempos_muertos_sala->count() }})
                                                        </h6>
                                                        @if($tiempos_muertos_sala->count() > 0)
                                                            <div class="space-y-3">
                                                                @foreach($tiempos_muertos_sala as $tiempo)
                                                                    <div class="border rounded-lg p-4 hover:bg-gray-50 bg-white">
                                                                        <div class="flex justify-between items-start">
                                                                            <div class="flex-1">
                                                                                <p class="font-medium text-gray-900">
                                                                                    {{ $tiempo->motivo ?? 'Sin motivo' }}
                                                                                </p>
                                                                                <p class="text-sm text-gray-600 mt-1">
                                                                                    {{ $tiempo->descripcion ?? 'Sin descripción' }}
                                                                                </p>
                                                                                @if(isset($tiempo->nombre))
                                                                                    <p class="text-sm text-gray-600 mt-1 flex items-center gap-1">
                                                                                        <i data-lucide="building" class="h-4 w-4"></i>
                                                                                        Departamento: {{ $tiempo->nombre }}
                                                                                    </p>
                                                                                @endif
                                                                            </div>
                                                                            <div class="text-right">
                                                                                <span
                                                                                    class="px-3 py-1 bg-red-100 text-red-800 rounded-md text-sm font-medium">
                                                                                    {{ number_format(($tiempo->duracion_minutos ?? 0) / 60, 1) }}h
                                                                                    ({{ $tiempo->duracion_minutos ?? 0 }} min)
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>

                                                            <!-- Resumen tiempos muertos -->
                                                            <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                                                                <h6 class="font-medium text-gray-900 mb-2">Resumen de Tiempos Muertos</h6>
                                                                <div class="grid grid-cols-2 gap-4 text-sm">
                                                                    <div>
                                                                        <p><strong>Total eventos:</strong> {{ $tiempos_muertos_sala->count() }}
                                                                        </p>
                                                                        <p><strong>Tiempo perdido:</strong>
                                                                            {{ number_format($tiempos_muertos_sala->sum('duracion_minutos') / 60, 1) }}h
                                                                            ({{ $tiempos_muertos_sala->sum('duracion_minutos') }} min)</p>
                                                                    </div>
                                                                    <div>
                                                                        <p><strong>Promedio por evento:</strong>
                                                                            {{ $tiempos_muertos_sala->count() > 0 ? number_format($tiempos_muertos_sala->sum('duracion_minutos') / $tiempos_muertos_sala->count(), 1) : 0 }}
                                                                            min</p>
                                                                        <p><strong>Impacto:</strong> <span
                                                                                class="text-red-600 font-medium">{{ number_format($tiempos_muertos_sala->sum('duracion_minutos') / 60, 1) }}h
                                                                                de producción perdida</span></p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @else
                                                            <div class="text-center text-gray-500 py-8">
                                                                <i data-lucide="clock" class="h-16 w-16 mx-auto mb-4 text-gray-400"></i>
                                                                <h3 class="text-lg font-semibold mb-2">Sin Tiempos Muertos</h3>
                                                                <p>No se registraron tiempos muertos para esta sala y proceso.</p>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach

                <!-- TAB DE FOTOS -->
                <div id="fotos-informe" class="tab-content p-6">
                    <div class="space-y-6">
                        <div class="mb-6">
                            <h2 class="text-2xl font-bold text-gray-800 mb-2">Fotos del Informe</h2>
                            <p class="text-gray-600">{{ $fotos_informe->count() }} fotos adjuntas</p>
                        </div>

                        @if($fotos_informe->count() > 0)
                            <!-- Grid de fotos -->
                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                @foreach($fotos_informe as $foto)
                                    <div class="foto-thumbnail"
                                        onclick="abrirFoto('{{ asset('storage/' . $foto->ruta_archivo) }}', '{{ $foto->nombre_original }}', '{{ $foto->comentario ?? '' }}')">
                                        <img src="{{ asset('storage/' . $foto->ruta_archivo) }}" alt="{{ $foto->nombre_original }}"
                                            class="w-full h-32 object-cover rounded-lg border border-gray-200">
                                        <div class="p-2 space-y-1">
                                            <p class="text-xs text-gray-600 truncate" title="{{ $foto->nombre_original }}">
                                                {{ $foto->nombre_original }}
                                            </p>
                                            @if($foto->comentario)
                                                <div class="text-xs text-blue-600 bg-blue-50 p-1 rounded border border-blue-200"
                                                    title="{{ $foto->comentario }}">
                                                    <i data-lucide="message-circle"
                                                        class="h-3 w-3 inline mr-1"></i>{{ Str::limit($foto->comentario, 40, '...') }}
                                                </div>
                                            @else
                                                <p class="text-xs text-gray-400 italic">Sin comentario</p>
                                            @endif
                                            <p class="text-xs text-gray-400">
                                                {{ \Carbon\Carbon::parse($foto->fecha_subida)->format('d/m H:i') }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8 text-gray-500">
                                <i data-lucide="image" class="h-16 w-16 mx-auto mb-4 text-gray-300"></i>
                                <p>Sin fotos adjuntas en este informe</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para fotos -->
        <div id="modal-foto" class="modal">
            <div class="relative max-w-6xl max-h-full flex flex-col">
                <div class="relative flex-shrink-0">
                    <img id="foto-ampliada" src="" alt="Foto ampliada" class="max-w-full max-h-[80vh] object-contain">
                    <button onclick="cerrarFoto()"
                        class="absolute top-4 right-4 text-white bg-black bg-opacity-50 rounded-full p-2 hover:bg-opacity-75 transition-all">
                        <i data-lucide="x" class="w-6 h-6"></i>
                    </button>
                </div>
                <div id="foto-info" class="bg-white text-gray-800 px-6 py-4 border-t border-gray-200 rounded-b-lg">
                    <!-- Información de la foto se carga dinámicamente -->
                </div>
            </div>
        </div>

        <!-- Modal para mostrar detalle de planilla individual -->
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
    <script>
        // Inicializar iconos Lucide
        lucide.createIcons();

        // Función para cambiar tabs
        function cambiarTab(tabId, button) {
            // Remover active de todos los tabs y botones
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });
            document.querySelectorAll('.tab-button').forEach(btn => {
                btn.classList.remove('active');
            });

            // Activar el tab seleccionado
            document.getElementById(tabId).classList.add('active');
            button.classList.add('active');

            // Reinicializar iconos
            setTimeout(() => lucide.createIcons(), 100);
        }

        // Función para abrir foto en modal
        function abrirFoto(src, nombre, comentario = '') {
            const modal = document.getElementById('modal-foto');
            const img = document.getElementById('foto-ampliada');
            const info = document.getElementById('foto-info');

            img.src = src;

            // Crear contenido con nombre y comentario
            let contenidoInfo = `<p class="font-medium text-gray-900">${nombre}</p>`;
            if (comentario) {
                contenidoInfo += `<p class="text-sm text-blue-600 mt-2 bg-blue-50 p-2 rounded border-l-4 border-blue-400"><i data-lucide="message-circle" class="h-4 w-4 inline mr-1"></i>${comentario}</p>`;
            } else {
                contenidoInfo += `<p class="text-sm text-gray-500 mt-1 italic">Sin comentario</p>`;
            }

            info.innerHTML = contenidoInfo;
            modal.classList.add('active');

            setTimeout(() => lucide.createIcons(), 100);
        }

        // Función para cerrar foto
        function cerrarFoto() {
            const modal = document.getElementById('modal-foto');
            modal.classList.remove('active');
        }

        // Cerrar modal al hacer clic fuera de la imagen     (con verificación)
        const modalFotoElement = document.getElementById('modal-foto');
        if (modalFotoElement) {
            modalFotoElement.addEventListener('click', function (e) {
                if (e.target === this) {
                    cerrarFoto();
                }
            });
        }

        // Cerrar modal con tecla Escape
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                cerrarFoto();
            }
        });

        // Función para cerrar modal de detalle de planilla (sin animaciones)
        function cerrarModalDetallePlanilla() {
            const modal = document.getElementById('modalDetallePlanilla');

            // Cerrar instantáneamente
            modal.classList.add('hidden');
            modal.classList.remove('flex');

            // Limpiar iframe
            document.getElementById("iframePlanillaDetalle").src = '';
        }

        // Función para abrir modal de detalle de planilla (sin animaciones)
        function abrirModalDetallePlanilla(codPlanilla) {
            const url = "{{ url('/ver-planilla/') }}/" + codPlanilla;
            const modal = document.getElementById('modalDetallePlanilla');
            const iframe = document.getElementById("iframePlanillaDetalle");

            // Configurar iframe y mostrar instantáneamente
            iframe.src = url;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        // Cerrar modal de detalle de planilla al hacer clic fuera (con verificación)
        const modalDetallePlanillaElement = document.getElementById('modalDetallePlanilla');
        if (modalDetallePlanillaElement) {
            modalDetallePlanillaElement.addEventListener('click', function (e) {
                if (e.target === this) {
                    cerrarModalDetallePlanilla();
                }
            });
        }

        // Cerrar modal con tecla Escape (solo si no hay otro modal abierto)
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                const modalFoto = document.getElementById('modal-foto');
                const modalPlanilla = document.getElementById('modalDetallePlanilla');

                // Priorizar cerrar modal de foto si está abierto (con verificación)
                if (modalFoto && modalFoto.classList.contains('active')) {
                    cerrarFoto();
                } else if (modalPlanilla && !modalPlanilla.classList.contains('hidden')) {
                    cerrarModalDetallePlanilla();
                }
            }
        });

        // Función para activar el primer tab de forma más robusta
        function activarPrimerTab() {
            console.log('🔍 Buscando primer tab...');

            // Buscar todos los elementos
            const allTabButtons = document.querySelectorAll('.tab-button');
            const allTabContents = document.querySelectorAll('.tab-content');

            console.log('📊 Elementos encontrados:', {
                buttons: allTabButtons.length,
                contents: allTabContents.length
            });

            if (allTabButtons.length > 0 && allTabContents.length > 0) {
                const primerButton = allTabButtons[0];
                const primerContent = allTabContents[0];

                console.log('🎯 Elementos seleccionados:', {
                    button: primerButton.textContent?.trim(),
                    content: primerContent.id
                });

                // Limpiar todos los estados activos
                allTabContents.forEach(tab => {
                    tab.classList.remove('active');
                });
                allTabButtons.forEach(btn => {
                    btn.classList.remove('active');
                });

                // Activar el primer tab
                primerButton.classList.add('active');
                primerContent.classList.add('active');

                console.log('✅ ¡Primer tab activado correctamente!');

                // Reinicializar iconos
                setTimeout(() => {
                    lucide.createIcons();
                    console.log('🔄 Iconos de Lucide reinicializados');
                }, 100);

                return true;
            }

            console.log('❌ No se encontraron elementos tab válidos');
            return false;
        }

        // Intentar activar múltiples veces para asegurar que funcione
        let intentos = 0;
        const maxIntentos = 10;

        function intentarActivacion() {
            intentos++;
            console.log(`🚀 Intento ${intentos}/${maxIntentos} de activar primer tab`);

            if (activarPrimerTab()) {
                console.log('🎉 ¡Éxito! Primer tab activado');
                return;
            }

            if (intentos < maxIntentos) {
                setTimeout(intentarActivacion, 200 * intentos); // Incrementar delay
            } else {
                console.error('💥 Error: No se pudo activar el primer tab después de múltiples intentos');
            }
        }

        // Iniciar inmediatamente
        setTimeout(intentarActivacion, 50);

        // También cuando el DOM esté listo
        document.addEventListener('DOMContentLoaded', intentarActivacion);

        // Y cuando todo esté cargado
        window.addEventListener('load', intentarActivacion);

        console.log('Vista de informe con tabs por sala cargada correctamente');
        console.log('Datos disponibles:', {
            informe: @json($informe->cod_informe ?? 'N/A'),
            salas: {{ count($informacion_sala) }},
            comentarios: {{ $comentarios_salas->count() }},
            fotos: {{ $fotos_informe->count() }}
        });
    </script>
@endsection