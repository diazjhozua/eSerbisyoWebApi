function createEmployee() {
    const url = 'employees/create'
    doAjax(url, 'GET').then((response) => {
        if (response.success) {
            const positions = response.positions;
            const terms = response.terms;
            $('.custom-file-label').html(''); //empty the html in the file input
            $('#employeeForm').trigger("reset") //reset all the input values
            $('#employeePositionDropDwn').empty() //reset dropdown button
            $('#employeeTermDropDwn').empty()
            $("#formMethod").empty(); //empty form method div
            $("#currentPictureDiv").hide(); // hide the current picture div
            $('#imgCurrentPicture').removeAttr('src'); //remoce the src attribute
            $("#imgCurrentPicture").prop("alt", ""); //remove the alt text

            $('#employeePositionDropDwn').append($("<option selected/>").val("").text('Choose...'))
            $.each(positions, function () {
                // Populate Position Drop Dowm
                $('#employeePositionDropDwn').append($("<option />").val(this.id).text(this.name))
            })

            $('#employeeTermDropDwn').append($("<option selected/>").val("").text('Choose...'))
            $.each(terms, function () {
                // Populate Term Drop Dowm
                $('#employeeTermDropDwn').append($("<option />").val(this.id).text(this.name + ' (' + this.year_start + '-' + this.year_end + ')'))
            })

            let actionURL = '/admin/employees/'
            let inputMethod = '<input type="hidden" id="method" name="_method" value="POST">'

            $("#formMethod").append(inputMethod) // append formMethod div
            $('#employeeForm').attr('action', actionURL) //set the method of the form
            $('#employeeFormModal').modal('show')
            $('#employeeFormModalHeader').text('Publish Employee')
            $('.btnTxt').text('Store') //set the text of the submit btn
        }
    })
}

function editEmployee(id) {

    url = 'employees/' + id + '/edit'
    doAjax(url, 'GET').then((response) => {
        if (response.success) {
            const data = response.data
            const positions = response.positions;
            const terms = response.terms;


            $('.custom-file-label').html(''); //empty the html in the file input
            $('#employeeForm').trigger("reset") //reset all the input values
            $('#employeePositionDropDwn').empty() //reset dropdown button
            $('#employeeTermDropDwn').empty()
            $("#formMethod").empty(); //empty form method div
            $("#currentPictureDiv").hide(); // hide the current picture div
            $('#imgCurrentPicture').removeAttr('src'); //remoce the src attribute
            $("#imgCurrentPicture").prop("alt", ""); //remove the alt text

            $('#employeePositionDropDwn').append($("<option selected/>").val(data.position_id).text(data.position_id == 0 ? data.custom_position : data.position))
            $.each(positions, function () {
                if (this.id != data.position_id) {
                    // Populate Position Drop Dowm
                    $('#employeePositionDropDwn').append($("<option />").val(this.id).text(this.name))
                }
            })

            $('#employeeTermDropDwn').append($("<option selected/>").val(data.term_id).text(data.term_id == 0 ? data.custom_term : data.term))
            $.each(terms, function () {
                if (this.id != data.term_id) {
                    // Populate Term Drop Dowm
                    $('#employeeTermDropDwn').append($("<option />").val(this.id).text(this.name + ' (' + this.year_start + '-' + this.year_end + ')'))
                }
            })

            $('#name').val(data.name)
            $('#description').val(data.description)
            $('.custom-file-label').html(data.picture_name)
            $("#currentPictureDiv").show(); // show the current picture div
            $('#imgCurrentPicture').prop('src', window.location.origin + '/storage/' + data.file_path); //add the src attribute
            $("#imgCurrentPicture").prop("alt", data.name + ' picture'); //add the alt text

            let actionURL = '/admin/employees/' + data.id
            let inputMethod = '<input type="hidden" id="method" name="_method" value="PUT">'

            $("#formMethod").append(inputMethod) // append formMethod div
            $('#employeeForm').attr('action', actionURL) //set the method of the form
            $('#employeeFormModal').modal('show')
            $('#employeeFormModalHeader').text('Edit Employee')
            $('.btnTxt').text('Update') //set the text of the submit btn
        }
    })
}

function deleteEmployee(id) {
    $('#confirmationDeleteModal').modal('show')
    $('#modalDeleteForm').attr('action', '/admin/employees/' + id)
    $('#confirmationMessage').text('Do you really want to delete this employee? This process cannot be undone.')
}

