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
    <div class="container ">
        <h1>Calibres</h1>
        <div class="d-flex justify-content-end">
            <button id="btnNuevo" class="btn btn-success">Nuevo Calibre</button>
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
                <tbody id="tablaCalibres">
                    @foreach($calibres as $calibre)
                    <tr>
                        <td>{{ $calibre->cod_calib }}</td>
                        <td>{{ $calibre->nombre }}</td>
                        <td>{{ $calibre->activo }}</td>
                        <td>
                            <div class="d-flex justify-content-end">

                                <button class="btn btn-light me-2   "
                                    onclick="modalEditarCalibre({{ $calibre->cod_calib }}, '{{ $calibre->nombre }}', {{ $calibre->activo }})">Editar</button>
                                <button class="btn btn-danger"
                                    onclick="eliminarCalibre({{ $calibre->cod_calib }})">Eliminar</button>

                            </div>
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