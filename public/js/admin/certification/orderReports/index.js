function sendRespond(id) {
    $('#orderReportRespondModal').modal('show');
    $('#orderReportForm').attr('action', window.location.origin + '/admin/orderReports/respond/' + id);
}

$(document).ready(function () {


// Initialize Year picker in form
$(".datepicker").datepicker({
    format: "yyyy-mm-dd", // Notice the Extra space at the beginning
});


    $('#orderReport').addClass('active')

    $("form[name='orderReportRespondForm']").validate({
        // Specify validation rules
        rules: {
            admin_respond: {
                required: true,
                minlength: 4,
                maxlength: 250,
            },
        },

        submitHandler: function (form, event) {
            event.preventDefault()
            let formAction = $("#orderReportForm").attr('action')
            let formData = new FormData(form)

            $('#btnFormSubmit').attr("disabled", true); //disabled login
            $('.btnTxt').text('Responding') //set the text of the submit btn
            $('.loadingIcon').prop("hidden", false) //show the fa loading icon from submit btn

            $.ajax({
                type: 'POST',
                url: formAction,
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                cache: false,
                processData: false,
                contentType: false,
                beforeSend: function () {
                    $('#btnFormSubmit').attr("disabled", true); //disabled login
                    $('.btnTxt').text('Responding') //set the text of the submit btn
                    $('.loadingIcon').prop("hidden", false) //show the fa loading icon from submit btn
                },
                success: function (response) {
                    toastr.success(response.message);
                    $('#orderReportRespondModal').modal('hide') //hide the modal
                    location.reload();
                },
                error: function (xhr) {
                    var error = JSON.parse(xhr.responseText);

                    // show error message from helper.js
                    ajaxErrorMessage(error);
                },
                complete: function () {
                    $('#orderReportRespondModal').modal('hide') //hide the modal
                    $('#btnFormSubmit').attr("disabled", false); //disabled login
                    $('.btnTxt').text('Respond') //set the text of the submit btn
                    $('.loadingIcon').prop("hidden", true) //show the fa loading icon from submit btn
                }
            });
        }
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
        status: {
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

        let date_start = $('#date_start').val();
        let date_end = $('#date_end').val();
        let sort_column = $('#sort_column').val();
        let sort_option = $('#sort_option').val();
        let status = $('#status').val();

        var url = `${window.location.origin}/admin/orderReports/report/${date_start}/${date_end}/${sort_column}/${sort_option}/${status}/`;
        window.open(url, '_blank');
    }
});
});
