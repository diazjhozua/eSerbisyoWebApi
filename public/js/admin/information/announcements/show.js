function editAnnouncement(id) {
    const url = window.location.origin + '/admin/announcements/' + id + '/edit'

    $.ajax({
        type: 'GET',
        url: url,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        },
        cache: false,
        processData: false,
        contentType: false,
        success: function (response) {
            const data = response.data
            const types = response.types
            $('#announcementForm').trigger("reset") //reset all the input values
            $('#announcementTypeDropDwn').empty() //reset dropdown button
            $("#formMethod").empty(); //empty form method div
            $('#announcementTypeDropDwn').append($("<option selected/>").val(data.type_id).text(data.type_id == 0 ? data.custom_type : data.announcement_type))
            $.each(types, function () {
                // Populate Drop Dowm
                $('#announcementTypeDropDwn').append($("<option />").val(this.id).text(this.name))
            })

            $('#title').val(data.title)
            $('#description').val(data.description)

            let actionURL = window.location.origin + '/admin/announcements/' + id
            let inputMethod = '<input type="hidden" id="method" name="_method" value="PUT">'

            $("#formMethod").append(inputMethod) // append formMethod div
            $('#announcementForm').attr('action', actionURL) //set the method of the form
            $('#announcementModal').modal('show')
            $('#announcementModalHeader').text('Edit Announcement')
            $('.btnTxt').text('Update') //set the text of the submit btn
        },
        error: function (xhr) {
            var error = JSON.parse(xhr.responseText);

            // show error message from helper.js
            ajaxErrorMessage(error);
        }
    });
}

var isDeletingAnnouncement
function deleteAnnouncement(announcementId) {
    isDeletingAnnouncement = true
    $('#confirmationDeleteModal').modal('show')
    $('#modalDeleteForm').attr('action', window.location.origin + '/admin/announcements/' + announcementId)
    $('#confirmationMessage').text('Do you really want to delete this announcements? This process cannot be undone.')
}

function addPicture(announcement_id) {
    let actionURL = window.location.origin + '/admin/announcement-pictures/'
    let inputMethod = '<input type="hidden" id="pictureMethod" name="_method" value="POST">'
    $('#announcementPictureModal').modal('show') //show the modal
    $('#announcementPictureModalHeader').text('Add Picture') //set the header of the

    $("#currentPictureDiv").hide(); // hide the current picture div
    $('#imgCurrentPicture').removeAttr('src'); //remoce the src attribute
    $("#imgCurrentPicture").prop("alt", ""); //remove the alt text

    $('.btnTxt').text('Store') //set the text of the submit btn
    $('#announcementPictureForm').trigger("reset"); //reset all the values

    $("#formPictureMethod").empty();
    $("#formPictureMethod").append(inputMethod) // append formMethod

    $('#announcementPictureForm').attr('action', actionURL) //set the method of the form
    $('#announcement_id').val(announcement_id)
    $('.custom-file-label').html(''); //empty the html in the file input
}

function editPicture(announcementPicture_id) {
    url = window.location.origin + '/admin/announcement-pictures/' + announcementPicture_id + '/edit';
    $.ajax({
        type: 'GET',
        url: url,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        },
        cache: false,
        processData: false,
        contentType: false,
        success: function (response) {
            const data = response.data

            let actionURL = window.location.origin + '/admin/announcement-pictures/' + data.id
            let inputMethod = '<input type="hidden" id="pictureMethod" name="_method" value="PUT">'
            $('#announcementPictureModal').modal('show') //show the modal
            $('#announcementPictureModalHeader').text('Edit Picture') //set the header of the
            $('.btnTxt').text('Update') //set the text of the submit btn
            $('#announcementPictureForm').trigger("reset"); //reset all the values
            $("#formPictureMethod").empty();
            $("#formPictureMethod").append(inputMethod) // append formMethod

            $('#announcement_id').val(data.announcement_id)
            $('.custom-file-label').html(data.picture_name)
            $("#currentPictureDiv").show(); // show the current picture div
            $('#imgCurrentPicture').prop('src', data.file_path); //add the src attribute
            $("#imgCurrentPicture").prop("alt", data.name + ' picture'); //add the alt text
            $('#announcementPictureForm').attr('action', actionURL) //set the method of the form
        },
        error: function (xhr) {
            var error = JSON.parse(xhr.responseText);

            // show error message from helper.js
            ajaxErrorMessage(error);
        }
    });
}

