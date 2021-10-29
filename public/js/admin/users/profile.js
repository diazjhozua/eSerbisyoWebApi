function editProfile() {
    url = window.location.origin + '/admin/users/profile/editProfile'
    doAjax(url, 'GET').then((response) => {
        console.log(response)
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
                    window.location.origin + '/storage/' + data.file_path);
            } else {
                $('#imgCurrentPicture').prop('src',
                    'https://pbs.twimg.com/media/D8tCa48VsAA4lxn.jpg');
            }

            $("#imgCurrentPicture").prop("alt", data.first_name + ' ' + data.last_name + ' picture'); //add the alt text

            let actionURL = '/admin/users/profile/updateProfile'
            $('#editUserForm').attr('action', actionURL)
        }
    })
}

function changePassword() {
    console.log('change password')
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

            $('#btnFormSubmit').attr("disabled", true); //disabled login
            $('.btnTxt').text('Updating') //set the text of the submit btn
            $('.loadingIcon').prop("hidden", false) //show the fa loading icon from submit btn

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
                            window.location.origin + '/storage/' + data.file_path);
                    } else {
                        $('#userProfileImg').prop('src',
                            'https://pbs.twimg.com/media/D8tCa48VsAA4lxn.jpg');
                    }
                }

                $('#btnFormSubmit').attr("disabled", false);
                $('.btnTxt').text('Update') //set the text of the submit btn
                $('.loadingIcon').prop("hidden", true) //hide the fa loading icon from submit btn
            })
        }
    });


})
