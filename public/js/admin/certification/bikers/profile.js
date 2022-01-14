


$(document).ready(function () {
    $('#bikerCollapse').addClass('active');
    $('#collapseBiker').collapse();
    $('#biker_nav').addClass('active');

    $('#dataTableRecord').DataTable({
        scrollY: "400px",
        scrollX: true,
        scrollCollapse: true,
        paging: true,
    });

    $('#dataTableRecord').wrap('<div id="scrooll_div"></div >');
    $('#scrooll_div').doubleScroll({
        resetOnWindowResize: true
    });


});
