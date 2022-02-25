$(document).ready(function () {
    $('#auditLogItem').addClass('active');

    // Initialize Year picker in report form
    $(".datepicker").datepicker({
        format: "yyyy-mm-dd",
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
        },
        messages: {
            date_end: {
                greaterThan: "Date end must be greater than selected date start"
            },
        },

        submitHandler: function (form, event) {
            event.preventDefault()

            let date_start = $('#date_start').val();
            let date_end = $('#date_end').val();
            let sort_column = $('#sort_column').val();
            let sort_option = $('#sort_option').val();

            var url = `${window.location.origin}/admin/audit-logs/reports/${date_start}/${date_end}/${sort_column}/${sort_option}/`;
            window.open(url, '_blank');
        }
    });
})
