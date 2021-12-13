
const COMPLAINT_NAV = $('#complaint');
const COMPLAINT_TABLE = $('#dataTable');

// DELETE MODAL VARIABLE
const DELETE_MODAL = $('#confirmationDeleteModal');
const DELETE_FORM = $('#modalDeleteForm');
const CONFIRMATION_MESSAGE = $('#confirmationMessage');
const BTN_DELETE = $('#btnDelete');
const BTN_DELETE_TXT = $('.btnDeleteTxt');
const BTN_DELETE_LOADING = $('.btnDeleteLoadingIcon');

// SPAN/DIV COUNTER
const COMPLAINT_COUNT = $('#complaintsCount');
const THIS_MONTH_COUNT = $('#thisMonthCount');
const THIS_MONTH_PENDING_COUNT = $('#thisMonthPendingCount');
const THIS_MONTH_APPROVED_COUNT = $('#thisMonthApprovedCount');
const THIS_MONTH_RESOLVED_COUNT = $('#thisMonthResolvedCount');
const THIS_MONTH_DENIED_COUNT = $('#thisMonthDeniedCount');

var currentRowCreatedAt; //for deleting purposes
var currentRowStatus; //for deleting purposes

//delete function
function deleteComplaint(id) {
    DELETE_MODAL.modal('show')
    DELETE_FORM.attr('action', '/admin/complaints/' + id)
    CONFIRMATION_MESSAGE.text('Do you really want to delete this missing item complaint data? This process cannot be undone. All of the complainants & defendants related to this complaint will be deleted')
}

