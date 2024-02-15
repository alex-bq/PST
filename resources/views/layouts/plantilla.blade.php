<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        @yield('title','Inicio')
    </title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css" />



    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    @yield('styles')





    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>


    @yield('scripts')

    <script>
        var baseUrl = "{{ url('/') }}";
    </script>

    <meta name="csrf-token" content="{{ csrf_token() }}">



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
                    @if(session('user')['cod_rol'] == 3)
                    <a class="nav-item nav-link" id="sidebar-toggle" href="#">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            style="fill: rgba(0, 0, 0, 0.5);transform: ;msFilter:;">
                            <path d="M4 6h16v2H4zm0 5h16v2H4zm0 5h16v2H4z"></path>
                        </svg>
                    </a>
                    @endif
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
    @if(session('user')['cod_rol'] == 3)
    <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-light ">
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
                                <a class="nav-link" href="{{ route('mUsuario') }}">Usuario</a>
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
    @endif

    <!-- Contenido -->
    <main class="main-content ">
        @yield('content')
    </main>

    @yield('modal')



    <script>

        var sidebarState = sessionStorage.getItem('sidebarState');
        if (sidebarState === 'open') {
            // Si el sidebar está abierto, agrega las clases correspondientes
            $('#sidebar').removeClass('active');
            $('.main-content').removeClass('sidebar-active');
        } else {
            sessionStorage.setItem('sidebarState', 'closed');
            $('#sidebar').addClass('active');
            $('.main-content').addClass('sidebar-active');
        }

        $(document).ready(function () {
            $(".tablaOrdenable").DataTable({
                select: false,
                autoWidth: false,
                info: false,
                processing: false,
                language: {
                    "decimal": "",
                    "emptyTable": "No hay información",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
                    "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
                    "infoFiltered": "(Filtrado de _MAX_ total entradas)",
                    "infoPostFix": "",
                    "thousands": ",",
                    "lengthMenu": "Mostrar _MENU_ Entradas",
                    "loadingRecords": "Cargando...",
                    "processing": "Procesando...",
                    "search": "Buscar:",
                    "zeroRecords": "Sin resultados encontrados",
                    "paginate": {
                        "first": "Primero",
                        "last": "Ultimo",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    }
                },
            });
            $('#mantencion-btn').on('click', function () {
                $('#item-mantencion').toggleClass('selected'); // Toggle clase del item
            });

            $('#home-collapse-btn').on('click', function () {
                $('#item-home').toggleClass('selected'); // Toggle clase del item
            });

            $('#sidebar-toggle').click(function () {
                event.preventDefault();
                var $sidebar = $('#sidebar');
                var $content = $('.main-content');

                if ($sidebar.hasClass('active')) {
                    $sidebar.animate({ width: '230px' }, 300, function () {
                        $sidebar.removeClass('active');
                    }); // Animación para cerrar el sidebar
                    $content.animate({ marginLeft: '230px' }, 300); // Animación para abrir el contenido
                    sessionStorage.setItem('sidebarState', 'open');
                } else {
                    $sidebar.addClass('active');
                    $sidebar.animate({ width: '0px' }, 300); // Animación para abrir el sidebar
                    $content.animate({ marginLeft: '0px' }, 300);
                    $content.addClass('sidebar-active') // Animación para cerrar el contenido
                    sessionStorage.setItem('sidebarState', 'closed')
                }
            });
        });
    </script>


    @yield('scripts2')

</body>

</html>