function deletePicture(announcementPictureId) {
    isDeletingAnnouncement = false
    $('#confirmationDeleteModal').modal('show')
    $('#modalDeleteForm').attr('action', window.location.origin + '/admin/announcement-pictures/' + announcementPictureId)
    $('#confirmationMessage').text('Do you really want to delete this announcement picture? This process cannot be undone.')
}

$(document).ready(function () {
    $('#dataTablePicture').DataTable({
        scrollY: "200px",
        scrollX: true,
        scrollCollapse: true,
        paging: true,
    });

    $('input[type="file"]').change(function (e) {
        var fileName = e.target.files[0].name;
        $('.custom-file-label').html(fileName);
    });

    // Set class row selected when any button was click in the selected
    $('#dataTablePicture').on('click', 'tr', function () {
        if (!$(this).hasClass('selected')) {
            $('#dataTablePicture').DataTable().$('tr.selected').removeClass('selected')
            $(this).addClass('selected')
        }
    })

    $('#dataTableLike').dataTable({
        "aaSorting": []
        // Your other options here...
    });

    $('#dataTableComment').dataTable({
        "aaSorting": []
        // Your other options here...
    });

    $('#announcement').addClass('active')

    // Remove file upload div in form blade
    $('#fileUploadContainer').empty()

    // Validation
    $("form[name='announcementForm']").validate({
        // Specify validation rules
        rules: {
            type_id: {
                required: true,
                minStrict: 0,
                number: true
            },
            title: {
                required: true,
                minlength: 4,
                maxlength: 250,
            },
            description: {
                required: true,
                minlength: 10,
                maxlength: 63206,
            },
        },
        messages: {
            type_id: {
                required: 'Please select one of the announcement types',
                digits: 'Invalid Input',
                minStrict: 'Please select one of the announcement types',
            },
        },

        submitHandler: function (form, event) {
            event.preventDefault()
            let formAction = $("#announcementForm").attr('action')
            let formMethod = $('#method').val()
            let formData = new FormData(form)

            $.ajax({
                type: 'POST',
                url: formAction,
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                cache: false,
                processData: false,
                contentType: false,
                beforeSend: function () {
                    $('.btnFormSubmit').attr("disabled", true); //disabled login
                    $('.btnTxt').text('Updating') //set the text of the submit btn
                    $('.loadingIcon').prop("hidden", false) //show the fa loading icon from submit btn
                },
                success: function (response) {
                    toastr.success(response.message);
                    $('#announcementModal').modal('hide') //hide the modal

                    const data = response.data

                    $('#announcementTitle').text(data.title)
                    $('#announcementTypes').text(data.announcement_type)
                    $('#announcementDescription').text(data.description)
                    $('#announcementUpdatedAt').text(data.updated_at)
                },
                error: function (xhr) {
                    var error = JSON.parse(xhr.responseText);

                    // show error message from helper.js
                    ajaxErrorMessage(error);
                },
                complete: function () {
                    $('.btnFormSubmit').attr("disabled", false);
                    $('.btnTxt').text(formMethod == 'POST' ? 'Store' : 'Update') //set the text of the submit btn
                    $('.loadingIcon').prop("hidden", true) //hide the fa loading icon from submit btn
                }
            });
        }
    })

    $("form[name='announcementPictureForm']").validate({
        // Specify validation rules
        rules: {
            picture: {
                required: function () {
                    return $('#formPictureMethod').val() == 'POST'
                },
                extension: "jpeg|jpg|png",
                filesize: 10485760, //10mb in bytes
            },
        },
        messages: {
            picture: {
                extension: "Invalid file type! Picture must be in jpeg|jpg|png format",
                filesize: "Selected file must be less than 10mb"
            },
        },

        submitHandler: function (form, event) {
            event.preventDefault()
            $.ajax({
                type: 'POST',
                url: formAction,
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                cache: false,
                processData: false,
                contentType: false,
                beforeSend: function () {
                    $('#btnFormSubmit').attr("disabled", true); //disabled login
                    $('.btnTxt').text(formMethod == 'POST' ? 'Storing' : 'Updating') //set the text of the submit btn
                    $('.loadingIcon').prop("hidden", false) //show the fa loading icon from submit btn
                },
                success: function (response) {
                    toastr.success(response.message);
                    $('#announcementPictureModal').modal('hide') //hide the modal

                    const data = response.data

                    col0 = '<td>' + data.id + '</td>'
                    col1 = '   <td><img style="height:100px; max-height: 100px; max-width:100px; width: 100px;" src="' + data.file_path + '" class="rounded" alt="' + data.picture_name + ' image"></td>'

                    viewBtn =
                        '<li class="list-inline-item mb-1">' +
                        '<a class="btn btn-info btn-sm" type="button" data-toggle="tooltip" data-placement="top" title="View" href="' + data.file_path + '" target="_blank"><i class="fas fa-eye"></i>' +
                        '</a></li>'

                    editBtn =
                        '<li class="list-inline-item mb-1">' +
                        '<button class="btn btn-primary btn-sm" onclick="editPicture(' + data.id + ')" type="button" data-toggle="tooltip" data-placement="top" title="Edit">' +
                        '<i class="fas fa-edit"></i>' +
                        '</button>' +
                        '</li>'

                    deleteBtn =
                        '<li class="list-inline-item mb-1">' +
                        '<button class="btn btn-danger btn-sm" onclick="deletePicture(' + data.id + ')" type="button" data-toggle="tooltip" data-placement="top" title="Delete">' +
                        '<i class="fas fa-trash-alt"></i>' +
                        '</button>' +
                        '</li>'

                    col2 = '<td><ul class="list-inline m-0">' + viewBtn + editBtn + deleteBtn + '</td></ul>'

                    // Get table reference - note: dataTable() not DataTable()
                    var table = $('#dataTablePicture').DataTable();

                    if (formMethod == 'POST') {
                        var currentPage = table.page();
                        table.row.add([col0, col1, col2]).draw()

                        selectedRow = 0
                        var index = table.row(selectedRow).index(),
                            rowCount = table.data().length - 1,
                            insertedRow = table.row(rowCount).data(),
                            tempRow

                        for (var i = rowCount; i > index; i--) {
                            tempRow = table.row(i - 1).data()
                            table.row(i).data(tempRow)
                            table.row(i - 1).data(insertedRow)
                        }

                        //refresh the page
                        table.page(currentPage).draw(false)

                        // increment employeeCount
                        $("#announcementPicturesCount").text(parseInt($("#announcementPicturesCount").text()) + 1);

                    } else {
                        table.row('.selected').data([col0, col1, col2]).draw(false);
                    }
                },
                error: function (xhr) {
                    var error = JSON.parse(xhr.responseText);

                    // show error message from helper.js
                    ajaxErrorMessage(error);
                },
                complete: function () {
                    $('#btnFormSubmit').attr("disabled", false); //disabled login
                    $('.btnTxt').text(formMethod == 'POST' ? 'Store' : 'Update') //set the text of the submit btn
                    $('.loadingIcon').prop("hidden", true) //show the fa loading icon from submit btn
                }
            });
        }
    })


    // Delete Modal Form
    $("#modalDeleteForm").submit(function (e) {
        e.preventDefault()
        let ajaxDelURL = $("#modalDeleteForm").attr('action')

        $.ajax({
            type: 'DELETE',
            url: ajaxDelURL,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
            cache: false,
            processData: false,
            contentType: false,
            beforeSend: function () {
                $('#btnDelete').attr("disabled", true); //disabled button
                $('.btnDeleteTxt').text('Deleting') //set the text of the submit btn
                $('.btnDeleteLoadingIcon').prop("hidden", false) //show the fa loading icon from delete btn
            },
            success: function (response) {
                toastr.success(response.message);

                if (isDeletingAnnouncement) {
                    window.setTimeout(function () {
                        window.location.replace(window.location.origin + '/admin/announcements');
                    }, 2000);
                } else {
                    var table = $('#dataTable').DataTable();
                    $('.selected').fadeOut(800, function () {
                        table.row('.selected').remove().draw();
                    });
                    // decrement typeCount
                    $("#announcementPicturesCount").text(parseInt($("#announcementPicturesCount").text()) - 1);
                }
            },
            error: function (xhr) {
                var error = JSON.parse(xhr.responseText);

                // show error message from helper.js
                ajaxErrorMessage(error);
            },
            complete: function () {
                $('#btnDelete').attr("disabled", false); //enable button
                $('.btnDeleteTxt').text('Delete') //set the text of the delete btn
                $('.btnDeleteLoadingIcon').prop("hidden", true) //hide the fa loading icon from submit btn

                $('#confirmationDeleteModal').modal('hide') //hide
                $('#modalDeleteForm').trigger("reset"); //reset all the values
            }
        });
    })
});
