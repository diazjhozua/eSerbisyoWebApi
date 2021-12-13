
function viewReport(reportID) {
    let url = window.location.origin + '/admin/reports/' + reportID


    $.ajax({
        type: 'GET',
        url: url,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        },
        cache: false,
        processData: false,
        contentType: false,
        success: function (response) {
            toastr.success(response.message);

            const data = response.data;

            $('#reportFormModal').modal('show') //show the modal

            $('#type').text(function (value) {
                if (data.type_id != 0) {
                    return value = data.report_type + '(#' + data.type_id + ')'
                } else {
                    return value = data.custom_type
                }
            })

            $('#statusSelect').empty() //reset dropdown button
            $('#reportForm').trigger("reset") //reset all the input values
            let actionURL = window.location.origin + '/admin/reports/' + data.id + '/respond/'
            $('#reportForm').attr('action', actionURL)

            $('#urgencyClassification').text(data.urgency_classification)
            $('#location_address').text(data.location_address)
            $('#landmark').text(data.landmark)
            $('#description').text(data.description)

            if (data.picture_name == null) {
                $('#picture').text('No picture provided')
            } else {
                $('#picture').html('<a href="' + window.location.origin + '/admin/files/reports/' + data.picture_name + '" target="_blank">' + data.picture_name + '</a></a>')
            }

            $('#profileHead').text(data.submitted_by + ' (#' + data.user_id + ')');

            // set picture
            $('#profilePicture').prop('src', data.user_file_path != null ? window.location.origin + '/storage/' + data.user_file_path : 'https://pbs.twimg.com/media/D8tCa48VsAA4lxn.jpg'); //add the src attribute
            $("#profilePicture").prop("alt", data.submitted_by + ' picture'); //add the alt text

            if (data.status == 'Pending') {
                $('#statusSelect').prop("disabled", false);
                $('#txtAreaAdminMessage').prop("disabled", false);

                $('#statusSelect').append($("<option selected/>").val("").text('Choose...'))
                $('#statusSelect').append($("<option />").val('Noted').text('Noted'))
                $('#statusSelect').append($("<option />").val('Invalid').text('Invalid'))

                $('.btnRespondReport').prop('disabled', false);
                $('.btnRespondReportTxt').text('Respond')
                $('.btnRespondReport').removeClass('btn-danger');
                $('.btnRespondReport').removeClass('btn-success');
                $('.btnRespondReport').addClass('btn-primary');

            } else {
                // If the report is already responded or ignored
                $('#statusSelect').append($("<option selected/>").val("").text(data.status))
                $('#txtAreaAdminMessage').val(data.admin_message)
                // disable the admin message and status select input
                $('#statusSelect').prop("disabled", true);
                $('#txtAreaAdminMessage').prop("disabled", true);
                // form button submit
                $('.btnRespondReport').prop('disabled', true);

                $('.btnRespondReportTxt').text(function () {
                    if (data.status != 'Ignored') {
                        $('.btnRespondReport').removeClass('btn-danger');
                        $('.btnRespondReport').removeClass('btn-primary');
                        $('.btnRespondReport').addClass('btn-success');
                        return 'Responded';
                    } else {
                        $('.btnRespondReport').removeClass('btn-success');
                        $('.btnRespondReport').removeClass('btn-primary');
                        $('.btnRespondReport').addClass('btn-danger');
                        return 'Ignored';
                    }
                })
            }

        },
        error: function (xhr) {
            var error = JSON.parse(xhr.responseText);

            // show error message from helper.js
            ajaxErrorMessage(error);
        }
    });
}

