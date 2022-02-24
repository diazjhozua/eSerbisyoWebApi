

function viewRequest(applicationID) {
    ajaxURL = window.location.origin + '/admin/bikers/application-requests/' + applicationID;

    console.log(ajaxURL);

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

            console.log(data);
            $('#reviewRequestModal').modal('show') //show bootstrap modal

            $('#profileHead').text(data.user.first_name + ' ' + data.user.last_name + ' Profile');
            if (data.user.file_path == null) {
                $('#profilePicture').prop('src', "https://pbs.twimg.com/media/D8tCa48VsAA4lxn.jpg"); //add the src attribute
            } else {
                $('#profilePicture').prop('src', data.user.file_path); //add the src attribute
            }

            $("#profilePicture").prop("alt", data.user.first_name + ' ' + data.user.last_name); //add the alt text
            $('#firstName').text(data.user.first_name);
            $('#middleName').text(data.user.middle_name);
            $('#lastName').text(data.user.last_name);
            $('#purok').text(data.user.purok.purok);
            $('#address').text(data.user.address);
            $('#credentials').html('<a href="' + data.credential_file_path + '" target="_blank">' + data.credential_name + '</a></a>')
            $('#bike_type').text(data.bike_type);
            $('#bike_color').text(data.bike_color);
            $('#bike_size').text(data.bike_size);
            $('#reason').text(data.reason);

            $('#verifyRequestForm').trigger("reset")
            let actionURL = window.location.origin + '/admin/bikers/application-requests/' + data.id
            $('#verifyRequestForm').attr('action', actionURL)
        },
        error: function (xhr) {
            var error = JSON.parse(xhr.responseText);

            // show error message from helper.js
            ajaxErrorMessage(error);
        }
    });
}

$(document).ready(function () {
    $('#bikerCollapse').addClass('active');
    $('#collapseBiker').collapse();
    $('#biker_request_nav').addClass('active');


    // Set class row selected when any button was click in the selected
    $('#dataTable').on('click', 'tr', function () {
        if (!$(this).hasClass('selected')) {
            $('#dataTable').DataTable().$('tr.selected').removeClass('selected')
            $(this).addClass('selected')
        }
    })

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
            let ajaxURL = $("#verifyRequestForm").attr('action')
            let formData = new FormData(form)

            $('#btnVerifyRequestFormSubmit').attr("disabled", true); //disabled login
            $('.btnVerifyRequestTxt').text($('#reviewRequestInputSelect').val() == 'Approved' ? 'Approving' : 'Denying') //set the text of the submit btn
            $('.btnVerifyRequestLoadingIcon').prop("hidden", false) //show the fa loading icon from submit btn

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

                    $('#reviewRequestModal').modal('hide') //hide the modal
                    location.reload(true);
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
