function createType() {
    let actionURL = '/admin/feedback-types/'
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
    url = 'feedback-types/' + id + '/edit'
    doAjax(url, 'GET').then((response) => {
        if (response.success) {
            const data = response.data;
            const inputMethod = '<input type="hidden" id="method" name="_method" value="PUT">'
            const actionURL = '/admin/feedback-types/' + data.id
            $('#typeFormModal').modal('show') //show the modal
            $('#typeFormModalHeader').text('Edit Feedback Type') //set the header of the
            $('#nameLabel').text('Feedback Type Name') //set the text of type name in the form
            $('#typeForm').trigger("reset"); //reset all the values
            $('#typeName').val(data.name) // set the text of the input
            $('.btnTxt').text('Update') //set the text of the submit btn
            $("#formMethod").empty();
            $("#formMethod").append(inputMethod) // append formMethod
            $('#typeForm').attr('action', actionURL) //set action
        }
    }
    )
}

function deleteType(id) {
    $('#confirmationDeleteModal').modal('show')
    $('#modalDeleteForm').attr('action', '/admin/feedback-types/' + id)
    $('#confirmationMessage').text('Do you really want to delete this feedback-type? This process cannot be undone. All of the feedbacks related to this type would be transfer to "Others"')
}

$(document).ready(function () {

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

            $('#btnFormSubmit').attr("disabled", true); //disabled login
            $('.btnTxt').text(formMethod == 'POST' ? 'Storing' : 'Updating') //set the text of the submit btn
            $('.loadingIcon').prop("hidden", false) //show the fa loading icon from submit btn

            doAjax(formAction, 'POST', formData).then((response) => {
                if (response.success) {
                    $('#typeFormModal').modal('hide') //hide the modal

                    const data = response.data

                    col0 = '<td>' + data.id + '</td>'
                    col1 = '<td>' + data.name + '</td>'
                    col2 = '<td>' + data.feedbacks_count + '</td>'
                    col3 = '<td>' + data.positive + '</td>'
                    col4 = '<td>' + data.neutral + '</td>'
                    col5 = '<td>' + data.negative + '</td>'
                    col6 = '<td>' + data.updated_at + '</td>'

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

                    col7 = '<td><ul class="list-inline m-0">' + viewBtn + editBtn + deleteBtn + '</td></ul>'

                    // Get table reference - note: dataTable() not DataTable()
                    var table = $('#dataTable').DataTable();
                    var currentPage = table.page();
                    table.row.add([col0, col1, col2, col3, col4, col5, col6, col7]).draw()

                    if (formMethod.toString() == 'POST') {
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
                        table.row('.selected').data([col0, col1, col2, col3, col4, col5, col6, col7]).draw(false);
                    }
                }

                $('#btnFormSubmit').attr("disabled", false);
                $('.btnTxt').text(formMethod == 'POST' ? 'Store' : 'Update') //set the text of the submit btn
                $('.loadingIcon').prop("hidden", true) //hide the fa loading icon from submit btn
            })
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

            let formAction = $("#reportForm").attr('action')
            let formData = new FormData(form)

            $('.btnReportFormSubmit').attr("disabled", true); //disabled login
            $('.btnReportFormTxt').text('Generating') //set the text of the submit btn
            $('.btnReportFormLoadingIcon').prop("hidden", false) //show the fa loading icon from submit btn

            $.ajax({
                type: 'POST',
                url: formAction,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                data: formData,
                xhrFields: {
                    responseType: 'blob'
                },
                cache: false,
                processData: false,
                contentType: false,

                success: function (response) {
                    toastr.success('Report successfully downloaded')
                    var blob = new Blob([response]);
                    var link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = "FeedbackTypeReport-" + Date.now() + ".pdf";
                    link.click();

                },
                error: function (response) {
                    toastr.error('Something went wrong :( (It could be the selected option produces no data)')
                },
                complete: function () {
                    $('.btnReportFormSubmit').attr("disabled", false); //enable the button
                    $('.btnReportFormTxt').text('Generate') //set the text of the submit btn
                    $('.btnReportFormLoadingIcon').prop("hidden", true) //hide the fa loading icon from submit btn
                }
            });
        }
    });

    // Delete Modal Form
    $("#modalDeleteForm").submit(function (e) {
        e.preventDefault()
        let formAction = $("#modalDeleteForm").attr('action')

        $('#btnDelete').attr("disabled", true); //disabled button
        $('.btnDeleteTxt').text('Deleting') //set the text of the submit btn
        $('.btnDeleteLoadingIcon').prop("hidden", false) //show the fa loading icon from delete btn

        doAjax(formAction, 'DELETE').then((response) => {
            if (response.success) {
                var table = $('#dataTable').DataTable();
                $('.selected').fadeOut(800, function () {
                    table.row('.selected').remove().draw();
                });

                // decrement typeCount
                $("#typeCount").text(parseInt($("#typeCount").text()) - 1);
            }

            $('#btnDelete').attr("disabled", false); //enable button
            $('.btnDeleteTxt').text('Delete') //set the text of the delete btn
            $('.btnDeleteLoadingIcon').prop("hidden", true) //hide the fa loading icon from submit btn
        })
        $('#confirmationDeleteModal').modal('hide') //hide
        $('#modalDeleteForm').trigger("reset"); //reset all the values
    })

})

