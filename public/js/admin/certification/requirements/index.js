
const NAV_REQUIREMENT = $('#requirement'); //nav for requirement
const DATA_TABLE = $('#dataTable');
const MODAL_REQ = $('#requirementFormModal');
const MODAL_HEADER_REQ = $('#requirementFormModalHeader');
const FORM_REQ = $('#requirementForm');
const FORM_REQ_METHOD = $('#formMethod');
const BTN_FORM_REQ = $('#btnFormSubmit');
const BTN_TXT_FORM_REQ = $('.btnTxt');
const BTN_LOADING_ICON_REQ = $('.loadingIcon');

function createRequirement() {
    let actionURL = window.location.origin + '/admin/requirements';
    let inputMethod = '<input type="hidden" id="method" name="_method" value="POST">';

    MODAL_REQ.modal('show');  //show the modal
    MODAL_HEADER_REQ.text('Create requirement'); //set the header of the modal
    BTN_TXT_FORM_REQ.text('Store');  //set the text of the submit btn
    FORM_REQ.trigger("reset"); //reset all the values
    FORM_REQ.attr('action', actionURL); //set the method of the form
    FORM_REQ_METHOD.empty();
    FORM_REQ_METHOD.append(inputMethod); // append formMethod
}

function editRequirement(id) {
    url = window.location.origin + '/admin/requirements/' + id + '/edit'

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
            toastr.success(response.message);
            toastr.info('The fields can now be edited');
            const data = response.data;
            const inputMethod = '<input type="hidden" id="method" name="_method" value="PUT">'
            const actionURL = window.location.origin + '/admin/requirements/' + data.id
            MODAL_REQ.modal('show');  //show the modal
            MODAL_HEADER_REQ.text('Edit requirement'); //set the header of the modal
            BTN_TXT_FORM_REQ.text('Update');  //set the text of the submit btn
            FORM_REQ.trigger("reset"); //reset all the values
            FORM_REQ.attr('action', actionURL); //set the method of the form
            FORM_REQ_METHOD.empty();
            FORM_REQ_METHOD.append(inputMethod); // append formMethod

            $('#requirementName').val(data.name); // set the text of the input
        },
        error: function (xhr, status, error) {
            var error = JSON.parse(xhr.responseText);

            // show error message from helper.js
            ajaxErrorMessage(error);
        }
    });

}

function deleteRequirement(id) {
    $('#confirmationDeleteModal').modal('show');
    $('#modalDeleteForm').attr('action', window.location.origin + '/admin/requirements/' + id);
    $('#confirmationMessage').text('Do you really want to delete this requirement? This process cannot be undone. All of the certificates requirements related to this requirement would be deleted also.');
}


$(document).ready(function () {
    NAV_REQUIREMENT.addClass('active');

    // Set class row selected when any button was click in the selected
    DATA_TABLE.on('click', 'tr', function () {
        if (!$(this).hasClass('selected')) {
            DATA_TABLE.DataTable().$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
    })


    // Create Modal Form Validation
    FORM_REQ.validate({
        // Specify validation rules
        rules: {
            name: {
                required: true,
                minlength: 6,
                maxlength: 200,
            },
        },

        submitHandler: function (form, event) {
            event.preventDefault()
            let formAction = $("#requirementForm").attr('action')
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
                    $('#btnFormSubmit').attr("disabled", true); //disabled login
                    $('.btnTxt').text(formMethod == 'POST' ? 'Storing' : 'Updating') //set the text of the submit btn
                    $('.loadingIcon').prop("hidden", false) //show the fa loading icon from submit btn
                },
                success: function (response) {
                    toastr.success(response.message);
                    MODAL_REQ.modal('hide') //hide the modal

                    const data = response.data

                    col0 = '<td>' + data.id + '</td>'
                    col1 = '<td>' + data.name + '</td>'
                    col2 = '<td>' + data.certificates_count + '</td>'
                    col3 = '<td>' + data.updated_at + '</td>'

                    editBtn =
                        '<li class="list-inline-item mb-1">' +
                        '<button class="btn btn-primary btn-sm" onclick="editRequirement(' + data.id + ')" type="button" data-toggle="tooltip" data-placement="top" title="Edit">' +
                        '<i class="fas fa-edit"></i>' +
                        '</button>' +
                        '</li>';
                    deleteBtn =
                        '<li class="list-inline-item mb-1">' +
                        '<button class="btn btn-danger btn-sm" onclick="deleteRequirement(' + data.id + ')" type="button" data-toggle="tooltip" data-placement="top" title="Delete">' +
                        '<i class="fas fa-trash-alt"></i>' +
                        '</button>' +
                        '</li>';

                    col4 = '<td><ul class="list-inline m-0">' + editBtn + deleteBtn + '</td></ul>';

                    // Get table reference - note: dataTable() not DataTable()
                    var table = $('#dataTable').DataTable();

                    if (formMethod == 'POST') {
                        var currentPage = table.page();
                        table.row.add([col0, col1, col2, col3, col4]).draw()

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

                        // increment typeCount
                        $("#requirementsCount").text(parseInt($("#requirementsCount").text()) + 1);

                    } else {
                        table.row('.selected').data([col0, col1, col2, col3, col4]).draw(false);
                    }
                },
                error: function (xhr) {
                    var error = JSON.parse(xhr.responseText);

                    // show error message from helper.js
                    ajaxErrorMessage(error);
                },
                complete: function () {
                    $('#btnFormSubmit').attr("disabled", false);
                    $('.btnTxt').text(formMethod == 'POST' ? 'Store' : 'Update') //set the text of the submit btn
                    $('.loadingIcon').prop("hidden", true) //hide the fa loading icon from submit btn
                }
            });
        }
    });

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

                var table = $('#dataTable').DataTable();
                $('.selected').fadeOut(800, function () {
                    table.row('.selected').remove().draw();
                });

                // decrement termCount
                $("#requirementsCount").text(parseInt($("#requirementsCount").text()) - 1);
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
