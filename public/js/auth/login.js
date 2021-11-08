$(document).ready(function () {
    // Create Modal Form Validation
    $("form[name='loginForm']").validate({
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
        },
        submitHandler: function (form, event) {
            event.preventDefault()
            let formAction = $("#loginForm").attr('action')
            let formData = new FormData(form)

            $('.btnFormSbmit').attr("disabled", true); //disabled login
            $('.btnTxt').text('Logging in') //set the text of the submit btn
            $('.loadingIcon').prop("hidden", false) //show the fa loading icon from submit btn

            doAjax(formAction, 'POST', formData).then((response) => {
                if (response.success != null && response.success == true) {
                    window.setTimeout(function () {
                        window.location.replace(response.route);
                    }, 1000);
                }
                $('.btnFormSbmit').attr("disabled", false);
                $('.btnTxt').text('Log in') //set the text of the submit btn
                $('.loadingIcon').prop("hidden", true) //hide the fa loading icon from submit btn
            })
        }

    })
})
