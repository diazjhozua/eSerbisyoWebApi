// global variables
var currentRowCreatedAt; //for deleting purposes
var currentRowStatus; //for deleting purposes

function modifyModal(title, btnText, inputMethod, actionURL,) {

    $('#reportFormModal').modal('show') //show the modal
    $('#reportFormModalHeader').text(title) //set the header of the
    $('.btnFormTxt').text(btnText) //set the text of the submit btn
    $('#reportForm').trigger("reset"); //reset all the values
    $("#formMethod").empty();
    $("#formMethod").append(inputMethod) // append formMethod
    $('#reportForm').attr('action', actionURL) //set the method of the form

    $("#currentPictureDiv").hide(); // hide the current picture div
    $('#imgCurrentPicture').removeAttr('src'); //remoce the src attribute
    $("#imgCurrentPicture").prop("alt", ""); //remove the alt text

    //empty dropdown list
    $('#report_type').empty();
}

function populateAllSelectFields(data, reportTypes) {

    // populate report type select form
    $('#report_type').append($("<option selected/>").val(data == null ? null : data.report_type).text(data == null ? 'Choose type' : data.report_type))
    $.each(reportTypes, function () {
        // Populate Drop Dowm
        if (data == null) {
            $('#report_type').append($("<option />").val(this.type).text(this.type))
        } else {
            if (this.type != data.report_type) {
                // Populate Position Drop Dowm
                $('#report_type').append($("<option />").val(this.type).text(this.type))
            }
        }
    })
}

function createReport() {
    url = 'missing-items/create';
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
            const reportTypes = response.reportTypes;

            let actionURL = '/admin/missing-items/';
            let inputMethod = '<input type="hidden" id="method" name="_method" value="POST">';

            //modify modal form function
            modifyModal('Publish Missing Report', 'Store', inputMethod, actionURL);

            //populate all the report select form fields
            populateAllSelectFields(null, reportTypes);
        },
        error: function (xhr, status, error) {
            var error = JSON.parse(xhr.responseText);

            // show error message from helper.js
            ajaxErrorMessage(error);
        },
        complete: function () {

        }
    });
}

function editReport(id) {
    url = 'missing-items/' + id + '/edit';

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
            toastr.info('The fields can now be edited');

            const data = response.data; //missing report data
            const reportTypes = response.reportTypes;

            let actionURL = '/admin/missing-items/' + data.id;
            let inputMethod = '<input type="hidden" id="method" name="_method" value="PUT">';

            //modify modal form function
            modifyModal('Edit Missing Report', 'Update', inputMethod, actionURL);

            //populate all the report select form fields
            populateAllSelectFields(data, reportTypes);

            // populate and show the current picture of missing person data
            $('.custom-file-label').html(data.picture_name);
            $("#currentPictureDiv").show(); // show the current picture div
            $('#imgCurrentPicture').prop('src', data.picture_src); //add the src attribute
            $("#imgCurrentPicture").prop("alt", data.name + ' picture'); //add the alt text

            // populate fields of missing person data
            $('#item').val(data.item);
            $('#contact_user_id').val(data.contact_id);
            $('#description').val(data.description);
            $('#email').val(data.email);
            $('#phone_no').val(data.phone_no);
            $('#last_seen').val(data.last_seen);
            $('#important_information').val(data.important_information);
        },
        error: function (xhr, status, error) {
            var error = JSON.parse(xhr.responseText);

            // show error message from helper.js
            ajaxErrorMessage(error);
        }
    });
}

