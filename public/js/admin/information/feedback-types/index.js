function createType() {
    let actionURL = window.location.origin + '/admin/feedback-types'
    let inputMethod = '<input type="hidden" id="method" name="_method" value="POST">'
    $('#typeFormModal').modal('show') //show the modal
    $('#typeFormModalHeader').text('Create Feedback Type') //set the header of the
    $('#nameLabel').text('Feedback Type Name') //set the text of type name in the form
    $('.btnTxt').text('Store') //set the text of the submit btn
    $('#typeForm').trigger("reset"); //reset all the values
    $("#formMethod").empty();
    $("#formMethod").append(inputMethod) // append formMethod
    $('#typeForm').attr('action', actionURL) //set the method of the form
}

function editType(id) {
    url = window.location.origin + '/admin/feedback-types/' + id + '/edit'

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

            const data = response.data;
            const inputMethod = '<input type="hidden" id="method" name="_method" value="PUT">'
            const actionURL = window.location.origin + '/admin/feedback-types/' + data.id
            $('#typeFormModal').modal('show') //show the modal
            $('#typeFormModalHeader').text('Edit Feedback Type') //set the header of the
            $('#nameLabel').text('Feedback Type Name') //set the text of type name in the form
            $('#typeForm').trigger("reset"); //reset all the values
            $('#typeName').val(data.name) // set the text of the input
            $('.btnTxt').text('Update') //set the text of the submit btn
            $("#formMethod").empty();
            $("#formMethod").append(inputMethod) // append formMethod
            $('#typeForm').attr('action', actionURL) //set action

        },
        error: function (xhr) {
            var error = JSON.parse(xhr.responseText);

            // show error message from helper.js
            ajaxErrorMessage(error);
        }
    });
}

function deleteType(id) {
    $('#confirmationDeleteModal').modal('show')
    $('#modalDeleteForm').attr('action', window.location.origin + '/admin/feedback-types/' + id)
    $('#confirmationMessage').text('Do you really want to delete this feedback-type? This process cannot be undone. All of the feedbacks related to this type would be transfer to "Others"')
}

$(document).ready(function () {
    // https://raw.githack.com/peet86/Ratyli/master/jquery.ratyli.min.js
    // Configure with Datasets

    // Set class row selected when any button was click in the selected
    $('#dataTable').on('click', 'tr', function () {
        if (!$(this).hasClass('selected')) {
            $('#dataTable').DataTable().$('tr.selected').removeClass('selected')
            $(this).addClass('selected')
        }
    })

    // Initialize Year picker in report form
    $(".datepicker").datepicker({
        format: "yyyy-mm-dd",
    });



    // Toggle Collapse Bar in SideBar Pane
    $('#TypeNavCollapse').addClass('active')
    $('#collapseType').collapse()
    $('#feedbackType').addClass('active')

    // Create Modal Form Validation
    $("form[name='typeForm']").validate({
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
            let formAction = $("#typeForm").attr('action')
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

                    const data = response.data
                    $('#typeFormModal').modal('hide') //hide the modal

                    col0 = '<td>' + data.id + '</td>'
                    col1 = '<td>' + data.name + '</td>'
                    col2 = '<td>' + data.feedbacks_count + '</td>'

                    if (data.feedbacks_count > 0) {
                        col3 = `<td class="text-center"> <div class="Stars" style="--rating: ${Number(data.ratings).toFixed(2)}; --star-size:50px"
                                            aria-label="Rating of this product is ${Number(data.ratings).toFixed(2)} out of 5."></div>
                                        <p>${Number(data.ratings).toFixed(2)}/5</p></td>`;
                    } else {
                        col3 = '<td class="text-center">No feedbacks submitted in this type</td>'
                    }

                    col4 = '<td>' + data.updated_at + '</td>'

                    viewBtn =
                        '<li class="list-inline-item mb-1">' +
                        '<a class="btn btn-info btn-sm" type="button" data-toggle="tooltip" data-placement="top" title="View" href="' + window.location.origin + '/admin/feedback-types/' + data.id + '"><i class="fas fa-eye"></i>' +
                        '</a></li>'
                    editBtn =
                        '<li class="list-inline-item mb-1">' +
                        '<button class="btn btn-primary btn-sm" onclick="editType(' + data.id + ')" type="button" data-toggle="tooltip" data-placement="top" title="Edit">' +
                        '<i class="fas fa-edit"></i>' +
                        '</button>' +
                        '</li>'
                    deleteBtn =
                        '<li class="list-inline-item mb-1">' +
                        '<button class="btn btn-danger btn-sm" onclick="deleteType(' + data.id + ')" type="button" data-toggle="tooltip" data-placement="top" title="Delete">' +
                        '<i class="fas fa-trash-alt"></i>' +
                        '</button>' +
                        '</li>'

                    col5 = '<td><ul class="list-inline m-0">' + viewBtn + editBtn + deleteBtn + '</td></ul>'

                    // Get table reference - note: dataTable() not DataTable()
                    var table = $('#dataTable').DataTable();

                    if (formMethod.toString() == 'POST') {
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

                        // increment typeCount
                        $("#typeCount").text(parseInt($("#typeCount").text()) + 1);

                    } else {
                        table.row('.selected').data([col0, col1, col2, col3, col4, col5]).draw(false);
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

    // Validation for report form
    $("form[name='reportForm']").validate({
        // Specify validation rules
        rules: {
            date_start: {
                required: true,
            },
            date_end: {
                required: true,
                greaterThan: "#date_start"
            },
            sort_column: {
                required: true,
            },
            sort_option: {
                required: true,
            },
        },
        messages: {
            date_end: {
                greaterThan: "Date end must be greater than selected date start"
            },
        },

        submitHandler: function (form, event) {
            event.preventDefault()

            let date_start = $('#date_start').val();
            let date_end = $('#date_end').val();
            let sort_column = $('#sort_column').val();
            let sort_option = $('#sort_option').val();

            var url = `${window.location.origin}/admin/feedback-types/report/${date_start}/${date_end}/${sort_column}/${sort_option}`;
            window.open(url, '_blank');
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
                $("#typeCount").text(parseInt($("#typeCount").text()) - 1);
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

