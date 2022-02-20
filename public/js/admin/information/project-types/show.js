function createProject() {
    const url = window.location.origin + '/admin/projects/create'

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
            const types = response.types;
            $('.custom-file-label').html(''); //empty the html in the file input
            $('#projectForm').trigger("reset") //reset all the input values
            $('#projectTypeDropDwn').empty() //reset dropdown button
            $("#formMethod").empty(); //empty form method div

            //since it is from the docu profile, the docutype would be fixed depending kung anong page yun
            const currentTypeID = window.location.href.match(/project-types\/(\d+)/)

            $.each(types, function () {
                // Populate Drop Dowm
                if (currentTypeID[1] == this.id) {
                    $('#projectTypeDropDwn').append($("<option />").val(this.id).text(this.name))
                }
            })
            let actionURL = '/admin/projects/'
            let inputMethod = '<input type="hidden" id="method" name="_method" value="POST">'

            $("#formMethod").append(inputMethod) // append formMethod div
            $('#projectForm').attr('action', actionURL) //set the method of the form
            $('#projectModal').modal('show')
            $('#projectModalHeader').text('Publish Project')
            $('.btnTxt').text('Store') //set the text of the submit btn
        },
        error: function (xhr) {
            var error = JSON.parse(xhr.responseText);

            // show error message from helper.js
            ajaxErrorMessage(error);
        }
    });
}

function editProject(id) {
    const url = window.location.origin + '/admin/projects/' + id + '/edit';

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
            const data = response.data
            const types = response.types

            $('.custom-file-label').html(''); //empty the html in the file input
            $('#projectForm').trigger("reset") //reset all the input values
            $('#projectTypeDropDwn').empty() //reset dropdown button
            $("#formMethod").empty(); //empty form method div

            //Set form input values
            $('#projectTypeDropDwn').append($("<option selected/>").val(data.type_id).text(data.type_id == 0 ? data.custom_type : data.project_type)) //set current type_id
            $.each(types, function () {
                // Populate Drop Dowm
                if (this.id != data.type_id) {
                    $('#projectTypeDropDwn').append($("<option />").val(this.id).text(this.name))
                }
            })

            $('#name').val(data.name)
            $('#description').val(data.description)
            $('#cost').val(data.cost)
            $('#project_start').val(data.project_start)
            $('#project_end').val(data.project_end)
            $('#location').val(data.location)
            $('.custom-file-label').html(data.pdf_name)

            let actionURL = '/admin/projects/' + data.id
            let inputMethod = '<input type="hidden" id="method" name="_method" value="PUT">'

            $("#formMethod").append(inputMethod) // append formMethod div
            $('#projectForm').attr('action', actionURL) //set the method of the form
            $('#projectModal').modal('show')
            $('#projectModalHeader').text('Edit Project')
            $('.btnTxt').text('Update') //set the text of the submit btn
        },
        error: function (xhr) {
            var error = JSON.parse(xhr.responseText);

            // show error message from helper.js
            ajaxErrorMessage(error);
        }
    });
}

function deleteProject(id) {
    $('#confirmationDeleteModal').modal('show')
    $('#modalDeleteForm').attr('action', '/admin/projects/' + id)
    $('#confirmationMessage').text('Do you really want to delete this project? This process cannot be undone.')
}