// add or replace the in the datatable
function addOrReplaceData(data, addOrReplace) {

    col0 = '<td>' + data.id + '</td>';
    col1 = '<td>' + data.user_name + '(#' + data.user_id + ')' + '(#' + data.user_role + ')' + '</td>';
    col2 = '<td><p><strong>' + data.custom_type != null ? data.complaint_type : data.custom_type + '</strong></p></td>';
    col3 = '<td>' + data.reason + ' yr old</td>';
    col4 = '<td>' + data.action + '</td>';

    let col5;
    if (data.user_id == data.contact_id) {
        col5 = '<td> Same user </td>';
    } else {
        col5 = '<td>' + data.contact_name + '(#' + data.contact_id + ')' + '(#' + data.contact_role + ')' + '</td>';
    }

    col6 =
        '<td>' +
        '<span>Email: <br>' +
        '<strong>' + data.email + '</strong>' +
        '</span> <br>' +
        '<span>Phone No: <br>' +
        '<strong>' + data.phone_no + '</strong>' +
        '</span>' +
        '</td>';


    switch (data.status) {
        case 'Pending':
            className = 'bg-info';
            break;
        case 'Approved':
            className = 'bg-white';
            break;
        case 'Resolved':
            className = 'bg-success';
            break;
        case 'Denied':
            className = 'bg-danger';
            break;
    }

    col7 = '<td>' +
        '<div class="p-2 ' + className + ' text-white rounded-pill text-center">' +
        data.status +
        '</div>' +
        '</td>';

    col8 = '<td>' + data.created_at + '</td>';

    viewBtn =
        '<li class="list-inline-item mb-1">' +
        '<a class="btn btn-info btn-sm" type="button" data-toggle="tooltip" data-placement="top" title="View" href="' + window.location.origin + '/admin/complaints/' + data.id + '"><i class="fas fa-eye"></i>' +
        '</a></li>';

    deleteBtn =
        '<li class="list-inline-item mb-1">' +
        '<button class="btn btn-danger btn-sm" onclick="deleteComplaint(' + data.id + ')" type="button" data-toggle="tooltip" data-placement="top" title="Delete">' +
        '<i class="fas fa-trash-alt"></i>' +
        '</button>' +
        '</li>';

    col9 = '<td><ul class="list-inline m-0">' + viewBtn + deleteBtn + '</td></ul>';

    // Get table reference - note: dataTable() not DataTable()
    var table = $('#dataTable').DataTable();

    if (addOrReplace == 'Replace') {
        // if replacing the table row
        table.row('.selected').data([col0, col1, col2, col3, col4, col5, col6, col7, col8, col9]).draw(false);
    } else {
        // if adding new table row
        var currentPage = table.page();
        table.row.add([col0, col1, col2, col3, col4, col5, col6, col7, col8, col9]).draw();

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
    COMPLAINT_NAV.addClass('active');

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

    var channel = pusher.subscribe('private-complaint-channel');

    channel.bind('complaint-channel', function (data) {
        addOrReplaceData(data.missingItem, 'Add')
        toastr.warning('User ' + data.missingItem.user_name + 'submitted a complaint. Please repond to the specific complaint.')
        // add to pending card if it is new report
        THIS_MONTH_COUNT.text(parseInt(THIS_MONTH_COUNT.text()) + 1);
        THIS_MONTH_PENDING_COUNT.text(parseInt(THIS_MONTH_PENDING_COUNT.text()) + 1);
        COMPLAINT_COUNT.text(parseInt(COMPLAINT_COUNT.text()) + 1);
    });

    channel.bind('pusher:subscription_succeeded', function (members) {
        toastr.info('Broadcast Server Connected. You may able receive a new report from a resident realtime')
    });

    // end of pusher //

    // Set class row selected when any button was click in the selected
    COMPLAINT_TABLE.on('click', 'tr', function () {
        if (!$(this).hasClass('selected')) {
            COMPLAINT_TABLE.DataTable().$('tr.selected').removeClass('selected')
            $(this).addClass('selected')

            var currentRow = $(this).closest("tr");

            //set value of global variables
            currentRowCreatedAt = currentRow.find("td:eq(8)").text();
            currentRowStatus = currentRow.find("td:eq(7)").text().trim();
        }
    });

    // Delete Modal Form
    DELETE_FORM.submit(function (e) {
        e.preventDefault()
        let ajaxDelURL = DELETE_FORM.attr('action')

        $.ajax({
            type: 'DELETE',
            url: ajaxDelURL,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
            cache: false,
            processData: false,
            contentType: false,
            beforeSend: function () {
                BTN_DELETE.attr("disabled", true); //disabled button
                BTN_DELETE_TXT.text('Deleting') //set the text of the submit btn
                BTN_DELETE_LOADING.prop("hidden", false) //show the fa loading icon from delete btn
            },
            success: function (response) {
                toastr.success(response.message);
                var table = COMPLAINT_TABLE.DataTable();
                $('.selected').fadeOut(800, function () {
                    table.row('.selected').remove().draw();
                });

                // decrement total report count
                COMPLAINT_COUNT.text(parseInt(COMPLAINT_COUNT.text()) - 1);

                let reportDate = new Date(currentRowCreatedAt);
                let currentDate = new Date();

                if (reportDate.getFullYear() == currentDate.getFullYear() && currentDate.getMonth() == reportDate.getMonth()) {
                    //means the date is in this current month

                    // decrement this month total report count
                    THIS_MONTH_COUNT.text(parseInt(THIS_MONTH_COUNT.text()) - 1);

                    switch (currentRowStatus) {
                        case 'Pending':
                            THIS_MONTH_PENDING_COUNT.text(parseInt(THIS_MONTH_PENDING_COUNT.text()) - 1);
                            break;
                        case 'Approved':
                            THIS_MONTH_APPROVED_COUNT.text(parseInt(THIS_MONTH_APPROVED_COUNT.text()) - 1);
                            break;
                        case 'Resolved':
                            THIS_MONTH_RESOLVED_COUNT.text(parseInt(THIS_MONTH_RESOLVED_COUNT.text()) - 1);
                            break;
                        case 'Denied':
                            THIS_MONTH_DENIED_COUNT.text(parseInt(THIS_MONTH_DENIED_COUNT.text()) - 1);
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
                BTN_DELETE.attr("disabled", false); //enable button
                BTN_DELETE_TXT.text('Delete') //set the text of the delete btn
                BTN_DELETE_LOADING.prop("hidden", true) //hide the fa loading icon from submit btn

                DELETE_MODAL.modal('hide') //hide
                DELETE_FORM.trigger("reset"); //reset all the values
            }
        });
    });

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
            let status_option = $('#status_option').val();

            var url = window.location.origin + `/admin/complaints/report/${date_start}/${date_end}/${sort_column}/${sort_option}/${status_option}`;
            window.open(url, '_blank');
        }
    });


})
