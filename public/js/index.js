jQuery(document).ready(function ($) {
    $("#tablaPlanillas").DataTable({
        paging: true,
        searching: true,
        select: false,
        autoWidth: false,
        info: false,
        processing: false,
        lengthChange: false,
        lengthMenu: [[10], [1]],
        language: {
            decimal: "",
            emptyTable: "No hay información",
            info: "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
            infoEmpty: "Mostrando 0 to 0 of 0 Entradas",
            infoFiltered: "(Filtrado de _MAX_ total entradas)",
            infoPostFix: "",
            thousands: ",",
            lengthMenu: "Mostrar _MENU_ Entradas",
            loadingRecords: "Cargando...",
            processing: "Procesando...",
            search: "Buscar:",
            zeroRecords: "Sin resultados encontrados",
            paginate: {
                first: "Primero",
                last: "Ultimo",
                next: "Siguiente",
                previous: "Anterior",
            },
        },
    });

    $(".js-example-basic-single").select2({
        placeholder: "Select an option",
        width: "resolve",
        theme: "bootstrap4",
    });

    $(".modalSelect").select2({
        placeholder: "______________",
        width: "resolve",
        theme: "bootstrap4",
        dropdownParent: $("#exampleModal"),
    });
    toastr.options = {
        positionClass: "toast-position",
        containerId: "toast-container",
    };
});
