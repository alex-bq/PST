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
    <form action="procesar_formulario.php" method="post">
        <!-- Cod Lote -->
        <div class="form-group">
            <label for="codLote">Código de Lote</label>
            <input type="text" class="form-control" id="codLote" name="codLote" required>
        </div>
        <!-- Fecha de Turno -->
        <div class="form-group">
            <label for="fechaTurno">Fecha de Turno</label>
            <input type="date" class="form-control" id="fechaTurno" name="fechaTurno" required>
        </div>
        <!-- Código de Turno -->
        <div class="form-group">
            <label for="codTurno">Código de Turno</label>
            <input type="text" class="form-control" id="codTurno" name="codTurno" required>
        </div>
        <!-- Código de Empresa -->
        <div class="form-group">
            <label for="codEmpresa">Código de Empresa</label>
            <input type="text" class="form-control" id="codEmpresa" name="codEmpresa" required>
        </div>
        <!-- Código de Especie -->
        <div class="form-group">
            <label for="codEspecie">Código de Especie</label>
            <input type="text" class="form-control" id="codEspecie" name="codEspecie" required>
        </div>
        <!-- Código de Planillero -->
        <div class="form-group">
            <label for="codPlanillero">Código de Planillero</label>
            <input type="text" class="form-control" id="codPlanillero" name="codPlanillero" required>
        </div>
        <!-- Código de Supervisor -->
        <div class="form-group">
            <label for="codSupervisor">Código de Supervisor</label>
            <input type="text" class="form-control" id="codSupervisor" name="codSupervisor" required>
        </div>
        <!-- Guardado -->
        <div class="form-group">
            <label for="guardado">Guardado</label>
            <input type="text" class="form-control" id="guardado" name="guardado" required>
        </div>

        <!-- Botón de Enviar -->
        <button type="submit" class="btn btn-primary">Crear Planilla</button>
    </form>






    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Tus scripts adicionales -->
    
</body>

</html>