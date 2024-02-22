<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambiar Contraseña</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" />

    <!-- Estilos personalizados -->
    <style>
        body {
            background-color: #000120;
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 1);
        }

        .card-header {
            background-color: #000121;
            color: #fff;
            border-radius: 10px 10px 0 0;
        }

        .card-body {
            padding: 30px;
        }

        .btn-dark {
            background-color: #000120;
            border-color: #000120;
        }

        .btn-dark:hover {
            background-color: #23272b;
            border-color: #23272b;
        }
    </style>
</head>

<body>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-center">
                        <h3>Cambiar Contraseña</h3>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ url('/cambiar-contra') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="current_password" class="form-label">Contraseña Actual</label>
                                <input type="password" id="current_password" name="current_password"
                                    class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="new_password" class="form-label">Nueva Contraseña</label>
                                <input type="password" id="new_password" name="new_password" class="form-control"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Confirmar Nueva Contraseña</label>
                                <input type="password" id="confirm_password" name="confirm_password"
                                    class="form-control" required>
                            </div>

                            <button type="submit" class="btn btn-dark w-100">Cambiar Contraseña</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS y otros scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>