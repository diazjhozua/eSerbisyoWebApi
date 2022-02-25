
function addOrReplaceDataRow(data, formMethod) {

    col0 = '<td>' + data.id + '</td>'
    col1 = '<td>' + data.name + '</td>'
    col2 = '<td>' + data.year_start + '</td>'
    col3 = '<td>' + data.year_end + '</td>'
    col4 = '<td>' + data.employees_count + '</td>'
    col5 = '<td>' + data.updated_at + '</td>'

    viewBtn =
        '<li class="list-inline-item mb-1">' +
        '<a class="btn btn-info btn-sm" type="button" data-toggle="tooltip" data-placement="top" title="View" href="' + window.location.origin + '/admin/terms/' + data.id + '"><i class="fas fa-eye"></i>' +
        '</a></li>'
    editBtn =
        '<li class="list-inline-item mb-1">' +
        '<button class="btn btn-primary btn-sm" onclick="editTerm(' + data.id + ')" type="button" data-toggle="tooltip" data-placement="top" title="Edit">' +
        '<i class="fas fa-edit"></i>' +
        '</button>' +
        '</li>'
    deleteBtn =
        '<li class="list-inline-item mb-1">' +
        '<button class="btn btn-danger btn-sm" onclick="deleteTerm(' + data.id + ')" type="button" data-toggle="tooltip" data-placement="top" title="Delete">' +
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
        $("#termCount").text(parseInt($("#termCount").text()) + 1);


    } else {
        table.row('.selected').data([col0, col1, col2, col3, col4, col5, col6]).draw(false);
    }
}

function createTerm() {
    let ajaxStoreURL = window.location.origin + '/admin/terms';
    let inputMethod = '<input type="hidden" id="method" name="_method" value="POST">';

    showTermFormModal(ajaxStoreURL, inputMethod, 'Create Term', 'Store');
}

function showTermFormModal(ajaxUrl, inputMethod, titleHeader, btnTxt) {
    $('#termFormModal').modal('show') //show the modal
    $('#termFormModalHeader').text(titleHeader) //set the header of the
    $('.btnTxt').text(btnTxt) //set the text of the submit btn
    $('#termForm').trigger("reset"); //reset all the values
    $("#formMethod").empty();
    $("#formMethod").append(inputMethod) // append formMethod
    $('#termForm').attr('action', ajaxUrl) //set the method of the form
}

function editTerm(id) {
    ajaxEditURL = window.location.origin + 'terms/' + id + '/edit'

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
            const ajaxUpdateURL = window.location.origin + '/admin/terms/' + data.id;

            //show form modal
            showTermFormModal(ajaxUpdateURL, inputMethod, 'Edit Term', 'Update');

            // set the input values
            $('#name').val(data.name)
            $('#year_start').val(data.year_start)
            $('#year_end').val(data.year_end)
        },
        error: function (xhr) {
            var error = JSON.parse(xhr.responseText);

            // show error message from helper.js
            ajaxErrorMessage(error);
        }
    });


}

function deleteTerm(id) {
    $('#confirmationDeleteModal').modal('show')
    $('#modalDeleteForm').attr('action', window.location.origin + '/admin/terms/' + id)
    $('#confirmationMessage').text('Do you really want to delete this type? This process cannot be undone. All of the employees related to this type would be transfer to "Others(Employees without assigned term)"')
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
    $('#term').addClass('active')

    // Initialize Year picker in form
    $(".year").datepicker({
        format: " yyyy", // Notice the Extra space at the beginning
        viewMode: "years",
        minViewMode: "years",
        yearRange: "-100:+0",

    });

    // Create Modal Form Validation
    $("form[name='termForm']").validate({
        // Specify validation rules
        rules: {
            name: {
                required: true,
                minlength: 6,
                maxlength: 150,
            },
            year_start: {
                required: true,
                max: new Date().getFullYear() + 10,
                min: 1900,
            },
            year_end: {
                required: true,
                max: new Date().getFullYear() + 10,
                min: 1900,
                greaterThan: "#year_start"
            },
        },
        messages: {
            year_end: {
                greaterThan: "Selected year end must be greater than year start"
            },
        },

        submitHandler: function (form, event) {
            event.preventDefault()
            let ajaxStoreURL = $("#termForm").attr('action')
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
                    $('.btnFormSubmit').attr("disabled", true); //disabled login
                    $('.btnTxt').text(formMethod == 'POST' ? 'Storing' : 'Updating') //set the text of the submit btn
                    $('.loadingIcon').prop("hidden", false) //show the fa loading icon from submit btn
                },
                success: function (response) {
                    toastr.success(response.message);
                    $('#termFormModal').modal('hide') //hide the modal
                    const data = response.data;

                    addOrReplaceDataRow(data, formMethod);
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
                $("#termCount").text(parseInt($("#termCount").text()) - 1);
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
