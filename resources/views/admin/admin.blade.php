<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página con Navbar y Sidebar</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar sticky-top navbar-expand" style="background-color: #fdfdfd;">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ url('/inicio') }}">
                <img src="{{ asset('image/logo.png') }}" alt="Logo" height="50">
            </a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <a class="nav-item nav-link" href="{{ url('/admin') }}">Datos</a>
                </ul>
            </div>

            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <div class="navbar-nav d-flex align-items-center">
                    <label class="nav-item nav-link">{{ session('user')['nombre'] }}</label>
                    <label class="nav-item nav-link">Rol: {{ session('user')['rol'] }}</label>
                    <a type="button" class="btn btn-light btn-sm" href="{{ url('/logout') }}">Cerrar sesión</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-light">
        <div class="position-sticky">
            <div class="pt-3">
                <ul class="nav flex-column mb-2">
                    <li id="item-mantencion" class="nav-item">
                        <a id="mantencion-btn" class="btn btn-toggle align-items-center rounded collapsed"
                            data-bs-toggle="collapse" data-bs-target="#mantencion" aria-expanded="false">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-chevron-right" viewBox="0 0 16 16">
                                <path fill-rule="evenodd"
                                    d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708" />
                            </svg> Mantencion Datos
                        </a>
                        <div class="collapse" id="mantencion">
                            <div class="nav flex-column" id="bar">
                                <a class="nav-link" href="{{ route('mCorte') }}">Corte</a>
                                <a class="nav-link" href="{{ route('mCalidad') }}">Calidad</a>
                                <a class="nav-link" href="{{ route('mDestino') }}">Destino</a>
                                <a class="nav-link" href="{{ route('mCalibre') }}">Calibre</a>
                                <a class="nav-link" href="{{ route('mSala') }}">Sala</a>
                            </div>
                        </div>
                    </li>
                    <li id="item-home" class="nav-item">
                        <a id="home-collapse-btn" class="btn btn-toggle align-items-center rounded collapsed"
                            data-bs-toggle="collapse" data-bs-target="#home-collapse" aria-expanded="false">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-chevron-right" viewBox="0 0 16 16">
                                <path fill-rule="evenodd"
                                    d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708" />
                            </svg> Grupo
                        </a>
                        <div class="collapse" id="home-collapse">
                            <div class="nav flex-column">
                                <a class="nav-link" href="#">item 1</a>
                                <a class="nav-link" href="#">item 2</a>
                                <a class="nav-link" href="#">item 3</a>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Contenido -->
    <main class="main-content ">
        <iframe src="" frameborder="0"></iframe>
    </main>

    <!-- Bootstrap JS y jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function () {
            function cambiarURL(url) {
                $('iframe').attr('src', url);
            }

            $('#bar a').on('click', function (e) {
                e.preventDefault();
                var linkURL = $(this).attr('href');
                cambiarURL(linkURL);
            });

            $('#mantencion-btn').on('click', function () {
                $('#item-mantencion').toggleClass('selected'); // Toggle clase del item
            });

            $('#home-collapse-btn').on('click', function () {
                $('#item-home').toggleClass('selected'); // Toggle clase del item
            });
        });
    </script>
</body>

</html>