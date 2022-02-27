function viewReport(reportID) {
    let url = window.location.origin + '/admin/reports/' + reportID;

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
            let actionURL = window.location.origin + '/admin/reports/' + data.id + '/respond'
            $('#reportForm').attr('action', actionURL)

            $('#urgency_classification').text(data.urgency_classification)
            $('#location_address').text(data.location_address)
            $('#landmark').text(data.landmark)
            $('#description').text(data.description)

            if (data.picture_name == null) {
                $('#picture').text('No picture provided')
            } else {
                $('#picture').html('<a href="' + data.file_path + '" target="_blank">' + data.picture_name + '</a></a>')
            }

            $('#profileHead').text(data.submitted_by + ' (#' + data.user_id + ')');

            // set picture
            $('#profilePicture').prop('src', data.user_file_path != null ? data.user_file_path : 'https://pbs.twimg.com/media/D8tCa48VsAA4lxn.jpg'); //add the src attribute
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
        col2 = '<td>' + data.custom_type + '</td>'
    } else {
        col2 = '<td><a href="' + window.location.origin + '/admin/report-types/' + data.type_id + '">' + data.report_type + '</a></td>'
    }

    if (data.urgency_classification == 'Urgent') {
        col3 = '<td><p class="text-danger"><strong>' + data.urgency_classification + '</strong></p></td>'
    } else {
        col3 = '<td><p class="text-warning"><strong>' + data.urgency_classification + '</strong></p></td>'
    }

    if (data.status == 'Noted') {
        col4 = '<td><div class="p-2 bg-success text-white rounded-pill text-center">'
            + data.status
            + '</div>'
            + '</td>'
    } else if (data.status == 'Pending') {
        col4 = '<td><div class="p-2 bg-warning text-white rounded-pill text-center">'
            + data.status
            + '</div>'
            + '</td>'
    } else if (data.status == 'Ignored') {
        col4 = '<td><div class="p-2 bg-danger text-white rounded-pill text-center">'
            + data.status
            + '</div>'
            + '</td>'
    } else if (data.status == 'Invalid') {
        col4 = '<td><div class="p-2 bg-secondary text-white rounded-pill text-center">'
            + data.status
            + '</div>'
            + '</td>'
    }

    col5 = '<td>' + data.created_at + '</td>'
    viewBtn =
        '<li class="list-inline-item mb-1">' +
        '<button class="btn btn-primary btn-sm" onclick="viewReport(' + data.id + ')" type="button" data-toggle="tooltip" data-placement="top" title="View">' +
        'View Report' +
        '</button>' +
        '</li>'

    col6 = '<td><ul class="list-inline m-0">' + viewBtn + '</td></ul>'

    // Get table reference - note: dataTable() not DataTable()
    var table = $('#dataTable').DataTable();
    if (addOrReplace == 'Replace') {
        table.row('.selected').data([col0, col1, col2, col3, col4, col5, col6]).draw(false);
    } else {
        var currentPage = table.page();
        table.row.add([col0, col1, col2, col3, col4, col5, col6]).draw()

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

    // start of pusher //

    // Enable pusher logging - don't include this in production
    Pusher.logToConsole = true;

    var pusher = new Pusher('246912fa37725a18907b', {
        cluster: 'ap1',
        authEndpoint: '/broadcasting/auth',
        forceTLS: true,
        auth: {
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        }
    });

    var channel = pusher.subscribe('private-report-channel');

    channel.bind('report-channel', function (data) {
        addOrReplace(data.report, 'Add')
        toastr.warning('User ' + data.report.submitted_by + 'submitted a report. Please repond to the specific report.')
        $("#reportsCount").text(parseInt($("#reportsCount").text()) + 1);
        $("#thisMonthPendingCount").text(parseInt($("#thisMonthPendingCount").text()) + 1);
        $("#thisMonthCount").text(parseInt($("#thisMonthCount").text()) + 1);
    });

    channel.bind('pusher:subscription_succeeded', function (members) {
        toastr.info('Broadcast Server Connected. You may able receive a new report from a resident realtime')
    });

    // end of pusher //



    $('#report').addClass('active')

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
                    const data = response.data
                    addOrReplace(data, 'Replace')

                    let reportDate = new Date(data.created_at);
                    let currentDate = new Date();

                    if (reportDate.getFullYear() == currentDate.getFullYear() && currentDate.getMonth() == reportDate.getMonth()) {
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
            classification_option: {
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
            event.preventDefault();

            let date_start = $('#date_start').val();
            let date_end = $('#date_end').val();
            let sort_column = $('#sort_column').val();
            let sort_option = $('#sort_option').val();
            let classification_option = $('#classification_option').val();
            let status_option = $('#status_option').val();

            var url = window.location.origin + `/admin/reports/report/${date_start}/${date_end}/${sort_column}/${sort_option}/${classification_option}/${status_option}`;
            window.open(url, '_blank');
        }
    });

})
