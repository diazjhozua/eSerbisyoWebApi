function sendRespond(id) {
    $('#feedbackRespondModal').modal('show');
    $('#respondForm').attr('action', window.location.origin + '/admin/feedbacks/respond/' + id);
}

$(document).ready(function () {
    // Set class row selected when any button was click in the selected
    $('#dataTable').on('click', 'tr', function () {
        if (!$(this).hasClass('selected')) {
            $('#dataTable').DataTable().$('tr.selected').removeClass('selected')
            $(this).addClass('selected')
        }
    })


    // Initialize Year picker in report form
    $(".datepicker").datepicker({
        format: "yyyy-mm-dd",
    });


    $('#feedback').addClass('active')

    $("form[name='feedbackRespondForm']").validate({
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
            let formAction = $("#respondForm").attr('action')
            let formData = new FormData(form)

            $('#btnFormSubmit').attr("disabled", true); //disabled login
            $('.btnTxt').text('Responding') //set the text of the submit btn
            $('.loadingIcon').prop("hidden", false) //show the fa loading icon from submit btn

            doAjax(formAction, 'POST', formData).then((response) => {
                if (response.success) {
                    $('#feedbackRespondModal').modal('hide') //hide the modal

                    const data = response.data

                    col0 = '<td>' + data.id + '</td>'
                    col1 = '<td>' + data.submitted_by + '</td>'
                    if (data.type_id === 0) {
                        col2 = '<td>' + data.custom_type + '</td>'
                    } else {
                        col2 = '<td>' + data.type + '</td>'
                    }
                    col3 = '<td>' + data.polarity + '</td>'
                    col4 = '<td>' + data.message + '</td>'
                    col5 = '<td>' + data.admin_respond + '</td>'
                    col6 = '<td>' + data.status + '</td>'
                    col7 = '<td>' + data.created_at + '</td>'
                    btnStatus = data.status !== 'Pending' ? 'disabled' : ''
                    col8 = '<td>' +
                        '<ul class="list-inline m-0">' +
                        '<li class="list-inline-item mb-1">' +
                        '<button class="btn btn-primary btn-sm" type="button" onclick="sendRespond(' + data.id + ')" data-toggle="tooltip" data-placement="top" title="Respond"' + btnStatus + '>' +
                        'Responded' +
                        '</button>' +
                        '</li>' +
                        '</ul>'
                    '</td>'

                    // Get table reference - note: dataTable() not DataTable()
                    var table = $('#dataTable').DataTable();

                    if (data.type_id === 0) {
                        table.row('.selected').data([col0, col1, col2, col3, col4, col5, col6, col7, col8]).draw(false);
                    } else {
                        table.row('.selected').data([col0, col1, col2, col3, col4, col5, col6, col7, col8]).draw(false);
                    }

                    // add or minus some cards
                    $("#pendingFeedbackCount").text(parseInt($("#pendingFeedbackCount").text()) - 1);
                    $("#notedFeedbackCount").text(parseInt($("#notedFeedbackCount").text()) + 1);
                }

                $('#btnFormSubmit').attr("disabled", false); //disabled login
                $('.btnTxt').text('Respond') //set the text of the submit btn
                $('.loadingIcon').prop("hidden", true) //show the fa loading icon from submit btn
            })
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
            polarity_option: {
                required: true,
            },
            status_option: {
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

            let formAction = $("#reportForm").attr('action')
            let formData = new FormData(form)

            $('.btnReportFormSubmit').attr("disabled", true); //disabled login
            $('.btnReportFormTxt').text('Generating') //set the text of the submit btn
            $('.btnReportFormLoadingIcon').prop("hidden", false) //show the fa loading icon from submit btn

            $.ajax({
                type: 'POST',
                url: formAction,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                data: formData,
                xhrFields: {
                    responseType: 'blob'
                },
                cache: false,
                processData: false,
                contentType: false,

                success: function (response) {
                    toastr.success('Report successfully downloaded')
                    var blob = new Blob([response]);
                    var link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = "FeedbackReport.pdf";
                    link.click();

                },
                error: function (response) {
                    toastr.error('Something went wrong :( (It could be the selected option produces no data)')
                },
                complete: function () {
                    $('.btnReportFormSubmit').attr("disabled", false); //enable the button
                    $('.btnReportFormTxt').text('Generate') //set the text of the submit btn
                    $('.btnReportFormLoadingIcon').prop("hidden", true) //hide the fa loading icon from submit btn
                }
            });
        }
    });


})
