<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informe de Turno - {{ \Carbon\Carbon::parse($fecha)->format('d/m/Y') }}</title>
    <style>
        /* CSS optimizado para PDF */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 10px;
            line-height: 1.3;
            color: #333;
            background: #f9fafb;
        }

        .container {
            max-width: 100%;
            margin: 0 auto;
            padding: 20px;
        }

        /* HEADER COMPACTO */
        .header {
            background: white;
            padding: 15px;
            margin-bottom: 15px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .header h1 {
            font-size: 16px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 8px;
        }

        .header-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .header-left p,
        .header-right p {
            margin: 2px 0;
            font-size: 10px;
        }

        .header-right {
            text-align: right;
            background: #f3f4f6;
            padding: 8px;
            border-radius: 4px;
        }

        /* SALAS - DISEÑO IDÉNTICO A SHOW.BLADE.PHP */
        .sala-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            border: 1px solid #e5e7eb;
            page-break-inside: avoid;
        }

        .sala-header {
            background: #f8fafc;
            border-bottom: 1px solid #e5e7eb;
            padding: 15px;
            border-radius: 8px 8px 0 0;
        }

        .sala-title {
            font-size: 14px;
            font-weight: bold;
            color: #1f2937;
        }

        .sala-subtitle {
            font-size: 10px;
            color: #6b7280;
            margin-top: 2px;
        }

        .sala-content {
            padding: 15px;
        }

        /* COMENTARIOS - IDÉNTICO AL ORIGINAL */
        .comentarios-section {
            background: #dbeafe;
            border: 1px solid #3b82f6;
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 15px;
        }

        .comentarios-title {
            font-weight: bold;
            color: #1d4ed8;
            margin-bottom: 8px;
            font-size: 11px;
        }

        .comentarios-content {
            background: white;
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #3b82f6;
            white-space: pre-wrap;
            font-size: 9px;
        }

        /* PROCESO HEADERS */
        .proceso-header {
            background: #f3f4f6;
            padding: 10px;
            margin-bottom: 12px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: bold;
            color: #374151;
        }

        /* EMPRESA CARDS - IDÉNTICO AL ORIGINAL */
        .empresa-card {
            background: white;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border: 1px solid #e5e7eb;
            margin-bottom: 15px;
            page-break-inside: avoid;
        }

        .empresa-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 15px;
        }

        .empresa-name {
            background: #dbeafe;
            color: #1e40af;
            padding: 6px 12px;
            border-radius: 6px;
            font-weight: bold;
            font-size: 11px;
        }

        .empresa-stats {
            font-size: 9px;
            color: #6b7280;
        }

        /* MÉTRICAS GRID - IDÉNTICO AL ORIGINAL */
        .metricas-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 8px;
            margin-bottom: 15px;
        }

        .metrica-card {
            background: #f9fafb;
            padding: 10px;
            border-radius: 8px;
            text-align: center;
            border: 1px solid #e5e7eb;
        }

        .metrica-label {
            font-size: 8px;
            color: #6b7280;
            margin-bottom: 2px;
        }

        .metrica-value {
            font-size: 12px;
            font-weight: bold;
            color: #1f2937;
        }

        .metrica-value.text-blue {
            color: #2563eb;
        }

        .metrica-value.text-green {
            color: #059669;
        }

        .metrica-unit {
            font-size: 7px;
            color: #6b7280;
            margin-top: 1px;
        }

        /* PRODUCTIVIDADES - IDÉNTICO AL ORIGINAL */
        .productividades-section {
            background: #fef3c7;
            border: 1px solid #f59e0b;
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 15px;
        }

        .productividades-title {
            font-weight: bold;
            color: #92400e;
            margin-bottom: 8px;
            font-size: 10px;
        }

        .productividades-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 6px;
        }

        .productividad-card {
            background: white;
            border: 1px solid #f59e0b;
            border-radius: 6px;
            padding: 6px;
            text-align: center;
        }

        .productividad-card.border-green {
            border-left: 3px solid #10b981;
        }

        .productividad-card.border-blue {
            border-left: 3px solid #3b82f6;
        }

        .productividad-label {
            font-size: 7px;
            color: #6b7280;
            margin-bottom: 1px;
        }

        .productividad-value {
            font-size: 11px;
            font-weight: bold;
            color: #1f2937;
        }

        .productividad-value.text-green {
            color: #059669;
        }

        .productividad-value.text-blue {
            color: #2563eb;
        }

        .productividad-formula {
            font-size: 6px;
            color: #6b7280;
            margin-top: 1px;
        }

        /* SECCIONES DE DETALLES */
        .detalle-section {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 12px;
        }

        .detalle-section.productos {
            background: #eff6ff;
            border-color: #3b82f6;
        }

        .detalle-section.tiempos {
            background: #fef2f2;
            border-color: #ef4444;
        }

        .detalle-title {
            font-weight: bold;
            margin-bottom: 8px;
            font-size: 10px;
        }

        .detalle-title.productos {
            color: #1e40af;
        }

        .detalle-title.tiempos {
            color: #dc2626;
        }

        /* TABLAS - IDÉNTICO AL ORIGINAL */
        .table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8px;
        }

        .table th,
        .table td {
            border: 1px solid #d1d5db;
            padding: 4px 6px;
            text-align: left;
        }

        .table th {
            background: #f9fafb;
            font-weight: bold;
            font-size: 7px;
            color: #6b7280;
            text-transform: uppercase;
        }

        .table tr:hover {
            background: #f9fafb;
        }

        .table-total {
            background: #f3f4f6 !important;
            font-weight: bold;
        }

        /* TIEMPO ITEMS */
        .tiempo-item {
            background: white;
            border: 1px solid #fecaca;
            border-radius: 4px;
            padding: 6px;
            margin-bottom: 4px;
        }

        .tiempo-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .tiempo-motivo {
            font-weight: bold;
            font-size: 8px;
        }

        .tiempo-duracion {
            color: #dc2626;
            font-weight: bold;
            font-size: 8px;
        }

        .tiempo-descripcion {
            font-size: 7px;
            color: #6b7280;
            margin-top: 2px;
        }

        .tiempo-resumen {
            background: #f9fafb;
            padding: 6px;
            margin-top: 6px;
            border-radius: 4px;
            font-size: 7px;
        }

        /* FOTOS SECTION */
        .fotos-section {
            margin-top: 20px;
            page-break-before: always;
        }

        .fotos-header {
            background: #7c3aed;
            color: white;
            padding: 12px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .fotos-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
        }

        .foto-item {
            text-align: center;
            page-break-inside: avoid;
        }

        .foto-img {
            width: 100%;
            max-height: 120px;
            object-fit: cover;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            margin-bottom: 4px;
        }

        .foto-info {
            font-size: 7px;
            color: #6b7280;
        }

        /* UTILIDADES */
        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .font-bold {
            font-weight: bold;
        }

        .no-break {
            page-break-inside: avoid;
        }

        /* FOOTER */
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 25px;
            background: #f9fafb;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            font-size: 7px;
            line-height: 25px;
            color: #6b7280;
        }
    </style>
