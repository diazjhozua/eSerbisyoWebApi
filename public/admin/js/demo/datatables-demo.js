// Call the dataTables jQuery plugin
$(document).ready(function() {
//   $('#dataTable').DataTable().column().visible();

    $('#dataTable').dataTable( {
        "aaSorting": []
        // Your other options here...
    });
});
