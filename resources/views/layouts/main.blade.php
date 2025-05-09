<!DOCTYPE html>
<html lang="en">

<head>
    <title>
        Sistema PST
    </title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet" />

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css" />
    <link rel="icon" href="{{ asset('image/logo.ico') }}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">



    <link rel="stylesheet" href="{{ asset('css/plantilla.css') }}" />

    <script>
        var baseUrl = "{{ url('/') }}";
    </script>

    <meta name="csrf-token" content="{{ csrf_token() }}">


</head>

<body>
    <div class="wrapper d-flex align-items-stretch">
        <nav id="sidebar">
            <div class="custom-menu">
                <button type="button" id="sidebarCollapse" class="btn btn-primary">
                    <i class="fa fa-bars"></i>
                    <span class="sr-only">Toggle Menu</span>
                </button>
            </div>
            <div class="p-4 pt-5 scrollsidebar d-flex flex-column" style="height: 100vh;">
                <a class="navbar-brand" href="{{ url('/main') }}">
                    <img src="{{ asset('image/logo.png') }}" alt="Logo" height="85">
                </a>
                <ul class="list-unstyled components scrollsidebar" style="overflow-y: auto; margin-bottom: -10vh">
                    <li>
                        <a href="#" data-url="{{ route('inicio') }}">Inicio</a>
                    </li>
                    <li>
                        <a href="#" data-url="{{ route('planillas') }}">Planillas</a>
                    </li>

                    @if(session('user')['cod_rol'] == 3 || session('user')['cod_rol'] == 4)
                        <li>
                            <a href="#" data-url="{{ route('mUsuario') }}">Usuarios</a>
                        </li>
                        <li class="active">
                            <a href="#adminSubMenu" data-toggle="collapse" aria-expanded="false"
                                class="dropdown-toggle">Mantencion Datos</a>
                            <ul class="collapse list-unstyled" id="adminSubMenu">
                                <li>
                                    <a href="#" data-url="{{ route('mCorte') }}">Corte</a>
                                </li>
                                <li>
                                    <a href="#" data-url="{{ route('mCalibre') }}">Calibre</a>
                                </li>
                                <li>
                                    <a href="#" data-url="{{ route('mCalidad') }}">Calidad</a>
                                </li>
                                <li>
                                    <a href="#" data-url="{{ route('mDestino') }}">Destino</a>
                                </li>
                                <li>
                                    <a href="#" data-url="{{ route('mSala') }}">Sala</a>
                                </li>
                            </ul>
                        </li>
                    @endif



                    @if(session('user')['cod_rol'] == 4)
                        <li class="nav-item">
                            <a href="#" data-url="{{ route('mis-informes') }}">Mis Informes</a>
                        </li>

                    @endif
                    @if(session('user')['cod_rol'] == 3 || session('user')['cod_rol'] == 4)
                        <li class="nav-item">
                            <a href="#" data-url="{{ route('informes') }}">Buscar Informes</a>
                        </li>
                        <li class="nav-item">
                            <a href="#" data-url="{{ route('dashboard-productividad') }}">Dashboard</a>
                        </li>

                    @endif
                </ul>




                <div class="mb-5">

                </div>
                <div class="mt-auto">
                    <div class="footer">
                        <p>
                            {{ session('user')['nombre'] }}<br>
                            {{ session('user')['rol'] }}<br>
                            <a href="{{ url('/cambiar-contra') }}">Cambiar contraseña</a> <br>
                            <a type="button" class="btn btn-light btn-sm" href="{{ url('/logout') }}">Cerrar sesión</a>
                        </p>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content  -->
        <div id="content">

            <iframe id="iframeContent" name="iframeContent" src="{{ route('inicio') }}" width="100%" height="100%"
                width="100%" frameborder="0" style="transition: opacity 0.1s ease-in-out;"></iframe>
        </div>

    </div>

    <script src="{{ asset('js/js-plantilla/jquery.min.js') }}"></script>
    <script src="{{ asset('js/js-plantilla/popper.js') }}"></script>
    <script src="{{ asset('js/js-plantilla/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/js-plantilla/main.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>

    <script>
        $(document).ready(function () {
            // Capturamos el evento de clic en los enlaces del menú
            $('ul.components a').on('click', function (event) {
                event.preventDefault();
                var url = $(this).data('url');
                if (url !== undefined && url !== '') {
                    sessionStorage.setItem('lastVisitedPage', url);

                    // Obtener el iframe
                    var iframe = document.getElementById('iframeContent');

                    // Mostrar loading antes de cambiar la URL
                    iframe.style.opacity = '0';

                    // Cambiar la URL del iframe
                    iframe.src = url;

                    // Cuando el iframe termine de cargar
                    iframe.onload = function () {
                        // Mostrar el contenido con una transición suave
                        iframe.style.opacity = '1';
                    };
                }
            });

            // Verificamos si hay una URL guardada en la sesión
            var lastVisitedPage = sessionStorage.getItem('lastVisitedPage');
            if (lastVisitedPage !== null && lastVisitedPage !== '') {
                $('#iframeContent').attr('src', lastVisitedPage);
            }
        });
    </script>


</body>

</html>