</head>

<body>
    <!-- HEADER COMPACTO -->
    <div class="header no-break">
        <h1>📄 Informe de Turno - {{ \Carbon\Carbon::parse($fecha)->format('d/m/Y') }}</h1>
        <div class="header-info">
            <div class="header-left">
                <p><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($fecha)->format('d/m/Y') }}</p>
                <p><strong>Turno:</strong> {{ $informe->turno }}</p>
                <p><strong>Jefe de Turno:</strong> {{ $informe->jefe_turno_nom }}</p>
                @if(isset($informe->hora_inicio) && isset($informe->hora_fin))
                    <p><strong>Horario:</strong> {{ $informe->hora_inicio }} - {{ $informe->hora_fin }}
                        @if(isset($informe->horas_trabajadas))
                            ({{ number_format($informe->horas_trabajadas, 1) }}h)
                        @endif
                    </p>
                @endif
            </div>
            <div class="header-right">
                <p><strong>Estado:</strong> Completado</p>
                <p><strong>Código:</strong> #{{ $informe->cod_informe }}</p>
                @if(isset($informe->fecha_finalizacion))
                    <p><strong>Finalizado:</strong>
                        {{ \Carbon\Carbon::parse($informe->fecha_finalizacion)->format('d/m H:i') }}</p>
                @endif
                <p><strong>Generado:</strong> {{ \Carbon\Carbon::now()->format('d/m H:i') }}</p>
            </div>
        </div>
    </div>

    @php
        // Organizar datos por sala (igual que en show.blade.php)
        $salas_agrupadas = collect($informacion_sala)->groupBy('nombre_sala');
    @endphp

    <!-- SALAS CON DISEÑO IDÉNTICO AL ORIGINAL -->
    @foreach($salas_agrupadas as $sala_nombre => $datos_sala)
        <div class="sala-card">
            <div class="sala-header">
                <div class="sala-title">🏢 {{ $sala_nombre }}</div>
                <div class="sala-subtitle">
                    Sala {{ $datos_sala->first()->cod_sala }} - {{ $datos_sala->count() }}
                    proceso{{ $datos_sala->count() != 1 ? 's' : '' }} operativo{{ $datos_sala->count() != 1 ? 's' : '' }}
                </div>
            </div>
            <div class="sala-content">
                @php
                    $cod_sala_actual = $datos_sala->first()->cod_sala;
                @endphp

                <!-- COMENTARIOS DE LA SALA -->
                @if(isset($comentarios_salas[$cod_sala_actual]))
                    <div class="comentarios-section no-break">
                        <div class="comentarios-title">💬 Comentarios de {{ $sala_nombre }}</div>
                        <div class="comentarios-content">
                            @if($comentarios_salas[$cod_sala_actual]->comentarios)
                                {{ $comentarios_salas[$cod_sala_actual]->comentarios }}
                            @else
                                Sin comentarios registrados para esta sala.
                            @endif
                        </div>
                    </div>
                @endif

                <!-- PROCESOS POR SALA -->
                @foreach($datos_sala as $sala)
                    @php
                        // Obtener empresas para esta sala y tipo (igual lógica que show.blade.php)
                        $empresas_en_sala = collect($detalle_procesamiento)
                            ->where('cod_sala', $sala->cod_sala)
                            ->where('cod_tipo_planilla', $sala->cod_tipo_planilla)
                            ->pluck('descripcion')
                            ->unique();
                    @endphp

                    @if($empresas_en_sala->count() > 0)
                        <div class="proceso-header">
                            🔧 {{ $sala->tipo_planilla }} - {{ $empresas_en_sala->count() }}
                            empresa{{ $empresas_en_sala->count() != 1 ? 's' : '' }}
                        </div>

                        @foreach($empresas_en_sala as $empresa_nombre)
                            @php
                                // Misma lógica de cálculos que en show.blade.php
                                $productos_empresa = collect($detalle_procesamiento)
                                    ->where('cod_sala', $sala->cod_sala)
                                    ->where('cod_tipo_planilla', $sala->cod_tipo_planilla)
                                    ->where('descripcion', $empresa_nombre);

                                $planillas_para_dotacion = collect($planillas_detalle)->filter(function ($planilla) use ($empresa_nombre, $sala) {
                                    return isset($planilla->descripcion) &&
                                        trim($planilla->descripcion) === trim($empresa_nombre) &&
                                        $planilla->cod_sala == $sala->cod_sala &&
                                        $planilla->cod_tipo_planilla == $sala->cod_tipo_planilla;
                                });

                                $dotacion_max = $planillas_para_dotacion->max('dotacion') ?? 1;
                                $horas_efectivas = $sala->horas_trabajadas ?? 1;
                                $horas_turno = $informe->horas_trabajadas ?? 1;
                                $kilos_objetivo = $productos_empresa->where('es_producto_objetivo', 1)->sum('kilos') ?? 0;
                                $kilos_pst_total = $productos_empresa->sum('kilos') ?? 0;

                                $horas_efectivas = $horas_efectivas > 0 ? $horas_efectivas : 1;
                                $horas_turno = $horas_turno > 0 ? $horas_turno : 1;

                                $productividad_objetivo_efectivas = round($kilos_objetivo / ($dotacion_max * $horas_efectivas), 2);
                                $productividad_objetivo_turno = round($kilos_objetivo / ($dotacion_max * $horas_turno), 2);
                                $productividad_total_efectivas = round($kilos_pst_total / ($dotacion_max * $horas_efectivas), 2);
                                $productividad_total_turno = round($kilos_pst_total / ($dotacion_max * $horas_turno), 2);

                                $planillas_unicas = $productos_empresa->pluck('n_planilla')->unique();
                                $tiempos_muertos_sala = collect($tiempos_muertos)->where('cod_sala', $sala->cod_sala);
                            @endphp

                            <div class="empresa-card no-break">
                                <!-- Header de empresa -->
                                <div class="empresa-header">
                                    <div>
                                        <span class="empresa-name">🏢 {{ $empresa_nombre }}</span>
                                    </div>
                                    <div class="empresa-stats">
                                        {{ $productos_empresa->count() }} productos | {{ $planillas_unicas->count() }} planillas
                                    </div>
                                </div>

                                <!-- Métricas Grid -->
                                <div class="metricas-grid">
                                    <div class="metrica-card">
                                        <div class="metrica-label">Dotación</div>
                                        <div class="metrica-value">{{ $dotacion_max }}</div>
                                        <div class="metrica-unit">personas</div>
                                    </div>
                                    <div class="metrica-card">
                                        <div class="metrica-label">Horas Efectivas</div>
                                        <div class="metrica-value">{{ number_format($horas_efectivas, 1) }}</div>
                                        <div class="metrica-unit">horas</div>
                                    </div>
                                    <div class="metrica-card">
                                        <div class="metrica-label">Horas Turno</div>
                                        <div class="metrica-value text-blue">{{ number_format($horas_turno, 1) }}</div>
                                        <div class="metrica-unit">horas</div>
                                    </div>
                                    <div class="metrica-card">
                                        <div class="metrica-label">PST Objetivo</div>
                                        <div class="metrica-value text-green">{{ number_format($kilos_objetivo, 0) }}</div>
                                        <div class="metrica-unit">kg</div>
                                    </div>
                                    <div class="metrica-card">
                                        <div class="metrica-label">PST Total</div>
                                        <div class="metrica-value text-blue">{{ number_format($kilos_pst_total, 0) }}</div>
                                        <div class="metrica-unit">kg</div>
                                    </div>
                                </div>

                                <!-- Productividades -->
                                <div class="productividades-section">
                                    <div class="productividades-title">📊 Productividades (kg/persona/hora)</div>
                                    <div class="productividades-grid">
                                        <div class="productividad-card border-green">
                                            <div class="productividad-label">Objetivo + Efectivas</div>
                                            <div class="productividad-value text-green">{{ $productividad_objetivo_efectivas }}</div>
                                            <div class="productividad-formula">{{ number_format($kilos_objetivo, 0) }}kg ÷
                                                ({{ $dotacion_max }} × {{ number_format($horas_efectivas, 1) }}h)</div>
                                        </div>
                                        <div class="productividad-card border-green">
                                            <div class="productividad-label">Objetivo + Turno</div>
                                            <div class="productividad-value text-green">{{ $productividad_objetivo_turno }}</div>
                                            <div class="productividad-formula">{{ number_format($kilos_objetivo, 0) }}kg ÷
                                                ({{ $dotacion_max }} × {{ number_format($horas_turno, 1) }}h)</div>
                                        </div>
                                        <div class="productividad-card border-blue">
                                            <div class="productividad-label">Total + Efectivas</div>
                                            <div class="productividad-value text-blue">{{ $productividad_total_efectivas }}</div>
                                            <div class="productividad-formula">{{ number_format($kilos_pst_total, 0) }}kg ÷
                                                ({{ $dotacion_max }} × {{ number_format($horas_efectivas, 1) }}h)</div>
                                        </div>
                                        <div class="productividad-card border-blue">
                                            <div class="productividad-label">Total + Turno</div>
                                            <div class="productividad-value text-blue">{{ $productividad_total_turno }}</div>
                                            <div class="productividad-formula">{{ number_format($kilos_pst_total, 0) }}kg ÷
                                                ({{ $dotacion_max }} × {{ number_format($horas_turno, 1) }}h)</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Productos Detallados -->
                                @if($productos_empresa->count() > 0)
                                    <div class="detalle-section productos">
                                        <div class="detalle-title productos">📦 Productos ({{ $productos_empresa->count() }})</div>
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Producto</th>
                                                    <th>Calidad</th>
                                                    <th>Destino</th>
                                                    <th class="text-right">Kg</th>
                                                    <th class="text-right">%</th>
                                                    <th class="text-center">Objetivo</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $total_kilos_empresa = $productos_empresa->sum('kilos');
                                                @endphp
                                                @foreach($productos_empresa as $producto)
                                                    @php
                                                        $porcentaje = $total_kilos_empresa > 0 ? (($producto->kilos / $total_kilos_empresa) * 100) : 0;
                                                        $nombre_producto = ($producto->corte_inicial ?? '') . ' → ' . ($producto->corte_final ?? '');
                                                        if (isset($producto->calibre) && $producto->calibre !== 'SIN CALIBRE') {
                                                            $nombre_producto .= ' → ' . $producto->calibre;
                                                        }
                                                    @endphp
                                                    <tr>
                                                        <td>{{ $nombre_producto }}</td>
                                                        <td>{{ $producto->calidad ?? '' }}</td>
                                                        <td>{{ $producto->destino ?? '' }}</td>
                                                        <td class="text-right">{{ number_format($producto->kilos ?? 0, 1) }}</td>
                                                        <td class="text-right">{{ number_format($porcentaje, 1) }}%</td>
                                                        <td class="text-center">{{ $producto->es_producto_objetivo == 1 ? 'SÍ' : 'NO' }}</td>
                                                    </tr>
                                                @endforeach
                                                <tr class="table-total">
                                                    <td colspan="3"><strong>TOTAL</strong></td>
                                                    <td class="text-right"><strong>{{ number_format($productos_empresa->sum('kilos'), 1) }}
                                                            kg</strong></td>
                                                    <td class="text-right"><strong>100.0%</strong></td>
                                                    <td class="text-center">
                                                        <strong>{{ $productos_empresa->where('es_producto_objetivo', 1)->count() }}/{{ $productos_empresa->count() }}</strong>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                @endif

                                <!-- Tiempos Muertos -->
                                @if($tiempos_muertos_sala->count() > 0)
                                    <div class="detalle-section tiempos">
                                        <div class="detalle-title tiempos">⏰ Tiempos Muertos ({{ $tiempos_muertos_sala->count() }})</div>
                                        @foreach($tiempos_muertos_sala as $tiempo)
                                            <div class="tiempo-item">
                                                <div class="tiempo-header">
                                                    <span class="tiempo-motivo">{{ $tiempo->motivo ?? 'Sin motivo' }}</span>
                                                    <span class="tiempo-duracion">
                                                        {{ number_format(($tiempo->duracion_minutos ?? 0) / 60, 1) }}h
                                                        ({{ $tiempo->duracion_minutos ?? 0 }} min)
                                                    </span>
                                                </div>
                                                @if(isset($tiempo->descripcion))
                                                    <div class="tiempo-descripcion">{{ $tiempo->descripcion }}</div>
                                                @endif
                                            </div>
                                        @endforeach
                                        <div class="tiempo-resumen">
                                            <strong>Total tiempo perdido:</strong>
                                            {{ number_format($tiempos_muertos_sala->sum('duracion_minutos') / 60, 1) }}h
                                            | <strong>Promedio por evento:</strong>
                                            {{ $tiempos_muertos_sala->count() > 0 ? number_format($tiempos_muertos_sala->sum('duracion_minutos') / $tiempos_muertos_sala->count(), 1) : 0 }}
                                            min
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    @endif
                @endforeach
            </div>
        </div>
    @endforeach

    <!-- SECCIÓN DE FOTOS -->
    @if($fotos_informe->count() > 0)
        <div class="fotos-section">
            <div class="fotos-header">
                📸 Fotos del Informe ({{ $fotos_informe->count() }})
            </div>
            <div class="fotos-grid">
                @foreach($fotos_informe as $foto)
                    <div class="foto-item no-break">
                        <img src="{{ storage_path('app/public/' . $foto->ruta_archivo) }}" alt="{{ $foto->nombre_original }}"
                            class="foto-img">
                        <div class="foto-info">
                            <div><strong>{{ $foto->nombre_original }}</strong></div>
                            <div>{{ \Carbon\Carbon::parse($foto->fecha_subida)->format('d/m H:i') }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- FOOTER -->
    <div class="footer">
        Sistema de Informes PST - Generado el {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}
    </div>
</body>

</html>