@extends('layouts.main-iframe')

@section('title', 'Informes de Producción')

@section('styles')
<style>
    .card-icon {
        color: #1C1D22;
    }

    .card {
        transition: all 0.3s ease;
    }

    .card:hover {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .custom-input {
        height: 56px;
    }

    .btn-search {
        background-color: #000120;
        color: white;
    }

    .btn-search:hover {
        background-color: #14142a;
        color: white;
    }
</style>
@endsection

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <!-- Título -->
            <h1 class="text-center fw-bold mb-4" style="font-size: 32px;">Buscar Informes Diarios</h1>

            <!-- Formulario de búsqueda -->
            <div class="row justify-content-center mb-4">
                <div class="col-12 col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-medium">Seleccionar Día</label>
                        <input type="date" class="form-control custom-input" placeholder="Ejemplo: 2023-10-01">
                    </div>
                    <div class="d-flex gap-2 justify-content-center">
                        <button class="btn btn-search flex-grow-1">Buscar</button>
                        <button class="btn btn-light flex-grow-1">Limpiar</button>
                    </div>
                </div>
            </div>

            <!-- Tarjetas de Informes -->
            <div class="row row-cols-1 row-cols-md-3 g-4">
                <!-- Informe Tarde -->
                <div class="col">
                    <div class="card h-100 border">
                        <div class="card-body">
                            <div class="card-icon mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                    viewBox="0 0 256 256">
                                    <path
                                        d="M232,208a8,8,0,0,1-8,8H32a8,8,0,0,1-8-8V48a8,8,0,0,1,16,0v94.37L90.73,98a8,8,0,0,1,10.07-.38l58.81,44.11L218.73,90a8,8,0,1,1,10.54,12l-64,56a8,8,0,0,1-10.07.38L96.39,114.29,40,163.63V200H224A8,8,0,0,1,232,208Z">
                                    </path>
                                </svg>
                            </div>
                            <h5 class="card-title fw-bold">Informe Tarde</h5>
                            <p class="card-text text-muted small">
                                Jefe de Turno: Prueba 1<br>
                                Productividad: 85%<br>
                                Rendimiento: 90%<br>
                                Kilos Totales: 5000
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Informe Día -->
                <div class="col">
                    <div class="card h-100 border">
                        <div class="card-body">
                            <div class="card-icon mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                    viewBox="0 0 256 256">
                                    <path
                                        d="M232,208a8,8,0,0,1-8,8H32a8,8,0,0,1-8-8V48a8,8,0,0,1,16,0v94.37L90.73,98a8,8,0,0,1,10.07-.38l58.81,44.11L218.73,90a8,8,0,1,1,10.54,12l-64,56a8,8,0,0,1-10.07.38L96.39,114.29,40,163.63V200H224A8,8,0,0,1,232,208Z">
                                    </path>
                                </svg>
                            </div>
                            <h5 class="card-title fw-bold">Informe Día</h5>
                            <p class="card-text text-muted small">
                                Jefe de Turno: Prueba 2<br>
                                Productividad: 87%<br>
                                Rendimiento: 88%<br>
                                Kilos Totales: 5200
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Informe Noche -->
                <div class="col">
                    <div class="card h-100 border">
                        <div class="card-body">
                            <div class="card-icon mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                    viewBox="0 0 256 256">
                                    <path
                                        d="M232,208a8,8,0,0,1-8,8H32a8,8,0,0,1-8-8V48a8,8,0,0,1,16,0v94.37L90.73,98a8,8,0,0,1,10.07-.38l58.81,44.11L218.73,90a8,8,0,1,1,10.54,12l-64,56a8,8,0,0,1-10.07.38L96.39,114.29,40,163.63V200H224A8,8,0,0,1,232,208Z">
                                    </path>
                                </svg>
                            </div>
                            <h5 class="card-title fw-bold">Informe Noche</h5>
                            <p class="card-text text-muted small">
                                Jefe de Turno: Prueba 3<br>
                                Productividad: 82%<br>
                                Rendimiento: 85%<br>
                                Kilos Totales: 4800
                            </p>
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



</script>
@endsection