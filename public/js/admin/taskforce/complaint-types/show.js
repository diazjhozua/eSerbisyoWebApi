
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
    DELETE_FORM.attr('action', window.location.origin + '/admin/complaints/' + id)
    CONFIRMATION_MESSAGE.text('Do you really want to delete this missing item complaint data? This process cannot be undone. All of the complainants & defendants related to this complaint will be deleted')
}

$(document).ready(function () {
    $('#TypeNavCollapse').addClass('active');
    $('#collapseType').collapse();
    $('#complaintType').addClass('active');

    // Set class row selected when any button was click in the selected
    COMPLAINT_TABLE.on('click', 'tr', function () {
        if (!$(this).hasClass('selected')) {
            COMPLAINT_TABLE.DataTable().$('tr.selected').removeClass('selected')
            $(this).addClass('selected')
        }
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

            let complaintTypeID = $('#complaintTypeID').val();

            let date_start = $('#date_start').val();
            let date_end = $('#date_end').val();
            let sort_column = $('#sort_column').val();
            let sort_option = $('#sort_option').val();
            let status_option = $('#status_option').val();

            var url = window.location.origin + `/admin/complaint-types/reportShow/${complaintTypeID}/${date_start}/${date_end}/${sort_column}/${sort_option}/${status_option}`;
            window.open(url, '_blank');
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


})
