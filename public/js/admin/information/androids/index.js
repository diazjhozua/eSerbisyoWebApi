function createAndroid() {
    let actionURL = window.location.origin + '/admin/androids/'
    let inputMethod = '<input type="hidden" id="method" name="_method" value="POST">'
    $('#androidFormModal').modal('show') //show the modal
    $('#androidFormModalHeader').text('Create Android Version Type') //set the header of the
    $('.btnTxt').text('Store') //set the text of the submit btn
    $('#typeForm').trigger("reset"); //reset all the values
    $("#formMethod").empty();
    $("#formMethod").append(inputMethod) // append formMethod
    $('#androidForm').attr('action', actionURL) //set the method of the form
}

function populateData(data, formMethod) {

    col0 = '<td>' + data.id + '</td>'
    col1 = '<td>' + data.version + '</td>'
    col2 = '<td>' + data.description + '</td>'
    col3 = '<td><a href="' + data.file_path + '">' + data.file_name + '</a></td>'
    col4 = '<td>' + data.updated_at + '</td>'

    editBtn =
        '<li class="list-inline-item mb-1">' +
        '<button class="btn btn-primary btn-sm" onclick="editAndroid(' + data.id + ')" type="button" data-toggle="tooltip" data-placement="top" title="Edit">' +
        '<i class="fas fa-edit"></i>' +
        '</button>' +
        '</li>'
    deleteBtn =
        '<li class="list-inline-item mb-1">' +
        '<button class="btn btn-danger btn-sm" onclick="deleteAndroid(' + data.id + ')" type="button" data-toggle="tooltip" data-placement="top" title="Delete">' +
        '<i class="fas fa-trash-alt"></i>' +
        '</button>' +
        '</li>'

    col5 = '<td><ul class="list-inline m-0">' + editBtn + deleteBtn + '</td></ul>'

    // Get table reference - note: dataTable() not DataTable()
    var table = $('#dataTable').DataTable();

    if (formMethod == 'POST') {
        var currentPage = table.page();
        table.row.add([col0, col1, col2, col3, col4, col5]).draw()

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
        $("#androidsCount").text(parseInt($("#androidsCount").text()) + 1);

    } else {
        table.row('.selected').data([col0, col1, col2, col3, col4, col5]).draw(false);
    }
}

function editAndroid(id) {

    url = 'androids/' + id + '/edit'
    doAjax(url, 'GET').then((response) => {
        if (response.success) {
            const data = response.data

            $('.custom-file-label').html(''); //empty the html in the file input
            $('#androidForm').trigger("reset") //reset all the input values
            $("#formMethod").empty(); //empty form method div

            //Set form input values

            $('#version').val(data.version)
            $('#description').val(data.description)
            $('.custom-file-label').html(data.file_name)

            let actionURL = window.location.origin + '/admin/androids/' + data.id
            let inputMethod = '<input type="hidden" id="method" name="_method" value="PUT">'

            $("#formMethod").append(inputMethod) // append formMethod div
            $('#androidForm').attr('action', actionURL) //set the method of the form
            $('#androidFormModal').modal('show')
            $('#androidFormModalHeader').text('Edit Android Version')
            $('.btnTxt').text('Update') //set the text of the submit btn
        }
    })
}


function deleteAndroid(id) {
    $('#confirmationDeleteModal').modal('show')
    $('#modalDeleteForm').attr('action', window.location.origin + '/admin/androids/' + id)
    $('#confirmationMessage').text('Do you really want to delete this android version? This process cannot be undone.')
}

$(document).ready(function () {
    $('#android').addClass('active')

    $('input[type="file"]').change(function (e) {
        var fileName = e.target.files[0].name;
        $('.custom-file-label').html(fileName);
    });

    // Set class row selected when any button was click in the selected
    $('#dataTable').on('click', 'tr', function () {
        if (!$(this).hasClass('selected')) {
            $('#dataTable').DataTable().$('tr.selected').removeClass('selected')
            $(this).addClass('selected')
        }
    })

    // Validation for android form
    $("form[name='androidForm']").validate({
        // Specify validation rules
        rules: {
            version: {
                required: true,
                minlength: 6,
                maxlength: 25,
            },
            description: {
                required: true,
                minlength: 6,
                maxlength: 500,
            },
            // apk: {
            //     required: function () {
            //         return $('#method').val() == 'POST'
            //     },
            //     extension: "apk",
            //     filesize: 60485760, //10mb in bytes
            // },
        },
        messages: {
            pdf: {
                extension: "Invalid file type! Apk file must be in .apk format",
                filesize: "Selected file must be less than 60mb"
            },
        },

        submitHandler: function (form, event) {
            event.preventDefault()

            let ajaxURL = $("#androidForm").attr('action')
            let formMethod = $('#method').val()
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
                    $('.btnFormSubmit').attr("disabled", true); //disabled login
                    $('.btnTxt').text(formMethod == 'POST' ? 'Storing' : 'Updating') //set the text of the submit btn
                    $('.loadingIcon').prop("hidden", false) //show the fa loading icon from submit btn
                },
                success: function (response) {
                    toastr.success(response.message);

                    // replace data
                    populateData(response.data, formMethod);

                    $("#androidsCount").text(parseInt($("#androidsCount").text()) + 1);
                },
                error: function (xhr) {
                    var error = JSON.parse(xhr.responseText);

                    // show error message from helper.js
                    ajaxErrorMessage(error);
                },
                complete: function () {
                    $('#androidFormModal').modal('hide') //hide the modal

                    $('.btnFormSubmit').attr("disabled", false); //enable the button
                    $('.btnTxt').text(formMethod == 'POST' ? 'Store' : 'Update') //set the text of the submit btn
                    $('.loadingIcon').prop("hidden", true) //hide the fa loading icon from submit btn
                }
            });
        }
    });

    // Delete Modal Form
    $("#modalDeleteForm").submit(function (e) {
        e.preventDefault()
        let ajaxURL = $("#modalDeleteForm").attr('action')

        $.ajax({
            type: 'DELETE',
            url: ajaxURL,
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

                var table = $('#dataTable').DataTable();
                $('.selected').fadeOut(800, function () {
                    table.row('.selected').remove().draw();
                });

                // decrement typeCount
                $("#androidsCount").text(parseInt($("#androidsCount").text()) - 1);
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




})
