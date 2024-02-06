<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mantencion destino</title>
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
                <button id="btnNuevo" class="btn btn-primary">Nuevo Destino</button>
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
                <tbody id="tablaDestinos">
                    @foreach($destinos as $destino)
                    <tr>
                        <td>{{ $destino->cod_destino }}</td>
                        <td>{{ $destino->nombre }}</td>
                        <td>{{ $destino->activo }}</td>
                        <td>
                            <button class="btn btn-light"
                                onclick="modalEditarDestino({{ $destino->cod_destino }}, '{{ $destino->nombre }}', {{ $destino->activo }})">Editar</button>
                            <button class="btn btn-danger"
                                onclick="eliminarDestino({{ $destino->cod_destino }})">Eliminar</button>


                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>

    <div class="modal fade" id="modalDestino" tabindex="-1" aria-labelledby="modalDestinoLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDestinoLabel">Nuevo Destino</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formDestino" action="" method="POST">
                        @csrf
                        <input type="hidden" id="cod_destino" name="cod_destino">
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
        function eliminarDestino(idDestino) {
            if (confirm('¿Estás seguro de que deseas eliminar este destino?')) {

                $.ajax({
                    url: '{{ route("eliminarDestino") }}',
                    method: 'POST',
                    data: { id: idDestino },
                    success: function (response) {
                        alert('Destino eliminado exitosamente.');
                        location.reload();

                    },
                    error: function (xhr, status, error) {
                        alert('Error al eliminar el destino.');
                        console.error(xhr.responseText);
                    }
                });
            }
        }


        $("#btnNuevo").click(function () {
            $("#cod_destino").val("");
            $("#nombre").val("");
            $("#activo").val("");
            $("#modalDestinoLabel").text("Nuevo Destino");
            $("#modalDestino").modal("show");
        });

        function modalEditarDestino(idDestino, nombre, activo) {
            $('#cod_destino').val(idDestino);
            $('#nombre').val(nombre);
            if (activo == 1) {
                $('#activoSi').prop('checked', true);
            } else {
                $('#activoNo').prop('checked', true);
            }
            $('#modalDestinoLabel').text('Editar Destino');
            $('#modalDestino').modal("show");
        }


        $("#formDestino").submit(function (event) {
            event.preventDefault();

            var formData = $(this).serialize();

            var url = $('#modalDestinoLabel').text() === 'Nuevo Destino' ? '{{ route("guardarDestino") }}' : '{{ route("editarDestino") }}';


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
                    alert('Error al guardar el destino.');
                    console.error(xhr.responseText);
                }
            });
        });


    </script>

</body>

</html>