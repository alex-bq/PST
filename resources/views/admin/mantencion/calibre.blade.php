<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mantencion calibre</title>
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
                <button id="btnNuevo" class="btn btn-primary">Nuevo Calibre</button>
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
                <tbody id="tablaCalibres">
                    @foreach($calibres as $calibre)
                    <tr>
                        <td>{{ $calibre->cod_calib }}</td>
                        <td>{{ $calibre->nombre }}</td>
                        <td>{{ $calibre->activo }}</td>
                        <td>
                            <button class="btn btn-light"
                                onclick="modalEditarCalibre({{ $calibre->cod_calib }}, '{{ $calibre->nombre }}', {{ $calibre->activo }})">Editar</button>
                            <button class="btn btn-danger"
                                onclick="eliminarCalibre({{ $calibre->cod_calib }})">Eliminar</button>


                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>

    <div class="modal fade" id="modalCalibre" tabindex="-1" aria-labelledby="modalCalibreLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCalibreLabel">Nuevo Calibre</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formCalibre" action="" method="POST">
                        @csrf
                        <input type="hidden" id="cod_calib" name="cod_calib">
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
        function eliminarCalibre(idCalibre) {
            if (confirm('¿Estás seguro de que deseas eliminar este calibre?')) {

                $.ajax({
                    url: '{{ route("eliminarCalibre") }}',
                    method: 'POST',
                    data: { id: idCalibre },
                    success: function (response) {
                        alert('Calibre eliminado exitosamente.');
                        location.reload();

                    },
                    error: function (xhr, status, error) {
                        alert('Error al eliminar el calibre.');
                        console.error(xhr.responseText);
                    }
                });
            }
        }


        $("#btnNuevo").click(function () {
            $("#cod_calib").val("");
            $("#nombre").val("");
            $("#activo").val("");
            $("#modalCalibreLabel").text("Nuevo Calibre");
            $("#modalCalibre").modal("show");
        });

        function modalEditarCalibre(idCalibre, nombre, activo) {
            $('#cod_calib').val(idCalibre);
            $('#nombre').val(nombre);
            if (activo == 1) {
                $('#activoSi').prop('checked', true);
            } else {
                $('#activoNo').prop('checked', true);
            }
            $('#modalCalibreLabel').text('Editar Calibre');
            $('#modalCalibre').modal("show");
        }


        $("#formCalibre").submit(function (event) {
            event.preventDefault();

            var formData = $(this).serialize();

            var url = $('#modalCalibreLabel').text() === 'Nuevo Calibre' ? '{{ route("guardarCalibre") }}' : '{{ route("editarCalibre") }}';


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
                    alert('Error al guardar el calibre.');
                    console.error(xhr.responseText);
                }
            });
        });


    </script>

</body>

</html>