function addOrReplace(data, addOrReplace) {

    $('#reportFormModal').modal('hide') //hide the modal

    col0 = '<td>' + data.id + '</td>'
    col1 = '<td>' + data.submitted_by + '(#' + data.user_id + ')' + '</td>'
    if (data.user_id == 0) {
        col1 = '<td>' + data.submitted_by + '</td>'
    } else {
        col1 = '<td>' + data.submitted_by + '(#' + data.user_id + ')' + '</td>'
    }

    if (data.type_id == 0) {
        extra = '<td>' + data.custom_type + '</td>'
    }

    if (data.urgency_classification == 'Urgent') {
        col2 = '<td><p class="text-danger"><strong>' + data.urgency_classification + '</strong></p></td>'
    } else {
        col2 = '<td><p class="text-warning"><strong>' + data.urgency_classification + '</strong></p></td>'
    }

    if (data.status == 'Noted') {
        col3 = '<td><div class="p-2 bg-success text-white rounded-pill text-center">'
            + data.status
            + '</div>'
            + '</td>'
    } else if (data.status == 'Pending') {
        col3 = '<td><div class="p-2 bg-warning text-white rounded-pill text-center">'
            + data.status
            + '</div>'
            + '</td>'
    } else if (data.status == 'Ignored') {
        col3 = '<td><div class="p-2 bg-danger text-white rounded-pill text-center">'
            + data.status
            + '</div>'
            + '</td>'
    } else if (data.status == 'Invalid') {
        col3 = '<td><div class="p-2 bg-secondary text-white rounded-pill text-center">'
            + data.status
            + '</div>'
            + '</td>'
    }

    col4 = '<td>' + data.created_at + '</td>'
    viewBtn =
        '<li class="list-inline-item mb-1">' +
        '<button class="btn btn-primary btn-sm" onclick="viewReport(' + data.id + ')" type="button" data-toggle="tooltip" data-placement="top" title="View">' +
        'View Report' +
        '</button>' +
        '</li>'

    col5 = '<td><ul class="list-inline m-0">' + viewBtn + '</td></ul>'

    // Get table reference - note: dataTable() not DataTable()
    var table = $('#dataTable').DataTable();
    if (addOrReplace == 'Replace') {
        if (data.type_id == 0) {
            table.row('.selected').data([col0, col1, extra, col2, col3, col4, col5]).draw(false);
        } else {
            table.row('.selected').data([col0, col1, col2, col3, col4, col5]).draw(false);
        }


    } else {
        var currentPage = table.page();
        if (data.type_id == 0) {
            table.row('.selected').data([col0, col1, extra, col2, col3, col4, col5]).draw();
        } else {
            table.row.add([col0, col1, col2, col3, col4, col5]).draw()
        }

        selectedRow = 0
        var index = table.row(selectedRow).index(),
            rowCount = table.data().length - 1,
            insertedRow = table.row(rowCount).data(),
            tempRow

        for (var i = rowCount; i > index; i--) {
            tempRow = table.row(i - 1).data()
            table.row(i).data(tempRow)
            table.row(i - 1).data(insertedRow)
        }

        //refresh the page
        table.page(currentPage).draw(false)
    }

}


$(document).ready(function () {

    $('#TypeNavCollapse').addClass('active')
    $('#collapseType').collapse()
    $('#reportType').addClass('active')

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

    $("form[name='respondReportForm']").validate({
        // Specify validation rules
        rules: {
            status: {
                required: true,
            },
            admin_message: {
                required: true,
                minlength: 4,
                maxlength: 250,
            },
        },

        submitHandler: function (form, event) {
            event.preventDefault()
            let formAction = $("#reportForm").attr('action')
            let formData = new FormData(form)

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
                    $('.btnRespondReport').attr("disabled", true); //disabled login
                    $('.btnRespondReportTxt').text($('#statusSelect').val() == 'Noted' ? 'Noting' : 'Invalidating') //set the text of the submit btn
                    $('.btnRespondReportLoadingIcon').prop("hidden", false) //show the fa loading icon from submit btn
                },
                success: function (response) {
                    toastr.success(response.message);
                    const data = response.data;
                    addOrReplace(data, 'Replace');

                    //means the date is in this current month
                    $("#thisMonthPendingCount").text(parseInt($("#thisMonthPendingCount").text()) - 1);

                    switch (data.status) {
                        case 'Noted':
                            $("#thisMonthNotedCount").text(parseInt($("#thisMonthNotedCount").text()) + 1);
                            break;
                        case 'Invalid':
                            $("#thisMonthInvalidCount").text(parseInt($("#thisMonthInvalidCount").text()) + 1);
                            break;
                    }
                },
                error: function (xhr) {
                    var error = JSON.parse(xhr.responseText);

                    // show error message from helper.js
                    ajaxErrorMessage(error);
                },
                complete: function () {
                    $('.btnRespondReport').attr("disabled", false); //enable the button
                    $('.btnRespondReportTxt').text('Respond') //set the text of the submit btn
                    $('.btnRespondReportLoadingIcon').prop("hidden", true) //hide the fa loading icon from submit btn
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

            let reportTypeID = $('#reportTypeID').val();

            let date_start = $('#date_start').val();
            let date_end = $('#date_end').val();
            let sort_column = $('#sort_column').val();
            let sort_option = $('#sort_option').val();
            let classification_option = $('#classification_option').val();
            let status_option = $('#status_option').val();

            var url = window.location.origin + `/admin/report-types/reportShow/${reportTypeID}/${date_start}/${date_end}/${sort_column}/${sort_option}/${classification_option}/${status_option}`;

            window.open(url, '_blank');
        }
    });


})
