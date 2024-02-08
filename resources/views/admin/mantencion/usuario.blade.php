<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mantenimiento de Usuarios</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
        <h1>Usuarios</h1>
        <div class="d-flex justify-content-end">
            <button id="btnNuevo" class="btn btn-success">Nuevo Usuario</button>
        </div>
        <br>
        <div class="table-responsive">
            <table class="table table-striped table-custom">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Usuario</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Rol</th>
                        <th>Activo</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="tablaUsuarios">
                    @foreach($usuarios as $usuario)
                    <tr>
                        <td>{{ $usuario->cod_usuario }}</td>
                        <td>{{ $usuario->usuario }}</td>
                        <td>{{ $usuario->snombre }}</td>
                        <td>{{ $usuario->sapellido }}</td>
                        <td>{{ $usuario->rol }}</td>
                        <td>
                            @if ($usuario->activo == 1)
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
                                <button class="btn btn-light me-2" (idUsuario, snombre, sapellido, usuario, contra,
                                    idRol, activo)
                                    onclick="modalEditarUsuario({{ $usuario->cod_usuario }},'{{ $usuario->snombre }}','{{ $usuario->sapellido}}','{{ $usuario->usuario}}','{{ $usuario->pass}}',{{ $usuario->cod_rol }},{{ $usuario->activo }})">Editar</button>
                                <button class="btn btn-danger"
                                    onclick="eliminarUsuario({{ $usuario->cod_usuario }})">Eliminar</button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
    <!-- Modal para Nuevo/Edit Usuario -->
    <div class="modal fade" id="modalUsuario" tabindex="-1" aria-labelledby="modalUsuarioLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalUsuarioLabel">Nuevo Usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formUsuario" action="" method="POST">
                        @csrf
                        <input type="hidden" id="cod_usuario" name="cod_usuario">

                        <div class="mb-3 row">
                            <div class="col-md-6">
                                <label for="nombre" class="form-label">Nombre:</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" autocomplete="nope"
                                    required>
                            </div>
                            <div class="col-md-6">
                                <label for="apellido" class="form-label">Apellido:</label>
                                <input type="text" class="form-control" id="apellido" name="apellido"
                                    autocomplete="nope" required>
                            </div>
                        </div>

                        <div class="mb-3 row">

                            <div class="col-md-6">
                                <label for="usuario" class="form-label">Usuario:</label>
                                <input type="text" class="form-control" id="usuario" name="usuario" autocomplete="nope"
                                    required>
                            </div>
                            <div class="col-md-6">
                                <label for="contra" class="form-label">Contraseña:</label>
                                <div class="input-group">
                                    <input type="password" id="contra" name="contra" class="form-control"
                                        autocomplete="nope" required>
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                            <path
                                                d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z" />
                                            <path
                                                d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="rol" class="form-label">Rol:</label>
                            <select id="rol" class="form-select" name="rol">
                                <option value="" selected disabled hidden>Selecciona un rol</option>
                                @foreach ($roles as $rol)
                                <option value="{{ $rol->cod_rol }}">{{ $rol->nombre_rol }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="activo" class="form-label">Activo:</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="activo" id="activoSi" value="1"
                                    checked>
                                <label class="form-check-label" for="activoSi">Si</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="activo" id="activoNo" value="0">
                                <label class="form-check-label" for="activoNo">No</label>
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
    <!-- JavaScript para manejar la interactividad -->
    <script>
        function eliminarUsuario(idUsuario) {
            if (confirm('¿Estás seguro de que deseas eliminar este usuario?')) {
                $.ajax({
                    url: '{{ route("eliminarUsuario") }}',
                    method: 'POST',
                    data: {
                        id: idUsuario
                    },
                    success: function (response) {
                        alert('Usuario eliminado exitosamente.');
                        location.reload();
                    },
                    error: function (xhr, status, error) {
                        alert('Error al eliminar el usuario.');
                        console.error(xhr.responseText);
                    }
                });
            }
        }
        $("#btnNuevo").click(function () {
            $("#usuario").val("");
            $("#nombre").val("");
            $("#apellido").val("");
            $("#contra").val("");
            $("#activo").val("");
            $("#modalUsuarioLabel").text("Nuevo Usuario");
            $("#modalUsuario").modal("show");
        });
        function modalEditarUsuario(idUsuario, snombre, sapellido, usuario, contra, idRol, activo) {
            $('#cod_usuario').val(idUsuario);
            $('#nombre').val(snombre);
            $('#apellido').val(sapellido);
            $('#usuario').val(usuario);
            $('#contra').val(contra);
            $('#rol').val(idRol);

            if (activo == 1) {
                $('#activoSi').prop('checked', true);
            } else {
                $('#activoNo').prop('checked', true);
            }
            $('#modalUsuarioLabel').text('Editar Usuario');
            $('#modalUsuario').modal("show");
        }
        $("#formUsuario").submit(function (event) {
            event.preventDefault();
            var formData = $(this).serialize();
            var url = $('#modalUsuarioLabel').text() === 'Nuevo Usuario' ? '{{ route("guardarUsuario") }}' : '{{ route("editarUsuario") }}';

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
                    alert('E alr el usuario.');
                    console.error(xhr.responseText);
                }
            });
        });

        $(document).ready(function () {
            $('#togglePassword').click(function () {
                const tipo = $('#contra').attr('type');
                if (tipo === 'password') {
                    $('#contra').attr('type', 'text');
                    $('#togglePassword i').removeClass('bi-eye').addClass('bi-eye-slash');
                } else {
                    $('#contra').attr('type', 'password');
                    $('#togglePassword i').removeClass('bi-eye-slash').addClass('bi-eye');
                }
            });
        });
    </script>
</body>

</html>