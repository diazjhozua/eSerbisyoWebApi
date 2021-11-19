// global variables
var currentRowCreatedAt; //for deleting purposes
var currentRowStatus; //for deleting purposes


function modifyModal(title, btnText, inputMethod, actionURL,) {

    $('#reportFormModal').modal('show') //show the modal
    $('#reportFormModalHeader').text(title) //set the header of the
    $('.btnFormTxt').text(btnText) //set the text of the submit btn
    $('#reportForm').trigger("reset"); //reset all the values
    $("#formMethod").empty();
    $("#formMethod").append(inputMethod) // append formMethod
    $('#reportForm').attr('action', actionURL) //set the method of the form

    $("#currentPictureDiv").hide(); // hide the current picture div
    $('#imgCurrentPicture').removeAttr('src'); //remoce the src attribute
    $("#imgCurrentPicture").prop("alt", ""); //remove the alt text

    //empty dropdown list
    $('#report_type').empty();
    $('#height_unit').empty();
    $('#weight_unit').empty();
}


function populateAllSelectFields(data, reportTypes, heightUnits, weightUnits) {

    // populate report type select form
    $('#report_type').append($("<option selected/>").val(data == null ? null : data.report_type).text(data == null ? 'Choose type' : data.report_type))
    $.each(reportTypes, function () {
        // Populate Drop Dowm
        if (data == null) {
            $('#report_type').append($("<option />").val(this.type).text(this.type))
        } else {
            if (this.type != data.report_type) {
                // Populate Position Drop Dowm
                $('#report_type').append($("<option />").val(this.type).text(this.type))
            }
        }
    })

    // populate height unit select form
    $('#height_unit').append($("<option selected/>").val(data == null ? null : data.height_unit).text(data == null ? 'Choose unit' : data.height_unit))
    $.each(heightUnits, function () {
        // Populate Drop Dowm
        if (data == null) {
            $('#height_unit').append($("<option />").val(this.unit).text(this.unit))
        } else {
            if (this.unit != data.height_unit) {
                // Populate Position Drop Dowm
                $('#height_unit').append($("<option />").val(this.unit).text(this.unit))
            }
        }
    })

    // populate height unit select form
    $('#weight_unit').append($("<option selected/>").val(data == null ? null : data.weight_unit).text(data == null ? 'Choose unit' : data.weight_unit))
    $.each(weightUnits, function () {
        // Populate Drop Dowm
        if (data == null) {
            $('#weight_unit').append($("<option />").val(this.unit).text(this.unit))
        } else {
            if (this.unit != data.weight_unit) {
                // Populate Position Drop Dowm
                $('#weight_unit').append($("<option />").val(this.unit).text(this.unit))
            }
        }
    })
}

function createReport() {
    url = 'missing-persons/create'
    doAjax(url, 'GET').then((response) => {
        const reportTypes = response.reportTypes;
        const heightUnits = response.heightUnits;
        const weightUnits = response.weightUnits;

        let actionURL = '/admin/missing-persons/';
        let inputMethod = '<input type="hidden" id="method" name="_method" value="POST">';

        //modify modal form function
        modifyModal('Publish Missing Report', 'Store', inputMethod, actionURL);

        //populate all the report select form fields
        populateAllSelectFields(null, reportTypes, heightUnits, weightUnits);

    })
}

function editReport(id) {
    url = 'missing-persons/' + id + '/edit'
    doAjax(url, 'GET').then((response) => {
        const data = response.data; //missing report data
        const reportTypes = response.reportTypes;
        const heightUnits = response.heightUnits;
        const weightUnits = response.weightUnits;

        let actionURL = '/admin/missing-persons/' + data.id;
        let inputMethod = '<input type="hidden" id="method" name="_method" value="PUT">';

        //modify modal form function
        modifyModal('Edit Missing Report', 'Update', inputMethod, actionURL);

        //populate all the report select form fields
        populateAllSelectFields(data, reportTypes, heightUnits, weightUnits);

        // populate and show the current picture of missing person data
        $('.custom-file-label').html(data.picture_name);
        $("#currentPictureDiv").show(); // show the current picture div
        $('#imgCurrentPicture').prop('src', data.picture_src); //add the src attribute
        $("#imgCurrentPicture").prop("alt", data.name + ' picture'); //add the alt text

        // populate fields of missing person data
        $('#name').val(data.name);
        $('#unique_sign').val(data.unique_sign);
        $('#age').val(data.age);
        $('#height').val(data.height);
        $('#weight').val(data.weight);
        $('#eyes').val(data.eyes);
        $('#hair').val(data.hair);
        $('#contact_information').val(data.contact_information);
        $('#last_seen').val(data.last_seen);
        $('#important_information').val(data.important_information);
    })
}

