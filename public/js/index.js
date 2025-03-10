jQuery(document).ready(function ($) {
  $("#tablaPlanillas7dias").DataTable({
    order: [[0, "desc"]],
    paging: true,
    searching: true,
    select: false,
    autoWidth: false,
    info: true,
    processing: false,
    lengthChange: false,
    lengthMenu: [[5], [5]],
    language: {
      decimal: "",
      emptyTable: "No hay información",
      info: "Mostrando _START_ a _END_ de _TOTAL_ Planillas",
      infoEmpty: "Mostrando 0 to 0 of 0 Entradas",
      infoFiltered: "(Filtrado de _MAX_ total planillas)",
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
    columnDefs: [{ type: "date", targets: [2] }],
  });
  $("#tablaPlanillasHoy").DataTable({
    order: [[0, "desc"]],
    paging: true,
    searching: true,
    select: false,
    autoWidth: false,
    info: true,
    processing: false,
    lengthChange: false,
    lengthMenu: [[5], [5]],
    language: {
      decimal: "",
      emptyTable: "No hay información",
      info: "Mostrando _START_ a _END_ de _TOTAL_ Planillas",
      infoEmpty: "",
      infoFiltered: "(Filtrado de _MAX_ total planillas)",
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
    columnDefs: [{ type: "date", targets: [2] }],
  });
  $("#tablaPlanillas").DataTable({
    order: [[0, "desc"]],
    paging: true,
    searching: true,
    select: false,
    autoWidth: false,
    info: true,
    processing: false,
    lengthChange: false,
    lengthMenu: [[10], [1]],
    language: {
      decimal: "",
      emptyTable: "No hay información",
      info: "Mostrando _START_ a _END_ de _TOTAL_ Planillas",
      infoEmpty: "",
      infoFiltered: "(Filtrado de _MAX_ total planillas)",
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
    columnDefs: [{ type: "date", targets: [2] }],
  });
  $("#tablaNoGuardado").DataTable({
    order: [[2, "desc"]],
    paging: true,
    searching: false,
    select: false,
    autoWidth: false,
    info: true,
    processing: false,
    lengthChange: false,
    lengthMenu: [[10], [1]],
    language: {
      decimal: "",
      emptyTable: "No hay información",
      info: "Mostrando _START_ a _END_ de _TOTAL_ Planillas",
      infoEmpty: "",
      infoFiltered: "(Filtrado de _MAX_ total planillas)",
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
    columnDefs: [{ type: "date", targets: [2] }],
  });

  $(".select2").select2({
    placeholder: "Seleccione una opción",
    allowClear: true,
    width: "100%",
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

  $("#formPlanilla").submit(function (e) {
    e.preventDefault();
    var errores = [];

    if (!$("#tipo_planilla").val()) {
      errores.push("Debe seleccionar un tipo de planilla");
    }

    if (!$("#codLote").val()) {
      errores.push("Debe ingresar un lote");
    }

    if (errores.length > 0) {
      let mensajeError = "<ul>";
      errores.forEach(function (error) {
        mensajeError += "<li>" + error + "</li>";
      });
      mensajeError += "</ul>";
      toastr.error(mensajeError);
      return false;
    }
  });
});
