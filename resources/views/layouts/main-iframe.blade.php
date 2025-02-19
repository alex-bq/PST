<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css" />
    <link rel="icon" href="{{ asset('image/logo.ico') }}" type="image/x-icon">

    <style>
        .iframe-loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
            z-index: 9999;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .iframe-spinner {
            width: 50px;
            height: 50px;
            border: 5px solid #f3f3f3;
            border-top: 5px solid #1a237e;
            border-radius: 50%;
            animation: iframeSpin 1s linear infinite;
        }

        @keyframes iframeSpin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>

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
    <!-- Solo un overlay -->
    <div class="iframe-loading-overlay" id="iframeLoadingOverlay">
        <div class="iframe-spinner"></div>
    </div>

    @yield('content')
    @yield('modal')

    <script>
        $(document).ready(function () {
            // Ocultar overlay cuando todo esté listo
            const overlay = document.getElementById('iframeLoadingOverlay');

            // Ocultar overlay después de un breve delay
            setTimeout(function () {
                if (overlay) {
                    overlay.style.display = 'none';
                }
            }, 500);

            // Configuración de DataTables
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
        });

        // Mostrar overlay en recarga
        window.addEventListener('beforeunload', function () {
            const overlay = document.getElementById('iframeLoadingOverlay');
            if (overlay) {
                overlay.style.display = 'flex';
            }
        });
    </script>

    @yield('scripts2')

</body>

</html>