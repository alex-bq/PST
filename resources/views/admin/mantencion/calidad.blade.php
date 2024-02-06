<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mantencion calidad</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>


</head>

<body>
    <div class="container mt-5">
        <div class="row mb-3">
            <div class="col-md-6">
                <button id="btnNuevo" class="btn btn-primary">Nueva calidad</button>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Activo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="tablaCalidades">
                    @foreach($calidades as $calidad)
                    <tr>
                        <td>{{ $calidad->cod_cald }}</td>
                        <td>{{ $calidad->nombre }}</td>
                        <td>{{ $calidad->activo }}</td>
                        <td>
                            <button class="btn btn-light"
                                onclick="modalEditarCalidad({{ $calidad->cod_cald }}, '{{ $calidad->nombre }}', {{ $calidad->activo }})">Editar</button>
                            <button class="btn btn-danger"
                                onclick="eliminarCalidad({{ $calidad->cod_cald }})">Eliminar</button>


                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>

    <div class="modal fade" id="modalCalidad" tabindex="-1" aria-labelledby="modalCalidadLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCalidadLabel">Nuevo Calidad</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formCalidad" action="" method="POST">
                        @csrf
                        <input type="hidden" id="cod_cald" name="cod_cald">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre:</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                        <div class="mb-3">
                            <label for="activo" class="form-label">Activo:</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="activo" id="activoSi" value="1"
                                    checked>
                                <label class="form-check-label" for="activoSi">
                                    Si
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="activo" id="activoNo" value="0">
                                <label class="form-check-label" for="activoNo">
                                    No
                                </label>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function eliminarCalidad(idCalidad) {
            if (confirm('¿Estás seguro de que deseas eliminar esta calidad?')) {

                $.ajax({
                    url: '{{ route("eliminarCalidad") }}',
                    method: 'POST',
                    data: { id: idCalidad },
                    success: function (response) {
                        alert('Calidad eliminada exitosamente.');
                        location.reload();

                    },
                    error: function (xhr, status, error) {
                        alert('Error al eliminar la calidad.');
                        console.error(xhr.responseText);
                    }
                });
            }
        }


        $("#btnNuevo").click(function () {
            $("#cod_calidad").val("");
            $("#nombre").val("");
            $("#activo").val("");
            $("#modalCalidadLabel").text("Nueva Calidad");
            $("#modalCalidad").modal("show");
        });

        function modalEditarCalidad(idCalidad, nombre, activo) {
            $('#cod_cald').val(idCalidad);
            $('#nombre').val(nombre);
            if (activo == 1) {
                $('#activoSi').prop('checked', true);
            } else {
                $('#activoNo').prop('checked', true);
            }
            $('#modalCalidadLabel').text('Editar Calidad');
            $('#modalCalidad').modal("show");
        }


        $("#formCalidad").submit(function (event) {
            event.preventDefault();

            var formData = $(this).serialize();

            var url = $('#modalCalidadLabel').text() === 'Nueva Calidad' ? '{{ route("guardarCalidad") }}' : '{{ route("editarCalidad") }}';


            $.ajax({
                url: url,
                method: "POST",
                data: formData,
                success: function (response) {


                    alert(response.message);

                    if (response.error === 0) {
                        location.reload();
                    }

                },
                error: function (xhr, status, error) {
                    alert('Error al guardar el calidad.');
                    console.error(xhr.responseText);
                }
            });
        });


    </script>

</body>

</html>