//delete function
function deleteReport(id) {
    $('#confirmationDeleteModal').modal('show')
    $('#modalDeleteForm').attr('action', '/admin/missing-persons/' + id)
    $('#confirmationMessage').text('Do you really want to delete this missing person report data? This process cannot be undone. All of the comments related to this report will be deleted')
}

// add or replace the in the datatable
function addOrReplaceData(data, addOrReplace) {

    col0 = '<td>' + data.id + '</td>';
    col1 = '<td>' + data.submitted_by + '(#' + data.user_id + ')' + '</td>';
    let className = data.report_type == 'Missing' ? 'text-warning' : 'text-info';

    col2 = '<p class="' + className + '"><strong>' + data.report_type + '</strong></p>';

    col3 = '<td>' +
        '<a href="' + data.picture_link + '" target="_blank">' +
        '<img style="height:150px; max-height: 150px; max-width:150px; width: 150px;" src="' + data.picture_src + '" class="rounded" alt="' + data.name + ' image">' +
        '</a>' +
        '</td>';

    col4 = '<td>' + data.name + '</td>';
    col5 = '<td>' + data.age + ' yr old</td>';
    col6 = '<td>' + data.height + '' + data.height_unit + '</td>';
    col7 = '<td>' + data.weight + '' + data.weight_unit + '</td>';
    col8 = '<td>' + data.last_seen + '</td>';
    col9 = '<td>' + data.contact_information + ' yr old</td>';


    switch (data.status) {
        case 'Pending':
            className = 'bg-info';
            break;
        case 'Approved':
            className = 'bg-white';
            break;
        case 'Resolved':
            className = 'bg-success';
            break;
        case 'Denied':
            className = 'bg-danger';
            break;
    }

    col10 = '<td>' +
        '<div class="p-2 ' + className + ' text-white rounded-pill text-center">' +
        data.status +
        '</div>' +
        '</td>';

    col11 = '<td>' + data.created_at + '</td>';


    viewBtn =
        '<li class="list-inline-item mb-1">' +
        '<a class="btn btn-info btn-sm" type="button" data-toggle="tooltip" data-placement="top" title="View" href="' + window.location.origin + '/admin/missing-persons/' + data.id + '"><i class="fas fa-eye"></i>' +
        '</a></li>'

    editBtn =
        '<li class="list-inline-item mb-1">' +
        '<button class="btn btn-primary btn-sm" onclick="editReport(' + data.id + ')" type="button" data-toggle="tooltip" data-placement="top" title="Edit">' +
        '<i class="fas fa-edit"></i>' +
        '</button>' +
        '</li>'

    deleteBtn =
        '<li class="list-inline-item mb-1">' +
        '<button class="btn btn-danger btn-sm" onclick="editReport(' + data.id + ')" type="button" data-toggle="tooltip" data-placement="top" title="Delete">' +
        '<i class="fas fa-trash-alt"></i>' +
        '</button>' +
        '</li>'

    col12 = '<td><ul class="list-inline m-0">' + viewBtn + editBtn + deleteBtn + '</td></ul>'

    // Get table reference - note: dataTable() not DataTable()
    var table = $('#dataTable').DataTable();
    if (addOrReplace == 'Replace') {
        // if replacing the table row
        table.row('.selected').data([col0, col1, col2, col3, col4, col5, col6, col7, col8, col9, col10, col11, col12]).draw(false);
    } else {
        // if adding new table row
        var currentPage = table.page();
        table.row.add([col0, col1, col2, col3, col4, col5, col6, col7, col8, col9, col10, col11, col12]).draw()

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
    }

}


