<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Página de Inicio</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">

</head>

@if(!session('user'))
    <script>
        window.location.href = "{{ url('/login') }}";
    </script>
@endif

<body>
<div class="container-fluid">
        <h1 class="mb-4">Página de Inicio</h1>  

        @if(session('user'))
            <h2>{{ session('user')['nombre_usuario'] }}</h2>


            @if(!session('user')['rol_admin'])
                <p>Rol: {{ session('user')['rol_supervisor'] ? 'Supervisor' : 'Planillero' }}</p>
            @endif
            
            <p><a href="{{ url('/logout') }}">Cerrar sesión</a></p>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="mb-0">Planillas</h2>
                <a href="{{ url('/crear_planilla') }}" class="btn btn-primary">Crear Nueva Planilla</a>
            </div>

            @if(count($planillas) > 0)
                @foreach($planillas as $planilla)
                    <a href="{{ url('/planilla/' . $planilla->cod_planilla) }}">
                        <div class="card">
                            <div class="card-body">
                                <p><strong>Lote:</strong>{{ $planilla->lote }}</p>
                                <p><strong>Fecha Turno:</strong> {{ date('d/m/Y', strtotime($planilla->fec_turno)) }}</p>
                                <p><strong>Turno:</strong>{{ $planilla->turno }}</p>
                                <p><strong>Proveedor:</strong>{{ $planilla->empresa }}</p>
                                <p><strong>Especie:</strong>{{ $planilla->especie }}</p>
                                <p><strong>Supervisor:</strong>{{ $planilla->supervisor_nombre }}</p>
                                <p><strong>Planillera:</strong>{{ $planilla->planillera_nombre }}</p>
                            </div>
                        </div>
                    </a>
                    <br>
                @endforeach
            @else
                <p>No hay datos de planilla disponibles.</p>
            @endif
        @endif
    </div>

    <!-- Bootstrap JS y otros scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Tus scripts adicionales -->
</body>

</html>
