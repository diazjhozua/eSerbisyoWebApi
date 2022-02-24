function editProfile() {
    url = window.location.origin + '/admin/profile/edit'
    doAjax(url, 'GET').then((response) => {
        if (response.success) {
            const data = response.data;
            $('#editUserFormModal').modal('show') //show bootstrap modal
            $('#editUserForm').trigger("reset") //reset all the input values
            $('#imgCurrentPicture').removeAttr('src'); //remoce the src attribute
            $("#imgCurrentPicture").prop("alt", ""); //remove the alt text

            // setting input values in the edit user form by ID
            $('#first_name').val(data.first_name); //set first name
            $('#last_name').val(data.last_name); // set last_name
            $('#middle_name').val(data.middle_name); // set middle_name
            $('#address').val(data.address); // set  address
            // by class name
            $('.custom-file-label').html(data.picture_name); //set current piicture name

            // if there is picture
            if (data.file_path) {
                $('#imgCurrentPicture').prop('src',
                    data.file_path);
            } else {
                $('#imgCurrentPicture').prop('src',
                    'https://pbs.twimg.com/media/D8tCa48VsAA4lxn.jpg');
            }

            $("#imgCurrentPicture").prop("alt", data.first_name + ' ' + data.last_name + ' picture'); //add the alt text

            let actionURL = '/admin/profile/update'
            $('#editUserForm').attr('action', actionURL)
        }
    })
}

function changePassword() {
    url = window.location.origin + '/admin/profile/changePassword'
    $('#changePasswordFormModal').modal('show') //show bootstrap modal
    $('#changePasswordForm').attr('action', url)
}

function changeEmail() {
    url = window.location.origin + '/admin/profile/changeEmail'
    $('#changeEmailFormModal').modal('show') //show bootstrap modal
    $('#changeEmailForm').attr('action', url)
}

$(document).ready(function () {

    $('input[type="file"]').change(function (e) {
        var fileName = e.target.files[0].name;
        $('.custom-file-label').html(fileName);
    });

    // Create Modal Form Validation
    $("form[name='editUserForm']").validate({
        // Specify validation rules
        rules: {
            first_name: {
                required: true,
                minlength: 4,
                maxlength: 150,
            },
            middle_name: {
                required: true,
                minlength: 4,
                maxlength: 150,
            },
            last_name: {
                required: true,
                minlength: 4,
                maxlength: 150,
            },
            address: {
                required: true,
                minlength: 4,
                maxlength: 250,
            },
            picture: {
                required: false,
                extension: "jpeg|jpg|png",
                filesize: 10485760, //10mb in bytes
            },
        },
        messages: {
            picture: {
                extension: "Invalid file type! Document must be in jpeg|jpg|png format",
                filesize: "Selected file must be less than 10mb"
            },
        },

        submitHandler: function (form, event) {
            event.preventDefault()
            let formAction = $("#editUserForm").attr('action')
            let formData = new FormData(form)

            $('#btnEditUserFormSubmit').attr("disabled", true); //disabled login
            $('#btnEditUserTxt').text('Updating') //set the text of the submit btn
            $('#btnEditUserLoading').prop("hidden", false) //show the fa loading icon from submit btn

            doAjax(formAction, 'POST', formData).then((response) => {
                if (response.success != null && response.success == true) {
                    $('#editUserFormModal').modal('hide') //hide the modal

                    const data = response.data
                    $('#userFirstName').text(data.first_name)
                    $('#userMiddleName').text(data.middle_name)
                    $('#userLastName').text(data.last_name)
                    $('#userAddress').text(data.address)
                    $('#userRole').text(data.role)

                    // if there is picture
                    if (data.file_path) {
                        $('#userProfileImg').prop('src',
                            data.file_path);
                    } else {
                        $('#userProfileImg').prop('src',
                            'https://pbs.twimg.com/media/D8tCa48VsAA4lxn.jpg');
                    }
                }

                $('#btnEditUserFormSubmit').attr("disabled", false);
                $('#btnEditUserTxt').text('Update') //set the text of the submit btn
                $('#btnEditUserLoading').prop("hidden", true) //hide the fa loading icon from submit btn
            })
        }
    });

    $("form[name='changePasswordForm']").validate({
        // Specify validation rules
        rules: {
            current_password: {
                required: true,
                minlength: 8,
                maxlength: 16,
            },
            new_password: {
                required: true,
                minlength: 8,
                maxlength: 16,
            },
            new_confirm_password: {
                required: true,
                equalTo: '#new_password',
                minlength: 8,
                maxlength: 16,
            },
        },
        messages: {
            new_confirm_password: {
                equalTo: 'your new password does not match',
            },
        },

        submitHandler: function (form, event) {
            event.preventDefault()
            let formAction = $("#changePasswordForm").attr('action')
            let formData = new FormData(form)

            $('#btnChangePasswordFormSubmit').attr("disabled", true); //disabled login
            $('#btnChangePasswordTxt').text('Updating') //set the text of the submit btn
            $('#btnChangePasswordLoading').prop("hidden", false) //show the fa loading icon from submit btn

            doAjax(formAction, 'POST', formData).then((response) => {
                if (response.success != null && response.success == true) {
                    $('#changePasswordFormModal').modal('hide') //hide the modal
                }
                $('#btnChangePasswordFormSubmit').attr("disabled", false);
                $('#btnChangePasswordTxt').text('Update') //set the text of the submit btn
                $('#btnChangePasswordLoading').prop("hidden", true) //hide the fa loading icon from submit btn
            })
        }
    });

    $("form[name='changeEmailForm']").validate({
        // Specify validation rules
        rules: {
            current_email: {
                required: true,
                email: true,
                maxlength: 30,
            },
            new_email: {
                required: true,
                email: true,
                maxlength: 30,
            },
            new_confirm_email: {
                required: true,
                equalTo: '#new_email',
                email: true,
                maxlength: 30,
            },
        },
        messages: {
            new_confirm_email: {
                equalTo: 'your new email does not match',
            },
        },

        submitHandler: function (form, event) {
            event.preventDefault()
            let formAction = $("#changeEmailForm").attr('action')
            let formData = new FormData(form)

            $('#btnChangeEmailFormSubmit').attr("disabled", true); //disabled login
            $('#btnChangeEmailTxt').text('Updating') //set the text of the submit btn
            $('#btnChangeEmailLoading').prop("hidden", false) //show the fa loading icon from submit btn

            doAjax(formAction, 'POST', formData).then((response) => {
                if (response.success != null && response.success == true) {
                    $('#changeEmailFormModal').modal('hide') //hide the modal
                    $('#userEmail').text(response.email)
                }

                $('#btnChangeEmailFormSubmit').attr("disabled", false);
                $('#btnChangeEmailTxt').text('Update') //set the text of the submit btn
                $('#btnChangeEmailLoading').prop("hidden", true) //hide the fa loading icon from submit btn
            })
        }
    });


})
