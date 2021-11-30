// Call the dataTables jQuery plugin
$(document).ready(function () {
    //   $('#dataTable').DataTable().column().visible();

    $('#dataTable').dataTable({
        "aaSorting": []
        // Your other options here...
    });

    $('#dataTable').wrap('<div id="scrooll_div"></div >');
    $('#scrooll_div').doubleScroll({
        resetOnWindowResize: true
    });
});
