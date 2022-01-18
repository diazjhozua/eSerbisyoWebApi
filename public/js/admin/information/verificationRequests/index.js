function viewVerificationRequest(requestID) {
    let ajaxURL = `${window.location.origin}/admin/user-verifications/${requestID}/edit`;

    $.ajax({
        type: 'GET',
        url: ajaxURL,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        },
        cache: false,
        processData: false,
        contentType: false,
        success: function (response) {
            toastr.success(response.message);

            const data = response.data;

            $('#reviewRequestModal').modal('show') //show bootstrap modal
            $('#profileHead').text(data.first_name + ' ' + data.last_name + ' Profile');
            $('#profilePicture').prop('src', window.location.origin + '/storage/' + data.file_path); //add the src attribute
            $("#profilePicture").prop("alt", data.first_name + ' ' + data.last_name + ' picture'); //add the alt text
            $('#firstName').text(data.first_name)
            $('#middleName').text(data.middle_name)
            $('#lastName').text(data.last_name)
            $('#purok').text(data.purok.purok)
            $('#address').text(data.address)
            $('#credentials').html('<a href="' + window.location.origin + '/admin/files/credentials/' + data.credential_name + '" target="_blank">' + data.credential_name + '</a></a>')

            $('#verifyRequestForm').trigger("reset")
            let actionURL = `${window.location.origin}/admin/user-verifications/${data.id}`;
            $('#verifyRequestForm').attr('action', actionURL)
        },
        error: function (xhr) {
            var error = JSON.parse(xhr.responseText);

            // show error message from helper.js
            ajaxErrorMessage(error);
        }
    });

}


function replaceData(data) {
    if (data.file_path) {
        col1 = '<td> <img style="height:50px; max-height: 50px; max-width:50px; width: 50px; border-radius: 50%;" src="' + window.location.origin + '/storage/' + data.file_path + '" class="rounded" alt="' + data.first_name + ' ' + data.last_name + ' image"></td>'
    } else {
        col1 = '<td> <img style="height:50px; max-height: 50px; max-width:50px; width: 50px; border-radius: 50%;" src="https://pbs.twimg.com/media/D8tCa48VsAA4lxn.jpg" class="rounded" alt="' + data.first_name + ' ' + data.last_name + ' image"></td>'
    }

    col2 = '<td>' + data.first_name + ' ' + data.last_name + '</td>'
    col3 = '<td>' + data.email + '</td>'
    col4 = '<td>' + data.role + '</td>'
    col5 = '<td>' + data.admin_message + '</td>'
    col6 = '<td>' + data.status + '</td>'
    col7 = '<td>' + data.created_at + '</td>'
    verifiedBtn =
        '<li class="list-inline-item mb-1">' +
        '<button class="btn btn-info btn-sm" onclick="viewVerificationRequest(' + data.id + ')" type="button" data-toggle="tooltip" data-placement="top" title="Edit" disabled>' +
        '<span class="btnText btnVerify">Review Request</span>' +
        '<i class="btnVerifyIcon fas fa-money-check ml-1"></i>' +
        '<i class="btnVerifyLoadingIcon fa fa-spinner fa-spin" hidden></i>' +
        '</button>' +
        '</li>'

    col8 = '<td><ul class="list-inline m-0">' + verifiedBtn + '</td></ul>'

    // Get table reference - note: dataTable() not DataTable()
    var table = $('#dataTable').DataTable();

    table.row('.selected').data([col1, col2, col3, col4, col5, col6, col7, col8]).draw(true);

}

$(document).ready(function () {

    $('#verificationRequests').addClass('active')

    // Set class row selected when any button was click in the selected
    $('#dataTable').on('click', 'tr', function () {
        if (!$(this).hasClass('selected')) {
            $('#dataTable').DataTable().$('tr.selected').removeClass('selected')
            $(this).addClass('selected')
        }
    });

    $("form[name='verifyRequestForm']").validate({
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
            $('.selected').addClass('verifying');
            let ajaxURL = $("#verifyRequestForm").attr('action')
            let formData = new FormData(form)

            $.ajax({
                type: 'POST',
                url: ajaxURL,
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                cache: false,
                processData: false,
                contentType: false,
                beforeSend: function () {
                    $('#btnVerifyRequestFormSubmit').attr("disabled", true); //disabled login
                    $('.btnVerifyRequestTxt').text($('#reviewRequestInputSelect').val() == 'Approved' ? 'Approving' : 'Denying') //set the text of the submit btn
                    $('.btnVerifyRequestLoadingIcon').prop("hidden", false) //show the fa loading icon from submit btn
                },
                success: function (response) {
                    toastr.success(response.message);

                    let data = response.data
                    $('#reviewRequestModal').modal('hide') //hide the modal
                    replaceData(data);
                },
                error: function (xhr) {
                    var error = JSON.parse(xhr.responseText);

                    // show error message from helper.js
                    ajaxErrorMessage(error);
                },
                complete: function () {

                    $('#changeStatusModal').modal('hide') //hide the modal

                    $('#btnVerifyRequestFormSubmit').attr("disabled", false); //enable the button
                    $('.btnVerifyRequestTxt').text('Submit') //set the text of the submit btn
                    $('.btnVerifyRequestLoadingIcon').prop("hidden", true) //hide the fa loading icon from submit btn
                }
            });
        }
    });

});

