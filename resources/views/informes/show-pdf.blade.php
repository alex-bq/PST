<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informe de Turno - {{ \Carbon\Carbon::parse($fecha)->format('d/m/Y') }}</title>
    <style>
        /* CSS optimizado para PDF con control de saltos */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 14px;
            line-height: 1.4;
            color: #333;
            background: #f9fafb;
        }

        /* CONTROL DE SALTOS DE PÁGINA OPTIMIZADO */
        .no-break {
            page-break-inside: avoid;
        }

        .break-before {
            page-break-before: always;
        }

        .break-after {
            page-break-after: always;
        }

        .keep-together {
            page-break-inside: avoid;
            orphans: 3;
            widows: 3;
        }

        /* CONTROL GLOBAL DE HUÉRFANAS Y VIUDAS */
        p,
        div,
        table {
            orphans: 2;
            widows: 2;
        }

        /* TABLAS: Permitir división pero mantener headers */
        .table {
            page-break-inside: auto;
        }

        .table thead {
            page-break-inside: avoid;
            page-break-after: avoid;
        }

        .table tbody tr {
            page-break-inside: avoid;
        }

        .container {
            max-width: 100%;
            margin: 0 auto;
            padding: 8px;
        }

        /* HEADER ULTRA COMPACTO */
        .header {
            background: white;
            padding: 8px 12px;
            margin-bottom: 8px;
            border: 1px solid #e5e7eb;
            border-radius: 4px;
            page-break-inside: avoid;
        }

        .header h1 {
            font-size: 20px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 4px;
        }

        .header-info {
            display: flex;
            justify-content: space-between;
            gap: 10px;
        }

        .header-left p,
        .header-right p {
            margin: 1px 0;
            font-size: 13px;
            line-height: 1.3;
        }

        .header-right {
            text-align: right;
            background: #f3f4f6;
            padding: 4px 6px;
            border-radius: 3px;
            min-width: 120px;
        }

        /* SALAS COMPACTAS - PUEDEN DIVIDIRSE ENTRE PÁGINAS */
        .sala-card {
            background: white;
            border-radius: 6px;
            margin-bottom: 12px;
            border: 1px solid #e5e7eb;
            /* Removido page-break-inside: avoid para permitir división */
        }

        .sala-header {
            background: #f8fafc;
            border-bottom: 1px solid #e5e7eb;
            padding: 8px 12px;
            border-radius: 6px 6px 0 0;
        }

        .sala-title {
            font-size: 17px;
            font-weight: bold;
            color: #1f2937;
        }

        .sala-subtitle {
            font-size: 14px;
            color: #6b7280;
            margin-top: 1px;
        }

        .sala-content {
            padding: 10px;
        }

        /* COMENTARIOS COMPACTOS */
        .comentarios-section {
            background: #dbeafe;
            border: 1px solid #3b82f6;
            border-radius: 6px;
            padding: 8px;
            margin-bottom: 8px;
        }

        .comentarios-title {
            font-weight: bold;
            color: #1d4ed8;
            margin-bottom: 4px;
            font-size: 15px;
        }

        .comentarios-content {
            background: white;
            padding: 6px;
            border-radius: 3px;
            border: 1px solid #3b82f6;
            white-space: pre-wrap;
            font-size: 13px;
        }

        /* PROCESO HEADERS COMPACTOS */
        .proceso-header {
            background: #f3f4f6;
            padding: 6px 8px;
            margin-bottom: 8px;
            border-radius: 4px;
            font-size: 16px;
            font-weight: bold;
            color: #374151;
        }

        /* EMPRESA CARDS COMPACTAS - EVITAR SALTOS SOLO EN ELEMENTOS PEQUEÑOS */
        .empresa-card {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 10px;
            margin-bottom: 12px;
            /* Removido page-break-inside: avoid para elementos grandes */
        }

        .empresa-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
            padding-bottom: 6px;
            border-bottom: 1px solid #e5e7eb;
        }

        .empresa-name {
            font-size: 17px;
            font-weight: bold;
            color: #1f2937;
        }

        .empresa-stats {
            font-size: 14px;
            color: #6b7280;
        }

        /* MÉTRICAS USANDO FLEXBOX - COMPATIBLE CON DOMPDF */
        .metricas-grid {
            display: flex;
            flex-wrap: wrap;
            margin-bottom: 15px;
            margin-left: -4px;
            margin-right: -4px;
        }

        .metrica-card {
            background: #f9fafb;
            padding: 10px;
            border-radius: 8px;
            text-align: center;
            border: 1px solid #e5e7eb;
            flex: 1;
            min-width: 80px;
            max-width: 120px;
            margin: 0 4px 8px 4px;
        }

        .metrica-label {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 2px;
        }

        .metrica-value {
            font-size: 16px;
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
            font-size: 12px;
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
            font-size: 16px;
        }

        /* PRODUCTIVIDADES USANDO FLEXBOX - COMPATIBLE CON DOMPDF */
        .productividades-grid {
            display: flex;
            flex-wrap: wrap;
            margin-left: -4px;
            margin-right: -4px;
        }

        .productividad-card {
            background: #f9fafb;
            padding: 8px;
            border-radius: 6px;
            text-align: center;
            border: 1px solid #e5e7eb;
            flex: 1;
            min-width: 100px;
            max-width: 140px;
            margin: 0 4px 6px 4px;
        }

        .productividad-card.border-green {
            border-left: 3px solid #10b981;
        }

        .productividad-card.border-blue {
            border-left: 3px solid #3b82f6;
        }

        .productividad-label {
            font-size: 12px;
            color: #6b7280;
            margin-bottom: 1px;
        }

        .productividad-value {
            font-size: 15px;
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
            font-size: 12px;
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
            font-size: 16px;
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
            font-size: 13px;
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
            font-size: 12px;
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
            font-size: 14px;
        }

        .tiempo-duracion {
            color: #dc2626;
            font-weight: bold;
            font-size: 14px;
        }

        .tiempo-descripcion {
            font-size: 13px;
            color: #6b7280;
            margin-top: 2px;
        }

        .tiempo-resumen {
            background: #f9fafb;
            padding: 6px;
            margin-top: 6px;
            border-radius: 4px;
            font-size: 13px;
        }

        /* FOTOS SECTION */
        .fotos-section {
            margin-top: 20px;
            page-break-before: always;
        }

        .fotos-header {
            background: #7c3aed;
            color: white;
            padding: 15px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 20px;
            text-align: center;
        }

        .fotos-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 20px;
        }

        /* Layout especial para pocas fotos (1-2 fotos) */
        .fotos-grid.single-photo {
            grid-template-columns: 1fr;
            max-width: 600px;
            margin: 0 auto;
        }

        .fotos-grid.single-photo .foto-img {
            max-height: 500px;
            max-width: 500px;
        }

        .foto-item {
            text-align: center;
            page-break-inside: avoid;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 15px;
            background: #f9fafb;
        }

        .foto-img {
            max-width: 100%;
            max-height: 300px;
            border: 2px solid #d1d5db;
            border-radius: 8px;
            margin: 0 auto 10px auto;
            background: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            display: block;
            image-rendering: -webkit-optimize-contrast;
            image-rendering: crisp-edges;
        }

        /* Fotos verticales (retratos) */
        .foto-img.vertical {
            max-height: 400px;
            max-width: 280px;
        }

        /* Fotos horizontales (paisajes) */
        .foto-img.horizontal {
            max-height: 250px;
            max-width: 100%;
        }

        .foto-error {
            width: 100%;
            min-height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
            border: 2px dashed #fca5a5;
            border-radius: 8px;
            background: #fef2f2;
        }

        .foto-info {
            font-size: 14px;
            color: #374151;
            line-height: 1.4;
            padding: 8px;
            background: white;
            border-radius: 6px;
            border: 1px solid #e5e7eb;
        }

        .foto-info .foto-nombre {
            font-weight: bold;
            font-size: 15px;
            color: #1f2937;
            margin-bottom: 4px;
            word-wrap: break-word;
        }

        .foto-info .foto-fecha {
            color: #6b7280;
            font-size: 13px;
            margin-bottom: 4px;
        }

        .foto-info .foto-comentario {
            color: #4b5563;
            font-size: 13px;
            font-style: italic;
            background: #f3f4f6;
            padding: 4px 6px;
            border-radius: 4px;
            margin-top: 4px;
            border-left: 3px solid #7c3aed;
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
            font-size: 12px;
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

                                // NUEVA VARIABLE: Calcular entrega de materia prima para esta empresa
                                $entrega_mp = $planillas_para_dotacion->sum('kilos_entrega') ?? 0;

                                $horas_efectivas = $horas_efectivas > 0 ? $horas_efectivas : 1;
                                $horas_turno = $horas_turno > 0 ? $horas_turno : 1;

                                $productividad_objetivo_efectivas = round($kilos_objetivo / ($dotacion_max * $horas_efectivas), 2);
                                $productividad_objetivo_turno = round($kilos_objetivo / ($dotacion_max * $horas_turno), 2);
                                $productividad_total_efectivas = round($kilos_pst_total / ($dotacion_max * $horas_efectivas), 2);
                                $productividad_total_turno = round($kilos_pst_total / ($dotacion_max * $horas_turno), 2);

                                // NUEVO CÁLCULO: Rendimiento (PST Objetivo / Entrega MP) × 100
                                $rendimiento = $entrega_mp > 0 ? round(($kilos_objetivo / $entrega_mp) * 100, 2) : 0;

                                $planillas_unicas = $productos_empresa->pluck('n_planilla')->unique();
                                $tiempos_muertos_sala = collect($tiempos_muertos)->where('cod_sala', $sala->cod_sala);
                            @endphp

                            <div class="empresa-card">
                                <!-- Header de empresa -->
                                <div class="empresa-header">
                                    <div>
                                        <span class="empresa-name">🏢 {{ $empresa_nombre }}</span>
                                    </div>
                                    <div class="empresa-stats">
                                        {{ $productos_empresa->count() }} productos | {{ $planillas_unicas->count() }} planillas
                                    </div>
                                </div>

                                <!-- Métricas en Tabla -->
                                <div class="detalle-section metricas">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Dotación</th>
                                                <th>Horas Efectivas</th>
                                                <th>Horas Turno</th>
                                                <th>Entrega MP</th>
                                                <th>PST Objetivo</th>
                                                <th>PST Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="text-center">
                                                    <strong>{{ $dotacion_max }}</strong><br>
                                                    <small style="color: #6b7280;">personas</small>
                                                </td>
                                                <td class="text-center">
                                                    <strong>{{ number_format($horas_efectivas, 1) }}</strong><br>
                                                    <small style="color: #6b7280;">horas</small>
                                                </td>
                                                <td class="text-center">
                                                    <strong style="color: #2563eb;">{{ number_format($horas_turno, 1) }}</strong><br>
                                                    <small style="color: #6b7280;">horas</small>
                                                </td>
                                                <td class="text-center">
                                                    <strong style="color: #7c3aed;">{{ number_format($entrega_mp, 0) }}</strong><br>
                                                    <small style="color: #6b7280;">kg</small>
                                                </td>
                                                <td class="text-center">
                                                    <strong style="color: #059669;">{{ number_format($kilos_objetivo, 0) }}</strong><br>
                                                    <small style="color: #6b7280;">kg</small>
                                                </td>
                                                <td class="text-center">
                                                    <strong
                                                        style="color: #2563eb;">{{ number_format($kilos_pst_total, 0) }}</strong><br>
                                                    <small style="color: #6b7280;">kg</small>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Productividades en Tabla -->
                                <div class="detalle-section productividades">
                                    <div class="detalle-title">📊 Productividades (kg/persona/hora) y Rendimiento</div>
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Tipo</th>
                                                <th>Objetivo + Efectivas</th>
                                                <th>Objetivo + Turno</th>
                                                <th>Total + Efectivas</th>
                                                <th>Total + Turno</th>
                                                <th>Rendimiento</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><strong>Valor</strong></td>
                                                <td class="text-center">
                                                    <strong style="color: #059669;">{{ $productividad_objetivo_efectivas }}</strong>
                                                </td>
                                                <td class="text-center">
                                                    <strong style="color: #059669;">{{ $productividad_objetivo_turno }}</strong>
                                                </td>
                                                <td class="text-center">
                                                    <strong style="color: #2563eb;">{{ $productividad_total_efectivas }}</strong>
                                                </td>
                                                <td class="text-center">
                                                    <strong style="color: #2563eb;">{{ $productividad_total_turno }}</strong>
                                                </td>
                                                <td class="text-center">
                                                    <strong style="color: #ea580c;">{{ $rendimiento }}%</strong>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>Cálculo</strong></td>
                                                <td class="text-center">
                                                    <small style="color: #6b7280;">{{ number_format($kilos_objetivo, 0) }}kg ÷
                                                        ({{ $dotacion_max }} × {{ number_format($horas_efectivas, 1) }}h)</small>
                                                </td>
                                                <td class="text-center">
                                                    <small style="color: #6b7280;">{{ number_format($kilos_objetivo, 0) }}kg ÷
                                                        ({{ $dotacion_max }} × {{ number_format($horas_turno, 1) }}h)</small>
                                                </td>
                                                <td class="text-center">
                                                    <small style="color: #6b7280;">{{ number_format($kilos_pst_total, 0) }}kg ÷
                                                        ({{ $dotacion_max }} × {{ number_format($horas_efectivas, 1) }}h)</small>
                                                </td>
                                                <td class="text-center">
                                                    <small style="color: #6b7280;">{{ number_format($kilos_pst_total, 0) }}kg ÷
                                                        ({{ $dotacion_max }} × {{ number_format($horas_turno, 1) }}h)</small>
                                                </td>
                                                <td class="text-center">
                                                    <small style="color: #6b7280;">{{ number_format($kilos_objetivo, 0) }}kg ÷
                                                        {{ number_format($entrega_mp, 0) }}kg × 100</small>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Productos Detallados -->
                                @if($productos_empresa->count() > 0)
                                    <div class="detalle-section productos">
                                        <div class="detalle-title productos">📦 Productos ({{ $productos_empresa->count() }})</div>
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Especie</th>
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
                                                        // REVERTIDO: Nombre del producto sin especie
                                                        $nombre_producto = ($producto->corte_inicial ?? '') . ' → ' . ($producto->corte_final ?? '');
                                                        if (isset($producto->calibre) && $producto->calibre !== 'SIN CALIBRE') {
                                                            $nombre_producto .= ' → ' . $producto->calibre;
                                                        }
                                                    @endphp
                                                    <tr>
                                                        <td>{{ $producto->especie ?? 'Sin especie' }}</td>
                                                        <td>{{ $nombre_producto }}</td>
                                                        <td>{{ $producto->calidad ?? '' }}</td>
                                                        <td>{{ $producto->destino ?? '' }}</td>
                                                        <td class="text-right">{{ number_format($producto->kilos ?? 0, 1) }}</td>
                                                        <td class="text-right">{{ number_format($porcentaje, 1) }}%</td>
                                                        <td class="text-center">{{ $producto->es_producto_objetivo == 1 ? 'SÍ' : 'NO' }}</td>
                                                    </tr>
                                                @endforeach
                                                <tr class="table-total">
                                                    <td colspan="4"><strong>TOTAL</strong></td>
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
                📸 Fotos del Informe ({{ $fotos_informe->count() }}
                {{ $fotos_informe->count() == 1 ? 'foto' : 'fotos' }})
                @if($fotos_informe->count() <= 2)
                    - Vista Ampliada
                @endif
            </div>
            <div class="fotos-grid{{ $fotos_informe->count() <= 2 ? ' single-photo' : '' }}">
                @foreach($fotos_informe as $foto)
                    @php
                        // Construir ruta completa al archivo
                        $ruta_completa = storage_path('app/public/' . $foto->ruta_archivo);
                        $imagen_base64 = null;
                        $es_vertical = false; // Inicializar variable

                        // Verificar que el archivo existe y leer como Base64
                        if (file_exists($ruta_completa)) {
                            try {
                                $extension = pathinfo($foto->ruta_archivo, PATHINFO_EXTENSION);
                                $mime_type = '';

                                // Determinar tipo MIME
                                switch (strtolower($extension)) {
                                    case 'jpg':
                                    case 'jpeg':
                                        $mime_type = 'image/jpeg';
                                        break;
                                    case 'png':
                                        $mime_type = 'image/png';
                                        break;
                                    case 'gif':
                                        $mime_type = 'image/gif';
                                        break;
                                    default:
                                        $mime_type = 'image/jpeg'; // fallback
                                }

                                // PROCESAR IMAGEN PARA MANTENER PROPORCIONES
                                $imagen_original = imagecreatefromstring(file_get_contents($ruta_completa));
                                if ($imagen_original) {
                                    // Obtener dimensiones originales
                                    $ancho_original = imagesx($imagen_original);
                                    $alto_original = imagesy($imagen_original);

                                    // Determinar orientación de la imagen
                                    $es_vertical = $alto_original > $ancho_original;

                                    // Calcular nuevas dimensiones manteniendo proporciones exactas
                                    if ($es_vertical) {
                                        // Para fotos verticales: limitar altura máxima
                                        $alto_maximo = 400;
                                        $ancho_maximo = 600; // Mayor ancho permitido
                                    } else {
                                        // Para fotos horizontales: limitar ancho máximo
                                        $ancho_maximo = 600;
                                        $alto_maximo = 300;
                                    }

                                    // Calcular ratio manteniendo proporción exacta
                                    $ratio_ancho = $ancho_maximo / $ancho_original;
                                    $ratio_alto = $alto_maximo / $alto_original;
                                    $ratio = min($ratio_ancho, $ratio_alto, 1); // No agrandar imágenes pequeñas

                                    $ancho_nuevo = round($ancho_original * $ratio);
                                    $alto_nuevo = round($alto_original * $ratio);

                                    // Crear nueva imagen redimensionada
                                    $imagen_redimensionada = imagecreatetruecolor($ancho_nuevo, $alto_nuevo);

                                    // Preservar transparencia para PNG
                                    if (strtolower($extension) === 'png') {
                                        imagealphablending($imagen_redimensionada, false);
                                        imagesavealpha($imagen_redimensionada, true);
                                        $transparente = imagecolorallocatealpha($imagen_redimensionada, 255, 255, 255, 127);
                                        imagefilledrectangle($imagen_redimensionada, 0, 0, $ancho_nuevo, $alto_nuevo, $transparente);
                                    }

                                    // Redimensionar
                                    imagecopyresampled($imagen_redimensionada, $imagen_original, 0, 0, 0, 0, $ancho_nuevo, $alto_nuevo, $ancho_original, $alto_original);

                                    // Capturar output como string
                                    ob_start();
                                    if (strtolower($extension) === 'png') {
                                        imagepng($imagen_redimensionada);
                                    } else {
                                        imagejpeg($imagen_redimensionada, null, 95);
                                    }
                                    $imagen_contenido = ob_get_clean();

                                    // Limpiar memoria
                                    imagedestroy($imagen_original);
                                    imagedestroy($imagen_redimensionada);

                                    $imagen_base64 = 'data:' . $mime_type . ';base64,' . base64_encode($imagen_contenido);
                                } else {
                                    // Fallback: usar imagen original sin procesar
                                    $imagen_contenido = file_get_contents($ruta_completa);
                                    $imagen_base64 = 'data:' . $mime_type . ';base64,' . base64_encode($imagen_contenido);

                                    // Para el fallback, también necesitamos determinar orientación
                                    $imagen_info = getimagesize($ruta_completa);
                                    if ($imagen_info) {
                                        $es_vertical = $imagen_info[1] > $imagen_info[0];
                                    }
                                }
                            } catch (Exception $e) {
                                // Error al leer archivo
                                $imagen_base64 = null;
                            }
                        }
                    @endphp

                    <div class="foto-item no-break">
                        @if($imagen_base64)
                            <img src="{{ $imagen_base64 }}" alt="{{ $foto->nombre_original }}"
                                class="foto-img{{ $es_vertical ? ' vertical' : ' horizontal' }}">
                        @else
                            <div class="foto-error">
                                <div style="text-align: center;">
                                    <div style="color: #dc2626; font-size: 16px; font-weight: bold; margin-bottom: 8px;">❌ Imagen no
                                        disponible</div>
                                    <div style="color: #7f1d1d; font-size: 14px; word-wrap: break-word;">
                                        {{ $foto->nombre_original }}
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="foto-info">
                            <div class="foto-nombre">{{ $foto->nombre_original }}</div>
                            <div class="foto-fecha">📅 {{ \Carbon\Carbon::parse($foto->fecha_subida)->format('d/m/Y H:i') }}
                            </div>
                            @if($foto->comentario)
                                <div class="foto-comentario">
                                    💬 {{ $foto->comentario }}
                                </div>
                            @else
                                <div style="color: #9ca3af; font-size: 13px; margin-top: 4px;">Sin comentario</div>
                            @endif
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