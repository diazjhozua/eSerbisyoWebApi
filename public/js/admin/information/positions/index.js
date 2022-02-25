// function to show
function showFormModal(ajaxURL, method, header, btnTxt) {
    $('#positionFormModal').modal('show'); //show the modal
    $('#positionFormModalHeader').text(header); //set the header of the
    $('.btnTxt').text(btnTxt); //set the text of the submit btn
    $('#positionForm').trigger("reset"); //reset all the values
    $("#formMethod").empty();
    $("#formMethod").append(method); // append formMethod
    $('#positionForm').attr('action', ajaxURL); //set the method of the form
}

function createPosition() {
    const ajaxCreateURL = window.location.origin + '/admin/positions/';
    const method = '<input type="hidden" id="method" name="_method" value="POST">';

    showFormModal(ajaxCreateURL, method, 'Create Position', 'Store');
}

function editPosition(id) {
    const ajaxEditURL = window.location.origin + 'positions/' + id + '/edit'

    $.ajax({
        type: 'GET',
        url: ajaxEditURL,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        },
        cache: false,
        processData: false,
        contentType: false,
        success: function (response) {
            toastr.success(response.message);

            const data = response.data;
            const inputMethod = '<input type="hidden" id="method" name="_method" value="PUT">';
            const ajaxUpdateURL = window.location.origin + '/admin/positions/' + data.id;

            showFormModal(ajaxUpdateURL, inputMethod, 'Edit Position', 'Update');

            // set the input values
            $('#ranking').val(data.ranking)
            $('#name').val(data.name)
            $('#job_description').val(data.job_description)
        },
        error: function (xhr) {
            var error = JSON.parse(xhr.responseText);

            // show error message from helper.js
            ajaxErrorMessage(error);
        }
    });
}

function deletePosition(id) {
    $('#confirmationDeleteModal').modal('show')
    $('#modalDeleteForm').attr('action', window.location.origin + '/admin/positions/' + id)
    $('#confirmationMessage').text('Do you really want to delete this position? This process cannot be undone. All of the employees related to this position would be transfer to "Others(Employees without assigned position)"')
}

$(document).ready(function () {
    // Set class row selected when any button was click in the selected
    $('#dataTable').on('click', 'tr', function () {
        if (!$(this).hasClass('selected')) {
            $('#dataTable').DataTable().$('tr.selected').removeClass('selected')
            $(this).addClass('selected')
        }
    })

    // Toggle Collapse Bar in SideBar Pane
    $('#OfficialNavCollapse').addClass('active')
    $('#collapseOfficial').collapse()
    $('#position').addClass('active')

    // Create Modal Form Validation
    $("form[name='positionForm']").validate({
        // Specify validation rules
        rules: {
            ranking: {
                required: true,
                minStrict: 0,
                number: true
            },
            name: {
                required: true,
                minlength: 6,
                maxlength: 200,
            },
            job_description: {
                required: true,
                minlength: 6,
                maxlength: 250,
            },
        },

        submitHandler: function (form, event) {
            event.preventDefault()
            const ajaxURL = $("#positionForm").attr('action')
            const formMethod = $('#method').val()
            const formData = new FormData(form)

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
                    $('#btnFormSubmit').attr("disabled", true); //disabled login
                    $('.btnTxt').text(formMethod == 'POST' ? 'Storing' : 'Updating') //set the text of the submit btn
                    $('.loadingIcon').prop("hidden", false) //show the fa loading icon from submit btn
                },
                success: function (response) {
                    toastr.success(response.message);

                    const data = response.data;


                    col0 = '<td>' + data.id + '</td>'
                    col1 = '<td>' + data.name + '</td>'
                    col2 = '<td>' + data.ranking + '</td>'
                    col3 = '<td>' + data.job_description + '</td>'
                    col4 = '<td>' + data.employees_count + '</td>'
                    col5 = '<td>' + data.updated_at + '</td>'

                    viewBtn =
                        '<li class="list-inline-item mb-1">' +
                        '<a class="btn btn-info btn-sm" type="button" data-toggle="tooltip" data-placement="top" title="View" href="' + window.location.origin + '/admin/employees/' + data.id + '"><i class="fas fa-eye"></i>' +
                        '</a></li>'
                    editBtn =
                        '<li class="list-inline-item mb-1">' +
                        '<button class="btn btn-primary btn-sm" onclick="editPosition(' + data.id + ')" type="button" data-toggle="tooltip" data-placement="top" title="Edit">' +
                        '<i class="fas fa-edit"></i>' +
                        '</button>' +
                        '</li>'
                    deleteBtn =
                        '<li class="list-inline-item mb-1">' +
                        '<button class="btn btn-danger btn-sm" onclick="deletePosition(' + data.id + ')" type="button" data-toggle="tooltip" data-placement="top" title="Delete">' +
                        '<i class="fas fa-trash-alt"></i>' +
                        '</button>' +
                        '</li>'

                    col6 = '<td><ul class="list-inline m-0">' + viewBtn + editBtn + deleteBtn + '</td></ul>'
                    // Get table reference - note: dataTable() not DataTable()
                    var table = $('#dataTable').DataTable();

                    if (formMethod == 'POST') {
                        var currentPage = table.page();
                        table.row.add([col0, col1, col2, col3, col4, col5, col6]).draw()

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

                        // increment termCount
                        $("#positionCount").text(parseInt($("#positionCount").text()) + 1);

                    } else {
                        table.row('.selected').data([col0, col1, col2, col3, col4, col5, col6]).draw(false);
                    }
                },
                error: function (xhr) {
                    var error = JSON.parse(xhr.responseText);

                    // show error message from helper.js
                    ajaxErrorMessage(error);
                },
                complete: function () {
                    $('#positionFormModal').modal('hide') //hide the modal

                    $('#btnFormSubmit').attr("disabled", false);
                    $('.btnTxt').text(formMethod == 'POST' ? 'Store' : 'Update') //set the text of the submit btn
                    $('.loadingIcon').prop("hidden", true) //hide the fa loading icon from submit btn
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

                var table = $('#dataTable').DataTable();
                $('.selected').fadeOut(800, function () {
                    table.row('.selected').remove().draw();
                });

                // decrement termCount
                $("#positionCount").text(parseInt($("#positionCount").text()) - 1);
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

