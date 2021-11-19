function editReport() {

}

$(document).ready(function () {

    // Initialize Year picker in report form
    $(".datepicker").datepicker({
        format: "yyyy-mm-dd",
    });

    $('#dataTableComment').DataTable({
        scrollY: "1000px",
        scrollX: true,
        scrollCollapse: true,
        paging: true,
    });

})
