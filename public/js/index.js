jQuery(document).ready(function ($) {
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
