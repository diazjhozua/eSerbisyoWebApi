function createDocument() {
    const url = 'documents/create'
    doAjax(url, 'GET').then((response) => {
        if (response.success) {
            const types = response.types;
            $('.custom-file-label').html(''); //empty the html in the file input
            $('#documentForm').trigger("reset") //reset all the input values
            $('#documentTypeDropDwn').empty() //reset dropdown button
            $("#formMethod").empty(); //empty form method div
            $('#documentTypeDropDwn').append($("<option selected/>").val("").text('Choose...'))
            $.each(types, function () {
                // Populate Drop Dowm
                $('#documentTypeDropDwn').append($("<option />").val(this.id).text(this.name))
            })
            let actionURL = '/admin/documents/'
            let inputMethod = '<input type="hidden" id="method" name="_method" value="POST">'

            $("#formMethod").append(inputMethod) // append formMethod div
            $('#documentForm').attr('action', actionURL) //set the method of the form
            $('#documentModal').modal('show')
            $('#documentModalHeader').text('Publish Document')
            $('.btnTxt').text('Store') //set the text of the submit btn
        }
    }
    )
}

function editDocument(id) {

    url = 'documents/' + id + '/edit'
    doAjax(url, 'GET').then((response) => {
        if (response.success) {
            const data = response.data
            const types = response.types

            $('.custom-file-label').html(''); //empty the html in the file input
            $('#documentForm').trigger("reset") //reset all the input values
            $('#documentTypeDropDwn').empty() //reset dropdown button
            $("#formMethod").empty(); //empty form method div

            //Set form input values

            $('#documentTypeDropDwn').append($("<option selected/>").val(data.type_id).text(data.type_id == 0 ? data.custom_type : data.document_type)) //set current type_id
            $.each(types, function () {
                // Populate Drop Dowm
                if (this.id != data.type_id) {
                    $('#documentTypeDropDwn').append($("<option />").val(this.id).text(this.name))
                }
            })

            $('#description').val(data.description)
            $('#year').val(data.year)
            $('.custom-file-label').html(data.pdf_name)

            let actionURL = '/admin/documents/' + data.id
            let inputMethod = '<input type="hidden" id="method" name="_method" value="PUT">'

            $("#formMethod").append(inputMethod) // append formMethod div
            $('#documentForm').attr('action', actionURL) //set the method of the form
            $('#documentModal').modal('show')
            $('#documentModalHeader').text('Edit Document')
            $('.btnTxt').text('Update') //set the text of the submit btn
        }
    })
}

function deleteDocument(id) {
    $('#confirmationDeleteModal').modal('show')
    $('#modalDeleteForm').attr('action', '/admin/documents/' + id)
    $('#confirmationMessage').text('Do you really want to delete this document? This process cannot be undone.')
}

$(document).ready(function () {
    $('#document').addClass('active')

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

    // Initialize Year picker in form
    $("#year").datepicker({
        format: " yyyy", // Notice the Extra space at the beginning
        viewMode: "years",
        minViewMode: "years",
        yearRange: "-100:+0",

    });

    // Validation
    $("form[name='documentForm']").validate({
        // Specify validation rules
        rules: {
            type_id: {
                required: true,
                minStrict: 0,
                number: true
            },
            description: {
                required: true,
                minlength: 4,
                maxlength: 250,
            },
            year: {
                required: true,
                max: new Date().getFullYear() + 1,
                min: 1900,
            },
            pdf: {
                required: function () {
                    return $('#method').val() == 'POST'
                },
                extension: "pdf",
                filesize: 10485760, //10mb in bytes
            },
        },
        messages: {
            type_id: {
                required: 'Please select one of the document types',
                digits: 'Invalid Input',
                minStrict: 'Please select one of the document types',
            },
            year: {
                max: 'The year must not be greater than ' + (new Date().getFullYear() + 1),
            },
            pdf: {
                extension: "Invalid file type! Document must be in .pdf format",
                filesize: "Selected file must be less than 10mb"
            },
        },

        submitHandler: function (form, event) {
            event.preventDefault()

            let formAction = $("#documentForm").attr('action')
            let formMethod = $('#method').val()
            let formData = new FormData(form)

            $('#btnFormSubmit').attr("disabled", true); //disabled login
            $('.btnTxt').text(formMethod == 'POST' ? 'Storing' : 'Updating') //set the text of the submit btn
            $('.loadingIcon').prop("hidden", false) //show the fa loading icon from submit btn

            doAjax(formAction, 'POST', formData).then((response) => {
                if (response.success) {
                    $('#documentModal').modal('hide') //hide the modal

                    const data = response.data

                    col1 = '<td>' + data.description + '</td>'
                    col2 = '<td><a href="' + window.location.origin + '/admin/document-types/' + data.type_id + '">' + data.document_type + '</a></td>'
                    col3 = '<td>' + data.year + '</td>'
                    col4 = '<td><a href="' + window.location.origin + '/admin/files/documents/' + data.pdf_name + '">' + data.pdf_name + '</a></td>'
                    col5 = '<td>' + data.updated_at + '</td>'

                    editBtn =
                        '<li class="list-inline-item mb-1">' +
                        '<button class="btn btn-primary btn-sm" onclick="editDocument(' + data.id + ')" type="button" data-toggle="tooltip" data-placement="top" title="Edit">' +
                        '<i class="fas fa-edit"></i>' +
                        '</button>' +
                        '</li>'
                    deleteBtn =
                        '<li class="list-inline-item mb-1">' +
                        '<button class="btn btn-danger btn-sm" onclick="deleteDocument(' + data.id + ')" type="button" data-toggle="tooltip" data-placement="top" title="Delete">' +
                        '<i class="fas fa-trash-alt"></i>' +
                        '</button>' +
                        '</li>'

                    col6 = '<td><ul class="list-inline m-0">' + editBtn + deleteBtn + '</td></ul>'

                    // Get table reference - note: dataTable() not DataTable()
                    var table = $('#dataTable').DataTable();

                    if (formMethod == 'POST') {
                        var currentPage = table.page();
                        table.row.add([col1, col2, col3, col4, col5, col6]).draw()

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

                        $("#thisDayCount").text(parseInt($("#thisDayCount").text()) + 1);
                        $("#documentsCount").text(parseInt($("#documentsCount").text()) + 1);

                    } else {
                        table.row('.selected').data([col1, col2, col3, col4, col5, col6]).draw(false);
                    }
                }

                $('#btnFormSubmit').attr("disabled", false); //enable the button
                $('.btnTxt').text(formMethod == 'POST' ? 'Store' : 'Update') //set the text of the submit btn
                $('.loadingIcon').prop("hidden", true) //hide the fa loading icon from submit btn
            })
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

                $("#documentsCount").text(parseInt($("#documentsCount").text()) - 1);
            }

            $('#btnDelete').attr("disabled", false); //enable button
            $('.btnDeleteTxt').text('Delete') //set the text of the delete btn
            $('.btnDeleteLoadingIcon').prop("hidden", true) //hide the fa loading icon from submit btn
        })
        $('#confirmationDeleteModal').modal('hide') //hide
        $('#modalDeleteForm').trigger("reset"); //reset all the values
    })


})


