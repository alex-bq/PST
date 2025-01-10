@extends('layouts.main-iframe')

@section('title', 'Detalle Turno Día')

@section('styles')
<style>
    .resumen-card {
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 30px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .resumen-title {
        color: #333;
        border-bottom: 2px solid #007bff;
        padding-bottom: 10px;
        margin-bottom: 20px;
    }

    .info-row {
        margin: 10px 0;
    }

    .info-label {
        font-weight: bold;
        color: #666;
    }

    .info-value {
        color: #333;
    }

    .accordion-button:not(.collapsed) {
        background-color: #e7f1ff;
    }

    .sala-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px;
    }

    .sala-info {
        padding: 15px;
    }
</style>
@endsection

@section('content')
<div class="container">
    <h1 class="text-center mb-4">Detalle Turno Día - 15/03/2024</h1>

    <!-- Resumen General -->
    <div class="resumen-card">
        <h3 class="resumen-title">Resumen General del Turno</h3>
        <div class="row">
            <div class="col-md-4">
                <div class="info-row">
                    <span class="info-label">Jefe de Turno:</span>
                    <span class="info-value">Juan Pérez</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Dotación Total:</span>
                    <span class="info-value">45 personas</span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-row">
                    <span class="info-label">Productividad Promedio:</span>
                    <span class="info-value">85.5%</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Rendimiento Promedio:</span>
                    <span class="info-value">92.3%</span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-row">
                    <span class="info-label">Total Kilos Entrega:</span>
                    <span class="info-value">4,500 kg</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Total Kilos Recepción:</span>
                    <span class="info-value">4,155 kg</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Acordeón de Salas -->
    <div class="accordion" id="salaAccordion">
        <!-- Sala 1 -->
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#sala1">
                    Sala 1
                </button>
            </h2>
            <div id="sala1" class="accordion-collapse collapse show" data-bs-parent="#salaAccordion">
                <div class="accordion-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-row">
                                <span class="info-label">Dotación:</span>
                                <span class="info-value">20 personas</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Productividad:</span>
                                <span class="info-value">86.2%</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Rendimiento:</span>
                                <span class="info-value">93.1%</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-row">
                                <span class="info-label">Kilos Entrega:</span>
                                <span class="info-value">2,000 kg</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Kilos Recepción:</span>
                                <span class="info-value">1,862 kg</span>
                            </div>
                        </div>

                        <!-- Tabla de Procesamiento -->
                        <div class="col-12 mt-4">
                            <h5>Detalle de Procesamiento</h5>
                            <div class="table-responsive">
                                <table class="table table-sm table-striped">
                                    <thead>
                                        <tr>
                                            <th>Corte Inicial</th>
                                            <th>Corte Final</th>
                                            <th>Destino</th>
                                            <th>Calibre</th>
                                            <th>Calidad</th>
                                            <th>Piezas</th>
                                            <th>Kilos</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>TRIM B</td>
                                            <td>TRIM C</td>
                                            <td>SIN DESTINO</td>
                                            <td>4-5</td>
                                            <td>INDUSTRIAL A</td>
                                            <td>199</td>
                                            <td>67</td>
                                        </tr>
                                        <tr>
                                            <td>TRIM E</td>
                                            <td>TRIM C</td>
                                            <td>SIN DESTINO</td>
                                            <td>4-5</td>
                                            <td>INDUSTRIAL B</td>
                                            <td>179</td>
                                            <td>76</td>
                                        </tr>
                                        <tr>
                                            <td>TRIM E</td>
                                            <td>TRIM D</td>
                                            <td>POR SELLAR</td>
                                            <td>2-3</td>
                                            <td>INDUSTRIAL B</td>
                                            <td>123</td>
                                            <td>84</td>
                                        </tr>
                                        <tr>
                                            <td>TRIM A</td>
                                            <td>TRIM E</td>
                                            <td>DESECHO</td>
                                            <td>3-4</td>
                                            <td>GRADO 1</td>
                                            <td>186</td>
                                            <td>92</td>
                                        </tr>
                                        <tr class="table-info">
                                            <td colspan="5"><strong>Total</strong></td>
                                            <td><strong>687</strong></td>
                                            <td><strong>319</strong></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Tiempos Muertos -->
                        <div class="col-12 mt-3">
                            <h5>Tiempos Muertos</h5>
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Motivo</th>
                                        <th>Minutos</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Falla Máquina</td>
                                        <td>45</td>
                                    </tr>
                                    <tr>
                                        <td>Cambio de Formato</td>
                                        <td>30</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Total</strong></td>
                                        <td><strong>75</strong></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sala 2 -->
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#sala2">
                    Sala 2
                </button>
            </h2>
            <div id="sala2" class="accordion-collapse collapse" data-bs-parent="#salaAccordion">
                <div class="accordion-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-row">
                                <span class="info-label">Dotación:</span>
                                <span class="info-value">15 personas</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Productividad:</span>
                                <span class="info-value">84.8%</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Rendimiento:</span>
                                <span class="info-value">91.5%</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-row">
                                <span class="info-label">Kilos Entrega:</span>
                                <span class="info-value">1,500 kg</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Kilos Recepción:</span>
                                <span class="info-value">1,373 kg</span>
                            </div>
                        </div>
                        <!-- Tiempos Muertos -->
                        <div class="col-12 mt-3">
                            <h5>Tiempos Muertos</h5>
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Motivo</th>
                                        <th>Minutos</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Falta Material</td>
                                        <td>25</td>
                                    </tr>
                                    <tr>
                                        <td>Reunión</td>
                                        <td>15</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Total</strong></td>
                                        <td><strong>40</strong></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sala 3 -->
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#sala3">
                    Sala 3
                </button>
            </h2>
            <div id="sala3" class="accordion-collapse collapse" data-bs-parent="#salaAccordion">
                <div class="accordion-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-row">
                                <span class="info-label">Dotación:</span>
                                <span class="info-value">10 personas</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Productividad:</span>
                                <span class="info-value">85.5%</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Rendimiento:</span>
                                <span class="info-value">92.3%</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-row">
                                <span class="info-label">Kilos Entrega:</span>
                                <span class="info-value">1,000 kg</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Kilos Recepción:</span>
                                <span class="info-value">920 kg</span>
                            </div>
                        </div>
                        <!-- Tiempos Muertos -->
                        <div class="col-12 mt-3">
                            <h5>Tiempos Muertos</h5>
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Motivo</th>
                                        <th>Minutos</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Limpieza</td>
                                        <td>20</td>
                                    </tr>
                                    <tr>
                                        <td>Mantenimiento</td>
                                        <td>35</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Total</strong></td>
                                        <td><strong>55</strong></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Aquí irá el JavaScript necesario para la interactividad
</script>
@endsection