
function resetForm() {
    $('.custom-file-label').html(''); //empty the html in the file input
    $('#employeeForm').trigger("reset") //reset all the input values
    $('#employeePositionDropDwn').empty() //reset dropdown button
    $('#employeeTermDropDwn').empty()
    $("#formMethod").empty(); //empty form method div
    $("#currentPictureDiv").hide(); // hide the current picture div
    $('#imgCurrentPicture').removeAttr('src'); //remoce the src attribute
    $("#imgCurrentPicture").prop("alt", ""); //remove the alt text
};

function showEmployeeFormModal(ajaxUrl, inputMethod, titleHeader, btnTxt, employeeData, positionSelect, termSelect) {
    //reset form fields
    resetForm();

    $("#formMethod").append(inputMethod) // append formMethod div
    $('#employeeForm').attr('action', ajaxUrl) //set the method of the form
    $('#employeeFormModal').modal('show')
    $('#employeeFormModalHeader').text(titleHeader)
    $('.btnTxt').text(btnTxt) //set the text of the submit btn

    if (employeeData != null) {
        //meaning the user is editing
        //assigning values to input fields
        $('#name').val(employeeData.name);
        $('#description').val(employeeData.description);
        $('.custom-file-label').html(employeeData.picture_name);
        $("#currentPictureDiv").show(); // show the current picture div
        $('#imgCurrentPicture').prop('src', employeeData.file_path); //add the src attribute
        $("#imgCurrentPicture").prop("alt", employeeData.name); //add the alt text

        //populate position select drop down
        $('#employeePositionDropDwn').append($("<option selected/>").val(employeeData.position_id).text(employeeData.position_id == 0 ? employeeData.custom_position : employeeData.position))
        $.each(positionSelect, function () {
            if (this.id != employeeData.position_id) {
                // Populate Position Drop Dowm
                $('#employeePositionDropDwn').append($("<option />").val(this.id).text(this.name));
            }
        });

        // populate term select dropdow
        $('#employeeTermDropDwn').append($("<option selected/>").val(employeeData.term_id).text(employeeData.term_id == 0 ? employeeData.custom_term : employeeData.term));
        $.each(termSelect, function () {
            if (this.id != employeeData.term_id) {
                // Populate Term Drop Dowm
                $('#employeeTermDropDwn').append($("<option />").val(this.id).text(this.name + ' (' + this.year_start + '-' + this.year_end + ')'));
            }
        });


    } else {
        // the form is in creating mode

        // poplute employeePositionDropDwn
        $('#employeePositionDropDwn').append($("<option selected/>").val("").text('Choose...'));
        $.each(positionSelect, function () {
            // Populate Position Drop Dowm
            $('#employeePositionDropDwn').append($("<option />").val(this.id).text(this.name))
        });

        // since it is from the term profile, the currentTermID would be fixed depending kung anong page yun
        const currentTermID = window.location.href.match(/terms\/(\d+)/)
        $.each(termSelect, function () {
            // Populate Term Drop Dowm
            if (currentTermID[1] == this.id) {
                $('#employeeTermDropDwn').append($("<option />").val(this.id).text(this.name + ' (' + this.year_start + '-' + this.year_end + ')'))
            }
        })

    }
}

function createEmployee() {
    const ajaxCreateURL = window.location.origin + '/admin/employees/create'

    $.ajax({
        type: 'GET',
        url: ajaxCreateURL,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        },
        cache: false,
        processData: false,
        contentType: false,
        success: function (response) {
            toastr.success(response.message);

            const termSelect = response.terms;
            const positionSelect = response.positions;
            const inputMethod = '<input type="hidden" id="method" name="_method" value="POST">';
            const ajaxStoreURL = window.location.origin + '/admin/employees';

            //show form modal
            showEmployeeFormModal(ajaxStoreURL, inputMethod, 'Create Employee', 'Store', null, positionSelect, termSelect);

        },
        error: function (xhr) {
            var error = JSON.parse(xhr.responseText);

            // show error message from helper.js
            ajaxErrorMessage(error);
        }
    });
}

function deleteEmployee(id) {
    $('#confirmationDeleteModal').modal('show')
    $('#modalDeleteForm').attr('action', window.location.origin + '/admin/employees/' + id)
    $('#confirmationMessage').text('Do you really want to delete this employee? This process cannot be undone.')
}


function editEmployee(id) {

    ajaxEditUrl = window.location.origin + '/admin/employees/' + id + '/edit'

    $.ajax({
        type: 'GET',
        url: ajaxEditUrl,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        },
        cache: false,
        processData: false,
        contentType: false,
        success: function (response) {
            toastr.success(response.message);

            const employeeData = response.data;
            const termSelect = response.terms;
            const positionSelect = response.positions;
            const inputMethod = '<input type="hidden" id="method" name="_method" value="PUT">';
            const ajaxUpdateURL = window.location.origin + '/admin/employees/' + employeeData.id;

            //show form modal
            showEmployeeFormModal(ajaxUpdateURL, inputMethod, 'Create Employee', 'Update', employeeData, positionSelect, termSelect);

        },
        error: function (xhr) {
            var error = JSON.parse(xhr.responseText);

            // show error message from helper.js
            ajaxErrorMessage(error);
        }
    });
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
    $('#term').addClass('active')

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
            let ajaxStoreURL = $("#employeeForm").attr('action')
            let formMethod = $('#method').val()
            let formData = new FormData(form)

            $.ajax({
                type: 'POST',
                url: ajaxStoreURL,
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

                    $('#employeeFormModal').modal('hide') //hide the modal

                    const data = response.data;

                    col0 = '<td>' + data.id + '</td>'
                    col1 = '<td>' + data.name + '</td>'
                    col2 = '<td><img style="height:150px; max-height: 150px; max-width:150px; width: 150px;" src="' + data.file_path + '" class="rounded" alt="' + data.name + '"></td>'
                    col3 = '<td><a href="' + window.location.origin + '/admin/positions/' + data.position_id + '">' + data.position + '</a></td>'
                    col4 = '<td>' + data.description + '</td>'
                    col5 = '<td>' + data.updated_at + '</td>'

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

                    col6 = '<td><ul class="list-inline m-0">' + editBtn + deleteBtn + '</td></ul>'
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

                        // increment employeeCount
                        $("#employeesCount").text(parseInt($("#employeesCount").text()) + 1);


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


                    $('#btnFormSubmit').attr("disabled", false); //disabled login
                    $('.btnTxt').text(formMethod == 'POST' ? 'Store' : 'Update') //set the text of the submit btn
                    $('.loadingIcon').prop("hidden", true) //show the fa loading icon from submit btn
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

                // decrement empCount;
                $("#employeesCount").text(parseInt($("#employeesCount").text()) - 1);
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