//delete function
function deleteReport(id) {
    $('#confirmationDeleteModal').modal('show');
    $('#modalDeleteForm').attr('action', '/admin/missing-items/' + id);
    $('#confirmationMessage').text('Do you really want to delete this missing item report data? This process cannot be undone. All of the comments related to this report will be deleted');
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

    var channel = pusher.subscribe('private-missingItem-channel');

    channel.bind('missingItem-channel', function (data) {
        addOrReplaceData(data.missingItem, 'Add')
        toastr.warning('User ' + data.missingItem.user_name + 'submitted a report. Please repond to the specific report.')
        // add to pending card if it is new report
        $("#thisMonthCount").text(parseInt($("#thisMonthCount").text()) + 1);
        $("#thisMonthPendingCount").text(parseInt($("#thisMonthPendingCount").text()) + 1);
        $("#reportsCount").text(parseInt($("#reportsCount").text()) + 1);
    });

    channel.bind('pusher:subscription_succeeded', function (members) {
        toastr.info('Broadcast Server Connected. You may able receive a new report from a resident realtime')
    });

    // end of pusher //

    $('#missingItem').addClass('active')

    $('input[type="file"]').change(function (e) {
        var fileName = e.target.files[0].name;
        $('.custom-file-label').html(fileName);
    });


    // Set class row selected when any button was click in the selected
    $('#dataTable').on('click', 'tr', function () {
        if (!$(this).hasClass('selected')) {
            $('#dataTable').DataTable().$('tr.selected').removeClass('selected')
            $(this).addClass('selected')

            var currentRow = $(this).closest("tr");

            //set value of global variables
            currentRowCreatedAt = currentRow.find("td:eq(10)").text();
            currentRowStatus = currentRow.find("td:eq(9)").text().trim();
        }
    })


    // Initialize Year picker in report form
    $(".datepicker").datepicker({
        format: "yyyy-mm-dd",
    });

    $("form[name='missingForm']").validate({
        // Specify validation rules
        rules: {
            name: {
                required: true,
                minlength: 3,
                maxlength: 100,
            },
            contact_user_id: {
                required: true,
                number: true,
                min: 1,
            },
            important_information: {
                required: true,
                minlength: 3,
                maxlength: 250,
            },
            last_seen: {
                required: true,
                minlength: 3,
                maxlength: 60,
            },
            email: {
                required: true,
                email: true,
                maxlength: 30,
            },
            phone_no: {
                required: true,
                number: true,
                minlength: 11,
                maxlength: 11,
            },

            report_type: {
                required: true,
            },
            picture: {
                required: function () {
                    return $('#method').val() == 'POST'
                },
                extension: "jpeg|jpg|png",
                filesize: 10485760, //10mb in bytes
            },
        },
        messages: {
            picture: {
                extension: "Invalid file type! picture must be in jpeg|jpg|png format",
                filesize: "Selected file must be less than 10mb"
            },
        },

        submitHandler: function (form, event) {
            event.preventDefault()

            let formAction = $("#reportForm").attr('action')
            let formMethod = $('#method').val()
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
                    $('.btnFormSubmit').attr("disabled", true); //disabled login
                    $('.btnFormTxt').text(formMethod == 'POST' ? 'Storing' : 'Updating') //set the text of the submit btn
                    $('.btnFormLoadingIcon').prop("hidden", false) //show the fa loading icon from submit btn
                },
                success: function (response) {
                    toastr.success(response.message);
                    const data = response.data
                    $('#reportFormModal').modal('hide') //hide the modal

                    addOrReplaceData(data, $('#method').val() == 'POST' ? 'Add' : 'Replace')

                    if ($('#method').val() == 'POST') {
                        // add to pending card if it is new report
                        $("#thisMonthCount").text(parseInt($("#thisMonthCount").text()) + 1);
                        $("#thisMonthPendingCount").text(parseInt($("#thisMonthPendingCount").text()) + 1);
                        $("#reportsCount").text(parseInt($("#reportsCount").text()) + 1);
                    }
                },
                error: function (xhr) {
                    var error = JSON.parse(xhr.responseText);

                    // show error message from helper.js
                    ajaxErrorMessage(error);
                },
                complete: function () {
                    $('.btnFormSubmit').attr("disabled", false); //enable the button
                    $('.btnFormTxt').text('') //set the text of the submit btn
                    $('.btnFormLoadingIcon').prop("hidden", true) //hide the fa loading icon from submit btn
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
            report_option: {
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
            let report_option = $('#report_option').val();
            let status_option = $('#status_option').val();

            var url = window.location.origin + `/admin/missing-items/report/${date_start}/${date_end}/${sort_column}/${sort_option}/${report_option}/${status_option}`;
            window.open(url, '_blank');
        }
    });


    // Delete Modal Form
    $("#modalDeleteForm").submit(function (e) {
        e.preventDefault()
        let ajaxDelURL = $("#modalDeleteForm").attr('action')

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
                $('#btnDelete').attr("disabled", true); //disabled button
                $('.btnDeleteTxt').text('Deleting') //set the text of the submit btn
                $('.btnDeleteLoadingIcon').prop("hidden", false) //show the fa loading icon from delete btn
            },
            success: function (response) {
                toastr.success(response.message);
                var table = $('#dataTable').DataTable();
                $('.selected').fadeOut(800, function () {
                    table.row('.selected').remove().draw();
                });

                // decrement total report count
                $("#reportsCount").text(parseInt($("#reportsCount").text()) - 1);

                let reportDate = new Date(currentRowCreatedAt);
                let currentDate = new Date();

                if (reportDate.getFullYear() == currentDate.getFullYear() && currentDate.getMonth() == reportDate.getMonth()) {
                    //means the date is in this current month

                    // decrement this month total report count
                    $("#thisMonthCount").text(parseInt($("#thisMonthCount").text()) - 1);

                    switch (currentRowStatus) {
                        case 'Pending':
                            $("#thisMonthPendingCount").text(parseInt($("#thisMonthPendingCount").text()) - 1);
                            break;
                        case 'Approved':
                            $("#thisMonthApprovedCount").text(parseInt($("#thisMonthApprovedCount").text()) - 1);
                            break;
                        case 'Resolved':
                            $("#thisMonthResolvedCount").text(parseInt($("#thisMonthResolvedCount").text()) - 1);
                            break;
                        case 'Denied':
                            $("#thisMonthDeniedCount").text(parseInt($("#thisMonthDeniedCount").text()) - 1);
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
                $('#btnDelete').attr("disabled", false); //enable button
                $('.btnDeleteTxt').text('Delete') //set the text of the delete btn
                $('.btnDeleteLoadingIcon').prop("hidden", true) //hide the fa loading icon from submit btn

                $('#confirmationDeleteModal').modal('hide') //hide
                $('#modalDeleteForm').trigger("reset"); //reset all the values
            }
        });
    });
});