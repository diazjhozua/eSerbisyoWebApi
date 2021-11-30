$(document).ready(function () {
    // Create Modal Form Validation
    $("form[name='resetPassForm']").validate({
        rules: {
            email: {
                required: true,
                email: true,
                maxlength: 200,
            },
            password: {
                required: true,
                minlength: 8,
                maxlength: 16,
            },
            password_confirmation: {
                required: true,
                equalTo: '#password',
                minlength: 8,
                maxlength: 16,
            },
        },
        messages: {
            password_confirmation: {
                equalTo: 'your new password does not match',
            },
        },
        submitHandler: function (form, event) {
            event.preventDefault()
            let ajaxURL = $("#resetPassForm").attr('action')
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
                    $('.btnFormSbmit').attr("disabled", true); //disabled login
                    $('.btnTxt').text('Resetting') //set the text of the submit btn
                    $('.loadingIcon').prop("hidden", false) //show the fa loading icon from submit btn
                },
                success: function (response) {
                    toastr.success(response.message)

                    $('#successMessage').prop("hidden", false);
                    $('#successMessage').html(response.html)
                    $('.loadingIcon').prop("hidden", true) //hide the fa loading icon from submit btn
                    $('.btnTxt').text('Password updated') //set the text of the submit btn

                    // disable the button
                    $("#email").prop('disabled', true);
                    $("#password").prop('disabled', true);
                    $("#password_confirmation").prop('disabled', true);
                },
                error: function (xhr) {
                    var error = JSON.parse(xhr.responseText);

                    // show error message from helper.js
                    ajaxErrorMessage(error);
                },
                complete: function () {
                    $('.btnFormSbmit').attr("disabled", false); //disabled login
                    $('.btnTxt').text('Reset') //set the text of the submit btn
                    $('.loadingIcon').prop("hidden", true) //hide the fa loading icon from submit btn
                }
            });


        }

    })
})
