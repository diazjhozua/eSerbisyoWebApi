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
            let formAction = $("#registerForm").attr('action')
            let formData = new FormData(form)

            $('.btnFormSbmit').attr("disabled", true); //disabled login
            $('.btnTxt').text('Registering') //set the text of the submit btn
            $('.loadingIcon').prop("hidden", false) //show the fa loading icon from submit btn

            doAjax(formAction, 'POST', formData).then((response) => {
                if (response.success != null && response.success == true) {
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
                } else {
                    $('.btnFormSbmit').attr("disabled", false); //disabled login
                    $('.btnTxt').text('Reset') //set the text of the submit btn
                    $('.loadingIcon').prop("hidden", true) //hide the fa loading icon from submit btn
                }
            })
        }

    })
})
