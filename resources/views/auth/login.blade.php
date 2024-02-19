<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>


<body>

    <div class="container">

        <div class="row justify-content-center align-items-center vh-100">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div style="display: flex; justify-content: center; align-items: center; height: 200px;">
                            <img src="{{ asset('image/logo.png') }}" alt="Logo" height="100">
                        </div>


                        @if(session('error'))
                        <p style="color: red;" class="text-center">{{ session('error') }}</p>
                        @endif

                        <form method="post" action="{{ url('/login') }}" class="mb-3">
                            @csrf
                            <div class="mb-3">
                                <label for="nombre_usuario" class="form-label">Nombre de Usuario:</label>
                                <input type="text" name="usuario" class="form-control" autocomplete="nope" required>
                            </div>

                            <div class="mb-3">
                                <label for="pass" class="form-label">Contraseña:</label>
                                <input type="password" name="pass" class="form-control" autocomplete="nope" required>
                            </div>

                            <button type="submit" class="btn btn-dark w-100">Iniciar Sesión</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS y otros scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Tus scripts adicionales -->

    <script>
        sessionStorage.clear();
        localStorage.clear();
    </script>



</body>



</html>