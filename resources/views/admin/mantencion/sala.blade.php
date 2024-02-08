<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mantencion sala</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background-color: #fff;
        }

        .container {
            margin-top: 50px;
        }

        .table-custom {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #ddd;
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .table-custom th {
            background-color: #222;
            color: #fff;
            padding: 12px;
            text-align: left;
            font-weight: normal;
        }

        .table-custom td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }



        .table-custom tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .table-custom tbody tr:hover {
            background-color: #e2e6ea;
        }

        /* Animación para las filas */
        @keyframes fadeInRow {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Aplicar animación a las filas */
        .table-custom tbody tr {
            animation: fadeInRow 0.5s ease forwards;
            margin-bottom: 10px;
            /* Agrega separación entre filas */
        }

        /* Estilo para hacer la tabla responsiva */
        @media (max-width: 767px) {
            .table-custom {
                overflow-x: auto;
                display: block;
            }

            .table-custom thead,
            .table-custom tbody,
            .table-custom th,
            .table-custom td,
            .table-custom tr {
                display: block;
                width: 100%;
            }

            .table-custom thead tr {
                position: absolute;
                top: -9999px;
                left: -9999px;
            }

            .table-custom tr {
                margin-bottom: 15px;
            }

            .table-custom td {
                border-bottom: none;
                border-right: none;
                position: relative;
                padding-left: 50%;
            }

            .table-custom td:before {
                position: absolute;
                top: 6px;
                left: 6px;
                width: 45%;
                padding-right: 10px;
                white-space: nowrap;
                content: attr(data-label);
                font-weight: bold;
            }
        }
    </style>

</head>

<body>
    <div class="container">
        <h1>Salas</h1>
        <div class="d-flex justify-content-end">
            <button id="btnNuevo" class="btn btn-success">Nueva sala</button>
        </div>
        <br>
        <div class="table-responsive">
            <table class="table table-striped table-custom">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Activo</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="tablaSalas">
                    @foreach($salas as $sala)
                    <tr>
                        <td>{{ $sala->cod_sala }}</td>
                        <td>{{ $sala->nombre }}</td>
                        <td>
                            @if ($sala->activo == 1)
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="green"
                                class="bi bi-check-circle" viewBox="0 0 16 16">
                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
                                <path
                                    d="m10.97 4.97-.02.022-3.473 4.425-2.093-2.094a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05" />
                            </svg>
                            @else
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="red" class="bi bi-ban"
                                viewBox="0 0 16 16">
                                <path
                                    d="M15 8a6.97 6.97 0 0 0-1.71-4.584l-9.874 9.875A7 7 0 0 0 15 8M2.71 12.584l9.874-9.875a7 7 0 0 0-9.874 9.874ZM16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0" />
                            </svg>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex justify-content-end">

                                <button class="btn btn-light me-2"
                                    onclick="modalEditarSala({{ $sala->cod_sala }}, '{{ $sala->nombre }}', {{ $sala->activo }})">Editar</button>
                                <button class="btn btn-danger"
                                    onclick="eliminarSala({{ $sala->cod_sala }})">Eliminar</button>

                            </div>

                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>

    <div class="modal fade" id="modalSala" tabindex="-1" aria-labelledby="modalSalaLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalSalaLabel">Nuevo Sala</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formSala" action="" method="POST">
                        @csrf
                        <input type="hidden" id="cod_sala" name="cod_sala">
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
        function eliminarSala(idSala) {
            if (confirm('¿Estás seguro de que deseas eliminar esta sala?')) {

                $.ajax({
                    url: '{{ route("eliminarSala") }}',
                    method: 'POST',
                    data: { id: idSala },
                    success: function (response) {
                        alert('Sala eliminada exitosamente.');
                        location.reload();

                    },
                    error: function (xhr, status, error) {
                        alert('Error al eliminar la sala.');
                        console.error(xhr.responseText);
                    }
                });
            }
        }


        $("#btnNuevo").click(function () {
            $("#cod_sala").val("");
            $("#nombre").val("");
            $("#activo").val("");
            $("#modalSalaLabel").text("Nueva Sala");
            $("#modalSala").modal("show");
        });

        function modalEditarSala(idSala, nombre, activo) {
            $('#cod_sala').val(idSala);
            $('#nombre').val(nombre);
            if (activo == 1) {
                $('#activoSi').prop('checked', true);
            } else {
                $('#activoNo').prop('checked', true);
            }
            $('#modalSalaLabel').text('Editar Sala');
            $('#modalSala').modal("show");
        }


        $("#formSala").submit(function (event) {
            event.preventDefault();

            var formData = $(this).serialize();

            var url = $('#modalSalaLabel').text() === 'Nueva Sala' ? '{{ route("guardarSala") }}' : '{{ route("editarSala") }}';


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
                    alert('Error al guardar el sala.');
                    console.error(xhr.responseText);
                }
            });
        });


    </script>

</body>

</html>