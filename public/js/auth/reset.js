$(document).ready(function () {
    // Create Modal Form Validation
    $("form[name='resetPassForm']").validate({
        rules: {
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
                equalTo: 'your new password does not match',
            },
        },
        submitHandler: function (form, event) {
            event.preventDefault()
            let formAction = $("#resetPassForm").attr('action')
            let formData = new FormData(form)

            $('.btnFormSbmit').attr("disabled", true); //disabled login
            $('.btnTxt').text('Resetting') //set the text of the submit btn
            $('.loadingIcon').prop("hidden", false) //show the fa loading icon from submit btn

            doAjax(formAction, 'POST', formData).then((response) => {
                if (response.success != null && response.success == true) {
                    $('#successMessage').prop("hidden", false);
                    $('#successMessage').html(response.html)
                    $('.loadingIcon').prop("hidden", true) //hide the fa loading icon from submit btn
                    $('.btnTxt').text('Password updated') //set the text of the submit btn

                    // disable the button
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
