function createOrdinance() {
    const url = window.location.origin + '/admin/ordinances/create'

    doAjax(url, 'GET').then((response) => {
        if (response.success) {
            const types = response.types;
            $('.custom-file-label').html(''); //empty the html in the file input
            $('#ordinanceForm').trigger("reset") //reset all the input values
            $('#ordinanceTypeDropDwn').empty() //reset dropdown button
            $("#formMethod").empty(); //empty form method div

            //since it is from the docu profile, the docutype would be fixed depending kung anong page yun
            const currentTypeID = window.location.href.match(/ordinance-types\/(\d+)/)

            $.each(types, function () {
                // Populate Drop Dowm
                if (currentTypeID[1] == this.id) {
                    $('#ordinanceTypeDropDwn').append($("<option />").val(this.id).text(this.name))
                }
            })
            let actionURL = '/admin/ordinances/'
            let inputMethod = '<input type="hidden" id="method" name="_method" value="POST">'

            $("#formMethod").append(inputMethod) // append formMethod div
            $('#ordinanceForm').attr('action', actionURL) //set the method of the form
            $('#ordinanceModal').modal('show')
            $('#ordinanceModalHeader').text('Publish Ordinance')
            $('.btnTxt').text('Store') //set the text of the submit btn
        }
    }
    )
}

function editOrdinance(id) {
    const url = window.location.origin + '/admin/ordinances/' + id + '/edit'
    doAjax(url, 'GET').then((response) => {
        if (response.success) {
            const data = response.data
            const types = response.types

            $('.custom-file-label').html(''); //empty the html in the file input
            $('#ordinanceForm').trigger("reset") //reset all the input values
            $('#ordinanceTypeDropDwn').empty() //reset dropdown button
            $("#formMethod").empty(); //empty form method div

            //Set form input values
            $('#ordinanceTypeDropDwn').append($("<option selected/>").val(data.type_id).text(data.type_id == 0 ? data.custom_type : data.ordinance_type)) //set current type_id
            $.each(types, function () {
                // Populate Drop Dowm
                if (this.id != data.type_id) {
                    $('#ordinanceTypeDropDwn').append($("<option />").val(this.id).text(this.name))
                }
            })

            $('#ordinance_no').val(data.ordinance_no)
            $('#title').val(data.title)
            $('#date_approved').val(data.date_approved)
            $('.custom-file-label').html(data.pdf_name)

            let actionURL = '/admin/ordinances/' + data.id
            let inputMethod = '<input type="hidden" id="method" name="_method" value="PUT">'

            $("#formMethod").append(inputMethod) // append formMethod div
            $('#ordinanceForm').attr('action', actionURL) //set the method of the form
            $('#ordinanceModal').modal('show')
            $('#ordinanceModalHeader').text('Edit Ordinance')
            $('.btnTxt').text('Update') //set the text of the submit btn
        }
    })
}

function deleteOrdinance(id) {
    $('#confirmationDeleteModal').modal('show')
    $('#modalDeleteForm').attr('action', '/admin/ordinances/' + id)
    $('#confirmationMessage').text('Do you really want to delete this ordinance? This process cannot be undone.')
}

$(document).ready(function () {
    $('input[type="file"]').change(function (e) {
        var fileName = e.target.files[0].name;
        $('.custom-file-label').html(fileName);
    });
    // Initialize Year picker in form
    $("#date_approved").datepicker({
        format: "yyyy-mm-dd", // Notice the Extra space at the beginning
    });

    // Set class row selected when any button was click in the selected
    $('#dataTable').on('click', 'tr', function () {
        if (!$(this).hasClass('selected')) {
            $('#dataTable').DataTable().$('tr.selected').removeClass('selected')
            $(this).addClass('selected')
        }
    })

    $('#TypeNavCollapse').addClass('active')
    $('#collapseType').collapse()
    $('#ordinanceType').addClass('active')

    // Validation
    $("form[name='ordinanceForm']").validate({
        // Specify validation rules
        rules: {
            type_id: {
                required: true,
                minStrict: 0,
                number: true
            },
            ordinance_no: {
                required: true,
                minlength: 4,
                maxlength: 60,
            },
            title: {
                required: true,
                minlength: 4,
                maxlength: 250,
            },
            date_approved: {
                required: true,
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
                required: 'Please select one of the ordinance types',
                digits: 'Invalid Input',
                minStrict: 'Please select one of the ordinance types',
            },
            pdf: {
                extension: "Invalid file type! Document must be in .pdf format",
                filesize: "Selected file must be less than 10mb"
            },
        },
        submitHandler: function (form, event) {
            event.preventDefault()

            let formAction = $("#ordinanceForm").attr('action')
            let formMethod = $('#method').val()
            let formData = new FormData(form)

            $('#btnFormSubmit').attr("disabled", true); //disabled login
            $('.btnTxt').text(formMethod == 'POST' ? 'Storing' : 'Updating') //set the text of the submit btn
            $('.loadingIcon').prop("hidden", false) //show the fa loading icon from submit btn

            doAjax(formAction, 'POST', formData).then((response) => {
                if (response.success) {
                    $('#ordinanceModal').modal('hide') //hide the modal

                    const data = response.data

                    col1 = '<td>' + data.ordinance_no + '</td>'
                    col2 = '<td>' + data.title + '</td>'
                    col3 = '<td>' + data.date_approved + '</td>'
                    col4 = '<td><a href="' + window.location.origin + '/files/ordinances/' + data.pdf_name + '">' + data.pdf_name + '</a></td>'
                    col5 = '<td>' + data.updated_at + '</td>'

                    editBtn =
                        '<li class="list-inline-item mb-1">' +
                        '<button class="btn btn-primary btn-sm" onclick="editOrdinance(' + data.id + ')" type="button" data-toggle="tooltip" data-placement="top" title="Edit">' +
                        '<i class="fas fa-edit"></i>' +
                        '</button>' +
                        '</li>'
                    deleteBtn =
                        '<li class="list-inline-item mb-1">' +
                        '<button class="btn btn-danger btn-sm" onclick="deleteOrdinance(' + data.id + ')" type="button" data-toggle="tooltip" data-placement="top" title="Delete">' +
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

                        $("#ordinancesCount").text(parseInt($("#ordinancesCount").text()) + 1);

                    } else {

                        // get the current href parameter
                        const currentTypeID = window.location.href.match(/ordinance-types\/(\d+)/)

                        // check if the data is equal to the current href parameter
                        if (parseInt(currentTypeID[1]) != parseInt(data.type_id)) {
                            var table = $('#dataTable').DataTable();
                            $('.selected').fadeOut(800, function () {
                                table.row('.selected').remove().draw();
                            });

                            // decrement ordinancesCount
                            $("#ordinancesCount").text(parseInt($("#ordinancesCount").text()) - 1);
                        } else {
                            table.row('.selected').data([col1, col2, col3, col4, col5, col6]).draw(false);
                        }
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
        doAjax(formAction, 'DELETE').then((response) => {
            if (response.success) {
                var table = $('#dataTable').DataTable();
                $('.selected').fadeOut(800, function () {
                    table.row('.selected').remove().draw();
                });

                // decrement ordinancesCount
                $("#ordinancesCount").text(parseInt($("#ordinancesCount").text()) - 1);
            }
        })
        $('#confirmationDeleteModal').modal('hide') //hide
        $('#modalDeleteForm').trigger("reset"); //reset all the values
    })


})
