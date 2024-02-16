<!DOCTYPE html>
<html lang="en">

<head>
    <title>
        Sistema Planillas
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
            <div class="p-4 pt-5">
                <a class="navbar-brand" href="{{ url('/inicio') }}">
                    <img src="{{ asset('image/logo.png') }}" alt="Logo" height="85">
                </a>
                <ul class="list-unstyled components mb-5">
                    <li>
                        <a href="#" data-url="{{ route('inicio') }}">Inicio</a>
                    </li>
                    @if(session('user')['cod_rol'] == 3)
                    <li class="active">
                        <a href="#adminMenu" data-toggle="collapse" aria-expanded="false"
                            class="dropdown-toggle">Admin</a>
                        <ul class="collapse list-unstyled" id="adminMenu">

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
                            <li>
                                <a href="#" data-url="{{ route('mUsuario') }}">Usuarios</a>
                            </li>
                        </ul>
                    </li>
                    @endif
                </ul>




                <div class="mb-5">

                </div>

                <div class="footer mt-auto">
                    <p>
                        <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                        {{ session('user')['nombre'] }}<br>
                        {{ session('user')['rol'] }}<br>
                        <a type="button" class="btn btn-light btn-sm" href="{{ url('/logout') }}">Cerrar sesión</a>

                        <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                    </p>
                </div>
            </div>
        </nav>

        <!-- Page Content  -->
        <div id="content">

            <iframe id="iframeContent" name="iframeContent" src="{{ route('inicio') }}" width="100%" height="100%"
                width="100%" frameborder="0"></iframe>
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
                event.preventDefault(); // Prevenimos el comportamiento predeterminado del enlace
                var url = $(this).data('url'); // Obtenemos la URL del atributo data-url del enlace
                if (url !== undefined && url !== '') {
                    // Actualizamos la URL del iframe
                    $('#iframeContent').attr('src', url);
                }
            });
        });


    </script>


</body>

</html>