$(document).ready(function () {
    $('#TypeNavCollapse').addClass('active')
    $('#collapseType').collapse()
    $('#projectType').addClass('active')


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
    $(".datepicker").datepicker({
        format: "yyyy-mm-dd", // Notice the Extra space at the beginning
    });

    // Validation
    $("form[name='projectForm']").validate({
        // Specify validation rules
        rules: {
            type_id: {
                required: true,
                minStrict: 0,
                number: true
            },
            name: {
                required: true,
                minlength: 6,
                maxlength: 150,
            },
            description: {
                required: true,
                minlength: 6,
                maxlength: 250,
            },
            cost: {
                required: true,
                min: 0,
                max: 999999999999,
                number: true
            },
            project_start: {
                required: true,
            },
            project_end: {
                required: true,
                greaterThan: "#project_start"
            },
            location: {
                required: true,
                minlength: 4,
                maxlength: 250,
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
                required: 'Please select one of the project types',
                digits: 'Invalid Input',
                minStrict: 'Please select one of the project types',
            },
            pdf: {
                required: 'Please select one of the project',
                extension: "Invalid file type! project must be in .pdf format",
                filesize: "Selected file must be less than 10mb"
            },
        },

        submitHandler: function (form, event) {
            event.preventDefault()

            let formAction = $("#projectForm").attr('action')
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
                    $('#projectModal').modal('hide') //hide the modal

                    const data = response.data

                    col0 = '<td>' + data.id + '</td>'
                    col1 = '<td>' + data.name + '</td>'
                    col2 = '<td>' + data.description + '</td>'
                    var formatter = new Intl.NumberFormat('en-US', {
                        style: 'currency',
                        currency: 'PHP',

                        // These options are needed to round to whole numbers if that's what you want.
                        //minimumFractionDigits: 0, // (this suffices for whole numbers, but will print 2500.10 as $2,500.1)
                        //maximumFractionDigits: 0, // (causes 2500.99 to be printed as $2,501)
                    });

                    col3 = '<td>' + formatter.format(data.cost) + '</td>'
                    col4 = '<td>' + data.project_start + '</td>'
                    col5 = '<td>' + data.project_end + '</td>'
                    col6 = '<td>' + data.location + '</td>'
                    col7 = '<td><a href="' + data.pdf_src + '"  target="_blank">' + data.pdf_name + '</a></td>'
                    col8 = '<td>' + data.updated_at + '</td>'

                    editBtn =
                        '<li class="list-inline-item mb-1">' +
                        '<button class="btn btn-primary btn-sm" onclick="editProject(' + data.id + ')" type="button" data-toggle="tooltip" data-placement="top" title="Edit">' +
                        '<i class="fas fa-edit"></i>' +
                        '</button>' +
                        '</li>'
                    deleteBtn =
                        '<li class="list-inline-item mb-1">' +
                        '<button class="btn btn-danger btn-sm" onclick="deleteProject(' + data.id + ')" type="button" data-toggle="tooltip" data-placement="top" title="Delete">' +
                        '<i class="fas fa-trash-alt"></i>' +
                        '</button>' +
                        '</li>'

                    col9 = '<td><ul class="list-inline m-0">' + editBtn + deleteBtn + '</td></ul>'

                    // Get table reference - note: dataTable() not DataTable()
                    var table = $('#dataTable').DataTable();

                    if (formMethod == 'POST') {
                        var currentPage = table.page();
                        table.row.add([col0, col1, col2, col3, col4, col5, col6, col7, col8, col9]).draw()

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

                        $("#projectsCount").text(parseInt($("#projectsCount").text()) + 1);

                    } else {

                        // get the current href parameter
                        const currentTypeID = window.location.href.match(/project-types\/(\d+)/)

                        // check if the data is equal to the current href parameter
                        if (parseInt(currentTypeID[1]) != parseInt(data.type_id)) {
                            var table = $('#dataTable').DataTable();
                            $('.selected').fadeOut(800, function () {
                                table.row('.selected').remove().draw();
                            });

                            // decrement projectsCount
                            $("#projectsCount").text(parseInt($("#projectsCount").text()) - 1);
                        } else {
                            table.row('.selected').data([col0, col1, col2, col3, col4, col5, col6, col7, col8, col9]).draw(false);
                        }
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
            let type_id = $('#type_id').val();

            var url = `${window.location.origin}/admin/project-types/report/${date_start}/${date_end}/${sort_column}/${sort_option}/${type_id}`;
            window.open(url, '_blank');
        
            // let formAction = $("#reportForm").attr('action')
            // let formData = new FormData(form)

            // $('.btnReportFormSubmit').attr("disabled", true); //disabled login
            // $('.btnReportFormTxt').text('Generating') //set the text of the submit btn
            // $('.btnReportFormLoadingIcon').prop("hidden", false) //show the fa loading icon from submit btn

            // $.ajax({
            //     type: 'POST',
            //     url: formAction,
            //     headers: {
            //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            //     },
            //     data: formData,
            //     xhrFields: {
            //         responseType: 'blob'
            //     },
            //     cache: false,
            //     processData: false,
            //     contentType: false,

            //     success: function (response) {
            //         toastr.success('Report successfully downloaded')
            //         var blob = new Blob([response]);
            //         var link = document.createElement('a');
            //         link.href = window.URL.createObjectURL(blob);
            //         link.download = "ProjectTypeProfileReport-" + Date.now() + ".pdf";
            //         link.click();
            //     },
            //     error: function (response) {
            //         toastr.error('Something went wrong :( (It could be the selected time range produces no data)')
            //     },
            //     complete: function () {
            //         $('.btnReportFormSubmit').attr("disabled", false); //enable the button
            //         $('.btnReportFormTxt').text('Generate') //set the text of the submit btn
            //         $('.btnReportFormLoadingIcon').prop("hidden", true) //hide the fa loading icon from submit btn
            //     }
            // });
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

                // decrement ordinancesCount
                $("#projectsCount").text(parseInt($("#projectsCount").text()) - 1);
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


