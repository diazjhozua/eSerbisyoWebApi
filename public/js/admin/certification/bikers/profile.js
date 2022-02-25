


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

    // Initialize Year picker in form
    $(".datepicker").datepicker({
        format: "yyyy-mm-dd", // Notice the Extra space at the beginning
    });

    // Validation for report form
    $("form[name='reportForm']").validate({
        // Specify validation rules
        rules: {
            date_start: {
                required: true,
            },
            date_end: {
                required: true,
                greaterThan: "#date_start"
            },
            sort_column: {
                required: true,
            },
            sort_option: {
                required: true,
            },
            order_status: {
                required: true,
            },
        },
        messages: {
            date_end: {
                greaterThan: "Date end must be greater than selected date start"
            },
        },

        submitHandler: function (form, event) {
            event.preventDefault();

            let biker_id = $('#user_id').val();
            let date_start = $('#date_start').val();
            let date_end = $('#date_end').val();
            let sort_column = $('#sort_column').val();
            let sort_option = $('#sort_option').val();
            let order_status = $('#order_status').val();

            var url = `${window.location.origin}/admin/bikers/report/${biker_id}/${date_start}/${date_end}/${sort_column}/${sort_option}/${order_status}`;
            window.open(url, '_blank');
        }
    });

});