$(document).ready(function () {
    // Set class row selected when any button was click in the selected
    $('#dataTable').on('click', 'tr', function () {
        if (!$(this).hasClass('selected')) {
            $('#dataTable').DataTable().$('tr.selected').removeClass('selected')
            $(this).addClass('selected')
        }
    })


    $('input[type="file"]').change(function (e) {
        var fileName = e.target.files[0].name;
        $('.custom-file-label').html(fileName);
    });

    // Toggle Collapse Bar in SideBar Pane
    $('#OfficialNavCollapse').addClass('active')
    $('#collapseOfficial').collapse()
    $('#employee').addClass('active')

    // Create Modal Form Validation
    $("form[name='employeeForm']").validate({
        // Specify validation rules
        rules: {
            name: {
                required: true,
                minlength: 4,
                maxlength: 150,
            },
            position_id: {
                required: true,
                minStrict: 0,
                number: true
            },
            employee_id: {
                required: true,
                minStrict: 0,
                number: true
            },
            description: {
                required: true,
                minlength: 4,
                maxlength: 250,
            },
            picture: {
                required: function () {
                    return $('#method').val() == 'POST'
                },
                extension: "jpeg|jpg|png",
                filesize: 10485760, //10mb in bytes
            },
        },
        messages: {
            position_id: {
                required: 'Please select one of the position types',
                digits: 'Invalid Input',
                minStrict: 'Please select one of the position types',
            },
            employee_id: {
                required: 'Please select one of the employee types',
                digits: 'Invalid Input',
                minStrict: 'Please select one of the employee types',
            },
            picture: {
                extension: "Invalid file type! Document must be in jpeg|jpg|png format",
                filesize: "Selected file must be less than 10mb"
            },
        },

        submitHandler: function (form, event) {
            event.preventDefault()
            let formAction = $("#employeeForm").attr('action')
            let formMethod = $('#method').val()
            let formData = new FormData(form)

            $('#btnFormSubmit').attr("disabled", true); //disabled login
            $('.btnTxt').text(formMethod == 'POST' ? 'Storing' : 'Updating') //set the text of the submit btn
            $('.loadingIcon').prop("hidden", false) //show the fa loading icon from submit btn

            doAjax(formAction, 'POST', formData).then((response) => {
                if (response.success != null && response.success == true) {
                    $('#employeeFormModal').modal('hide') //hide the modal

                    const data = response.data

                    col0 = '<td>' + data.id + '</td>'
                    col1 = '<td>' + data.name + '</td>'
                    col2 = '<td><img style="height:150px; max-height: 150px; max-width:150px; width: 150px;" src="' + window.location.origin + '/storage/' + data.file_path + '" class="rounded" alt="' + data.name + ' image"></td>'
                    col3 = '<td><a href="' + window.location.origin + '/admin/positions/' + data.position_id + '">' + data.position + '</a></td>'
                    col4 = '<td><a href="' + window.location.origin + '/admin/terms/' + data.term_id + '">' + data.term + '</a></td>'
                    col5 = '<td>' + data.description + '</td>'
                    col6 = '<td>' + data.updated_at + '</td>'

                    editBtn =
                        '<li class="list-inline-item mb-1">' +
                        '<button class="btn btn-primary btn-sm" onclick="editEmployee(' + data.id + ')" type="button" data-toggle="tooltip" data-placement="top" title="Edit">' +
                        '<i class="fas fa-edit"></i>' +
                        '</button>' +
                        '</li>'
                    deleteBtn =
                        '<li class="list-inline-item mb-1">' +
                        '<button class="btn btn-danger btn-sm" onclick="deleteEmployee(' + data.id + ')" type="button" data-toggle="tooltip" data-placement="top" title="Delete">' +
                        '<i class="fas fa-trash-alt"></i>' +
                        '</button>' +
                        '</li>'

                    col7 = '<td><ul class="list-inline m-0">' + editBtn + deleteBtn + '</td></ul>'
                    // Get table reference - note: dataTable() not DataTable()
                    var table = $('#dataTable').DataTable();

                    if (formMethod == 'POST') {
                        var currentPage = table.page();
                        table.row.add([col0, col1, col2, col3, col4, col5, col6, col7]).draw()

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
                        $("#employeesCount").text(parseInt($("#employeesCount").text()) + 1);


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
                $("#employeesCount").text(parseInt($("#employeesCount").text()) - 1);
            }

            $('#btnDelete').attr("disabled", false); //enable button
            $('.btnDeleteTxt').text('Delete') //set the text of the delete btn
            $('.btnDeleteLoadingIcon').prop("hidden", true) //hide the fa loading icon from submit btn
        })
        $('#confirmationDeleteModal').modal('hide') //hide
        $('#modalDeleteForm').trigger("reset"); //reset all the values
    })

})
