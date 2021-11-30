$(document).ready(function () {
    // Create Modal Form Validation
    $("form[name='loginForm']").validate({
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
        },
        submitHandler: function (form, event) {
            event.preventDefault()
            let ajaxURL = $("#loginForm").attr('action')
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
                    $('.btnTxt').text('Logging in') //set the text of the submit btn
                    $('.loadingIcon').prop("hidden", false) //show the fa loading icon from submit btn
                },
                success: function (response) {
                    toastr.success(response.message)
                    window.setTimeout(function () {
                        window.location.replace(response.route);
                    }, 1000);
                },
                error: function (xhr) {
                    var error = JSON.parse(xhr.responseText);

                    // show error message from helper.js
                    ajaxErrorMessage(error);
                },
                complete: function () {
                    $('.btnFormSbmit').attr("disabled", false);
                    $('.btnTxt').text('Log in') //set the text of the submit btn
                    $('.loadingIcon').prop("hidden", true) //hide the fa loading icon from submit btn
                }
            });


        }

    })
})
