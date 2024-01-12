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

<body>
    <div class="container-fluid">
        <h1 class="mb-4">Página de Inicio</h1>
        @if(session('user'))
        <h2>{{ session('user')['nombre_usuario'] }}</h2>
        <p>Rol: {{ session('user')['rol_supervisor'] ? 'Supervisor' : 'Planillero' }}</p>
        <p><a href="{{ url('/logout') }}">Cerrar sesión</a></p>

            
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="mb-0">Planillas</h2>
                <a href="crear_planilla.php" class="btn btn-primary">Crear Nueva Planilla</a>
            </div>

                <a href="">
                    <div class="card">
                        <div class="card-body">
                            <p><strong>Fecha Turno:</strong> 03/01/2024</p>
                            <p><strong>Turno:</strong>Noche</p>
                            <p><strong>Proveedor:</strong>Patagonia King Salmon</p>
                            <p><strong>Especie:</strong>Salmon Chinook</p>
                            <p><strong>Supervisor:</strong>Natalie Altamirano</p>
                        </div>
                    </div>
                </a>
        @else
            <p>Debes iniciar sesión para acceder al dashboard.</p>
        @endif
        
    </div>

    <!-- Bootstrap JS y otros scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Tus scripts adicionales -->
</body>

</html>
