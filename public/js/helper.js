toastr.options = {
    "closeButton": false,
    "debug": false,
    "newestOnTop": false,
    "progressBar": false,
    "positionClass": "toast-top-right",
    "preventDuplicates": false,
    "onclick": null,
    "showDuration": "300",
    "hideDuration": "1000",
    "timeOut": "3000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
}


function ajaxErrorMessage(error) {
    if (error.message != null) {
        toastr.error(error.message)
    } else {
        $.each(error.errors, function (key, value) {
            toastr.error(value)
        });
    }
}

const doAjax = async function (url, method, body) {
    let result;

    try {
        result = await $.ajax({
            url: url,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
            type: method,
            data: body,
            cache: false,
            processData: false,
            contentType: false,
            success: function (response) {
                toastr.success(response.message)
                return response;
            },
            error: function (xhr, status, error) {
                var err = JSON.parse(xhr.responseText);
                alert(err);

                if (err.message != null) {
                    toastr.error(result.message)

                } else {
                    $.each(err.errors, function (key, value) {
                        toastr.error(value)
                    });
                }

                return false;
            },
            complete: function () {
                btnReportFormSubmit.attr("disabled", false);
                btnReportFormTxt.text('Submit');
                btnReportFormLoadingIcon.prop("hidden", true);
            }

        });

        if (result.success) {
            toastr.success(result.message)
        } else {
            if (result.message != null) {
                toastr.error(result.message)

            } else {
                $.each(result.errors, function (key, value) {
                    toastr.error(value)
                });
            }
        }
        return result;
    } catch (error) {
        toastr.error('Something went wrong :(')
    }
}


jQuery.validator.addMethod("extension", function (value, element, param) {
    param = typeof param === "string" ? param.replace(/,/g, '|') : "png|jpe?g|gif";
    return this.optional(element) || value.match(new RegExp(".(" + param + ")$", "i"));
}, "Please enter a value with a valid extension.");

jQuery.validator.addMethod('minStrict', function (value, el, param) {
    return value > param;
}, "");


jQuery.validator.addMethod('filesize', function (value, element, param) {
    return this.optional(element) || (element.files[0].size <= param)
}, 'File size must be less than {0}');

$.validator.methods.email = function (value, element) {
    return this.optional(element) || /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/.test(value);
}


jQuery.validator.addMethod("greaterThan",
    function (value, element, params) {

        if (!/Invalid|NaN/.test(new Date(value))) {
            return new Date(value) > new Date($(params).val());
        }

        return isNaN(value) && isNaN($(params).val())
            || (Number(value) > Number($(params).val()));
    }, 'Must be greater than {0}.');







