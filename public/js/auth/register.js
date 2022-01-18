$(document).ready(function () {
    // Create Modal Form Validation
    $("form[name='registerForm']").validate({
        rules: {
            first_name: {
                required: true,
                minlength: 4,
                maxlength: 150,
            },
            middle_name: {
                required: false,
                minlength: 4,
                maxlength: 150,
            },
            last_name: {
                required: true,
                minlength: 4,
                maxlength: 150,
            },
            purok_id: {
                required: true,
            },
            email: {
                required: true,
                email: true,
                maxlength: 30,
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
                equalTo: 'your password does not match',
            },
        },
        submitHandler: function (form, event) {
            event.preventDefault()
            let ajaxURL = $("#registerForm").attr('action')
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
                    $('.btnTxt').text('Registering') //set the text of the submit btn
                    $('.loadingIcon').prop("hidden", false) //show the fa loading icon from submit btn
                },
                success: function (response) {
                    toastr.success(response.message)

                    $('#successMessage').html(response.message)
                    $('.loadingIcon').prop("hidden", true) //hide the fa loading icon from submit btn
                    $('.btnTxt').text('Registration completed') //set the text of the submit btn

                    // disable the button
                    $("#first_name").prop('disabled', true);
                    $("#middle_name").prop('disabled', true);
                    $("#last_name").prop('disabled', true);
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
                    $('.btnTxt').text('Register') //set the text of the submit btn
                    $('.loadingIcon').prop("hidden", true) //hide the fa loading icon from submit btn
                }
            });

        }

    })
})