$(document).ready(function () {

    $('#missingPerson').addClass('active')

    // Set class row selected when any button was click in the selected
    $('#dataTable').on('click', 'tr', function () {
        if (!$(this).hasClass('selected')) {
            $('#dataTable').DataTable().$('tr.selected').removeClass('selected')
            $(this).addClass('selected')

            var currentRow = $(this).closest("tr");

            //set value of global variables
            currentRowCreatedAt = currentRow.find("td:eq(11)").text();
            currentRowStatus = currentRow.find("td:eq(10)").text().trim();
        }
    })

    // Initialize Year picker in report form
    $(".datepicker").datepicker({
        format: "yyyy-mm-dd",
    });

    $("form[name='reportForm']").validate({
        // Specify validation rules
        rules: {
            name: {
                required: true,
                minlength: 3,
                maxlength: 100,
            },
            height: {
                required: true,
                number: true,
                min: 1,
                max: 200,
            },
            height_unit: {
                required: true,
            },
            weight: {
                required: true,
                number: true,
                min: 1,
                max: 500,
            },
            weight_unit: {
                required: true,
            },
            age: {
                required: true,
                number: true,
                min: 1,
                max: 200,
            },
            eyes: {
                minlength: 3,
                maxlength: 50,
            },
            hair: {
                minlength: 3,
                maxlength: 50,
            },
            unique_sign: {
                required: true,
                minlength: 3,
                maxlength: 250,
            },
            unique_sign: {
                required: true,
                minlength: 3,
                maxlength: 250,
            },
            important_information: {
                required: true,
                minlength: 3,
                maxlength: 250,
            },
            last_seen: {
                required: true,
                minlength: 3,
                maxlength: 60,
            },
            contact_information: {
                required: true,
                minlength: 3,
                maxlength: 250,
            },
            report_type: {
                required: true,
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
            picture: {
                extension: "Invalid file type! picture must be in jpeg|jpg|png format",
                filesize: "Selected file must be less than 10mb"
            },
        },

        submitHandler: function (form, event) {
            event.preventDefault()

            let formAction = $("#reportForm").attr('action')
            let formMethod = $('#method').val()
            let formData = new FormData(form)

            $('.btnFormSubmit').attr("disabled", true); //disabled login
            $('.btnFormTxt').text(formMethod == 'POST' ? 'Storing' : 'Updating') //set the text of the submit btn
            $('.btnFormLoadingIcon').prop("hidden", false) //show the fa loading icon from submit btn

            doAjax(formAction, 'POST', formData).then((response) => {
                if (response.success) {
                    const data = response.data
                    $('#reportFormModal').modal('hide') //hide the modal

                    addOrReplaceData(data, $('#method').val() == 'POST' ? 'Add' : 'Replace')

                    if ($('#method').val() == 'POST') {
                        // add to pending card if it is new report
                        $("#thisMonthCount").text(parseInt($("#thisMonthCount").text()) + 1);
                        $("#thisMonthPendingCount").text(parseInt($("#thisMonthPendingCount").text()) + 1);
                        $("#reportsCount").text(parseInt($("#reportsCount").text()) + 1);
                    }
                }

                $('.btnFormSubmit').attr("disabled", false); //enable the button
                $('.btnFormTxt').text('') //set the text of the submit btn
                $('.btnFormLoadingIcon').prop("hidden", true) //hide the fa loading icon from submit btn
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

                // decrement total report count
                $("#reportsCount").text(parseInt($("#reportsCount").text()) - 1);

                let reportDate = new Date(currentRowCreatedAt);
                let currentDate = new Date();

                if (reportDate.getFullYear() == currentDate.getFullYear() && currentDate.getMonth() == reportDate.getMonth()) {
                    //means the date is in this current month

                    // decrement this month total report count
                    $("#thisMonthCount").text(parseInt($("#thisMonthCount").text()) - 1);

                    switch (currentRowStatus) {
                        case 'Pending':
                            $("#thisMonthPendingCount").text(parseInt($("#thisMonthPendingCount").text()) - 1);
                            break;
                        case 'Approved':
                            $("#thisMonthApprovedCount").text(parseInt($("#thisMonthApprovedCount").text()) - 1);
                            break;
                        case 'Resolved':
                            $("#thisMonthResolvedCount").text(parseInt($("#thisMonthResolvedCount").text()) - 1);
                            break;
                        case 'Denied':
                            $("#thisMonthDeniedCount").text(parseInt($("#thisMonthDeniedCount").text()) - 1);
                            break;
                    }
                }
            }

            $('#btnDelete').attr("disabled", false); //enable button
            $('.btnDeleteTxt').text('Delete') //set the text of the delete btn
            $('.btnDeleteLoadingIcon').prop("hidden", true) //hide the fa loading icon from submit btn
        })

        $('#confirmationDeleteModal').modal('hide') //hide
        $('#modalDeleteForm').trigger("reset"); //reset all the values
    })

})
